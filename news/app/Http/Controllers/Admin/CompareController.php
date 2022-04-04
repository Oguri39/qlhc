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

class CompareController extends DefinedController
{
       
    public function compareday(){
        return view('admin.compare.indexday');
    }


    public function comparejob(Request $req) {        
        return view('admin.compare.indexlistjob');
    }

    public function getdatajob(Request $req){
        $user = Sentinel::getUser();          
        if(isset($req->jid) && $req->jid > 0){
            $jid = $req->jid;
            $wkd = DB::table('workdays')
                        ->select('wkd_id')                        
                        ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')                        
                        ->where('hrs_jobid',$jid)                        
                        ->where('wkd_sc_id',$user->sc_id)
                        ->distinct('wkd_id')
                        ->get();
            $listwkdid = array();
            foreach ($wkd as $key => $value) {
                array_push($listwkdid, $value->wkd_id);
            }            
            $data = DB::table('workdays')
                        ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                        ->where('us_sc_id',$user->sc_id)
                        ->whereIn('wkd_id',$listwkdid)
                        ->orderBy('wkd_day','desc')
                        ->orderBy('lastname')
                        ->orderBy('firstname')
                        ->get();   
            foreach ($data as $key => $value) {
                $value->wkd_daytext = $this->toUSDate($value->wkd_day);        
            }         
        }else{
            $data = array();
        }
        return view('admin.compare.indexjob')->with(['data' => $data]);
    }

