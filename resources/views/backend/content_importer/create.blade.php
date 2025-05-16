@extends('layouts.backend')

@section('content')
    <div class="w-full p-5 text-white bg-gray-900 rounded-t">
        İçerik Aktarıcı
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            <span class="font-medium">Başarılı!</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            <span class="font-medium">Hata!</span> {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800" role="alert">
            <span class="font-medium">Uyarı!</span> {!! session('warning') !!}
        </div>
    @endif

    {{-- Görsel İndirme Geri Bildirimleri --}}
    @if(session('image_feedback'))
        <div class="p-4 mb-4 text-sm text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300" role="alert">
            <span class="font-medium">Görsel İndirme Sonuçları:</span>
            <ul class="mt-1.5 ml-4 list-disc list-inside">
                @foreach(session('image_feedback') as $feedback)
                    @php
                        $bgColor = 'bg-gray-100'; // default
                        $textColor = 'text-gray-700'; // default
                        $darkBgColor = 'dark:bg-gray-700';
                        $darkTextColor = 'dark:text-gray-300';
                        $icon = '';

                        switch ($feedback['type']) {
                            case 'success':
                                $bgColor = 'bg-green-100';
                                $textColor = 'text-green-700';
                                $darkBgColor = 'dark:bg-green-200';
                                $darkTextColor = 'dark:text-green-800';
                                $icon = '✓'; // Check mark
                                break;
                            case 'error':
                                $bgColor = 'bg-red-100';
                                $textColor = 'text-red-700';
                                $darkBgColor = 'dark:bg-red-200';
                                $darkTextColor = 'dark:text-red-800';
                                $icon = '✗'; // Cross mark
                                break;
                            case 'warning':
                                $bgColor = 'bg-yellow-100';
                                $textColor = 'text-yellow-700';
                                $darkBgColor = 'dark:bg-yellow-200';
                                $darkTextColor = 'dark:text-yellow-800';
                                $icon = '!'; // Exclamation mark
                                break;
                            case 'info':
                                $bgColor = 'bg-blue-100';
                                $textColor = 'text-blue-700';
                                $darkBgColor = 'dark:bg-blue-200';
                                $darkTextColor = 'dark:text-blue-800';
                                $icon = 'ℹ'; // Info icon
                                break;
                        }
                    @endphp
                    <li class="p-2 my-1 rounded-md {{ $bgColor }} {{ $textColor }} {{ $darkBgColor }} {{ $darkTextColor }}">
                        <span class="font-bold">{{ $icon }}</span> {{ $feedback['text'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- /Görsel İndirme Geri Bildirimleri --}}

    @if(session('processed_item_id') && session('seasons_to_process'))
        @php
            $itemForEpisodes = \App\Models\Items::find(session('processed_item_id'));
            $seasonsToProcess = session('seasons_to_process');
            $totalSeasons = count($seasonsToProcess);
            $totalEpisodes = 0;
            foreach($seasonsToProcess as $season) {
                if (isset($season['episodes']) && is_array($season['episodes'])) {
                    $totalEpisodes += count($season['episodes']);
                }
            }
        @endphp

        @if($itemForEpisodes)
            <div class="p-4 my-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                <span class="font-medium">Bölümler İşlenmeye Hazır!</span><br>
                <strong>{{ $itemForEpisodes->title }}</strong> dizisi için toplam <strong>{{ $totalSeasons }} sezon</strong> ve <strong>{{ $totalEpisodes }} bölüm</strong> linki bulundu.
                <form action="{{ route('admin.content_importer.process_episodes') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                        {{ $itemForEpisodes->title }} İçin Bölümleri Çek ve Kaydet
                    </button>
                </form>
            </div>
        @endif
    @endif

    <section class="w-full p-5 bg-gray-800 text-white rounded-b">
        <form action="{{ route('admin.content_importer.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="import_url" class="block mb-2 text-sm font-medium text-gray-300">İçerik URL'si</label>
                <input type="url" id="import_url" name="import_url" value="{{ old('import_url') }}" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="https://ornek.com/dizi/dizi-adi" required>
                @error('import_url')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content_type" class="block mb-2 text-sm font-medium text-gray-300">İçerik Tipi</label>
                <select id="content_type" name="content_type" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="series" {{ old('content_type', 'series') == 'series' ? 'selected' : '' }}>Dizi</option>
                    <option value="movie" {{ old('content_type') == 'movie' ? 'selected' : '' }}>Film</option>
                </select>
                @error('content_type')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">İçeriği Çek</button>
        </form>
    </section>
@endsection 