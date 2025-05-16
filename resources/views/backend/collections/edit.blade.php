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

@if(Session::has('message'))
<div x-data="{ show: true }" x-show="show"
    class="mb-4 flex justify-between items-center bg-red-500 relative text-white py-2 px-4 rounded">
    <div>
        {{ Session::get('progress') }}
    </div>
    <div>
        <button type="button" @click="show = false" class="text-white focus:outline-none">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
</div>
@endif

<!-- Message End -->

<div class="w-full p-5 text-white bg-gray-900 rounded-t flex justify-between">
    <span>Edit Collections</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($editcollections, ['route' => ['collections-update',$editcollections->id],'method'=>'put']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full space-y-6">
            <div class="w-full" id="tmdb">
                <label><h4 class="w-full mb-2 font-medium">Name</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t p-2 bg-gray-600 text-white" type="text" name="c_name" placeholder="Collection Name.." value="{{ $editcollections->name }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Select Movies</h4></label>
                <div class="w-full">
                    <input id="c_items" type="text" name="c_items" placeholder="Select Movies.." >
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-900 justify-between items=center border-1 border-t border-gray-700 p-5 w-full flex items-center">
        <button type="submit" class="bg-green-500 rounded text-white px-4 py-2">Update Collections</button>
        <a href="/admin/collections" class="bg-gray-500 rounded text-white px-4 py-2">Cancel</a>
    </div>
    {!! Form::close() !!}
</section>
@endsection

@section('js')
<script>
	var takenitems = {!! $editcollections->items->pluck('id') !!};
</script>
<script type="text/javascript">
    $(document).ready(function(){
        //items
		var $items = $('#c_items').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
	    var item = $items[0].selectize;
		$.getJSON('/admin/data/get_items', function(itemsData) {
			$.each(itemsData, function(val, key) {
				item.addOption({ value: key.id, text: key.title });
			});
			$.each(takenitems, function(val, key) {
				item.addItem(takenitems[val]);
			});
	    });
    });
</script>
@endsection
