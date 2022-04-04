<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dataChart extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

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
        $danhSach = [['X', '1']];
        $i = 1;
        for ($j = 1; $j <= date('d'); $j++) {
            $danhSach[$j] = [$j, 0];
        }
        $suDungHoaChat = DB::table('su_dung_hoa_chat')
            ->select('*')
            ->where('ma_su_dung_hoa_chat', $id)
            ->get()->toArray();

        if (!$suDungHoaChat) return false;
        $loHoaChat = DB::table('lo_hoa_chat')
            ->select('*')
            ->where('ma_lo_hoa_chat', $suDungHoaChat[0]->ma_lo_hoa_chat)
            ->get()->toArray();
        if (!$loHoaChat) return false;
        $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
            ->select('*')
            ->where('ma_danh_muc_hoa_chat', $loHoaChat[0]->ma_danh_muc_hoa_chat)
            ->get()->toArray();
        if (!$danhMucHoaChat) return false;
        $nhatKy = DB::table('nhat_ky_su_dung_hoa_chat')
            ->select('*')
            ->where('ma_su_dung_hoa_chat', $suDungHoaChat[0]->ma_su_dung_hoa_chat)
            ->whereDate('ngay_cap_nhap', '>=', date("Y-m-1"))
            ->whereDate('ngay_cap_nhap', '<=', date('Y-m-d'))
            ->where('xac_nhan_cua_quan_kho', '>', 0)
            ->get()->toArray();
        foreach ($nhatKy as $nk) {
            $date = date_parse($nk->ngay_cap_nhap);
            $danhSach[$date['day']] = [$date['day'], (int)($nk->so_luong_su_dung_trong_ngay / $loHoaChat[0]->so_luong_dong_goi)];
        }
        return [$danhSach, $danhMucHoaChat[0]->ten_hoa_chat];
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
