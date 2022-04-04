<?php

namespace App\Http\Controllers\Admin;

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

class WeekCalulateController extends DefinedController
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){ 
        $user = Sentinel::getUser();     
        $SCOMPANYCALC = 0;
        $sccalt = DB::table('schultes')->where('sc_id',$user->sc_id)->first();
        if($sccalt){
            $SCOMPANYCALC = $sccalt->sc_calctype;
        }    
        return view('admin.weekcalculate.index')->with(['SCOMPANYCALC' => $SCOMPANYCALC]);
    }

    public function getdata(Request $req){
        $user = Sentinel::getUser();
        $SCOMPANYCALC = 0;
        $sccalt = DB::table('schultes')->where('sc_id',$user->sc_id)->first();
        if($sccalt){
            $SCOMPANYCALC = $sccalt->sc_calctype;
        }

        // generate holidays
        $res = DB::table('holidays')
                        ->where('ho_status',0)
                        ->whereDate('ho_day','<=',date('Y-m-d'))
                        ->where('ho_sc_id',$user->sc_id)->get();
        
        foreach ($res as $key => $r) {            
            $oneyearago = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")-1));
            $resb = DB::table('employees')
                    ->leftJoin('workdays','workdays.wkd_us_id','=','employees.us_id')
                    ->where('wkd_day',$r->ho_day)
                    ->whereNull('wkd_id')
                    ->whereDate('datehired','<=',$oneyearago)
                    ->where('us_sc_id',$user->sc_id)
                    ->where('us_active',1)->get();
            foreach ($resb as $key1 => $rb) {
                $employeeDept = $rb->departmentNr;
                $typeOfEmployee=$this->typeOfEmployee($employeeDept);
                $code = $this->CODE_HOLIDAYS;
                $driller=1;
                if (substr($employeeDept,1,2)=='02') $driller=0;
                
                $dat = array(                    
                    'wkd_sc_id' => $user->sc_id,
                    'wkd_us_id' => $rb->us_id,
                    'wkd_driller_helper' => $driller,
                    'wkd_truck_driver' => 0,
                    'wkd_liveexp' => 0,
                    'wkd_lunch' => 0,
                    'wkd_lunchtime' => "0000-00-00 00:00:00",
                    'wkd_miles' => 0,
                    'wkd_day' => $r->ho_day,
                    'wkd_shift_work' => 1,
                    'wkd_end_realtime' => 0,
                    'wkd_gps_latitude' => 0,
                    'wkd_gps_longitude' => 0,
                    'wkd_timestamp' => date('Y-m-d H:i:s'),
                    'wkd_status' => 0,                    
                    'wkd_notes' => "",
                    'wkd_recalctime' => '0000-00-00 00:00:00',
                    'wkd_locked' => 0,
                );

                $resu = DB::table('workdays')->insertGetId($dat);                
                $wkd_id=$resu;

                $dat = array(                    
                    'hrs_jobid' => $code,
                    'hrs_starttime' => $r->ho_day . ' 08:00:00',
                    'hrs_endtime' => $r->ho_day . ' 16:00:00',
                    'hrs_realtime' => '0000-00-00 00:00:00',
                    'hrs_truckdriver' => 0,
                    'hrs_gps_start_lat' => 0,
                    'hrs_gps_start_lon' => 0,
                    'hrs_gps_end_lat' => 0,
                    'hrs_gps_end_lon' => 0,
                    'hrs_regular' => 480,
                    'hrs_ovt' => 0,
                    'hrs_double' => 0,
                    'hrs_status' => 0,
                    'hrs_wkd_id' => $wkd_id,                    
                );
                $resu = DB::table('hours')->insert($dat);
                
                $dat = array(
                    'ho_status' => 1,                    
                );
                $resu = DB::table('holidays')->where('ho_day',$r->ho_day)->where('ho_sc_id',$user->sc_id)->update($dat);
            }
        }

        // generate vacations        
        $res = DB::table('vacations')
                        ->where('va_status',0)
                        ->whereDate('va_day','<=',date('Y-m-d'))
                        ->where('va_sc_id',$user->sc_id)->get();
        
        foreach ($res as $key => $r) {            
            $resb = DB::table('employees')->where('us_id',$r->va_us_id)
                    ->where('us_sc_id',$user->sc_id)
                    ->where('us_active',1)->get();            
            foreach ($resb as $key1 => $rb) {
                $employeeDept = $rb->departmentNr;
                $typeOfEmployee=$this->typeOfEmployee($employeeDept);
                $code = $this->CODE_VACATION;
                $driller=1;
                $hours=$r->va_hours * 60;
                $time = $r->va_hours + 8;
                if($time < 10) $time = "0" . $time;
                $notes = addslashes($r->va_title);
                if (substr($employeeDept,1,2)=='02') $driller=0;                
                $dat = array(                    
                    'wkd_sc_id' => $user->sc_id,
                    'wkd_us_id' => $rb->us_id,
                    'wkd_driller_helper' => $driller,
                    'wkd_truck_driver' => 0,
                    'wkd_liveexp' => 0,
                    'wkd_lunch' => 0,
                    'wkd_lunchtime' => "0000-00-00 00:00:00",
                    'wkd_miles' => 0,
                    'wkd_day' => $r->va_day,
                    'wkd_shift_work' => 1,
                    'wkd_end_realtime' => 0,
                    'wkd_gps_latitude' => 0,
                    'wkd_gps_longitude' => 0,
                    'wkd_timestamp' => date('Y-m-d H:i:s'),
                    'wkd_status' => 0,                    
                    'wkd_notes' => $notes,
                    'wkd_recalctime' => '0000-00-00 00:00:00',
                    'wkd_locked' => 0,
                );

                $resu = DB::table('workdays')->insertGetId($dat);                
                $wkd_id=$resu;

                $dat = array(                    
                    'hrs_jobid' => $code,
                    'hrs_starttime' => $r->va_day . ' 08:00:00',
                    'hrs_endtime' => $r->va_day . ' ' . $time .':00:00',
                    'hrs_realtime' => '0000-00-00 00:00:00',
                    'hrs_truckdriver' => 0,
                    'hrs_gps_start_lat' => 0,
                    'hrs_gps_start_lon' => 0,
                    'hrs_gps_end_lat' => 0,
                    'hrs_gps_end_lon' => 0,
                    'hrs_regular' => $hours,
                    'hrs_ovt' => 0,
                    'hrs_double' => 0,
                    'hrs_status' => 0,
                    'hrs_wkd_id' => $wkd_id,                    
                );
                $resu = DB::table('hours')->insert($dat);                
                $dat = array(
                    'va_status' => 1,                    
                );
                $resu = DB::table('vacations')->where('va_id',$r->va_id)->update($dat);                
            }
        }
        
        // check if there are times to calculate
        if ($SCOMPANYCALC==0) {            
            $res = DB::table('hours')
                    ->select(DB::raw('hrs_wkd_id, sum(hrs_ovt + hrs_regular + hrs_double) as tot'))
                    ->groupBy('hrs_wkd_id')
                    ->having('tot', '=', 0)->get();
            foreach ($res as $key => $r) {                
                $this->calculateTime($r->hrs_wkd_id);
            }
        }

        if ($SCOMPANYCALC==1) {
            $res = DB::table('workdays')
                    ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                    ->where('wkd_sc_id',$user->sc_id)
                    ->where('hrs_status','<=',1)
                    ->where('wkd_locked',0)
                    ->whereRaw('(hrs_ovt + hrs_regular + hrs_double) = 0')
                    ->orderBy('wkd_day')->get();

            foreach ($res as $key => $r) {
                $startTime = $r->hrs_starttime;
                $endTime = $r->hrs_endtime;
                $timeWorked = $this->MinuteDiffInt($startTime, $endTime);
                if ($r->wkd_lunch == 1) {
                    $lunchTime = $r->wkd_lunchtime;
                    if (($r->wkd_lunchtime >= $startTime) && ($r->wkd_lunchtime < $endTime)) {
                        $timeWorked = max($timeWorked - 30,0);
                    }
                }
                $regular = $timeWorked;
                $ovt = 0;
                $dbl = 0;
                $datt = array(
                    'hrs_regular'   => $regular,
                    'hrs_ovt'       => $ovt,
                    'hrs_double'    => $dbl,
                );
                $res3 = DB::table('hours')->where('hrs_id',$r->hrs_id)->update($datt);
            }
        }
        $selectedyear = $req->year;
        $enddate=strtotime('monday', mktime(0,0,0,date("m"),date("d"),date("Y")));
        $weeksStart=array();
        $weeksEnd=array();
        $weeksStartM=array();
        $weeksEndM=array();
        $firstDayOfYear = mktime(0,0,0,1,1,$selectedyear);
        $nextMonday = strtotime('monday', $firstDayOfYear);
        $nextSunday = strtotime('sunday', $nextMonday);
        while ((date('Y', $nextMonday) == $selectedyear)&&($nextMonday <= $enddate)) {
            $weeksStart[]=date("m/d/Y",$nextMonday);
            $weeksEnd[]=date("m/d/Y",$nextSunday);
            $weeksStartM[]=date("Y-m-d",$nextMonday);
            $weeksEndM[]=date("Y-m-d",$nextSunday);
            $nextMonday = strtotime("+1 week", $nextMonday);
            $nextSunday = strtotime("+1 week", $nextSunday);
        }

        $data = new Collection;

        for ($i=count($weeksStart)-1; $i>=0; $i--) {
            $res = DB::table('workdays')
                    ->select(DB::raw('count(*) as tot'))
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day','<=',$weeksEndM[$i])
                    ->whereDate('wkd_day','>=',$weeksStartM[$i])
                    ->where('wkd_status','<',10)->first();
            $nrTot=$res->tot;

            $res = DB::table('workdays')
                    ->select(DB::raw('count(*) as tot'))
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day','<=',$weeksEndM[$i])
                    ->whereDate('wkd_day','>=',$weeksStartM[$i])
                    ->where('wkd_status',1)->first();
            $nrTotA=$res->tot;

            $res = DB::table('workdays')
                    ->select(DB::raw('count(*) as tot'))
                    ->where('wkd_sc_id',$user->sc_id)
                    ->whereDate('wkd_day','<=',$weeksEndM[$i])
                    ->whereDate('wkd_day','>=',$weeksStartM[$i])
                    ->where('wkd_status','<',10)
                    ->whereDate('wkd_recalctime','>','0000-00-00')
                    ->first();
            $nrTotC=$res->tot;

            $data->push([
                'actions'     => '<a href="' . route('admin.weekcalculate.details',[$weeksStartM[$i],$weeksEndM[$i],$selectedyear]) . '" class="btn btn-success">' . Lang::get('button.details') . '</a>',
                'week'        => $weeksStart[$i]." - ".$weeksEnd[$i],
                'nrTot'       => $nrTot,
                'nrTotA'      => $nrTotA,
                'nrTotC'      => $nrTotC,                  
            ]);
        }
        return DataTables::of($data)->rawColumns(['actions'])->make(true); 
    }

    public function details(Request $req){
        $user = Sentinel::getUser();
        $SCOMPANYCALC = 0;
        $sccalt = DB::table('schultes')->where('sc_id',$user->sc_id)->first();
        if($sccalt){
            $SCOMPANYCALC = $sccalt->sc_calctype;
        }
        $wstart = $req->wstart;
        $wend = $req->wend;
        $year = $req->year;
        $padmin=DB::table('jobs')
                ->select('padmin')
                ->where('jstatus',0)
                ->whereRaw('LENGTH(padmin) > 0')
                ->where('jb_sc_id',$user->sc_id)
                ->distinct('padmin')
                ->orderBy('padmin')->get();

        return view('admin.weekcalculate.details')->with(['listpadmin'  => $padmin, 'SCOMPANYCALC' => $SCOMPANYCALC, 'wstart' => $wstart, 'wend' => $wend, 'year' => $year]);        
    }    

    public function recalculatedetail(Request $req){
        $user = Sentinel::getUser();
        $us_id = $req->us_id;
        $weeksStart = $req->weekstart;
        $weeksEnd = $req->weekend;

        $maxWeeklyRegular = 40*60;
        $recalcDate = date("Y-m-d h:s:i");
        $tot_regular = 0;
        $tot_ovt = 0;
        $tot_dbl = 0;
        
        $r = DB::table('workdays')
                ->select(DB::raw('sum(hrs_regular) as regular, sum(hrs_ovt) as ovt, sum(hrs_double) as dbl'))
                ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                ->where('wkd_sc_id',$user->sc_id)
                ->where('wkd_us_id',$us_id)
                ->whereDate('wkd_day','<=',$weeksEnd)
                ->whereDate('wkd_day','>=',$weeksStart)
                ->where('hrs_status','<=',1)
                ->where('wkd_status',1)
                ->where('wkd_locked',1)->first();

        $tot_regular =  $r->regular;
        $tot_ovt = $r->ovt;
        $tot_dbl = $r->dbl;

        // get all hours not locked
        $res = DB::table('workdays')
                ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                ->leftJoin('jobs','hours.hrs_jobid','=','jobs.jid')
                ->where('wkd_sc_id',$user->sc_id)
                ->where('wkd_us_id',$us_id)
                ->whereDate('wkd_day','<=',$weeksEnd)
                ->whereDate('wkd_day','>=',$weeksStart)
                ->where('hrs_status','<=',1)
                ->where('wkd_status',1)
                ->where('wkd_locked',0)->get();

        foreach ($res as $key => $r) {                                
            $startTime = $r->hrs_starttime;
            $endTime = $r->hrs_endtime;
            $timeWorked = $this->MinuteDiffInt($startTime, $endTime);
            if ($r->wkd_lunch==1) {
                $lunchTime = $r->wkd_lunchtime;
                if (($r->wkd_lunchtime >= $startTime) && ($r->wkd_lunchtime < $endTime)) {

                    $timeWorked = max($timeWorked - 30,0);
                }
            }

            // if saturday or sunday or holiday put dbl
            $regular = $timeWorked;
            $ovt = 0;
            $dbl = 0;
            if ($this->isSunday($r->wkd_day)&&false) {
                $ovt=$ovt+$regular;
                $regular = 0;
                $dbl=0;
            } else {
                if ($tot_regular < $maxWeeklyRegular) { // still some regular time
                    if ($regular > ($maxWeeklyRegular - $tot_regular)) {
                        $diff = ($maxWeeklyRegular - $tot_regular);
                        $ovt = $regular - $diff;
                        $regular = $diff;
                        $tot_regular = $tot_regular + $regular;
                        $tot_ovt = $tot_ovt + $ovt;
                    } else {
                        $ovt = 0;
                        $tot_regular = $tot_regular + $regular;
                    }

                } else { // all overtime
                    $ovt = $regular;
                    $regular = 0;
                    $tot_ovt = $tot_ovt + $ovt;
                }

            }
            $data = array(
                'hrs_regular'   => $regular,
                'hrs_ovt'       => $ovt,
                'hrs_double'    => $dbl,
            );
            $res2 = DB::table('hours')->where('hrs_id',$r->hrs_id)->update($data);            
        }

        // get all shop not locked           
        $res = DB::table('workdays')
                ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                ->where('wkd_sc_id',$user->sc_id)
                ->where('hrs_jobid','-2')                
                ->where('wkd_us_id',$us_id)
                ->whereDate('wkd_day','<=',$weeksEnd)
                ->whereDate('wkd_day','>=',$weeksStart)
                ->where('hrs_status','<=',1)
                ->where('wkd_status',1)
                ->where('wkd_locked',0)->orderBy('wkd_day')->get();
            
        foreach ($res as $key => $r) {
            // if saturday or sunday or holiday put dbl
            $startTime = $r->hrs_starttime;
            $endTime = $r->hrs_endtime;
            $timeWorked = $this->MinuteDiffInt($startTime, $endTime);
            if ($r->wkd_lunch==1) {
                $lunchTime = $r->wkd_lunchtime;
                if (($r->wkd_lunchtime >= $startTime) && ($r->wkd_lunchtime < $endTime)) {

                    $timeWorked = max($timeWorked - 30,0);
                }
            }

            // if saturday or sunday or holiday put dbl
            $regular = $timeWorked;
            $ovt = 0;
            $dbl = 0;
            if ($this->isSunday($r->wkd_day) && false) {
                $ovt=$ovt+$regular;
                $regular = 0;
                $dbl=0;
            } else {
                if ($tot_regular < $maxWeeklyRegular) { // still some regular time
                    if ($regular > ($maxWeeklyRegular - $tot_regular)) {
                        $diff = ($maxWeeklyRegular - $tot_regular);
                        $ovt = $regular - $diff;
                        $regular = $diff;
                        $tot_regular = $tot_regular + $regular;
                        $tot_ovt = $tot_ovt + $ovt;
                    } else {
                        $ovt = 0;
                        $tot_regular = $tot_regular + $regular;
                    }
                } else { // all overtime
                    $ovt = $regular;
                    $regular = 0;
                    $tot_ovt = $tot_ovt + $ovt;
                }
            }
            $data = array(
                'hrs_regular'   => $regular,
                'hrs_ovt'       => $ovt,
                'hrs_double'    => $dbl,
            );
            $res2 = DB::table('hours')->where('hrs_id',$r->hrs_id)->update($data);     
        }

        // get all drive cdl not locked
        $res = DB::table('workdays')
                ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                ->where('wkd_sc_id',$user->sc_id)
                ->where('hrs_jobid','-10')                
                ->where('wkd_us_id',$us_id)
                ->whereDate('wkd_day','<=',$weeksEnd)
                ->whereDate('wkd_day','>=',$weeksStart)
                ->where('hrs_status','<=',1)
                ->where('wkd_status',1)
                ->where('wkd_locked',0)->orderBy('wkd_day')->get();

        foreach ($res as $key => $r) {
            // if saturday or sunday or holiday put dbl
            $startTime = $r->hrs_starttime;
            $endTime = $r->hrs_endtime;
            $timeWorked = $this->MinuteDiffInt($startTime, $endTime);
            if ($r->wkd_lunch==1) {
                $lunchTime = $r->wkd_lunchtime;
                if (($r->wkd_lunchtime >= $startTime) && ($r->wkd_lunchtime < $endTime)) {
                    $timeWorked = max($timeWorked - 30,0);
                }
            }

            // if saturday or sunday or holiday put dbl
            $regular = $timeWorked;
            $ovt = 0;
            $dbl = 0;
            if ($this->isSunday($r->wkd_day)&& false) {
                $ovt=$ovt+$regular;
                $regular = 0;
                $dbl=0;
            } else {
                if ($tot_regular < $maxWeeklyRegular) { // still some regular time
                    if ($regular > ($maxWeeklyRegular - $tot_regular)) {
                        $diff = ($maxWeeklyRegular - $tot_regular);
                        $ovt = $regular - $diff;
                        $regular = $diff;
                        $tot_regular = $tot_regular + $regular;
                        $tot_ovt = $tot_ovt + $ovt;
                    } else {
                        $ovt = 0;
                        $tot_regular = $tot_regular + $regular;
                    }
                } else { // all overtime
                    $ovt = $regular;
                    $regular = 0;
                    $tot_ovt = $tot_ovt + $ovt;
                }
            }
            $data = array(
                'hrs_regular'   => $regular,
                'hrs_ovt'       => $ovt,
                'hrs_double'    => $dbl,
            );
            $res2 = DB::table('hours')->where('hrs_id',$r->hrs_id)->update($data);            
        }

        // get all drive  not locked
        $res = DB::table('workdays')
                ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                ->where('wkd_sc_id',$user->sc_id)
                ->where('hrs_jobid','-1')                
                ->where('wkd_us_id',$us_id)
                ->whereDate('wkd_day','<=',$weeksEnd)
                ->whereDate('wkd_day','>=',$weeksStart)
                ->where('hrs_status','<=',1)
                ->where('wkd_status',1)
                ->where('wkd_locked',0)->orderBy('wkd_day')->get();

        foreach ($res as $key => $r) {
            // if saturday or sunday or holiday put dbl
            $startTime = $r->hrs_starttime;
            $endTime = $r->hrs_endtime;
            $timeWorked = $this->MinuteDiffInt($startTime, $endTime);
            if ($r->wkd_lunch==1) {
                $lunchTime = $r->wkd_lunchtime;
                if (($r->wkd_lunchtime >= $startTime) && ($r->wkd_lunchtime < $endTime)) {

                    $timeWorked = max($timeWorked - 30,0);
                }
            }
            // if saturday or sunday or holiday put dbl
            $regular = $timeWorked;
            $ovt = 0;
            $dbl = 0;
            if ($this->isSunday($r->wkd_day) && false) {
                $ovt=$ovt+$regular;
                $regular = 0;
                $dbl=0;
            } else {
                if ($tot_regular < $maxWeeklyRegular) { // still some regular time
                    if ($regular > ($maxWeeklyRegular - $tot_regular)) {
                        $diff = ($maxWeeklyRegular - $tot_regular);
                        $ovt = $regular - $diff;
                        $regular = $diff;
                        $tot_regular = $tot_regular + $regular;
                        $tot_ovt = $tot_ovt + $ovt;
                    } else {
                        $ovt = 0;
                        $tot_regular = $tot_regular + $regular;
                    }
                } else { // all overtime
                    $ovt = $regular;
                    $regular = 0;
                    $tot_ovt = $tot_ovt + $ovt;
                }
            }
            $data = array(
                'hrs_regular'   => $regular,
                'hrs_ovt'       => $ovt,
                'hrs_double'    => $dbl,
            );
            $res2 = DB::table('hours')->where('hrs_id',$r->hrs_id)->update($data);                        
        }

        $res2 = DB::table('workdays')
                    ->where('wkd_sc_id',$user->sc_id)
                    ->where('wkd_us_id',$us_id)
                    ->whereDate('wkd_day','<=',$weeksEnd)
                    ->whereDate('wkd_day','>=',$weeksStart)                    
                    ->where('wkd_status',1)
                    ->where('wkd_locked',0)->update(['wkd_recalctime' => $recalcDate]);
        return 1;
    }


    public function getdatadetails(Request $req){
        $user = Sentinel::getUser();
        $SCOMPANYCALC = 0;
        $sccalt = DB::table('schultes')->where('sc_id',$user->sc_id)->first();
        if($sccalt){
            $SCOMPANYCALC = $sccalt->sc_calctype;
        }
        $weeksStart = $req->wstart;
        $weeksEnd = $req->wend;
        $selectedyear = $req->year;

        // Filter on
        $foundFilter[1][1] = 0;        
        $isfilteradmin = false;
        $isfilter = false;
        $filterJob = "";
        if(isset($req->filterJob)){
            $filterJob = trim($req->filterJob);
        }  
        $filterAdmin = $req->filterAdmin;
        if ((strlen($filterAdmin)>0)&&($filterAdmin != "@")) $isfilteradmin = true;; 

        if ($isfilteradmin) $isfilter = true;
        if (strlen(trim($filterJob))>0) $isfilter = true;
            //echo "********************************************************* ".$filterSql." - ".$filterJob;
        
        if ($isfilter) {
            if ($filterJob > 0) {
                $res = DB::table('workdays')
                            ->select('wkd_day','wkd_us_id')
                            ->leftJoin('hours','workdays.wkd_id', '=' ,'hours.hrs_wkd_id')
                            ->leftJoin('jobs','jobs.jid', '=' ,'hours.hrs_jobid')
                            ->where('wkd_sc_id',$user->sc_id)
                            ->where('hrs_jobid',$filterJob)
                            ->whereDate('wkd_day', '<=', $weeksEnd)
                            ->whereDate('wkd_day', '>=', $weeksStart);
                if ($isfilteradmin){                    
                    $res = $res->where('padmin','like', $filterAdmin . "%");    
                }
                $res = $res->distinct('wkd_day')
                            ->orderBy('wkd_us_id')
                            ->orderBy('wkd_day')->get();
                foreach ($res as $key => $r) {                    
                    $foundFilter[$r->wkd_us_id][$r->wkd_day] = 1;
                }
            } else {
                if (!$isfilteradmin) {
                    $res = DB::table('workdays')
                                ->select('wkd_day','wkd_us_id')
                                ->leftJoin('hours','workdays.wkd_id', '=' ,'hours.hrs_wkd_id')  
                                ->where('hrs_jobid',$filterJob)                      
                                ->where('wkd_sc_id',$user->sc_id)                            
                                ->whereDate('wkd_day', '<=', $weeksEnd)
                                ->whereDate('wkd_day', '>=', $weeksStart)
                                ->distinct('wkd_day')
                                ->orderBy('wkd_us_id')
                                ->orderBy('wkd_day')->get();

                    foreach ($res as $key => $r) {                    
                        $foundFilter[$r->wkd_us_id][$r->wkd_day] = 1;
                    }
                } else {
                    if (strlen(trim($filterJob))==0) {                        
                        $res = DB::table('workdays')
                                    ->select('wkd_day','wkd_us_id')
                                    ->leftJoin('hours','workdays.wkd_id', '=' ,'hours.hrs_wkd_id')
                                    ->leftJoin('jobs','jobs.jid', '=' ,'hours.hrs_jobid')
                                    ->where('wkd_sc_id',$user->sc_id)
                                    ->where('hrs_jobid',$filterJob)
                                    ->whereDate('wkd_day', '<=', $weeksEnd)
                                    ->whereDate('wkd_day', '>=', $weeksStart)
                                    ->where('padmin','like',$filterAdmin . "%")
                                    ->distinct('wkd_day')
                                    ->orderBy('wkd_us_id')
                                    ->orderBy('wkd_day')->get();
                        foreach ($res as $key => $r) {                    
                            $foundFilter[$r->wkd_us_id][$r->wkd_day] = 1;
                        }
                    }
                }
            }
        }

        $res = DB::table('employees')
                ->select('us_id','firstname','lastname')
                ->leftJoin('workdays','employees.us_id','=','workdays.wkd_us_id')
                ->where('us_sc_id',$user->sc_id)
                ->whereDate('wkd_day','>=',$weeksStart)
                ->whereDate('wkd_day','<=',$weeksEnd)
                ->where('wkd_status','<',10)
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->distinct('us_id')->get();

        foreach ($res as $key => $r) {    
            $tot_monday_tobe = 0;
            $tot_monday_approved = 0;
            $tot_monday_approved_rec = 0;
            $tot_tuesday_tobe = 0;
            $tot_tuesday_approved = 0;
            $tot_tuesday_approved_rec = 0;
            $tot_wednesday_tobe = 0;
            $tot_wednesday_approved = 0;
            $tot_wednesday_approved_rec = 0;
            $tot_thursday_tobe = 0;
            $tot_thursday_approved = 0;
            $tot_thursday_approved_rec = 0;
            $tot_friday_tobe = 0;
            $tot_friday_approved = 0;
            $tot_friday_approved_rec = 0;
            $tot_saturday_tobe = 0;
            $tot_saturday_approved = 0;
            $tot_saturday_approved_rec = 0;
            $tot_sunday_tobe = 0;
            $tot_sunday_approved = 0;
            $tot_sunday_approved_rec = 0;
            
            $tot_monday_live = 0;
            $tot_tuesday_live = 0;
            $tot_wednesday_live = 0;
            $tot_thursday_live = 0;
            $tot_friday_live = 0;
            $tot_saturday_live = 0;
            $tot_sunday_live = 0;
            
            $monday_shw =0;
            $monday_trd=0;
            $tuesday_shw = 0;
            $tuesday_trd = 0;
            $wednesday_shw = 0;
            $wednesday_trd = 0;
            $thursday_shw = 0;
            $thursday_trd = 0;
            $friday_shw = 0;
            $friday_trd = 0;
            $saturday_shw = 0;
            $saturday_trd = 0;
            $sunday_shw =0;
            $sunday_trd = 0;

            $monday=$weeksStart;            
            $res2 = DB::table('workdays')
                        ->select(DB::raw('wkd_status, wkd_recalctime, wkd_liveexp, count(*) as tot, MAX(wkd_shift_work) as shw, MAX(wkd_truck_driver) as trd'))
                        ->where('wkd_us_id',$r->us_id)
                        ->where('wkd_day',$weeksStart)
                        ->where('wkd_status','<',10)
                        ->groupBy('wkd_status')
                        ->groupBy('wkd_recalctime')->get();

            foreach ($res2 as $key2 => $r2) {                
                $monday_shw = $r2->shw;
                $monday_trd = $r2->trd;
                if ($r2->wkd_status==0) $tot_monday_tobe = $r2->tot;
                if (($r2->wkd_status==1) && ($r2->wkd_recalctime == "0000-00-00 00:00:00")) {
                    $tot_monday_approved_rec = $r2->tot;
                } else {
                    if ($r2->wkd_status==1) $tot_monday_approved = $r2->tot;
                }
                $tot_monday_live = $r2->wkd_liveexp;
            }

            $exp=explode("-", $weeksStart);
            $firstDay = mktime(0,0,0, $exp[1], $exp[2], $exp[0]);
            $day = date("Y-m-d", strtotime("+1 day", $firstDay));
            $tuesday = $day;

            $res2 = DB::table('workdays')
                        ->select(DB::raw('wkd_status, wkd_recalctime, wkd_liveexp, count(*) as tot, MAX(wkd_shift_work) as shw, MAX(wkd_truck_driver) as trd'))
                        ->where('wkd_us_id',$r->us_id)
                        ->where('wkd_day',$day)
                        ->where('wkd_status','<',10)
                        ->groupBy('wkd_status')
                        ->groupBy('wkd_recalctime')->get();

            foreach ($res2 as $key2 => $r2) {
                $tuesday_shw = $r2->shw;
                $tuesday_trd = $r2->trd;
                if ($r2->wkd_status==0) $tot_tuesday_tobe = $r2->tot;
                if (($r2->wkd_status==1) && ($r2->wkd_recalctime == "0000-00-00 00:00:00")) {
                    $tot_tuesday_approved_rec = $r2->tot;
                } else {
                    if ($r2->wkd_status==1) $tot_tuesday_approved = $r2->tot;
                }
                $tot_tuesday_live = $r2->wkd_liveexp;
            }

            $day = date("Y-m-d", strtotime("+2 day", $firstDay));
            $wednesday = $day;
            $res2 = DB::table('workdays')
                        ->select(DB::raw('wkd_status, wkd_recalctime, wkd_liveexp, count(*) as tot, MAX(wkd_shift_work) as shw, MAX(wkd_truck_driver) as trd'))
                        ->where('wkd_us_id',$r->us_id)
                        ->where('wkd_day',$day)
                        ->where('wkd_status','<',10)
                        ->groupBy('wkd_status')
                        ->groupBy('wkd_recalctime')->get();

            foreach ($res2 as $key2 => $r2) {
                $wednesday_shw = $r2->shw;
                $wednesday_trd = $r2->trd;
                if ($r2->wkd_status==0) $tot_wednesday_tobe = $r2->tot;
                if (($r2->wkd_status==1) && ($r2->wkd_recalctime == "0000-00-00 00:00:00")) {
                    $tot_wednesday_approved_rec = $r2->tot;
                } else {
                    if ($r2->wkd_status==1) $tot_wednesday_approved = $r2->tot;
                }
                $tot_wednesday_live = $r2->wkd_liveexp;
            }


            $day = date("Y-m-d", strtotime("+3 day", $firstDay));
            $thursday = $day;
            $res2 = DB::table('workdays')
                        ->select(DB::raw('wkd_status, wkd_recalctime, wkd_liveexp, count(*) as tot, MAX(wkd_shift_work) as shw, MAX(wkd_truck_driver) as trd'))
                        ->where('wkd_us_id',$r->us_id)
                        ->where('wkd_day',$day)
                        ->where('wkd_status','<',10)
                        ->groupBy('wkd_status')
                        ->groupBy('wkd_recalctime')->get();

            foreach ($res2 as $key2 => $r2) {
                $thursday_shw = $r2->shw;
                $thursday_trd = $r2->trd;
                if ($r2->wkd_status==0) $tot_thursday_tobe = $r2->tot;
                if (($r2->wkd_status==1) && ($r2->wkd_recalctime == "0000-00-00 00:00:00")) {
                    $tot_thursday_approved_rec = $r2->tot;
                } else {
                    if ($r2->wkd_status==1) $tot_thursday_approved = $r2->tot;
                }
                $tot_thursday_live = $r2->wkd_liveexp;
            }

            $day = date("Y-m-d", strtotime("+4 day", $firstDay));
            $friday = $day;
            $res2 = DB::table('workdays')
                        ->select(DB::raw('wkd_status, wkd_recalctime, wkd_liveexp, count(*) as tot, MAX(wkd_shift_work) as shw, MAX(wkd_truck_driver) as trd'))
                        ->where('wkd_us_id',$r->us_id)
                        ->where('wkd_day',$day)
                        ->where('wkd_status','<',10)
                        ->groupBy('wkd_status')
                        ->groupBy('wkd_recalctime')->get();

            foreach ($res2 as $key2 => $r2) {            
                $friday_shw = $r2->shw;
                $friday_trd = $r2->trd;
                if ($r2->wkd_status==0) $tot_friday_tobe = $r2->tot;
                if (($r2->wkd_status==1) && ($r2->wkd_recalctime == "0000-00-00 00:00:00")) {
                    $tot_friday_approved_rec = $r2->tot;
                } else {
                    if ($r2->wkd_status==1) $tot_friday_approved = $r2->tot;
                }
                $tot_friday_live = $r2->wkd_liveexp;
            }

            $day = date("Y-m-d", strtotime("+5 day", $firstDay));
            $saturday = $day;
            $res2 = DB::table('workdays')
                        ->select(DB::raw('wkd_status, wkd_recalctime, wkd_liveexp, count(*) as tot, MAX(wkd_shift_work) as shw, MAX(wkd_truck_driver) as trd'))
                        ->where('wkd_us_id',$r->us_id)
                        ->where('wkd_day',$day)
                        ->where('wkd_status','<',10)
                        ->groupBy('wkd_status')
                        ->groupBy('wkd_recalctime')->get();

            foreach ($res2 as $key2 => $r2) {
                $saturday_shw = $r2->shw;
                $saturday_trd = $r2->trd;
                if ($r2->wkd_status==0) $tot_saturday_tobe = $r2->tot;
                if (($r2->wkd_status==1) && ($r2->wkd_recalctime == "0000-00-00 00:00:00")) {
                    $tot_saturday_approved_rec = $r2->tot;
                } else {
                    if ($r2->wkd_status==1) $tot_saturday_approved = $r2->tot;
                }
                $tot_saturday_live = $r2->wkd_liveexp;
            }

            $day = date("Y-m-d", strtotime("+6 day", $firstDay));
            $sunday = $day;
            $res2 = DB::table('workdays')
                        ->select(DB::raw('wkd_status, wkd_recalctime, wkd_liveexp, count(*) as tot, MAX(wkd_shift_work) as shw, MAX(wkd_truck_driver) as trd'))
                        ->where('wkd_us_id',$r->us_id)
                        ->where('wkd_day',$day)
                        ->where('wkd_status','<',10)
                        ->groupBy('wkd_status')
                        ->groupBy('wkd_recalctime')->get();

            foreach ($res2 as $key2 => $r2) {
                $sunday_shw = $r2->shw;
                $sunday_trd = $r2->trd;
                if ($r2->wkd_status==0) $tot_sunday_tobe = $r2->tot;
                if (($r2->wkd_status==1) && ($r2->wkd_recalctime == "0000-00-00 00:00:00")) {
                    $tot_sunday_approved_rec = $r2->tot;
                } else {
                    if ($r2->wkd_status==1) $tot_sunday_approved = $r2->tot;
                }
                $tot_sunday_live = $r2->wkd_liveexp;
            }

            $res2 = DB::table('workdays')->select(DB::raw('count(distinct wkd_recalctime) as tot'))
                    ->where('wkd_us_id',$r->us_id)
                    ->whereDate('wkd_day','<',$day)
                    ->whereDate('wkd_day','>=',$weeksStart)
                    ->where('wkd_status',1)->first();
            $tot_recalc = $res2->tot;
            $tot_recalc2 = 0;
            if ($tot_recalc==1) {
                $res2 = DB::table('workdays')->select(DB::raw('count(distinct wkd_recalctime) as tot'))
                    ->where('wkd_us_id',$r->us_id)
                    ->whereDate('wkd_day','<=',$weeksEnd)
                    ->whereDate('wkd_day','>=',$weeksStart)
                    ->whereDate('wkd_recalctime','>','0000-00-00 00:00:00')
                    ->where('wkd_status',1)->first();
                $tot_recalc2 = $res2->tot;
            }

            $r2 = DB::table('workdays')->select(DB::raw('sum(hrs_regular) as regular, sum(hrs_ovt) as ovt, sum(hrs_double) as dbl'))
                    ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                    ->where('wkd_us_id',$r->us_id)
                    ->whereDate('wkd_day','<=',$weeksEnd)
                    ->whereDate('wkd_day','>=',$weeksStart)
                    ->where('hrs_status','<=',1)
                    ->where('wkd_status',1)
                    ->whereRaw('(hrs_jobid=' . $this->CODE_DRIVE .' or hrs_jobid = ' . $this->CODE_DRIVE_CDL . ')')
                    ->first();

            $tot_regular_drive = $this->showTime($r2->regular);
            $tot_ovt_drive = $this->showTime($r2->ovt);
            $tot_dbl_drive = $this->showTime($r2->dbl);
            $tot_drive = $this->showTime($r2->regular + $r2->ovt + $r2->dbl);

            $tot_regular=$r2->regular;
            $tot_ovt = $r2->ovt;
            $tot_dbl = $r2->dbl;
            $tot_tot =$r2->regular + $r2->ovt + $r2->dbl;

            $r2 = DB::table('workdays')->select(DB::raw('sum(hrs_regular) as regular, sum(hrs_ovt) as ovt, sum(hrs_double) as dbl'))
                    ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                    ->where('wkd_us_id',$r->us_id)
                    ->whereDate('wkd_day','<=',$weeksEnd)
                    ->whereDate('wkd_day','>=',$weeksStart)
                    ->where('hrs_status','<=',1)
                    ->where('wkd_status',1)
                    ->where('hrs_jobid',$this->CODE_SHOP)                    
                    ->first();

            $tot_regular_shop = $this->showTime($r2->regular);
            $tot_ovt_shop = $this->showTime($r2->ovt);
            $tot_dbl_shop = $this->showTime($r2->dbl);
            $tot_shop = $this->showTime($r2->regular + $r2->ovt + $r2->dbl);

            $tot_regular+=$r2->regular;
            $tot_ovt += $r2->ovt;
            $tot_dbl += $r2->dbl;
            $tot_tot += $r2->regular + $r2->ovt + $r2->dbl;

            $r2 = DB::table('workdays')->select(DB::raw('sum(hrs_regular) as regular, sum(hrs_ovt) as ovt, sum(hrs_double) as dbl'))
                    ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                    ->where('wkd_us_id',$r->us_id)
                    ->whereDate('wkd_day','<=',$weeksEnd)
                    ->whereDate('wkd_day','>=',$weeksStart)
                    ->where('hrs_status','<=',1)
                    ->where('wkd_status',1)
                    ->whereRaw('not (hrs_jobid=' . $this->CODE_SHOP .' or hrs_jobid=' . $this->CODE_DRIVE .' or hrs_jobid = ' . $this->CODE_DRIVE_CDL . ')')
                    ->first();

            $tot_regular_jobs = $this->showTime($r2->regular);
            $tot_ovt_jobs = $this->showTime($r2->ovt);
            $tot_dbl_jobs = $this->showTime($r2->dbl);
            $tot_jobs = $this->showTime($r2->regular + $r2->ovt + $r2->dbl);

            $tot_regular+=$r2->regular;
            $tot_ovt += $r2->ovt;
            $tot_dbl += $r2->dbl;
            $tot_tot += $r2->regular + $r2->ovt + $r2->dbl;

            $tot_regular = $this->showTime($tot_regular);
            $tot_ovt = $this->showTime($tot_ovt);
            $tot_dbl = $this->showTime($tot_dbl);
            $tot_tot = $this->showTime($tot_tot);
            $mus_id = $r->us_id;

            $r->out_em = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '">' . $r->firstname . " " . $r->lastname .'</a>';           
            
            $r->out_mon = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '?wkd_day=' . $monday . '">' . $this->showCheck($tot_monday_tobe, $tot_monday_approved_rec, $tot_monday_approved, $monday_shw, $monday_trd, $tot_monday_live, (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$monday]) && $foundFilter[$r->us_id][$monday]==1),$SCOMPANYCALC) .'</a>';
            $r->out_mon_sel = (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$monday]) && $foundFilter[$r->us_id][$monday]==1);

            $r->out_tue = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '?wkd_day=' . $tuesday . '">' . $this->showCheck($tot_tuesday_tobe, $tot_tuesday_approved_rec, $tot_tuesday_approved, $tuesday_shw, $tuesday_trd, $tot_tuesday_live, (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$tuesday]) && $foundFilter[$r->us_id][$tuesday]==1),$SCOMPANYCALC) .'</a>';
            $r->out_tue_sel = (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$tuesday]) && $foundFilter[$r->us_id][$tuesday]==1);

            $r->out_wed = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '?wkd_day=' . $wednesday . '">' . $this->showCheck($tot_wednesday_tobe, $tot_wednesday_approved_rec, $tot_wednesday_approved, $wednesday_shw, $wednesday_trd, $tot_wednesday_live, (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$wednesday]) && $foundFilter[$r->us_id][$wednesday]==1),$SCOMPANYCALC) .'</a>';
            $r->out_wed_sel = (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$wednesday]) && $foundFilter[$r->us_id][$wednesday]==1);
 
            $r->out_thu = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '?wkd_day=' . $thursday . '">' . $this->showCheck($tot_thursday_tobe, $tot_thursday_approved_rec, $tot_thursday_approved, $thursday_shw, $thursday_trd, $tot_thursday_live, (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$thursday]) && $foundFilter[$r->us_id][$thursday]==1),$SCOMPANYCALC) .'</a>';
            $r->out_thu_sel = (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$thursday]) && $foundFilter[$r->us_id][$thursday]==1);

            $r->out_fri = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '?wkd_day=' . $friday . '">' . $this->showCheck($tot_friday_tobe, $tot_friday_approved_rec, $tot_friday_approved, $friday_shw, $friday_trd, $tot_friday_live, (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$friday]) && $foundFilter[$r->us_id][$friday]==1),$SCOMPANYCALC) .'</a>';
            $r->out_fri_sel = (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$friday]) && $foundFilter[$r->us_id][$friday]==1);

            $r->out_sat = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '?wkd_day=' . $saturday . '">' . $this->showCheck($tot_saturday_tobe, $tot_saturday_approved_rec, $tot_saturday_approved, $saturday_shw, $saturday_trd, $tot_saturday_live, (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$saturday]) && $foundFilter[$r->us_id][$saturday]==1),$SCOMPANYCALC) .'</a>';
            $r->out_sat_sel = (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$saturday]) && $foundFilter[$r->us_id][$saturday]==1);

            $r->out_sun = '<a href="' . route('admin.weekcalculate.calendar',[1,$r->us_id,$monday,$sunday,$weeksStart,$weeksEnd,$selectedyear]) . '?wkd_day=' . $sunday . '">' . $this->showCheck($tot_sunday_tobe, $tot_sunday_approved_rec, $tot_sunday_approved, $sunday_shw, $sunday_trd, $tot_sunday_live, (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$sunday]) && $foundFilter[$r->us_id][$sunday]==1),$SCOMPANYCALC) .'</a>';
            $r->out_sun_sel = (isset($foundFilter[$r->us_id]) && isset($foundFilter[$r->us_id][$sunday]) && $foundFilter[$r->us_id][$sunday]==1);
                                    
            $r->out_tot = '<table border="1" width="100%">
                                <tr bgcolor="#DDDDDD">
                                    <th>&nbsp;</th>
                                    <td align="center"><b>' . Lang::get('weekcalculate/title.reg') . '</b></td>
                                    <td align="center"><b>' . Lang::get('weekcalculate/title.ovt') . '</b></td>';
            if ($SCOMPANYCALC==0){
                $r->out_tot .= '<td align="center"><b>' . Lang::get('weekcalculate/title.dbl') . '</b></td>';
            } 
            $r->out_tot .=          '<td align="center"><b>' . Lang::get('weekcalculate/title.dbl') . '</b></td>
                                </tr>
                                <tr>
                                    <th  bgcolor="#DDDDDD">' . Lang::get('weekcalculate/title.drive') . '</th>
                                    <td align="center">' . $tot_regular_drive . '</td>
                                    <td align="center">' . $tot_ovt_drive . '</td>';
            if ($SCOMPANYCALC==0){
                $r->out_tot .=      '<td align="center">' . $tot_dbl_drive . "</td>";   
            }
            $r->out_tot .=          '<td align="center">' . $tot_drive . '</td>
                                </tr>
                                <tr>
                                    <th  bgcolor="#DDDDDD">' . Lang::get('weekcalculate/title.drive') . '</th>
                                    <td align="center">' . $tot_regular_shop . '</td>
                                    <td align="center">' . $tot_ovt_shop . '</td>';
            if ($SCOMPANYCALC==0){  
                $r->out_tot .=      '<td align="center">' . $tot_dbl_shop . "</td>";
            }
            $r->out_tot .=          '<td align="center">' . $tot_shop .'</td>
                                </tr>
                                <tr>
                                    <th  bgcolor="#DDDDDD">' . Lang::get('weekcalculate/title.jobs') . '</th>
                                    <td align="center">' . $tot_regular_jobs . '</td>
                                    <td align="center">' . $tot_ovt_jobs . '</td>';
            if ($SCOMPANYCALC==0){
                $r->out_tot .=      '<td align="center">' . $tot_dbl_jobs . "</td>"; 
            } 
            $r->out_tot .=          '<td align="center">' . $tot_jobs . '</td>
                                </tr>

                                <tr  bgcolor="#DDDDDD">
                                    <th>' . Lang::get('weekcalculate/title.toth') . '</th>
                                    <td align="center">' . $tot_regular . '</td>
                                    <td align="center">' . $tot_ovt . '</td>';
            if ($SCOMPANYCALC==0){
                $r->out_tot .=      '<td align="center">' . $tot_dbl . "</td>";
            } 
            $r->out_tot .=          '<td align="center">' . $tot_tot . '</td>
                                </tr>
                        </table>';
            
            $r->out_torecalculate = (($tot_recalc>1)||($tot_recalc2==0))  ? "Yes" : "No";
            //$r->out_torecalculate2 = '<a href="weekstart=$weeksStart."&weekend=".$weeksEnd&recalc=1&us_id=$r->us_id">' . Lang::get('weekcalculate/title.recalculate') . '</a>';
            $r->out_torecalculate2 = '<button onclick="' . "recalculatedetail('" . trim($weeksStart) . "','" . trim($weeksEnd) . "','" . trim($r->us_id) . "')" . '" class="btn btn-success">' . Lang::get('weekcalculate/title.recalculate') . '</button>';

        }

        return DataTables::of($res)
                ->rawColumns(['out_em','out_mon','out_tue','out_wed','out_thu','out_fri','out_sat','out_sun','out_tot','out_torecalculate2'])
                ->make(true); 
    }

    public function calendar(Request $req){
        $user = Sentinel::getUser();
        $dat = new \stdClass;
        $dat->fromWeek = $req->fromWeek;
        $dat->us_id = $req->us_id;
        $dat->wkd_day_from = $req->wkd_day_from;
        $dat->wkd_day_to = $req->wkd_day_to;
        $dat->weekstart = $req->weekstart;
        $dat->weekend = $req->weekend;
        $dat->selectedyear = $req->selectedyear;
        $dat->wkd_day = isset($req->wkd_day) ? $req->wkd_day : "";
        $listemployees = array();
        $res = DB::table('employees')
                    ->where('us_sc_id',$user->sc_id)
                    ->where('us_active',1)
                    ->orderBy('lastname')
                    ->orderBy('firstname')->get();
        foreach ($res as $key => $r2) {
            $listemployees[$r2->us_id] = $r2->userId." - ".$r2->lastname." ".$r2->firstname;            
        }
        return view('admin.weekcalculate.calendar')->with(['datainput' => $dat, 'listemployees' => $listemployees, 'listdrillers' => $this->driller, 'listtrucks' => $this->truckdriver, 'listlunch' => $this->lunch]);
    }

    public function getcalendar(Request $req){
        $user = Sentinel::getUser();
        $SCOMPANYCALC = 0;
        $sccalt = DB::table('schultes')->where('sc_id',$user->sc_id)->first();
        if($sccalt){
            $SCOMPANYCALC = $sccalt->sc_calctype;
        }
        $wkd_day = "";
        if(isset($req->wkd_day)){
            $wkd_day = trim($req->wkd_day);
        }        

        $wkd_status = ""; 
        if(isset($req->wkd_status)){
            $wkd_status = trim($req->wkd_status);
        }

        $us_id = $req->us_id;
        $wkd_day_from = $req->wkd_day_from;
        $wkd_day_to = $req->wkd_day_to;
        $fromWeek = $req->fromWeek;
        $minutes = $req->minutes;
        if ($minutes=="") $minutes = 60;

        $listinputs = array($req->fromWeek,$req->us_id,$req->wkd_day_from,$req->wkd_day_to,$req->weekstart,$req->weekend,$req->selectedyear);
        
        $res = DB::table('workdays')
                ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                ->where('wkd_sc_id',$user->sc_id);

        if (strlen($wkd_day) > 0)
            $res = $res->where('wkd_day',$wkd_day);
        if (strlen($wkd_status) > 0)
            $res = $res->where('wkd_status',$wkd_status);
        if (strlen($us_id) > 0)
            $res = $res->where('us_id',$us_id);
        if (strlen($wkd_day_from) >0){
            $res = $res->whereDate('wkd_day','>=',$wkd_day_from)
                       ->whereDate('wkd_day','<=',$wkd_day_to); 
        } 

        $res = $res->orderBy('lastname')->orderBy('firstname')->orderBy('wkd_day')->get(); 
        $textout = "";        
        $clr=0;
        foreach ($res as $key => $r) {            
            $employeeDept = $r->departmentNr;
            $typeOfEmployee=$this->typeOfEmployee($employeeDept);
            $color="#CCCCFF";
            if ($clr %2==0) $color="#AAAAFF";
            $clr++;

            $textout .= '<div class="row" style="background-color: #FFFFFF"><br></div>';
            $textout .= '<div class="row" style="background-color: ' . $color . '">';
            //$textout .= '<center>';
            $textout .= '<br>';
            $textout .= '<table border="1" width="100%">';
            $textout .= '<tr><td>';
            $textout .= '<table border="1" style="background-color: #DDDDDD; padding: 10px;" width="100%">';
            $textout .= '<tr><td width="40%">';
            $textout .= '<table border="0" width="100%">';
            $textout .= '<tr>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.employee') . ':</th>';
            $textout .= '<td>';
            $textout .= $r->firstname." ".$r->lastname;
            $textout .= '</td>';
            $textout .= '</tr><tr><th>'. Lang::get('weekcalculate/title.workday') .':</th>';
            $textout .= '<td>';            
            $textout .= $this->toUSDate($r->wkd_day);
            $textout .= '</td>';
            $textout .= '</tr><tr>';
            $textout .= '<th>';
            $textout .= Lang::get('weekcalculate/title.drillerh') . ':';
            $textout .= '</th>';
            $textout .= '<td>';
            $textout .= $this->driller[$r->wkd_driller_helper];
            $textout .= '</td>';
            $textout .= '</tr><tr>';            
            $textout .= '<th>';
            $textout .= Lang::get('weekcalculate/title.truckdriver') . ':';
            $textout .= '</th>';
            $textout .= '<td>';
            $textout .= $this->truckdriver[$r->wkd_truck_driver];
            $textout .= '</tr><tr>';
            $textout .= '<th>';
            $textout .= Lang::get('weekcalculate/title.lunch') . ':';
            $textout .= '</th>';
            $textout .= '<td>';
            $textout .= $this->lunch[$r->wkd_lunch];

            if ($r->wkd_lunch > 0) $textout .= "&nbsp;&nbsp;&nbsp;(".$this->toUSDateTime($r->wkd_lunchtime).")";
            
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '<tr>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.liveexpenses') . ':</th>';
            $textout .= '<td>';
            $textout .= $this->lunch[$r->wkd_liveexp];
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '<tr><th>' . Lang::get('weekcalculate/title.miles') .':</th>';
            $textout .= '<td>';
            $textout .= $r->wkd_miles;
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '<tr><th>'. Lang::get('weekcalculate/title.shiftwork') .':</th>';
            $textout .= '<td>' . $r->wkd_shift_work . '</td>';
            $textout .= '</tr>';
            $textout .= '<tr><th>' . Lang::get('weekcalculate/title.enddayrt') . ':</th>';
            $textout .= '<td>';
            $textout .= $this->toUSDateTime($r->wkd_end_realtime);
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '<tr><th>' . Lang::get('weekcalculate/title.lastpos') . ':</th>';
            $textout .= '<td>';

            if (($r->wkd_gps_latitude + $r->wkd_gps_longitude) <> 0) {
                $textout .= '<a href="http://maps.google.com/?q=' . $r->wkd_gps_latitude . ',' .$r->wkd_gps_longitude .'" target=_new><img src="' . asset('img/mapicon.png') . '" border=0 height=30></a>';
            } else {
                $textout .= '<font color="red">' . Lang::get('weekcalculate/title.notavailable') . '</font>';
            
            }
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</td><td>';
            $textout .= '<table border="0" width="400">';
            $textout .= '<tr><td><b>' . Lang::get('weekcalculate/title.notes') . ':</b><br>';
            $textout .= '<font color="#FF0000">' . nl2br(str_replace("\\n","\n",$r->wkd_notes)) . '</font>';
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</td>';
            $textout .= '<td valign="bottom" width="200">';
            $textout .= '<table border="1" height="120" width="200">';
            $textout .= '<tr><td>';
            $textout .= '<center>';            
            $textout .= '<select name="wkd_locked" onchange="lockdate(this,' . $r->wkd_id .')">';
            $sel0="";
            $sel1="";
            if ($r->wkd_locked==0) $sel0="SELECTED";
            if ($r->wkd_locked==1) $sel1="SELECTED";

            $textout .= '<option value="0" ' . $sel0 . '>' . Lang::get('weekcalculate/title.unlock') . '</option>';
            $textout .= '<option value="1" ' . $sel1 . '>' . Lang::get('weekcalculate/title.lock') . '</option>';
            $textout .= '</select>';
            //$textout .= '</form>';
            $textout .= '</center>';
            $textout .= '</td></tr>';
            $textout .= '</table>';
            $textout .= '<form action="">';
            $textout .= '<table border="1" style="background-color: #FFFFFF" width="100%"">';
            $textout .= '<tr>';
            $textout .= '<td align="center">';
                            
            $status_pending="";
            $status_approved="";
            $status_refused="";
            if ($r->wkd_status==0) $status_pending="checked='checked'";
            if ($r->wkd_status==1) $status_approved="checked='checked'";
            if ($r->wkd_status==10) $status_refused="checked='checked'";

            $textout .= '<input type="radio" name="active" value="0" onclick="save_val(0,' . $r->wkd_id . ')" ' . $status_pending .'/>&nbsp;' . Lang::get('weekcalculate/title.pending');
            $textout .= '</td>';
            $textout .= '<td align="center">';
            $textout .= '<input type="radio" name="active" value="1" onclick="save_val(1,' . $r->wkd_id . ')" ' . $status_approved .'/>&nbsp;' . Lang::get('weekcalculate/title.approved');
            $textout .= '</td>';
            $textout .= '<td align="center">';
            $textout .= '<input type="radio" name="active" value="10" onclick="save_val(10,' . $r->wkd_id . ')" ' . $status_refused .'/>&nbsp;' . Lang::get('weekcalculate/title.refused');
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</form>';
            $textout .= '</td></tr>';
            $textout .= '<tr><td>';
                            
            $isTruckDriver = $r->wkd_truck_driver;
            $totalTime = 60*8;
            $totalTimeOVT=0;
            $totalTimeWorked = 0;
            $totalTimeDrive = 0;
            $totalTimeShop = 0;

            $resb = DB::table('hours')
                        ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                        ->where('hrs_wkd_id',$r->wkd_id)
                        ->orderBy('hrs_starttime')->get();
    
            $textout .= '<table border="1" style="background-color: #EEEEEE; padding: 10px" width="100%" cellspacing="5">';
            $textout .= '<tr>';
            $textout .= '<th>&nbsp;</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.starttime') . '</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.endtime') . '</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.inserttime') . '</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.jobnr') . '</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.jobname') . '</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.regular') . '</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.ovth') . '</th>';
            $textout .= '<th>'. Lang::get('weekcalculate/title.double') . '</th>';
            $textout .= '</tr>';    
                    
            $totRegular = 0;
            $totOvt = 0;
            $totDouble = 0;
            foreach ($resb as $key1 => $rb) {                
                $totRegular=$totRegular+$rb->hrs_regular;
                $totOvt=$totOvt+$rb->hrs_ovt;
                $totDouble=$totDouble+$rb->hrs_double;
                $color="#EEEEEE";
                if ($rb->hrs_status>1) $color="#FF9999";

                if ($rb->hrs_jobid==$this->CODE_DRIVE)  {
                    $rb->description="DRIVE";
                    $rb->nr = $typeOfEmployee."20300";
                }
                if ($rb->hrs_jobid==$this->CODE_DRIVE_CDL)  {
                    $rb->description="DRIVE CDL";
                    // $rb->nr = $typeOfEmployee."20200";
                    $rb->nr = $typeOfEmployee."20300";
                }
                if ($rb->hrs_jobid==$this->CODE_SHOP) {
                    $rb->description="SHOP";
                    $rb->nr = $typeOfEmployee."20100";

                }

                if ($rb->hrs_jobid==$this->CODE_HOLIDAYS) {
                    $rb->nr = $typeOfEmployee."20700";
                    $rb->description = "HOLIDAY";
                }
                if ($rb->hrs_jobid==$this->CODE_VACATION) {
                    $rb->nr = $typeOfEmployee."20400";
                    $rb->description = "VACATION";
                }

                $textout .= '<tr bgcolor="' . $color . '">';
                $textout .= '<td align="center">';
                        
                if (($rb->hrs_gps_start_lat + $rb->hrs_gps_start_lon) <> 0) {
                    $textout .= '<a href="http://maps.google.com/?q=' . $rb->hrs_gps_start_lat .',' . $rb->hrs_gps_start_lon .'" target="_new"><img src="' . asset('img/mapicon.png') . '" border="0" height="30"></a>';
                }
        
                $to_time = strtotime($rb->hrs_starttime);
                $from_time = strtotime($rb->hrs_realtime);
                $mindiff = round(abs($to_time - $from_time) / 60,2);
                $colorbgcell = $color;
                if ($mindiff >= $minutes) $colorbgcell = "#FF3333";
                $textout .= '</td>';
                $textout .= '<td bgcolor="' .$colorbgcell .'">' . $this->toUSDateTime($rb->hrs_starttime) . '</td>';
                $textout .= '<td>' . $this->toUSDateTime($rb->hrs_endtime) . '</td>';
                $textout .= '<td bgcolor="' . $colorbgcell .'">' . $this->toUSDateTime($rb->hrs_realtime) . '</td>';
                $textout .= '<td>' . $rb->nr . '</td>';
                $textout .= '<td>' . $rb->description .'<br>' . $rb->company . '</td>';
                $textout .= '<td align="right">' . $this->showTime($rb->hrs_regular) . '</td>';
                $textout .= '<td align="right">' . $this->showTime($rb->hrs_ovt) . '</td>';
                $textout .= '<td align="right">' . $this->showTime($rb->hrs_double) . '</td>';
                $textout .= '</tr>';
            }                    
            $textout .= '<tr bgcolor="' . $color .'">';
            $textout .= '<th>&nbsp;</th>';
            $textout .= '<th>&nbsp;</th>';
            $textout .= '<th>&nbsp;</th>';
            $textout .= '<th>&nbsp;</th>';
            $textout .= '<th>&nbsp;</th>';
            $textout .= '<td align="right"><b>' . Lang::get('weekcalculate/title.total') .':</b></td>';
            $textout .= '<td align="right"><b>' . $this->showTime($totRegular) . '</b></td>';
            $textout .= '<td align="right"><b>' . $this->showTime($totOvt) . '</b></td>';
            $textout .= '<td align="right"><b>' . $this->showTime($totDouble) . '</b></td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</td><td align="center"><br>';
            $listinputs[7] = $r->wkd_id;
            // $textout .= '<button class="btn btn-success" data-toggle="modal" data-target="#modal_edit" onclick="window.location.href=\'' . route('admin.weekcalculate.editdate',$r->wkd_id) .'\'" style="width:150px;">' . Lang::get('button.edit') .'</button>';
            // $textout .= '<button onclick="openeditdate(' . $r->wkd_id) . ');" class="btn btn-success">' . Lang::get('button.edit') .'</button>';
            $textout .= '<button class="btn btn-success" onclick="openeditdate('.$r->wkd_id.');" style="width:150px">' . Lang::get('button.edit'). '</button>';
            $textout .= '<br><br>';                    
            if ($r->wkd_locked<=0) {
                if ($SCOMPANYCALC==0) {
                    //$textout .= '<a href="' . route('admin.weekcalculate.recalculate',$r->wkd_id) . '"  class="btn btn-warning" onclick="return confirm_delete();" style="width:150px">' . Lang::get('weekcalculate/title.recalculate'). '</a><br>';
                    $textout .= '<button class="btn btn-warning" onclick="recalculate('.$r->wkd_id.');" style="width:150px">' . Lang::get('weekcalculate/title.recalculate'). '</button><br>';
                }
            }
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            //$textout .= '</center>';
            $textout .= '<br/>&nbsp;<br/>';
            $textout .= '<b>' . Lang::get('weekcalculate/title.equipmentused') . '</b>';
            $textout .= '<br/>';
            $textout .= '<table border="1" style="background-color: #EEEEEE; padding: 10px;" width="100%" cellspacing="5">';
            $textout .= '<tr>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.equipment') . '</th>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.starttime') . '</th>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.endtime') . '</th>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.odometerstart') . '</th>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.odometerend') . '</th>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.starthour') . '</th>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.endhour') . '</th>';
            $textout .= '<th>' . Lang::get('weekcalculate/title.miles') . '</th>';
            $textout .= '</tr>';
            $onsite[0] = Lang::get('general.no');
            $onsite[1] = Lang::get('general.yes');
            $inuse[0] = Lang::get('general.inuse');
            $inuse[1] = Lang::get('general.no');
            
            $resb = DB::table('equipments')
                        ->leftJoin('equipusers','equipments.eq_id','=','equipusers.eu_eq_id')
                        ->leftJoin('equiptypes','equipments.eq_et_id','=','equiptypes.et_id')
                        ->where('eu_start','>=',$r->wkd_day . " 00:00:00")
                        ->where('eu_start','<=',$r->wkd_day . " 23:59:59")
                        ->where('eu_us_id',$r->wkd_us_id)
                        ->orderBy('eq_internalcode')
                        ->orderBy('eq_name')->get();
            foreach ($resb as $keyb => $rb) {
                $textout .= '<tr>';
                $textout .= '<td>' . $rb->eq_internalcode . " : " . $rb->eq_name . '</td>';
                $textout .= '<td>' . $this->toUSDate($rb->eu_start);
                if (($rb->eu_start_lat + $rb->eu_start_lon) <> 0) {
                    $textout .= '<a href="http://maps.google.com/?q=' . $rb->eu_start_lat . ',' . $rb->eu_start_lon .'" target="_new"><img src="' . asset('img/mapicon.png') . '" border="0" height="30"></a>';
                } 
                $textout .= '</td>';
                $textout .= '<td>' . $this->toUSDate($rb->eu_end);
                if (($rb->eu_end_lat + $rb->eu_end_lon) <> 0) {
                    $textout .= '<a href="http://maps.google.com/?q=' . $rb->eu_end_lat . ',' . $rb->eu_end_lon . '" target="_new"><img src="' . asset('img/mapicon.png') . '" border="0" height="30"></a>';
                } 
                $textout .= '</td>';
                $textout .= '<td>' . $rb->eu_miles_start . '</td>';
                $textout .= '<td>' . $rb->eu_miles_end . '</td>';
                $textout .= '<td>' . $rb->eu_nrhoursstart . '</td>';
                $textout .= '<td>' . $rb->eu_nrhoursend .'</td>';
                        
                if ($rb->eq_candrive==1) {                                    
                    // check if there are points from the gps  assigned                        
                    $resc = DB::table('gps')
                                    ->where('gps_us_id',$rb->eu_us_id)
                                    ->whereDate('gps_datetime','>=',$rb->eu_start)
                                    ->whereDate('gps_datetime','<=',$rb->eu_end)
                                    ->where('gps_eq_id',$rb->eq_id)
                                    ->orderBy('gps_datetime')->get();
                    $totMiles = 0;
                    $startcalc = true;
                    $oldlat = 0;
                    $oldlong = 0;
                    $laststate = "";
                    $strdist = "";
                    foreach ($resc as $keyc => $rc) {                            
                        if ($startcalc) {                                
                            $laststate = $rc->gps_state;
                            $strdist = $laststate.": ";
                            $startcalc = false;
                        } else {
                            if ($laststate <> $rc->gps_state) {
                                $totMiles = floor($totMiles);
                                $strdist = $strdist.$totMiles."&nbsp;&nbsp;&nbsp;";
                                $totMiles=0;
                            } else {
                                $dist = $this->distance($oldlat, $oldlong, $rc->gps_lat, $rc->gps_long, "M");
                                $totMiles = $totMiles + $dist;
                            }
                        }                            
                        $oldlat = $rc->gps_lat;
                            $oldlong = $rc->gps_long;
                    } 
                    $totMiles = floor($totMiles);
                    $strdist = $strdist.$totMiles."&nbsp;&nbsp;&nbsp;";
                                                                    
                    $textout .= "<td>";
                    $textout .= '<a href="" class="btn btn-warning">';
                    $textout .= Lang::get('weekcalculate/title.driven') . ":" . $strdist;
                    if ($totMilesToAssign>0) {
                    //  $textout .= "- To Assign: $totMilesToAssign";                            
                    }
                    $textout .= "</a></td>";                        
                } else {
                    $textout .= "<td>" . Lang::get('general.no') . " " . Lang::get('weekcalculate/title.drive') . "</td>";                        
                }
                $textout .= '</tr>';
            }                   
            $textout .= '</table>';
            $textout .= '<br/>&nbsp;<br/>';
            $textout .= '</div><br/><br/>';        
        }
        return $textout;
    }

    public function recalculate(Request $req){
        $wkd_id = $req->wkd_id;
        $isdel = $req->isdel; 
        $user = Sentinel::getUser();
        $SCOMPANYCALC = 0;
        $sccalt = DB::table('schultes')->where('sc_id',$user->sc_id)->first();
        if($sccalt){
            $SCOMPANYCALC = $sccalt->sc_calctype;
        }
        if($wkd_id > 0){
            if ($isdel > 0) {
                $res = DB::table('hours')->where('hrs_wkd_id',$wkd_id)->delete();
                $res = DB::table('workdays')->where('wkd_id',$wkd_id)->delete();            
            }
            if ($SCOMPANYCALC==0) $this->calculateTime($wkd_id);
            return 1;
        }else{
            return 0;
        }
    }

    public function lockdate(Request $req){
        $user = Sentinel::getUser();
        $toLock = $req->wkd_id_lock;
        $wkd_lock = $req->wkd_lock;
        if ($toLock>0) {
            $data = array(
                'wkd_locked' => $wkd_lock
            );            
            $res = DB::table('workdays')->where('wkd_id',$toLock)->where('wkd_sc_id',$user->sc_id)->update($data);            
            if(!$res) return 0;
        }
        return 1;        
    }

    public function savestatus(Request $req){
        $user = Sentinel::getUser();
        $wkd_id=$req->wkd_id;
        $status=$req->status;    
        $res = DB::table('workdays')->where('wkd_id',$wkd_id)->where('wkd_sc_id',$user->sc_id)->update(['wkd_status' => $status]);
        return 1;
    }

    public function editdate(Request $req){
        $user = Sentinel::getUser();        
        $wkd_id = $req->wkd_id;
        $res = DB::table('workdays')
                    ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                    ->where('wkd_id',$wkd_id)
                    ->where('wkd_sc_id',$user->sc_id)->first();

        $res->wkd_day = $this->toUSDate($res->wkd_day);
        $res->wkd_lunchtimed = "";
        $res->wkd_lunchtimeh = "";
        if($res->wkd_lunchtime != "0000-00-00 00:00:00"){
            $str = explode(" ", $this->toUSDateTime($res->wkd_lunchtime));
            $res->wkd_lunchtimed = $str[0];
            $res->wkd_lunchtimeh = $str[1];
        }        
        
        $employeeDept = $res->departmentNr;
        $typeOfEmployee = $this->typeOfEmployee($employeeDept);

        $textout = '<table border="1" style="background-color: #EEEEEE; padding: 10px;" width="100%" cellspacing="5">';
        $textout .= '<tr>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.action') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.starttime') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.endtime') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.jobnr') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.jobname') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.regular') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.ovth') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.double') . '</th>';
        $textout .= '</tr>';

        $hours = DB::table('hours')
                    ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                    ->where('hrs_wkd_id',$wkd_id)->orderBy('hrs_starttime')->get();
        
        $totRegular = 0;
        $totOvt = 0;
        $totDouble = 0;
        $color="#EEEEEE";
        foreach ($hours as $key => $rb) {            
            $totRegular=$totRegular+$rb->hrs_regular;
            $totOvt=$totOvt+$rb->hrs_ovt;
            $totDouble=$totDouble+$rb->hrs_double;            
            if ($rb->hrs_status>1) $color="#FF9999";

            if ($rb->hrs_jobid==$this->CODE_DRIVE)  {
                $rb->description="DRIVE";
                $rb->nr = $typeOfEmployee."20300";
            }
            if ($rb->hrs_jobid==$this->CODE_DRIVE_CDL)  {
                $rb->description="DRIVE CDL";
                //$rb->nr = $typeOfEmployee."20200";
                $rb->nr = $typeOfEmployee."20300";
            }
            if ($rb->hrs_jobid==$this->CODE_SHOP) {
                $rb->description="SHOP";
                $rb->nr = $typeOfEmployee."20100";
            }

            if ($rb->hrs_jobid==$this->CODE_HOLIDAYS) {
                $rb->nr = $typeOfEmployee."20700";
                $rb->description = "HOLIDAY";
            }

            if ($rb->hrs_jobid==$this->CODE_VACATION) {
                $rb->nr = $typeOfEmployee."20400";
                $rb->description = "VACATION";
            }

            $textout .= '<tr bgcolor="' . $color . '">';
            $textout .= '<td align="center">';
            $textout .= '<button onclick="openedithour('. $rb->hrs_id . ',' . $wkd_id . ')" class="btn btn-success">' . Lang::get('button.edit') . '</button>';
            $textout .= '</td>';
            $textout .= '<td>' . $this->toUSDateTime($rb->hrs_starttime) . '</td>';
            $textout .= '<td>' . $this->toUSDateTime($rb->hrs_endtime) . '</td>';
            $textout .= '<td>' . $rb->nr . '</td>';
            $textout .= '<td>' . $rb->description . "<br/>" . $rb->company . '</td>';
            $textout .= '<td align="right">' . $this->showTime($rb->hrs_regular) . '</td>';
            $textout .= '<td align="right">' . $this->showTime($rb->hrs_ovt) . '</td>';
            $textout .= '<td align="right">' . $this->showTime($rb->hrs_double) . '</td>';
            $textout .= '</tr>';
        }
        $textout .= '<tr bgcolor="' . $color . '">';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<td align="right"><b>' . Lang::get('weekcalculate/title.total') . '</b></td>';
        $textout .= '<td align="right"><b>' . $this->showTime($totRegular) . '</b></td>';
        $textout .= '<td align="right"><b>' . $this->showTime($totOvt) . '</b></td>';
        $textout .= '<td align="right"><b>' . $this->showTime($totDouble) . '</b></td>';
        $textout .= '</tr>';
        $textout .= '</table><br>';
        $textout .= '<div style="width:100%;" align="center">';
        $textout .= '<button onclick="openedithour(0,' . $wkd_id . ')" class="btn btn-success">' . Lang::get('weekcalculate/title.addjob') . '</button></div>';        
        $res->hours = $textout;
        return json_encode($res);
    }

    public function edithour(Request $req){
        $user = Sentinel::getUser();        
        $wkd_id = $req->wkd_id;
        $hrs_id = $req->hrs_id;
        $res = DB::table('workdays')
                    ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                    ->where('wkd_id',$wkd_id)
                    ->where('wkd_sc_id',$user->sc_id)->first();
                
        $employeeDept = $res->departmentNr;
        $typeOfEmployee = $this->typeOfEmployee($employeeDept);

        $textout = '<table border="1" style="background-color: #EEEEEE; padding: 10px;" width="100%" cellspacing="5">';
        $textout .= '<tr>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.action') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.starttime') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.endtime') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.jobnr') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.jobname') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.regular') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.ovth') . '</th>';
        $textout .= '<th>' . Lang::get('weekcalculate/title.double') . '</th>';
        $textout .= '</tr>';

        $hours = DB::table('hours')
                    ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                    ->where('hrs_wkd_id',$wkd_id)->orderBy('hrs_starttime')->get();
        $jobdescription = "";
        $totRegular = 0;
        $totOvt = 0;
        $totDouble = 0;
        $color="#EEEEEE";
        foreach ($hours as $key => $rb) {            
            $totRegular=$totRegular+$rb->hrs_regular;
            $totOvt=$totOvt+$rb->hrs_ovt;
            $totDouble=$totDouble+$rb->hrs_double;            
            if ($rb->hrs_status>1) $color="#FF9999";

            if ($rb->hrs_jobid==$this->CODE_DRIVE)  {
                $rb->description="DRIVE";
                $rb->nr = $typeOfEmployee."20300";
            }
            if ($rb->hrs_jobid==$this->CODE_DRIVE_CDL)  {
                $rb->description="DRIVE CDL";
                //$rb->nr = $typeOfEmployee."20200";
                $rb->nr = $typeOfEmployee."20300";
            }
            if ($rb->hrs_jobid==$this->CODE_SHOP) {
                $rb->description="SHOP";
                $rb->nr = $typeOfEmployee."20100";
            }

            if ($rb->hrs_jobid==$this->CODE_HOLIDAYS) {
                $rb->nr = $typeOfEmployee."20700";
                $rb->description = "HOLIDAY";
            }

            if ($rb->hrs_jobid==$this->CODE_VACATION) {
                $rb->nr = $typeOfEmployee."20400";
                $rb->description = "VACATION";
            }

            $textout .= '<tr bgcolor="' . $color . '">';
            $textout .= '<td align="center">';
            if($rb->hrs_id == $hrs_id){
                $textout .= '<img src="' . asset('img/edit.png') . '" border=0 height=20>';
                $jobdescription = $rb->description;
            }
            $textout .= '</td>';
            $textout .= '<td>' . $this->toUSDateTime($rb->hrs_starttime) . '</td>';
            $textout .= '<td>' . $this->toUSDateTime($rb->hrs_endtime) . '</td>';
            $textout .= '<td>' . $rb->nr . '</td>';
            $textout .= '<td>' . $rb->description . "<br/>" . $rb->company . '</td>';
            $textout .= '<td align="right">' . $this->showTime($rb->hrs_regular) . '</td>';
            $textout .= '<td align="right">' . $this->showTime($rb->hrs_ovt) . '</td>';
            $textout .= '<td align="right">' . $this->showTime($rb->hrs_double) . '</td>';
            $textout .= '</tr>';
        }
        $textout .= '<tr bgcolor="' . $color . '">';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<th>&nbsp;</th>';
        $textout .= '<td align="right"><b>' . Lang::get('weekcalculate/title.total') . '</b></td>';
        $textout .= '<td align="right"><b>' . $this->showTime($totRegular) . '</b></td>';
        $textout .= '<td align="right"><b>' . $this->showTime($totOvt) . '</b></td>';
        $textout .= '<td align="right"><b>' . $this->showTime($totDouble) . '</b></td>';
        $textout .= '</tr>';
        $textout .= '</table><br>';        

        $out = new \stdClass;
        $out->edit = 0;
        if($hrs_id > 0){
            $out = DB::table('hours')->where('hrs_id',$hrs_id)->first();
            $out->edit = 1;
            $out->hrs_starttimed = "";
            $out->hrs_starttimeh = "";
            $out->hrs_endtimed = "";
            $out->hrs_endtimeh = "";
            if($out->hrs_starttime != "0000-00-00 00:00:00"){
                $str = explode(" ", $this->toUSDateTime($out->hrs_starttime));
                $out->hrs_starttimed = $str[0];
                $out->hrs_starttimeh = $str[1];
            }   
            if($out->hrs_endtime != "0000-00-00 00:00:00"){
                $str = explode(" ", $this->toUSDateTime($out->hrs_endtime));
                $out->hrs_endtimed = $str[0];
                $out->hrs_endtimeh = $str[1];
            }
            $out->jobdescription = $jobdescription;     
        }        
        $out->listhours = $textout;
        return json_encode($out);
    }

    public function deletedate(Request $req){
        $user = Sentinel::getUser();
        $wkd_id = $req->wkd_id;
        $wkd = DB::table('workdays')->where('wkd_id',$wkd_id)->where('wkd_sc_id',$user->sc_id)->delete();
        if($wkd){
            $hours = DB::table('hours')->where('hrs_wkd_id',$wkd_id)->delete();    
            return 1;
        }else{
            return 0;
        }
    }

    public function deletehour(Request $req){
        $hrs_id = $req->hrs_id;
        $hour = DB::table('hours')->where('hrs_id',$hrs_id)->delete();
        if($hour){
            return 1;
        }else{
            return 0;
        }
    }

    public function storedate(Request $req){
        $user = Sentinel::getUser();
        $wkd_id = $req->wkd_id;
        $wkd_us_id = $req->wkd_us_id;
        $wkd_day = "0000-00-00";
        if($req->wkd_day != '') $wkd_day = Carbon::parse($req->wkd_day)->format('Y-m-d');
        $wkd_driller_helper = $req->wkd_driller_helper;
        $wkd_truck_driver = $req->wkd_truck_driver;
        $wkd_liveexp = $req->wkd_liveexp;
        $wkd_lunch = $req->wkd_lunch;
        $dd = "0000-00-00";
        if($req->wkd_lunchtimed != ""){
            $dd = Carbon::parse($req->wkd_lunchtimed)->format('Y-m-d');
        }
        $hh = "00:00:00";
        if($req->wkd_lunchtimeh) $hh = $req->wkd_lunchtimeh;
        $wkd_lunchtime = $dd . " " . $hh;
        $wkd_miles = $req->wkd_miles;
        $wkd_shift_work = $req->wkd_shift_work;
        $wkd_notes = $req->wkd_notes;

        $data = array(
            'wkd_sc_id' => $user->sc_id,
            'wkd_us_id' => $wkd_us_id,
            'wkd_driller_helper' => $wkd_driller_helper,
            'wkd_truck_driver' => $wkd_truck_driver,
            'wkd_liveexp' => $wkd_liveexp,
            'wkd_lunch' => $wkd_lunch,
            'wkd_lunchtime' => $wkd_lunchtime,
            'wkd_miles' => $wkd_miles,
            'wkd_day' => $wkd_day,
            'wkd_shift_work' => $wkd_shift_work,            
            'wkd_timestamp' => date('Y-m-d H:i:s'),
            'wkd_status'    => 0,            
            'wkd_notes'     => $wkd_notes,
            'wkd_recalctime'    => '0000-00-00 00:00:00',
            'wkd_locked'    => 0,
        );
        $res = null;
        //edit
        if($wkd_id > 0){
            $res = DB::table('workdays')->where('wkd_id',$wkd_id)->where('wkd_sc_id',$user->sc_id)->update($data);
        }else{        
            $data['wkd_end_realtime']  = '0000-00-00 00:00:00';
            $data['wkd_gps_latitude'] = 0;
            $data['wkd_gps_longitude'] = 0;
            $res = DB::table('workdays')->insert($data);
        }    
        
        if($res) return 1;
        else return 0;
    }

    public function storehour(Request $req){
        $user = Sentinel::getUser();
        $hrs_wkd_id = $req->hrs_wkd_id;
        $hrs_id = $req->hrs_id;
        $hrs_starttimed = $req->hrs_starttimed;
        $hrs_endtimed = $req->hrs_endtimed;
        $hrs_starttimeh = $req->hrs_starttimeh;
        $hrs_endtimeh = $req->hrs_endtimeh;
        $hrs_job = $req->hrs_job;
        $hrs_jobid = $req->hrs_jobid;
        $hrs_regular = $req->hrs_regular;
        $hrs_ovt = $req->hrs_ovt;
        $hrs_double = $req->hrs_double;

        $hrs_starttime = '0000-00-00 00:00:00';
        $hrs_endtime = '0000-00-00 00:00:00';
        if($hrs_starttimed != ''){
            if($hrs_starttimeh != ''){
                $hrs_starttime = Carbon::parse($hrs_starttimed)->format('Y-m-d') . " " . $hrs_starttimeh;    
            }else{
                $hrs_starttime = Carbon::parse($hrs_starttimed)->format('Y-m-d') . " 00:00:00";
            }            
        }

        if($hrs_endtimed != ''){
            if($hrs_endtimeh != ''){
                $hrs_endtime = Carbon::parse($hrs_endtimed)->format('Y-m-d') . " " . $hrs_endtimeh;    
            }else{
                $hrs_endtime = Carbon::parse($hrs_endtimed)->format('Y-m-d') . " 00:00:00";
            }            
        }

        if($hrs_jobid != ''){
            $data = array(
                'hrs_wkd_id' => $hrs_wkd_id,                
                'hrs_starttime' => $hrs_starttime,
                'hrs_endtime' => $hrs_endtime,                
                'hrs_jobid' => $hrs_jobid,
                'hrs_regular' => $hrs_regular,
                'hrs_ovt' => $hrs_ovt,
                'hrs_double' => $hrs_double,
            );
            $res = null;
            if($hrs_id > 0){
                $res = DB::table('hours')->where('hrs_id',$hrs_id)->update($data);
            }else{
                $data['hrs_truckdriver'] = 0;
                $data['hrs_gps_start_lat'] = 0;
                $data['hrs_gps_start_lon'] = 0;
                $data['hrs_gps_end_lat'] = 0;
                $data['hrs_gps_end_lon'] = 0;
                $data['hrs_realtime'] = date('Y-m-d H:i:s');
                $res = DB::table('hours')->insert($data);
            }
            if($res) return 1;
            else return 0;
        }else{
            return 0;
        }
    }
    
    private function showCheck($notapproved, $approved_rec, $approved, $shifttime, $truckdriver, $live=0, $found=false, $SCOMPANYCALC) {
        $textout = "";
        if ($SCOMPANYCALC==1) {
            if ($approved_rec > 0) {
                $textout =  "<font color='#FF0000'>";
            } else {

                if ($approved > 0) {
                    $textout =  "<font color='#000000'>";
                } else {
                    if ($notapproved > 0) {
                        $textout =  "<font color='#FFD700'>";
                    }

                }
            }
        } else {
            if (($approved+$approved_rec) > 0) {
                $textout =  "<font color='#000000'>";
            } else {
                if ($notapproved > 0) {
                    $textout = "<font color='#FFD700'>";
                }
            }
        }

        if ($notapproved+$approved+$approved_rec > 0) $textout .= "<font size=+2><b>&check;</b></font>";
        if ((($approved+$approved_rec>0) && ($notapproved>0)) || ($approved + $approved_rec > 1)) $textout .= "<font size=+2><b>&nbsp; !!</b></font>";
        if ($shifttime>1) $textout .= "<font size=+2><b>&nbsp;S</b></font>";
        if ($truckdriver>0) $textout .= "<font size=+2><b>&nbsp;T</b></font>";
        
        $textout .= "</font>";
        if ($live > 0) {
            $textout .= "<br><font color=\"#FF0000\">LiveExp</font>";
        }
        return $textout;
    }

    public function searchjob(Request $req){
        $user = Sentinel::getUser();
        $term = $req->q;        
        $result = array();
        $res = DB::table('jobs')
                ->select(DB::raw("jid as id, CONCAT(nr,' - ', description) as text"))
                ->where('nr','like', $term . '%')
                ->where('jb_sc_id',$user->sc_id)
                ->orderBy('nr')
                ->limit(10)
                ->get();
        $x = new \stdClass;
        $y = new \stdClass;

        if (!(strpos("DRIVE", strtoupper($term))===false)) {
            $x->id=$this->CODE_DRIVE;
            $x->text = "DRIVE";
            array_push($result,$x);
        }
        if (!(strpos("DRIVE", strtoupper($term))===false)) {
            $y->id=$this->CODE_DRIVE_CDL;
            $y->text = "DRIVE CDL";
            array_push($result,$y);
        }   
        if (!(strpos("SHOP", strtoupper($term))===false)) {
            $y->id=$this->CODE_SHOP;
            $y->text = "SHOP";
            array_push($result,$y);            
        }
        
        if (!(strpos("HOLIDAY", strtoupper($term))===false)) {
            $y->id=$this->CODE_HOLIDAYS;
            $y->text = "HOLIDAY";
            array_push($result,$y);            
        }
        
        if (!(strpos("VACATION", strtoupper($term))===false)) {
            $y->id=$this->CODE_VACATION;
            $y->text = "VACATION";
            array_push($result,$y);
            
        }
        foreach ($res as $key => $r) {            
            $r->id = floor($r->id);
            array_push($result,$r);
        }   
        //$item['items']=$result;
        return json_encode($result);
    }
}
