@extends('layouts.backend')

@section('content')
    <div class="w-full p-5 text-gray-900 bg-white rounded-t">
        <h1 class="text-2xl font-semibold">Ön Yükleme Videosunu Düzenle: {{ $preRollVideo->name ?? 'Adsız Video' }}</h1>
    </div>

    <div class="w-full p-5 bg-gray-100 rounded-b">
        <form action="{{ route('admin.pre-roll-videos.update', $preRollVideo->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.pre_roll_videos._form', ['preRollVideo' => $preRollVideo])
        </form>
    </div>
@endsection 