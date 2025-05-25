@extends('layouts.backend')

@section('content')
    <div class="w-full p-5 text-gray-900 bg-white rounded-t">
        <h1 class="text-2xl font-semibold">Yeni Ön Yükleme Videosu Ekle</h1>
    </div>

    <div class="w-full p-5 bg-gray-100 rounded-b">
        <form action="{{ route('admin.pre-roll-videos.store') }}" method="POST">
            @csrf
            @include('admin.pre_roll_videos._form')
        </form>
    </div>
@endsection 