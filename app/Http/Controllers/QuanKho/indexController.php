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


        
        $j = 0 ;
        $danhMucCanhBao = [];
        $canhBao = DB::table('canh_bao')
                    ->select('ma_su_dung_hoa_chat','loai_canh_bao')
                    ->whereDate('thoi_gian_canh_bao','<=',date("Y-m-d"))
                    ->whereDate('thoi_gian_canh_bao','>=',date('Y-m-1'))
                    ->get();
        foreach($canhBao as $cb) {
            $phong = DB::table('phong')
                            ->select('ten_phong','ma_phong')
                            ->get();
            foreach($phong as $p){
                $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->where('ma_su_dung_hoa_chat',$cb->ma_su_dung_hoa_chat)
                                ->where('ma_phong',$p->ma_phong)
                                ->get();
                foreach($suDungHoaChat as $sdhc) {
               
                    $lhc = DB::table('lo_hoa_chat')
                                ->select('so_lo','ma_danh_muc_hoa_chat','so_luong_tinh','han_su_dung')
                                ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                                ->get();
                    foreach($lhc as $l) {
                        $suDung = DB::table('su_dung_hoa_chat')
                                    ->select('so_luong_hoa_chat_nhap_ve',
                                    'so_luong_su_dung')
                                    ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                                    ->get();
                        $soluong = 0;
                        foreach($suDung as $sd) {
                            $soluong += $sd->so_luong_su_dung ;
                        }
                        $soluong =$l->so_luong_tinh - $soluong; 
                        $dmuc = DB::table('danh_muc_hoa_chat')
                                    ->select('ten_hoa_chat')
                                    ->where('ma_danh_muc_hoa_chat',$l->ma_danh_muc_hoa_chat)
                                    ->get();
                        foreach($dmuc as $dm) {
                            $danhMucCanhBao[$j] = array('tinhtrang'=>$cb,'suDung'=>$sdhc, 'hoachat'=>$dm, 'lo'=>$l,'phong'=>$p,'conLai'=>$soluong);
                            $j++;
                        }
                    }
                }
            }
        }
        $dshc = [];
        $i = 0;
        $hoachat = DB::table('danh_muc_hoa_chat')
                        ->join('lo_hoa_chat','danh_muc_hoa_chat.ma_danh_muc_hoa_chat','=','lo_hoa_chat.ma_danh_muc_hoa_chat')
                        ->select('*')
                        ->get();
        foreach($hoachat as $lhc){
            $tong = 0 ;
            $conlai = 0 ;
            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                ->select('*')
                ->where('ma_lo_hoa_chat',$lhc->ma_lo_hoa_chat)
                ->get();

            foreach($suDungHoaChat as $sdhc) {
                $tong += $sdhc->so_luong_hoa_chat_nhap_ve;
            }
            $conlai = $lhc->so_luong_tinh - $tong ;
            if($conlai > 0){
                 $dshc[$i] = array('hoaChat'=>$lhc,'soLuongConLai'=>$conlai);
                 $i++;
            }
            $conlai = 0 ;
           
        }
        // if(count($line->datasets)==0){ $line->dataset('chưa có hoắ chất', 'line', [0])->options(
        //     [
        //         'color' => '#418bca'.$j
        //     ]
        //     );}
        $phong = DB::table('phong')
                    ->select('*')
                    ->get()->toArray();
                    $danhSach = new tables();
                    $danhSachTonKho = $danhSach->tonKho();
                    $danhSachHoaChat = $danhSach->hoaChatSuDung();
                    $danhSachDuTru = $danhSach->duTruHoaChat();
                    $nhapKho = $danhSach->nhapKho();
       return view('admin.Quankho.trang_chu',['danhSachTonKho'=>$danhSachTonKho,'danhSachHoaChat'=>$danhSachHoaChat,'danhSachDuTru'=>$danhSachDuTru,'nhapKho'=>$nhapKho,
       'danhMucCanhBao'=>$danhMucCanhBao,'dshc'=>$dshc,'dsp'=>$phong]);

        
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
        $abc = []; $i = 0;$j=0;
        $line = new Frappe;
        $line->labels(['1', '2','3','4','5', '6','7','8','9', '10','11','12','13', '14','15','16',
        '17', '18','19','20','21','22','23','24','25','26','27','28','29','30','31']);
        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                            ->select('*')
                            ->where('ma_danh_muc_hoa_chat',$id)
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
                                    ->get();
                foreach($suDungHoaChat as $suDung) {
                    $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                    ->select('*')
                    ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                    ->whereDate('ngay_cap_nhap','<=',date("Y-m-d"))
                    ->whereDate('ngay_cap_nhap','>',date("Y-m-1"))
                    ->get();
                    foreach($nhatKy as $nk) {
                    $abc[$i] = $nk->so_luong_su_dung_trong_ngay ;
                    $i++;
                    }
                    $line->dataset('Element '.$j, 'line', $abc)->options(
                    [
                        'color' => '#418bca'.$j
                    ]
                    );
                    $abc=[];
                    $j++;
                    $i=0;
                }
            }
        }
        
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