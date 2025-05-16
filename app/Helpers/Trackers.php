<?php

namespace App\Helpers;

use Request;
use App\Models\Pageviews;
use App\Models\TotalPageviews;
use Carbon\Carbon;

use Jenssegers\Agent\Agent;

class Trackers
{
    public static function track_agent(){
		//Start Tracker
    	$agent = new Agent();
        $today = strtotime(Carbon::now()->toDateTimeString());
        $ipaddress = Request::ip();
        $resultip = Pageviews::where('ip_address',$ipaddress)->count();

        $details = \Location::get($ipaddress);

        $pageviews = new Pageviews();
        if($resultip < 1){
            $pageviews->ip_address = $ipaddress;
            if(isset($details->countryCode)){
                $pageviews->country_code = $details->countryCode;
            }else{
                $pageviews->country_code = '';
            }

            $pageviews->browser = $agent->browser();
            $pageviews->platform = $agent->platform().' - '.$agent->version($agent->platform());
            $pageviews->is_robot = $agent->isRobot();

            if($agent->isRobot() == 0){

            }else{
            	$pageviews->robot_name = $agent->robot();
            }

            $pageviews->visit_date = Carbon::now();
            $pageviews->save();
        }else{
            if(Pageviews::where('visit_date','=',date('Y-m-d',$today))->count() < 1){
                $pageviews->ip_address = $ipaddress;
                if(isset($details->countryCode)){
                    $pageviews->country_code = $details->countryCode;
                }else{
                    $pageviews->country_code = '';
                }

                $pageviews->browser = $agent->browser();
	            $pageviews->platform = $agent->platform().' - '.$agent->version($agent->platform());
	            $pageviews->is_robot = $agent->isRobot();

	            if($agent->isRobot() == 0){

	            }else{
	            	$pageviews->robot_name = $agent->robot();
	            }

                $pageviews->visit_date = Carbon::now();
                $pageviews->save();
            }
        }
        if(TotalPageviews::where('visit_date','=',date('Y-m-d',$today))->count() < 1){
            $tPageviews = new TotalPageviews();
            $tPageviews->total = 1;
            $tPageviews->visit_date = Carbon::now();
            $tPageviews->save();
        }else{
            $totalpageviews = TotalPageviews::where('visit_date','=',date('Y-m-d',$today))->first();
            $totalpageviews->increment('total');
        }
        //End Tracker
	}
}
