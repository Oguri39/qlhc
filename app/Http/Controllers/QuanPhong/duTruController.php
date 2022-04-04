<?php

namespace App\Http\Controllers\QuanPhong;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sentinel;

class duTruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //$userId = Sentinel::getUser()->idPhong;
        foreach($request->check as $req) {
            $baoCao = DB::table('bao_cao')
                        ->where('ma_su_dung_hoa_chat',$req)
                        ->where('ten_bao_cao','=','phòng dự trù')
                        ->where('ngay_bao_cao','<',date("y-m"))
                        ->get()->toArray();
            if($baoCao) {
                try{
                    DB::table('bao_cao')
                        ->where('ma_su_dung_hoa_chat',$req)
                        ->update([
                            'so_luong_du_tru'=>$request->list[$req]
                        ]);
                }
                catch(Exception $e) {
                    dd($e);
                    return redirect()->back()->with('không thành công');
                }
            }
            else {
                try{
                    DB::table('bao_cao')
                        ->insert([
                            'ma_su_dung_hoa_chat'=>$req,
                            'ten_bao_cao'=> 'phòng dự trù',
                            'so_luong_du_tru'=>$request->list[$req],
                            'ngay_bao_cao'=>date("y-m-d-h-i-s")
                        ]);
                }
                catch(Exception $e) {
                    return redirect()->back()->with('error',$e);
                }
            }

        }
        return redirect()->back()->with('success','thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
