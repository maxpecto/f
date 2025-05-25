@extends('layouts.backend') {{-- admin. -> backend. --}}

@section('title', 'Platform Düzenle: ' . $platform->name)

@section('content')

<!-- Message -->
@if (session('success'))
    <div x-data="{ show: true }" x-show="show"
        class="mb-4 flex justify-between items-center bg-green-500 relative text-white py-2 px-4 rounded">
        <div>
            {{ session('success') }}
        </div>
        <div>
            <button type="button" @click="show = false" class="text-white focus:outline-none">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    </div>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div x-data="{ show: true }" x-show="show"
        class="mb-4 flex justify-between items-center bg-red-500 relative text-white py-2 px-4 rounded">
        <div>
            {{ $error }}
        </div>
        <div>
            <button type="button" @click="show = false" class="text-white focus:outline-none">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    </div>
    @endforeach
@endif
<!-- Message End -->

<div class="w-full p-5 text-white bg-gray-900 rounded-t flex justify-between items-center">
    <h2 class="text-xl font-semibold">Platform Düzenleme Formu: {{ $platform->name }}</h2>
</div>

<section class="w-full text-gray-300 rounded-b bg-gray-800">
    <form action="{{ route('admin.platforms.update', $platform->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block mb-2 text-sm font-medium">Platform Adı <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $platform->name) }}" required 
                   class="w-full rounded p-2 bg-gray-700 border border-gray-600 text-white focus:ring-yellow-500 focus:border-yellow-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="slug" class="block mb-2 text-sm font-medium">Platform Slug (URL Dostu Ad)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $platform->slug) }}" 
                   class="w-full rounded p-2 bg-gray-700 border border-gray-600 text-white focus:ring-yellow-500 focus:border-yellow-500 @error('slug') border-red-500 @enderror">
            <small class="mt-1 text-xs text-gray-400">Değiştirmek istemiyorsanız boş bırakın. Boş bırakılırsa ve platform adı değişirse, slug otomatik güncellenir.</small>
            @error('slug')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="logo_image" class="block mb-2 text-sm font-medium">Platform Logosu</label>
            <input type="file" id="logo_image" name="logo_image" 
                   class="block w-full text-sm text-gray-400 border border-gray-600 rounded cursor-pointer bg-gray-700 focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-yellow-500 file:text-gray-900 hover:file:bg-yellow-600 @error('logo_image') border-red-500 @enderror">
            <small class="mt-1 text-xs text-gray-400">Değiştirmek istemiyorsanız boş bırakın. İzin verilen türler: JPG, PNG, GIF, SVG, WEBP. Max: 2MB.</small>
            @error('logo_image')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror

            @if ($platform->logo_image_path)
                <div class="mt-4">
                    <label class="block mb-1 text-sm font-medium">Mevcut Logo:</label>
                    <img src="{{ Storage::url($platform->logo_image_path) }}" alt="{{ $platform->name }}" class="h-20 w-auto rounded bg-gray-700 p-1 border border-gray-600">
                </div>
            @endif
        </div>

        <div class="flex items-center justify-start space-x-4 pt-4 border-t border-gray-700">
            <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-gray-900 rounded font-medium focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 flex items-center">
                <span class="iconify mr-2" data-icon="fluent:save-28-filled" data-inline="false"></span> Güncelle
            </button>
            <a href="{{ route('admin.platforms.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded font-medium focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800 flex items-center">
                <span class="iconify mr-2" data-icon="fluent:arrow-left-28-filled" data-inline="false"></span> İptal
            </a>
        </div>
    </form>
</section>
@endsection

@push('scripts')
{{-- Eski bsCustomFileInput script'i Tailwind ile direkt uyumlu olmayabilir ve modern bir dosya inputu için gereksiz olabilir. --}}
{{-- Gerekirse, dosya adını göstermek için basit bir Alpine.js veya vanilla JS çözümü eklenebilir. Şimdilik kaldırıldı. --}}
{{-- <script src="{{ asset('assets/backend/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script> --}}
@endpush 