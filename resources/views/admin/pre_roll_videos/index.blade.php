@extends('layouts.backend')

@section('content')
    <div class="w-full p-5 text-gray-900 bg-white rounded-t flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Ön Yükleme Videoları</h1>
        <a href="{{ route('admin.pre-roll-videos.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
            <span class="iconify mr-1" data-icon="mdi:plus-circle-outline"></span> Yeni Video Ekle
        </a>
    </div>

    <div class="w-full p-5 bg-gray-100 rounded-b">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($preRollVideos->isEmpty())
            <p class="text-gray-700">Henüz ön yükleme videosu eklenmemiş.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Ad</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Video URL</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Hedef URL</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm text-center">Atlanabilir (sn)</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm text-center">Aktif</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm text-left">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($preRollVideos as $video)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $video->name ?? '-' }}</td>
                                <td class="py-3 px-4"><a href="{{ $video->video_url }}" target="_blank" class="text-blue-500 hover:underline">{{ Str::limit($video->video_url, 40) }}</a></td>
                                <td class="py-3 px-4">@if($video->target_url)<a href="{{ $video->target_url }}" target="_blank" class="text-blue-500 hover:underline">{{ Str::limit($video->target_url, 30) }}</a>@else - @endif</td>
                                <td class="py-3 px-4 text-center">{{ $video->skippable_after_seconds ?? 'Atlanamaz' }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if($video->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Evet</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Hayır</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 flex items-center space-x-2">
                                    <a href="{{ route('admin.pre-roll-videos.edit', $video->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                        <span class="iconify" data-icon="mdi:pencil"></span> Düzenle
                                    </a>
                                    <form action="{{ route('admin.pre-roll-videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Bu videoyu silmek istediğinizden emin misiniz?');" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <span class="iconify" data-icon="mdi:delete"></span> Sil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $preRollVideos->links() }} 
            </div>
        @endif
    </div>
@endsection 