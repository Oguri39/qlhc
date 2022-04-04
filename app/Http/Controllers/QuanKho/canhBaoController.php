<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class canhBaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $danhMucCanhBao = []; $j = 0;
        $canhBao = DB::table('canh_bao')
                    ->whereDate('thoi_gian_canh_bao','<',date("Y-m-d"))
                    ->whereDate('thoi_gian_canh_bao','>=',date('Y-m-1'))
                    ->get();
        foreach($canhBao as $cb) {
            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->where('ma_su_dung_hoa_chat',$cb->ma_su_dung_hoa_chat)
                                ->get();
            foreach($suDungHoaChat as $sdhc) {
                $phong = DB::table('phong')
                            ->select('*')
                            ->where('ma_phong',$sdhc->ma_phong)
                            ->get();
                foreach($phong as $p){
                    $lhc = DB::table('lo_hoa_chat')
                                ->select('*')
                                ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                                ->get();
                    foreach($lhc as $l) {
                        $dmuc = DB::table('danh_muc_hoa_chat')
                                    ->select('*')
                                    ->where('ma_danh_muc_hoa_chat',$l->ma_danh_muc_hoa_chat)
                                    ->get();
                        foreach($dmuc as $dm) {
                            $danhMucCanhBao[$j] = array('tinhtrang'=>$cb, 'hoachat'=>$dm, 'lo'=>$l,'phong'=>$p);
                            $j++;
                        }
                    }
                }
            }
        }
        return view();
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
