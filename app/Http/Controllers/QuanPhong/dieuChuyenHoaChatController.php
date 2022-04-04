<?php

namespace App\Http\Controllers\Quanphong;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sentinel ;

class dieuChuyenHoaChatController extends Controller
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
                    $danhSach[$i] = array("hoachat"=>$dmhc,"lo"=>$lhc,"sudung"=>$sdhc);
                    $i++;
                }
            }
        }
        $i = 0;$dsPhong = [];
        $phong =DB::table('phong')
        ->select('*')
        ->get();
        foreach($phong as $p) {
            $dsPhong[$i] = array('phong'=>$p);
            $i++;
        }
        return view('admin.Quanphong.2_dieuchuyen_hc',['danhSach'=>$danhSach,'dsPhong'=>$dsPhong]);
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
        //dd($request);
        $userId = Sentinel::getUser()->idPhong ;
        if($request->so_luong>0){

            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                ->select('so_luong_hoa_chat_nhap_ve')
                ->where('ma_lo_hoa_chat',$request->ma_lo_hoa_chat)
                ->where('ma_phong',$userId)
                ->get()->toArray();
                $soluong=0;
                $soluong = $suDungHoaChat[0]->so_luong_hoa_chat_nhap_ve - $request->so_luong;
                if($soluong > 0&&$suDungHoaChat) {
                    try{
                DB::table('su_dung_hoa_chat')
                    ->where('ma_lo_hoa_chat',$request->ma_lo_hoa_chat)
                    ->where('ma_phong',$userId)
                    ->update([
                        'so_luong_hoa_chat_nhap_ve'=>$soluong
                    ]);
                }catch(Exception $e) {
                    return redirect()->back()->with('error',$e);
                }
                $suDungHoaChat1 = DB::table(('su_dung_hoa_chat'))
                ->select('*')
                ->where('ma_lo_hoa_chat',$request->ma_lo_hoa_chat)
                ->where('ma_phong',$request->ma_phong)
                ->get()->toArray();
                $soluong=0 ;
                foreach($suDungHoaChat1 as $sd){
                    $soluong = $sd->so_luong_hoa_chat_nhap_ve;
                }
                if($suDungHoaChat1){
                    try{
                        DB::table('su_dung_hoa_chat')
                        ->where('ma_lo_hoa_chat',$request->ma_lo_hoa_chat)
                        ->where('ma_phong',$request->ma_phong)
                        ->update([
                        'so_luong_hoa_chat_nhap_ve'=>$soluong + $request->so_luong
                        ]);
                    }catch(Exception $e) {
                        return redirect()->back()->with('error',$e);
                    }
                    
                }else {
                    try{
                        DB::table('su_dung_hoa_chat')
                        ->insert([
                            'ma_lo_hoa_chat'=>$request->ma_lo_hoa_chat,
                            'ma_phong'=>$request->ma_phong,
                            'so_luong_hoa_chat_nhap_ve'=>$request->so_luong,
                            'so_luong_su_dung'=>0,
                            'ten_thiet_bi_su_dung'=> 'chưa có',
                            'huy_bo'=>0
                        ]);
                    }catch(Exception $e) {
                        return redirect()->back()->with('error',$e);
                    }
                    
                }

                }else{
                    return redirect()->back()->with('error','hóa chất không hợp lệ');
                }
                
            
        }
        else {
            return redirect()->back()->with('error', 'Số lượng không hợp lệ');
        }
        
        return redirect()->back()->with('success', 'Thành công');

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