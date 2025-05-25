@extends('layouts.backend') {{-- admin. -> backend. --}}

@section('title', 'Platformlar')

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
    <h2 class="text-xl font-semibold">Platform Listesi</h2>
    <a href="{{ route('admin.platforms.create') }}" class="px-4 py-2 bg-green-500 uppercase text-white rounded focus:outline-none flex items-center">
        <span class="iconify mr-1" data-icon="gridicons:create" data-inline="false"></span> Yeni Platform Ekle
    </a>
</div>

<section class="w-full text-white rounded-b bg-gray-800">
    <div class="bg-gray-700 p-5 overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-500 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-500 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">Logo</th>
                    <th class="px-5 py-3 border-b-2 border-gray-500 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">Adı</th>
                    <th class="px-5 py-3 border-b-2 border-gray-500 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">Slug</th>
                    <th class="px-5 py-3 border-b-2 border-gray-500 bg-gray-900 text-center text-xs font-semibold text-white uppercase tracking-wider">İçerik Sayısı</th>
                    <th class="px-5 py-3 border-b-2 border-gray-500 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">Oluşturulma T.</th>
                    <th class="px-5 py-3 border-b-2 border-gray-500 bg-gray-900 text-right text-xs font-semibold text-white uppercase tracking-wider">Eylemler</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse ($platforms as $platform)
                    <tr class="border-b border-gray-700 hover:bg-gray-600">
                        <td class="px-5 py-4 text-sm">{{ $platform->id }}</td>
                        <td class="px-5 py-4 text-sm">
                            @if ($platform->logo_image_path)
                                <img src="{{ Storage::url($platform->logo_image_path) }}" alt="{{ $platform->name }}" class="h-10 w-auto rounded">
                            @else
                                <span class="text-gray-500">Logo Yok</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm">{{ $platform->name }}</td>
                        <td class="px-5 py-4 text-sm">{{ $platform->slug }}</td>
                        <td class="px-5 py-4 text-sm text-center">{{ $platform->items_count }}</td>
                        <td class="px-5 py-4 text-sm">{{ $platform->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4 text-sm text-right whitespace-nowrap">
                            <div class="flex space-x-2 items-center justify-end">
                                <a href="{{ route('admin.platforms.edit', $platform->id) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded focus:outline-none flex items-center text-xs" title="Düzenle">
                                    <span class="iconify mr-1" data-icon="akar-icons:edit" data-inline="false"></span> Düzenle
                                </a>
                                <form action="{{ route('admin.platforms.destroy', $platform->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu platformu silmek istediğinizden emin misiniz? İlişkili içeriklerin platform bilgisi kaldırılacaktır.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded focus:outline-none flex items-center text-xs" title="Sil">
                                        <span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span> Sil
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-5 text-center text-gray-500">
                            Kayıtlı platform bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($platforms->hasPages())
    <div class="p-5 bg-gray-800 border-t border-gray-700">
        {{ $platforms->links('layouts.pagination') }} 
    </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
    // Gerekirse buraya özel JavaScript kodları eklenebilir.
</script>
@endpush 