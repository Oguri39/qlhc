<?php

namespace App\Http\Controllers\QuanPhong;

use Sentinel ;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class danhSachLoiHongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $danhsach = []; $i = 0 ; 
        $userId = Sentinel::getUser()->idPhong;
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
                    $danhsach[$i] = array('stt'=>$i+1,'hoachat'=>$dmhc, 'lo'=>$lhc, 'sdhc'=>$sdhc);
                    $i++;
                }
            }
        }

        return view('admin.2_hc_hl',['danhsach'=>$danhsach]);
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
        $userId = Sentinel::getUser()->idPhong;
        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                            ->select('*')
                            ->where('ma_danh_muc_hoa_chat',$request->ma_danh_muc_hoa_chat)
                            ->get();
        foreach($danhMucHoaChat as $hoaChat) {
            $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_danh_muc_hoa_chat',$hoaChat->ma_danh_muc_hoa_chat)
                            ->where('so_lo','=',$request->so_lo)
                            ->get();
            foreach($loHoaChat as $lo) {
                $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('ma_lo_hoa_chat',$lo->ma_lo_hoa_chat)
                                    ->where('ma_phong',$userId)
                                    ->get();
                foreach($suDungHoaChat as $suDung) {
                    try{
                        DB::table('su_dung_hoa_chat')
                        ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                        ->update([
                            'huy_bo'=>$request->so_luong,
                            'so_luong_su_dung'=>$suDung->so_luong_su_dung + $request->so_luong
                        ]);
                    }catch(Exception $e) {
                        return redirect()->back()->with('error','lỗi');
                    }
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
