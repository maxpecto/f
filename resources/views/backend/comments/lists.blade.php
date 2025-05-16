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
    <span>Comments</span><span>Total Comments : {{ count($total_comments)  }}</span>
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
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Title</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-left">Comments</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">Approve</th>
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
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-cenleftter w-20">
                                <div>
                                    <div class="flex items-center">
                                        <strong>
                                        @if($row->type == 0)
                                            Movie
                                         @elseif($row->type == 1)
                                            Series
                                        @else
                                            Episode
                                        @endif
                                        </strong>
                                    </div>
                                <div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-cenleftter w-70">
                                <div class="w-full">
                                    <div class="flex items-center w-full">

                                        @if($row->type == 0)
                                            <img class="h-10 md:h-20 mr-5 hidden xl:block rounded" src="/assets/movies/backdrop/movie_image_{{ @$row->items[0]->id }}.jpg"/>
                                        @elseif($row->type == 1)
                                            <img class="h-10 md:h-20 mr-5 hidden xl:block rounded" src="/assets/series/backdrop/series_image_{{ @$row->items[0]->id }}.jpg"/>
                                        @else
                                            <img class="h-10 md:h-20 mr-5 hidden xl:block rounded" src="/assets/episodes/backdrop/episodes_image_{{ $row->episodes[0]->id }}.jpg"/>
                                        @endif

                                        <div class="flex-wrap  w-full">
                                            @if($row->type == 0)
                                            <strong class="flex overflow-hidden">
                                                {{ @$row->items[0]->title }}
                                            </strong>
                                            @elseif($row->type == 1)
                                            <strong class="flex overflow-hidden">
                                                {{ @$row->items[0]->title }}
                                            </strong>
                                            @else
                                            <strong class="flex overflow-hidden">
                                                {{ $row->episodes[0]->series->title }} (S{{ $row->episodes[0]->season_id }}E{{ $row->episodes[0]->episode_id }})
                                            </strong>
                                            @endif
                                            <div class="text-xs mt-2 mb-2"><strong>Comment added</strong> {{ $row->created_at }}</div>
                                            <div class="text-xs mt-2 mb-2"><strong>by
                                                <a target="_blank" href="/&#64;{{ getUsername($row->users_id)->username }}">{{ getUsername($row->users_id)->username }}</a></strong>
                                            </div>
                                        </div>
                                    </div>
                                <div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left w-96">
                                <div class="flex-wrap items-center">
                                    <p class="text-xs hidden xl:block">{{ $row->comments }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <label class="approve checkbox">
                                    <input value="{{ $row->id  }}" type="checkbox" @if($row->approve == 1) checked @endif>
                                </label>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <div class="flex space-x-2 items-center float-right">
                                    <a href="{{ url('/admin/comments/delete/['.$row->id.']') }}" class="px-4 py-2 bg-red-500 uppercase text-white rounded flex items-center"><span class="iconify mr-1" data-icon="fluent:delete-dismiss-28-filled" data-inline="false"></span></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="9" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center"><div class="ui orange message"><b>There is no comments!</b></div></td></tr>
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
        $('#delete')[0].href = '/admin/episodes/delete/' + postsId;
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
        $('#delete')[0].href = '/admin/episodes/delete/' + postsId;

    });

    $(".checkBoxClass").change(function(){
        if (!$(this).prop("checked")){
            $("#ckbCheckAll").prop("checked",false);
        }
    });

    $('.approve.checkbox input').on('change', function() {
        var approve     = $(this).prop('checked') ? 1 : 0;
        var id      = parseInt($(this).val());

        if(/^(0|1)$/.test(approve) && /^(\d+)$/.test(id))
        {
            var _token = $('input[name="_token"]').val();
            var data        = {'id': id, 'approve': approve, '_token' : _token};
            var ajaxReqUrl  = '/admin/comments/approve';

            $.post(ajaxReqUrl, data);
        }
    });
</script>
@endsection
