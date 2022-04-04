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

class EquipusersController extends DefinedController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){ 
        return view('admin.equip_users.index');
    }

    public function getData(){        
        $user = Sentinel::getUser();
        $res = DB::table('equipusers')
                    ->leftJoin('equipments','equipments.eq_id','=','equipusers.eu_eq_id')
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                    ->leftJoin('employees','employees.us_id','=','equipusers.eu_us_id')
                    ->where('eq_sc_id',$user->sc_id)
                    ->where('us_sc_id',$user->sc_id)
                    ->orderBy('eu_ec_end','desc')
                    ->orderBy('eu_id','desc')->get();
        return DataTables::of($res)    
            ->addColumn(
                'bgcolor',
                function ($r) {
                    if($r->eu_ec_end == 0) return "#FFFFFF";
                    else return "#CCCCCC";
                }
            ) 
            ->addColumn(
                'eu_starttext',
                function ($r) {
                    $out = $this->toUSDateTime($r->eu_start);
                    if (($r->eu_start_lat + $r->eu_start_lon) != 0) {
                        $out .= ' <a href="http://maps.google.com/?q=' . $r->eu_start_lat . ',' . $r->eu_start_lon .'" target="_new"><img src="' . asset('img/mapicon.png') . '" border=0 height=30></a>';
                    }
                    return $out;
                }
            )
            ->addColumn(
                'eu_endtext',
                function ($r) {
                    $out = $this->toUSDateTime($r->eu_end);
                    if (($r->eu_end_lat + $r->eu_end_lon) != 0) {
                        $out .= ' <a href="http://maps.google.com/?q=' . $r->eu_end_lat . ',' . $r->eu_end_lon .'" target="_new"><img src="' . asset('img/mapicon.png') . '" border=0 height=30></a>';
                    }
                    return $out;
                }
            )
            ->addColumn(
                'eu_onsitetext',
                function ($r) {
                    if ($r->eu_onsite=="0") {
                        return Lang::get('general.no');
                    }else{
                        return Lang::get('general.yes');
                    }
                }
            )
            ->addColumn(
                'eu_ec_starttext',
                function ($r) {
                    return '<a href="'. route('admin.equip_users.editcheck',[1,$r->eq_id,$r->eu_ec_start]) .'" >' . $r->eu_ec_start . '</a>';                  
                }
            )
            ->addColumn(
                'eu_ec_endtext',
                function ($r) {
                    return '<a href="'. route('admin.equip_users.editcheck',[1,$r->eq_id,$r->eu_ec_end]) .'" >' . $r->eu_ec_end . '</a>';
                }
            )  
            ->addColumn(
                'employeename',
                function ($r) {
                    return $r->firstname." ".$r->lastname;                  
                }
            )
            ->rawColumns(['eu_starttext','eu_endtext','eu_ec_starttext','eu_ec_endtext'])         
            ->make(true);
        
    }
     
    public function editcheck(Request $req){     
        $user = Sentinel::getUser();   
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;
        $backmode = $req->backmode;

        if($eq_id == "") $eq_id = 0;
        if($ec_id == "") $ec_id = 0;
        
        $routeback = route('admin.equipments.edit',$eq_id);
        if($backmode == 1){
            $routeback = route('admin.equip_users');
        }else if($backmode == 2){
            $routeback = "";
        } 

        $r = DB::table('equipments')
                ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                ->where('eq_id',$eq_id)->first();

        $checkType = $r->et_checklist;
        $eq_internalcode = $r->eq_internalcode;
        $eq_name = $r->eq_name;
        $fields = $this->getInspectionReport($checkType);

        $ec_date = date("Y-m-d");

        $eq_et_id= '';
        $eq_name = '';
        $eq_notes= '';
        $eq_status = '';
        $equip = '';

        if ($eq_id > 0) {
            $r = DB::table('equipments')
                ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                ->where('eq_id',$eq_id)
                ->where('eq_sc_id',$user->sc_id)->first();
            $eq_et_id=$r->eq_et_id;
            $eq_name = $r->eq_name;
            $eq_notes=$r->eq_notes;
            $eq_status = $r->eq_status;
            $equip = $r;
        }

        $current ="";
        $ec_typeck1 = "";
        $ec_typeck2 = "";
        $ec_typeck3 = "";
        $ec_typeck4 = "";
        $ec_us_id = '';
        $ec_ins_mai_rep=0;
        $ec_notes = "";
        $ec_fields = "";
        $ec_extra = "";
        $inspector = '';  
        $ec_status = '';  
        $notes = array();    

        if ($ec_id>0) {
            //$qry= "select * from equipcheck left join SchultesUsers on su_sc_id=ec_us_id where ec_id=".$ec_id;
            $r=DB::table('equipcheck')
                    ->leftJoin('employees','employees.us_id','=','equipcheck.ec_us_id')
                    ->where('ec_id',$ec_id)->first();
            if($r != null){
                $ec_date=$r->ec_date;
                $ec_us_id = $r->ec_us_id;
                $ec_ins_mai_rep = $r->ec_ins_mai_rep;
                $ec_notes = $r->ec_notes;
                $ec_fields = $r->ec_fields;
                $ec_extra = $r->ec_extra;
                $inspector = $r->login;
                $ec_status = $r->ec_status;            
                
                $current = $this->splitReportItemsStatus($ec_fields);

                if ($ec_ins_mai_rep==0) $ec_typeck1="checked";
                if ($ec_ins_mai_rep==1) $ec_typeck2="checked";
                if ($ec_ins_mai_rep==2) $ec_typeck3="checked";
                if ($ec_ins_mai_rep==3) $ec_typeck4="checked";
                if (strlen(trim($ec_typeck1.$ec_typeck2.$ec_typeck3.$ec_typeck4))==0) $ec_typeck1=Lang::get('equipments/title.checked');            
                $notes = $this->splitReportNotes($ec_extra, $fields);
            }else{
                $ec_id = 0;
            }
        } else {
            //$qry= "select * from SchultesUsers where su_sc_id=$ID";                        
            $ec_us_id = $user->id;
            $inspector = $user->email;            
        }

        $out = new \stdClass;
        $out->reporttitle = Lang::get('equipments/title.reportfor') . " " . $eq_internalcode . " - " . $eq_name;
        $out->equip = $this->showEquipment($equip);
        $out->date = $this->toUSDate($ec_date);
        $out->inspector = $inspector;
        $out->ec_us_id = $ec_us_id;
        $out->type = array($ec_typeck1,$ec_typeck2,$ec_typeck3,$ec_typeck4);
        $out->typelist = Lang::get('equipments/title.typelist');
        $out->ec_status = $ec_status;
        $out->liststatus = $this->wstatus;
        $out->notes = $ec_notes;
        $panel = "";

        $col = 0;
        $nrelemcol = count($fields) / 3;
        $j=0;
        $editmode = 1;
        if($ec_id == 0) $editmode = 0;
        while ($j<count($fields)) {
            $panel .= '<div class="col-lg-4">';
            for ($m = 0; $m<$nrelemcol; $m++) {
                if ($j<count($fields)) 
                    $panel .= $this->showPanel($j, $current, $notes, $editmode, $this->pastelColors(), $fields);
                $j++;
            }
            $panel .= '</div>';
        }
        return view('admin.equip_users.editcheck')->with(['data' => $out, 'panel' => $panel, 'eq_id' => $eq_id, 'ec_id' => $ec_id, 'backmode' => $backmode]);        
    }

    public function storecheck(Request $req){
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;
        $backmode = $req->backmode;

        if($eq_id == "") $eq_id = 0;
        if($ec_id == "") $ec_id = 0;
                
        $r = DB::table('equipments')
                ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                ->where('eq_id',$eq_id)->first();

        $checkType = $r->et_checklist;
        $eq_internalcode = $r->eq_internalcode;
        $eq_name = $r->eq_name;
        $fields = $this->getInspectionReport($checkType);

        $ec_fields = "";
        $ec_extra = "";
        for ($i = 0; $i<count($fields); $i++) {
            for ($j=0; $j<count($fields[$i]['fields']);$j++) {
                $fid = $fields[$i]['fields'][$j]['pos'];
                $var = "ck_btn_$fid";
                $val = $req->{$var};
                if ($val=="") $val = 0;
                $ec_fields.="$fid:$val;";
            }
            $xfid = $fields[$i]['pos'];
            $xvar = "ck_notes_$xfid";
            $xval= addslashes($req->{$xvar});
            $ec_extra.="$xfid~".$xval."|";
        }

        $ec_notes = $req->ec_notes;
        $ec_date = Carbon::parse($req->ec_date)->format('Y-m-d');
        $ec_us_id = $req->ec_us_id;
        $ec_ins_mai_rep = $req->ec_ins_mai_rep;
        $ec_status = $req->ec_status;
        
        $data = array(
            'ec_date'   => $ec_date,
            'ec_us_id'  => $ec_us_id,
            'ec_ins_mai_rep'    => $ec_ins_mai_rep,
            'ec_notes'  => $ec_notes,
            'ec_fields' => $ec_fields,
            'ec_extra'  => $ec_extra,
            'ec_status' => $ec_status
        );

        if($ec_id == 0){
            $data['ec_eq_id'] = $eq_id;
            $res = DB::table('equipcheck')->insertGetId($data);
            $ec_id = $res;
        }else{
            $res = DB::table('equipcheck')->where('ec_id',$ec_id)->where('ec_eq_id',$eq_id)->update($data);
        }
        return Redirect::route('admin.equip_users.editcheck',[$backmode,$eq_id,$ec_id])->with('success', trans('equip_users/message.success.save'));        
    }

    public function deletecheck(Request $req){
        $eq_id = $req->eq_id;
        $ec_id = $req->ec_id;
        $backmode = $req->backmode;
        $res = DB::table('equipcheck')->where('ec_id',$ec_id)->where('ec_eq_id',$eq_id)->delete();

        if($backmode == 1){
            return Redirect::route('admin.equip_users')->with('success', trans('equip_users/message.success.delete'));
        }else {
            // return to report
            return Redirect::route('admin.equip_users')->with('success', trans('equip_users/message.success.delete'));
        }
    }
}
