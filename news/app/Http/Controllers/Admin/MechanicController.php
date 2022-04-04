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

class MechanicController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){               
        return view('admin.mechanics.index');
    }

    public function getData(Request $req){
        $user = Sentinel::getUser();
        $opt = $req->opt;
        $employees = DB::table('employees')->where('us_sc_id',$user->sc_id)->where('role',1);
        if($opt == 1){
            $employees = $employees->where('us_active',1);
        }

        $employees = $employees->orderBy('us_active','desc')->orderBy('lastname')->orderBy('firstname')->get();

        return DataTables::of($employees)
            ->addColumn(
                'activetext',
                function ($value) {
                    return $value->us_active == 0  ? Lang::get('general.no') : Lang::get('general.yes');
                }
            )
            ->addColumn(
                'born',
                function ($value) {
                    return Carbon::parse($value->datebirth)->format('m/d/Y');
                }
            )
            ->addColumn(
                'hired',
                function ($value) {
                    return Carbon::parse($value->datehired)->format('m/d/Y');
                }
            )
            ->addColumn(
                'actions',
                function ($value) {
                    return '<a href="'. route('mechanics.vacation',[$value->us_id,$value->firstname,$value->lastname]) .'" class="btn btn-warning">' . Lang::get('employees/title.vacations') . '</a>';
                }
            )
            ->addColumn(
                'clock',
                function ($value) {
                    return '<div style="margin-top:5px;" id="divshowtime' . $value->us_id . '"></div>';
                }
            )
            ->addColumn(
                'addwork',
                function ($value) {
                    $day = date('Y-m-d');
                    $hidestart = 'style="display: none;"';
                    $hideend = 'style="display: none;"'; 
                    $wkd = DB::table('workdays')->where('wkd_us_id',$value->us_id)->where('wkd_day',$day)->first();
                    $starttime = '';
                    $hrsid = 0;
                    $wkdid = 0;
                    if($wkd == null){
                        $hidestart = '';
                    }else{
                        $wkdid = $wkd->wkd_id;
                        $hour = DB::table('hours')                                
                                ->where('hrs_wkd_id',$wkd->wkd_id)
                                ->orderBy('hrs_starttime','desc')
                                ->first();

                        if($hour->hrs_starttime != '0000-00-00 00:00:00' && $hour->hrs_endtime == $day . ' 00:00:00'){
                            $hideend = '';
                            $starttime = $hour->hrs_starttime;
                            $hrsid = $hour->hrs_id;
                        }else{
                            $hidestart = '';
                        }
                    }

                    $str = '<button class="btn btn-success" id="start' . $value->us_id . '" onclick="startwork(' . $value->us_id . ')" ' . $hidestart . '>' . Lang::get('employees/title.startwork') . '</button>';
                    $str .= '<input type="hidden" id="wkdid' . $value->us_id . '" value="' . $wkdid . '">';
                    $str .= '<input type="hidden" id="hrsid' . $value->us_id . '" value="' . $hrsid. '">';
                    $str .= '<input type="hidden" id="starttime' . $value->us_id . '" value="' . $starttime. '" class="starttime">';
                    $str .= ' <button class="btn btn-danger" id="end' . $value->us_id . '" onclick="endwork(' . $value->us_id . ')" ' . $hideend . '>' . Lang::get('employees/title.endwork') . '</button>';

                    return $str;
                }
            )
            ->rawColumns(['actions','addwork','clock'])
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
            'role'        => 1,
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
            'role'        => 1,
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
        $res = DB::table('employees')->where('us_sc_id',$user->sc_id)->where('login',$login)->where('role',1)->count();
        if($res > 0) return 0;
        else return 1;
    }

    public function vacation(Request $req){
        $firstname = $req->firstname;
        $lastname = $req->lastname;
        $us_id = $req->us_id;

        return view('admin.mechanics.vacation')->with(['firstname' => $firstname, 'lastname' => $lastname, 'us_id' => $us_id]);
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
        $user = Sentinel::getUser();
        $employees = DB::table('employees')->where('us_sc_id',$user->sc_id)->where('role',1)->orderBy('us_active','desc')->orderBy('lastname')->orderBy('firstname')->get();
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

    public function startwork(Request $req){
        $user = Sentinel::getUser();
        $us_id = $req->us_id;
        $wkd_id = $req->wkd_id;   

        $rb = DB::table('employees')
                    ->where('us_id',$us_id)->first();

        $employeeDept = $rb->departmentNr;
        $driller=1;
        if (substr($employeeDept,1,2)=='02') $driller=0;

        if($wkd_id == 0){
            $dat = array(                    
                'wkd_sc_id' => $user->sc_id,
                'wkd_us_id' => $rb->us_id,
                'wkd_driller_helper' => $driller,
                'wkd_truck_driver' => 0,
                'wkd_liveexp' => 0,
                'wkd_lunch' => 0,
                'wkd_lunchtime' => "0000-00-00 00:00:00",
                'wkd_miles' => 0,
                'wkd_day' => date('Y-m-d'),
                'wkd_shift_work' => 1,
                'wkd_end_realtime' => 0,
                'wkd_gps_latitude' => 0,
                'wkd_gps_longitude' => 0,
                'wkd_timestamp' => date('Y-m-d H:i:s'),
                'wkd_status' => 0,                    
                'wkd_notes' => "",
                'wkd_recalctime' => '0000-00-00 00:00:00',
                'wkd_locked' => 0,
            );
            $wkd_id = DB::table('workdays')->insertGetId($dat);
        }
            
        $dat = array(                    
            'hrs_jobid' => -20,
            'hrs_starttime' => date('Y-m-d H:i:s'),
            'hrs_endtime' => date('Y-m-d') . ' 00:00:00',
            'hrs_realtime' => '0000-00-00 00:00:00',
            'hrs_truckdriver' => 0,
            'hrs_gps_start_lat' => 0,
            'hrs_gps_start_lon' => 0,
            'hrs_gps_end_lat' => 0,
            'hrs_gps_end_lon' => 0,
            'hrs_regular' => 0,
            'hrs_ovt' => 0,
            'hrs_double' => 0,
            'hrs_status' => 0,
            'hrs_wkd_id' => $wkd_id,                    
        );
        $hrs_id = DB::table('hours')->insertGetId($dat);
        return array(0 => 1, 1 => date('Y-m-d H:i:s'), 2 => $wkd_id, 3 => $hrs_id);    
    }

    public function endwork(Request $req){
        $user = Sentinel::getUser();
        $us_id = $req->us_id;
        $wkd_id = $req->wkd_id;   
        $hrs_id = $req->hrs_id;
                    
        $dat = array(                    
            'hrs_endtime' => date('Y-m-d H:i:s'),            
        );
        $resu = DB::table('hours')->where('hrs_id',$hrs_id)->update($dat);
        if($resu){
            // check wkd
            $wkd = DB::table('workdays')->where('wkd_id',$wkd_id)->first();
            $hours = DB::table('hours')
                    ->where('hrs_wkd_id',$wkd_id)
                    ->get();
            $sum = 0;
            foreach ($hours as $key => $value) {
                $tg = strtotime($value->hrs_endtime) - strtotime($value->hrs_starttime);
                if($tg > 0){
                    $sum += $tg;
                }
            }

            if($tg / 60 >= 270){
                $resus = DB::table('workdays')->where('wkd_id',$wkd_id)->update([
                    'wkd_lunch' => 1,
                    'wkd_lunchtime' => date('Y-m-d') . ' 12:00:00',
                ]);
            }
            return 1;
        }else{
            return 0;        
        }
        
    }

}
