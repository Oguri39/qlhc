<?php

namespace App\Http\Controllers\Quanphong;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sentinel;

class baoCaoLoiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $i = 0 ; $danhsach = [];
        //$userId = Auth::id();
        $userId = Sentinel::getUser()->idPhong;
        //dd($userId);
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
                foreach($danhMucHoaChat as $dmhc) {
                    $danhsach[$i] = array("hoachat"=>$dmhc,"lo"=>$l,"sudung"=>$sdhc);
                    $i++;
                }
               
            }
        }
        return view('admin.2_hc_hongloi',['danhsach'=>$danhsach]);
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
        //dd($userId);
        $soLuong = 0 ;
        if($request->idSu_dung_hoa_chat&&$request->so_luong) {
            try{
                $suDungHoaChat= DB::table('su_dung_hoa_chat')
                ->where('idPhong',$userId)
                ->where('idSu_dung_hoa_chat',$request->idSu_dung_hoa_chat)
                ->select('huy_bo')
                ->get();
                foreach($suDungHoaChat as $sdhc) {
                    $soLuong += $sdhc->huy_bo;
                }
                //dd($request->idSu_dung_hoa_chat,$userId);
                DB::table('Su_dung_hoa_chat')
                ->where('idPhong','=',$userId)
                ->where('idSu_dung_hoa_chat','=',$request->idSu_dung_hoa_chat)
                ->update([
                    'huy_bo' => $request->so_luong + $soLuong,
                    'so_luong_hoa_chat_nhap_ve'
                ]);
            }
            catch(Exception $e) {
                //dd($e);
                return redirect()->back()->with('error', $e);
            }
            
        }
        else{
            return redirect()->back()->with('error', 'Lỗi');
        }
        return redirect()->back()->with('success', 'Thành công');
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
