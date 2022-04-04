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

class EquipmenttypeController extends DefinedController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){                  
        return view('admin.equipmenttype.index')->with(['listchecklist' => $this->listchecklist]);
    }

    public function getData(){               
        $equiptypes = DB::table('equiptypes')->orderBy('et_title')->get(); 
        foreach ($equiptypes as $key => $r) {
            if(!isset($this->listchecklist[$r->et_checklist])){
                $r->et_checklisttext = $this->listchecklist[0];
            }else{
                $r->et_checklisttext = $this->listchecklist[$r->et_checklist];
            }
        }
        return DataTables::of($equiptypes)            
            ->make(true);
    }
    

    /**
     * Store a newly created Job in storage.
     *
     * @param CreateJobRequest $req
     *
     * @return Response
     */
    public function store(Request $req){
        $et_id = $req->et_id;      
        $et_checklist = $req->et_checklist;  
        $data = array(
            'et_title'  => $req->et_title,            
            'et_checklist'   => $et_checklist,            
        );
        $res = null;
        if($et_id > 0){
            $res = DB::table('equiptypes')->where('et_id',$et_id)->update($data);    
        }else{
            $res = DB::table('equiptypes')->insertGetId($data);
            // if($et_checklist == 0){
            //     $resu = DB::table('equiptypes')->where('et_id',$res)->update(['et_checklist' => $res]);        
            // }
        }        
        return 1;        
    }
    
    
    /**
     * Remove the specified Job from storage.
     *
     * 
     *
     * @return Response
     */
     
    public function delete(Request $req){        
        $et_id = $req->et_id;
        $res = DB::table('equiptypes')->where('et_id',$et_id)->delete();
        if($res) return 1;
        else return 0;
    }

}
