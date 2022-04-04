<?php

namespace App\Models;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tablesQuanPhong extends Model
{
    //
    // báo cáo // tồn đầu tháng trước // sử dụng trong tháng // tồn kho hiện tại 
    public function danhSachSuDungHoaChat() {
        $hoaChatSuDung = []; $i = 0;
        $id =  Sentinel::getUser()->idPhong;
        $tongSL = 0; $tonKho = 0; $tonghcnv = 0;
        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                            ->select('*')
                            ->get();
        foreach($danhMucHoaChat as $danhMuc) {
            $loHoaChat = DB::table('lo_hoa_chat')
                            ->where('ma_danh_muc_hoa_chat',$danhMuc->ma_danh_muc_hoa_chat)
                            ->select('*')
                            ->get();
            foreach($loHoaChat as $lo) {
                $suDungHoaChat =DB::table('su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('ma_lo_hoa_chat',$lo->ma_lo_hoa_chat)
                                    ->where('ma_phong',$id)
                                    ->get();
                foreach($suDungHoaChat as $suDung) {
                    $nhatKySuDung = DB::table('nhat_ky_su_dung_hoa_chat')
                                        ->select('*')
                                        ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                                        ->where('ma_phong',$id)
                                        ->whereDate('ngay_cap_nhap','<',date("Y-m-d"))
                                        ->whereDate('ngay_cap_nhap','>=',date('Y-m-1'))
                                        ->get();
                    foreach($nhatKySuDung as $nhatKy) {
                        $tongSL += $nhatKy->so_luong_su_dung_trong_ngay;
                    }
                    $tonghcnv += $suDung->so_luong_hoa_chat_nhan_ve;

                    $month = date("m");
                    $month--;
                    $baoCao = DB::table('bao_cao')
                            ->select('*')
                            ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                            ->whereDate('ngay_cap_nhap','<',date("Y-m"."-1"))
                            ->whereDate('ngay_cap_nhap','>=',date('Y-'.$month.'-1'))
                            ->get();
                    foreach($baoCao as $bc) {
                        $tonKho += $bc->so_luong_ton_truoc_khi_nhap ;
                    }
                }
                $tongSL = (int)($tongSL/$lo->so_luong_tinh);

                $hoaChatSuDung[$i] = array('hoaChat'=>$danhMuc,'lo'=>$lo,'tonDauThangTruoc'=>$tonKho,'soLuongDungTrongThang'=>$tongSL);
                $i++;
                $tongSL = 0; $tonKho = 0; $tonghcnv = 0; $tonKho = 0;


            }
            
        }
        return $hoaChatSuDung ;  
    }

    public function hoaChat() {
        $hoaChatSuDung = []; $i = 0;
        $id =  Sentinel::getUser()->idPhong;
        $hoaChatSuDung = DB::table('hoa_chat_su_dung')
                            ->select('*')
                            ->where('ma_phong',$id)
                            ->get();
        foreach($hoaChatSuDung as $suDung) {
            $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_lo_hoa_chat',$suDung->ma_lo_hoa_chat)
                            ->get();
            foreach($loHoaChat as $lo) {
                $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                      ->select('*')
                                      ->where('ma_danh_muc_hoa_chat',$lo->ma_danh_muc_hoa_chat)
                                      ->get();
                foreach($danhMucHoaChat as $danhMuc) {
                    $ton = $suDung->so_luong_hoa_chat_nhap_ve - $suDung->so_luong_su_dung;
                    $hoaChatSuDung[$i] = array('hoachat'=>$danhMuc,'lo'=>$lo,'tonKho'=>$ton);
                }
            }
        }
        return $hoaChatSuDung;
    }

}
