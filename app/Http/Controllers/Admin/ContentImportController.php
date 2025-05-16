<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Laravel 7+ için Guzzle'ı daha kolay kullanmamızı sağlar
use Symfony\Component\DomCrawler\Crawler; // Ekledik
use Illuminate\Support\Facades\Validator; // Ekledik
use Illuminate\Support\Facades\Log;
use App\Models\Items;
use App\Models\Genres;
use App\Models\Persons;
use App\Models\Episodes; // Bölüm kaydı için ileride gerekecek
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Eklendi
use Illuminate\Support\Facades\File; // Eklendi

class ContentImportController extends Controller
{
    /**
     * Display a form to import content.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.content_importer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_url' => 'required|url',
            'content_type' => 'required|in:movie,series',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.content_importer.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $importUrl = $request->input('import_url');
        $contentType = $request->input('content_type');
        $seriesData = []; // Çekilecek tüm dizi bilgilerini burada toplayacağız
        $feedbackMessages = []; // Görsel indirme geri bildirimleri için eklendi

        try {
            $response = Http::timeout(30)->get($importUrl);

            if ($response->successful()) {
                $htmlContent = $response->body();
                $contentTypeHeader = $response->header('Content-Type');
                Log::info("İstek URL: {$importUrl} - Gelen Content-Type başlığı: " . $contentTypeHeader);

                // Karakter kodlamasını UTF-8 yapmaya çalış
                $initialEncoding = mb_detect_encoding($htmlContent, ['UTF-8', 'ISO-8859-1', 'ISO-8859-9', 'WINDOWS-1254'], true);
                Log::info("İlk tespit edilen kodlama: " . ($initialEncoding ?: 'Tespit edilemedi'));

                if ($initialEncoding && strtoupper($initialEncoding) !== 'UTF-8') {
                    $htmlContentTry = mb_convert_encoding($htmlContent, 'UTF-8', $initialEncoding);
                    if ($htmlContentTry !== false && !empty($htmlContentTry)) {
                        $htmlContent = $htmlContentTry;
                        Log::info("{$initialEncoding} -> UTF-8 çevrimi yapıldı.");
                    } else {
                        Log::warning("{$initialEncoding} -> UTF-8 çevrimi başarısız oldu veya boş sonuç verdi.");
                    }
                } elseif (!$initialEncoding && !mb_check_encoding($htmlContent, 'UTF-8')) {
                     // Tespit edilemediyse ve UTF-8 değilse, olası bir kaynak kodlamadan (örn: ISO-8859-9) çevirmeyi dene
                    Log::warning("Kodlama tespit edilemedi ve geçerli UTF-8 değil. ISO-8859-9'dan çevirme denenecek.");
                    $htmlContentTry = mb_convert_encoding($htmlContent, 'UTF-8', 'ISO-8859-9');
                     if ($htmlContentTry !== false && !empty($htmlContentTry)) {
                        $htmlContent = $htmlContentTry;
                        Log::info("ISO-8859-9 -> UTF-8 çevrimi (fallback) yapıldı.");
                    } else {
                        Log::warning("ISO-8859-9 -> UTF-8 çevrimi (fallback) başarısız oldu veya boş sonuç verdi.");
                    }
                }

                $crawler = new Crawler($htmlContent);

                // 1. JSON-LD Verisini Çekme (varsa)
                $jsonData = null;
                if ($crawler->filter('script[type="application/ld+json"]')->count() > 0) {
                    try {
                        $jsonText = $crawler->filter('script[type="application/ld+json"]')->first()->text();
                        $jsonData = json_decode(trim($jsonText), true);
                    } catch (\Exception $e) {
                        Log::warning("JSON-LD parse edilemedi. URL: " . $importUrl . " Hata: " . $e->getMessage());
                        $jsonData = null;
                    }
                }

                // 2. Dizi/Film Temel Bilgilerini Çekme
                $titleFromSource = $jsonData['name'] ?? $this->getNodeText($crawler, 'div.page-title h1 a');
                $seriesData['title'] = !is_null($titleFromSource) && $titleFromSource !== '' ? html_entity_decode($titleFromSource, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;
                $seriesData['original_title'] = $this->getNodeText($crawler, 'div.page-title p');
                $seriesData['year'] = isset($jsonData['datePublished']) ? substr($jsonData['datePublished'], 0, 4) : null;
                if (!$seriesData['year']) {
                    $yearText = $this->getNodeText($crawler, 'div.page-title h1 span.inline-block');
                    if (preg_match('/\((\d{4})\)/', $yearText, $matches)) {
                        $seriesData['year'] = $matches[1];
                    }
                }

                $descriptionFromSource = $jsonData['description'] ?? $this->getNodeText($crawler, 'div.series-profile-summary p');
                $seriesData['description'] = !is_null($descriptionFromSource) && $descriptionFromSource !== '' ? html_entity_decode($descriptionFromSource, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;
                
                // 1. Poster URL'si: Öncelik div.series-profile-image img
                $seriesData['poster_url'] = $this->getNodeAttribute($crawler, 'div.series-profile-image img', 'src');

                // 2. Eğer div ile bulunamazsa, JSON-LD image veya og:image dene
                if (empty($seriesData['poster_url'])) {
                    $seriesData['poster_url'] = $jsonData['image'] ?? $this->getNodeAttribute($crawler, 'meta[property="og:image"]', 'content');
                }

                // 3. Backdrop URL'si: og:image:secure_url (veya og:image eğer secure_url yoksa)
                // Kaynak HTML'de belirgin ayrı bir backdrop görseli yoksa, genellikle posterle aynı olabilir.
                $seriesData['backdrop_url'] = $this->getNodeAttribute($crawler, 'meta[property="og:image:secure_url"]', 'content');
                if (empty($seriesData['backdrop_url'])) {
                    $seriesData['backdrop_url'] = $this->getNodeAttribute($crawler, 'meta[property="og:image"]', 'content');
                }

                // Poster URL'sini tam URL yap (gerekirse)
                if ($seriesData['poster_url'] && !filter_var($seriesData['poster_url'], FILTER_VALIDATE_URL)) {
                    $parsedImportUrl = parse_url($importUrl);
                    $base = ($parsedImportUrl['scheme'] ?? 'https') . '://' . ($parsedImportUrl['host'] ?? '');
                    $seriesData['poster_url'] = $base . (strpos($seriesData['poster_url'], '/') === 0 ? '' : '/') . ltrim($seriesData['poster_url'], '/');
                }

                // Backdrop URL'sini tam URL yap (gerekirse)
                if (isset($seriesData['backdrop_url']) && $seriesData['backdrop_url'] && !filter_var($seriesData['backdrop_url'], FILTER_VALIDATE_URL)) {
                    $parsedImportUrl = parse_url($importUrl);
                    $base = ($parsedImportUrl['scheme'] ?? 'https') . '://' . ($parsedImportUrl['host'] ?? '');
                    $seriesData['backdrop_url'] = $base . (strpos($seriesData['backdrop_url'], '/') === 0 ? '' : '/') . ltrim($seriesData['backdrop_url'], '/');
                }

                // Poster ve backdrop aynıysa logla (ve isteğe bağlı olarak backdrop'u null yap)
                if (!empty($seriesData['poster_url']) && !empty($seriesData['backdrop_url']) && $seriesData['poster_url'] === $seriesData['backdrop_url']) {
                    Log::info("Poster ve Backdrop URL'leri aynı çıktı: " . $seriesData['poster_url'] . ". Bu durumda backdrop için farklı bir kaynak bulunmuyorsa, bu normaldir veya backdrop için varsayılan kullanılabilir.");
                    // İsteğe bağlı: Eğer poster ve backdrop aynıysa ve farklı olmaları gerekiyorsa backdrop'u null yapıp varsayılana düşür.
                    // $seriesData['backdrop_url'] = null; 
                }

                // Görselleri İndirme ve Kaydetme
                $seriesData['poster_path'] = 'default_poster.jpg'; // Varsayılan
                if (!empty($seriesData['poster_url'])) {
                    try {
                        $imageContents = Http::timeout(20)->get($seriesData['poster_url'])->body();
                        if ($imageContents) {
                            $originalExtension = pathinfo(parse_url($seriesData['poster_url'], PHP_URL_PATH), PATHINFO_EXTENSION);
                            $extension = !empty($originalExtension) ? strtolower($originalExtension) : 'jpg'; // webp veya jpg gibi
                            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { // Güvenlik için bilinen uzantılar
                                $extension = 'jpg';
                            }
                            $filename = Str::random(40) . '.' . $extension;
                            $posterDirectory = 'assets/series/poster/';
                            $posterDirFullPath = public_path($posterDirectory);

                            if (!File::isDirectory($posterDirFullPath)) {
                                File::makeDirectory($posterDirFullPath, 0755, true, true);
                            }
                            
                            $dbPath = $posterDirectory . $filename; // DB'ye kaydedilecek yol: "assets/series/poster/xxxx.webp"
                            $fullFilePath = public_path($dbPath);

                            File::put($fullFilePath, $imageContents);
                            $seriesData['poster_path'] = $filename; // Sadece dosya adı kaydedilecek
                            Log::info("Poster indirildi ve kaydedildi: " . $dbPath);
                            $feedbackMessages[] = ['type' => 'success', 'text' => 'Poster başarıyla indirildi: ' . $dbPath . ' (Veritabanına kaydedilen ad: ' . $filename . ')'];
                        } else {
                            Log::warning("Poster içeriği boş geldi. URL: " . $seriesData['poster_url']);
                            $feedbackMessages[] = ['type' => 'warning', 'text' => 'Poster içeriği boş geldi, indirilemedi. URL: ' . $seriesData['poster_url']];
                        }
                    } catch (\Exception $e) {
                        Log::error("Poster indirme hatası: " . $e->getMessage() . " URL: " . $seriesData['poster_url']);
                        $feedbackMessages[] = ['type' => 'error', 'text' => 'Poster indirme hatası: ' . $e->getMessage() . ' URL: ' . $seriesData['poster_url']];
                    }
                } else {
                    $feedbackMessages[] = ['type' => 'info', 'text' => 'Poster URL\'si kaynakta bulunamadı, varsayılan poster kullanılacak.'];
                }

                $seriesData['backdrop_path'] = 'default_backdrop.jpg'; // Varsayılan
                if (!empty($seriesData['backdrop_url'])) {
                    try {
                        $imageContents = Http::timeout(20)->get($seriesData['backdrop_url'])->body();
                        if ($imageContents) {
                            $originalExtension = pathinfo(parse_url($seriesData['backdrop_url'], PHP_URL_PATH), PATHINFO_EXTENSION);
                            $extension = !empty($originalExtension) ? strtolower($originalExtension) : 'jpg';
                            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                $extension = 'jpg';
                            }
                            $filename = Str::random(40) . '.' . $extension;
                            $backdropDirectory = 'assets/series/backdrop/';
                            $backdropDirFullPath = public_path($backdropDirectory);

                            if (!File::isDirectory($backdropDirFullPath)) {
                                File::makeDirectory($backdropDirFullPath, 0755, true, true);
                            }

                            $dbPath = $backdropDirectory . $filename; // DB'ye kaydedilecek yol: "assets/series/backdrop/xxxx.webp"
                            $fullFilePath = public_path($dbPath);
                            
                            File::put($fullFilePath, $imageContents);
                            $seriesData['backdrop_path'] = $filename; // Sadece dosya adı kaydedilecek
                            Log::info("Backdrop indirildi ve kaydedildi: " . $dbPath);
                            $feedbackMessages[] = ['type' => 'success', 'text' => 'Backdrop başarıyla indirildi: ' . $dbPath . ' (Veritabanına kaydedilen ad: ' . $filename . ')'];
                        } else {
                            Log::warning("Backdrop içeriği boş geldi. URL: " . $seriesData['backdrop_url']);
                            $feedbackMessages[] = ['type' => 'warning', 'text' => 'Backdrop içeriği boş geldi, indirilemedi. URL: ' . $seriesData['backdrop_url']];
                        }
                    } catch (\Exception $e) {
                        Log::error("Backdrop indirme hatası: " . $e->getMessage() . " URL: " . $seriesData['backdrop_url']);
                        $feedbackMessages[] = ['type' => 'error', 'text' => 'Backdrop indirme hatası: ' . $e->getMessage() . ' URL: ' . $seriesData['backdrop_url']];
                    }
                } else {
                    $feedbackMessages[] = ['type' => 'info', 'text' => 'Backdrop URL\'si kaynakta bulunamadı, varsayılan backdrop kullanılacak.'];
                }

                $seriesData['imdb_rating'] = $jsonData['aggregateRating']['ratingValue'] ?? null;
                if (!$seriesData['imdb_rating']) {
                    $imdbNode = $crawler->filter('div.series-profile-info ul li:contains("IMDB Puanı") p span.color-imdb');
                    if ($imdbNode->count() > 0) {
                        $seriesData['imdb_rating'] = trim($imdbNode->text());
                    }
                }

                $seriesData['duration'] = $jsonData['timeRequired'] ?? null;
                if (!$seriesData['duration']) {
                     $durationNode = $crawler->filter('div.series-profile-info ul li:contains("Süre") p');
                     if ($durationNode->count() > 0) {
                         $seriesData['duration'] = trim(str_replace('dk', '', $durationNode->text()));
                     }
                }

                $seriesData['country'] = $jsonData['countryOfOrigin']['name'] ?? null;
                 if (!$seriesData['country']) {
                    $countryNode = $crawler->filter('div.series-profile-info ul li:contains("Ülke") p');
                    if ($countryNode->count() > 0) {
                        $seriesData['country'] = trim($countryNode->text());
                    }
                }

                $seriesData['genres'] = [];
                $crawler->filter('div.series-profile-type span a')->each(function (Crawler $node) use (&$seriesData) {
                    $seriesData['genres'][] = trim($node->text());
                });
                if (empty($seriesData['genres']) && isset($jsonData['genre'])) {
                     $seriesData['genres'] = is_array($jsonData['genre']) ? $jsonData['genre'] : [$jsonData['genre']];
                }

                $seriesData['actors'] = [];
                if (isset($jsonData['actor']) && is_array($jsonData['actor'])) {
                    foreach($jsonData['actor'] as $actorJson) {
                        $seriesData['actors'][] = [
                            'name' => $actorJson['name'] ?? null,
                            'character_name' => $actorJson['additionalName'] ?? null,
                        ];
                    }
                } else {
                    $crawler->filter('div.series-profile-cast ul li a')->each(function (Crawler $node) use (&$seriesData, $importUrl) {
                        $actorName = $this->getNodeText($node, 'h5.truncate');
                        $characterName = $this->getNodeText($node, 'p.truncate');
                        $actorImage = $this->getNodeAttribute($node, 'img.lazy', 'data-src');
                        if ($actorImage && !filter_var($actorImage, FILTER_VALIDATE_URL)) {
                             $parsedImportUrl = parse_url($importUrl);
                             $base = ($parsedImportUrl['scheme'] ?? 'https') . '://' . ($parsedImportUrl['host'] ?? '');
                             $actorImage = $base . (strpos($actorImage, '/') === 0 ? '' : '/') . ltrim($actorImage, '/');
                        }
                        if ($actorName) {
                            $seriesData['actors'][] = [
                                'name' => $actorName,
                                'character_name' => $characterName,
                                'image_url' => $actorImage
                            ];
                        }
                    });
                }

                // 3. Sezon ve Bölüm Bilgilerini Çekme (URL'ler ile birlikte)
                if ($contentType === 'series') {
                    $seriesData['seasons'] = [];
                    Log::info("Dizi için sezonları çekmeye başlıyorum. URL: " . $importUrl);

                    $crawler->filter('div.series-profile-episodes-nav ul li[data-season]')->each(function (Crawler $seasonTabNode, $i) use (&$seriesData, $crawler, $importUrl) {
                        $seasonNameFromTab = $this->getNodeText($seasonTabNode, 'a');
                        $seasonIdSlug = $seasonTabNode->attr('data-season');
                        Log::info("Bulunan Sezon Tab: İsim='{$seasonNameFromTab}', ID Slug='{$seasonIdSlug}'");

                        if (empty($seasonIdSlug)) {
                            Log::warning("Boş seasonIdSlug bulundu. Sezon Tab HTML: " . $seasonTabNode->outerHtml());
                            return; 
                        }

                        $seasonNumberNode = $seasonTabNode->filter('a data[itemprop="name"]');
                        $seasonNumber = $seasonNumberNode->count() > 0 ? trim($seasonNumberNode->text()) : null;

                        if (empty($seasonNumber)) {
                            $seasonNumber = $seasonTabNode->attr('data-num');
                            Log::info("itemprop ile sezon no bulunamadı, data-num denendi: " . $seasonNumber);
                        }
                        
                        if (empty($seasonNumber)) {
                            if (preg_match('/(?:Sezon|Season)\s*(\d+)/i', $seasonNameFromTab, $matches)) {
                                $seasonNumber = $matches[1];
                                Log::info("itemprop ve data-num ile sezon no bulunamadı, regex ile denendi: " . $seasonNumber);
                            }
                        }

                        if (empty($seasonNumber)) {
                            Log::warning("Sezon numarası alınamadı. Sezon Adı: '{$seasonNameFromTab}', ID Slug='{$seasonIdSlug}'");
                            return; 
                        }
                        
                        Log::info("İşlenecek Sezon: No='{$seasonNumber}', ID Slug='{$seasonIdSlug}'");

                        $currentSeasonData = [
                            'season_number' => $seasonNumber,
                            'episodes' => []
                        ];

                        $episodeAreaSelector = 'div.series-profile-episodes-area#'.$seasonIdSlug;
                        Log::info("Bölüm alanı seçicisi: " . $episodeAreaSelector);
                        
                        $episodeNodes = $crawler->filter($episodeAreaSelector.' div.series-profile-episode-list ul li');
                        Log::info("Sezon {$seasonNumber} için " . $episodeNodes->count() . " potansiyel bölüm elementi bulundu.");

                        $episodeNodes->each(function (Crawler $episodeNode, $epIndex) use (&$currentSeasonData, $importUrl, $seasonNumber) {
                            $episodeLinkNode = $episodeNode->filter('.series-profile-episode-list-left a.truncate')->first();
                            
                            $episodeUrl = null;
                            if($episodeLinkNode->count() > 0){
                                $episodeUrl = $this->getNodeAttribute($episodeLinkNode, null, 'href');
                            }
                            
                            if ($episodeUrl && !filter_var($episodeUrl, FILTER_VALIDATE_URL)) {
                                $parsedImportUrl = parse_url($importUrl);
                                $base = ($parsedImportUrl['scheme'] ?? 'https') . '://' . ($parsedImportUrl['host'] ?? '');
                                $episodeUrl = $base . (strpos($episodeUrl, '/') === 0 ? '' : '/') . ltrim($episodeUrl, '/');
                            }

                            $episodeNumberText = $episodeLinkNode->count() > 0 ? $this->getNodeText($episodeLinkNode, 'data') : null;
                            
                            $episodeTitle = null;
                            $episodeTitleNode = $episodeNode->filter('.series-profile-episode-list-left h6.truncate a.truncate')->first();
                            if($episodeTitleNode->count() > 0) {
                                $episodeTitle = $this->getNodeText($episodeTitleNode);
                            }

                            $episodeReleaseDate = $this->getNodeText($episodeNode, 'span.block'); 

                            if ($episodeUrl) {
                                Log::info("S{$seasonNumber} - Bölüm #{$epIndex} Eklenecek: Başlık='{$episodeTitle}', NoMetni='{$episodeNumberText}', URL='{$episodeUrl}'");
                                $currentSeasonData['episodes'][] = [
                                    'episode_number_text' => $episodeNumberText,
                                    'title' => $episodeTitle,
                                    'url' => $episodeUrl, 
                                    'release_date_text' => $episodeReleaseDate
                                ];
                            } else {
                                Log::warning("S{$seasonNumber} - Bölüm #{$epIndex} için URL bulunamadı. HTML: " . $episodeNode->filter('.series-profile-episode-list-left')->html());
                            }
                        });

                        if (!empty($currentSeasonData['episodes'])){
                           Log::info("Sezon {$seasonNumber} için " . count($currentSeasonData['episodes']) . " bölüm başarıyla eklendi.");
                           $seriesData['seasons'][] = $currentSeasonData;
                        } else {
                            Log::warning("Sezon {$seasonNumber} için bölüm çekilemedi. Bölüm alanı seçicisi: {$episodeAreaSelector}");
                        }
                    });

                    if (empty($seriesData['seasons'])) {
                        Log::warning("Hiç sezon ve bölüm bilgisi çekilemedi! URL: " . $importUrl);
                    } else {
                        Log::info(count($seriesData['seasons']) . " sezon bilgisi (en az bir bölüm içeren) başarıyla çekildi.");
                    }
                }
                
                // Debug: Log scraped data
                Log::info("Scraped Series Data for DB:", $seriesData);

                // VERİTABANI KAYIT İŞLEMLERİ
                $item = null;
                DB::beginTransaction();
                try {
                    // 1. Ana Item (Dizi/Film) Kaydı
                    // Benzersizlik için title ve year (veya varsa tmdb_id) kullanılabilir.
                    // Önce tmdb_id ile bulmaya çalışalım, yoksa title ve year ile.
                    $uniqueIdentifiers = [];
                    if (!empty($seriesData['tmdb_id'])) {
                        $uniqueIdentifiers['tmdb_id'] = $seriesData['tmdb_id'];
                    } elseif (!empty($seriesData['title'])) {
                        $uniqueIdentifiers['title'] = $seriesData['title'];
                        // Yıl bilgisi varsa, benzersizliği artırmak için eklenebilir ama release_date alanına tam tarih yazmak daha iyi.
                        // if (!empty($seriesData['year'])) {
                        //     $uniqueIdentifiers['release_date'] = $seriesData['year'] . '-01-01'; 
                        // }
                    } else {
                        throw new \Exception("Kaydedilecek başlık veya TMDB ID bulunamadı.");
                    }

                    $baseSlug = Str::slug($seriesData['title'] ?? 'untitled-content');
                    if(empty($baseSlug)) { // Başlık tamamen geçersiz karakterlerden oluşuyorsa diye ek bir kontrol
                        $baseSlug = 'untitled-'.time(); // Veya başka bir fallback
                    }

                    $itemDataToSave = [
                        'type' => $contentType, // 'movie' veya 'series'
                        'overviews' => $seriesData['description'] ?? null,
                        'poster' => $seriesData['poster_path'] ?? 'default_poster.jpg', 
                        'backdrop' => $seriesData['backdrop_path'] ?? 'default_backdrop.jpg', 
                        'duration' => isset($seriesData['duration']) ? (int)trim(str_replace('dk','',$seriesData['duration'])) : null,
                        'rating' => isset($seriesData['imdb_rating']) ? (float)$seriesData['imdb_rating'] : null,
                        'release_date' => !empty($seriesData['year']) ? $seriesData['year'] . '-01-01' : ($jsonData['datePublished'] ?? null), // Tam tarih varsa daha iyi
                        'tagline' => $seriesData['tagline'] ?? null,
                        'imdb_id' => $seriesData['imdb_id'] ?? null,
                        'tmdb_id' => $seriesData['tmdb_id'] ?? null, // Scrape edildiyse
                        'visible' => 1, 
                        'slug' => $baseSlug, // Geçici slug eklendi
                    ];
                    // Sadece title ile unique arama yapıyorsak ve tmdb_id update edilecekse:
                    if(isset($uniqueIdentifiers['title']) && !isset($uniqueIdentifiers['tmdb_id']) && isset($seriesData['tmdb_id'])){
                        $itemDataToSave['tmdb_id'] = $seriesData['tmdb_id'];
                    }
                    if(!isset($uniqueIdentifiers['title']) && isset($seriesData['title'])){
                         $itemDataToSave['title'] = $seriesData['title'];
                    }


                    $item = Items::updateOrCreate($uniqueIdentifiers, $itemDataToSave);

                    // Slug'ı ID ile birlikte daha benzersiz hale getir ve title değişmişse de güncelle
                    $newSlug = Str::slug(($item->title ?? 'item') . '-' . $item->id);
                    if ($item->slug !== $newSlug) {
                        $item->slug = $newSlug;
                        $item->save(); 
                    }

                    // 2. Türleri Kaydet ve İlişkilendir
                    if (!empty($seriesData['genres'])) {
                        $genreIds = [];
                        foreach ($seriesData['genres'] as $genreName) {
                            if (empty(trim($genreName))) continue;
                            $genre = Genres::firstOrCreate(
                                ['name' => trim($genreName)],
                                ['visible' => 1] // Varsayılan olarak görünür yap
                            );
                            $genreIds[] = $genre->id;
                        }
                        if (!empty($genreIds)) {
                            $item->genres()->syncWithoutDetaching($genreIds);
                        }
                    }

                    // 3. Oyuncuları Kaydet ve İlişkilendir
                    if (!empty($seriesData['actors'])) {
                        $actorIds = [];
                        foreach ($seriesData['actors'] as $actorData) {
                            if (empty(trim($actorData['name']))) continue;
                            $person = Persons::firstOrCreate(
                                ['name' => trim($actorData['name'])],
                                [
                                    'profile_path' => $actorData['image_url'] ?? 'uploads/cast/actoricon.jpg',
                                    'tmdb_id' => $actorData['tmdb_id'] ?? null,
                                    'gender' => $actorData['gender'] ?? null,
                                ]
                            );
                            $actorIds[] = $person->id;
                        }
                        if (!empty($actorIds)) {
                            $item->actors()->syncWithoutDetaching($actorIds);
                        }
                    }
                    
                    DB::commit();
                    $finalMessage = "İçerik başarıyla çekildi ve veritabanına kaydedildi/güncellendi: " . ($item->title ?? 'Bilinmeyen İçerik');

                    if ($contentType === 'series' && !empty($seriesData['seasons'])) {
                        session(['processed_item_id' => $item->id]);
                        session(['seasons_to_process' => $seriesData['seasons']]);
                        Log::info("Session'a atandı: item_id = " . $item->id . ", sezon sayısı = " . count($seriesData['seasons']));
                        $finalMessage .= ". Şimdi bu dizinin bölümlerini çekmeye hazırız.";
                    } else if ($contentType === 'series') {
                        Log::warning("Dizi olarak işaretlendi ancak $seriesData[seasons] boş veya dolu değil! Session'a bölüm bilgisi atlanıyor.");
                    }
                     return redirect()->route('admin.content_importer.create')->with('success', $finalMessage)->withInput($request->except('import_url'));

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Veritabanı kaydı sırasında hata: " . $e->getMessage() . " - Satır: " . $e->getLine() . " - Dosya: " . $e->getFile() . " - Data: " . json_encode($seriesData));
                    return redirect()->route('admin.content_importer.create')
                                    ->with('error', "Veritabanı kaydı sırasında bir hata oluştu: " . $e->getMessage())
                                    ->withInput();
                }

            } else {
                Log::error("URL çekilemedi. HTTP Hata Kodu: " . $response->status() . " - Denenen URL: " . $importUrl);
                return redirect()->route('admin.content_importer.create')
                                ->with('error', "URL çekilemedi. HTTP Hata Kodu: " . $response->status())
                                ->withInput();
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("İçerik aktarma bağlantı hatası: " . $e->getMessage() . " URL: " . $importUrl);
            return redirect()->route('admin.content_importer.create')
                            ->with('error', "Bağlantı hatası: " . $e->getMessage())
                            ->withInput();
        } catch (\Throwable $e) { // Catch Throwable for broader error catching including ParseError
            Log::error("İçerik aktarma sırasında genel hata: " . $e->getMessage() . " URL: " . $importUrl . " Satır: " . $e->getLine() . " Dosya: " . $e->getFile());
            return redirect()->route('admin.content_importer.create')
                            ->with('error', "Bir hata oluştu (scraping): " . $e->getMessage())
                            ->withInput();
        }
    }

    private function getNodeText(Crawler $crawlerNode = null, string $selector = null, $default = null)
    {
        if (!$crawlerNode) return $default;
        $node = $selector ? $crawlerNode->filter($selector) : $crawlerNode;
        if ($node->count() > 0) {
            $text = trim($node->first()->text(null, true));
            // HTML entity'lerini decode edelim, null veya boş string ise olduğu gibi bırakalım.
            return !is_null($text) && $text !== '' ? html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8') : $default;
        }
        return $default;
    }

    private function getNodeAttribute(Crawler $crawlerNode = null, string $selector = null, string $attribute, $default = null)
    {
        if (!$crawlerNode) return $default;
        $node = $selector ? $crawlerNode->filter($selector) : $crawlerNode;
        if ($node->count() > 0) {
            return trim($node->first()->attr($attribute));
        }
        return $default;
    }

    public function fetchAndStoreEpisodes(Request $request)
    {
        $itemId = session('processed_item_id');
        $seasonsToProcess = session('seasons_to_process');

        if (!$itemId || empty($seasonsToProcess)) {
            return redirect()->route('admin.content_importer.create')->with('error', 'İşlenecek bölüm bilgisi bulunamadı veya eksik.');
        }

        $item = Items::find($itemId);
        if (!$item) {
            return redirect()->route('admin.content_importer.create')->with('error', 'Bölümlerin ekleneceği ana içerik bulunamadı.');
        }

        Log::info("Bölüm işleme başlatıldı. Item ID: " . $itemId . ". Sezon Sayısı: " . count($seasonsToProcess));
        $totalEpisodesProcessed = 0;
        $allErrors = [];

        foreach ($seasonsToProcess as $seasonData) {
            $seasonNumberClean = filter_var($seasonData['season_number'], FILTER_SANITIZE_NUMBER_INT);
            if (empty($seasonNumberClean) && $seasonData['season_number'] === 'Fragman') { // Özel durum: Fragmanlar için
                $seasonNumberClean = 0; // Veya farklı bir belirteç
            } elseif (empty($seasonNumberClean)) {
                Log::warning("Geçersiz sezon numarası: " . $seasonData['season_number'] . " - Item ID: " . $itemId);
                continue; // Bu sezonu atla
            }


            foreach ($seasonData['episodes'] as $episodeLinkData) {
                try {
                    $episodeUrl = $episodeLinkData['url'];
                    if (empty($episodeUrl)) {
                        Log::warning("Boş bölüm URL'si. Sezon: {$seasonNumberClean}, Başlık: {$episodeLinkData['title']}");
                        continue;
                    }
                    
                    Log::info("Çekiliyor: Sezon {$seasonNumberClean}, Bölüm URL: {$episodeUrl}");
                    $episodePageResponse = Http::timeout(30)->get($episodeUrl);

                    if ($episodePageResponse->successful()) {
                        $episodeHtmlContent = $episodePageResponse->body();
                        $episodeCrawler = new Crawler($episodeHtmlContent);

                        $episodeTitle = $this->getNodeText($episodeCrawler, 'div.page-title h1 a');
                        
                        // episode_number_text için güvenli erişim
                        $episodeNumberTextFromLinkData = isset($episodeLinkData['episode_number_text']) ? $episodeLinkData['episode_number_text'] : null;

                        if (empty($episodeTitle)) {
                           // $episodeTitle için fallback, episodeNumberTextFromLinkData kullanılırken null kontrolü
                           $episodeTitle = $episodeLinkData['title'] ?? ('Bölüm ' . ($episodeNumberTextFromLinkData ?? '[No Bilinmiyor]'));
                        }
                        
                        // JSON-LD ile bölüm no ve sezon no çekme (daha güvenilir olabilir)
                        $episodeJsonData = null;
                        if ($episodeCrawler->filter('script[type="application/ld+json"]')->count() > 0) {
                            try {
                                $jsonText = $episodeCrawler->filter('script[type="application/ld+json"]')->first()->text();
                                $episodeJsonData = json_decode(trim($jsonText), true);
                            } catch (\Exception $e) {
                                $episodeJsonData = null;
                            }
                        }

                        $currentEpisodeIdForDB = $episodeJsonData['episodeNumber'] ?? filter_var($episodeNumberTextFromLinkData, FILTER_SANITIZE_NUMBER_INT);
                        $currentSeasonNumberForEpisode = $episodeJsonData['partOfSeason']['seasonNumber'] ?? $seasonNumberClean;

                        if (empty($currentEpisodeIdForDB)) {
                            // Eğer $episodeNumberTextFromLinkData null değilse (yani store metodundan bir metin gelmiş ama sayıya çevrilememişse)
                            // ve $episodeJsonData['episodeNumber'] da boşsa, bunu loglayabiliriz.
                            // Şimdilik sadece genel bir kontrol yapıyoruz.
                            Log::warning("Bölüm ID/Numarası (JSON-LD: " . ($episodeJsonData['episodeNumber'] ?? 'yok') . ", LinkDataText: " . ($episodeNumberTextFromLinkData ?? 'yok') . ") alınamadı. Sezon: {$currentSeasonNumberForEpisode}, URL: {$episodeUrl}");
                            continue; // Bu bölümü atla
                        }

                        // Video kaynaklarını (iframe src) çekme
                        $player_data_for_json = ['type' => [], 'name' => [], 'url' => []]; // Yeni yapı

                        $episodeCrawler->filter('div#tv-spoox2 iframe') // CSS SEÇİCİSİ GÜNCELLENDİ
                            ->each(function (Crawler $iframeNode, $i) use (&$player_data_for_json, $episodeUrl) { 
                                $src = $iframeNode->attr('src');

                                if (empty($src)) {
                                    Log::info("[ContentImporter] iframe found with no src attribute. Episode URL: {$episodeUrl}");
                                    return; // Skips this iteration of ->each()
                                }

                                $finalSrc = null; // This will hold the URL to be added

                                if (strpos($src, 'https://dizivar.top/stream/') === 0) {
                                    // It's a dizivar stream URL, attempt to decode
                                    $urlPath = parse_url($src, PHP_URL_PATH);
                                    if ($urlPath) {
                                        $pathSegments = explode('/', trim($urlPath, '/')); // e.g., ["stream", "BASE64STRING"]
                                        if (count($pathSegments) >= 2 && $pathSegments[0] === 'stream' && !empty($pathSegments[1])) {
                                            $base64Part = $pathSegments[1];
                                            $decodedAttempt = base64_decode($base64Part, true); // strict mode

                                            if ($decodedAttempt !== false && filter_var($decodedAttempt, FILTER_VALIDATE_URL)) {
                                                Log::info("[ContentImporter] Base64 stream URL decoded. Original: '{$src}', Decoded: '{$decodedAttempt}', Episode: {$episodeUrl}");
                                                $finalSrc = $decodedAttempt;
                                            } else {
                                                Log::warning("[ContentImporter] Failed to decode Base64 stream to a valid URL. Original: '{$src}', Attempt: '".($decodedAttempt ?: 'false')."', Episode: {$episodeUrl}. Skipping this source.");
                                                // $finalSrc remains null, so this source will be skipped
                                            }
                                        } else {
                                            Log::warning("[ContentImporter] Could not parse Base64 part from stream URL path. URL: '{$src}', Path Segments: " . implode(', ', $pathSegments) . ", Episode: {$episodeUrl}. Skipping.");
                                        }
                                    } else {
                                        Log::warning("[ContentImporter] Could not parse path from stream URL. URL: '{$src}', Episode: {$episodeUrl}. Skipping.");
                                    }
                                } else {
                                    // Not a dizivar stream URL, use src directly
                                    $finalSrc = $src;
                                }

                                if ($finalSrc) {
                                    // Ensure the src is a full URL (handles cases like //example.com/embed)
                                    if (strpos($finalSrc, '//') === 0) {
                                        $finalSrc = (request()->isSecure() ? 'https:' : 'http:') . $finalSrc;
                                    }

                                    // Add to list if it's a valid URL and not duplicate
                                    if (filter_var($finalSrc, FILTER_VALIDATE_URL)) {
                                        if (!in_array($finalSrc, $player_data_for_json['url'])) {
                                            $player_data_for_json['url'][] = $finalSrc;
                                            $player_data_for_json['type'][] = 'embeded';

                                            // Generate player name
                                            $playerName = 'Player'; // Default
                                            $urlHost = parse_url($finalSrc, PHP_URL_HOST);
                                            if ($urlHost) {
                                                $hostParts = explode('.', $urlHost);
                                                $mainDomainPartIndex = max(0, count($hostParts) - 2); // e.g. google from www.google.com
                                                $playerName = ucfirst($hostParts[$mainDomainPartIndex]);
                                            }
                                            
                                            // Ensure unique player name
                                            $tempPlayerName = $playerName;
                                            $nameCounter = 1;
                                            // Check against already added names for the current episode's players
                                            $existingNamesThisEpisode = array_filter($player_data_for_json['name']); // Get non-empty names
                                            while (in_array($tempPlayerName, $existingNamesThisEpisode)) {
                                                $nameCounter++;
                                                $tempPlayerName = $playerName . ' ' . $nameCounter;
                                            }
                                            $player_data_for_json['name'][] = $tempPlayerName;

                                            Log::info("[ContentImporter] Added player: Name='{$tempPlayerName}', URL='{$finalSrc}' for Episode: {$episodeUrl}");

                                        } else {
                                             Log::info("[ContentImporter] Duplicate player URL skipped: '{$finalSrc}', Episode: {$episodeUrl}");
                                        }
                                    } else {
                                        Log::warning("[ContentImporter] Processed src '{$finalSrc}' (Original from iframe: '{$src}') is not a valid URL after all checks. Episode: {$episodeUrl}. Skipping.");
                                    }
                                }
                                // If $finalSrc is null (due to failed decoding of dizivar stream or other issues), it's skipped.
                            });
                        
                        $player_json_to_save = null;
                        if (empty($player_data_for_json['url'])) { // Kaynak bulunup bulunmadığını kontrol et
                            Log::warning("Video kaynağı (iframe) bulunamadı. URL: {$episodeUrl} - Seçici: div#tv-spoox2 iframe");
                        } else {
                            $player_json_to_save = json_encode($player_data_for_json);
                        }
                        
                        $episodeReleaseDateText = $episodeLinkData['release_date_text'] ?? null;
                        $parsedReleaseDate = null;
                        if($episodeReleaseDateText){
                            try {
                                $parsedReleaseDate = \Carbon\Carbon::createFromFormat('d.m.Y', $episodeReleaseDateText)->format('Y-m-d');
                            } catch (\Exception $e) {
                                try {
                                   $parsedReleaseDate = \Carbon\Carbon::createFromFormat('d/m/Y', $episodeReleaseDateText)->format('Y-m-d');
                                } catch (\Exception $e){
                                    Log::info("Bölüm yayın tarihi parse edilemedi: " . $episodeReleaseDateText);
                                }
                            }
                        }

                        // episode_unique_id için geçici bir değer
                        // Bu alanın gerçekte neyi temsil ettiğini ve nasıl oluşturulması gerektiğini kontrol etmek iyi olur.
                        $episodeUniqueIdValue = intval("{$item->id}{$currentSeasonNumberForEpisode}{$currentEpisodeIdForDB}"); 

                        Episodes::updateOrCreate(
                            [
                                'series_id' => $item->id,
                                'season_id' => $currentSeasonNumberForEpisode, 
                                'episode_id' => $currentEpisodeIdForDB,       
                            ],
                            [
                                'name' => $episodeTitle,
                                'backdrop' => $item->backdrop,   // EKLENDİ: Ana dizinin backdrop'ını kullan
                                'air_date' => $parsedReleaseDate,
                                'player' => $player_json_to_save, // GÜNCELLENMİŞ VERİ KAYDEDİLECEK
                                'views' => 0, // NOT NULL olduğu için varsayılan değer atandı
                                'episode_unique_id' => $episodeUniqueIdValue, // NOT NULL olduğu için değer atandı
                            ]
                        );
                        $totalEpisodesProcessed++;
                        Log::info("Kaydedildi/Güncellendi: Sezon {$currentSeasonNumberForEpisode}, Bölüm {$currentEpisodeIdForDB} - {$episodeTitle}");

                    } else {
                        Log::error("Bölüm sayfası çekilemedi. URL: {$episodeUrl}, HTTP Status: " . $episodePageResponse->status());
                        $allErrors[] = "Bölüm URL {$episodeUrl} çekilemedi (Hata: {$episodePageResponse->status()}).";
                    }
                     usleep(500000); 

                } catch (\Exception $e) {
                    Log::error("Bölüm işleme hatası: " . $e->getMessage() . " - URL: " . ($episodeLinkData['url'] ?? 'Bilinmiyor') . " - Satır: " . $e->getLine());
                    $allErrors[] = "Bölüm işlenirken hata: " . $e->getMessage() . " (URL: " . ($episodeLinkData['url'] ?? 'Bilinmiyor') . ")";
                }
            }
        }

        session()->forget(['processed_item_id', 'seasons_to_process']);

        $message = "{$totalEpisodesProcessed} bölüm başarıyla işlendi ve kaydedildi/güncellendi.";
        if (!empty($allErrors)) {
            $message .= " Bazı hatalar oluştu: <br>" . implode("<br>", $allErrors);
            return redirect()->route('admin.content_importer.create')->with('warning', $message);
        }

        return redirect()->route('admin.content_importer.create')->with('success', $message);
    }
} 