    public function getdataday(Request $req){
        $user = Sentinel::getUser();  
        $todate = date('Y-m-d');
        if(isset($req->todate)){
            $todate = $req->todate;
        }
        
        $data = DB::table('workdays')
                        ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                        // ->leftJoin('hours','hours.hrs_wkd_id','=','workdays.wkd_id')
                        ->where('us_sc_id',$user->sc_id)
                        ->whereDate('wkd_day',$todate)                        
                        ->orderBy('lastname')
                        ->orderBy('firstname')
                        ->get();
        
        return DataTables::of($data)
                ->addColumn(
                    'action',
                    function ($user) {
                        $typeOfEmployee=$this->typeOfEmployee($user->departmentNr);
                        $textout = '<table style="width:100%;"><tr><td width="10%"><input type="checkbox" id="check' . $user->wkd_id . '" class="form-control" onclick="clickcheck(' . $user->us_id . ',' . $user->wkd_id . ');"></td>';
                        $textout .= '<td width="90%"><label class="control-label" style="margin-top: 5px;">' . $user->firstname . ' ' . $user->lastname . '</label></td></tr><tr><td colspan="2">';
                       
                        $resb = DB::table('hours')
                                ->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
                                ->where('hrs_wkd_id',$user->wkd_id)
                                ->orderBy('hrs_starttime')->get();
    
                        $textout .= '<table border="2" style="background-color: #EEEEEE; padding: 10px" width="100%" cellspacing="5">';
                        $textout .= '<tr>';
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
                            $textout .= '<td>' . $rb->nr . '</td>';
                            $textout .= '<td>' . $rb->description .'<br>' . $rb->company . '</td>';
                            $textout .= '<td align="right">' . $this->showTime($rb->hrs_regular) . '</td>';
                            $textout .= '<td align="right">' . $this->showTime($rb->hrs_ovt) . '</td>';
                            $textout .= '<td align="right">' . $this->showTime($rb->hrs_double) . '</td>';
                            $textout .= '</tr>';
                        } 
                        $textout .= '<tr bgcolor="' . $color .'">';
                        $textout .= '<td>&nbsp;</td>';
                        $textout .= '<td align="right"><b>' . Lang::get('weekcalculate/title.total') .':</b></td>';
                        $textout .= '<td align="right"><b>' . $this->showTime($totRegular) . '</b></td>';
                        $textout .= '<td align="right"><b>' . $this->showTime($totOvt) . '</b></td>';
                        $textout .= '<td align="right"><b>' . $this->showTime($totDouble) . '</b></td>';
                        $textout .= '</tr>';
                        $textout .= '</table>';
                        $textout .= '</td></tr></table>';
                        return $textout;
                    }
                )                
                ->rawColumns(['action'])                             
                ->make(true);
    }

    private function getlistjobselected($str){
        $user = Sentinel::getUser();
        $SCOMPANYCALC = 0;
        $sccalt = DB::table('schultes')->where('sc_id',$user->sc_id)->first();
        if($sccalt){
            $SCOMPANYCALC = $sccalt->sc_calctype;
        }
        
        $res = DB::table('workdays')
                ->leftJoin('employees','employees.us_id','=','workdays.wkd_us_id')
                ->where('wkd_sc_id',$user->sc_id)
                ->whereIn('wkd_id',$str)
                ->orderBy('lastname')->orderBy('firstname')->orderBy('wkd_day')->get(); 

        $listus = array();
        $listus[0] = '---';
        foreach ($res as $key => $value) {
            $listus[$value->us_id . '_' . $value->wkd_id] = $value->firstname . ' ' . $value->lastname;
        }

        $textout = "";  
        $minutes = 60;      
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
            $textout .= '<select name="wkd_locked" readonly="">';
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

            $textout .= '<input type="radio" name="active" value="0" readonly="" ' . $status_pending .'/>&nbsp;' . Lang::get('weekcalculate/title.pending');
            $textout .= '</td>';
            $textout .= '<td align="center">';
            $textout .= '<input type="radio" name="active" value="1" readonly="" ' . $status_approved .'/>&nbsp;' . Lang::get('weekcalculate/title.approved');
            $textout .= '</td>';
            $textout .= '<td align="center">';
            $textout .= '<input type="radio" name="active" value="10" readonly="" ' . $status_refused .'/>&nbsp;' . Lang::get('weekcalculate/title.refused');
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
            
            //$textout .= '<button class="btn btn-success" onclick="openeditdate('.$r->wkd_id.');" style="width:150px">' . Lang::get('button.edit'). '</button>';
            $textout .= '<select class="form-control" id="selus'.$r->wkd_id.'" onchange="selectus(\'' . $r->us_id . '_' . $r->wkd_id. '\',' .$r->wkd_id.');" style="width: 80%;" >';
            foreach ($listus as $keyus => $valueus) {
                if($keyus != $r->us_id){
                    $textout .= '<option value="' . $keyus . '">' . $valueus . '</option>';
                }
            }
            $textout .= '</select>';
            $textout .= '<br><br>';
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</td>';
            $textout .= '</tr>';
            $textout .= '</table>';
            $textout .= '</div><br/><br/>';        
        }
        return $textout;
    }

    public function listcomparejob(Request $req){
        $listid = $req->listid;        
        $str = explode(',', $listid);
        $textout = $this->getlistjobselected($str);        
        return view('admin.compare.listjob')->with(['textout' => $textout, 'listid' => $listid]);
    }

    public function copyjobhour(Request $req){
        $listid = $req->listid;
        $strlistid = explode(',', $listid);
        $from = $req->from;
        $to = $req->to;        
        $listwkd = $req->listwkd;

        // copy hour
        for($i = 0;$i < sizeof($from);$i++){                        
            if($to[$i] != 0){
                $str = explode("_", $from[$i]);
                $usfrom = $str[0];
                $wkdidfrom = $str[1];

                $str = explode("_", $to[$i]);
                $usto = $str[0];
                $wkdidto = $str[1];
                
                $resa = DB::table('workdays')->where('wkd_id',$wkdidto)->first();
                $data = array(
                    'wkd_sc_id'=> $resa->wkd_sc_id ,
                    'wkd_us_id'=> $usfrom ,
                    'wkd_driller_helper'=> $resa->wkd_driller_helper ,
                    'wkd_truck_driver'=> $resa->wkd_truck_driver ,
                    'wkd_liveexp'=> $resa->wkd_liveexp ,
                    'wkd_lunch'=> $resa->wkd_lunch ,
                    'wkd_lunchtime'=> $resa->wkd_lunchtime ,
                    'wkd_miles'=> $resa->wkd_miles ,
                    'wkd_day'=> $resa->wkd_day ,
                    'wkd_shift_work'=> $resa->wkd_shift_work ,
                    'wkd_end_realtime'=> $resa->wkd_end_realtime ,
                    'wkd_gps_latitude'=> $resa->wkd_gps_latitude ,
                    'wkd_gps_longitude'=> $resa->wkd_gps_longitude ,
                    'wkd_timestamp'=> $resa->wkd_timestamp ,
                    'wkd_status'=> $resa->wkd_status ,
                    'wkd_notes'=> $resa->wkd_notes ,
                    'wkd_recalctime'=> $resa->wkd_recalctime ,
                    'wkd_locked' => $resa->wkd_locked
                );                

                $resb = DB::table('workdays')->insertGetId($data);

                //clone the hours
                $resc = DB::table('hours')->where('hrs_wkd_id',$wkdidto)->get();
                foreach ($resc as $key => $value) {
                    $data = array(                         
                        'hrs_jobid' => $value->hrs_jobid, 
                        'hrs_starttime' => $value->hrs_starttime, 
                        'hrs_endtime' => $value->hrs_endtime, 
                        'hrs_realtime' => $value->hrs_realtime, 
                        'hrs_truckdriver' => $value->hrs_truckdriver, 
                        'hrs_gps_start_lat' => $value->hrs_gps_start_lat, 
                        'hrs_gps_start_lon' => $value->hrs_gps_start_lon, 
                        'hrs_gps_end_lat' => $value->hrs_gps_end_lat, 
                        'hrs_gps_end_lon' => $value->hrs_gps_end_lon, 
                        'hrs_regular' => $value->hrs_regular, 
                        'hrs_ovt' => $value->hrs_ovt, 
                        'hrs_double' => $value->hrs_double, 
                        'hrs_status' => $value->hrs_status, 
                        'hrs_wkd_id' => $resb, 
                    );
                    $resd = DB::table('hours')->insert($data);
                }
                // delete hours of from
                $resc = DB::table('hours')->where('hrs_wkd_id',$wkdidfrom)->delete();
                // delete the workday
                $resd = DB::table('workdays')->where('wkd_id',$wkdidfrom)->delete();

                for ($j=0; $j < sizeof($strlistid); $j++) { 
                    if($strlistid[$j] == $wkdidfrom) $strlistid[$j] = $resb;
                }
            }
        }      
        $out[0] = $this->getlistjobselected($strlistid);
        $out[1] = '';
        for ($j=0; $j < sizeof($strlistid); $j++) { 
            $out[1] .= ',' . $strlistid[$j];
        }  
        $out[1] = substr($out[1], 1);
        return $out;
    }
}
