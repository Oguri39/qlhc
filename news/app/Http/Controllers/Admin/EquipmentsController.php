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

class EquipmentsController extends DefinedController 
{
    private $listeqfield1 = array(
                'eq_internalcode',
                'eq_name',
                'eq_vin',
                'eq_lic',
                'eq_notes',
                'eq_date_start',
                'eq_date_end',
                'eq_description',
                'eq_company',
                'eq_model',
                'eq_department',
                'eq_gvw',
                'eq_regtype',
                'eq_regexp',
                'eq_insuranceexp',
                'eq_ezpass',
                'eq_detexp',
                'eq_detsticker',
                'eq_nyinsexp',
                'eq_nyhut',
                'eq_iftaexp',
                'eq_iftanr',
            );                  

    private $listeqfield2 = array(
                'eq_id',
                'eq_sc_id',
                'eq_et_id',
                'eq_hours_operation',
                'eq_check_in_miles',
                'eq_check_in_hours',
                'eq_status',
                'eq_us_id',                
                'eq_eq_id',
                'eq_year',
                'eq_regreq',
                'eq_regcost',
                'eq_titlebank',
                'eq_insurancereq',
                'eq_ezpassreq',
                'eq_detreq',
                'eq_nyinspreq',
                'eq_ifta',
                'eq_candrive'
            );

