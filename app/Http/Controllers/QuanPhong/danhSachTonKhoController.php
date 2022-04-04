<?php

namespace App\Http\Controllers\QuanPhong;

use Sentinel;
use App\Http\Controllers\Controller;
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
        $danhsach = []; $i = 0 ;
        $userId = Sentinel::getUser()->idPhong;
        $suDungHoaChat = DB::table('Su_dung_hoa_chat')
                            ->select('*')
                            ->where('idPhong',$userId)
                            ->get();
        foreach($suDungHoaChat as $sdhc) {
            $loHoaChat = DB::table('Lo_hoa_chat')
                            ->select('*')
                            ->where('idLo_hoa_chat',$sdhc->idLo_hoa_chat)
                            ->get();
            foreach($loHoaChat as $lhc) {
                $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
                                    ->select('*')
                                    ->where('idDanh_muc_hoa_chat',$lhc->idDanh_muc_hoa_chat)
                                    ->get();
                foreach($danhMucHoaChat as $dmhc) {
                    $danhsach[$i] = array('stt'=>$i+1,'hoachat'=>$dmhc, 'lo'=>$lhc, 'sdhc'=>$sdhc);
                    $i++;
                }
            }
        }

        return view('admin.2_hc_tk',['danhsach'=>$danhsach]);

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
