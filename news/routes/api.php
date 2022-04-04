<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/Login', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;
	if ($api_key == "SCHULTESKEY009911") {
		$r = DB::table('employees')->leftJoin('schultes','Schultes.sc_id','=','employees.us_sc_id')
					->where('login',$Email)
					->where('password',$Password)->first();

		if ($r != null) {
			$toEncode["firstname"] = $r->firstname;
			$toEncode["lastname"] = $r->lastname;
			$toEncode["sc_id"] = $r->us_sc_id;
			$toEncode["department"] = $r->departmentNr;
			$toEncode["calctype"] = $r->sc_calctype;
			$toEncode["ID"] = $r->us_id;
			return response($toEncode,200);
		} else {
			$toEncode["Error"] = "Wrong username or password";
			$toEncode["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});

Route::post('/changePassword', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;
	$newPassword = $req->newPassword;
	if ($api_key == "SCHULTESKEY009911") {
		$Email = str_replace("%","", $Email);			
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();

		if ($r != null) {
			$res = DB::table('employees')->where('login',$Email)->update(['password' => $newPassword]);
			if ($result){
				$toEncode["Status"] = "1";
			} else {
				$toEncode["Status"] = "0";
			}
			return response($toEncode,200);
		} else {
			$toEncode["Status"] = "0";
			return response($toEncode,200);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});

Route::post('/jobsList', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;		
	if ($api_key == "SCHULTESKEY009911") {
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();

		if ($r != null) {
			$sc_id = $r->us_sc_id;
			$res = DB::table('jobs')->where('jb_sc_id',$sc_id)->where('jstatus',0)->get();				
			$toEncode = array();
			$x=0;
			foreach ($res as $key => $r) {
				$toEncode[$x]["jid"] = $r->jid;
				$toEncode[$x]["company"] = (utf8_encode( ($r->company)));
				$toEncode[$x]["nr"] = $r->nr;
				$toEncode[$x]["padmin"] = $r->padmin;
				$toEncode[$x]["dateopen"] = $r->dateopen;
				$toEncode[$x]["description"] = (utf8_encode( ($r->description)));
				$toEncode[$x]["jpaytype"] = $r->jpaytype;
				$x++;
			}
			return response($toEncode,200);
		} else {
			$toEncode[0]["Error"] = "Wrong username or password";
			$toEncode[0]["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode[0]["Error"] = "Wrong credentials";
		$toEncode[0]["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});
	
Route::post('/saveCheck', function(Request $req) {
	$orig = base64_encode($req->getBody());

	$inp = json_decode($req->getBody());

	$Email=$inp->Email;

	$Password=$inp->Password;
	$api_key = $inp->ApiKey;
	$dataCheck  = $inp->dataCheck;

	if ($api_key == "SCHULTESKEY009911") {
		$toEncode = array();
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();
		if ($r != null) {
			$us_id = $r->us_id;
			$sc_id=$r->us_sc_id;
			$data = array(
				'ec_eq_id'	=> $dataCheck->eqid,
				'ec_date'	=> date('Y-m-d'),
				'ec_us_id'	=> $us_id,
				'ec_ins_mai_rep'	=> 0,
				'ec_notes'	=> $dataCheck->mainNote,
				'ec_fields'	=> $dataCheck->check,
				'ec_extra'	=> $dataCheck->notes,
				'ec_status'	=> 0,
			);
			$ckid = DB::table('equipcheck')->insertGetId($data);
							
			if ($dataCheck->euid==0) {
				// $qry="insert into equipusers values (".$dataCheck->euid.",$us_id, ".$dataCheck->eqid.", NOW(),NOW(),$ckid,0,'".$dataCheck->startDate."','".$dataCheck->endDate."','".$dataCheck->lat."','".$dataCheck->lon."','0','0','".$dataCheck->startMiles."','0','".$dataCheck->startHours."','0','1','0')";
				
				$data = array(
					'eu_us_id' => $us_id,
					'eu_eq_id' => $dataCheck->eqid,
					'eu_real_start' => date('Y-m-d H:i:s'),
					'eu_real_end' => date('Y-m-d H:i:s'),
					'eu_ec_start' => $ckid,
					'eu_ec_end' => 0,
					'eu_start' => $dataCheck->startDate,
					'eu_end' => $dataCheck->endDate,
					'eu_start_lat' => $dataCheck->lat,
					'eu_start_lon' => $dataCheck->lon,
					'eu_end_lat' => 0,
					'eu_end_lon' => 0,
					'eu_miles_start' => $dataCheck->startMiles,
					'eu_miles_end' => 0,
					'eu_nrhoursstart' => $dataCheck->startHours,
					'eu_nrhoursend' => 0,
					'eu_onsite' => 1,
					'eu_status' => 0
				);
				$res2 = DB::table('equipusers')->insert($data);
			} else {
				
				$res = $db->query("select * from equipusers where eu_id = '".$dataCheck->euid."' ");
				if ($r=$res->fetch_object()) {
					$eu_real_start = $r->eu_real_start;
					$eu_start = $r->eu_start;
					$eu_ec_start = $r->eu_ec_start;
					$startLon = $r->eu_start_lon;
  				    $startLat = $r->eu_start_lat;
				}
				$qry="replace into equipusers values (".$dataCheck->euid.",$us_id, ".$dataCheck->eqid.", '".$eu_real_start."',NOW(),$eu_ec_start,$ckid,'".$eu_start."','".$dataCheck->endDate."','".$startLat."','".$startLon."','".$dataCheck->lat."','".$dataCheck->lon."','".$dataCheck->startMiles."','".$dataCheck->endMiles."','".$dataCheck->startHours."','".$dataCheck->endHours."','".$dataCheck->endOnSite."','1')";

				$data = array(
					'eu_us_id' => $us_id,
					'eu_eq_id' => $dataCheck->eqid,
					'eu_real_start' => $eu_real_start,
					'eu_real_end' => date('Y-m-d H:i:s'),
					'eu_ec_start' => $eu_ec_start,
					'eu_ec_end' => $ckid,
					'eu_start' => $eu_start,
					'eu_end' => $dataCheck->endDate,
					'eu_start_lat' => $startLat,
					'eu_start_lon' => $startLon,
					'eu_end_lat' => $dataCheck->lat,
					'eu_end_lon' => $dataCheck->lon,
					'eu_miles_start' => $dataCheck->startMiles,
					'eu_miles_end' => $dataCheck->endMiles,
					'eu_nrhoursstart' => $dataCheck->startHours,
					'eu_nrhoursend' => $dataCheck->endHours,
					'eu_onsite' => $dataCheck->endOnSite,
					'eu_status' => 1
				);
				$res2 = DB::table('equipusers')->where('eu_id',$dataCheck->euid)->update($data);
			}
			$toEncode["status"]="1";
			return response($toEncode,200);
		} else {
			$toEncode[0]["Error"] = "Wrong username or password";
			$toEncode[0]["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode[0]["Error"] = "Wrong credentials";
		$toEncode[0]["ErrorNr"] = "400";
		return response($toEncode,400);

	}
});
Route::post('/endDay', function(Request $req) {
	//error_log(print_r($req->getBody(), true));
	
	$orig = base64_encode($req->getBody());
	
	$inp = json_decode($req->getBody());
	
	$Email=$inp->Email;

	$Password=$inp->Password;
	$api_key = $inp->ApiKey;
	$workday  = $inp->workday;	

	if ($api_key == "SCHULTESKEY009911") {
		$toEncode = array();		
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();
		if ($r != null) {
			$sc_id=$r->us_sc_id;
			if ($workday->wkd_shift_work > 1) $workday->wkd_shift_work = $workday->wkd_shift_work -1;
			$data = array(
				'wkd_sc_id' => $sc_id,
                'wkd_us_id' => $workday->us_id,
                'wkd_driller_helper' => $workday->wkd_driller_helper,
                'wkd_truck_driver' => $workday->wkd_truck_driver,
                'wkd_liveexp' => $workday->wkd_live_exp,
                'wkd_lunch' => $workday->wkd_lunch,
                'wkd_lunchtime' => $workday->wkd_lunchtime,
                'wkd_miles' => $workday->wkd_miles,
                'wkd_day' => $workday->wkd_day,
                'wkd_shift_work' => $workday->wkd_shift_work,
                'wkd_end_realtime' => $workday->wkd_end_realtime,
                'wkd_gps_latitude' => $workday->wkd_gps_latitude,
                'wkd_gps_longitude' => $workday->wkd_gps_longitude,
                'wkd_timestamp' => date('Y-m-d H:i:s'),
                'wkd_status' => 0,                    
                'wkd_notes' => $workday->wkd_notes,
                'wkd_recalctime' => '0000-00-00 00:00:00',
                'wkd_locked' => 0,
			);
			$wkd_id = DB::table('workdays')->insertGetId($data);
					
			for ($i=0; $i<count($workday->hours); $i++) {
				$hour = $workday->hours[$i];
				$data = array(
					'hrs_jobid' => $hour->hrs_id,
                    'hrs_starttime' =>$hour->hrs_starttime,
                    'hrs_endtime' => $hour->hrs_endtime,
                    'hrs_realtime' => $hour->hrs_realtime,
                    'hrs_truckdriver' => 0,
                    'hrs_gps_start_lat' => $hour->hrs_gps_latitude,
                    'hrs_gps_start_lon' => $hour->hrs_gps_longitude,
                    'hrs_gps_end_lat' => 0,
                    'hrs_gps_end_lon' => 0,
                    'hrs_regular' => 0,
                    'hrs_ovt' => 0,
                    'hrs_double' => 0,
                    'hrs_status' => $hour->hrs_status,
                    'hrs_wkd_id' => $wkd_id,
				);
				$res = DB::table('hours')->insert($data);		
			}
			$data = array(
				'wk_wkd_id'	=> $wkd_id,
				'wl_us_id'	=> $workday->us_id,
				'wl_day'	=> $workday->wkd_day,
				'wl_src'	=> $orig,
			);
			$res = DB::table('worklog')->insert($data);
			$toEncode["status"]="1";				
			return response($toEncode,200);
		} else {
			$toEncode[0]["Error"] = "Wrong username or password";
			$toEncode[0]["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode[0]["Error"] = "Wrong credentials";
		$toEncode[0]["ErrorNr"] = "400";
		return response($toEncode,400);
	}	
});

Route::post('/getLog', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;
	if ($api_key == "SCHULTESKEY009911") {
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();

		if ($r != null) {
			$us_id=$r->us_id;
			//$res = $db->query("select * from worklog where wl_us_id=$us_id order by wl_day desc limit 10");
			$res = DB::table('worklog')->where('wl_us_id',$us_id)->orderBy('wl_day','desc')->limit(10)->get();
			$toEncode = array();
			$x=0;
			foreach ($res as $key => $r) {
				$toEncode[$x]["wl_day"] = $r->wl_day;
				$toEncode[$x]["wl_src"] = $r->wl_src;
				$x++;
			}
			return response($toEncode,200);
		} else {
			$toEncode["Error"] = "Wrong username or password";
			$toEncode["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});
	
Route::post('/getMyEquipments', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;

	if ($api_key == "SCHULTESKEY009911") {
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();

		if ($r != null) {
			$scid = $r->us_sc_id;
			//$qry="select * from equipusers,equipments,equiptypes,employees where login = '$Email' and password = '$Password' and us_sc_id=eq_sc_id and et_id=eq_et_id and eu_us_id=us_id and eu_ec_end=0 and eq_id=eu_eq_id and eq_et_id>0  order by et_title, eq_internalcode, eq_name  desc ";			
			//error_log($qry);
			$res = DB::table('equipusers')
						->leftJoin('equipments','equipments.eq_id','=','equipusers.eu_eq_id')
						->leftJoin('equiptypes','equipments.eq_et_id','=','equiptypes.et_id')
						->leftJoin('employees','employees.us_id','=','equipusers.eu_us_id')
						->whereRaw('us_sc_id = eq_sc_id')
						->where('login',$Email)->where('password',$Password)
						->where('eu_ec_end',0)->where('eq_et_id','>',0)
						->orderBy('et_title')->orderBy('eq_internalcode')->orderBy('eq_name','desc')->get();
			$i = 0;
			$toEncode = array();
			
			foreach ($res as $key => $r) {
				$toEncode[$i]['et_id'] = $r->et_id;
				$toEncode[$i]['eq_id'] = $r->eq_id;
				$toEncode[$i]['et_title'] = $r->et_title;
				$toEncode[$i]['eq_name'] = $r->eq_name;
				$toEncode[$i]['eq_internalcode'] = $r->eq_internalcode;
				$toEncode[$i]['et_checklist'] = $r->et_checklist;
				$toEncode[$i]['eu_id'] = $r->eu_id;
				$toEncode[$i]['eu_ec_start'] = $r->eu_ec_start;
				$toEncode[$i]['eu_ec_end'] = $r->eu_ec_end;
				$toEncode[$i]['eu_start'] = $r->eu_start;
				$toEncode[$i]['eu_end'] = $r->eu_end;
				
				$toEncode[$i]['eu_start_lat'] = $r->eu_start_lat;
				$toEncode[$i]['eu_start_lon'] = $r->eu_start_lon;
				$toEncode[$i]['eu_end_lat'] = $r->eu_end_lat;
				$toEncode[$i]['eu_end_lon'] = $r->eu_end;
				$toEncode[$i]['eu_miles_start'] = $r->eu_miles_start;
				$toEncode[$i]['eu_miles_end'] = $r->eu_miles_end;
				$toEncode[$i]['eu_nrhoursstart'] = $r->eu_nrhoursstart;
				$toEncode[$i]['eu_nrhoursend'] = $r->eu_nrhoursend;
				$toEncode[$i]['eu_onsite'] = $r->eu_onsite;
				$toEncode[$i]['eu_status'] = $r->eu_status;				
				$i++;
			}
			return response($toEncode,200);
		} else {
			$toEncode["Error"] = "Wrong username or password";
			$toEncode["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});
	 
Route::post('/getCheck', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;
	$ec_id = $req->ec_id;
	if ($api_key == "SCHULTESKEY009911") {
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();

		if ($r != null) {
			//$qry="select * from equipcheck where ec_id=$ec_id ";			
			$r = DB::table('equipcheck')->where('ec_id',$ec_id)->first();
			$i = 0;
			$toEncode = array();			
			if ($r != null) {
				$toEncode[0]['ec_id'] = $r->ec_id;
				$toEncode[0]['ec_notes'] = $r->ec_notes;
				$toEncode[0]['ec_fields'] = $r->ec_fields;
				$toEncode[0]['ec_extra'] = $r->ec_extra;
				$i++;
			}
			return response($toEncode,200);
		} else {
			$toEncode["Error"] = "Wrong username or password";
			$toEncode["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});
	

Route::post('/getQr', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;
	if ($api_key == "SCHULTESKEY009911") {
		$eqID = $req->eqID;
		//$res = $db->query("select * from equipments,equiptypes,employees where login = '$Email' and password = '$Password' and us_sc_id=eq_sc_id and et_id=eq_et_id and eq_id = '$eqID' and eq_et_id>0 ");
		$r = DB::table('equipments')
					->leftJoin('equiptypes','equipments.eq_et_id','=','equiptypes.et_id')
					->leftJoin('employees','employees.us_sc_id','=','equipments.eq_sc_id')
					->where('login',$Email)->where('password',$Password)
					->where('eq_id',$eqID)->where('eq_et_id','>',0)->first();
		if ($r != null) {
			$toEncode = array();
			$x=0;
			$toEncode[0]['eq_id'] = $r->eq_id;
			$toEncode[0]['eq_internalcode'] = $r->eq_internalcode;
			$toEncode[0]['eq_name'] = $r->eq_name;
			$toEncode[0]['et_title'] = $r->et_title;
			
			$r = DB::table('equipusers')->where('eu_eq_id',$eqID)->orderBy('eu_end','desc')->first();
			if ($r != null) {
				$toEncode[0]['eu_miles'] = $r->eu_miles;	
			} else {
				$toEncode[0]['eu_miles'] = 0;				
			}			
			return response($toEncode,200);
		} else {
			$toEncode["Error"] = "Wrong username or password";
			$toEncode["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});

Route::post('/getInspectionReport', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;
	$checkType = $req->checkType;
	if ($api_key == "SCHULTESKEY009911") {
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();

		if ($r != null) {
			$fields = array();
			//$qry="select * from equipmodel1 where eqm1_et_checklist = $checkType order by eqm1_pos";			
			$res = DB::table('equipmodel1')->where('eqm1_et_checklist',$checkType)->orderBy('eqm1_pos')->get();
			$i = 0;
			foreach ($res as $key => $r) {
				$fields[$i]['id'] = $r->eqm1_id;
				$fields[$i]['pos'] = $r->eqm1_pos;
				$fields[$i]['title'] = $r->eqm1_title;
				$fields[$i]['fields'] = array();
				//$qry="select * from equipmodel2 where eqm2_eqm1_id = $r->eqm1_id order by eqm2_pos";
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
			return response($toEncode,200);			
		} else {
			$toEncode["Error"] = "Wrong username or password";
			$toEncode["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});
	
Route::post('/getEquipmentList', function(Request $req) {
	$Email=$req->Email;
	$Password=$req->Password;
	$api_key = $req->ApiKey;
	if ($api_key == "SCHULTESKEY009911") {
		$r = DB::table('employees')->where('login',$Email)->where('password',$Password)->first();

		if ($r != null) {
			$scid = $r->us_sc_id;
			$qry="select et_title, et_checklist, eq_internalcode, eq_name, eq_id, et_id from (equipments, equiptypes) 
			left join equipusers on eu_eq_id=eq_id and eu_status=0
			where eq_sc_id = $scid and et_id=eq_et_id and eu_id is NULL  and eq_et_id>0  order by et_title, eq_internalcode, eq_name  ";
			$res = DB::table('equipments')
					->select('et_title', 'et_checklist', 'eq_internalcode', 'eq_name', 'eq_id', 'et_id')
					->leftJoin('equiptypes','equipments.eq_et_id','=','equiptypes.et_id')
					->leftJoin('equipusers','equipusers.eu_eq_id','=','equipments.eq_id')
					->where('eu_start',0)->where('eq_sc_id',$scid)
					->whereNull('eu_id')->where('eq_et_id','>',0)
					->orderBy('et_title')->orderBy('eq_internalcode')->orderBy('eq_name')->get();
			$i = 0;
			$toEncode = array();
			foreach ($res as $key => $r) {
				$toEncode[$i]['et_id'] = $r->et_id;
				$toEncode[$i]['eq_id'] = $r->eq_id;
				$toEncode[$i]['et_title'] = $r->et_title;
				$toEncode[$i]['eq_name'] = $r->eq_name;
				$toEncode[$i]['eq_internalcode'] = $r->eq_internalcode;
				$toEncode[$i]['et_checklist'] = $r->et_checklist;
				$i++;
			}
			return response($toEncode,200);
		} else {
			$toEncode["Error"] = "Wrong username or password";
			$toEncode["ErrorNr"] = "401";
			return response($toEncode,401);
		}
	} else {
		$toEncode["Error"] = "Wrong credentials";
		$toEncode["ErrorNr"] = "400";
		return response($toEncode,400);
	}
});
