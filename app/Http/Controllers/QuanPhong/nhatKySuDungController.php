<?php

namespace App\Http\Controllers\Quanphong;

use App\Http\Controllers\Controller;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class nhatKySuDungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $danhSach = []; $i = 0;
        $userId = Sentinel::getUser()->idPhong ;
        $suDungHoaChat = DB::table('su_dung_hoa_chat')
                            ->select('*')
                            ->where('ma_phong',$userId)
                            ->get();
        foreach($suDungHoaChat as $sdhc) {
            $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                            ->get();
            foreach($loHoaChat as $lhc) {
                $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                    ->select('*')
                                    ->where('ma_danh_muc_hoa_chat',$lhc->ma_danh_muc_hoa_chat)
                                    ->get();
                foreach($danhMucHoaChat as $dmhc) {
                    $nhatky = DB::table('nhat_ky_su_dung_hoa_chat')
                                ->select('*')
                                ->where('ma_su_dung_hoa_chat',$sdhc->ma_su_dung_hoa_chat)
                                ->get();
                    foreach($nhatky as $nk) {
                        $danhSach[$i] = array("danhmuchoachat"=>$dmhc,"lo"=>$lhc,"sudung"=>$sdhc,'nhatKy'=>$nk);
                    $i++;
                    }
                    
                }
            }
        }
        //dd($danhSach);
        return view('admin.Quanphong.2_nhatky_sd',['danhsach'=>$danhSach]);
    
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
        $danhSach = [];$i = 0;
        $userId = Sentinel::getUser()->idPhong ;
        $nhatky = DB::table('nhat_ky_su_dung')
                    ->select('*')
                    ->where('ngat_cap_nhat',$id)
                    ->get();
        foreach($nhatky as $nk){
            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                            ->select('*')
                            ->where('ma_su_dung_hoa_chat',$nk->ma_su_dung_hoa_chat)
                            ->where('ma_phong',$userId)
                            ->get();
            foreach($suDungHoaChat as $sdhc) {
                $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                            ->get();
                foreach($loHoaChat as $lhc) {
                    $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                    ->select('*')
                                    ->where('ma_danh_muc_hoa_chat')
                                    ->get();
                    foreach($danhMucHoaChat as $dmhc) {

                        $danhSach[$i] = array("danhmuchoachat"=>$dmhc,"lo"=>$lhc,"sudung"=>$sdhc);
                        $i++;
                    }
                }
            }
        }
        return view('admin.Quankho.2_nhatky_sd',['danhsach'=>$danhSach]);
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
