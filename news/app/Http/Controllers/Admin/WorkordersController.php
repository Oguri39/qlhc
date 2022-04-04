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

class WorkordersController extends DefinedController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){         
        return view('admin.workorders.index')->with(['listoc' => $this->wstatus]);
    }

    public function getData(){        
        $user = Sentinel::getUser();
        $res = DB::table('workorders')
                    ->leftJoin('equipments','equipments.eq_id','=','workorders.wo_eq_id')
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                    ->leftJoin('employees','employees.us_id','=','workorders.wo_us_id')
                    ->where('eq_sc_id',$user->sc_id)
                    ->orderBy('wo_status')
                    ->orderBy('wo_id','desc')->get();
        
        return DataTables::of($res)    
            ->addColumn(
                'startdatetext',
                function ($r) {
                    return '<a href="' . route('admin.workorders.edit',[1,$r->wo_eq_id,$r->wo_ec_id,$r->wo_id]) . '" >'.$this->toUSDate($r->wo_startdate).'</a>';
                }
            ) 
            ->addColumn(
                'enddatetext',
                function ($r) {                    
                    return $this->toUSDate($r->wo_enddate);
                }
            ) 
            ->addColumn(
                'assignedto',
                function ($r) {                    
                    return $r->lastname." ".$r->firstname;
                }
            )  
            ->addColumn(
                'statustext',
                function ($r) {                    
                    return $this->wstatus[$r->wo_status];
                }
            )           
            ->rawColumns(['startdatetext'])         
            ->make(true);        
    }
     
    public function updateopenclose(Request $req){
        $openclose = $req->openclose;
        if (strlen($openclose)>0) {
            $exp = explode(",",$toopenclose);
            // $openclose=$_POST['openclose'];
            // for ($i=0;$i<count($exp);$i++) {
            //     $qry="update workorders set wo_status=$openclose where wo_id=".$exp[$i];
            //     $res =$dba->query($qry);
            // }
            
        }
    }

    public function edit(Request $req){     
        $user = Sentinel::getUser();   
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;
        $wo_id = $req->wo_id;
        $backmode = $req->backmode;

        if($eq_id == "") $eq_id = 0;
        if($ec_id == "") $ec_id = 0;
        if($wo_id == "") $wo_id = 0;

        $routeback = route('admin.equipments.edit',$eq_id);
        if($backmode == 1){
            $routeback = route('admin.workorders');
        }else if($backmode == 2){
            $routeback = "";
        } 
                
        $checkType = '';
        $eq_internalcode = '';
        $eq_name = '';
        $equip = null;
        $eq_date_start = date("m/d/Y");
        $ec_date='';
        $ec_us_id = '';
        $ec_ins_mai_rep = '';
        $ec_notes = '';
        $ec_fields = '';
        $ec_extra = '';
        $inspector = '';
        $ec_typeck1 = "";
        $ec_typeck2 = "";
        $ec_typeck3 = "";
        $ec_typeck4 = "";
        $allfields = array();
        $checkstatus = array();
        $items = array();
        $notes = array();
        $wo_startdate = date("m/d/Y");
        $wo_hours = 0;
        $wo_status = 0;
        $wo_ec_field = "";
        $wo_ec_extra = "";
        $wo_item = "";
        $wo_startdate = "";
        $wo_enddate = "";
        $wo_description = "";
        $wo_us_id = 0;
        $wo_hours = "";
        $wo_status = 0;
        $wo_priority = 0;

        if ($eq_id > 0) {            
            $r = DB::table('equipments')
                ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                ->where('eq_id',$eq_id)->first();

            $checkType = $r->et_checklist;
            $eq_internalcode = $r->eq_internalcode;
            $eq_name = $r->eq_name;
            $equip = $r;

            $r=DB::table('equipcheck')
                    ->leftJoin('employees','employees.us_id','=','equipcheck.ec_us_id')
                    ->where('ec_id',$ec_id)->first();
            //$qry= "select * from equipcheck left join SchultesUsers on su_sc_id=ec_us_id where ec_id=".$ec_id;
            
            $ec_date=$r->ec_date;
            $ec_us_id = $r->ec_us_id;
            $ec_ins_mai_rep = $r->ec_ins_mai_rep;
            $ec_notes = $r->ec_notes;
            $ec_fields = $r->ec_fields;
            $ec_extra = $r->ec_extra;
            $inspector = $r->login;

            if ($ec_ins_mai_rep==0) $ec_typeck1="checked";
            if ($ec_ins_mai_rep==1) $ec_typeck2="checked";
            if ($ec_ins_mai_rep==2) $ec_typeck3="checked";
            if ($ec_ins_mai_rep==3) $ec_typeck4="checked";
            if (strlen(trim($ec_typeck1.$ec_typeck2.$ec_typeck3.$ec_typeck4))==0) $ec_typeck1="checked";

            $allfields = $this->getInspectionReport($checkType);
            $checkstatus = $this->splitReportItemsStatus($ec_fields);
            $items = $this->getListOfItemsToCheck($allfields, $checkstatus);
            $notes = $this->splitReportNotes($ec_extra, $allfields);

            $wo_ec_field = isset($req->wec_field) ? $req->wec_field : 0;
            $wo_ec_extra = isset($req->wec_extra) ? $req->wec_extra : 0;
            if ($wo_ec_field>0) {
                for ($i=0; $i<count($items); $i++) {
                    if ($items[$i]['id']==$wo_ec_field)  $wo_item = $items[$i]['item'];
                }
            }
            if ($wo_ec_extra>0) {
                for ($i=0; $i<count($notes); $i++) {
                    if ($notes[$i]['id']==$wo_ec_extra)  $wo_item = $notes[$i]['note'];
                }
            }

            $wo_startdate = date("m/d/Y");
            $wo_hours = 0;
            $wo_status = 0;
        }

        if ($wo_id > 0) {            
            $r = DB::table('workorders')->where('wo_id',$wo_id)->first();
            $wo_ec_field = $r->wo_ec_field;
            $wo_ec_extra = $r->wo_ec_extra;
            $wo_item = $r->wo_item;
            $wo_startdate = $this->toUSDate($r->wo_startdate);
            $wo_enddate = $this->toUSDate($r->wo_enddate);
            $wo_description = $r->wo_description;
            $wo_us_id = $r->wo_us_id;
            $wo_hours = $r->wo_hours;
            $wo_status = $r->wo_status;
            $wo_priority = $r->wo_priority;            
        }

        $listassigned = array();
        $listassigned[0] = Lang::get('workorders/title.assign');
        $resu = DB::table('employees')->where('us_sc_id',$user->sc_id)
                    ->orderBy('lastname')->orderBy('firstname')->get();
        foreach ($resu as $key => $value) {
            $listassigned[$value->us_id] = $value->firstname." ".$value->lastname;
        }

        $listpriority = array('0','1','2','3','4','5');
        $liststatus = $this->wstatus;

        $out = new \stdClass;
        $out->equip = $this->showEquipment($equip);
        $out->wo_item = $wo_item;
        $out->wo_startdate = $wo_startdate;
        $out->wo_enddate = $wo_enddate;
        $out->wo_description = $wo_description;
        $out->wo_us_id = $wo_us_id;
        $out->wo_hours = $wo_hours;
        $out->wo_priority = $wo_priority;
        $out->wo_status = $wo_status;       

        return view('admin.workorders.edit')->with(['data' => $out, 'eq_id' => $eq_id, 'ec_id' => $ec_id, 'backmode' => $backmode, 'wo_id' => $wo_id, 'wo_ec_field' => $wo_ec_field, 'wo_ec_extra' => $wo_ec_extra, 'listassigned' => $listassigned, 'listpriority' => $listpriority,'liststatus' => $liststatus]);        
    }

    public function getdatapart(Request $req){
        $res = DB::table('workordersparts')->where('wp_wo_id',$req->wo_id)->orderBy('wp_date')->get();
        
        return DataTables::of($res)    
            ->addColumn(
                'actions',
                function ($r) {
                    return '<button class="btn btn-success" onclick="editpart(' . $r->wp_id . ');" >'. Lang::get('button.edit') .'</button> <button class="btn btn-danger" onclick="deletepart(' . $r->wp_id . ');" >'. Lang::get('button.delete') .'</button>';
                }
            ) 
            ->addColumn(
                'wp_datetext',
                function ($r) {                    
                    return $this->toUSDate($r->wp_date);
                }
            )             
                       
            ->rawColumns(['actions'])         
            ->make(true);        
    }

    public function store(Request $req){
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;
        $wo_id = $req->wo_id;
        $backmode = $req->backmode;

        if($eq_id == "") $eq_id = 0;
        if($ec_id == "") $ec_id = 0;
        if($wo_id == "") $wo_id = 0;
                
        $wo_eq_id = $eq_id;
        $wo_ec_id = $ec_id;
        $wo_ec_field = $req->wo_ec_field == '' ? 0 : $req->wo_ec_field;
        $wo_ec_extra = $req->wo_ec_field == '' ? 0 : $req->wo_ec_field;
        $wo_startdate = Carbon::parse($req->wo_startdate)->format('Y-m-d');
        $wo_enddate = Carbon::parse($req->wo_enddate)->format('Y-m-d');
        $wo_item = $req->wo_item;
        $wo_description = $req->wo_description;
        $wo_us_id = $req->wo_us_id;
        $wo_hours = $req->wo_hours;
        $wo_priority = $req->wo_priority;
        $wo_status = $req->wo_status;
        
        $data = array(
            'wo_eq_id' => $wo_eq_id,
            'wo_ec_id' => $wo_ec_id,
            'wo_priority' => $wo_priority,
            'wo_ec_field' => $wo_ec_field, 
            'wo_ec_extra' => $wo_ec_extra, 
            'wo_startdate' => $wo_startdate,
            'wo_enddate' => $wo_enddate,
            'wo_item' => $wo_item,
            'wo_description' => $wo_description,
            'wo_us_id' => $wo_us_id,
            'wo_hours' => $wo_hours,            
            'wo_status' => $wo_status,
        );

        if($wo_id > 0){
            $res = DB::table('workorders')
                        ->where('wo_id',$wo_id)
                        ->update($data);
        }else{
            $res = DB::table('workorders')->insertGetId($data);
            $wo_id = $res;
        }
        return Redirect::route('admin.workorders.edit',[$backmode,$eq_id,$ec_id,$wo_id])->with('success', trans('workorders/message.success.save'));        
    }

    public function delete(Request $req){
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;
        $wo_id = $req->wo_id;
        $backmode = $req->backmode;
        $res = DB::table('workordersparts')->where('wp_wo_id',$wo_id)->delete();
        $res = DB::table('workorders')->where('wo_id',$wo_id)->where('wo_eq_id',$eq_id)->where('wo_ec_id',$ec_id)->delete();

        if($backmode == 1){
            return Redirect::route('admin.workorders')->with('success', trans('workorders/message.success.delete'));
        }else {
            // return to report
            return Redirect::route('admin.workorders')->with('success', trans('workorders/message.success.delete'));
        }
    }

    public function storepart(Request $req){
        $wo_id = $req->wo_id;
        $wp_id = $req->wp_id;
        $wp_date = Carbon::parse($req->wp_date)->format('Y-m-d');
        $wp_partnr = $req->wp_partnr;
        $wp_quantity = $req->wp_quantity;
        $wp_description = $req->wp_description;

        $data = array(
            'wp_wo_id' => $wo_id,            
            'wp_date' => $wp_date,
            'wp_partnr' => $wp_partnr,
            'wp_quantity' => $wp_quantity,
            'wp_description' => $wp_description,
        );

        if($wp_id > 0){
            $res = DB::table('workordersparts')->where('wp_id',$wp_id)->where('wp_wo_id',$wo_id)->update($data);
        }else{
            $res = DB::table('workordersparts')->insert($data);
        }
        if($res) return 1;
        else return 0;
    }

    public function deletepart(Request $req){
        $wo_id = $req->wo_id;
        $wp_id = $req->wp_id;
        $res = DB::table('workordersparts')->where('wp_id',$wp_id)->where('wp_wo_id',$wo_id)->delete();
        if($res) return 1;
        else return 0;
    }
}
