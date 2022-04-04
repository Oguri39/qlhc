<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\danhMucHoaChat ;
use App\Models\loHoaChat ;
use App\Models\suDungHoaChat ;
use App\Models\nhatKySuDungHoaChat;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;

class chemicalListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $danhsach = []; $hoaChatDaPhan = 0 ; $ton = 0; $i = 0 ;
        $danhMucHoaChat = DB::table('Danh_muc_hoa_chat')
                            ->select('*')
                            ->get();
        foreach($danhMucHoaChat as $dmhc) {
            $loHoaChat = DB::table('Lo_hoa_chat')
                            ->select('*')
                            ->where('idDanh_muc_hoa_chat',$dmhc->idDanh_muc_hoa_chat)
                            ->get();
            foreach($loHoaChat as $lhc) {
                $suDungHoaChat = DB::table('Su_dung_hoa_chat')
                                    ->select('*')
                                    ->where('idLo_hoa_chat',$lhc->idLo_hoa_chat)
                                    ->get();
                foreach($suDungHoaChat as $sdhc) {
                    $hoaChatDaPhan += $sdhc->so_luong_hoa_chat_nhap_ve;
                }
                $ton = $lhc->so_luong - $hoaChatDaPhan;
                $danhsach[$i] = array('danhmuc'=>$dmhc,'lo'=>$lhc,'tonkho'=>$ton);
                $hoaChatDaPhan = 0 ;$i++;
            }
        }
        //dd($danhsach);
        return view('admin.1_ds_hc',compact($danhsach));

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
