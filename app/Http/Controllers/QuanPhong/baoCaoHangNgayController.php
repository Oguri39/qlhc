<?php

namespace App\Http\Controllers\Quanphong;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Sentinel;

class baoCaoHangNgayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $i = 0 ; $danhsach = [];
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $tgiankhoa = mktime(17,00,00,date("m"),date("d"),date("y"));
        //$userId = Auth::id();
        $userId = Sentinel::getUser()->idPhong;
        // $idPhong = DB::table('QuanPhong')
        //             ->select('idPhong')
        //             ->where('id',Sentinel::getUser()->idPhong;)
        //             ->get();
        $suDungHoaChat = DB::table('Su_dung_hoa_chat')
            ->select('*')
            ->where('idPhong',$userId)
            ->get();
        foreach($suDungHoaChat as $sdhc) {
            $lo = DB::table('Lo_hoa_chat')
                    ->select('*')
                    ->where('idLo_hoa_chat',$sdhc->idLo_hoa_chat)
                    ->get();
            foreach($lo as $l) {
                $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
                                    ->select('*')
                                    ->where('idDanh_muc_hoa_chat',$l->idDanh_muc_hoa_chat)
                                    ->get();
                foreach($danhMucHoaChat as $dmhc){
                     $danhsach[$i] = array("stt"=>$i+1,"danhmuchoachat"=>$dmhc,"lo"=>$l,"sudung"=>$sdhc);
                     $i++;
                }
               
            }
        }
        //dd($danhsach);
        return view('admin.2_hc_sd',['danhsach'=>$danhsach]);
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
        $ktra = 0;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $tgian = mktime(17,00,00,Gmdate("m"),Gmdate("d"),Gmdate("Y"))-time();
        if($tgian > 0) {
           $ktra = 1;
        }
        if(!$request->input('check')){
            return redirect()->back()->with('error','chưa chọn hóa chất');
        }
        foreach($request->input('check') as $req) {
            if($request->list[$req] < 0){
                return redirect()->back()->with('error', 'số lượng hóa chất không hợp lệ');
            }
        }
        // block chức năng
        $suDungHoaChat = DB::table('su_dung_hoa_chat')
                            ->select('*')
                            ->where('ma_phong',$userId)
                            ->get();
        foreach($suDungHoaChat as $suDung) {
            $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                    ->select('*')
                    ->where('ma_su_dung_hoa_chat',$suDung->ma_su_dung_hoa_chat)
                    ->get();
            foreach($nhatKy as $nk) {
                if($nk->xac_nhan_cua_quan_kho == 0) {
                    return redirect()->back()
                    ->with('error','Tài khoản hiện đang bị khóa chức năng này hãy liên hệ với quản kho');
                }
            } 
        }
       
        foreach($request->input('check') as $req){    
            $suDungHoaChat =DB::table('su_dung_hoa_chat')
                ->select('*')
                ->where('ma_lo_hoa_chat',$req)
                ->where('ma_phong',$userId)
                ->get();
            foreach($suDungHoaChat as $sdhc) {
                $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                            ->get();
                foreach($loHoaChat as $lhc) {
                    $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
                                ->select('so_luong_con_lai')
                                ->where('ma_su_dung_hoa_chat',$sdhc->ma_su_dung_hoa_chat)
                                ->orderByDesc('ngay_cap_nhap')
                                ->limit(1)
                                ->get();
                        if($request->list[$req] > $lhc->so_luong_dong_goi){
                            (int)$soLuongConLai = (int)$request->list[$req]%(int)$lhc->so_luong_dong_goi ;
                            if($soLuongConLai < $lhc->so_luong_dong_goi) {
                                $soLuongConLai = $lhc->so_luong_dong_goi - $soLuongConLai;
                            }
                        }
                        else{
                            (int)$soLuongConLai = (int)($lhc->so_luong_dong_goi-(int)$request->list[$req]) ;
                        }
                        
                        
                    try{
                        DB::table('nhat_ky_su_dung_hoa_chat')
                            ->insert([
                                'ma_su_dung_hoa_chat'=>$sdhc->ma_su_dung_hoa_chat,
                                'ngay_cap_nhap'=>date("y-m-d-h-i-s"),
                                'so_luong_su_dung_trong_ngay'=> $request->list[$req],
                                'so_luong_con_lai'=> $soLuongConLai,
                                'xac_nhan_cua_quan_kho'=> $ktra
                            ]);
                    }catch(Exception $e) {
                        return redirect()->back()->with('error', $e);
                    }
                        $abc = (int)((int)$request->list[$req]/$lhc->so_luong_dong_goi) + $sdhc->so_luong_su_dung;
                        if($abc > $sdhc->so_luong_hoa_chat_nhap_ve){
                        return redirect()->back()->with('error','Số lượng nhập quá phạm vi');
                        }
                        
                    if($ktra == 1 ){
                        try{
                         DB::table('su_dung_hoa_chat')
                            ->where('ma_su_dung_hoa_chat',$sdhc->ma_su_dung_hoa_chat)
                            ->update([
                            'so_luong_su_dung'=>$abc
                                ]);
                        }catch(Exception $e){
                            return redirect()->back()->with('error', $e);
                    }
                    }
                   
                   
                    
                
                    }
                }
                   
            
            
        }
        
        return redirect('quanphong')->with('success', 'Thành công');
       

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
