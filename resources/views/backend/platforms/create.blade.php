@extends('layouts.backend') {{-- admin. -> backend. --}}

@section('title', 'Yeni Platform Ekle')

@section('content')

<!-- Messages -->
@if (session('success'))
    <div x-data="{ show: true }" x-show="show"
        class="mb-4 flex justify-between items-center bg-green-500 relative text-white py-3 px-5 rounded-lg shadow-md">
        <div>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
        <div>
            <button type="button" @click="show = false" class="text-white hover:text-gray-200 focus:outline-none transition-colors duration-150">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative shadow-md" role="alert">
        <strong class="font-bold">Hata!</strong>
        <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="parentNode.remove()">
            <span class="text-2xl text-red-500 hover:text-red-700 transition-colors duration-150">&times;</span>
        </button>
    </div>
@endif
<!-- End Messages -->

<div class="bg-gray-800 shadow-xl rounded-lg">
    <div class="w-full p-5 text-white bg-gray-700 rounded-t-lg flex justify-between items-center">
        <h2 class="text-xl font-semibold">Yeni Platform Formu</h2>
        <a href="{{ route('admin.platforms.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-75">
            <span class="iconify mr-1" data-icon="ic:round-arrow-back" data-inline="false"></span>Geri Dön
        </a>
    </div>

    <form action="{{ route('admin.platforms.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Platform Adı --}}
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Platform Adı <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full bg-gray-700 text-white border @error('name') border-red-500 @else border-gray-600 @enderror rounded-lg py-2.5 px-4 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition duration-150 ease-in-out"
                       required>
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Platform Slug --}}
            <div class="md:col-span-1">
                <label for="slug" class="block text-sm font-medium text-gray-300 mb-1">Platform Slug (URL Dostu Ad)</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                       class="w-full bg-gray-700 text-white border @error('slug') border-red-500 @else border-gray-600 @enderror rounded-lg py-2.5 px-4 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition duration-150 ease-in-out"
                       placeholder="Otomatik oluşturulur (isteğe bağlı)">
                @error('slug')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Platform Logosu --}}
        <div class="mt-6">
            <label for="logo_image" class="block text-sm font-medium text-gray-300 mb-1">Platform Logosu</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 @error('logo_image') border-red-500 @else border-gray-600 @enderror border-dashed rounded-lg bg-gray-700 hover:border-gray-500 transition-colors duration-150">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-400">
                        <label for="logo_image" class="relative cursor-pointer bg-gray-600 rounded-md font-medium text-yellow-400 hover:text-yellow-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-gray-700 focus-within:ring-yellow-500 px-2 py-1 transition-colors duration-150">
                            <span>Dosya Yükle</span>
                            <input id="logo_image" name="logo_image" type="file" class="sr-only">
                        </label>
                        <p class="pl-1">veya sürükleyip bırakın</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF, SVG, WEBP (MAX. 5MB)</p>
                    <div id="logo_preview_container" class="mt-2 hidden">
                        <img id="logo_preview" src="#" alt="Logo Önizleme" class="max-h-24 mx-auto rounded"/>
                        <button type="button" id="remove_logo_preview" class="mt-1 text-xs text-red-500 hover:text-red-400">Kaldır</button>
                    </div>
                </div>
            </div>
            @error('logo_image')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Butonlar --}}
        <div class="mt-8 pt-5 border-t border-gray-700">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.platforms.index') }}" class="bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                    İptal
                </a>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                    <span class="iconify mr-1" data-icon="fluent:save-20-filled" data-inline="false"></span>Kaydet
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const logoInput = document.getElementById('logo_image');
    const logoPreview = document.getElementById('logo_preview');
    const logoPreviewContainer = document.getElementById('logo_preview_container');
    const removeLogoPreviewButton = document.getElementById('remove_logo_preview');

    logoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreviewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            logoPreview.src = '#';
            logoPreviewContainer.classList.add('hidden');
        }
    });

    removeLogoPreviewButton.addEventListener('click', function() {
        logoInput.value = ''; // Clear the file input
        logoPreview.src = '#';
        logoPreviewContainer.classList.add('hidden');
    });
});
</script>
@endpush 