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

class TimecheckController extends DefinedController
{

    public function index(){
        $user = Sentinel::getUser();
        $listemployees = array();
        $listemployees[0] = Lang::get('timecheck/title.all'); 
        $res = DB::table('employees')
                    ->where('us_sc_id',$user->sc_id)
                    ->where('us_active',1)
                    ->orderBy('lastname')
                    ->orderBy('firstname')->get();
        foreach ($res as $key => $r2) {
            $listemployees[$r2->us_id] = $r2->userId." - ".$r2->lastname." ".$r2->firstname;            
        }
        return view('admin.timecheck.index')->with(['listemployees' => $listemployees]);
    }
    
    public function getdata(Request $req){        
        $user = Sentinel::getUser();

        $fromdate = $req->fromdate;        
        if(isset($fromdate) && $fromdate != '') $fromdate = Carbon::parse($fromdate)->format('Y-m-d');
        $todate = $req->todate;
        if(isset($todate) && $todate != '') $todate = Carbon::parse($todate)->format('Y-m-d');
        $minutes = $req->minutes;
        if($minutes == "") $minutes = 60;
        $us_id = $req->us_id;
        
        $res = DB::table('workdays')
                ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                ->where('wkd_sc_id',$user->sc_id);
        if($us_id > 0){
            $res = $res->where('us_id',$us_id);
        }

        if(isset($fromdate) && $fromdate != ''){
            $res = $res->whereDate('wkd_day','>=',$fromdate);
        }

        if(isset($todate) && $todate != ''){
            $res = $res->whereDate('wkd_day','<=',$todate);
        }
        $res = $res->orderBy('lastname')
                ->orderBy('firstname')
                ->orderBy('wkd_day');

        $res = $res->get();
        $data = new Collection;
        foreach ($res as $key => $r) {
            $employeeDept = $r->departmentNr;
            $typeOfEmployee=$this->typeOfEmployee($employeeDept);

            $str = '<b>' . Lang::get('timecheck/title.employee'). ':</b> '.$r->firstname." ".$r->lastname."<br>";
            $str.= '<b>' . Lang::get('timecheck/title.workingday'). ':</b> ' . $this->toUSDate($r->wkd_day);
            $endRealTime = '<b>' . Lang::get('timecheck/title.endrealtime'). ':</b> '. $this->toUSDateTime($r->wkd_end_realtime);            
            $resb = DB::table('hours')
                        ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                        ->where('hrs_wkd_id',$r->wkd_id)
                        ->orderBy('hrs_starttime');            
            $rw = 1;
            $nrRows = $resb->count();
            $strdet = '<table border=1 style="background-color: #EEEEEE; padding: 10px" width="100%" cellspacing="5">
                    <tr>                   
                        <th>' . Lang::get('timecheck/title.starttime') . '</th>
                        <th>' . Lang::get('timecheck/title.endtime') . '</th>
                        <th>' . Lang::get('timecheck/title.inserttime') . '</th>
                        <th>' . Lang::get('timecheck/title.jobnr') . '</th>
                        <th>' . Lang::get('timecheck/title.jobname') . '</th>
                        <th>' . Lang::get('timecheck/title.regular') . '</th>
                        <th>' . Lang::get('timecheck/title.ovt') . '</th>
                        <th>' . Lang::get('timecheck/title.double') . '</th>
                    </tr>';
            $totRegular = 0;
            $totOvt = 0;
            $totDouble = 0;
            $error = false;
            $resb = $resb->get();
            foreach ($resb as $keyb => $rb) {
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
                $to_time = strtotime($rb->hrs_starttime);
                $from_time = strtotime($rb->hrs_realtime);
                $mindiff = round(abs($to_time - $from_time) / 60,2);
                $colorbgcell = "#FFFFFF";
                if ($mindiff >= $minutes) {
                    $colorbgcell = "#FF5555";
                    $error = true;
                }

                if ($nrRows==$rw) {                    
                    $to_time = strtotime($rb->hrs_endtime);
                    $from_time = strtotime($r->wkd_end_realtime);
                    $mindiff = round(abs($to_time - $from_time) / 60,2);

                    if ($mindiff >= $minutes) {
                        $colorbgcell = "#FF5555";
                        $endRealTime = '<b>' . Lang::get('timecheck/title.endrealtime'). ':</b> <font color="'. $colorbgcell . '">'. $this->toUSDateTime($r->wkd_end_realtime)."</font>";
                        $error = true;
                    };
                }
                $strdet.='
                            <tr bgcolor="' . $colorbgcell . '">
                                <td>'.$this->toUSDateTime($rb->hrs_starttime).'</td>
                                <td>'.$this->toUSDateTime($rb->hrs_endtime).'</td>
                                <td>'.$this->toUSDateTime($rb->hrs_realtime).'</td>
                                <td>'.$rb->nr.'</td>
                                <td>'.$rb->description."<br>". $rb->company.'</td>
                                <td align="right">'.$this->showTime($rb->hrs_regular).'</td>
                                <td align="right">'.$this->showTime($rb->hrs_ovt).'</td>
                                <td align="right">'.$this->showTime($rb->hrs_double).'</td>
                            </tr>';
                $rw++;
            }
            $strdet.= "<tr>            
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <td align=right><b>" . Lang::get('timecheck/title.total') . ":</b></td>
                                <td align=right><b>".$this->showTime($totRegular)."</b></td>
                                <td align=right><b>".$this->showTime($totOvt)."</b></td>
                                <td align=right><b>".$this->showTime($totDouble)."</b></td>
                            </tr>
                        </table>"; 
            if ($error) {
                $output = $str."<br>" . $endRealTime ."<br>";
                $output .= $strdet."<br><hr>";                
                $data->push([
                    'content'     => $output,                  
                ]);                
            }               
        }
        return DataTables::of($data)->rawColumns(['content'])->make(true);
    }        
}
