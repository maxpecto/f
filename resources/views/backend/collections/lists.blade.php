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
    <span>Collections</span><span>Total Collections : {{ count($total_collections)  }}</span>
</div>
<section class="w-full text-white rounded-b">

    <div class="flex bg-gray-800 justify-between items=center p-5">
        <div class="flex space-x-2 items-center">
            <a href="#" id="delete" class="px-4 py-2 bg-red-500 uppercase text-white rounded flex items-center hidden transition delay-150 duration-300 ease-in-out"><span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span> Delete Selected</a>
        </div>
        <div class="flex space-x-2 items-center">
            <a href="/admin/collections/add" class="px-4 py-2 bg-green-500 uppercase text-white rounded focus:outline-none flex items-center"><span class="iconify mr-1" data-icon="gridicons:create" data-inline="false"></span> Add Collections</a>
        </div>
    </div>

    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 overflow-x-scroll">
        <div>
            {!! Form::open(['route' => ['admin.collections'] ,'method' => 'get' ,'class' => 'flex items-center mb-5 w-full relative']) !!}
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
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Collections Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Lists of Items</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Views</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">Visible</th>
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
                                <div>
                                    <div class="flex items-center">
                                        <div class="flex-wrap">
                                            <strong class="flex overflow-hidden">{{ $row->name }}</strong>
                                            <div class="text-xs mt-2 mb-2"><strong>Created at</strong> {{ $row->created_at }}</div>
                                        </div>
                                    </div>
                                <div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-cenleftter leading-9">
                                @foreach ($row->items as $singlemovie)
                                    <span class="px-2 py-1 rounded bg-yellow-400 text-white">{{ $singlemovie->title }}</span>
                                @endforeach
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-cenleftter">
                                <strong>{{ $row->views }}</strong>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <label class="visible checkbox">
                                    <input value="{{ $row->id  }}" type="checkbox" @if($row->visible == 1) checked @endif>
                                </label>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <div class="flex space-x-2 items-center float-right">
                                    <a href="{{ url('/admin/collections/edit/'.$row->id) }}" class="px-4 py-2 bg-green-500 uppercase text-white rounded focus:outline-none flex items-center"><span class="iconify mr-1" data-icon="akar-icons:edit" data-inline="false"></span> Edit</button>

                                    <a href="{{ url('/admin/collections/delete/['.$row->id.']') }}" class="px-4 py-2 bg-red-500 uppercase text-white rounded flex items-center"><span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span> Delete</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="9" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center"><div class="ui orange message"><b>There haven't any collections!</b></div></td></tr>
                @endif
            </tbody>
            <tfoot>
				<tr>
					<th colspan="9" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
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
        $('#delete')[0].href = '/admin/collections/delete/' + postsId;
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
        $('#delete')[0].href = '/admin/collections/delete/' + postsId;

    });

    $(".checkBoxClass").change(function(){
        if (!$(this).prop("checked")){
            $("#ckbCheckAll").prop("checked",false);
        }
    });

    $('.visible.checkbox input').on('change', function() {
        var visible     = $(this).prop('checked') ? 1 : 0;
        var id      = parseInt($(this).val());

        if(/^(0|1)$/.test(visible) && /^(\d+)$/.test(id))
        {
            var _token = $('input[name="_token"]').val();
            var data        = {'id': id, 'visible': visible, '_token' : _token};
            var ajaxReqUrl  = '/admin/collections/visible';

            $.post(ajaxReqUrl, data);
        }
    });
</script>
@endsection
