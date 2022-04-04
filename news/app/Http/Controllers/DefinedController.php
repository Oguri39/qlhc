<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\MessageBag;
use Sentinel;
use Analytics;
use View;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;
use App\Charts\Highcharts;
use App\Models\User;
//use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Analytics\Period;
use File;
use Artisan;
use Str;
use DB;
use Lang;

class DefinedController extends Controller{
	
	protected $CODE_DRIVE = -1;
    protected $CODE_SHOP = -2;
    protected $CODE_HOLIDAYS = -3;
    protected $CODE_VACATION = -4;
    protected $CODE_DRIVE_CDL = -10;

    protected $driller = array("Driller","Helper");

	protected $truckdriver = array("NO","YES");

	protected $lunch = array("NO","YES");

	protected $wstatus = array("OPEN","CLOSED"); 

	protected $listchecklist = array("None","Backhoe","Crane","Knuckle Boom","Pick Up","Truck","Trailer","Misc");

	protected $listcompanies = array('ACSI', 'ACSI IN FLORIDA', 'ACSIMPR', 'ACSI OFFROAD');

	protected $listdepartments = array('DRILLING', 'GENERAL', 'MOTOR SHOP', 'PUMP');
	
    public function typeOfEmployee($employeeDept) {
		$employeeDept=substr($employeeDept,0,3);
		$typeOfEmployee=substr($employeeDept,0,1);
		// NJ
		if ($employeeDept=="A02") $typeOfEmployee="D";
		if ($employeeDept=="A03") $typeOfEmployee="P";
		if ($employeeDept=="A05") $typeOfEmployee="A";
		// DE
		if ($employeeDept=="B02") $typeOfEmployee="B";
		if ($employeeDept=="B05") $typeOfEmployee="B";
		// MD
		if ($employeeDept=="C02") $typeOfEmployee="H";
		if ($employeeDept=="C03") $typeOfEmployee="H";
		if ($employeeDept=="C05") $typeOfEmployee="H";
		// NC
		if ($employeeDept=="E02") $typeOfEmployee="C";
		if ($employeeDept=="E05") $typeOfEmployee="C";
		// NC Utility
		if ($employeeDept=="U02") $typeOfEmployee="U";
		if ($employeeDept=="U05") $typeOfEmployee="U";
		// FL
		if ($employeeDept=="F02") $typeOfEmployee="F";
		if ($employeeDept=="F05") $typeOfEmployee="F";
		// Miami
		if ($employeeDept=="J02") $typeOfEmployee="J";
		if ($employeeDept=="J05") $typeOfEmployee="J";
		
		return $typeOfEmployee;
	}

	public function MinuteDiffInt($dt1, $dt2) {
		$to_time = strtotime($dt1);
		$from_time = strtotime($dt2);
		return round(abs($to_time - $from_time) / 60,2);

	}

	public function isSunday($date) {
		$weekDay = date('w', strtotime($date));
		return ($weekDay == 0);
	}

	public function isSaturday($date) {
		$weekDay = date('w', strtotime($date));
		return ($weekDay == 6);
	}

	public function showTime($minutes) {
		$hours = floor($minutes / 60);
		$min = $minutes - ($hours * 60);
		return str_pad($hours,2,"0", STR_PAD_LEFT).":".str_pad($min,2,"0", STR_PAD_LEFT);
	}

	public function showDecimalTime($minutes) {
        $hours = floor($minutes / 60);
        $min = $minutes - ($hours * 60);
        $decmin = number_format($min / 60,2);
        return number_format($hours+$decmin,2);
    }

	public function toUSDate($date){
		if($date == '0000-00-00') return "";
		else return Carbon::parse($date)->format('m/d/Y');
	}

	public function toUSDateTime($date){
		if($date == '0000-00-00 00:00:00') return "";
		else return Carbon::parse($date)->format('m/d/Y H:i:s');
	}

