<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class hoaChatCacPhongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $danhsach =[];$danhsach1=[];
        $i = 0 ; $j =0 ;
        $phong = DB::table('phong')
                    ->select('*')
                    ->get();
        foreach($phong as $p) {
            $suDungHoaChat = DB::table('su_dung_hoa_chat')
                                ->select('*')
                                ->where('ma_phong',$p->ma_phong)
                                ->get();
            foreach($suDungHoaChat as $sdhc) {
                $loHoaChat = DB::table('lo_hoa_chat')
                                ->select('*')
                                ->where('ma_lo_hoa_chat',$sdhc->ma_lo_hoa_chat)
                                ->get()->toArray();
                foreach($loHoaChat as $lhc) {
                    $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                        ->select('*')
                                        ->where('ma_danh_muc_hoa_chat',$lhc->ma_danh_muc_hoa_chat)
                                        ->get()->toArray();
                    foreach($danhMucHoaChat as $dmhc) {
                        $danhsach[$i] = array($dmhc,$lhc,$sdhc,$i+1); //'lo'=>$lhc,'sudung'=>$sdhc );
                        $i++;
                    }
                   
                }
            }
            if($j==0){
                $danhsach1[$j]= array('phong'=>$p->ma_phong,'danhsach'=>$danhsach,'abc'=>' fade active show');
            }
            else{
                $danhsach1[$j]= array('phong'=>$p->ma_phong,'danhsach'=>$danhsach,'abc'=>null);
            }
            $j++;$danhsach =[];$i=0;
        }
        //dd($danhsach,$phong,$danhsach1);
        return view('admin.Quankho.1_dsp_xetnghiem',['danhsach'=>$danhsach1],['phong'=>$phong]); // xem hóa chất từng phòng
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
