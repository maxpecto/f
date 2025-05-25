{{-- Platform Logoları Başlangıç --}}
@if(isset($platformsGlobal) && $platformsGlobal->isNotEmpty())
<div class="container mx-auto py-3 px-4">
    <div class="flex flex-wrap items-center justify-center text-white space-x-2 sm:space-x-4">
        @foreach($platformsGlobal->take(8) as $platform)
            <a href="{{ route('frontend.platform.items', $platform->slug) }}" title="{{ $platform->name }}" class="hover:opacity-80 transition-opacity duration-150">
                @if($platform->logo_image_path)
                    <img src="{{ Storage::url($platform->logo_image_path) }}" alt="{{ $platform->name }}" class="h-7 sm:h-9 object-contain" style="max-width: 90px;">
                @else
                    <span class="text-sm px-2 py-1 bg-gray-700 rounded hover:bg-gray-600">{{ $platform->name }}</span>
                @endif
            </a>
        @endforeach
    </div>
</div>
@endif
{{-- Platform Logoları Son --}}

<div class="container mx-auto bg-gray-800 p-4 rounded-b-lg shadow-lg relative">
    {{-- Search Input --}}
    <div class="relative w-full md:w-3/4 lg:w-1/2 mx-auto">
        {!! Form::open(['id' => 'liveSearchForm', 'url' => 'search','method'=>'get', 'class' => 'relative']) !!}
        <input type="text" name="keywords" id="liveSearchInput" class="w-full bg-gray-700 text-white placeholder-gray-400 border border-gray-600 rounded-lg py-2.5 px-4 pl-10 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none" placeholder="{{ __('Film, dizi veya oyuncu ara...') }}">
        <input type="hidden" name="countries" value="">
        <input type="hidden" name="years" value="">
        <input type="hidden" name="rating_from" value="">
        <input type="hidden" name="rating_to" value="">
        <input type="hidden" name="duration_from" value="">
        <input type="hidden" name="duration_to" value="">
        <input type="hidden" name="genres" value="">
        <input type="hidden" name="quality" value="">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="iconify text-gray-400" data-icon="carbon:search" data-inline="false" style="font-size: 1.2rem;"></span>
        </div>
        {{-- Arama butonu gizli olabilir veya inputun bir parçası gibi davranabilir --}}
        {{-- <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-yellow-400 hover:text-yellow-300">
            <span class="iconify" data-icon="carbon:search" data-inline="false"></span>
        </button> --}}
        {!! Form::close() !!}
        {{-- Live Search Results Area --}}
        <div id="liveSearchResults" class="absolute w-full bg-gray-700 border border-gray-600 rounded-b-lg shadow-xl mt-1 z-50" style="min-height: 100px; max-height: 400px; overflow-y: auto; display: none;">
            {{-- Results will be injected here by JavaScript --}}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('liveSearchInput');
    const resultsContainer = document.getElementById('liveSearchResults');
    const searchForm = document.getElementById('liveSearchForm');
    let debounceTimer;

    searchInput.addEventListener('keyup', function () {
        clearTimeout(debounceTimer);
        const keywords = this.value.trim();

        if (keywords.length >= 2) { // Minimum 2 karakter girilince aramayı başlat
            debounceTimer = setTimeout(function () {
                // Collect all form data
                const formData = new FormData(searchForm);
                const params = new URLSearchParams(formData).toString();

                resultsContainer.style.display = 'block'; // Arama başladığında görünür yap
                resultsContainer.innerHTML = '<div class="p-3 text-gray-400">{{ __("Yükleniyor...") }}</div>'; // Yükleniyor mesajı

                fetch(`/search?${params}&source=live_search`, { // source=live_search gibi bir parametre ekleyerek backend'in AJAX isteğini anlamasını sağlayabiliriz
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Laravel'in AJAX isteğini tanıması için
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Veya response.json() eğer JSON dönecekse
                })
                .then(html => {
                    resultsContainer.innerHTML = html;
                    // resultsContainer.style.display = 'block'; // Zaten yukarıda block yapıldı
                })
                .catch(error => {
                    console.error('Canlı arama sırasında hata:', error);
                    resultsContainer.innerHTML = '<div class="p-3 text-gray-400">{{ __("Arama sırasında bir hata oluştu.") }}</div>';
                    // resultsContainer.style.display = 'block'; // Zaten yukarıda block yapıldı
                });
            }, 300); // 300ms gecikme
        } else {
            resultsContainer.style.display = 'none'; // Karakter sayısı yetersizse veya input boşsa gizle
            resultsContainer.innerHTML = '';
        }
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !resultsContainer.contains(event.target)) {
            resultsContainer.style.display = 'none';
        }
    });

     // Prevent form submission on enter if live search is active and showing results
    searchForm.addEventListener('submit', function(event) {
        if (resultsContainer.style.display === 'block' && searchInput.value.trim().length > 0) {
            // İsteğe bağlı: Enter'a basıldığında canlı arama sonuçlarından ilkine gitmek veya
            // formu normal şekilde göndermek yerine canlı arama sonuçlarını kullanmak gibi
            // bir davranış eklenebilir. Şimdilik, normal submit'i engelliyoruz eğer canlı sonuçlar açıksa.
            // Eğer normal sayfa aramasını da korumak isterseniz bu bloğu kaldırın veya düzenleyin.
            // event.preventDefault(); // Eğer enter ile normal sayfa aramasını engellemek isterseniz
        }
    });
});
</script>
