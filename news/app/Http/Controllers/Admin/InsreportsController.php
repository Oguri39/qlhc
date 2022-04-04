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

class InsreportsController extends DefinedController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){         
        return view('admin.insreports.index');
    }

    public function getData(Request $req){        
        $user = Sentinel::getUser();
        $show = 0;
        if(isset($req->show) && $req->show > 0){
            $show = 1;
        }
        $res = DB::table('equipcheck')
                    ->select(DB::raw("ec_id, ec_date, eq_id,eq_internalcode,et_title, eq_name, email, ec_ins_mai_rep, ROUND ((LENGTH(ec_fields) - LENGTH( REPLACE (ec_fields, ':1', '')))/LENGTH(':1')) AS nrKo, ec_extra, count(wo_id) as totWO, sum(IF(wo_status=0,1,0)) as totWoOpen, (count(wo_id)-sum(IF(wo_status=0,1,0))) as totDif, ec_status"))
                    ->leftJoin('users','users.id','=','equipcheck.ec_us_id')
                    ->leftJoin('workorders','equipcheck.ec_id','=','workorders.wo_ec_id')
                    ->leftJoin('equipments','equipments.eq_id','=','equipcheck.ec_eq_id')
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id') 
                    ->where('eq_sc_id',$user->sc_id)
                    ->groupBy('ec_id')
                    ->orderBy('ec_status')
                    ->orderBy('nrKo','desc')
                    ->orderBy('ec_date','desc')->get();
        
        $out = new Collection;

        foreach ($res as $key => $r) {
            $typeofdoc = "Inspection";
            if ($r->ec_ins_mai_rep==1) $typeofdoc = "Maintenance";
            if ($r->ec_ins_mai_rep==2) $typeofdoc = "Repair";
            $expl = explode("|", $r->ec_extra);
            $nrNotes = 0;
            for ($i=0; $i<count($expl); $i++) {
                $expl2=explode("~", $expl[$i]);
                if(sizeof($expl2) > 1 && strlen(trim($expl2[1]))>0) $nrNotes++;
            }
            
            $ec_datetext = '<a href="' . route('admin.equip_users.editcheck',[1,$r->eq_id,$r->ec_id]) .'">'.$r->ec_date.'</a>';
            $workorders = '<a href="' . route('admin.insreports.editworkorder',[1,$r->eq_id,$r->ec_id]) . '">'.$r->totWoOpen." / ".$r->totWO.'</a>';
                        
            if (($r->nrKo+$nrNotes) > 0 || $show > 0) {
                $out->push([
                    'id' => $r->ec_id,
                    'date' => $ec_datetext,
                    'code'  => $r->eq_internalcode,
                    'eqtype'    => $r->et_title,
                    'equipment' => $r->eq_name,
                    'user'      => $r->email,
                    'type'      => $typeofdoc,
                    'problems'  => ($r->nrKo+$nrNotes),
                    'workorders'=> $workorders,
                    'status'    => $this->wstatus[$r->ec_status]
                ]);                
            }  
        }

        return DataTables::of($out)                
            ->rawColumns(['date','workorders'])         
            ->make(true);        
    }

    public function editworkorder(Request $req){
        $user = Sentinel::getUser();
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;
        $backmode = $req->backmode;

        if ($eq_id=="") $eq_id=0;
        if ($ec_id=="") $ec_id=0;
        $eq_date_start = date("m/d/Y");

        $routeback = route('admin.equipments.edit',$eq_id);
        if($backmode == 1){
            $routeback = route('admin.insreports');
        }else if($backmode == 2){
            $routeback = "";
        }

        $eq_internalcode='';        
        $eq_name = '';
        $equip = array();

        if ($eq_id > 0) {
            $r = DB::table('equipments')
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                    ->where('eq_sc_id',$user->sc_id)
                    ->where('eq_id',$eq_id)->first();

            $eq_internalcode=$r->eq_internalcode;            
            $eq_name = $r->eq_name;            
            $equip = $r;
        }
        
        $reporttitle = Lang::get('insreports/title.workordersfor') . " " . $eq_internalcode . " - " . $eq_name;
        $equip = $this->showEquipment($equip);

        return view('admin.insreports.edit')->with(['reporttitle' => $reporttitle, 'equip' => $equip, 'eq_id' => $eq_id, 'ec_id' => $ec_id, 'routeback' => $routeback, 'liststatus' => $this->wstatus]); 
    }

    public function getdatapart(Request $req){        
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;

        $r = DB::table('equipments')
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                    ->where('eq_id',$eq_id)->first();
        $checkType = $r->et_checklist;
        $eq_internalcode = $r->eq_internalcode;
        $eq_name = $r->eq_name;

        $r = DB::table('equipcheck')
                    ->leftJoin('users','equipcheck.ec_us_id','=','users.id')
                    ->where('ec_id',$ec_id)->first();
        $ec_date=$r->ec_date;
        $ec_us_id = $r->ec_us_id;
        $ec_ins_mai_rep = $r->ec_ins_mai_rep;
        $ec_notes = $r->ec_notes;
        $ec_fields = $r->ec_fields;
        $ec_extra = $r->ec_extra;
        $inspector = $r->email;
        $ec_typeck1 = "";
        $ec_typeck2 = "";
        $ec_typeck3 = "";
        $ec_typeck4 = "";

        if ($ec_ins_mai_rep==0) $ec_typeck1="checked";
        if ($ec_ins_mai_rep==1) $ec_typeck2="checked";
        if ($ec_ins_mai_rep==2) $ec_typeck3="checked";
        if ($ec_ins_mai_rep==3) $ec_typeck4="checked";
        if (strlen(trim($ec_typeck1.$ec_typeck2.$ec_typeck3.$ec_typeck4))==0) $ec_typeck1="checked";

        $allfields = $this->getInspectionReport($checkType);
        $checkstatus = $this->splitReportItemsStatus($ec_fields);
        $items = $this->getListOfItemsToCheck($allfields, $checkstatus);
        $notes = $this->splitReportNotes($ec_extra, $allfields);

        $xx=0;
        $out = new Collection;
        for ($i=0; $i<count($items); $i++) {
                // $qry = "select * from workorders left join employees on us_id=wo_us_id  where wo_ec_id = $ec_id and wo_ec_field = ".$items[$i]['id'];
            $rw = DB::table('workorders')
                        ->leftJoin('employees','employees.us_id','=','workorders.wo_us_id')
                        ->where('wo_ec_id',$ec_id)
                        ->where('wo_ec_field',$items[$i]['id'])->first();                        
            if ($rw != null) {
                $checkb = '<input type="checkbox" name="ck_'.$xx.'" value="W'.$rw->wo_id.'" class="insreport form-control">';
                $equipclass = $items[$i]['class'];
                $equip = $items[$i]['item'];
                $worknr = '<a href="' . route('admin.workorders.edit',[1,$eq_id,$ec_id,$rw->wo_id]) . '">'.$rw->wo_id.'</a>';
                $desc = $rw->wo_description;
                $start = $this->toUSDate($rw->wo_startdate);
                $end = $this->toUSDate($rw->wo_enddate);
                $asign = $rw->firstname." ".$rw->lastname;
                $stat = $this->wstatus[$rw->wo_status];
            } else {
                $checkb = '<input type="checkbox" name="ck_'.$xx.'" value="'.$i.'" class="insreport form-control">';
                $equipclass = $items[$i]['class'];
                $equip = $items[$i]['item'];
                $worknr = '<a href="' . route('admin.workorders.edit',[1,$eq_id,$ec_id,0]) . '?wec_field='.$items[$i]['id'] . '" >'.Lang::get('insreports/title.createworkorder').'</a>';
                $desc = '';
                $start = '';
                $end = '';
                $asign = '';
                $stat = $this->wstatus[0];
            }
            $out->push([
                'checkb' => $checkb,
                'equipclass' => $equipclass,
                'equip' => $equip,
                'worknr' => $worknr,
                'desc' => $desc,
                'start' => $start,
                'end' => $end,
                'asign' => $asign,
                'stat' => $stat,
            ]);
            $xx++;
        }

        for ($i=0; $i<count($notes); $i++) {
            if (strlen(trim($notes[$i]['note']))>2) {
                $rw = DB::table('workorders')
                        ->leftJoin('employees','employees.us_id','=','workorders.wo_us_id')
                        ->where('wo_ec_id',$ec_id)
                        ->where('wo_ec_extra',$notes[$i]['id'])->first(); 
                
                if ($rw != null) {
                    if ($rw->wo_status=="") $rw->wo_status=0;
                    $checkb = '<input type="checkbox" name="ck_'.$xx.'" value="W'.$rw->wo_id.'" class="insreport form-control">';
                    $equipclass = $notes[$i]['class'];
                    $equip = $notes[$i]['note'];

                    $worknr = '<a href="' . route('admin.workorders.edit',[1,$eq_id,$ec_id,$rw->wo_id]) . '">'.$rw->wo_id.'</a>';
                    $desc = $rw->wo_description;
                    $start = $this->toUSDate($rw->wo_startdate);
                    $end = $this->toUSDate($rw->wo_enddate);
                    $asign = $rw->firstname." ".$rw->lastname;
                    $stat = $this->wstatus[$rw->wo_status];
                } else {                   
                    $checkb = '<input type="checkbox" name="ck_'.$xx.'" value="'.($i + 1000).'" class="insreport form-control">';
                    $equipclass = $notes[$i]['class'];
                    $equip = $notes[$i]['note'];
                    $worknr = '<a href="' . route('admin.workorders.edit',[1,$eq_id,$ec_id,0]) . '?wec_extra='.$notes[$i]['id'] . '" >'.Lang::get('insreports/title.createworkorder').'</a>';
                    $desc = '';
                    $start = '';
                    $end = '';
                    $asign = '';
                    $stat = $this->wstatus[0];
                }
                $out->push([
                    'checkb' => $checkb,
                    'equipclass' => $equipclass,
                    'equip' => $equip,
                    'worknr' => $worknr,
                    'desc' => $desc,
                    'start' => $start,
                    'end' => $end,
                    'asign' => $asign,
                    'stat' => $stat,
                ]);
                $xx++;
            }

        }

        return DataTables::of($out)                
            ->rawColumns(['checkb','worknr'])         
            ->make(true);
    }

    public function closeopen(Request $req){
        $openclose = $req->closeopen;
        $valor = $req->valor;
        $listname = $req->listname;
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;

        $r = DB::table('equipments')
                ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                ->where('eq_id',$eq_id)->first();

        $checkType = $r->et_checklist;
        
        $r = DB::table('equipcheck')
                    ->leftJoin('users','users.sc_id','=','equipcheck.ec_us_id')
                    ->where('ec_id',$ec_id)->first();

        $ec_notes = $r->ec_notes;
        $ec_fields = $r->ec_fields;
        $ec_extra = $r->ec_extra;
        
        $allfields = $this->getInspectionReport($checkType);
        $checkstatus = $this->splitReportItemsStatus($ec_fields);
        $items = $this->getListOfItemsToCheck($allfields, $checkstatus);
        $notes = $this->splitReportNotes($ec_extra, $allfields);

        for ($i=0; $i < sizeof($valor); $i++) { 
            $val = $valor[$i];                
            $firstletter=substr($val,0,1);
            if ($firstletter=="W") {                
                $val2 = substr($val,1);
                $dt="0000-00-00";
                if ($openclose == 1) $dt=date('Y-m-d');                
                $res = DB::table('workorders')->where('wo_id',$val2)->update([
                                'wo_status' => $openclose, 'wo_enddate' => $dt]);
            } else {
                if ($val>999) {
                    $val=$val-1000;
                    $wo_ec_field="";
                    $wo_ec_extra=$notes[$val]['id'];
                    $wo_item=$notes[$val]['note'];
                } else {
                    $wo_ec_field=$items[$val]['id'];
                    $wo_ec_extra="";
                    $wo_item=$items[$val]['item'];                        
                }
                $wo_startdate = date("Y-m-d");
                $wo_enddate = date("Y-m-d");
                
                $wo_description="";
                $wo_us_id=0;
                $wo_hours=0;
                $wo_status=$openclose;
                
                $data = array(
                    'wo_eq_id' => $eq_id,
                    'wo_ec_id' => $ec_id,
                    'wo_priority' => 0,
                    'wo_ec_field' => $wo_ec_field,
                    'wo_ec_extra' => $wo_ec_extra,
                    'wo_startdate'=> $wo_startdate,
                    'wo_enddate'=> $wo_enddate,
                    'wo_item'   => $wo_item,
                    'wo_description' => $wo_description,
                    'wo_us_id'  => $wo_us_id,
                    'wo_hours'  => $wo_hours,
                    'wo_status' => $wo_status,
                );

                $res = DB::table('workorders')->insert($data);                
            }
        }
        return 1;
    }
}