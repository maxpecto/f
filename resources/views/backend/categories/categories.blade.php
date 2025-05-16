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
    <span>Categories</span><span>Total Categories : {{ count($total_categories)  }}</span>
</div>
<section class="w-full text-white rounded-b">

    <div class="flex bg-gray-800 justify-between items=center p-5">
        <div class="flex space-x-2 items-center">
            <a href="#" id="delete" class="px-4 py-2 bg-red-500 uppercase text-white rounded flex items-center hidden transition delay-150 duration-300 ease-in-out"><span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span> Delete</a>
        </div>
        <div class="flex space-x-2 items-center">
            <!-- Modal Start -->
            <div x-cloak x-data="{ open: false }">
                <button class="px-4 py-2 bg-green-500 uppercase text-white rounded focus:outline-none flex items-center" @click="open = true"><span class="iconify mr-1" data-icon="gridicons:create" data-inline="false"></span> Create Categories</button>
                <div class="fixed z-50 top-0 left-0 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);" x-show="open" >
                    <div class="h-auto p-4 mx-2 text-left bg-gray-900 rounded shadow-xl w-9/12 md:max-w-xl md:p-6 lg:p-8 md:mx-0" @click.away="open = false">
                        {!! Form::open(['url' => 'admin/categories/store','method'=>'post']) !!}
                            <div class="text-center">
                                <h3 class="text-lg font-medium leading-6 text-white uppercase border-1 border-b border-gray-600 w-max pb-4">
                                    Create Categories
                                </h3>
                                <div class="mt-6">
                                    <p class="text-sm leading-5 text-gray-500">
                                        <input type="text" name="name" autofocus class="w-full p-2 bg-gray-200 text-gray-500 rounded border border-transparent focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Categories Name..">
                                    </p>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <span class="flex w-full rounded-md shadow-sm space-x-4">
                                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 focus:outline-none">Create</button>
                                    <a @click="open = false" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 cursor-pointer focus:outline-none">
                                        Close
                                    </a>
                                </span>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!-- Modal end -->
        </div>
    </div>

    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 overflow-x-scroll">
        <div>
            {!! Form::open(['route' => ['categories'] ,'method' => 'get' ,'class' => 'flex items-center mb-5 w-full relative']) !!}
                <input class="pl-4 pr-10 py-2 bg-gray-200 text-gray-900 rounded w-full" type="text" autofocus name="search" placeholder="Search..">
                <button type="submit" class="absolute right-0 top-0 bottom-0 items-center text-gray-400 hover:text-gray-800 pr-2 text-xl transition delay-150 duration-300 ease-in-out"><span class="iconify" data-icon="akar-icons:search" data-inline="false"></span></button>
            {!! Form::close() !!}
        </div>
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">
                        <input type="checkbox" id="ckbCheckAll" />
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Categories Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-right">Created at</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-900">
                @if(!$data->isEmpty())
                    @foreach($data as $i=>$row)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <div class="basic checkbox">
                                    <input type="checkbox" class="checkBoxClass" value="{{ $row->id  }}" tabindex="0" name="">
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-cenleftter">
                                <strong>{{ $row->name }}</strong>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                {{ $row->visible }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                {{ $row->created_at }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <div class="flex space-x-2 items-center float-right">
                                    <!-- Update Modal Start -->
                                    <div x-cloak x-data="{ open: false }">
                                        <button class="px-4 py-2 bg-green-500 uppercase text-white rounded focus:outline-none flex items-center" @click="open = true"><span class="iconify mr-1" data-icon="akar-icons:edit" data-inline="false"></span> Edit</button>
                                        <div class="fixed z-50 top-0 left-0 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);" x-show="open" >
                                            <div class="h-auto p-4 mx-2 text-left bg-gray-900 rounded shadow-xl w-9/12 md:max-w-xl md:p-6 lg:p-8 md:mx-0" @click.away="open = false">
                                                {!! Form::model($row, ['route' => ['categories-update', $row->id ], 'method'=>'put' ]) !!}
                                                    <div class="text-center">
                                                        <h3 class="text-lg font-medium leading-6 text-white uppercase border-1 border-b border-gray-600 w-max pb-4">
                                                            Update Categories
                                                        </h3>
                                                        <div class="mt-6">
                                                            <p class="text-sm leading-5 text-gray-500">
                                                                <input type="text" name="name" autofocus value="{{ $row->name }}" class="w-full p-2 bg-gray-200 text-gray-500 rounded border border-transparent focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Categories Name..">
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-5 sm:mt-6">
                                                        <span class="flex w-full rounded-md shadow-sm space-x-4">
                                                            <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 focus:outline-none">Update</button>
                                                            <a @click="open = false" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 cursor-pointer focus:outline-none">
                                                                Close
                                                            </a>
                                                        </span>
                                                    </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal end -->
                                    <a href="{{ url('/admin/categories/delete/['.$row->id.']') }}" class="px-4 py-2 bg-red-500 uppercase text-white rounded flex items-center"><span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span> Delete</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center"><div class="ui orange message"><b>You dont have any categories</b></div></td></tr>
                @endif
            </tbody>
            <tfoot>
				<tr>
					<th colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
						{{ $data->links('layouts.pagination')  }}
					</th>
				</tr>
			</tfoot>
        </table>
    </div>

</section>
@endsection


@section('js')
<script type="text/javascript">
    $('.basic.checkbox input').on('change', function() {
        var checkedBox      = $('.basic.checkbox input:checked');
        var deleteCondition = checkedBox.length > 0;

        var postsId       = [];
        var token = $('input[name="_token"]').val();

        $("#delete").toggleClass("hidden", !deleteCondition);

        checkedBox.each(function() {
            postsId.push(parseInt($(this).val()));
        })

        postsId = postsId.length ? JSON.stringify(postsId) : '';
        $('#delete')[0].href = '/admin/categories/delete/' + postsId;
    });

    $("#ckbCheckAll").click(function () {
        $(".checkBoxClass").prop('checked', $(this).prop('checked'));

        var checkedBox      = $('.basic.checkbox input:checked');
        var deleteCondition = checkedBox.length > 0;
        var postsId       = [];
        $("#delete").toggleClass("hidden", !deleteCondition);
        checkedBox.each(function() {
            postsId.push(parseInt($(this).val()));
        })
        postsId = postsId.length ? JSON.stringify(postsId) : '';
        $('#delete')[0].href = '/admin/tags/delete/' + postsId;

    });

    $(".checkBoxClass").change(function(){
        if (!$(this).prop("checked")){
            $("#ckbCheckAll").prop("checked",false);
        }
    });
</script>

@endsection
