@foreach($danhSachTonKho as $danhSach)
<tr>
    <td>
        {{$danhSach['stt']}}
    </td>
    <td>
        {{$danhSach['hoaChat']->ma_danh_muc_hoa_chat}}
    </td>
    <td>
        {{$danhSach['hoaChat']->ten_hoa_chat}}
    </td>
    <td>
        {{$danhSach['congTy']->ten_cong_ty_cung_ung}}
    </td>
    <td>
        {{$danhSach['congTy']->noi_san_xuat}}
    </td>
    <td>
        {{$danhSach['phong']->ten_phong}}
    </td>
    <td>
        {{$danhSach['hoaChat']->don_vi_tinh}}
    </td>
    <td>
        {{$danhSach['hoaChat']->don_vi_dong_goi}}
    </td>

    <td>
        <P class="money">{{$danhSach['soLuongTon']}}</P>
    </td>
    <td>
        <input type="number" class="money form-control" id="row-1-number" name="list[{{$danhSach['lo']->ma_lo_hoa_chat}}]" value="{{$danhSach['lo']->nguong_canh_bao}}" min="0">
        <input type="hidden" name="check[]" value="{{$danhSach['lo']->ma_lo_hoa_chat}}">
    </td>
</tr>
@endforeach