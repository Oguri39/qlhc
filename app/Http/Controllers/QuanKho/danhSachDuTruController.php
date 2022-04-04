<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use App\Models\tables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class danhSachDuTruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $tonKho = 0 ;
        // $danhsach = []; $i = 0;$tongSl = 0 ; $tongSlSD = 0 ;
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
        //     $danhsach[$i] = array('stt'=>$i+1,'hoachat'=>$dmhc , 'tondauthang'=>$soLuongTon ,'nhaptrongthang'=>$nhapTrongThang, 'sudungtrongtt'=>$tongSlSD,'tonkho'=>$tonKho);
        //     $i++;
        //     $tonKho = 0 ;
        //     $tongSl = 0 ; $tongSlSD = 0 ;
        //     $tonghcnv = 0 ; $soLuong = 0 ;
        // }
        // //dd($danhsach);
        // return view('admin.1_ds_dt',['danhsach'=>$danhsach]);

        $danhSach = new tables();
        $danhSach = $danhSach->duTruHoaChat();
        return view('admin.Quankho.danhSachDuTru',['danhSachDuTru'=>$danhSach]);
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
        if(!$request->input('check')){
            return redirect()->back();
        }

        foreach($request->input('check') as $req) {
            //dd($request->list[$req]);
            try{
                 DB::table('danh_muc_hoa_chat')
                ->where('ma_danh_muc_hoa_chat',$req) 
                ->update([
                    'du_tru' => $request->list[$req]
                ]);
            } catch(Exception $e){
                return redirect()->back()->with('error', 'Lỗi ');
            }
           
        }
        
        return redirect()->back()->with('success', 'Thành công ');
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
