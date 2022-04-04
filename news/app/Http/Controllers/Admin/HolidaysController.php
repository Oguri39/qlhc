<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
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

class HolidaysController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){          
        $user = Sentinel::getUser();
        $today = date('Y-m-d');
        $date1 = date('Y-m-d',strtotime('-2 month',strtotime($today)));
        $date2 = date('Y-m-d',strtotime('+2 month',strtotime($today)));        
        $res = DB::table('holidays')->where('ho_sc_id',$user->sc_id)
                        ->whereDate('ho_day','>=',$date1)
                        ->whereDate('ho_day','<',$date2)->get();

        return view('admin.holidays.index')->with(['listdate' => $res]);
    }

    public function getData(Request $req){
        $user = Sentinel::getUser();
        $date = $req->date;
        $date1 = date('Y-m-d',strtotime('-2 month',strtotime($date)));
        $date2 = date('Y-m-d',strtotime('+2 month',strtotime($date)));
        $output = array();

        $res = DB::table('holidays')->where('ho_sc_id',$user->sc_id)
                        ->whereDate('ho_day','>=',$date1)
                        ->whereDate('ho_day','<',$date2)->get();

        foreach ($res as $key => $value) {
            array_push($output, $value->ho_day);
        }              

        return $output;
    }

    public function saveday(Request $req){
        $user = Sentinel::getUser();
        $date = $req->date;
        
        if($date > date('Y-m-d')){
            $check = DB::table('holidays')->where('ho_sc_id',$user->sc_id)
                        ->whereDate('ho_day',$date)->count();

            if($check > 0){
                $res = DB::table('holidays')->where('ho_sc_id',$user->sc_id)
                        ->whereDate('ho_day',$date)->delete();                
                return -1;
            }else{
                $data = array(
                    'ho_day' => $date,
                    'ho_sc_id' => $user->sc_id,
                    'ho_status' => 0,
                );
                $res = DB::table('holidays')->insert($data);
                if($res) return 1;
                else return 0;
            }
        }else{
            return 0;
        }
    }    
}
