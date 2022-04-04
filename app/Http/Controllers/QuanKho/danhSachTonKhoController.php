<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use App\Models\tables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class danhSachTonKhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    //     $danhsach =[];
    //     $i = 0 ;
    //     $dadung = 0;
    //     $loHoaChat = DB::table('Lo_hoa_chat')
    //                     ->select('*')
    //                     ->get();
    //     foreach($loHoaChat as $lhc) {
    //         $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
    //                             ->select('*')
    //                             ->where('idDanh_muc_hoa_chat',$lhc->idDanh_muc_hoa_chat)
    //                             ->get();
    //         foreach($danhMucHoaChat as $dmhc) {
    //              $suDungHoaChat = DB::table('Su_dung_hoa_chat')
    //                                     ->select('*')
    //                                     ->where('idLo_hoa_chat',$lhc->idLo_hoa_chat)
    //                                     ->get();
    //             foreach($suDungHoaChat as $sdhc) {
    //                 $dadung += $sdhc->so_luong_hoa_chat_nhap_ve;
    //             }
    //             $conlai = $lhc->so_luong -$dadung;
    //             if($conlai>0){
    //                 $danhsach[$i] = array('stt'=>$i+1,'hoachat'=>$dmhc , 'lo'=>$lhc ,'tonkho'=>$conlai);
    //                 $i++;
    //             }
               
    //             $dadung = 0 ;
    //         }
    //     }
    //    // dd($danhsach);
    //     return view('admin.1_ds_tk',['danhsach'=>$danhsach]);

        // $danhSach = new tables();
        // $danhSach = $danhSach->tonKho();
        // return view('admin.Quankho.danhSachTonKho',['danhSachTonKho'=>$danhSach]);
        return view('admin.1_suaxoa_hc');
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
        foreach($request->check as $req) {
            try{
                DB::table('lo_hoa_chat')
                    ->where('ma_lo_hoa_chat',$req)
                    ->update([
                        'nguong_canh_bao'=>$request->list[$req]
                    ]);
            }catch(Exception $e){
                return redirect('quankho')->with($e);
            }
        }
        return redirect()->back()->with('success','cập nhật ngưỡng cảnh báo thành công');
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
