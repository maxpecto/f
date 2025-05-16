
<button type="submit" class="focus:outline-none" id="like" data-id="{{ $items->id }}">
    <div class="like flex items-center space-x-1 @auth {{  $items->isLikedBy(current_user()) ? 'text-yellow-400' : 'text-white'  }} @endauth"><span class="iconify" data-icon="ant-design:like-filled" data-inline="false"></span><span id="count_like">{{ $totalLikes }}</span></div>
</button>

<button type="submit" class="focus:outline-none" id="dislike" data-id="{{ $items->id }}">
    <div class="dislike flex items-center space-x-1 @auth {{ $items->isDislikedBy(current_user()) ? 'text-yellow-400' : 'text-white' }} @endauth"><span class="iconify" data-icon="ant-design:dislike-filled" data-inline="false"></span><span id="count_dislike">{{ $totalDislikes }}</span></div>
</button>

<script>
    //Liking
    $('#like').on('click', function(event) {
        event.preventDefault();
        var id          = parseInt($(this).attr('data-id'));
        var _token = $('input[name="_token"]').val();

        var data        = {'id': id, '_token' : _token};
        var ajaxReqUrl  = '/items/{!! $items->id !!}/like';
        $.ajax({
            method: 'POST',
            url: ajaxReqUrl,
            data: data
        })
        .done(function(result) {
            if(result.like == true){
                $('.dislike').removeClass("text-yellow-400");
                document.getElementById("like").innerHTML = '<div class="like flex items-center space-x-1 text-yellow-400"><span class="iconify" data-icon="ant-design:like-filled" data-inline="false"></span><span id="count_like">'+result.total_like+'</span></div>';
                document.getElementById('count_dislike').innerHTML = result.total_dislike;
            }
        });
    });
    //Disliking
    $('#dislike').on('click', function(event) {
        event.preventDefault();
        var id          = parseInt($(this).attr('data-id'));
        var _token = $('input[name="_token"]').val();

        var data        = {'id': id, '_token' : _token};
        var ajaxReqUrl  = '/items/{!! $items->id !!}/like';
        $.ajax({
            method: 'DELETE',
            url: ajaxReqUrl,
            data: data
        })
        .done(function(result) {
            if(result.dislike == true){
                $('.like').removeClass("text-yellow-400");
                document.getElementById("dislike").innerHTML = '<div class="dislike flex items-center space-x-1 text-yellow-400"><span class="iconify" data-icon="ant-design:dislike-filled" data-inline="false"></span><span id="count_dislike">'+result.total_dislike+'</span></div>';
                document.getElementById('count_like').innerHTML = result.total_like;
            }
        });
    });
</script>
