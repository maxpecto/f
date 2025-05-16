.
@extends('layouts.backend')

@section('content')

<!-- Message -->
@if ($message = Session::get('success'))
    <div x-data="{ show: true }" x-show="show"
        class="mb-4 flex justify-between items-center bg-green-500 relative text-white py-2 px-4 rounded">
        <div>
            {{ $message }}
        </div>
        <div>
            <button type="button" @click="show = false" class="text-white focus:outline-none">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    </div>
@endif

@if(count($errors) > 0)
    @foreach($errors->all() as $error)
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

<div class="w-full p-5 text-white bg-gray-900 rounded-t flex justify-between">
    <span>Sitemaps</span>
</div>
<section class="w-full text-white rounded-b">


    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 overflow-x-scroll">
        <table class="min-w-full leading-normal">
            <tbody class="text-gray-900">
				<tr class="overflow-x-scroll">
					<td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left"><b>Index All (Homepage / Movies / Series / Episodes / Pages / Genres)</b></td>
					<td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
						<a class="px-4 py-2 bg-gray-900 rounded text-white" href="{{ url('sitemap.xml') }}" target="_blank">Read</a>
					</td>
					<td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
						{!! Form::open(['url' => 'admin/sitemaps/index','method'=>'post','class'=>'m-0']) !!}
							<input type="hidden" name="items" value="index">
							<button type="submit" class="px-4 py-2 bg-gray-900 rounded text-white">Generate</button>
						{!! Form::close() !!}
					</td>
				</tr>

                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left"><b>Movies</b></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a class="px-4 py-2 bg-gray-900 rounded text-white" href="{{ url('movies-sitemap.xml') }}" target="_blank">Read</a>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        {!! Form::open(['url' => 'admin/sitemaps/movie','method'=>'post','class'=>'m-0']) !!}
                            <input type="hidden" name="items" value="movies">
                            <button type="submit" class="px-4 py-2 bg-gray-900 rounded text-white">Generate</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left"><b>Series</b></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a class="px-4 py-2 bg-gray-900 rounded text-white" href="{{ url('series-sitemap.xml') }}" target="_blank">Read</a>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        {!! Form::open(['url' => 'admin/sitemaps/series','method'=>'post','class'=>'m-0']) !!}
                            <input type="hidden" name="items" value="series">
                            <button type="submit" class="px-4 py-2 bg-gray-900 rounded text-white">Generate</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left"><b>Episodes</b></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a class="px-4 py-2 bg-gray-900 rounded text-white" href="{{ url('episodes-sitemap.xml') }}" target="_blank">Read</a>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        {!! Form::open(['url' => 'admin/sitemaps/episodes','method'=>'post','class'=>'m-0']) !!}
                            <input type="hidden" name="items" value="episodes">
                            <button type="submit" class="px-4 py-2 bg-gray-900 rounded text-white">Generate</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left"><b>Pages</b></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a class="px-4 py-2 bg-gray-900 rounded text-white" href="{{ url('pages-sitemap.xml') }}" target="_blank">Read</a>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        {!! Form::open(['url' => 'admin/sitemaps/page','method'=>'post','class'=>'m-0']) !!}
                            <input type="hidden" name="items" value="pages">
                            <button type="submit" class="px-4 py-2 bg-gray-900 rounded text-white">Generate</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left"><b>Genres</b></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <a class="px-4 py-2 bg-gray-900 rounded text-white" href="{{ url('genres-sitemap.xml') }}" target="_blank">Read</a>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        {!! Form::open(['url' => 'admin/sitemaps/genre','method'=>'post','class'=>'m-0']) !!}
                            <input type="hidden" name="items" value="genres">
                            <button type="submit" class="px-4 py-2 bg-gray-900 rounded text-white">Generate</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</section>
@endsection
