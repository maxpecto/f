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
    <span>Reports</span><span>Pendings Reports : {{ count($total_reports) }}</span>
</div>
<section class="w-full text-white rounded-b">

    <div class="flex bg-gray-800 justify-between items=center p-5">
        <div class="flex space-x-2 items-center">
            <a href="#" id="delete" class="px-4 py-2 bg-red-500 uppercase text-white rounded flex items-center hidden transition delay-150 duration-300 ease-in-out"><span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span> Delete Selected</a>
        </div>
    </div>

    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 overflow-x-scroll">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">
                        <input type="checkbox" id="ckbCheckAll" />
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Type</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Report Type</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Description</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">Solved</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-900">
                @if(!$data->isEmpty())
                    @foreach($data as $i=>$row)
                        <tr class="overflow-x-scroll">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <div class="basic checkbox">
                                    <input type="checkbox" class="checkBoxClass" value="{{ $row->id  }}" tabindex="0" name="">
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white text-sm text-left">
                                @if($row->items_type == 0)
                                    <span class="bg-gray-900 px-2 py-1 text-yellow-400 rounded text-xs mb-1">Movie</span>
                                @elseif($row->items_type == 1)
                                    <span class="bg-gray-900 px-2 py-1 text-yellow-400 rounded text-xs mb-1">Series</span>
                                @elseif($row->items_type == 2)
                                    <span class="bg-gray-900 px-2 py-1 text-yellow-400 rounded text-xs mb-1">Episode</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-cenleftter w-60">
                                <div class="flex items-center">
                                    @if($row->items_type == 0)
                                        @if($row->type == 1)
                                            <div class="flex flex-col ">
                                                <span class="text-green-400 font-bold">The Video Not Working</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/movies/edit/{{$row->items_id}}">Edit Movie</a></span>
                                            </div>
                                        @elseif($row->type == 2)
                                            <div class="flex flex-col w-50">
                                                <span class="text-green-400 font-bold">Subtitle error</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/movies/edit/{{$row->items_id}}">Edit Movie</a></span>
                                            </div>
                                        @else
                                            <div class="flex flex-col w-50">
                                                <span class="text-green-400 font-bold">Other</span>v
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/movies/edit/{{$row->items_id}}">Edit Movie</a></span>
                                            </div>
                                        @endif
                                    @elseif($row->items_type == 1)
                                        @if($row->type == 1)
                                            <div class="flex flex-col ">
                                                <span class="text-green-400 font-bold">The Video Not Working</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/series/edit/{{$row->items_id}}">Edit Series</a></span>
                                            </div>
                                        @elseif($row->type == 2)
                                            <div class="flex flex-col w-50">
                                                <span class="text-green-400 font-bold">Subtitle error</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/series/edit/{{$row->items_id}}">Edit Series</a></span>
                                            </div>
                                        @else
                                            <div class="flex flex-col w-50">
                                                <span class="text-green-400 font-bold">Other</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/series/edit/{{$row->items_id}}">Edit Series</a></span>
                                            </div>
                                        @endif
                                    @elseif($row->items_type == 2)
                                        @if($row->type == 1)
                                            <div class="flex flex-col ">
                                                <span class="text-green-400 font-bold">The Video Not Working</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/episodes/edit/{{$row->items_id}}">Edit Episode</a></span>
                                            </div>
                                        @elseif($row->type == 2)
                                            <div class="flex flex-col w-50">
                                                <span class="text-green-400 font-bold">Subtitle error</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/episodes/edit/{{$row->items_id}}">Edit Episode</a></span>
                                            </div>
                                        @else
                                            <div class="flex flex-col w-50">
                                                <span class="text-green-400 font-bold">Other</span>
                                                <span class="text-xs">Reported at : {{ $row->created_at }}</span>
                                                <span class="text-red-400 font-medium"><a href="/admin/episodes/edit/{{$row->items_id}}">Edit Episode</a></span>
                                            </div>
                                        @endif
                                     @endif
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-cenleftter">
                                <span class="text-sm">{{$row->desc}}</span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <label class="solved checkbox">
                                    <input value="{{ $row->id  }}" type="checkbox" @if($row->solve == 1) checked @endif>
                                </label>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <div class="flex space-x-2 items-center float-right">
                                    <a href="{{ url('/admin/reports/delete/['.$row->id.']') }}" class="px-4 py-2 bg-red-500 uppercase text-white rounded flex items-center"><span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span> Delete</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center"><div class="ui orange message"><b>There haven't any reports!</b></div></td></tr>
                @endif
            </tbody>
            <tfoot>
				<tr>
					<th colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
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
        $('#delete')[0].href = '/admin/reports/delete/' + postsId;

    });

    $(".checkBoxClass").change(function(){
        if (!$(this).prop("checked")){
            $("#ckbCheckAll").prop("checked",false);
        }
    });

    $('.solved.checkbox input').on('change', function() {
        var solved     = $(this).prop('checked') ? 1 : 0;
        var id      = parseInt($(this).val());

        if(/^(0|1)$/.test(solved) && /^(\d+)$/.test(id))
        {
            var _token = $('input[name="_token"]').val();
            var data        = {'id': id, 'solved': solved, '_token' : _token};
            var ajaxReqUrl  = '/admin/reports/solved';

            $.post(ajaxReqUrl, data);
        }
    });


</script>
@endsection
