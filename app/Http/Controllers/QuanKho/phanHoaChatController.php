<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class phanHoaChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dshc = [];
        $i = 0;
        $hoachat = DB::table('danh_muc_hoa_chat')
        ->join('lo_hoa_chat','danh_muc_hoa_chat.ma_danh_muc_hoa_chat','=','lo_hoa_chat.ma_danh_muc_hoa_chat')
        ->select('*')
        ->get()->toArray();
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
            $conlai = ($lhc->so_luong_tinh) - $tong ;
            if($conlai > 0){
                 $dshc[$i] = array('hoaChat'=>$lhc,'soLuongConLai'=>$conlai);
                 $i++;
            }
            $conlai = 0 ;
           
        }
        $phong = DB::table('phong')
                    ->select('*')
                    ->get();
               // dd($dshc);
        return view('admin.1_phan_hc',['dshc'=>$dshc,'dsp'=>$phong]);
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
        //dd(($request->all()));
      
        if(!$request->input('check')){
            return redirect()->back()->with('error', 'Lỗi');
        }
        foreach($request->input('check') as $req){
            if($request->list[$req]<=0){
                return redirect()->back()->with('error','Lỗi');
            }
        }
        foreach($request->input('check') as $req) {
            //dd($request->list[$req]);
            $loHoaChat = DB::table('lo_hoa_chat')
                            ->select('*')
                            ->where('ma_lo_hoa_chat',$req)
                            ->get();
            foreach($loHoaChat as $lhc) {
                if($lhc->so_luong_tinh >= (int)$request->list[$req] &&(int)$request->list[$req]>0){
                    $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                        ->select('*')
                                        ->where('ma_lo_hoa_chat',$lhc->ma_lo_hoa_chat)
                                        ->where('ma_phong',$request->ma_phong)
                                        ->get()->toArray();
                        if($suDungHoaChat) {
                            try {
                                DB::table('su_dung_hoa_chat')
                                    ->where('ma_su_dung_hoa_chat',$suDungHoaChat[0]->ma_su_dung_hoa_chat)
                                    ->update([
                                        'so_luong_hoa_chat_nhap_ve'=>$suDungHoaChat[0]->so_luong_hoa_chat_nhap_ve + $request->list[$req]
                                    ]);

                            }catch(Exception $e) {
                                return redirect()->back()->with('error', $e);
                            }
                        }
                        else {
                            try{
                                DB::table('su_dung_hoa_chat')
                                ->insert([
                                'ma_lo_hoa_chat'=>$lhc->ma_lo_hoa_chat,
                                'ma_phong'=>$request->ma_phong,
                                'so_luong_hoa_chat_nhap_ve'=>$request->list[$req],
                                'so_luong_su_dung'=>0,
                                'ten_thiet_bi_su_dung'=>'chưa có',
                                'huy_bo'=> 0,
                                ]);
                            }catch(Exception $e){
                                return redirect()->back()->with('error', $e);
                            }
                        }

                        
                
                }
            }

        }
        
        return redirect()->back()->with('success', 'Tạo thành công ');
        
        
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
