@foreach($danhSachDuTru as $ds)
<td>
    {{$ds['stt']}}
</td>
<td>
    {{$ds['hoaChat']->ma_hoa_chat}}
</td>
<td>
    {{$ds['hoaChat']->ten_hoa_chat}}
</td>
<td>
    {{$ds['hoaChat']->don_vi_tinh}}
</td>
<td>
    <input type="number" class="money form-control" id="row-1-number" name="row-1-number" value="{{$ds['baoCao']->so_luong_su_tru}}" min="0">

</td>
<td>
    {{$ds['phong']->ten_phong}}
</td>
@endforeach