	function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		if (($lat1 == $lat2) && ($lon1 == $lon2)) {
			return 0;
		}
		else {
			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K") {
				return ($miles * 1.609344);
			} else if ($unit == "N") {
			  	return ($miles * 0.8684);
			} else {
			  	return $miles;
			}
		}
	}

	public function calculateTime($nrRecord) {		
		$driveCode = -2;
		$shopCode = -1;
		$r = DB::table('workdays')->where('wkd_id',$nrRecord)->first();
		$isTruckDriver = $r->wkd_truck_driver;
		$totalTime = 60*8;
		$totalTimeOVT = 0;
		$firstJob = FALSE;

		$totalTimeWorked =0;
		$totalTimeDrive = 0;
		$totalTimeShop = 0;
		
		$resbsub = DB::table('hours')
					->leftJoin('jobs','jobs.jid','=','hours.hrs_jobid')
					->where('hrs_wkd_id',$nrRecord)
					->where('hrs_status','<',2)
					->orderBy('hrs_id')->get();
		$i=0;
		$gotFirstJobID=FALSE;
		foreach ($resbsub as $key => $rb) {			
			$startTime = $rb->hrs_starttime;
			$endTime = $rb->hrs_endtime;
			$timeWorked = $this->MinuteDiffInt($startTime, $endTime);
			
			if ($r->wkd_lunch==1) {
				$lunchTime = $r->wkd_lunchtime;
				if (($r->wkd_lunchtime >= $startTime) && ($r->wkd_lunchtime < $endTime)) {

					$timeWorked = max($timeWorked - 30,0);
				}
			}
			if (((!($rb->hrs_jobid == $shopCode||$rb->hrs_jobid == $driveCode))&&(!$gotFirstJobID))) {
				$gotFirstJobID=true;

			}
			if ((!$gotFirstJobID) && (!$isTruckDriver)) {
				if ($rb->hrs_jobid == $shopCode) {
					$totalTimeShop = $totalTimeShop + $timeWorked;
					$totalTimeWorked = $totalTimeWorked + $timeWorked;
				} else {
					if ($rb->hrs_jobid == $driveCode) {
						$totalTimeDrive = $totalTimeDrive + $timeWorked;
						$totalTimeWorked = $totalTimeWorked + $timeWorked;
					} else {
						$totalTimeWorked = $totalTimeWorked + $timeWorked;
					}

				}
			} else {
				$totalTimeWorked = $totalTimeWorked + $timeWorked;
			}
			$i++;
		}
		if ($totalTimeWorked > $totalTime) {
			$totalTimeOVT = max($totalTimeWorked - $totalTime,0);
		}

		$resbsub = DB::table('hours')->leftJoin('jobs','hours.hrs_jobid','=','jobs.jid')
					->where('hrs_wkd_id',$nrRecord)->where('hrs_status','<',2)->orderBy('hrs_starttime')->get();
		$i=0;
		$gotFirstJobID=FALSE;
		foreach ($resbsub as $key => $rb) {			
			$startTime = $rb->hrs_starttime;
			$endTime = $rb->hrs_endtime;
			$timeWorked = $this->MinuteDiffInt($startTime, $endTime);

			if ($r->wkd_lunch==1) {
				$lunchTime = $r->wkd_lunchtime;
				if (($r->wkd_lunchtime >= $startTime) && ($r->wkd_lunchtime < $endTime)) {

					$timeWorked = max($timeWorked - 30,0);
				}
			}
			if (((!($rb->hrs_jobid == $shopCode||$rb->hrs_jobid == $driveCode))&&(!$gotFirstJobID))) {
				$gotFirstJobID=true;
			}

			if (!$isTruckDriver) {

				if (!$gotFirstJobID) {

					if ((($rb->hrs_jobid == $shopCode||$rb->hrs_jobid == $driveCode))&&(!$gotFirstJobID)) {

						if ($totalTimeOVT>0) {
							if ($totalTimeOVT>$timeWorked) {
								$timeCalculationOVT = $timeWorked;
								$timeCalculationRegular = 0;
								$totalTimeOVT = $totalTimeOVT - $timeWorked;

							} else {
								$timeCalculationOVT = $totalTimeOVT;
								$timeCalculationRegular = ($timeWorked - $totalTimeOVT);
								$totalTime = $totalTime -($timeWorked-$totalTimeOVT);
								$totalTimeOVT = 0;
							}
						} else {
							if ($totalTime>=$timeWorked) {
								$timeCalculationOVT = 0;
								$timeCalculationRegular = $timeWorked;
								$totalTime = $totalTime - $timeWorked;

							} else {
								$ovt = $timeWorked - $totalTime;
								$timeCalculationOVT = $ovt;
								$timeCalculationRegular = $timeWorked - $ovt;
								$totalTime = 0;

							}
						}

					} else {
						if ($totalTime>=$timeWorked) {
							$timeCalculationOVT = 0;
							$timeCalculationRegular = $timeWorked;
							$totalTime = $totalTime - $timeWorked;

						} else {
							$ovt = $timeWorked - $totalTime;
							$timeCalculationOVT = $ovt;
							$timeCalculationRegular = $timeWorked - $ovt;
							$totalTime = 0;

						}
					}
				} else {
					if ($totalTime>=$timeWorked) {
						$timeCalculationOVT = 0;
						$timeCalculationRegular = $timeWorked;
						$totalTime = $totalTime - $timeWorked;

					} else {

						$ovt = $timeWorked - $totalTime;
						$timeCalculationOVT = $ovt;
						$timeCalculationRegular = $timeWorked - $ovt;
						$totalTime = 0;
					}

				}
			} else {				
				if ($totalTime >= $timeWorked) {
					$timeCalculationOVT = 0;
					$timeCalculationRegular = $timeWorked;
					$totalTime = $totalTime - $timeWorked;
				} else {
					$ovt = $timeWorked - $totalTime;
					$timeCalculationOVT = $ovt;
					$timeCalculationRegular = $timeWorked - $ovt;
					$totalTime = 0;
				}
			}

			$regular = floor($timeCalculationRegular);
			$ovt = floor($timeCalculationOVT);
			$dbl=0;
			if ($this->isSunday($r->wkd_day)) {
				$dbl=$ovt + $regular;
				$regular = 0;
				$ovt=0;

			}
			if ($this->isSaturday($r->wkd_day)) {
				$ovt=$ovt+$regular;
				$regular = 0;
				$dbl=0;

			}
			$datt = array(
				'hrs_ovt'		=> $ovt,
				'hrs_regular'	=> $regular,
				'hrs_double'	=> $dbl,
			);
			$resul = DB::table('hours')->where('hrs_id',$rb->hrs_id)->update($datt);			
			$i++;
		}
	}

	function showEquipment($r) {	
		$text = '<table border="1" width="100%">';
		$text .= '<tr>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.internalcode') . '</th>';
		$text .= '<td colspan="3">';
		$text .= $r->eq_internalcode;
		$text .= '</td>';
		$text .= '</tr>';
		$text .= '<tr>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.type') . '</th>';
		$text .= '<td>';
		$text .= $r->et_title;
		$text .= '</td>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.name') . '</th>';
		$text .= '<td>';
		$text .= $r->eq_name;
		$text .= '</td>';
		$text .= '</tr>';
		$text .= '<tr>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.vin') . '</th>';
		$text .= '<td>' . $r->eq_vin . '</td>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.lic') . '</th>';
		$text .= '<td>' . $r->eq_lic . '</td>';
		$text .= '</tr>';
		$text .= '<tr>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.checkinmiles') . '</th>';
		$text .= '<td>' . $r->eq_check_in_miles . '</td>';
		$text .= '<th style="background-color:#ccccee"><b>' . Lang::get('equipments/title.checkinhours') . '</b></th>';
		$text .= '<td>' . $r->eq_check_in_hours . '</td>';
		$text .= '</tr>';
		$text .= '<tr>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.startdate') . '</th>';
		$text .= '<td>' . $this->toUSDate($r->eq_date_start) . '</td>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.status') . '</th>';
		$text .= '<td>';
		if ($r->eq_status == 0) $text .= Lang::get('equipments/title.canbeused');
		elseif ($r->eq_status == 1) $text .= Lang::get('equipments/title.cannotbeused');		
		$text .= '</td>';
		$text .= '</tr>';
		$text .= '<tr>';
		$text .= '<th style="background-color:#ccccee">' . Lang::get('equipments/title.notes') . '</th>';
		$text .= '<td colspan="3">';
		$text .= $r->eq_notes;
		$text .= '</td>';
		$text .= '</tr>';
		$text .= '</table>';			

		return $text;
	}

	public function getInspectionReport($checkType) {
		$fields = array();		
		$i = 0;
		$res = DB::table('equipmodel1')->where('eqm1_et_checklist',$checkType)->orderBy('eqm1_pos')->get();
		foreach ($res as $key => $r) {
			$fields[$i]['id'] = $r->eqm1_id;
			$fields[$i]['pos'] = $r->eqm1_pos;
			$fields[$i]['title'] = $r->eqm1_title;
			$fields[$i]['fields'] = array();
			$res2 = DB::table('equipmodel2')->where('eqm2_eqm1_id',$r->eqm1_id)->orderBy('eqm2_pos')->get();
			$j = 0;
			foreach ($res2 as $key2 => $r2) {
				$fields[$i]['fields'][$j]['id'] = $r2->eqm2_id;
				$fields[$i]['fields'][$j]['pos'] = $r2->eqm2_pos;
				$fields[$i]['fields'][$j]['type'] = $r2->eqm2_type;
				$fields[$i]['fields'][$j]['title'] = $r2->eqm2_title;
				$j++;
			}
			$i++;
		}
		return $fields;		
	}

	public function showPanel($headerid, $current, $notes, $mode, $color, $fields) {
		$out = "<div class='panel' style='border-color:$color'>\n";
		$out .= "<div class='panel-heading' style='background-color:$color'>";
		$bgcolor = "#FFFFFF";
		if(isset($fields[$headerid])){
			$out .= $fields[$headerid]['title'];
			$out .= "</div>";		
			$out .= "<div class='panel-body'>";
			$out .= "<table width=100%>";

			if ($mode == 0) { // edit mode
				for ($i=0; $i<count($fields[$headerid]['fields']); $i++) {
					$id = $fields[$headerid]['fields'][$i]['pos'];
					$ck1 = "";
					$ck2 = "checked";
					if (isset($current[$id]) && $current[$id]==0) {
						$ck1 = "checked";
						$ck2 = "";
					}
					if (isset($current[$id]) && $current[$id]==1) {
						$ck1 = "";
						$ck2 = "checked";

					}
					$out .= "<tr bgcolor='$bgcolor'><td nowrap><input type='radio' name='ck_btn_$id' value=0 $ck1>&nbsp;OK&nbsp;&nbsp;&nbsp;<input type='radio' name='ck_btn_$id' value=1 $ck2>&nbsp;NO&nbsp;&nbsp;&nbsp;</td><td>".$fields[$headerid]['fields'][$i]['pos']." - ".$fields[$headerid]['fields'][$i]['title']."</td></tr>";

				}
				$hpos = $fields[$headerid]['pos'];
				$out .= "<tr bgcolor='$bgcolor'><td nowrap colspan=2><textarea name='ck_notes_$hpos' cols=30 rows=3>". (isset($notes[$headerid]) ? $notes[$headerid]['note'] : "") ."</textarea></td></tr>";
			} else {
				for ($i=0; $i<count($fields[$headerid]['fields']); $i++) {
					$check = "&check;";
					$id = $fields[$headerid]['fields'][$i]['pos'];
					if (isset($current[$id]) && $current[$id]==1) {
						$bgcolor = "#ff8888";
						$check = "X";
					}
					$out .= "<tr bgcolor='$bgcolor'><td>$check</td><td>".$fields[$headerid]['fields'][$i]['pos']." - ".$fields[$headerid]['fields'][$i]['title']."</td></tr>";
				}
			}
			$hpos = $fields[$headerid]['pos'];
			//$out .= "<tr bgcolor='$bgcolor'><td nowrap colspan=2><textarea name='ck_notes_$hpos' cols=30 rows=3></textarea></td></tr>";
			$out .= "</table>";
			$out .= "</div>";
			$out .= "<div class='panel-footer'>";

			if (isset($notes[$headerid]) && strlen(trim($notes[$headerid]['note']))>0) $out .= "<b><em>".$notes[$headerid]['note']."</em></b>";
			$out .= "</div>";
			$out .= "</div>";
		}else{
			$out .= "</div>";
		}
		return $out;
	}

	public function pastelColors() {
        $r = dechex(round(((float) rand() / (float) getrandmax()) * 127) + 127);
        $g = dechex(round(((float) rand() / (float) getrandmax()) * 127) + 127);
        $b = dechex(round(((float) rand() / (float) getrandmax()) * 127) + 127);
        return "#" . $r . $g . $b;
    }

    public function splitReportItemsStatus($ec_fields) {
		$checkstatus = array();
		$exp1 = explode(";", $ec_fields);
		for ($i=0; $i<count($exp1); $i++) {
			$exp2 = explode(":", $exp1[$i]);
			if(sizeof($exp2) > 1){
				$checkstatus[$exp2[0]] = $exp2[1];			
			}
		}
		return $checkstatus;		
	}

	public function splitReportNotes($ec_extra, $ec_fields) {		
		$notes = array();		
		$exp1 = explode("|", $ec_extra);
		for ($i=0; $i<count($exp1); $i++) {
			$exp2 = explode("~", $exp1[$i]);
			if(sizeof($exp2) > 1 && isset($ec_fields[$i])){
				$notes[$i]['id'] = $exp2[0];
				$notes[$i]['class'] = $ec_fields[$i]['title'];
				$notes[$i]['note'] = trim($exp2[1]);
			}
		}
		return $notes;
	}

	public function getListOfItemsToCheck($fields, $checkstatus) {
		$fieldList = array();	
		$j=0;
		for ($headerid=0; $headerid<count($fields); $headerid++) {
			for ($i=0; $i<count($fields[$headerid]['fields']); $i++) {
				$id = $fields[$headerid]['fields'][$i]['pos'];
				if (isset($checkstatus[$id]) && $checkstatus[$id]==1) {
					$fieldList[$j]['id'] = $fields[$headerid]['fields'][$i]['id'];
					$fieldList[$j]['class'] = $fields[$headerid]['title'];
					$fieldList[$j]['item'] = $id." - ".$fields[$headerid]['fields'][$i]['title'];
					$j++;
				}
			}
		}	
		return $fieldList;
		
	}
}