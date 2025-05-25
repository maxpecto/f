<?php

use Illuminate\Support\Facades\Route;
use App\Models\Settings;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\PreRollVideoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/////////////////////////
// Maintenance //////////
/////////////////////////

Route::get('/maintenance', function () {
	$general = Settings::findOrFail('1');
    return view('frontend.maintenance', compact('general'));
})->name('maintenance');

//Auth
Auth::routes();

//Frontend
Route::group(['namespace' => 'App\Http\Controllers\Frontend','middleware' => 'App\Http\Middleware\CheckMaintenance::class'], function () {
    //Lanugage Change
    Route::get('lang/home', 'LangController@index');
    Route::get('lang/change', 'LangController@change')->name('changeLang');
    //Home
    Route::get('/', 'HomeController@index')->name('home');
    //User Profile
    Route::get('/@{username}', 'UserController@index')->name('user-profile');
    //Edit Profile
    Route::get('/edit-profile', 'UserController@edit_profile')->name('edit-profile');
    //Update Profile
    Route::put('/update-profile', 'UserController@update_profile')->name('update_profile');
    //Update Profile
    Route::get('/delete-profile/{id}', 'UserController@delete_profile')->name('delete_profile');

    //Movie
	Route::get('/movie/{id}', 'DetailMovieController@index')->name('single-movie');
    //Series
	Route::get('/series/{id}', 'DetailSeriesController@index')->name('single-series');
    //Episodes
	Route::get('/{id}/{series_slug}/season-{series_season}/episode-{series_episode}', 'DetailEpisodeController@index')->name('single-episode');
    //Person
    Route::get('/person/{id}', 'DetailPersonsController@index')->name('single-person');
    //Collections
    Route::get('/collection/{id}', 'DetailCollectionsController@index')->name('single-collection');
    //Pages
    Route::get('/page/{id}', 'DetailPagesController@index')->name('single-page');

    //Lisitng Movies
    Route::get('/movies', 'ListingsController@movies')->name('movies-lists');
    //Lisitng Series
    Route::get('/series', 'ListingsController@series')->name('series-lists');
    //Lisitng Episodes
    Route::get('/episodes', 'ListingsController@episodes')->name('episodes-lists');
    //Trendings
    Route::get('/trendings', 'ListingsController@trendings')->name('trendings-lists');
    //Recommendeds
    Route::get('/recommendeds', 'ListingsController@recommendeds')->name('recommendeds-lists');
    //Lisitng People
    Route::get('/persons', 'ListingsController@persons')->name('persons-lists');
    //Lisitng People
    Route::get('/collections', 'ListingsController@collections')->name('collections-lists');
    //Listing Watchlists
    Route::get('/watchlists', 'ListingsController@watchlists')->name('watchlists-lists');
    //Lisitng Alpa
    Route::get('/start-with/{id}', 'ListingsController@alpa')->name('start-with-lists');
    //Lisitng Genres
    Route::get('/genres/{id}', 'ListingsController@genres')->name('genres_lists');
    //Lisitng Years
    Route::get('/years/{id}', 'ListingsController@years')->name('years_lists');
    //Lisitng Countries
    Route::get('/countries/{id}', 'ListingsController@countries')->name('countries_lists');
    //Lisitng Countries
    Route::get('/qualities/{id}', 'ListingsController@qualities')->name('qualities_lists');
    //Lisitng Searching
    Route::get('/search', 'ListingsController@search')->name('search-lists');

    //Comments
    Route::post('/movie-comments', 'DetailMovieController@comments');
    Route::get('/movie-delete-comment/{id}', 'DetailMovieController@deletecomments');
    Route::post('/series-comments', 'DetailSeriesController@comments');
    Route::get('/series-delete-comment/{id}', 'DetailSeriesController@deletecomments');
    Route::post('/episodes-comments', 'DetailEpisodeController@comments');
    Route::get('/episodes-delete-comment/{id}', 'DetailMovieController@deletecomments');


    //Seasons Episodes
	Route::get('/get-season-episodes/series-{series_id}/season-{season_id}', 'DetailSeriesController@getSeasonsEpisodes');
	Route::get('/get-all-episodes/series-{series_id}', 'DetailSeriesController@getAllEpisodes');
    //Like Dislike
    Route::post('/episode/{episode}/like', 'HomeController@EpisodeLikeStore');
    Route::delete('/episode/{episode}/like', 'HomeController@EpisodeLikeDestroy');
    //Like Dislike
    Route::post('/items/{items}/like', 'HomeController@LikeStore');
    Route::delete('/items/{items}/like', 'HomeController@LikeDestroy');
    //Watchlists
    Route::post('/items/{items}/watchlist', 'HomeController@watchlistStore');

    // Platforma Ait İçerikler (Yeni Eklendi)
    Route::get('/platform/{platform_slug}', 'PlatformResultsController@show')->name('frontend.platform.items');

});

