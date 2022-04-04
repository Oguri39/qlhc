<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class canhBaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $loHoaChat = DB::table('lo_hoa_chat')
            ->select('*')
            ->get();
        $dateCheck = date_parse(date('y-m-d'));
        //dd($dateCheck);
        foreach ($loHoaChat as $lo) {
            $month = date('m');
            $month--;
            $homnay = date('Y-m-d');
            $dateHSD = strtotime($lo->han_su_dung);
            $day = strtotime(date('Y-m-d'));
            $lastMonth = $day - strtotime(date('Y-' . $month . '-d'));
            if ($dateHSD - $day < $lastMonth) {
                $suDungHoaChat = DB::table('su_dung_hoa_chat')
                    ->select('*')
                    ->where('ma_lo_hoa_chat', $lo->ma_lo_hoa_chat)
                    ->get();

                foreach ($suDungHoaChat as $suDung) {
                    $canhBao = DB::table('canh_bao')
                        ->select('*')
                        ->where('ma_su_dung_hoa_chat', $suDung->ma_su_dung_hoa_chat)
                        ->where('loai_canh_bao', '=', 'hết hạn')
                        ->get()->toArray();
                    if (!$canhBao) {
                        try {
                            DB::table('canh_bao')
                                ->insert([
                                    'ma_su_dung_hoa_chat' => $suDung->ma_su_dung_hoa_chat,
                                    'ten_canh_bao' => 'all',
                                    'loai_canh_bao' => 'hết hạn',
                                    'thoi_gian_canh_bao' => date('y-m-d-h-i-s')
                                ]);
                        } catch (Exception $e) {
                            return "Lỗi cập nhật 1";
                        }
                    }
                }
            }
        }
        $suDungHoaChat = DB::table('su_dung_hoa_chat')
            ->select('*')
            ->get();
        foreach ($suDungHoaChat as $suDung) {
            $lo = DB::table('lo_hoa_chat')
                ->select('*')
                ->where('ma_lo_hoa_chat', $suDung->ma_lo_hoa_chat)
                ->get()->toArray();
            $soLuongConLai = $suDung->so_luong_hoa_chat_nhap_ve - $suDung->so_luong_su_dung;
            if ($soLuongConLai <= $lo[0]->nguong_canh_bao) {
                $canhBao = DB::table('canh_bao')
                    ->select('*')
                    ->where('ma_su_dung_hoa_chat', $suDung->ma_su_dung_hoa_chat)
                    ->where('loai_canh_bao', '=', 'số lượng')
                    ->get()->toArray();
                if (!$canhBao) {
                    try {
                        DB::table('canh_bao')
                            ->insert([
                                'ma_su_dung_hoa_chat' => $suDung->ma_su_dung_hoa_chat,
                                'ten_canh_bao' => 'phòng',
                                'loai_canh_bao' => 'số lượng',
                                'thoi_gian_canh_bao' => date('y-m-d-h-i-s')
                            ]);
                    } catch (Exception $e) {
                        dd($e);
                        return "lỗi cập nhật 2 ";
                    }
                }
            }
        }
        return "Cảnh báo thành công";
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
