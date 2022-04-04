<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class contingencyListController extends Controller
{
    //
    public function index() {
        $number = 0 ; $soluongdadung = 0 ; $danhsachnhapkho = []; $i = 0 ;
        $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
        ->select('*')
        ->get()->toArray();
        foreach($danhMucHoaChat as $dmhc){
            $loHoaChat = DB::table('Lo_hoa_chat')
                ->select('*')
                ->where('idDanh_muc_hoa_chat',$dmhc->idDanh_muc_hoa_chat)
                ->get();
            foreach($loHoaChat as $lhc) {
                $number += $lhc->so_luong ;
                $arrA = DB::table('Su_dung_hoa_chat')
                    ->select('*')
                    ->where('idLo_hoa_chat',$lhc->idLo_hoa_chat)
                    ->get();
                    foreach($arrA as $ar){
                        $soluongdadung += $ar->so_luong_su_dung ;
                    }
                    $congTyCungUng = DB::table('Cong_ty_cung_ung')
                                ->select('*')
                                ->where('idCong_ty_cung_ung',$lhc->idCong_ty_cung_ung)
                                ->get();
                                $cty =[];
                                foreach($congTyCungUng as $ctcu){
                                    $cty = $ctcu;
                                }

                    $number = $number -$soluongdadung;
                    $danhsachnhapkho[$i] = array('danhmuc'=>$dmhc,'lo'=>$lhc,'cungung'=>$cty,'conlai'=>$number);  ;
                    $i++;

            }

            
        }
        // $number = $number -$soluongdadung ;
        // $arrnumber[] = $number;
        // $i-- ;
        // $danhsachnhapkho[$i] = array_merge($danhsachnhapkho[$i],array('conlai'=>$number));
        //dd($danhsachnhapkho);
        dd($danhsachnhapkho);
        return view('admin.dsnk',compact($danhsachnhapkho));
    }
}
