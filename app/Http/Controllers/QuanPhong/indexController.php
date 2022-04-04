<?php

namespace App\Http\Controllers\QuanPhong;

use App\Charts\Frappe;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sentinel;

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
        // $userId = Sentinel::getUser()->idPhong ; $j = 0;$abc=[];
        // $line = new Frappe;
        // $line->labels(['1', '2','3','4','5', '6','7','8','9', '10','11','12','13', '14','15','16',
        // '17', '18','19','20','21','22','23','24','25','26','27','28','29','30','31']);

        // $i=0;$abc = [];
        // $suDungHoaChat = DB::table('su_dung_hoa_chat')
        //                     ->select('*')
        //                     ->where('ma_phong',$userId)
        //                     ->get();
        // foreach($suDungHoaChat as $sdhc) {
        //         $loHoaChat = DB::table('lo_hoa_chat')
        //                         ->select('*')
        //                         ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
        //                         ->get();
        //         foreach($loHoaChat as $lo) {
        //             $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
        //                         ->select('*')
        //                         ->where('ma_danh_muc_hoa_chat',$lo->ma_danh_muc_hoa_chat)
        //                         ->get();
        //             foreach($danhMucHoaChat as $danhMuc) {
        //             $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
        //                         ->select('*')
        //                         ->where('ma_su_dung_hoa_chat',$sdhc->ma_su_dung_hoa_chat)
        //                         ->whereDate('ngay_cap_nhap','<=',date("Y-m-d"))
        //                         ->whereDate('ngay_cap_nhap','>=',date("Y-m-1"))
        //                         ->get();
        //                         for($i=0; $i<date('d');$i++) {
        //                             $abc[$i]=0;
        //                         }
        //             foreach($nhatKy as $nk) {
        //                 $date = date_parse($nk->ngay_cap_nhap);
        //                 $abc[$date['day']] = (int)($nk->so_luong_su_dung_trong_ngay/$lo->so_luong_dong_goi);
        //                 $i++;
        //             }
        //             $line->dataset($danhMuc->ten_hoa_chat, 'line', $abc)->options(
        //                 [
        //                     'color' => '#418bca'.$i
        //                 ]
        //             );
        //             $abc=[]; $i=0;
        //         }
        //     }
        // }


        $userId = Sentinel::getUser()->idPhong ;
        $j = 0 ;
        $danhMucCanhBao = [];
        $canhBao = DB::table('canh_bao')
                    ->whereDate('thoi_gian_canh_bao','<=',date("Y-m-d"))
                    ->whereDate('thoi_gian_canh_bao','>=',date('Y-m-1'))
                    ->get();
        foreach($canhBao as $cb) {
            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->where('ma_phong',$userId)
                                ->where('ma_su_dung_hoa_chat',$cb->ma_su_dung_hoa_chat)
                                ->get();
            foreach($suDungHoaChat as $sdhc) {
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
                            $danhMucCanhBao[$j] = array('tinhtrang'=>$cb, 'hoachat'=>$dm, 'lo'=>$l);
                            $j++;
                        }
                    }
                
            }
        }

        $danhSachHoaChatSuDung = []; $i = 0;
        $hoaChatSuDung = DB::table('su_dung_hoa_chat')
                            ->select('*')
                            ->where('ma_phong',$userId)
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
                foreach($danhMucHoaChat as $hoaChat) {
                    $danhSachHoaChatSuDung[$i] = array('stt'=>$i+1,'maHoaChat'=>$hoaChat->ma_danh_muc_hoa_chat,'tenHoaChat'=>$hoaChat->ten_hoa_chat
                                                        ,'donViTinh'=>$hoaChat->don_vi_tinh,'soLuongTinh'=>$lo->so_luong_tinh, 'donViDongGoi'=>$hoaChat->don_vi_dong_goi,
                                                    'soLuongDongGoi'=>$lo->so_luong_dong_goi,'soLo'=>$lo->so_lo,
                                                    'tonDauThangTruoc'=>0,'soLuongSuDung'=>$suDung->so_luong_su_dung,'maSuDung'=>$suDung->ma_su_dung_hoa_chat,
                                                    'soLuongHong'=>$suDung->huy_bo,'tonKho'=>$suDung->so_luong_hoa_chat_nhap_ve - $suDung->so_luong_su_dung,
                                                    'HanSuDung'=>$lo->han_su_dung);
                    $i++;
                }
            }
        }

        $danhSachHoaChat = []; $i = 0; $tong = 0;

        $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->select('*')
                                ->where('ma_phong',$userId)
                                ->get();
        foreach($suDungHoaChat as $suDung) {
            $tong = $suDung->so_luong_hoa_chat_nhap_ve - $suDung->so_luong_su_dung ;
            $loHoaChat = DB::table('lo_hoa_chat')
                        ->select('*')
                        ->where('ma_lo_hoa_chat',$suDung->ma_lo_hoa_chat)
                        ->get();
            foreach($loHoaChat as $lo) {
            
                $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                ->select('*')
                                ->where('ma_danh_muc_hoa_chat',$lo->ma_danh_muc_hoa_chat)
                                ->get();

                foreach($danhMucHoaChat as $danhMuc){
                    $danhSachHoaChat[$i] = array('stt'=>$i+1,'maHoaChat'=>$danhMuc->ma_danh_muc_hoa_chat,
                                             'tenHoaChat'=>$danhMuc->ten_hoa_chat, 'donViTinh'=>$danhMuc->don_vi_tinh,
                                             'donViDongGoi'=>$danhMuc->don_vi_dong_goi,'hanSuDung'=>$tong);
                    $i++;
                }
            }
        }

        $i = 0 ; $danhsach = [];
        $suDungHoaChat = DB::table('su_dung_hoa_chat')
            ->select('*')
            ->where('ma_phong',$userId)
            ->get();
        foreach($suDungHoaChat as $sdhc) {
            $lo = DB::table('lo_hoa_chat')
                    ->select('*')
                    ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                    ->get();
            foreach($lo as $l) {
                $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                    ->select('*')
                                    ->where('ma_danh_muc_hoa_chat',$l->ma_danh_muc_hoa_chat)
                                    ->get();
                foreach($danhMucHoaChat as $dmhc){
                     $danhsach[$i] = array("stt"=>$i+1,"danhmuchoachat"=>$dmhc,"lo"=>$l,"sudung"=>$sdhc);
                     $i++;
                }
               
            }
        }

        $danhsachhong = []; $i = 0 ; 
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
                    $danhsachhong[$i] = array('stt'=>$i+1,'hoachat'=>$dmhc, 'lo'=>$lhc, 'sdhc'=>$sdhc);
                    $i++;
                }
            }
        }


        //dự trù

        $danhSachDuTru =[];$i=0;

        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                            ->select('*')
                            ->get();
        foreach($danhMucHoaChat as $danhMuc) {
            $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_danh_muc_hoa_chat',$danhMuc->ma_danh_muc_hoa_chat)
                            ->get();
            foreach($loHoaChat as $lo) {
                $cTy = DB::table('cong_ty_cung_ung')
                        ->select('*')
                        ->where('ma_cong_ty_cung_ung',$lo->ma_cong_ty_cung_ung)
                        ->get()->toArray();
                $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('ma_lo_hoa_chat',$lo->ma_lo_hoa_chat)
                                    ->where('ma_phong',$userId)
                                    ->get();
                foreach($suDungHoaChat as $suDung) {
                    $duTru = DB::table('bao_cao')
                                ->select('*')
                                ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                                ->get();
                    $soluong = 0;
                    foreach($duTru as $dt){
                        $soluong = $dt->so_luong_du_tru ;
                    }
                    $danhSachDuTru[$i] = array('stt'=>$i+1,'cty'=>$cTy[0],'hoaChat'=>$danhMuc,'suDung'=>$suDung ,'duTru'=>$soluong);
                    $i++;
                }
            }
        }

        // $danhSachTonKho = [];
        // $suDungHoaChat = DB::table('su_dung_hoa_chat')
        //                     ->select('*')
        //                     ->where('ma_phong',$userId)
        //                     ->get();
        // foreach($suDungHoaChat as $suDung){
            
        // }
        

        // if(count($line->datasets)==0){ $line->dataset('chưa có hoắ chất', 'line', [0])->options(
        //     [
        //         'color' => '#418bca'.$j
        //     ]
        //     );}
        return view('admin.Quanphong.trang_chu_p',['danhMucCanhBao'=>$danhMucCanhBao,'danhSachTonKho'=>$danhSachDuTru,'danhSachDuTru'=>$danhSachDuTru,
                    'suDungHoaChat'=>$danhSachHoaChatSuDung,'danhSachHoaChat'=>$danhSachHoaChat,'danhSach'=>$danhsach],['danhsachhong'=>$danhsachhong]);


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
