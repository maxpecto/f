<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Reports;
use Redirect;

class ReportsController extends BackendController
{
    public function index(Request $request){
        $total_reports = Reports::where('solve',0)->get();
        $data = Reports::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
        return view('backend.reports.lists',compact('data','total_reports'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'report_id' => 'required',
        ]);

        $reports = new Reports();
        $reports->type = $request->report_id;
        $reports->desc = $request->report_desc;
        $reports->items_id = $request->items_id;
        $reports->items_type = $request->items_type; //0 for movie - 1 for series - 2 episode
        $reports->save();

        return Redirect::back()->withErrors(['Reported successfully!']);
    }

    //Change Solved
    public function solved(Request $request){
        $reports = Reports::find($request->id);
        if ($reports->solve == 1) {
            $reports->solve = 0;
        }else{
            $reports->solve = 1;
        }
        $reports->save();
    }

    //Delete Reports
    public function destroy($id){
        $ids = trim($id, '[]');
        $reportsid = explode(",",$ids);
        $reports = Reports::whereIn('id', $reportsid)->get();
        foreach ($reports as $report) {
            //Delete reports
            $report->delete();
        }
        return redirect()->action([ReportsController::class,'index'])->with('success','Reports Deleted Successfully!');
    }

}
