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

class JobController 
{
    
    /**
     * Display a listing of the Job.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){        
        return view('admin.jobs.index');
    }

    public function getData(){
        $user = Sentinel::getUser();
        $jobs = DB::table('jobs')->where('jb_sc_id',$user->sc_id)->orderBy('jid')->get();

        return DataTables::of($jobs)
            ->addColumn(
                'jpaytypetext',
                function ($user) {
                    return $user->jpaytype == 0  ? Lang::get('jobs/title.normal') : Lang::get('jobs/title.high');
                }
            )
            ->addColumn(
                'jstatustext',
                function ($user) {
                    //return $user->jstatus == 0  ? Lang::get('jobs/title.active') : Lang::get('jobs/title.notactive');
                    $ac = $user->jstatus == 0  ? Lang::get('jobs/title.active') : Lang::get('jobs/title.notactive');
                    $text = '<button class="btn btn-success" onclick="changeactive(' ;
                    if($user->jstatus == 1) $text = '<button class="btn btn-warning" onclick="changeactive(' ;
                    $text .= $user->jid . ',' . $user->jstatus .  ' );return false;">' . $ac . '</button>';
                    return $text;
                }
            )
            ->rawColumns(['jstatustext'])
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
            'jb_sc_id'  => $user->sc_id,            
            'company'   => $req->company,
            'nr'        => $req->jobnr,
            'padmin'    => $req->admin,
            'dateopen'  => $req->dateopen,
            'dateopenm'  => Carbon::parse($req->dateopen)->format('Y-m-d'),
            'description' => $req->description,
            'jpaytype'  => $req->payrate,
            'jstatus'   => $req->status
        );
        $res = DB::table('jobs')->insertGetId($data);
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
        $jid = $req->jid;
        $data = array(
            'jb_sc_id'  => $user->sc_id,            
            'company'   => $req->company,
            'nr'        => $req->jobnr,
            'padmin'    => $req->admin,
            'dateopen'  => $req->dateopen,
            'dateopenm'  => Carbon::parse($req->dateopen)->format('Y-m-d'),
            'description' => $req->description,
            'jpaytype'  => $req->payrate,
            'jstatus'   => $req->status
        );
        $res = DB::table('jobs')->where('jid',$jid)->update($data);
        if($res) return $jid;
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
        $jid = $req->jid;
        $res = DB::table('jobs')->where('jid',$jid)->where('jb_sc_id',$user->sc_id)->delete();
        if($res) return 1;
        else return 0;
    }

    public function changeactive(Request $req){
        $jid = $req->jid;
        $status = $req->status;
        $res = DB::table('jobs')->where('jid',$jid)->update(['jstatus' => $status]);
        if($res) return 1;
        else return 0;
    }

}
