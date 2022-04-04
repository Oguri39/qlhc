<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tables;



use App\Charts\Chartjs;
use App\Charts\Frappe;
use App\Charts\Highcharts;
use App\Models\Datatable;



class indexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $abc = []; $i = 0;
        $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                    ->select('*')
                    ->whereDate('ngay_cap_nhap','<',date("Y-m-d"))
                    ->whereDate('ngay_cap_nhap','>',date("Y-m-1"))
                    ->get();
        foreach($nhatKy as $nk) {
            $abc[$i] = $nk->so_luong_su_dung_trong_ngay ;
            $i++;
        }
        $line = new Frappe;
        $line->labels(['One', 'Two', 'Three','abc']);
        $line->dataset('Element 1', 'line', [5, 30, 100,67])->options(
            [
            'color' => '#418bca'
            ]
        );


        // $danhSachHoaChat =[];$danhSachTonKho=[];
        // $danhSachNhapKho = []; $i = 0;$tong =0 ;
        // $month = date("m");
        // $month--;
        // $tongSoLuong = 0 ;$slh = 0;
        // $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
        //                     ->select('*')
        //                     ->get();
        // foreach($danhMucHoaChat as $dmhc) {
        //     $loHoaChat = DB::table('lo_hoa_chat')
        //                     ->select('*')
        //                     ->where('ma_anh_muc_hoa_chat',$dmhc->idDanh_muc_hoa_chat)
        //                     ->get();
        //     foreach($loHoaChat as $lhc) {
        //         $suDungHoaChat = DB::table('su_dung_hoa_chat')
        //                             ->select('*')
        //                             ->where('ma_loo_hoa_chat',$lhc->idLo_hoa_chat)
        //                             ->get();
        //         foreach($suDungHoaChat as $sdhc) {
        //             $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
        //                         ->select('*')
        //                         ->where('ma_su_dung_hoa_chat',$sdhc->idSu_dung_hoa_chat)
        //                         ->whereDate('ngay_cap_nhap','<',date("Y-m"."-1"))
        //                         ->whereDate('ngay_cap_nhap','>=',date('Y-'.$month.'-1'))
        //                         ->get();
        //             foreach($nhatKy as $nk) {
        //                 $tong += $nk->so_luong_su_dung_trong_ngay ;
        //             }
        //             $tongSoLuong += $sdhc->so_luong_hoa_chat_nhap_ve ;
        //             $slh += $sdhc->huy_bo;
        //         }
        //         $suDungTrongThang = (int)($tong/$lhc->so_luong_dong_goi);
        //         $soLuongTonThangTruoc = $tongSoLuong - $suDungTrongThang ;
        //         $tonkho = $lhc->so_luong - $tongSoLuong ;
        //         $danhSachNhapKho[$i] = array('hoachat'=>$dmhc,'lo'=>$lhc);
        //         $lohoac = DB::table('lo_hoa_chat')
        //                     ->select('*')
        //                     ->whereDate('ngay_nhap','<',date("Y-m-d"))
        //                     ->whereDate('ngay_nhap','>=',date('Y-m-1'))
        //                     ->get();
        //         foreach($lohoac as $lhchat) {
        //             $nhaptrongthang = $lhchat->so_luong;
        //         }
        //         $danhSachHoaChat[$i] = array('danhmuc'=>$dmhc,'lo'=>$lhc,'tondauthangt'=>$soLuongTonThangTruoc,
        //                               'nhaptrongthang'=>$nhaptrongthang, 'sdtrongthangt'=>$suDungTrongThang,
        //                               'tonthangtruoc'=>$soLuongTonThangTruoc,'tonkho'=>$tonkho,'huybo'=>$slh);//json_encode($CB,JSON_UNESCAPED_UNICODE));
        //                               $i++;
        //                               $tong = 0; $tongSoLuong=0;
        //         //danh sách hóa chất
        //         $soLuongSuDung = 0 ; $tongsl = 0 ; $congtycu =""; $j = 0 ;
        //         $suDungHC = DB::table('Su_dung_hoa_chat')
        //                             ->select('*')
        //                             ->where('idLo_hoa_chat',$lhc->idLo_hoa_chat)
        //                             ->get();
        //         foreach($suDungHC as $sdhc) {
        //             $soLuongSuDung += $sdhc->so_luong_hoa_chat_nhap_ve ;
        //         }
        //         $tongsl += $lhc->so_luong ;
        //         $congTy = DB::table('cong_ty_cung_ung')
        //                     ->select('*')
        //                     ->where('ma_ong_ty_cung_ung',$lhc->idCong_ty_cung_ung)
        //                     ->get();
        //         foreach($congTy as $ct) {
        //             $congtycu = $congtycu." ".$ct->ten_cong_ty_cung_ung." ".$ct->noi_san_xuat ;
        //         }
        //     }
        //     $tonkho = $tongsl - $soLuongSuDung ;
        //     $danhSachTonKho[$j] = array('danhmuc'=>$dmhc,'congty'=>$congtycu, 'tonkho'=>$tonkho);
        //     $j++;
        // }
        // // $soLuongSuDung = 0 ; $tongsl = 0 ; $congtycu =""; $i = 0 ;
        // // $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
        // //                     ->select('*')
        // //                     ->get();

        // // foreach($danhMucHoaChat as $dmhc) {
        // //     $loHoaChat = DB::table('Lo_hoa_chat')
        // //                     ->select('*')
        // //                     //->where('idDanh_muc_su_dung_hoa_chat')
        // //                     ->get();
        // //     foreach($loHoaChat as $lhc) {
        // //         $suDungHoaChat = DB::table('Su_dung_hoa_chat')
        // //                             ->select('*')
        // //                             ->where('idLo_hoa_chat',$lhc->idLo_hoa_chat)
        // //                             ->get();
        // //         foreach($suDungHoaChat as $sdhc) {
        // //             $soLuongSuDung += $sdhc->so_luong_hoa_chat_nhap_ve ;
        // //         }
        // //         $tongsl += $lhc->so_luong ;
        // //         $congTy = DB::table('cong_ty_cung_ung')
        // //                     ->select('*')
        // //                     ->where('idCong_ty_cung_ung',$lhc->idCong_ty_cung_ung)
        // //                     ->get();
        // //         foreach($congTy as $ct) {
        // //             $congtycu = $congtycu." ".$ct->ten_cong_ty_cung_ung." ".$ct->noi_san_xuat ;
        // //         }
        // //     }
        //     // $tonkho = $tongsl - $soLuongSuDung ;
        //     // $danhsach2[$i] = array('danhmuc'=>$dmhc,'congty'=>$congtycu, 'tonkho'=>$tonkho);
        //     // $i++;
        // //}


        //     //danh sách dự trù
        //     $tonKho = 0 ;
        // $danhSachDuTru = []; $i = 0;$tongSl = 0 ; $tongSlSD = 0 ;
        // $tonghcnv = 0 ; $soLuong = 0 ;
        // $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
        //                     ->select('*')
        //                     ->get();
        // foreach($danhMucHoaChat as $dmhc) {
        //     $tonKho = 0;
        //     $loHoaChat = DB::table('Lo_hoa_chat')
        //                     ->select('*')
        //                     ->where('idDanh_muc_hoa_chat',$dmhc->idDanh_muc_hoa_chat)
        //                     ->get();
        //     foreach($loHoaChat as $lhc) {
        //         $suDungHoaChat = DB::table('Su_dung_hoa_chat')
        //                             ->select('*')
        //                             ->where('idLo_hoa_chat',$lhc->idLo_hoa_chat)
        //                             ->get();
        //         foreach($suDungHoaChat as $sdhc) {
        //             $tonKho += $sdhc->so_luong_su_dung;
        //             $month = date("m");
        //             $month--;
        //             $nhatKy = DB::table('Nhat_ky_su_dung_hoa_chat')
        //                         ->select('*')
        //                         ->where('idSu_dung_hoa_chat',$sdhc->idSu_dung_hoa_chat)
        //                         ->whereDate('ngay_cap_nhap','<',date("Y-m"."-1"))
        //                         ->whereDate('ngay_cap_nhap','>=',date('Y-'.$month.'-1'))
        //                         ->get();
        //             foreach($nhatKy as $nk) {
        //                 $tongSl += $nk->so_luong_su_dung_trong_ngay;
        //             }
        //             $tonghcnv += $sdhc->so_luong_hoa_chat_nhap_ve ;
        //         }
        //         $tongSl = (int)($tongSl/$lhc->so_luong_dong_goi);
        //         $soLuong += $lhc->so_luong ;
        //         $tongSlSD +=$tongSl;
        //         $soLuongTon = $tonghcnv - $tongSl ;
        //         $tongSl = 0 ;
        //     }
        //     $tonKho = $soLuong - $tonKho ;
        //     // $month = date("m");
        //     //         $month--;
        //     $nhapTrongThang = 0 ;
        //     $loHoaChat = DB::table('Lo_hoa_chat')// trong tháng
        //                     ->select('*')
        //                     ->where('idDanh_muc_hoa_chat',$dmhc->idDanh_muc_hoa_chat)
        //                     ->whereDate('ngay_nhap','<',date("Y-m-d"))
        //                     ->whereDate('ngay_nhap','>=',date('Y-m-1'))
        //                     ->get();
        //     foreach($loHoaChat as $lhc) {
        //         $nhapTrongThang += $lhc->so_luong ;
        //     }
        //     $danhSachDuTru[$i] = array('stt'=>$i+1,'hoachat'=>$dmhc , 'tondauthang'=>$soLuongTon ,'nhaptrongthang'=>$nhapTrongThang, 'sudungtrongtt'=>$tongSlSD,'tonkho'=>$tonKho);
        //     $i++;
        //     $tonKho = 0 ;
        //     $tongSl = 0 ; $tongSlSD = 0 ;
        //     $tonghcnv = 0 ; $soLuong = 0 ;
        // }
        $j = 0 ;
        $danhMucCanhBao = [];
       // $danhMucCanhBao[0] = array('tinhtrang'=>null, 'hoachat'=>null, 'lo'=>null,'phong'=>null);
        $canhBao = DB::table('canh_bao')
                    ->whereDate('thoi_gian_canh_bao','<',date("Y-m-d"))
                    ->whereDate('thoi_gian_canh_bao','>=',date('Y-m-1'))
                    ->get();
        foreach($canhBao as $cb) {
            $suDungHoaChat = DB::table('Su_dung_hoa_chat')
                                ->where('idSu_dung_hoa_chat',$cb->idSu_dung_hoa_chat)
                                ->get();
            foreach($suDungHoaChat as $sdhc) {
                $phong = DB::table('Phong')
                            ->select('*')
                            ->where('idPhong',$sdhc->idPhong)
                            ->get();
                foreach($phong as $p){
                    $lhc = DB::table('Lo_hoa_chat')
                                ->select('*')
                                ->where('idLo_hoa_chat',$sdhc->idLo_hoa_chat)
                                ->get();
                    foreach($lhc as $l) {
                        $dmuc = DB::table('Danh_muc_hoa_chat')
                                    ->select('*')
                                    ->where('idDanh_muc_hoa_chat',$l->idDanh_muc_hoa_chat)
                                    ->get();
                        foreach($dmuc as $dm) {
                            $danhMucCanhBao[$j] = array('tinhtrang'=>$cb, 'hoachat'=>$dm, 'lo'=>$l,'phong'=>$p);
                            $j++;
                        }
                    }
                }
            }
        }
        //dd($danhMucCanhBao);


        // //phan hoa chat


        // $dshc = [];
        // $i = 0;
        // $hoachat = DB::table('Danh_muc_hoa_chat')
        // ->join('Lo_hoa_chat','Danh_muc_hoa_chat.idDanh_muc_hoa_chat','=','Lo_hoa_chat.idDanh_muc_hoa_chat')
        // ->select('*')
        // ->get()->toArray();
        // foreach($hoachat as $lhc){
        //     $tong = 0 ;
        //     $conlai = 0 ;
        //     $suDungHoaChat = DB::table('Su_dung_hoa_chat')
        //         ->select('*')
        //         ->where('idLo_hoa_chat',$lhc->idLo_hoa_chat)
        //         ->get();

        //     foreach($suDungHoaChat as $sdhc) {
        //         $tong += $sdhc->so_luong_hoa_chat_nhap_ve;
        //     }
        //     $conlai = ($lhc->so_luong) - $tong ;
        //     if($conlai > 0){
        //          $dshc[$i] = array('hoaChat'=>$lhc,'soLuongConLai'=>$conlai);
        //          $i++;
        //     }
        //     $conlai = 0 ;
           
        // }
        // $Phong = DB::table('Phong')
        //             ->select('*')
        //             ->get()->toArray();
        // //dd($Phong, $dshc);
        $danhSachHoaChat =['manhhung'];$i=0;
        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                            ->select('*')
                            ->get();
        foreach($danhMucHoaChat as $hoaChat) {
            $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_danh_muc_hoa_chat',$hoaChat->ma_danh_muc_hoa_chat)
                            ->get();
            foreach($loHoaChat as $lo) {
                $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('ma_lo_hoa_chat',$lo->ma_lo_hoa_chat)
                                    ->where('so_luong_su_dung','=',0)
                                    ->get();
                foreach($suDungHoaChat as $suDung) {
                    $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                                ->select('*')
                                ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                                ->where('so_luong_su_dung_trong_ngay','=',0)
                                ->get();
                    foreach($nhatKy as $nk) {
                        $congTy = DB::table('cong_ty_cung_ung')
                                    ->select('*')
                                    ->where('ma_cong_ty_cung_ung',$lo->ma_cong_ty_cung_ung)
                                    ->get();
                        foreach($congTy as $ct){
                            $danhSachHoaChat[$i] = array('stt'=>$i+1,'hoaChat'=>$hoaChat,'lo'=>$lo,'congTy'=>$ct);
                            $i++;
                        }
                    }
                }
            }
        }
       return view('admin.QuanKho.trang_chu',['danhMucCanhBao'=>$danhMucCanhBao,'line'=>$line],['danhSach1'=>$danhSachHoaChat]);//,'danhSachHoaChat'=>$danhSachHoaChat,'danhSachNhapKho'=>$danhSachNhapKho,'danhsachtonkho'=>$danhSachTonKho,'danhsachdutru'=>$danhSachDuTru, 'canhbao'=>$danhMucCanhBao],['dsahc'=>$dshc,'dsp'=>$Phong]);
        // //dd($danhsach2 , $danhsach);
        // // return view('admin.')

        
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
