<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class baoCaoMuonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $danhsach = []; $i = 0; $j = 0;
        $danhSachHoaChatPhong = []; $chuaXacNhan = 0;$danhSachP = [];
        $phong = DB::table('phong')
                        ->select('*')
                        ->get()->toArray();
        foreach($phong as $p) {
            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->select('*')
                                ->where('ma_phong',$p->ma_phong)
                                ->get();

            foreach($suDungHoaChat as $suDung) {
                $loHoaChat = DB::table('lo_hoa_chat')
                                ->select('*')
                                ->where('ma_lo_hoa_chat',$suDung->ma_lo_hoa_chat)
                                ->get();
                foreach($loHoaChat as $lo) {
                    $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                        ->select('*')
                                        ->where('ma_danh_muc_hoa_chat',$lo->ma_danh_muc_hoa_chat)
                                        ->get();
                    foreach($danhMucHoaChat as $hoaChat) {
                        $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                                    ->orWhere(function($query){
                                        $query->where('xac_nhan_cua_quan_kho','=',3)
                                            ->where('xac_nhan_cua_quan_kho','=',0);
                                    })
                                    ->get();
                        foreach($nhatKy as $nk) {
                            $danhsach[$i] = array('stt'=>$i+1,'phong'=>$p, 'hoachat'=>$hoaChat,'lo'=>$lo, 'sudung'=>$suDung, 'nhatky'=>$nk);
                            $i++;
                            if($nk->xac_nhan_cua_quan_kho == 0) {
                                $chuaXacNhan++ ;
                            }
                        }
                    }
                }
            }
            if($j == 0){
                $danhSachHoaChatPhong[$j] = array('phong'=>$p, 'hoaChatPhong'=>$danhsach, 'trangThai'=>$chuaXacNhan, 'abc'=> 'fade active show');
            }
            else {
                $danhSachHoaChatPhong[$j] = array('phong'=>$p, 'hoaChatPhong'=>$danhsach, 'trangThai'=>$chuaXacNhan, 'abc'=> null);
            }
            $danhSachP[$j] = array('phong'=>$p,'thongBao'=>$chuaXacNhan);
            $j++;$i = 0;$danhsach = [];$chuaXacNhan = 0;
        }

        return view('admin.Quankho.1_nhatky_sd',['danhSach'=>$danhSachHoaChatPhong,'phong'=>$danhSachP]);
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
    public function edit()
    {
        //
        $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('xac_nhan_cua_quan_kho','=',0)
                                    ->get();
        foreach($nhatKy as $nk) {
            DB::table('nhat_ky_su_dung_hoa_chat')
                ->where('ma_nhat_ky_su_dung_hoa_chat',$nk->ma_nhat_ky_su_dung_hoa_chat)
                ->update([
                    'xac_nhan_cua_quan_kho'=>1
                ]);
            
           $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->select('*')
                                ->where('ma_su_dung_hoa_chat',$nk->ma_su_dung_hoa_chat)
                                ->get();
            foreach($suDungHoaChat as $suDung){
                $loHoaChat = DB::table('lo_hoa_chat')
                                ->select('*')
                                ->where('ma_lo_hoa_chat',$suDung->ma_lo_hoa_chat)
                                ->get();
                foreach($loHoaChat as $lo) {
                    $abc = (int)($nk->so_luong_su_dung_trong_ngay/$lo->so_luong_dong_goi) + $suDung->so_luong_su_dung;
                }
                DB::table('su_dung_hoa_chat')
                ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                ->update([
                'so_luong_su_dung'=>$abc
                    ]);
            }
            
        
        }
        return redirect('quankho')->with('thành công');
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
        if($request->ma_nhat_ky_su_dung_hoa_chat && $request->ma_phong == $id){
            $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                        ->select('ma_su_dung_hoa_chat','so_luong_su_dung_trong_ngay')
                        ->where('ma_nhat_ky_su_dung_hoa_chat',$request->ma_nhat_ky_su_dung_hoa_chat)
                        ->get()->toArray();
            if($nhatKy){
                $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                    ->select('ma_lo_hoa_chat','ma_su_dung_hoa_chat','so_luong_su_dung')
                                    ->where('ma_phong',$id)
                                    ->where('ma_su_dung_hoa_chat',$nhatKy[0]->ma_su_dung_hoa_chat)
                                    ->get()->toArray();

                $loHoaChat = DB::table('lo_hoa_chat')
                                ->select('so_luong_dong_goi')
                                ->where('ma_lo_hoa_chat',$suDungHoaChat[0]->ma_lo_hoa_chat)
                                ->get()->toArray();

                if($loHoaChat && $suDungHoaChat) {
                    try {
                        DB::table('nhat_ky_su_dung_hoa_chat')
                            ->where('ma_nhat_ky_su_dung_hoa_chat',$request->ma_nhat_ky_su_dung_hoa_chat)
                            ->update([
                                'xac_nhan_cua_quan_kho'=>3
                            ]);
                        
                        DB::table('su_dung_hoa_chat')
                            ->where('ma_su_dung_hoa_chat',$suDungHoaChat[0]->ma_su_dung_hoa_chat)
                            ->update([
                                'so_luong_su_dung'=> $suDungHoaChat[0]->so_luong_su_dung + (int)($nhatKy[0]->so_luong_su_dung_trong_ngay/$loHoaChat[0]->so_luong_dong_goi) 
                            ]);
                    }catch(Exception $e) {
                        return redirect()->back()->with('success','Lỗi');
                    }
                }
                else {
                    return redirect()->back()->with('success','Không hợp lệ');
                }
            }else {
                return redirect()->back()->with('success','Mã Nhật Ký Không Hợp Lệ');
            }
            return redirect()->back()->with('success','Thành Công');
        }else {
            if($request->ma_phong == $id) {
                $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('ma_phong',$id)
                                    ->get();
                foreach($suDungHoaChat as $suDung) {
                    $loHoaChat = DB::table('lo_hoa_chat')
                                    ->select('*')
                                    ->where('ma_lo_hoa_chat',$suDung->ma_lo_hoa_chat)
                                    ->get()->toArray();
                    $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                                ->select('*')
                                ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                                ->where('xac_nhan_cua_quan_kho','=',0)
                                ->get();
                    $loHoaChat = DB::table('lo_hoa_chat')
                                    ->select('*')
                                    ->where('ma_lo_hoa_chat',$suDung->ma_lo_hoa_chat)
                                    ->get()->toArray();
                    foreach($nhatKy as $nk) {
                        try {
                            DB::table('nhat_ky_su_dung_hoa_chat')
                                ->where('ma_nhat_ky_su_dung_hoa_chat',$nk->ma_nhat_ky_su_dung_hoa_chat)
                                ->update([
                                    'xac_nhan_cua_quan_kho'=>3
                                ]);
                            DB::table('su_dung_hoa_chat')
                                ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                                ->update([
                                    'so_luong_su_dung'=>$suDung->so_luong_su_dung + (int)($nk->so_luong_su_dung_trong_ngay/$loHoaChat[0]->so_luong_dong_goi)
                                ]);
                        }catch(Exception $e) {
                            return redirect()->back()->with('success','Lỗi');
                        }
                    }
                }
            }
            else {
                return redirect()->back()->with('success','Phòng Không Hợp Lệ');
            }
            return redirect()->back()->with('success','Thành Công');
        }

                        
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
