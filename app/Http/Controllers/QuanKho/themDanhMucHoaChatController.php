<?php

namespace App\Http\Controllers\QuanKho;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class themDanhMucHoaChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dshc = DB::table('danh_muc_hoa_chat')
        ->select('*')
        ->get()->toArray();
        $ctcu = DB::table('cong_ty_cung_ung')
                ->select('*')
                ->get()->toArray();
        //dd($dshc);
        return view('admin.1_them_hc',compact($dshc),compact($ctcu));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //return view('trang tạo danh sách hóa chất');
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

            //Danh mục hóa chất
            if(!$request->ten_hoa_chat || !$request->don_vi_tinh ||
               !$request->ten_cong_ty_cung_ung || !$request->noi_san_xuat){
                return redirect()->back()->with('error', 'Nhập thiếu thông tin');
               }
            
            if($request->ma_hoa_chat){
                $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                    ->select('ma_danh_muc_hoa_chat')
                                    ->where('ma_danh_muc_hoa_chat',$request->ma_hoa_chat)
                                    ->get()->toArray();
                if($danhMucHoaChat) {
                    $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                        ->select('ma_danh_muc_hoa_chat')
                                        ->where('ma_danh_muc_hoa_chat',$request->ma_hoa_chat)
                                        ->where('don_vi_tinh','=',$request->don_vi_tinh)
                                        ->where('don_vi_dong_goi','=',$request->don_vi_dong_goi)
                                        ->where('ten_hoa_chat','=',$request->ten_hoa_chat)
                                        ->get()->toArray();
                    if(!$danhMucHoaChat) {
                        return redirect()->back()->with('error','Mã Hóa Chất không hợp lệ');
                    }

                }
                else {
                    try{
                        DB::table('danh_muc_hoa_chat')
                        ->insert([
                            'ma_danh_muc_hoa_chat'=>$request->ma_hoa_chat,
                            'ten_hoa_chat' => $request->ten_hoa_chat,
                            'don_vi_dong_goi' =>$request->don_vi_dong_goi,
                            'don_vi_tinh' => $request->don_vi_tinh
                        ]);
                    }catch(Exception $e){
                        return redirect()->back()->with('error','Hóa chất chưa được tạo');
                    }
                }
            }
            else {
                $danhMucHoaChat = DB::table('danh_muc_hoa_chat')
                                    ->select('ma_danh_muc_hoa_chat')
                                    ->where('ten_hoa_chat','=',$request->ten_hoa_chat)
                                    ->where('don_vi_tinh','=',$request->don_vi_tinh)
                                    ->where('don_vi_dong_goi','=',$request->don_vi_dong_goi)
                                    ->get()->toArray();
                if($danhMucHoaChat) {
                    //return redirect()->back()->with('error','Hóa chất đã tồn tại');
                }
                else {
                    try{
                        DB::table('danh_muc_hoa_chat')
                        ->insert([
                            'ten_hoa_chat' => $request->ten_hoa_chat,
                            'don_vi_dong_goi' =>$request->don_vi_dong_goi,
                            'don_vi_tinh' => $request->don_vi_tinh
                        ]);
                    }catch(Exception $e){
                        return redirect()->back()->with('error','Hóa chất chưa được tạo');
                    }
                }
                
            }

            // if ($request->ten_hoa_chat && $request->don_vi_tinh) {
            //         $dshc = DB::table('danh_muc_hoa_chat')
            //                 ->select('*')
            //                 ->where('ten_hoa_chat','=',$request->ten_hoa_chat)
            //                 ->where('don_vi_tinh','=',$request->don_vi_tinh)
            //                 ->where('don_vi_dong_goi','=',$request->don_vi_dong_goi)
            //                 ->get()->toArray();
                    
            //         if ($dshc) {
            //             return redirect('quankho/1_nhap_hc')->with('success', 'Tên hóa chất đã tồn tại');
            //         }
            //         else {
            //             try{
            //                 DB::table('danh_muc_hoa_chat')
            //                 ->insert([
            //                     'ten_hoa_chat' => $request->ten_hoa_chat,
            //                     'don_vi_dong_goi' =>$request->don_vi_dong_goi,
            //                     'don_vi_tinh' => $request->don_vi_tinh
            //                 ]);
            //             }catch(Exception $e){
            //                 return redirect('quankho/1_nhap_hc')->with('success', 'lỗi');
            //             }
                        
            //         }
                    
            // }else {

            //         return redirect('quankho/1_nhap_hc')->with('success', 'Nhập thiếu thông tin hóa chất');

            // }


            //Công ty cung ứng

            $ctcu = DB::table('cong_ty_cung_ung')
                        ->select('ma_cong_ty_cung_ung')
                        ->where('ten_cong_ty_cung_ung','=',$request->ten_cong_ty_cung_ung)
                        ->where('noi_san_xuat','=',$request->noi_san_xuat)
                        ->get()->toArray();
                   
            if(!$ctcu&&$request->ten_cong_ty_cung_ung&&$request->noi_san_xuat) {
                try{
                    DB::table('cong_ty_cung_ung')
                    ->insert([
                        'ten_cong_ty_cung_ung' => $request->ten_cong_ty_cung_ung ,
                        'noi_san_xuat' => $request->noi_san_xuat
                    ]);
                }catch(Exception $e) {
                    return redirect()->back()->with('error','Tạo Công Ty Lỗi');
                }

            }

                

        return redirect()->back()->with('success','Tạo thành công');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $dshc = DB::table('danh_muc_hoa_chat')
        ->select('*')
        ->where('ma_danh_muc_hoa_chat',$id)
        ->get()->toArray();

       // return view('trang xem chi tiết', compact($dshc));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id , $name)
    {
        if($name == 'hoachat'){
        $show = DB::table('danh_muc_hoa_chat')
            ->select('*')
            ->where('ma_danh_muc_hoa_chat',$id)  
            ->get()->toArray();
        }
        if($name == 'congty'){
        $show = DB::table('cong_ty_cung_ung')
            ->select('*')
            ->where('ma_cong_ty_cung_ung',$id)
            ->get()->toArray();
        }

    //return view('trang chỉnh sửa', compact($show));

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
        if($request->ten_hoa_chat && $request->don_vi_dong_goi && $request->don_vi_tinh && $request->quy_cach_dong_goi){
            
            try{
                DB::table('danh_muc_hoa_chat')
                ->where('ma_danh_muc_hoa_chat',$id)
                ->update([
                    'ten_hoa_chat' => $request->ten_hoa_chat,
                    'don_vi_dong_goi' => $request->don_vi_dong_goi,
                    'don_vi_tinh' => $request->don_vi_tinh,
                    'quy_cach_dong_goi' => $request->quy_cach_dong_goi
                ]);
            }catch(Exception $e) {

            }
            

        }
        else{
            //return redirect('đường dẫn trang chính')->with('success', 'Cập nhật thất bại');  ;
        }
        //return redirect('đường dẫn trang chính')->with('success', 'cập nhật thành công');
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
