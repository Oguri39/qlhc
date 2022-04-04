<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleXLSX;

class excelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('admin.ecxel');
        
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
        // if ($request->hasFile('fileTest')) {
        //     $file = $request->filesTest;
        //     if($file->getClientOriginalExtension()=='xlsx'){

        //     }
        //     else{
        //         return redirect('quankho')->with('file không hợp lệ');
        //     }
        // }

        //dd($request->all());
        require "SimpleXLSX.php";
        $file = $request->newfile ;
        $ecxel = SimpleXLSX::parse($file);
            //dd($ecxel->rows(1));
        $dshc = $ecxel->rows(1);
       //dd(explode (', ',$dshc[10][8]));
       for($i=1 ; $i < count($dshc) ; $i++) {
           $dateCheck = date_parse($dshc[$i][10]);
           if($dateCheck["error_count"]){
            return redirect()->back()->with('error','Ngày không hợp lệ');
            // thông báo có ngày không hợp lệ trong file ecxel 
           }
          for($j =1 ; $j <=10 ;$j++){
            if($dshc[$i][$j]==null){
                return redirect()->back()->with('error','ecxel thiếu trường');
            }
          }
          $lo = DB::table('lo_hoa_chat')
                        ->select('so_lo')
                        ->where('so_lo','=',$dshc[$i][9])
                        ->get()->toArray();
            if($lo){
                return redirect()->back()->with('error','số lô đã tồn tại');//redirect('quankho')->with('Không thành công','số lô đã tồn tại');
            }

       }
       for($i=1 ; $i < count($dshc) ; $i++) {
        $kiemTraMaHC = DB::table('danh_muc_hoa_chat')
                            ->select('ma_danh_muc_hoa_chat')
                            ->where('ma_danh_muc_hoa_chat',$dshc[$i][3])
                            ->get()->toArray();
        $kiemTraMaCT = DB::table('cong_ty_cung_ung')
                            ->select('*')
                            ->where('ten_cong_ty_cung_ung','=',$dshc[$i][1])
                            ->where('noi_san_xuat','=',$dshc[$i][8])
                            ->get()->toArray();
       // dd($dshc[$i][10]);
        if($kiemTraMaHC && $kiemTraMaCT) {
            try{
                DB::table('lo_hoa_chat')
                    ->insert([
                        'ma_cong_ty_cung_ung'=>$kiemTraMaCT[0]->ma_cong_ty_cung_ung,
                        'ma_danh_muc_hoa_chat'=>$dshc[$i][3],
                        'so_lo'=>$dshc[$i][9],
                        'so_luong_tinh'=>$dshc[$i][5],
                        'so_luong_dong_goi'=>$dshc[$i][7],
                        'ngay_nhap'=>date('Y-m-d'),
                        'han_su_dung'=>date($dshc[$i][10]),
                        'nguong_canh_bao'=>0,

                    ]);
            }catch(Exception $e){
                return redirect()->back()->with('error','Lỗi');
            }
        }
        else {
            if(!$kiemTraMaHC&&$kiemTraMaCT){
                try{
                    DB::table('danh_muc_hoa_chat')
                    ->insert([
                        'ma_danh_muc_hoa_chat'=>$dshc[$i][3],
                        'ten_hoa_chat' => $dshc[$i][2],
                        'don_vi_dong_goi' =>$dshc[$i][6],
                        'don_vi_tinh' => $dshc[$i][4]
                    ]);
                    DB::table('lo_hoa_chat')
                    ->insert([
                        'ma_cong_ty_cung_ung'=>$kiemTraMaCT[0]->ma_cong_ty_cung_ung,
                        'ma_danh_muc_hoa_chat'=>$dshc[$i][3],
                        'so_lo'=>$dshc[$i][9],
                        'so_luong_tinh'=>$dshc[$i][5],
                        'so_luong_dong_goi'=>$dshc[$i][7],
                        'ngay_nhap'=>date('Y-m-d'),
                        'han_su_dung'=>date($dshc[$i][10]),
                        'nguong_canh_bao'=>0,

                    ]);
                    }catch(Exception $e){
                        return redirect()->back()->with('error','Lỗi');
                    }
            }
            else if(!$kiemTraMaCT && $kiemTraMaHC) {
                try{
               $ct = DB::table('cong_ty_cung_ung')
                ->insertGetId([
                    'ten_cong_ty_cung_ung' => $dshc[$i][1],
                    'noi_san_xuat' => $dshc[$i][8]
            
                ]);
                DB::table('lo_hoa_chat')
                    ->insert([
                        'ma_cong_ty_cung_ung'=>$ct,
                        'ma_danh_muc_hoa_chat'=>$dshc[$i][3],
                        'so_lo'=>$dshc[$i][9],
                        'so_luong_tinh'=>$dshc[$i][5],
                        'so_luong_dong_goi'=>$dshc[$i][5],
                        'ngay_nhap'=>date('Y-m-d'),
                        'han_su_dung'=>date($dshc[$i][10]),
                        'nguong_canh_bao'=>0,

                    ]);
                }catch(Exception $e){
                    return redirect()->back()->with('error','Lỗi');
                }
            }
            else {
                try{
                DB::table('danh_muc_hoa_chat')
                    ->insert([
                        'ma_danh_muc_hoa_chat'=>$dshc[$i][3],
                        'ten_hoa_chat' => $dshc[$i][2],
                        'don_vi_dong_goi' =>$dshc[$i][6],
                        'don_vi_tinh' => $dshc[$i][4]
                    ]);
                
                $ct = DB::table('cong_ty_cung_ung')
                ->insertGetId([
                    'ten_cong_ty_cung_ung' => $dshc[$i][1],
                    'noi_san_xuat' => $dshc[$i][8]
            
                ]);
            
                
                DB::table('lo_hoa_chat')
                    ->insert([
                        'ma_cong_ty_cung_ung'=>$ct,
                        'ma_danh_muc_hoa_chat'=>$dshc[$i][3],
                        'so_lo'=>$dshc[$i][9],
                        'so_luong_tinh'=>$dshc[$i][5],
                        'so_luong_dong_goi'=>$dshc[$i][5],
                        'ngay_nhap'=>date('Y-m-d'),
                        'han_su_dung'=>date($dshc[$i][10]),
                        'nguong_canh_bao'=>0,

                    ]);
                }catch(Exception $e){
                    return redirect()->back()->with('error','Lỗi');
                }
                
                
            }

            
        }
       }
       $date = date("Y-m-d-h-i-s");
       $fileName = $date."_".$file->getClientOriginalName() ;
       $seve = $file->move('ecxelUpload',$fileName);
       return redirect()->back()->with('Upload' , 'Thành Công');

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
