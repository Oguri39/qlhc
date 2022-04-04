                                @foreach( $nhapKho as $danhSach)
                                <tr>
                                    <td>
                                        {{$danhSach['stt']}}
                                    </td>
                                    <td>
                                        {{$danhSach['maHoaChat']}}
                                    </td>
                                    <td>
                                        {{$danhSach['tenHoaChat']}}
                                    </td>
                                    <td>
                                        {{$danhSach['tenNhaThau']}}
                                    </td>
                                    <td>
                                        {{$danhSach['nuocSanXuat']}}
                                    </td>
                                    <td>
                                        {{$danhSach['soLo']}}
                                    </td>
                                    <td>
                                        {{$danhSach['donViTinh']}}
                                    </td>
                                    <td>
                                        {{$danhSach['soLuongTinh']}}
                                    </td>

                                    <td>
                                        <P class="money">{{$danhSach['donViDongGoi']}}</P>
                                    </td>
                                    <td>
                                        {{$danhSach['soLuongDongGoi']}}
                                    </td>
                                    <td>
                                        {{$danhSach['hanSuDung']}}
                                    </td>
                                </tr>

                                @endforeach