<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\Episodes;
use App\Models\Comments;
use App\Models\User;

use App\Models\Pageviews;
use App\Models\TotalPageviews;

use Carbon\Carbon;
use DB;

class DashboardController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
    	$movies_count = Items::where('type','movies')->count();
    	$series_count = Items::where('type','series')->count();
    	$episodes_count = Episodes::count();
        $users_count = User::count();

        $most_views_items = Items::orderBy('views', 'DESC')->take(20)->get();
        $latest_users = User::orderBy('id', 'DESC')->take(10)->get();
        $latest_comments = Comments::orderBy('id', 'DESC')->take(10)->get();



        //MAPS
        $num = TotalPageviews::sum(DB::raw('total'));
        if($num>1000) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
            $totalpagevies = $x_display;
        }else{
            $totalpagevies = $num;
        }
        $num1 = Pageviews::count();
        if($num1>1000) {
            $x = round($num1);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
            $pageviwes = $x_display;
        }else{
            $pageviwes = $num1;
        }
        $jsMaps = Pageviews::all();
        $code = array("BD","BE","BF","BG","BA","BN","BO","JP","BI","BJ","BT","JM","BW","BR","BS","BY","BZ","RU","RW","RS","TL","TM","TJ","RO","GW","GT","GR","GQ","GY","GE","GB","GA","GN","GM","GL","GH","OM","TN","JO","HR","HT","HU","HN","PR","PS","PT","PY","PA","PG","PE","PK","PH","PL","ZM","EH","EE","EG","ZA","EC","IT","VN","SB","ET","SO","ZW","ES","ER","ME","MD","MG","MA","UZ","MM","ML","MN","MK","MW","MR","UG","MY","MX","IL","FR","XS","FI","FJ","FK","NI","NL","NO","NA","VU","NC","NE","NG","NZ","NP","XK","CI","CH","CO","CN","CM","CL","XC","CA","CG","CF","CD","CZ","CY","CR","CU","SZ","SY","KG","KE","SS","SR","KH","SV","SK","KR","SI","KP","KW","SN","SL","KZ","SA","SE","SD","DO","DJ","DK","DE","YE","DZ","US","UY","LB","LA","TW","TT","TR","LK","LV","LT","LU","LR","LS","TH","TF","TG","TD","LY","AE","VE","AF","IQ","IS","IR","AM","AL","AO","AR","AU","AT","IN","TZ","AZ","IE","ID","UA","QA","MZ");
        $jsMaps = [];
        foreach($code as $code_id){
            $jsMaps[] = [
                strtolower($code_id),
                Pageviews::where('country_code',$code_id)->count()
            ];
        }
        $popularcountry = collect($jsMaps)->sortByDesc(1)->take(5);

        $robots_name = DB::table('pageviews')
            ->select('robot_name', DB::raw('COUNT(*) as `count`'))
            ->groupBy('robot_name')
            ->havingRaw('COUNT(*) > 0')->orderBy('count', 'DESC')
            ->get();

        $browser_name = DB::table('pageviews')
            ->select('browser', DB::raw('COUNT(*) as `count`'))
            ->groupBy('browser')
            ->havingRaw('COUNT(*) > 0')->orderBy('count', 'DESC')
            ->get();

        $os_name = DB::table('pageviews')
            ->select('platform', DB::raw('COUNT(*) as `count`'))
            ->groupBy('platform')
            ->havingRaw('COUNT(*) > 0')->orderBy('count', 'DESC')
            ->get();

        return view('backend.home',compact('movies_count','series_count','episodes_count','users_count','most_views_items','latest_users','latest_comments','totalpagevies','pageviwes','jsMaps','code','popularcountry','robots_name','browser_name','os_name'));
    }

    public function chart(Request $request){
        $dates = date("t");

        if($dates == 31){
            $traffic_chart_days = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
        }elseif($dates == 30){
            $traffic_chart_days = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30];
        }elseif($dates == 28){
            $traffic_chart_days = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28];
         }elseif($dates == 29){
            $traffic_chart_days = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29];
        }

        $unique_visit = array();

        foreach($traffic_chart_days as $days){
            if(Pageviews::where('visit_date',date("Y-m-").$days)->count()){
                $unique_visit[] .= Pageviews::where('visit_date',date("Y-m-").$days)->count();
            }else{
                $unique_visit[] .= 0;
            }
        }

        $string = implode(',', $unique_visit);
        $json = "[" . trim($string) . "]";
        $unique_visitor = json_decode($json, true);
        $unique_pageviews = array();

        foreach($traffic_chart_days as $days){
            if(TotalPageviews::where('visit_date',date("Y-m-").$days)->value('total')){
                $valuee = TotalPageviews::where('visit_date',date("Y-m-").$days)->value('total');

                $unique_pageviews[] .= $valuee;
            }else{
                $unique_pageviews[] .= 0;
            }
        }

        $string1 = implode(',', $unique_pageviews);
        $json1 = "[" . trim($string1) . "]";
        $total_pageviews = json_decode($json1, true);

        return response()->json(compact('traffic_chart_days','unique_visitor','total_pageviews'));
    }


}
