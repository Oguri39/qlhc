@foreach($danhSachHoaChat as $danhSach)
<tr>
    <td>
        {{ $danhSach['stt'] }}
    </td>
    <td>
        {{ $danhSach['hoaChat']->ma_danh_muc_hoa_chat }}
    </td>
    <td>
        {{ $danhSach['hoaChat']->ten_hoa_chat }}
    </td>
    <td>
        {{ $danhSach['phong']->ten_phong }}
    </td>
    <td>
        {{ $danhSach['lo']->so_lo }}
    </td>
    <td>
        {{ $danhSach['hoaChat']->don_vi_tinh }}
    </td>
    <td>
        {{ $danhSach['hoaChat']->don_vi_dong_goi }}
    </td>
    <td>
        {{ $danhSach['lo']->so_luong_dong_goi }}
    </td>
    <td>
        {{ $danhSach['lo']->han_su_dung }}
    </td>
    <td>
        {{ $danhSach['tonDauThangTruoc'] }}
    </td>
    <td>
        <P class="money">{{ $danhSach['soLuongDungTrongThang'] }}</P>
    </td>
    <td>
        <P class="money">{{ $danhSach['loi'] }}</P>
    </td>
    <td>
        <P class="money">{{ $danhSach['tonKho'] }}</P>
    </td>
</tr>
@endforeach