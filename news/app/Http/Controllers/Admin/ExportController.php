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

class ExportController extends DefinedController
{
       
    public function index(){
        $user = Sentinel::getUser();
        $employees = DB::table('employees')
                        ->select('us_id', 'firstname', 'lastname')
                        ->where('us_sc_id',$user->sc_id)
                        ->where('us_active',1)
                        ->orderBy('lastname')
                        ->orderBy('firstname')->get();

        return view('admin.export.index')->with(['employees' => $employees]);
    }


    public function export(Request $req) {   
        $user = Sentinel::getUser();     
        $fromdate = trim($req->fromdate);
        $todate = trim($req->todate);
        $hrs_jobid = $req->filterJob;
        $listeq = $req->listeq;
     
        $out = $this->getdata($fromdate,$todate,$hrs_jobid,$listeq);

        $listeqtext = '';
        if(sizeof($listeq) > 0){
            $listeqtext = $listeq[0];
            for ($i=1; $i < sizeof($listeq); $i++) { 
                $listeqtext .=  ',' . $listeq[$i];
            }
        }
        return view('admin.export.export')->with(['data' => $out, 'fromdate' => $fromdate, 'todate' => $todate, 'hrs_jobid' => $hrs_jobid, 'listeqtext' => $listeqtext]);
    }

    public function exportcsv(Request $req) {           
        $fromdate = trim($req->fromdate);
        $todate = trim($req->todate);
        $hrs_jobid = $req->hrs_jobid;
        $listeq = $req->listeq;
        if($listeq != ''){
            $listeq = explode(',', $listeq);
        }else{
            $listeq = array();
        }
     
        $out = $this->getdata($fromdate,$todate,$hrs_jobid,$listeq);
        
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );      

        $columns = array(   Lang::get('export/title.employeeno'),
                            Lang::get('export/title.name'),
                            Lang::get('export/title.jobno'),
                            Lang::get('export/title.costcode'),
                            Lang::get('export/title.date'),
                            Lang::get('export/title.hours'),
                            Lang::get('export/title.amount'),
                            Lang::get('export/title.payrate'),
                            Lang::get('export/title.earncode'),
                            Lang::get('export/title.shiftno'),
                            Lang::get('export/title.deptno'),
                            Lang::get('export/title.tradeno'),
                            Lang::get('export/title.union'),
                            Lang::get('export/title.taxtable'),
                            Lang::get('export/title.unused')   );

