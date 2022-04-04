<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\DefinedController;
use App\Http\Requests\UserRequest;
use App\Mail\Register;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use File;
use Hash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Redirect;
use Sentinel;
use URL;
use View;
use Lang;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Validator;
use App\Mail\Restore;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Country;

class EmployeemapController extends DefinedController
{

    public function index(){
        $user = Sentinel::getUser();
        $listemployees = array();
        $listemployees[0] = Lang::get('employeemap/title.all'); 
        $res = DB::table('employees')
                    ->where('us_sc_id',$user->sc_id)
                    ->where('us_active',1)
                    ->orderBy('lastname')
                    ->orderBy('firstname')->get();
        foreach ($res as $key => $r2) {
            $listemployees[$r2->us_id] = $r2->userId." - ".$r2->lastname." ".$r2->firstname;            
        }
        return view('admin.employeemap.index')->with(['listemployees' => $listemployees]);
    }
    
    public function getdata(Request $req){        
        $user = Sentinel::getUser();

        $fromdate = $req->fromdate;        
        if(isset($fromdate) && $fromdate != '') $fromdate = Carbon::parse($fromdate)->format('Y-m-d');
        $todate = $req->todate;
        if(isset($todate) && $todate != '') $todate = Carbon::parse($todate)->format('Y-m-d');
        $us_id = $req->us_id;
        
        $res = DB::table('workdays')
                ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')                
                ->where('wkd_sc_id',$user->sc_id)
                ->where('hrs_gps_start_lat','>',0);
        if($us_id > 0){
            $res = $res->where('us_id',$us_id);
        }
        if(isset($fromdate) && $fromdate != ''){
            $res = $res->whereDate('wkd_day','>=',$fromdate);   
        }
        if(isset($todate) && $todate != ''){
            $res = $res->whereDate('wkd_day','<=',$todate);   
        }
        $res = $res->get();

        $listmarkers = array();
        foreach ($res as $key => $r) {            
            $description = addslashes($r->hrs_jobid." - ".$r->description);
            if ($r->hrs_jobid==$this->CODE_DRIVE) $description = "DRIVE";
            if ($r->hrs_jobid==$this->CODE_SHOP) $description = "SHOP";
            if ($r->hrs_jobid==$this->CODE_HOLIDAYS) $description = "HOLIDAYS";  
            if ($r->hrs_jobid==$this->CODE_VACATION) $description = "VACATION";
            if ($r->hrs_jobid==$this->CODE_DRIVE_CDL) $description = "DRIVE CDL";
            
            $tg = new \stdClass;
            $tg->title = $r->firstname." ".$r->lastname;
            $tg->lat = $r->hrs_gps_start_lat;
            $tg->lng = $r->hrs_gps_start_lon;
            $tg->desc = $description.'  ('.$this->showTime($r->hrs_regular+$r->hrs_ovt+$r->hrs_double).')';
            array_push($listmarkers, $tg);
        }
        return json_encode($listmarkers);
    }        
}
