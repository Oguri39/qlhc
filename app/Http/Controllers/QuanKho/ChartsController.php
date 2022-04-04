<?php

namespace App\Http\Controllers\QuanKho;

use App\Charts\Chartjs;
use App\Charts\Frappe;
use App\Charts\Highcharts;
use App\Http\Controllers\Controller;
use App\Models\Datatable;
use App\Models\tables;

class ChartsController extends Controller
{
    public function index() {
        $danhSach = new tables();
        $danhSachTonKho = $danhSach->tonKho();
        $danhSachHoaChat = $danhSach->hoaChatSuDung();
        $danhSachDuTru = $danhSach->duTruHoaChat();
        $nhapKho = $danhSach->nhapKho();

        return view('admin.Quankho.test1',['danhSachTonKho'=>$danhSachTonKho,'danhSachHoaChat'=>$danhSachHoaChat,'danhSachDuTru'=>$danhSachDuTru,'nhapKho'=>$nhapKho]);


    }
    
}
