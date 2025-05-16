@extends('layouts.backend')

@section('content')
    <div class="w-full p-5 text-white bg-gray-900 rounded-t">Yeni Duyuru Ekle</div>
    <section class="w-full p-5 bg-gray-800 text-white rounded-b">

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <span class="font-medium">Hata!</span> Lütfen aşağıdaki hataları düzeltin:
                <ul class="mt-1.5 ml-4 text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300">Başlık</label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="block w-full shadow-sm sm:text-sm rounded-md bg-gray-700 border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-white">
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-300">İçerik (Kayan yazıda görünecek metin)</label>
                <div class="mt-1">
                    <textarea name="content" id="content" rows="3" required class="block w-full shadow-sm sm:text-sm rounded-md bg-gray-700 border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-white">{{ old('content') }}</textarea>
                </div>
            </div>

            <div>
                <label for="link_url" class="block text-sm font-medium text-gray-300">Link URL (Opsiyonel, ör: /haber/ detay veya https://site.com/sayfa)</label>
                <div class="mt-1">
                    <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" placeholder="https://..." class="block w-full shadow-sm sm:text-sm rounded-md bg-gray-700 border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-white">
                </div>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-300">Başlangıç Tarihi (Opsiyonel)</label>
                <div class="mt-1">
                    <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" class="block w-full shadow-sm sm:text-sm rounded-md bg-gray-700 border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-white">
                </div>
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-300">Bitiş Tarihi (Opsiyonel)</label>
                <div class="mt-1">
                    <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="block w-full shadow-sm sm:text-sm rounded-md bg-gray-700 border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-white">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-500 rounded focus:ring-indigo-500 bg-gray-700">
                <label for="is_active" class="ml-2 block text-sm text-gray-300">Aktif</label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 border border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">İptal</a>
                <button type="submit" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Kaydet</button>
            </div>
        </form>
    </section>
@endsection 