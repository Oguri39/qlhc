<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use App\Models\tables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Complex\sec;

class danhSachNhapKhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $danhsach =[];$i = 0;$tong =0 ;
        // $month = date("m");
        // $month--;
        // $tongSoLuong = 0 ; $CB = "";
        // $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
        //                     ->select('*')
        //                     ->get();
        // foreach($danhMucHoaChat as $dmhc) {
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
        //             $nhatKy = DB::table('Nhat_ky_su_dung_hoa_chat')
        //                         ->select('*')
        //                         ->where('idSu_dung_hoa_chat',$sdhc->idSu_dung_hoa_chat)
        //                         ->whereDate('ngay_cap_nhap','<',date("Y-m"."-1"))
        //                         ->whereDate('ngay_cap_nhap','>=',date('Y-'.$month.'-1'))
        //                         ->get();
        //             foreach($nhatKy as $nk) {
        //                 $tong += $nk->so_luong_su_dung_trong_ngay ;
        //             }
        //             $canhBao = DB::table('Canh_bao')
        //                             ->select()
        //                             ->where('idSu_dung_hoa_chat',$sdhc->idSu_dung_hoa_chat)
        //                             ->get();
        //             foreach($canhBao as $cb) {
        //                 $CB = $cb->noi_dung_canh_bao;
        //             }
        //             $tongSoLuong += $sdhc->so_luong_hoa_chat_nhap_ve ;
        //         }
        //         $suDungTrongThang = (int)($tong/$lhc->so_luong_dong_goi);
        //         $soLuongTonThangTruoc = $tongSoLuong - $suDungTrongThang ;
        //         $tonkho = $lhc->so_luong - $tongSoLuong ;

        //         $lohoac = DB::table('Lo_hoa_chat')
        //                     ->select('*')
        //                     ->whereDate('ngay_nhap','<',date("Y-m-d"))
        //                     ->whereDate('ngay_nhap','>=',date('Y-m-1'))
        //                     ->get();
        //         foreach($lohoac as $lhchat) {
        //             $nhaptrongthang = $lhchat->so_luong;
        //         }
        //         $danhsach[$i] = array('danhmuc'=>$dmhc,'lo'=>$lhc,'tondauthangt'=>$soLuongTonThangTruoc,
        //                               'nhaptrongthang'=>$nhaptrongthang, 'sdtrongthangt'=>$suDungTrongThang,
        //                               'tonthangtruoc'=>$soLuongTonThangTruoc,'tonkho'=>$tonkho,'canhbao'=>$CB);//json_encode($CB,JSON_UNESCAPED_UNICODE));
        //                               $i++;
        //                               $tong = 0; $tongSoLuong=0; $CB ="";
        //     }
        // }
        // //dd($danhsach);
        // return view('admin.1_ds_hc', ['danhsach'=>$danhsach]);

        //
        // $danhsach = new tables();
        // $danhsach = $danhsach->nhapKho();
     
        // return view('admin.Quankho.danhSachNhapKho',['nhapKho'=>$danhsach]);
        //

       // return view('') // đợi view
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
    public function show()
    {
        //
        // $danhSachHoaChat =[];$i=0;
        // $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
        //                     ->select('*')
        //                     ->get();
        // foreach($danhMucHoaChat as $hoaChat) {
        //     $loHoaChat = DB::table('lo_hoa_chat')
        //                     ->select('*')
        //                     ->where('ma_danh_muc_hoa_chat',$hoaChat->ma_danh_muc_hoa_chat)
        //                     ->get();
        //     foreach($loHoaChat as $lo) {
        //         $suDungHoaChat = DB::table('su_dung_hoa_chat')
        //                             ->select('*')
        //                             ->where('ma_lo_hoa_chat',$lo->ma_lo_hoa_chat)
        //                             ->where('so_luong_su_dung','=',0)
        //                             ->get();
        //         foreach($suDungHoaChat as $suDung) {
        //             $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
        //                         ->select('*')
        //                         ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
        //                         ->where('so_luong_su_dung_trong_ngay','=',0)
        //                         ->get();
        //             foreach($nhatKy as $nk) {
        //                 $congTy = DB::table('cong_ty_cung_ung')
        //                             ->select('*')
        //                             ->where('ma_cong_ty_cung_ung',$lo->ma_cong_ty_cung_ung)
        //                             ->get();
        //                 foreach($congTy as $ct){
        //                     $danhSachHoaChat[$i] = array('stt'=>$i+1,'hoaChat'=>$hoaChat,'lo'=>$lo,'congTy'=>$ct);
        //                     $i++;
        //                 }
        //             }
        //         }
        //     }
        // }
        // return view('admin.Quankho.index.suaxoa',['danhSach'=>$danhSachHoaChat]);


        $danhSach = new tables();
        $danhSach = $danhSach->nhapKhoChuaSD();
        return view('admin.1_suaxoa_hc',['danhSach'=>$danhSach]);
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
        $loHoaChat = DB::table('lo_hoa_chat')
                        ->select('*')
                        ->where('ma_lo_hoa_chat',$id)
                        ->get()->toArray();
        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                            ->select('*')
                            ->where('ma_danh_muc_hoa_chat',$loHoaChat[0]->ma_danh_muc_hoa_chat)
                            ->get()->toArray();
        $congTy = DB::table('cong_ty_cung_ung')
                    ->select('*')
                    ->where('ma_cong_ty_cung_ung',$loHoaChat[0]->ma_cong_ty_cung_ung)
                    ->get()->toArray();

        $danhMucAll = DB::table('danh_muc_hoa_chat')
                        ->select('*')
                        ->get()->toArray();
        $congTyAll = DB::table('cong_ty_cung_ung')
                        ->select('*')
                        ->get()->toArray();
        $phong = DB::table('phong')
                    ->select('*')
                    ->get()->toArray();
        $danhSach = array('hoaChat'=>$danhMucAll,'congTy'=>$congTyAll,'phong'=>$phong,'lo'=>$loHoaChat[0],
        'hoaChatCu'=>$danhMucHoaChat[0],'congTyCu'=>$congTy[0],'loHoaChatCu'=>$loHoaChat[0]); 
        return view('admin.1_edit_suaxoa_hc',['danhSach'=>$danhSach]);
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
       // dd($request->all(),$id);
        $loHoaChat = DB::table('lo_hoa_chat')
                        ->select('ma_lo_hoa_chat')
                        ->where('ma_lo_hoa_chat',$id)
                        ->get()->toArray();
        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                            ->select('*')
                            ->where('ma_danh_muc_hoa_chat',$request->ma_danh_muc_hoa_chat)
                            ->get()->toArray();
        $congTy = DB::table('cong_ty_cung_ung')
                    ->select('ma_cong_ty_cung_ung')
                    ->where('ma_cong_ty_cung_ung',$request->ma_cong_ty_cung_ung)
                    ->get()->toArray();
        if($request->so_luong_tinh <=0 || $request->so_luong_dong_goi<=0) {
            return redirect()->back()->with('error','Không Hợp Lệ') ;
        }
        if($loHoaChat && $danhMucHoaChat && $congTy) {
            try{
                DB::table('lo_hoa_chat')
                ->where('ma_lo_hoa_chat',$id)
                ->update([
                    'so_lo'=>$request->so_lo,
                    'so_luong_tinh'=>$request->so_luong_tinh,
                    'so_luong_dong_goi'=>$request->so_luong_dong_goi,
                    'han_su_dung'=>$request->han_su_dung,
                    'ma_cong_ty_cung_ung'=>$congTy[0]->ma_cong_ty_cung_ung,
                    'ma_danh_muc_hoa_chat'=>$danhMucHoaChat[0]->ma_danh_muc_hoa_chat
                ]);
            }catch(Exception $e) {
                return redirect()->back()->with('error' ,'Không thành công');
            }
            return redirect()->back()->with('success','Thành Công') ;// thông báo thành công
        }else {
            if(!$loHoaChat){
                return redirect()->back()->with('error', 'Lô Hóa Chất Không Tồn Tại') ; // lô hóa chất không tồn tại
            }
            if(!$danhMucHoaChat) {
                return redirect()->back()->with('error', 'Hóa Chất Không hợp lệ');
            }
            if(!$congTy) {
                return redirect()->back()->with('error', 'Công Ty không hợp lệ');
            }
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
        $loHoaChat = DB::table('lo_hoa_chat')
                        ->select('ma_lo_hoa_chat')
                        ->where('ma_lo_hoa_chat',$id)
                        ->get()->toArray();
        if($loHoaChat) {
            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->select('ma_su_dung_hoa_chat')
                                ->where('ma_lo_hoa_chat',$id)
                                ->get()->toArray();
            if(!$suDungHoaChat) {
                try{
                    DB::table('lo_hoa_chat')
                        ->where('ma_lo_hoa_chat',$id)
                        ->delete();
                }
                catch(Exception $e) {
                    return redirect()->back()->with('error','không thành công');// xóa chưa thành công
                }
            }
            else{
                return redirect()->back()->with('error', 'Hóa Chất Đã Phân Về Kho');//  hóa chất đã phân về kho
            }
        }
        else{
            return  redirect()->back()->with('error', 'Lô Hóa Chất Không Tồn Tại') ;// lô hóa chất không tồn tại
        }
        return  redirect()->back()->with('success', 'Thành Công');
    }
}
