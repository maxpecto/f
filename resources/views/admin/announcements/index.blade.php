@extends('layouts.backend')

@section('content')
    <div class="w-full p-5 text-white bg-gray-900 rounded-t">Duyuruları Yönet</div>
    <section class="w-full p-5 bg-gray-800 text-white rounded-b">

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('admin.announcements.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Yeni Duyuru Ekle</a>
        </div>

        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6">ID</th>
                        <th scope="col" class="py-3 px-6">Başlık</th>
                        <th scope="col" class="py-3 px-6">Aktif mi?</th>
                        <th scope="col" class="py-3 px-6">Başlangıç Tarihi</th>
                        <th scope="col" class="py-3 px-6">Bitiş Tarihi</th>
                        <th scope="col" class="py-3 px-6">Link</th>
                        <th scope="col" class="py-3 px-6">Eylemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr class="border-b bg-gray-800 border-gray-700 hover:bg-gray-600">
                            <td class="py-4 px-6">{{ $announcement->id }}</td>
                            <td class="py-4 px-6">{{ $announcement->title }}</td>
                            <td class="py-4 px-6">
                                @if($announcement->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pasif</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">{{ $announcement->start_date ? $announcement->start_date->format('d.m.Y H:i') : '-' }}</td>
                            <td class="py-4 px-6">{{ $announcement->end_date ? $announcement->end_date->format('d.m.Y H:i') : '-' }}</td>
                            <td class="py-4 px-6">
                                @if($announcement->link_url)
                                    <a href="{{ $announcement->link_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 hover:underline">{{ Str::limit($announcement->link_url, 30) }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="font-medium text-blue-500 hover:underline mr-2">Düzenle</a>
                                <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bu duyuruyu silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-500 hover:underline">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-b bg-gray-800 border-gray-700">
                            <td colspan="7" class="py-4 px-6 text-center">Gösterilecek duyuru bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($announcements->hasPages())
            <div class="mt-4 p-2 bg-gray-700 rounded">
                {{ $announcements->links() }} 
            </div>
        @endif
    </section>
@endsection 