//Backend
Route::group(['prefix' => 'admin', 'middleware' => 'App\Http\Middleware\CheckAdmin::class', 'as' => 'admin.'], function () {
    Route::get('/', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard');
	Route::get('/chart', [App\Http\Controllers\Backend\DashboardController::class, 'chart'])->name('api.chart');

    //Data
    Route::get('/data/get_genres', [App\Http\Controllers\Backend\DataController::class, 'get_genres']);
    Route::get('/data/get_quality', [App\Http\Controllers\Backend\DataController::class, 'get_quality']);
    Route::get('/data/get_actors', [App\Http\Controllers\Backend\DataController::class, 'get_actors']);
    Route::get('/data/get_directing', [App\Http\Controllers\Backend\DataController::class, 'get_directing']);
    Route::get('/data/get_writing', [App\Http\Controllers\Backend\DataController::class, 'get_writing']);
    Route::get('/data/get_countries', [App\Http\Controllers\Backend\DataController::class, 'get_countries']);
    Route::get('/data/get_keywords', [App\Http\Controllers\Backend\DataController::class, 'get_keywords']);
    Route::get('/data/get_items', [App\Http\Controllers\Backend\DataController::class, 'get_items']);
    Route::get('/data/get_series', [App\Http\Controllers\Backend\DataController::class, 'get_series']);

    //Movies
    Route::get('/movies', [App\Http\Controllers\Backend\MoviesController::class, 'index'])->name('movies');
    Route::get('/movies/add', [App\Http\Controllers\Backend\MoviesController::class, 'add'])->name('movies-add');
    Route::post('/movies/store', [App\Http\Controllers\Backend\MoviesController::class, 'store'])->name('movies-store');
    Route::get('/movies/edit/{id}', [App\Http\Controllers\Backend\MoviesController::class, 'edit']);
    Route::put('/movies/update/{id}', [App\Http\Controllers\Backend\MoviesController::class, 'update'])->name('movies-update');
    Route::get('/movies/delete/{id}', [App\Http\Controllers\Backend\MoviesController::class, 'destroy'])->name('movies-delete');
    // Fetch
	Route::get('/movies/get_movie_data/{movie_id}', [App\Http\Controllers\Backend\MoviesController::class, 'get_movie_data'])->name('get-movie-data');
    Route::get('/movies/check_tmdb/{movie_id}', [App\Http\Controllers\Backend\MoviesController::class, 'check_tmdb'])->name('check-tmdb');
    //Visible
    Route::post('/movies/visible', [App\Http\Controllers\Backend\MoviesController::class, 'visible'])->name('movies-visible');
    Route::post('/movies/feature', [App\Http\Controllers\Backend\MoviesController::class, 'feature'])->name('movies-feature');
    Route::post('/movies/recommended', [App\Http\Controllers\Backend\MoviesController::class, 'recommended'])->name('movies-recommended');

    //Series
    Route::get('/series', [App\Http\Controllers\Backend\SeriesController::class, 'index'])->name('series');
    Route::get('/series/add', [App\Http\Controllers\Backend\SeriesController::class, 'add'])->name('series-add');
    Route::post('/series/store', [App\Http\Controllers\Backend\SeriesController::class, 'store'])->name('series-store');
    Route::get('/series/edit/{id}', [App\Http\Controllers\Backend\SeriesController::class, 'edit']);
    Route::put('/series/update/{id}', [App\Http\Controllers\Backend\SeriesController::class, 'update'])->name('series-update');
    Route::get('/series/delete/{id}', [App\Http\Controllers\Backend\SeriesController::class, 'destroy'])->name('series-delete');
    // Fetch
	Route::get('/series/get_series_data/{series_id}', [App\Http\Controllers\Backend\SeriesController::class, 'get_series_data'])->name('get-serie-data');
    Route::get('/series/check_tmdb/{series_id}', [App\Http\Controllers\Backend\SeriesController::class, 'check_tmdb'])->name('check-series-tmdb');
    //Visible
    Route::post('/series/visible', [App\Http\Controllers\Backend\SeriesController::class, 'visible'])->name('series-visible');
    Route::post('/series/feature', [App\Http\Controllers\Backend\SeriesController::class, 'feature'])->name('series-feature');
    Route::post('/series/recommended', [App\Http\Controllers\Backend\SeriesController::class, 'recommended'])->name('series-recommended');

    //Episodes (Backend namespace altında olduğunu varsayıyorum, eğer Admin altındaysa FQCN güncellenmeli)
    Route::get('/episodes', [App\Http\Controllers\Backend\EpisodesController::class, 'index'])->name('episodes');
    Route::get('/episodes/add', [App\Http\Controllers\Backend\EpisodesController::class, 'add'])->name('episodes-add');
    Route::post('/episodes/store', [App\Http\Controllers\Backend\EpisodesController::class, 'store'])->name('episodes-store');
    Route::get('/episodes/edit/{id}', [App\Http\Controllers\Backend\EpisodesController::class, 'edit']);
    Route::put('/episodes/update/{id}', [App\Http\Controllers\Backend\EpisodesController::class, 'update'])->name('episodes-update');
    Route::get('/episodes/delete/{id}', [App\Http\Controllers\Backend\EpisodesController::class, 'destroy'])->name('episodes-delete');
    // Fetch
	Route::get('/episodes/get_episodes_data/{series_id}', [App\Http\Controllers\Backend\EpisodesController::class, 'get_series_data']);
    Route::get('/episodes/check_tmdb/{episodes_id}', [App\Http\Controllers\Backend\EpisodesController::class, 'check_tmdb'])->name('check-episodes-tmdb');
    //Visible
    Route::post('/episodes/visible', [App\Http\Controllers\Backend\EpisodesController::class, 'visible'])->name('episodes-visible');
    Route::post('/episodes/feature', [App\Http\Controllers\Backend\EpisodesController::class, 'feature'])->name('episodes-feature');
    Route::post('/episodes/recommended', [App\Http\Controllers\Backend\EpisodesController::class, 'recommended'])->name('episodes-recommended');
    // Fetch Episodes Details
	Route::get('/episodes/get_series/{series_id}', [App\Http\Controllers\Backend\EpisodesController::class, 'get_series_seasons'])->name('get-series-seasons');
	Route::get('/episodes/get_series/{series_id}/{season_id}', [App\Http\Controllers\Backend\EpisodesController::class, 'get_series_episodes'])->name('get-series-episodes');
	Route::get('/episodes/get_series/{series_id}/{season_id}/{episode_id}', [App\Http\Controllers\Backend\EpisodesController::class, 'get_episodes_data'])->name('get-episodes-data');

    //Collections
    Route::get('/collections', [App\Http\Controllers\Backend\CollectionsController::class, 'index'])->name('collections');
    Route::get('/collections/add', [App\Http\Controllers\Backend\CollectionsController::class, 'add'])->name('collections-add');
    Route::post('/collections/store', [App\Http\Controllers\Backend\CollectionsController::class, 'store'])->name('collections-store');
    Route::get('/collections/edit/{id}', [App\Http\Controllers\Backend\CollectionsController::class, 'edit']);
    Route::put('/collections/update/{id}', [App\Http\Controllers\Backend\CollectionsController::class, 'update'])->name('collections-update');
    Route::get('/collections/delete/{id}', [App\Http\Controllers\Backend\CollectionsController::class, 'destroy'])->name('collections-delete');
    Route::post('/collections/visible', [App\Http\Controllers\Backend\CollectionsController::class, 'visible'])->name('collections-visible');

    //Persons
    Route::get('/persons', [App\Http\Controllers\Backend\PersonsController::class, 'index'])->name('persons');
    Route::get('/persons/add', [App\Http\Controllers\Backend\PersonsController::class, 'add'])->name('persons-add');
    Route::post('/persons/store', [App\Http\Controllers\Backend\PersonsController::class, 'store'])->name('persons-store');
    Route::get('/persons/edit/{id}', [App\Http\Controllers\Backend\PersonsController::class, 'edit'])->name('persons-edit');
    Route::put('/persons/edit/{id}', [App\Http\Controllers\Backend\PersonsController::class, 'update'])->name('persons-update');
    Route::get('/persons/delete/{id}', [App\Http\Controllers\Backend\PersonsController::class, 'destroy'])->name('persons-delete');
    // Fetch
    Route::get('/persons/get_person_data/{person_id}', [App\Http\Controllers\Backend\PersonsController::class, 'get_person_data'])->name('get-person-data');
    Route::get('/persons/check_person/{person_id}', [App\Http\Controllers\Backend\PersonsController::class, 'check_person'])->name('check-person');

    //Genres
    Route::get('/genres', [App\Http\Controllers\Backend\GenresController::class, 'index'])->name('genres');
    Route::post('/genres/store', [App\Http\Controllers\Backend\GenresController::class, 'store'])->name('genres-store');
    Route::put('/genres/edit/{id}', [App\Http\Controllers\Backend\GenresController::class, 'update'])->name('genres-update');
    Route::get('/genres/delete/{id}', [App\Http\Controllers\Backend\GenresController::class, 'destroy'])->name('genres-delete');
    //Visible
    Route::post('/genres/visible', [App\Http\Controllers\Backend\GenresController::class, 'visible'])->name('genres-visible');

    //Reports
    Route::get('/reports', [App\Http\Controllers\Backend\ReportsController::class, 'index'])->name('reports');
    Route::post('/reports/store', [App\Http\Controllers\Backend\ReportsController::class, 'store'])->name('reports-store');
    Route::post('/reports/solved', [App\Http\Controllers\Backend\ReportsController::class, 'solved'])->name('reports-solved');
    Route::get('/reports/delete/{id}', [App\Http\Controllers\Backend\ReportsController::class, 'destroy'])->name('reports-delete');

    //Comments
    Route::get('/comments', [App\Http\Controllers\Backend\CommentsController::class, 'index'])->name('comments-lists');
    Route::get('/comments/delete/{id}', [App\Http\Controllers\Backend\CommentsController::class, 'destroy'])->name('comments-delete');
    Route::post('/comments/approve', [App\Http\Controllers\Backend\CommentsController::class, 'approve'])->name('comments-approve');

    //Users
    Route::get('/users', [App\Http\Controllers\Backend\UsersController::class, 'index'])->name('members');
    Route::get('/users/add', [App\Http\Controllers\Backend\UsersController::class, 'add'])->name('members-add');
    Route::post('/users/store', [App\Http\Controllers\Backend\UsersController::class, 'store'])->name('members-store');
    Route::get('/users/edit/{id}', [App\Http\Controllers\Backend\UsersController::class, 'edit']);
    Route::put('/users/update/{id}', [App\Http\Controllers\Backend\UsersController::class, 'update'])->name('members-update');
    Route::get('/users/delete/{id}', [App\Http\Controllers\Backend\UsersController::class, 'destroy'])->name('members-delete');
    Route::post('/users/blocked', [App\Http\Controllers\Backend\UsersController::class, 'blocked'])->name('members-blocked');
    Route::post('/users/verify', [App\Http\Controllers\Backend\UsersController::class, 'verify'])->name('members-verify');

    //Pages
    Route::get('/pages', [App\Http\Controllers\Backend\PagesController::class, 'index'])->name('pages');
    Route::get('/pages/add', [App\Http\Controllers\Backend\PagesController::class, 'add'])->name('pages-add');
    Route::post('/pages/store', [App\Http\Controllers\Backend\PagesController::class, 'store'])->name('pages-store');
    Route::get('/pages/edit/{id}', [App\Http\Controllers\Backend\PagesController::class, 'edit']);
    Route::put('/pages/update/{id}', [App\Http\Controllers\Backend\PagesController::class, 'update'])->name('pages-update');
    Route::get('/pages/delete/{id}', [App\Http\Controllers\Backend\PagesController::class, 'destroy'])->name('pages-delete');

    // Platforms (Yeni Eklendi)
    Route::resource('platforms', PlatformController::class);

    // Pre-roll Videos (Yeni Eklendi)
    Route::resource('pre-roll-videos', PreRollVideoController::class);

    //Settings
    Route::get('/settings', [App\Http\Controllers\Backend\SettingsController::class, 'general'])->name('general-settings');
	Route::put('/settings/general/update', [App\Http\Controllers\Backend\SettingsController::class, 'update_general_settings'])->name('update_general_settings');

    Route::get('/settings/search-engine', [App\Http\Controllers\Backend\SettingsController::class, 'searchengine'])->name('searchengine-settings');
	Route::put('/settings/search-engine/update', [App\Http\Controllers\Backend\SettingsController::class, 'update_searchengine_settings'])->name('update_searchengine_settings');

    Route::get('/settings/advertisements', [App\Http\Controllers\Backend\SettingsController::class, 'advertisements'])->name('advertisements-settings');
	Route::put('/settings/advertisements/update', [App\Http\Controllers\Backend\SettingsController::class, 'update_advertisements_settings'])->name('update_advertisements_settings');

    //Sitemaps
	Route::get('/sitemaps', [App\Http\Controllers\Backend\SitemapsController::class, 'sitemaps_lists']);
	Route::post('/sitemaps/index', [App\Http\Controllers\Backend\SitemapsController::class, 'sitemapsindex']);
	Route::post('/sitemaps/movie', [App\Http\Controllers\Backend\SitemapsController::class, 'sitemapsmovie']);
	Route::post('/sitemaps/series', [App\Http\Controllers\Backend\SitemapsController::class, 'sitemapsseries']);
	Route::post('/sitemaps/episodes', [App\Http\Controllers\Backend\SitemapsController::class, 'sitemapsepisodes']);
	Route::post('/sitemaps/pages', [App\Http\Controllers\Backend\SitemapsController::class, 'sitemapspages']);

    //Announcements
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
    Route::post('/announcements/visible', [App\Http\Controllers\Admin\AnnouncementController::class, 'visible'])->name('announcements.visible');

    // Content Importer Routes
    Route::get('/content-importer', [App\Http\Controllers\Admin\ContentImportController::class, 'create'])->name('content_importer.create');
    Route::post('/content-importer', [App\Http\Controllers\Admin\ContentImportController::class, 'store'])->name('content_importer.store');
    Route::post('/content-importer/process-episodes', [App\Http\Controllers\Admin\ContentImportController::class, 'fetchAndStoreEpisodes'])->name('content_importer.process_episodes');

});
