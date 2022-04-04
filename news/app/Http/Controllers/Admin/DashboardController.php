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
use Redirect;
use Sentinel;
use URL;
use View;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Validator;
use App\Mail\Restore;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Country;

class DashboardController extends DefinedController
{
       
    /*
     * Pass data through ajax call
     */
    /**
     * @return mixed
     */
    public function getdata(Request $req)
    {        
        $user = Sentinel::getUser();
        $fromDate = $req->fromdate;
        $toDate = $req->todate;
        $jobnr = $req->jobnr;

        if ($fromDate=="") {
            $fromDate = date('Y-m-d',strtotime('-1 week'));
        } else {
            //$fromDate = $this->USToMysqlDate($fromDate);
            $fromDate = Carbon::parse($fromDate)->format('Y-m-d');
            
        }
        if ($toDate=="")  {
            $toDate =  date("Y-m-d");
        } else {
            //$toDate = $this->USToMysqlDate($toDate);
            $toDate = Carbon::parse($toDate)->format('Y-m-d');
        }
       
        $output = array();
        for($i = 0; $i < 5;$i++){
            for ($j=0; $j < 4; $j++) { 
                $output[$i][$j] = 0;
            }
        }
        $vacation_regular = 0;
        $holidays_regular = 0;
        $vacation_ovt = 0;
        $holidays_ovt = 0;
        $vacation_dbl = 0;
        $holidays_dbl = 0;
        $vacation_tot = 0;
        $holidays_tot = 0;

        $work_regular = 0;
        $work_ovt = 0;
        $work_dbl = 0;
        $work_tot = 0;         
        $drive_regular = 0;
        $drive_ovt=0;
        $drive_dbl = 0;
        $drive_tot=0;
        $shop_regular = 0;
        $shop_ovt = 0;
        $shop_dbl = 0;
        $shop_tot = 0;
        if ($jobnr != null && strlen($jobnr)>0) {
            $res = DB::table('jobs')->where('nr',$jobnr)->where('jb_sc_id',$user->sc_id)->first();
            if($res){
                $jcompany = $res->company;
                $jobid = $res->jid;
                $jdescription = $res->description; 
            
                $res = DB::table('hours')
                        ->select(DB::raw('COALESCE(sum(hrs_regular),0) as regular, COALESCE(sum(hrs_ovt),0) as ovt, COALESCE(sum(hrs_double),0) as dbl'))
                        ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')
                        ->where('hrs_jobid',$jobid)
                        ->where('hrs_status','<=',1)
                        ->where('hrs_jobid','>',0)
                        ->where('wkd_status',1)
                        ->where('wkd_sc_id',$user->sc_id)
                        ->whereDate('wkd_day', '>=', $fromDate)
                        ->whereDate('wkd_day', '<=', $toDate)->first();
                if($res){
                    $work_regular = $res->regular;
                    $work_ovt =  $res->ovt;
                    $work_dbl = $res->dbl;
                    $work_tot = $work_regular + $work_ovt + $work_dbl;
                }
                
                // for each day where there is this job, check if there is drive and shop before               
                $res = DB::table('hours')
                        ->select('wkd_day')
                        ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')
                        ->where('hrs_jobid',$jobid)
                        ->where('hrs_status','<=',1)
                        ->where('hrs_jobid','>',0)
                        ->where('wkd_status',1)
                        ->where('wkd_sc_id',$user->sc_id)
                        ->whereDate('wkd_day', '>=', $fromDate)
                        ->whereDate('wkd_day', '<=', $toDate)->distinct('wkd_day')->get();
                
                foreach ($res as $key => $value) {
                    $res2 = DB::table('hours')
                        ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')
                        ->where('hrs_status','<=',1)
                        ->where('wkd_status',1)
                        ->where('wkd_sc_id',$user->sc_id)
                        ->where('wkd_day',$value->wkd_day)
                        ->orderBy('wkd_us_id')
                        ->orderBy('hrs_id')
                        ->get();
                    $hrs = array();
                    $jobpoints = array();
                    $i = 0;
                    foreach ($res2 as $key2 => $value2) {
                        $hrs[$i]['hrs_jobid'] = $value2->hrs_jobid;
                        $hrs[$i]['hrs_regular'] = $value2->hrs_regular;
                        $hrs[$i]['hrs_ovt'] = $value2->hrs_ovt;
                        $hrs[$i]['hrs_double'] = $value2->hrs_double;
                        if ($value2->hrs_jobid == $jobid) $jobpoints[] = $i;
                        $i++;
                    }

                    for ($jp = 0; $jp < count($jobpoints); $jp++) {                        
                        $end = false;
                        $tdrive_regular = 0;
                        $tdrive_ovt = 0;
                        $tdrive_dbl = 0;
                        $tdrive_tot = 0;
                        $cuthalf = 1;
                        
                        for ($bt = $jobpoints[$jp]-1; $bt >= 0; $bt-- ) {                        
                            if (($hrs[$bt]['hrs_jobid'] > 0) || ($hrs[$bt]['hrs_jobid'] == $this->CODE_SHOP)) {
                                 $end = true;
                                 if ($hrs[$bt]['hrs_jobid'] > 0) $cuthalf = 0.5;
                            }
                            if (!$end) {                                
                                if ($hrs[$bt]['hrs_jobid'] == $this->CODE_DRIVE) {
                                    $tdrive_regular = $tdrive_regular + $hrs[$bt]['hrs_regular'];
                                    $tdrive_ovt = $tdrive_ovt + $hrs[$bt]['hrs_ovt'];
                                    $tdrive_dbl = $tdrive_dbl + $hrs[$bt]['hrs_double'];
                                    $tdrive_tot = $tdrive_tot + $hrs[$bt]['hrs_regular'] + $hrs[$bt]['hrs_ovt'] + $hrs[$bt]['hrs_double'];
                                }
                                
                                // if ($hrs[$bt]['hrs_jobid'] == $this->CODE_SHOP) {
                                //     $shop_regular = $shop_regular + $hrs[$bt]['hrs_regular'];
                                //     $shop_ovt = $shop_ovt + $hrs[$bt]['hrs_ovt'];
                                //     $shop_dbl = $shop_dbl + $hrs[$bt]['hrs_double'];
                                //     $shop_tot = $shop_tot + $hrs[$bt]['hrs_regular'] + $hrs[$bt]['hrs_ovt'] + $hrs[$bt]['hrs_double'];
                                // }                                
                            }                            
                        }
                        
                        $drive_regular = $drive_regular + $tdrive_regular * $cuthalf;
                        $drive_ovt = $drive_ovt + $tdrive_ovt * $cuthalf;
                        $drive_dbl = $drive_dbl + $drive_dbl * $cuthalf;
                        $drive_tot = $drive_tot + ($tdrive_regular + $tdrive_ovt + $drive_dbl) * $cuthalf;
                        
                        $end = false;
                        $tdrive_regular = 0;
                        $tdrive_ovt = 0;
                        $tdrive_dbl = 0;
                        $tdrive_tot = 0;
                        $cuthalf = 1;
                        
                        for ($bt = $jobpoints[$jp] + 1; $bt <= count($hrs); $bt++ ) {
                        
                            if (($hrs[$bt]['hrs_jobid'] > 0) || ($hrs[$bt]['hrs_jobid'] == $this->CODE_SHOP)) {
                                 $end = true;
                                 if ($hrs[$bt]['hrs_jobid'] > 0) $cuthalf = 0.5;
                            }
                            if (!$end) {
                                
                                if ($hrs[$bt]['hrs_jobid'] == $this->CODE_DRIVE) {
                                    $tdrive_regular = $tdrive_regular + $hrs[$bt]['hrs_regular'];
                                    $tdrive_ovt = $tdrive_ovt + $hrs[$bt]['hrs_ovt'];
                                    $tdrive_dbl = $tdrive_dbl + $hrs[$bt]['hrs_double'];
                                    $tdrive_tot = $tdrive_tot + $hrs[$bt]['hrs_regular'] + $hrs[$bt]['hrs_ovt'] + $hrs[$bt]['hrs_double'];        
                                }
                                // if ($hrs[$bt]['hrs_jobid'] == $this->CODE_SHOP) {
                                //     $shop_regular = $shop_regular + $hrs[$bt]['hrs_regular'];
                                //     $shop_ovt = $shop_ovt + $hrs[$bt]['hrs_ovt'];
                                //     $shop_dbl = $shop_dbl + $hrs[$bt]['hrs_double'];
                                //     $shop_tot = $shop_tot + $hrs[$bt]['hrs_regular'] + $hrs[$bt]['hrs_ovt'] + $hrs[$bt]['hrs_double'];
                                // }                                
                            }                            
                        }
                        
                        $drive_regular = $drive_regular + $tdrive_regular * $cuthalf;
                        $drive_ovt = $drive_ovt + $tdrive_ovt * $cuthalf;
                        $drive_dbl = $drive_dbl + $drive_dbl * $cuthalf;
                        $drive_tot = $drive_tot + ($tdrive_regular + $tdrive_ovt + $drive_dbl) * $cuthalf;
                        
                    }
                }               
            }

            $vacation_tot = $vacation_regular + $vacation_ovt + $vacation_dbl;
            $holidays_tot = $holidays_regular + $holidays_ovt + $holidays_dbl;
            
        } else {            
            $res = DB::table('hours')
                    ->select(DB::raw('COALESCE(sum(hrs_regular),0) as regular, COALESCE(sum(hrs_ovt),0) as ovt, COALESCE(sum(hrs_double),0) as dbl'))
                    ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')                    
                    ->where('hrs_status','<=',1)
                    ->where('hrs_jobid',$this->CODE_DRIVE)
                    ->where('wkd_status',1)
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day', '>=', $fromDate)
                    ->whereDate('wkd_day', '<=', $toDate)->first();
            if($res){
                $drive_regular = $res->regular;
                $drive_ovt = $res->ovt;
                $drive_dbl = $res->dbl;
                $drive_tot = $drive_regular + $drive_ovt + $drive_dbl;
            }

            $res = DB::table('hours')
                    ->select(DB::raw('COALESCE(sum(hrs_regular),0) as regular, COALESCE(sum(hrs_ovt),0) as ovt, COALESCE(sum(hrs_double),0) as dbl'))
                    ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')                    
                    ->where('hrs_status','<=',1)
                    ->where('hrs_jobid',$this->CODE_SHOP)
                    ->where('wkd_status',1)
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day', '>=', $fromDate)
                    ->whereDate('wkd_day', '<=', $toDate)->first();
            if($res){
                $shop_regular = $res->regular;
                $shop_ovt=$res->ovt;
                $shop_dbl = $res->dbl;
                $shop_tot=$shop_regular + $shop_ovt + $shop_dbl;
            }

            $res = DB::table('hours')
                    ->select(DB::raw('COALESCE(sum(hrs_regular),0) as regular, COALESCE(sum(hrs_ovt),0) as ovt, COALESCE(sum(hrs_double),0) as dbl'))
                    ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')                    
                    ->where('hrs_status','<=',1)
                    ->where('hrs_jobid',$this->CODE_HOLIDAYS)
                    ->where('wkd_status',1)
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day', '>=', $fromDate)
                    ->whereDate('wkd_day', '<=', $toDate)->first();
            if($res){
                $holidays_regular = $res->regular;
                $holidays_ovt = $res->ovt;
                $holidays_dbl = $res->dbl;
                $holidays_tot = $holidays_regular + $holidays_ovt + $holidays_dbl;
            }

            $res = DB::table('hours')
                    ->select(DB::raw('COALESCE(sum(hrs_regular),0) as regular, COALESCE(sum(hrs_ovt),0) as ovt, COALESCE(sum(hrs_double),0) as dbl'))
                    ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')                    
                    ->where('hrs_status','<=',1)
                    ->where('hrs_jobid',$this->CODE_VACATION)
                    ->where('wkd_status',1)
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day', '>=', $fromDate)
                    ->whereDate('wkd_day', '<=', $toDate)->first();
            if($res){
                $vacation_regular = $res->regular;
                $vacation_ovt=$res->ovt;
                $vacation_dbl = $res->dbl;
                $vacation_tot=$vacation_regular + $vacation_ovt + $vacation_dbl;
            }
        
            $res = DB::table('hours')
                    ->select(DB::raw('COALESCE(sum(hrs_regular),0) as regular, COALESCE(sum(hrs_ovt),0) as ovt, COALESCE(sum(hrs_double),0) as dbl'))
                    ->leftJoin('workdays','hours.hrs_wkd_id','=','workdays.wkd_id')
                    ->where('hrs_status','<=',1)
                    ->where('hrs_jobid','>',0)
                    ->where('wkd_status',1)
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day', '>=', $fromDate)
                    ->whereDate('wkd_day', '<=', $toDate)->first();
            if($res){
                $work_regular = $res->regular;
                $work_ovt=$res->ovt;
                $work_dbl = $res->dbl;
                $work_tot=$work_regular + $work_ovt + $work_dbl;
            }
        }
        
        $totmiles=0;
        $res = DB::table('workdays')->select(DB::raw('sum(wkd_miles) as miles'))
                ->where('wkd_status',1)
                ->where('wkd_sc_id',$user->sc_id)
                ->whereDate('wkd_day', '>=', $fromDate)
                ->whereDate('wkd_day', '<=', $toDate)->first();
            
        if ($res) {
            $totmiles = $res->miles;
        }
        if(!$totmiles) $totmiles = 0;

        $output[0][0] = $work_regular;
        $output[0][1] = $work_ovt;
        $output[0][2] = $work_dbl;
        $output[0][3] = $work_tot;

        $output[1][0] = $shop_regular;
        $output[1][1] = $shop_ovt;
        $output[1][2] = $shop_dbl;
        $output[1][3] = $shop_tot;

        $output[2][0] = $drive_regular;
        $output[2][1] = $drive_ovt;
        $output[2][2] = $drive_dbl;
        $output[2][3] = $drive_tot; 

        $output[3][0] = $vacation_regular;
        $output[3][1] = $vacation_ovt;
        $output[3][2] = $vacation_dbl;
        $output[3][3] = $vacation_tot;

        $output[4][0] = $holidays_regular;
        $output[4][1] = $holidays_ovt;
        $output[4][2] = $holidays_dbl;
        $output[4][3] = $holidays_tot;
        
        return array($output, $totmiles);
    }
    
    public function showTime($minutes) {
        $hours = floor($minutes / 60);
        $min = $minutes - ($hours * 60);
        return str_pad($hours,2,"0", STR_PAD_LEFT).":".str_pad($min,2,"0", STR_PAD_LEFT);
    }

    private function USToMysqlDate($dt) {
        $d = explode("/", $dt);
        return $d[2]."-".$d[1]."-".$d[0];
    }
}