        $callback = function() use ($out, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($out as $key => $r) {
                fputcsv($file, $r);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    private function getdata($fromdate,$todate,$hrs_jobid,$listeq){
        $user = Sentinel::getUser();     
        
        $res = DB::table('workdays') 
                    ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                    ->where('wkd_status',1)
                    ->where('wkd_sc_id',$user->sc_id);
        if($fromdate != ''){
            $fromdate = Carbon::parse($fromdate)->format('Y-m-d');
            $res = $res->whereDate('wkd_day','>=',$fromdate);
        }
        if($todate != ''){
            $todate = Carbon::parse($todate)->format('Y-m-d');
            $res = $res->whereDate('wkd_day','<=',$todate);
        }
        if(sizeof($listeq) > 0){
            $res = $res->whereIn('us_id',$listeq);   
        }
        $res = $res->orderBy('wkd_day')->orderBy('us_id')->get();

        $out = new Collection;

        foreach ($res as $key => $r) {
            $employeeNr = $r->wkd_us_id;
            $shiftNr = $r->wkd_shift_work ;
            $employeeDept = $r->departmentNr;
            $typeOfEmployee=$this->typeOfEmployee($employeeDept);
            if (!(abs($hrs_jobid)>0)) {
                if ($r->wkd_miles > 0) {
                    // 0.22 * miles
                    $out->push([
                        'employeeNr' => $r->userId,
                        'employeeName' => $r->firstname." ".$r->lastname,
                        'jobNr' => $typeOfEmployee."30200",
                        'costCode' => '',
                        'date' => $this->toUSDate($r->wkd_day),
                        'hours' => '',
                        'amount' => ($r->wkd_miles * $r->milesrate),
                        'payrate' => '',
                        'earnCode' => 'EXP',
                        'shiftNr' => $shiftNr,
                        'deptNo' => $r->departmentNr,
                        'tradeNr' => $r->tradeNr,
                        'union' => '',
                        'taxtable' => '',
                        'unused' => '',
                    ]);
                }
                if ($r->wkd_liveexp > 0) {
                    // 0.22 * miles
                    $out->push([
                        'employeeNr' => $r->userId,
                        'employeeName' => $r->firstname." ".$r->lastname,
                        'jobNr' => $typeOfEmployee."30200",
                        'costCode' => '',
                        'date' => $this->toUSDate($r->wkd_day),
                        'hours' => '',
                        'amount' => $r->liveawayexp,
                        'payrate' => '',
                        'earnCode' => 'EXP',
                        'shiftNr' => $shiftNr,
                        'deptNo' => $r->departmentNr,
                        'tradeNr' => $r->tradeNr,
                        'union' => '',
                        'taxtable' => '',
                        'unused' => '',
                    ]);
                }
            }

            $resb = DB::table('hours')
                                ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                                ->where('hrs_wkd_id',$r->wkd_id);
            if (abs($hrs_jobid) > 0) {
                $resb = $resb->where('hrs_jobid',$hrs_jobid);
            }               
            $resb = $resb->orderBy('hrs_jobid')->get();
            foreach ($resb as $key => $rb) {                
                $jobCode = $rb->hrs_jobid;
                $payrate="";
                $regStr = "REG";
                if ($rb->hrs_jobid==$this->CODE_DRIVE)  {
                    $payrate = $r->drivetimerate;
                    $rb->description="DRIVE";
                    $rb->nr = $typeOfEmployee."20300";
                }
                if ($rb->hrs_jobid==$this->CODE_DRIVE_CDL)  {
                    $payrate = $r->drivetimeratecdl;
                    $rb->description="DRIVE CDL";
                    $rb->nr = $typeOfEmployee."20300";
                }
                if ($rb->hrs_jobid==$this->CODE_SHOP) {
                    $rb->description="SHOP";
                    $rb->nr = $typeOfEmployee."20100";
                }

                if ($rb->hrs_jobid==$this->CODE_HOLIDAYS) {
                    $rb->nr = $typeOfEmployee."20700";
                    $rb->description = "HOLIDAY";
                    $regStr = "HOL";
                }
                if ($rb->hrs_jobid==$this->CODE_VACATION) {
                    $rb->nr = $typeOfEmployee."20400";
                    $rb->description = "VACATION";
                    $regStr="VAC";
                }
                if ($rb->hrs_regular > 0) {
                    $out->push([
                        'employeeNr' => $r->userId,
                        'employeeName' => $r->firstname." ".$r->lastname,
                        'jobNr' => $rb->nr,
                        'costCode' => '',
                        'date' => $this->toUSDate($r->wkd_day),
                        'hours' => $this->showTime($rb->hrs_regular)." (".$this->showDecimalTime($rb->hrs_regular).")",
                        'amount' => '',
                        'payrate' => $payrate,
                        'earnCode' => $regStr,
                        'shiftNr' => $shiftNr,
                        'deptNo' => $r->departmentNr,
                        'tradeNr' => $r->tradeNr,
                        'union' => '',
                        'taxtable' => '',
                        'unused' => '',
                    ]);
                }
                if ($rb->hrs_ovt > 0) {
                    $out->push([
                        'employeeNr' => $r->userId,
                        'employeeName' => $r->firstname." ".$r->lastname,
                        'jobNr' => $rb->nr,
                        'costCode' => '',
                        'date' => $this->toUSDate($r->wkd_day),
                        'hours' => $this->showTime($rb->hrs_ovt)." (".$this->showDecimalTime($rb->hrs_ovt).")",
                        'amount' => '',
                        'payrate' => $payrate,
                        'earnCode' => 'OVT',
                        'shiftNr' => $shiftNr,
                        'deptNo' => $r->departmentNr,
                        'tradeNr' => $r->tradeNr,
                        'union' => '',
                        'taxtable' => '',
                        'unused' => '',
                    ]);                    
                }
                if ($rb->hrs_double > 0) {
                    $out->push([
                        'employeeNr' => $r->userId,
                        'employeeName' => $r->firstname." ".$r->lastname,
                        'jobNr' => $rb->nr,
                        'costCode' => '',
                        'date' => $this->toUSDate($r->wkd_day),
                        'hours' => $this->showTime($rb->hrs_double)." (".$this->showDecimalTime($rb->hrs_double).")",
                        'amount' => '',
                        'payrate' => $payrate,
                        'earnCode' => 'DBL',
                        'shiftNr' => $shiftNr,
                        'deptNo' => $r->departmentNr,
                        'tradeNr' => $r->tradeNr,
                        'union' => '',
                        'taxtable' => '',
                        'unused' => '',
                    ]);                     
                }
            }
        }
        return $out;
    }

    public function exportnote(){
        $user = Sentinel::getUser();
        $listemployees = array();
        $listemployees[0] = '---';
        $employees = DB::table('employees')->where('us_sc_id',$user->sc_id)->orderBy('lastname')->orderBy('firstname')->get();
        foreach ($employees as $key => $value) {
            $listemployees[$value->us_id] = $value->firstname . ' ' . $value->lastname;
        }

        return view('admin.export.exportnote')->with(['listemployees' => $listemployees]);
    }

    public function getlistjob(Request $req){
        $us_id = $req->us_id;
        $user = Sentinel::getUser();
        $listemp = '<option value="0">---</option>';
        
        $jids = DB::table('hours')->select('hrs_jobid')
                    ->leftJoin('workdays','workdays.wkd_id','=','hours.hrs_wkd_id')
                    ->where('wkd_us_id',$us_id)->distinct('hrs_jobid')->get();
        $listjid = array();
        foreach ($jids as $key => $value) {
            array_push($listjid,$value->hrs_jobid);
        }

        $jobs = DB::table('jobs')->where('jb_sc_id',$user->sc_id)->whereIn('jid',$listjid)->get();
        $data = array();
        foreach ($jobs as $key => $value) {
            array_push($data, array($value->jid, $value->nr));
        }
        return $data;
    }

    public function getdatanote(Request $req){
        $jid = 0;
        if(isset($req->jid) && $req->jid > 0){
            $jid = $req->jid;
        }

        $us_id = 0;
        if(isset($req->us_id) && $req->us_id > 0){
            $us_id = $req->us_id;
        }

        $opt = 0;
        if(isset($req->opt) && $req->opt > 0){
            $opt = 1;
        }        

        $user = Sentinel::getUser();                
        $data = DB::table('hours')
                    ->leftJoin('workdays','workdays.wkd_id','=','hours.hrs_wkd_id')
                    ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                    ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                    ->where('wkd_us_id',$us_id)
                    ->where('hrs_jobid',$jid)
                    ->where('jb_sc_id',$user->sc_id)
                    ->where('us_sc_id',$user->sc_id)
                    ->groupBy('wkd_id')
                    ->orderBy('wkd_end_realtime')
                    ->orderBy('hrs_endtime')
                    ->get();
        
        if($opt == 1){
            $out = array();

            foreach ($data as $key => $value) {
                array_push($out, array(
                    'employeeno' => $value->userId,
                    'name' => $value->firstname . ' ' . $value->lastname,
                    'jobno' => $value->nr,
                    'notes' => $value->wkd_notes,
                    'wkd_end_time' => $this->toUSDateTime($value->wkd_end_realtime),
                    'hrs_end_time' => $this->toUSDateTime($value->hrs_endtime),
                ));
            }
        
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );      

            $columns = array(   Lang::get('export/title.employeeno'),
                                Lang::get('export/title.name'),
                                Lang::get('export/title.jobno'),
                                Lang::get('export/title.notes'),
                                Lang::get('export/title.wkd_end_time'),
                                Lang::get('export/title.hrs_end_time'),
            );

            $callback = function() use ($out, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($out as $key => $r) {
                    fputcsv($file, $r);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
            
        }else{
            return DataTables::of($data)
                ->addColumn(
                    'employeeno',
                    function ($user) {
                        return $user->userId;
                    }
                )
                ->addColumn(
                    'name',
                    function ($user) {
                        return $user->firstname . ' ' . $user->lastname;
                    }
                )
                ->addColumn(
                    'jobno',
                    function ($user) {
                        return $user->nr;
                    }
                )
                ->addColumn(
                    'notes',
                    function ($user) {
                        return $user->wkd_notes;
                    }
                )
                ->addColumn(
                    'wkd_end_time',
                    function ($user) {
                        return $this->toUSDateTime($user->wkd_end_realtime);
                    }
                )
                ->addColumn(
                    'hrs_end_time',
                    function ($user) {
                        return $this->toUSDateTime($user->hrs_endtime);
                    }
                )                                
                ->make(true);
        }

    }
}
