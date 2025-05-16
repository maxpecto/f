@auth
    @if($items->isWatchlistedBy(current_user()) == 'true')
        <button type="submit" data-id="{{ $items->id }}" id="watchlist" class="w-full tracking-widest">
            <div class="flex items-center bg-red-900 text-white rounded p-2 space-x-1 justify-center font-medium">
                <span class="iconify" data-icon="fluent:bookmark-off-24-filled" data-inline="false" data-width="23" data-height="23"></span><span class="text-sm uppercase">{{ __("Remove Watchlist") }}</span>
            </div>
        </button>
    @else
        <button type="submit" data-id="{{ $items->id }}" id="watchlist" class="w-full tracking-widest">
            <div class="flex items-center bg-gray-900 rounded p-2 space-x-1 justify-center font-medium">
                <span class="iconify" data-icon="fluent:bookmark-add-24-filled" data-inline="false" data-width="23" data-height="23"></span><span class="text-sm uppercase">{{ __("Add Watchlist") }}</span>
            </div>
        </button>
    @endif

    <script>
        //adding to watchlist
        $('#watchlist').on('click', function(event) {
            event.preventDefault();
            var id          = parseInt($(this).attr('data-id'));
            var _token = $('input[name="_token"]').val();

            var data        = {'id': id, '_token' : _token};
            var ajaxReqUrl  = '/items/{!! $items->id !!}/watchlist';
            $.ajax({
                method: 'POST',
                url: ajaxReqUrl,
                data: data
            })
            .done(function(result) {
                if(result.watchlist == true){
                    document.getElementById("watchlist").innerHTML = '<div class="flex items-center bg-red-900 text-white rounded p-2 space-x-1 justify-center font-medium"><span class="iconify" data-icon="fluent:bookmark-off-24-filled" data-inline="false" data-width="23" data-height="23"></span><span class="text-sm uppercase">{{ __("Remove Watchlist") }}</span></div>';
                }else if(result.watchlist == false){
                    document.getElementById("watchlist").innerHTML = '<div class="flex items-center bg-gray-900 rounded p-2 space-x-1 justify-center font-medium"><span class="iconify" data-icon="fluent:bookmark-add-24-filled" data-inline="false" data-width="23" data-height="23"></span><span class="text-sm uppercase">{{ __("Add Watchlist") }}</span></div>';
                }else{
                    console.log('error adding or removing from watchlist!');
                }
            });
        });
    </script>
@endauth
