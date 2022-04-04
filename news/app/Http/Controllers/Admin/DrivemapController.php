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

class DrivemapController extends DefinedController
{

    public function index(){
        $user = Sentinel::getUser();
        $listemployees = array();
        $listemployees[0] = Lang::get('drivemap/title.all'); 
        $res = DB::table('employees')
                    ->where('us_sc_id',$user->sc_id)
                    ->where('us_active',1)
                    ->orderBy('lastname')
                    ->orderBy('firstname')->get();
        foreach ($res as $key => $r2) {
            $listemployees[$r2->us_id] = $r2->userId." - ".$r2->lastname." ".$r2->firstname;            
        }

        $listequipments = array();
        $listequipments[0] = Lang::get('drivemap/title.all'); 
        $res = DB::table('equipments')
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                    ->where('eq_sc_id',$user->sc_id)                    
                    ->orderBy('eq_internalcode','desc')->get();
        foreach ($res as $key => $r) {
            $listequipments[$r->eq_id] = $r->eq_internalcode." ".$r->et_title." ".$r->eq_name;            
        }
        return view('admin.drivemap.index')->with(['listemployees' => $listemployees, 'listequipments' => $listequipments]);
    }
    
    public function getdata(Request $req){        
        $user = Sentinel::getUser();

        $fromdate = $req->fromdate;        
        if(isset($fromdate) && $fromdate != '') $fromdate = Carbon::parse($fromdate)->format('Y-m-d');
        $todate = $req->todate;
        if(isset($todate) && $todate != '') $todate = Carbon::parse($todate)->format('Y-m-d');
        $us_id = $req->us_id;
        $eq_id = $req->eq_id;
        $timezone = "America/New_York";
        $res = DB::table('gps')
                ->select(DB::raw("*, CONVERT_TZ(gps_datetime, 'UTC', '" . $timezone . "') as myDate"))
                ->leftJoin('employees','employees.us_id','=','gps.gps_us_id')                
                ->where('us_sc_id',$user->sc_id);
        if($us_id > 0){
            $res = $res->where('gps_us_id',$us_id);
        }
        if($eq_id > 0){
            $res = $res->where('gps_eq_id',$eq_id);
        }
        if(isset($fromdate) && $fromdate != ''){
            $res = $res->whereRaw("CONVERT_TZ(gps_datetime, 'UTC', '$timezone') >= '" . $fromdate . " 00:00:00'");
        }
        if(isset($todate) && $todate != ''){
            $res = $res->whereRaw("CONVERT_TZ(gps_datetime, 'UTC', '$timezone') <= '" . $todate . " 23:59:59'");
        }
        $res = $res->orderBy('gps_us_id')->orderBy('gps_datetime')->get();
    
        $paths = array();
        $redPoints = array();
    
        $lastState = "";
        $lastUser=0;
        $x = 0;
        $i = 0;
        foreach ($res as $key => $r) {            
            $gps_us_id = $r['gps_us_id'];
            $paths[$gps_us_id][$x] = $r;
            if (($lastState <> $r['gps_state'])||($lastUser<>$r['us_id'])) {
                $tg = new \stdClass;
                $tg->title = $r->firstname . ' ' . $r->lastname;           
                $tg->gps_lat = $r->gps_lat;
                $tg->gps_lon = $r->gps_lon;
                $tg->description = $this->toUSDateTime($r->myDate);
                $redPoints[$i] = $tg;
                $lastState = $r['gps_state'];
                $lastUser = $r['us_id'];
                $i++;
            }
            $x++;
        }
        $output = array('points' => $redPoints, 'path'  => $paths, 'lastUser' => $lastUser, 'lastState' => $lastState);
        return json_encode($output);
    }        
}
