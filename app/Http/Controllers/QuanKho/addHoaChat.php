<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class addHoaChat extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd($danhMucHoaChat);
        $i = 0;
        $danhsach = [];
        $danhMuc = DB::table('danh_muc_hoa_chat')
            ->select('*')
            ->get();
        $congTy = DB::table('cong_ty_cung_ung')
            ->select('*')
            ->get();
        foreach ($danhMuc as $dm) {
            $danhsach[$i] = array(
                'tenHoaChat' => $dm->ten_hoa_chat,
                'maHoaChat' => $dm->ma_danh_muc_hoa_chat,
                'donvitinh' => $dm->don_vi_tinh,
                'donvidonggoi' => $dm->don_vi_dong_goi
            );
            $i++;
        }
        $j = 0;
        $danhSachCongTy = [];
        foreach ($congTy as $ct) {

            $danhSachCongTy[$j] = array(
                'congTyCungUng' => $ct->ten_cong_ty_cung_ung,
                'maSanXuat' => $ct->ma_cong_ty_cung_ung,
                'noiSanXuat' => $ct->noi_san_xuat
            );
            $j++;
        }




        return view('admin.1_nhap_hc', ['danhMucHoaChat' => $danhsach, 'congTyCungUng' => $danhSachCongTy]);
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
        // $i=0;
        // dd($request->all(),$request->input('han_su_dung.'.$i));
        $i = 0;
        while (
            $request->input('so_lo.' . $i) ||
            $request->input('so_luong.' . $i) ||
            $request->input('han_su_dung.' . $i) ||
            $request->input('idDanh_muc_hoa_chat.' . $i) ||
            $request->input('idCong_ty_cung_ung.' . $i) ||
            $request->input('so_luong_dong_goi.' . $i)
        ) {
            if (
                $request->input('so_lo.' . $i) &&
                $request->input('so_luong.' . $i) &&
                $request->input('han_su_dung.' . $i) &&
                $request->input('idDanh_muc_hoa_chat.' . $i) &&
                $request->input('idCong_ty_cung_ung.' . $i) &&
                $request->input('so_luong_dong_goi.' . $i)
            ) {

                $loHoaChat = DB::table('lo_hoa_chat')
                    ->select('*')
                    ->where('so_lo', '=', $request->input('so_lo.' . $i))
                    ->get()->toArray();

                if ($loHoaChat) {
                    return redirect()->back()->with('error', 'Trùng số lô');
                }
            } else {
                return redirect()->back()->with('error', 'Nhập Thiếu Thông Tin Hóa Chất');
            }
            $i++;
            $loHoaChat = null;
        }
        $i = 0;
        while (
            $request->input('so_lo.' . $i) &&
            $request->input('so_luong.' . $i) &&
            $request->input('han_su_dung.' . $i) &&
            $request->input('idDanh_muc_hoa_chat.' . $i) &&
            $request->input('idCong_ty_cung_ung.' . $i) &&
            $request->input('so_luong_dong_goi.' . $i)
        ) {
            if (
                $request->input('so_lo.' . $i) && $request->input('so_luong_dong_goi.' . $i) &&
                $request->input('so_luong.' . $i) && $request->input('han_su_dung.' . $i) &&
                $request->input('idCong_ty_cung_ung.' . $i) && $request->input('idDanh_muc_hoa_chat.' . $i)
            ) {
                try {
                    DB::table('lo_hoa_chat')
                        ->insert([
                            'ma_danh_muc_hoa_chat' => $request->input('idDanh_muc_hoa_chat.' . $i),
                            'ma_cong_ty_cung_ung' => $request->input('idCong_ty_cung_ung.' . $i),
                            'so_lo' => $request->input('so_lo.' . $i),
                            'so_luong_tinh' => $request->input('so_luong.' . $i),
                            'ngay_nhap' => date("Y-m-d"),
                            'so_luong_dong_goi' => $request->input('so_luong_dong_goi.' . $i),
                            'han_su_dung' => $request->input('han_su_dung.' . $i),
                            'nguong_canh_bao' => 0
                        ]);
                } catch (Exception $e) {
                    return redirect()->back()->with('error', $e);
                };
            } else {
                return redirect()->back()->with('error', 'Lỗi');
            }
            $i++;
            $loHoaChat = null;
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
