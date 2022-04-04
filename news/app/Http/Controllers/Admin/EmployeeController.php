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

class EmployeeController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){        
        return view('admin.employees.index');
    }

    public function getData(Request $req){
        $user = Sentinel::getUser();
        $opt = $req->opt;
        $employees = DB::table('employees')->where('us_sc_id',$user->sc_id);
        if($opt == 1){
            $employees = $employees->where('us_active',1);
        }

        $employees = $employees->orderBy('us_active','desc')->orderBy('lastname')->orderBy('firstname')->get();

        return DataTables::of($employees)
            ->addColumn(
                'activetext',
                function ($user) {
                    return $user->us_active == 0  ? Lang::get('general.no') : Lang::get('general.yes');
                }
            )
            ->addColumn(
                'born',
                function ($user) {
                    return Carbon::parse($user->datebirth)->format('m/d/Y');
                }
            )
            ->addColumn(
                'hired',
                function ($user) {
                    return Carbon::parse($user->datehired)->format('m/d/Y');
                }
            )
            ->addColumn(
                'actions',
                function ($user) {
                    return '<a href="'. route('admin.employees.vacation',[$user->us_id,$user->firstname,$user->lastname]) .'" class="btn btn-warning">' . Lang::get('employees/title.vacations') . '</a>';
                }
            )
            ->rawColumns(['actions'])
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
        $user = Sentinel::getUser();

        $data = array(
            'us_sc_id'  => $user->sc_id,            
            'login'   => $req->login,
            'password'        => $req->password,
            'firstname'    => $req->firstname,
            'lastname'  => $req->lastname,
            'datebirth'  => Carbon::parse($req->born)->format('Y-m-d'),
            'datehired'  => Carbon::parse($req->hired)->format('Y-m-d'),                        
            'address1'  => $req->address,
            'city'   => $req->city,
            'zip'   => $req->zip,
            'state'   => $req->state,
            'tradeNr'   => $req->tradenr,
            'departmentNr'   => $req->deptnr,
            'tel'   => $req->tel,
            'liveawayexp'   => $req->liveexpressrate,
            'milesrate'   => $req->milesrate,
            'drivetimerate'   => $req->drivetimerate,
            'drivetimeratecdl'   => $req->drivecdltimerate,
            'us_active'   => $req->active,
            'role'        => $req->role,
        );        
        $res = DB::table('employees')->insertGetId($data);
        if($req->id == ''){
            $resu = DB::table('employees')->where('us_sc_id',$user->sc_id)->where('us_id',$res)->update(['userId' => $res]);
        }else{
            $resu = DB::table('employees')->where('us_sc_id',$user->sc_id)->where('us_id',$res)->update(['userId' => $req->id]);
        }
        if($res > 0) return $res;
        else return 0;
    }
    
    /**
     * Update the specified Job in storage.
     *
     * @param UpdateJobRequest $req
     *
     * @return Response
     */
    public function update(Request $req){
        $user = Sentinel::getUser();

        $data = array(
            'us_sc_id'  => $user->sc_id,            
            'login'   => $req->login,
            'password'        => $req->password,
            'firstname'    => $req->firstname,
            'lastname'  => $req->lastname,
            'datebirth'  => Carbon::parse($req->born)->format('Y-m-d'),
            'datehired'  => Carbon::parse($req->hired)->format('Y-m-d'),                        
            'address1'  => $req->address,
            'city'   => $req->city,
            'zip'   => $req->zip,
            'state'   => $req->state,
            'tradeNr'   => $req->tradenr,
            'departmentNr'   => $req->deptnr,
            'tel'   => $req->tel,
            'liveawayexp'   => $req->liveexpressrate,
            'milesrate'   => $req->milesrate,
            'drivetimerate'   => $req->drivetimerate,
            'drivetimeratecdl'   => $req->drivecdltimerate,
            'us_active'   => $req->active,
            'userId' => $req->id,
            'role'        => $req->role,
        );        
        $res = DB::table('employees')->where('us_sc_id',$user->sc_id)->where('us_id',$req->us_id)->update($data);        
        if($res) return 1;
        else return 0;
    }

    /**
     * Remove the specified Job from storage.
     *
     * 
     *
     * @return Response
     */
     
    public function delete(Request $req){
        $user = Sentinel::getUser();
        $us_id = $req->us_id;
        $res = DB::table('employees')->where('us_id',$us_id)->where('us_sc_id',$user->sc_id)->delete();
        if($res) return 1;
        else return 0;
    }

    /**
    *   Check the login is unique for storing.
    */
    public function checkloginunique(Request $req){
        $user = Sentinel::getUser();
        $login = $req->login;
        $res = DB::table('employees')->where('us_sc_id',$user->sc_id)->where('login',$login)->count();
        if($res > 0) return 0;
        else return 1;
    }

    public function vacation(Request $req){
        $firstname = $req->firstname;
        $lastname = $req->lastname;
        $us_id = $req->us_id;

        return view('admin.employees.vacation')->with(['firstname' => $firstname, 'lastname' => $lastname, 'us_id' => $us_id]);
    }

    public function getdatavacation(Request $req){
        $us_id = $req->us_id;
        $vacations = DB::table('vacations')->where('va_us_id',$us_id)->orderBy('va_day','desc')->get();
        
        return DataTables::of($vacations)
            ->addColumn(
                'day',
                function ($user) {
                    return Carbon::parse($user->va_day)->format('m/d/Y');
                }
            )
            ->addColumn(
                'actions',
                function ($user) {
                    if($user->va_status == 0){
                        return Lang::get('button.edit');   
                    }else{
                        return "";
                    }                    
                }
            )
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function savevacation(Request $req){
        $user = Sentinel::getUser();
        $us_id = $req->us_id;
        $day = Carbon::parse($req->day)->format('Y-m-d');
        $hours = $req->hours;
        $description = $req->description;
        $data = array(
            'va_us_id'  => $us_id,
            'va_sc_id'  => $user->sc_id,
            'va_title'  => $description,
            'va_day'    => $day,
            'va_hours'  => $hours,
            'va_status' => 0,
        );
        $check = DB::table('vacations')->where('va_us_id',$us_id)->where('va_day',$day)->first();
        if($check){
            if($hours == 0){
                $res = DB::table('vacations')->where('va_id',$check->va_id)->delete();
                
            }else{
                $res = DB::table('vacations')->where('va_id',$check->va_id)->update($data);                
            }
        }else{
            if($hours > 0){
                $res = DB::table('vacations')->insert($data);                
            }else{
                $res = null;
            }
        }
        if($res) return 1;
        else return 0;
    }

    public function export(){
        $employees = DB::table('employees')->orderBy('us_active','desc')->orderBy('lastname')->orderBy('firstname')->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );      

        $columns = array(   'us_id',
                            'us_sc_id',
                            'login',
                            'password',
                            'firstname',
                            'lastname',
                            'address1',
                            'city',
                            'state',
                            'zip',
                            'datebirth',
                            'datehired',
                            'gender',
                            'tel',
                            'tradeNr',
                            'departmentNr',
                            'userId',
                            'drivetimerate',
                            'drivetimeratecdl',
                            'liveawayexp',
                            'milesrate',
                            'us_active',
                            'role'   );

        $out = array();
        foreach ($employees as $key => $value) {
            $data = array();
            foreach ($columns as $key1 => $value1) {
                $data[$value1] = $value->{$value1};
            }
            array_push($out, $data);
        }

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

}
