<form>
<div class="modal-header">
    <h4 class="modal-title" id="deleteLabel">@lang('weekcalculate/title.editdate')</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <div class="form-group">
        <div class="row">
            <input type="hidden" id="wkd_id" value="{{$data->wkd_id}}"/>
            <table width="100%">
                <tr>
                    <td width="2%"></td>
                    <td width="73%">
                        <table width="100%">
                            <tr>
                                <td><label for="e_wkd_us_id" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.employee') &nbsp;</label></td>
                                <td colspan="4">
                                    {!!Form::select('e_wkd_us_id', $listemployees, $data->wkd_us_id, ['class' => 'form-control', 'id' => 'e_wkd_us_id'])!!}
                                </td>
                                <td style="width: 10px;"></td>
                            </tr>
                            <tr>
                                <td><label for="e_wkd_day" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.workday') &nbsp;</label></td>
                                <td colspan="4">
                                    <input type="text" id="e_wkd_day" class="form-control" style="width: 110px;" value="{{$data->wkd_day}}" />
                                </td>
                                <td style="width: 10px;"></td>
                            </tr>

                            <tr>
                                <td><label for="e_wkd_driller_helper" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.drillerh') &nbsp;</label></td>
                                <td colspan="4">
                                    {!!Form::select('e_wkd_driller_helper', $listdrillers, $data->wkd_driller_helper, ['class' => 'form-control', 'id' => 'e_wkd_driller_helper', 'style' => 'width: 90px;'])!!}
                                </td>
                                <td style="width: 10px;"></td>
                            </tr>
                            <tr>
                                <td><label for="e_wkd_truck_driver" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.truckdriver') &nbsp;</label></td>
                                <td colspan="4">
                                    {!!Form::select('e_wkd_truck_driver', $listtrucks, $data->wkd_truck_driver, ['class' => 'form-control', 'id' => 'e_wkd_truck_driver', 'style' => 'width: 90px;'])!!}
                                </td>
                                <td style="width: 10px;"></td>
                            </tr>
                            <tr>
                                <td><label for="e_wkd_liveexp" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.liveexpenses') &nbsp;</label></td>
                                <td colspan="4">
                                    {!!Form::select('e_wkd_liveexp', $listlunch, $data->wkd_liveexp, ['class' => 'form-control', 'id' => 'e_wkd_liveexp', 'style' => 'width: 90px;'])!!}
                                </td>
                                <td style="width: 10px;"></td>
                            </tr>
                            <tr>
                                <td><label for="e_wkd_lunch" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.lunch') &nbsp;</label></td>
                                <td>
                                    {!!Form::select('e_wkd_lunch', $listlunch, $data->wkd_lunch, ['class' => 'form-control', 'id' => 'e_wkd_lunch', 'style' => 'width: 90px;'])!!}
                                </td>
                                <td>
                                    <label class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.lunchtime') &nbsp;</label>
                                </td>
                                <td>
                                    <input type="text" id="e_wkd_lunchtimed" class="form-control" style="width: 110px;" value="{{$data->wkd_lunchtimed}}" />
                                </td> 
                                <td>
                                    <input type="text" id="e_wkd_lunchtimeh" class="form-control" style="width: 90px;" value="{{$data->wkd_lunchtimeh}}" />
                                </td>                                            
                                <td style="width: 10px;"></td>
                            </tr>
                            <tr>
                                <td><label for="e_wkd_miles" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.miles') &nbsp;</label></td>
                                <td colspan="4">
                                    <input type="text" id="e_wkd_miles" class="form-control" value="{{$data->wkd_miles}}"/>
                                </td>
                                <td style="width: 10px;"></td>
                            </tr>
                            <tr>
                                <td><label for="e_wkd_shift_work" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.shiftwork') &nbsp;</label></td>
                                <td colspan="4">
                                    <input type="text" id="e_wkd_shift_work" class="form-control" value="{{$data->wkd_shift_work}}"/>
                                </td>
                                <td style="width: 10px;"></td>
                            </tr>
                        </table>   
                    </td>
                    <td width="23%">
                        <table width="100%">
                            <tr>
                                <td><label for="e_wkd_notes" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.notes')</label></td>
                            </tr>
                            <tr>
                                <td>
                                    <textarea id="e_wkd_notes" class="form-control" rows="12">{{$data->wkd_notes}}</textarea>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="2%"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
    <button id="btn_update" class="btn btn-success Remove_square">@lang('button.save')</button>
    <button id="btn_delete" class="btn btn-danger Remove_square">@lang('button.delete')</button>
</div>
</form>