    private $listconvertdate = array(
                'eq_detexp',
                'eq_iftaexp',
                'eq_date_start',
                'eq_date_end',
                'eq_regexp',
                'eq_insuranceexp',
                'eq_nyinspreq',
                'eq_iftaexp',
    );
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){         
        return view('admin.equipments.index');
    }

    public function getData(Request $req){        
        $user = Sentinel::getUser();
        
        $res = DB::table('equipments')
                    ->select(DB::raw("eq_id, et_title, eq_internalcode, eq_name, eq_candrive,eq_status, eq_notes, eq_check_in_miles, eq_check_in_hours, if(eu_id > 0,CONCAT('yes - ', firstname, ' ', lastname),'no') as eq_in_use, 0 as eq_has_rreq"))
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                    ->leftJoin('equipusers','equipments.eq_id','=','equipusers.eu_eq_id')
                    ->leftJoin('employees','employees.us_id','=','equipusers.eu_us_id')
                    ->where('eq_sc_id',$user->sc_id)
                    //->where('eu_ec_end',0)
                    ->orderBy('eq_name', 'desc')
                    ->groupBy('eq_id')
                    ->distinct('eq_id')
                    ->get();
        
        return DataTables::of($res)
            ->addColumn(
                'qr',
                function ($r) {
                    return '<input type="checkbox" name="ck_'.$r->eq_id.'" value="'.$r->eq_id.'" class="equipqr form-control">';
                }
            )
            ->editColumn(
                'eq_candrive',
                function ($r) {
                    if($r->eq_candrive == 0){
                        return '<button class="btn btn-danger" onclick="setdrive(1,' . $r->eq_id . ');" >' . Lang::get('general.no') . '</button>';
                    }else{
                        return '<button class="btn btn-success" onclick="setdrive(0,' . $r->eq_id . ');" >' . Lang::get('general.yes') . '</button>';
                    }                    
                }
            )
            ->editColumn(
                'eq_status',
                function ($r) {
                    if($r->eq_status == 0){
                        return Lang::get('equipments/title.canbeused');
                    }else{
                        return Lang::get('equipments/title.cannotbeused');
                    }                    
                }
            )
            ->editColumn(
                'eq_name',
                function ($r) {
                    return '<a href="' . route('admin.equipments.edit',$r->eq_id) . '">' . $r->eq_name . '</a>';
                }
            )            
            ->rawColumns(['qr','eq_name','eq_candrive'])         
            ->make(true);        
    }

    public function changedrive(Request $req){
        $eq_id = $req->eq_id;
        $state = $req->state;        
        $res = DB::table('equipments')->where('eq_id',$eq_id)->update(['eq_candrive' => $state]);
        if($res) return 1;
        else return 0;
    }

    public function exportqr(Request $req){
        $valor = $req->valor;
        $listname = $req->listname;
        $out = "";

        $res = DB::table('equipments')->whereIn('eq_id',$valor)->get();
        if($res){
            $path = public_path() . "/uploads/equipmentqr/";
            $i = 0;
            foreach ($res as $key => $value) {
                $file = $path . $value->eq_id . '.png';
                if(!file_exists($file)){
                    $img = \QrCode::format('png')
                         ->size(200)->generate($value->eq_id);
                    file_put_contents($file, $img);
                }
                $link = asset('uploads/equipmentqr/'. $value->eq_id . '.png');
                if($i > 0 && $i % 3 == 0){
                    $out .= '</tr><tr>';
                }
                $out .= '<td align="center">';
                $out .= '<img src="'. $link .'" width="200" height="200"/><br/>';
                $out .= $value->eq_internalcode . '<br/>';
                $out .= $value->eq_name;
                $out .= '</td>';
                $i++;
            }
        }
        return $out;        
    }

    public function edit(Request $req){
        $user = Sentinel::getUser();
        $eq_id = $req->eq_id;
                
        if ($eq_id > 0) {
            $data = DB::table('equipments')
                    ->leftJoin('equiptypes','equiptypes.et_id','=','equipments.eq_et_id')
                    ->where('eq_sc_id',$user->sc_id)
                    ->where('eq_id',$eq_id)->first();

            foreach ($this->listconvertdate as $key => $value) {
                $data->{$value} = $this->toUSDate($data->{$value});    
            }
        }else{
            $data = new \stdClass;
            
            foreach ($this->listeqfield1 as $key => $value) {
                $data->{$value} = '';
            }            
           
            foreach ($this->listeqfield2 as $key => $value) {
                $data->{$value} = 0;
            }
            $data->eq_company = null;
            $data->eq_year = '';
        }
        
        $listtype = array();
        $listtype[0] = Lang::get('equipments/title.pleaseselect');
        $res = DB::table('equiptypes')->orderBy('et_title')->get();
        foreach ($res as $key => $value) {
            $listtype[$value->et_id] = $value->et_title;
        }

        $liststatus = array(Lang::get('equipments/title.canbeused'),Lang::get('equipments/title.cannotbeused'));
        $listoperators = array();
        $listoperators[0] = Lang::get('equipments/title.pleaseselect');
        $res = DB::table('employees')->where('us_sc_id',$user->sc_id)
                        ->orderBy('lastname')->orderBy('firstname')->get();
        foreach ($res as $key => $value) {
            $listoperators[$value->us_id] = $value->firstname . " " . $value->lastname;
        }

        $listassoc[0] = Lang::get('equipments/title.pleaseselect');
        $res = DB::table('equipments')->where('eq_sc_id',$user->sc_id)
                        ->orderBy('eq_internalcode')->get();
        foreach ($res as $key => $value) {
            $listassoc[$value->eq_id] = $value->eq_internalcode . " " . $value->eq_name;
        }

        $listyesno = array(Lang::get('general.no'),Lang::get('general.yes'));

        return view('admin.equipments.edit')->with(['data'=>$data, 'listtype' => $listtype, 'eq_id' => $eq_id, 'liststatus' => $liststatus, 'listoperators' => $listoperators, 'listassoc' => $listassoc, 'listyesno' => $listyesno, 'listcompanies' => $this->listcompanies, 'listdepartments' => $this->listdepartments]); 
    }

    public function store(Request $req){ 
        $user = Sentinel::getUser();       
        $eq_id = $req->eq_id;
        $dat = new \stdClass;

        foreach ($this->listeqfield1 as $key => $value) {
            if(isset($req->{$value})){
                $dat->{$value} = $req->{$value};    
            }            
        }
        foreach ($this->listeqfield2 as $key => $value) {
            if(isset($req->{$value})){
                $dat->{$value} = $req->{$value};    
            }
        }

        foreach ($this->listconvertdate as $key => $value) {
            if($req->{$value} != ''){
                $dat->{$value} = Carbon::parse($req->{$value})->format('Y-m-d');
            }else{
                $dat->{$value} = "0000-00-00";
            }            
        }

        $datainsert = array();
        $dataupdate = array();
        foreach ($this->listeqfield1 as $key => $value) {
            if(!isset($dat->{$value})){
                $datainsert[$value] = '';                
            }else{
                $datainsert[$value] = $dat->{$value};
                $dataupdate[$value] = $dat->{$value};
            }            
        }
        foreach ($this->listeqfield2 as $key => $value) {
            if(!isset($dat->{$value})){
                $datainsert[$value] = '';                
            }else{
                $datainsert[$value] = $dat->{$value};
                $dataupdate[$value] = $dat->{$value};
            }
        }

        if($eq_id > 0){
            $res = DB::table('equipments')->where('eq_id',$eq_id)->update($dataupdate);
        }else{
            $datainsert['eq_sc_id'] = $user->sc_id;
            $res = DB::table('equipments')->insert($datainsert);
        }

        if($res) return Redirect::route('admin.equipments')->with('success', trans('equipments/message.success.save'));
        else return Redirect::back()->withInput()->with('error', trans('equipments/message.error.save'));
    }

    public function delete(Request $req){
        $user = Sentinel::getUser();
        $res = DB::table('equipments')->where('eq_id',$req->eq_id)->where('eq_sc_id',$user->sc_id)->delete();
        if($res) return Redirect::route('admin.equipments')->with('success', trans('equipments/message.success.delete'));
        else return Redirect::back()->withInput()->with('error', trans('equipments/message.error.delete'));
    }
}