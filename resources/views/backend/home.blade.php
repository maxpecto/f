@extends('layouts.backend')

@section('content')

<div class="w-full p-5 text-white bg-gray-900 rounded-t">Dashboard</div>
<section class="w-full p-5 bg-gray-800 text-white rounded-b ">

    <div class="bg-gray-800 p-5 w-full space-y-10">
        {{-- Total Count --}}
        <div class="grid xl:grid-cols-4 lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 grid-cols-1 gap-3 w-full">
            <div class="flex items-center bg-gray-700 hover:bg-gray-900 justify-bewteen w-full">
                <div class="bg-gray-600 p-5">
                    <span class="iconify" data-icon="bx:bx-movie" data-inline="false" data-width="34" data-height="34"></span>
                </div>
                <a href="/admin/movies" class="flex items-center flex justify-between px-4 py-2 justify-bewteen w-full">
                    <div class="flex flex-col">
                        <span class="text-2xl uppercase">Movies</span>
                        <span class="text-xs text-yellow-500">Total Movies</span>
                    </div>
                    <div>
                        <span class="text-3xl">{{ $movies_count }}</span>
                    </div>
                </a>
            </div>
            <div class="flex items-center bg-gray-700 hover:bg-gray-900 justify-bewteen w-full">
                <div class="bg-gray-600 p-5">
                    <span class="iconify" data-icon="bx:bx-movie-play" data-inline="false" data-width="34" data-height="34"></span>
                </div>
                <a href="/admin/series" class="flex items-center flex justify-between px-4 py-2 justify-bewteen w-full">
                    <div class="flex flex-col">
                        <span class="text-2xl uppercase">Series</span>
                        <span class="text-xs text-yellow-500">Total Series</span>
                    </div>
                    <div>
                        <span class="text-3xl">{{ $series_count }}</span>
                    </div>
                </a>
            </div>
            <div class="flex items-center bg-gray-700 hover:bg-gray-900 justify-bewteen w-full">
                <div class="bg-gray-600 p-5">
                    <span class="iconify" data-icon="ic:baseline-movie-filter" data-inline="false" data-width="34" data-height="34"></span>
                </div>
                <a href="/admin/episodes" class="flex items-center flex justify-between px-4 py-2 justify-bewteen w-full">
                    <div class="flex flex-col">
                        <span class="text-2xl uppercase">Episodes</span>
                        <span class="text-xs text-yellow-500">Total Episodes</span>
                    </div>
                    <div>
                        <span class="text-3xl">{{ $episodes_count }}</span>
                    </div>
                </a>
            </div>
            <div class="flex items-center bg-gray-700 hover:bg-gray-900 justify-bewteen w-full">
                <div class="bg-gray-600 p-5">
                    <span class="iconify" data-icon="gridicons:multiple-users" data-inline="false" data-width="34" data-height="34"></span>
                </div>
                <a href="/admin/users" class="flex items-center flex justify-between px-4 py-2 justify-bewteen w-full">
                    <div class="flex flex-col">
                        <span class="text-2xl uppercase">Users</span>
                        <span class="text-xs text-yellow-500">Total Users</span>
                    </div>
                    <div>
                        <span class="text-3xl">{{ $users_count }}</span>
                    </div>
                </a>
            </div>
        </div>
        {{-- daily visit and most visit items --}}
        <div class="w-full xl:flex w-full">
            <div class="w-full xl:w-3/5 mr-0 xl:mr-5 mb-5 xl:mb-0">
                <!-- myChart -->
                <div class="flex space-x-2 mb-2 items-center">
                    <span class="iconify" data-icon="gridicons:stats-up-alt" data-width="38" data-height="38"></span>
                    <div class="flex flex-col">
                        <span class="text-2xl">Daily Visits</span>
                        <span class="text-sm">Unique & Non-Unique Visits</span>
                    </div>
                </div>
                <div class="px-4 py-2 w-full bg-gray-700 rounded">
                    <canvas id="myChart" class="w-full" style="height: 320px; min-width: 100%; display: block;"></canvas>
                </div>
            </div>
            <div class="w-full xl:w-2/5">
                <div class="flex space-x-2 mb-2 items-center">
                    <span class="iconify" data-icon="eva:trending-up-fill" data-width="38" data-height="38"></span>
                    <div class="flex flex-col">
                        <span class="text-2xl">Most Visited Items</span>
                        <span class="text-sm">Top 20 Popular Items</span>
                    </div>
                </div>
                <div class="w-full bg-gray-700 rounded" style="height: 335px; overflow-y:auto;">
                    <table class="w-full">
						<thead>
							<tr>
								<th class="bg-gray-700 text-left px-4 py-4">Items</th>
								<th class="bg-gray-700 text-left px-4 py-4">Views</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($most_views_items as $most_views)
							<tr class="border-b-2 border-gray-700 bg-gray-600 hover:bg-gray-900">
								<td class="text-left px-4 py-2">
                                    @if($most_views->type == 'movies')
                                        <div class="flex flex-col">
                                            <a href="{{ url('movie/'.$most_views->slug) }}" title="">{{ $most_views->title }}</a>
                                            <span class="text-xs text-yellow-500">Movie</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col">
                                            <a href="{{ url('series/'.$most_views->slug) }}" title="">{{ $most_views->title }}</a>
                                            <span class="text-xs text-yellow-500">Series</span>
                                        </div>

                                    @endif
								</td>
								<td class="text-left px-4 py-2">{{ $most_views->views }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
        </div>
        {{-- user and comments --}}
        <div class="w-full xl:flex w-full">
            <div class="w-full xl:w-6/12 mr-0 xl:mr-5 mb-5 xl:mb-0">
                <div class="flex mb-2 items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="iconify" data-icon="gridicons:multiple-users" data-width="38" data-height="38"></span>
                        <div class="flex flex-col">
                            <span class="text-2xl">Users</span>
                            <span class="text-sm">Latest Registered Users</span>
                        </div>
                    </div>
                    <div class="">
                        <a href="/admin/users" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-900">View All</a>
                    </div>
                </div>
                <div class="w-full bg-gray-700 rounded" style="height: 335px; overflow-y:auto;">
                    <table class="w-full">
						<thead>
							<tr>
								<th class="bg-gray-700 text-left px-4 py-4">Name</th>
								<th class="bg-gray-700 text-left px-4 py-4">Username</th>
								<th class="bg-gray-700 text-left px-4 py-4">Email</th>
								<th class="bg-gray-700 text-left px-4 py-4">Created at</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($latest_users as $latest_user)
							<tr class="border-b-2 border-gray-700 bg-gray-600 hover:bg-gray-900">
								<td class="text-left px-4 py-2">{{ $latest_user->fname }} {{ $latest_user->lname }}</td>
								<td class="text-left px-4 py-2"><a target="_blank" href="/&#64;{{$latest_user->username}}">&#64;{{ $latest_user->username }}</a></td>
								<td class="text-left px-4 py-2">{{ $latest_user->email }}</td>
								<td class="text-left px-4 py-2">{{ $latest_user->created_at }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
            <div class="w-full xl:w-6/12">
                <div class="flex mb-2 items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="iconify" data-icon="akar-icons:comment" data-width="38" data-height="38"></span>
                        <div class="flex flex-col">
                            <span class="text-2xl">Comments</span>
                            <span class="text-sm">Latest Comments</span>
                        </div>
                    </div>
                    <div class="">
                        <a href="/admin/comments" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-900">View All</a>
                    </div>
                </div>
                <div class="w-full bg-gray-700 rounded" style="height: 335px; overflow-y:auto;">
                    <table class="w-full">
						<thead>
							<tr>
								<th class="bg-gray-700 text-left px-4 py-4">TYPE</th>
								<th class="bg-gray-700 text-left px-4 py-4">TITLE</th>
								<th class="bg-gray-700 text-left px-4 py-4">COMMENTS</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($latest_comments as $latest_comment)
							<tr class="border-b-2 border-gray-700 bg-gray-600 hover:bg-gray-900">
								<td class="text-left px-4 py-2">
                                    @if($latest_comment->type == 0)
                                        Movie
                                    @elseif($latest_comment->type == 1)
                                        Series
                                    @else
                                        Episode
                                    @endif
                                </td>
								<td class="text-left px-4 py-2">
                                    <div class="flex-wrap  w-full">
                                        @if($latest_comment->type == 0)
                                        <strong class="flex overflow-hidden">
                                            {{ @$latest_comment->items[0]->title }}
                                        </strong>
                                        @elseif($latest_comment->type == 1)
                                        <strong class="flex overflow-hidden">
                                            {{ @$latest_comment->items[0]->title }}
                                        </strong>
                                        @else
                                        <strong class="flex overflow-hidden">
                                            {{ $latest_comment->episodes[0]->series->title }} (S{{ $latest_comment->episodes[0]->season_id }}E{{ $latest_comment->episodes[0]->episode_id }})
                                        </strong>
                                        @endif
                                        <div class="text-xs mt-2 mb-2"><strong>Comment added</strong> {{ $latest_comment->created_at }}</div>
                                        <div class="text-xs mt-2 mb-2"><strong>by
                                            <a target="_blank" href="/&#64;{{ getUsername($latest_comment->users_id)->username }}">{{ getUsername($latest_comment->users_id)->username }}</a></strong>
                                        </div>
                                    </div>
                                </td>
								<td class="text-left px-4 py-2">{{ $latest_comment->comments }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
        </div>
        {{-- map and browser type --}}
        <div class="w-full xl:flex w-full">
            <div class="w-full xl:w-3/5 mr-0 xl:mr-5 mb-5 xl:mb-0">
                <div class="flex space-x-2 mb-2 items-center">
                    <span class="iconify" data-icon="fontisto:earth" data-width="38" data-height="38"></span>
                    <div class="flex flex-col">
                        <span class="text-2xl">Traffic Origin</span>
                        <span class="text-sm">Traffic By Country</span>
                    </div>
                </div>
                <div class="w-full bg-gray-700">
                    <div id="world-map"></div>
                </div>
                <div class="w-full bg-gray-700 rounded-b p-4 flex items-center space-x-2">
                    <div class="">Top locations</div>
                    @foreach($popularcountry as $country)
                        <span class="p-2 bg-gray-600 rounded hover:bg-gray-900">{{ strtoupper($country[0]) }} : {{ $country[1] }}</span>
                    @endforeach
                </div>
            </div>
            <div class="w-full xl:w-2/5">
                <div class="flex space-x-2 mb-2 items-center">
                    <span class="iconify" data-icon="ion:browsers" data-width="38" data-height="38"></span>
                    <div class="flex flex-col">
                        <span class="text-2xl">Browsers</span>
                        <span class="text-sm">Browsers list</span>
                    </div>
                </div>
                <div class="w-full bg-gray-700 rounded" style="height: 365px; overflow-y:auto;">
                    <table class="w-full">
						<thead>
							<tr>
								<th class="bg-gray-700 text-left px-4 py-4">Browser</th>
								<th class="bg-gray-700 text-left px-4 py-4">Total Visits</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($browser_name as $bname)
							<tr class="border-b-2 border-gray-700 bg-gray-600 hover:bg-gray-900">
								<td class="text-left px-4 py-2">
                                    @if($bname->browser == '0'  || $bname->browser == '')
                                        Unknown
                                    @else
                                        {{ $bname->browser }}
                                    @endif
								</td>
								<td class="text-left px-4 py-2">{{ $bname->count }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
        </div>
        {{-- robots and OS --}}
        <div class="w-full xl:flex w-full">
            <div class="w-full xl:w-6/12 mr-0 xl:mr-5 mb-5 xl:mb-0">
                <div class="flex mb-2 items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="iconify" data-icon="file-icons:robots" data-width="38" data-height="38"></span>
                        <div class="flex flex-col">
                            <span class="text-2xl">Web robots scraper</span>
                            <span class="text-sm">Robots viewing your website</span>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-gray-700 rounded" style="height: 335px; overflow-y:auto;">
                    <table class="w-full">
						<thead>
							<tr>
								<th class="bg-gray-700 text-left px-4 py-4">Robot</th>
								<th class="bg-gray-700 text-left px-4 py-4">Total Visits</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($robots_name as $rname)
							<tr class="border-b-2 border-gray-700 bg-gray-600 hover:bg-gray-900">
								<td class="text-left px-4 py-2">
                                        @if($rname->robot_name == '0' || $rname->robot_name == '')
                                        Unknown
                                    @else
                                        {{ $rname->robot_name }}
                                    @endif
                                </td>
								<td class="text-left px-4 py-2">{{ $rname->count }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
            <div class="w-full xl:w-6/12">
                <div class="flex mb-2 items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="iconify" data-icon="wpf:android-os" data-width="38" data-height="38"></span>
                        <div class="flex flex-col">
                            <span class="text-2xl">Operating Systems</span>
                            <span class="text-sm">OS List</span>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-gray-700 rounded" style="height: 335px; overflow-y:auto;">
                    <table class="w-full">
						<thead>
							<tr>
								<th class="bg-gray-700 text-left px-4 py-4">OS</th>
								<th class="bg-gray-700 text-left px-4 py-4">Total Visits</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($os_name as $osname)
							<tr class="border-b-2 border-gray-700 bg-gray-600 hover:bg-gray-900">
								<td class="text-left px-4 py-2">{{ $osname->platform }}</td>
								<td class="text-left px-4 py-2">{{ $osname->count }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
@php
$jobs1 = json_encode($jsMaps);
$jobs2 = str_replace("],[",",",$jobs1);
$jobs3 = str_replace("\",","\":",$jobs2);
$jobs4 = str_replace("[[","{",$jobs3);
$jobs = str_replace("]]","}",$jobs4);
@endphp

<script>
	window.worldMapData = {!! $jobs !!};
</script>

<script>
    //myChart
	var ctx = document.getElementById("myChart").getContext('2d');
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    responsive: false,
	    data: {
	      	labels: [],
			datasets: [{
				label: 'Unique visits',
				backgroundColor: '#e3a008',
				data: []
			}, {
				label: 'Non-unique visits',
				backgroundColor: '#ffe3a4',
				data: []
			}]
	    },
	    options: {
	    	tooltips: {
				mode: 'index',
				intersect: false,
				backgroundColor: '#fff',
				cornerRadius: 0,
				bodyFontColor: '#000',
				titleFontColor: '#000',
				legendColorBackground: '#000'
			},
	      	responsive: false,
			maintainAspectRatio: false,
			scales: {
				xAxes: [{
					stacked: true,
				}],
				yAxes: [{
					stacked: false
				}]
			}
	    }
	});

	var updateChart = function() {
	    $.ajax({
	      url: "{{ route('admin.api.chart') }}",
	      type: 'GET',
	      dataType: 'json',
	      headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	      },
	      success: function(data) {
	        myChart.data.labels = data.traffic_chart_days;
	        myChart.data.datasets[0]['data'] = data.unique_visitor;
	        myChart.data.datasets[1]['data'] = data.total_pageviews;

	        myChart.update();
	      },
	      error: function(data){
	        console.log(data);
	      }
	    });
	}
	updateChart();

    // WORDMAP
	$('#world-map').vectorMap({
        map: 'world_en',
        backgroundColor: "#fff",
        enableZoom: false,
        showTooltip: false,
        series: {
            regions: [{
              values: worldMapData,
              scale: ['#ffe3a4', '#e3a008'],
              normalizeFunction: 'polynomial'
            }]
        },
        onRegionTipShow: (e, el, code)=> {
          el.html(el.html() + ':' +  worldMapData[code] + ' visits');
        }
    });
</script>

@endsection
