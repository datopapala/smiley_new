<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
include('../../../includes/classes/log.class.php');

$log 		= new log();
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

//incomming
$client_id				= $_REQUEST['id'];
$id_g					= $_REQUEST['id_g'];
$gift_date				= $_REQUEST['gift_date'];
$gift_date1				= $_REQUEST['gift_date1'];
$gift_date2				= $_REQUEST['gift_date2'];
$gift_date3				= $_REQUEST['gift_date3'];
$gift_date4				= $_REQUEST['gift_date4'];
$client_gift			= $_REQUEST['id1'];
$gift_production_id		= $_REQUEST['gift_production_id'];
$gift_price				= $_REQUEST['gift_price'];


switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		$incom_id				= $_REQUEST['id'];
		mysql_query("			UPDATE `incomming_call`
									    SET `actived`=0
										WHERE `id`=$incom_id ");
		break;
	case 'get_edit_page':
		$page		= GetPage(Getclient_gift($client_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT 	clinet_gift.id,
										clinet_gift.date,
	  									production.`name`,
										clinet_gift.price
								FROM    clinet_gift
								JOIN    production ON  clinet_gift.production_id=production.id
								WHERE	clinet_gift.actived=1 AND clinet_gift.client_id=$client_id");
							  
		$data = array(
				"aaData"	=> array()
		);

		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = $aRow[$i];
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'save_client_gift':
		$gift_date				= $_REQUEST['gift_date'];
		$gift_date1				= $_REQUEST['gift_date1'];
		$gift_date2				= $_REQUEST['gift_date2'];
		$gift_date3				= $_REQUEST['gift_date3'];
		$gift_date4				= $_REQUEST['gift_date4'];
		$date='';
		if($gift_date!=''){
			$date=$gift_date;
		}elseif ($gift_date1!=''){
			$date=$gift_date1;
		}elseif ($gift_date2!=''){
			$date=$gift_date2;
		}
		elseif($gift_date3!=''){
			$date=$gift_date3;
		}elseif($gift_date4!=''){
			$date=$gift_date4;
		}
		
	$client_gift			= $_REQUEST['id1'];
		if($id_g == ''){			
			Addclient_gift($client_gift, $date, $gift_production_id, $gift_price);
			$incomming_call_id = mysql_insert_id();
		}else {
			
			Saveclient_gift($date, $gift_production_id, $gift_price);
		}
		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
* ******************************
*/

function Addclient_gift($client_gift, $date, $gift_production_id, $gift_price){
	
	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];
	
	
	mysql_query("INSERT INTO `clinet_gift`
									(`user_id`, `client_id`, `date`, `production_id`, `price`, `actived`) 
									VALUES 
									('$user', '$client_gift', '$date', '$gift_production_id', '$gift_price', '1');");
	//GLOBAL $log;
	//$log->setInsertLog('clinet_gift');
}


				
function Saveclient_gift($date, $gift_production_id, $gift_price)
{
	
	$user		= $_SESSION['USERID'];
	$c_date		= date('Y-m-d H:i:s');
	
	//GLOBAL $log;
	//$log->setUpdateLogAfter('clinet_gift', $_REQUEST['id_g']);
	mysql_query("	UPDATE `clinet_gift` SET 
											`user_id`		='$user', 
											`date`			='$date', 
											`production_id`	='$gift_production_id', 
											`price`			='$gift_price', 
											`actived`='1' 
					WHERE					`id`			='".$_REQUEST['id_g']."'
			");
	
	//$log->setInsertLog('clinet_gift',$_REQUEST['id_g']);
}       



function Get_production($gift_production_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`production`
						WHERE 	actived=1");
	

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		
		if($res['id'] == $gift_production_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}
	return $data;
}

function Getclient_gift($client_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 
												clinet_gift.id,
												clinet_gift.date AS gift_date,
												clinet_gift.production_id AS gift_production_id,
												clinet_gift.price AS gift_price
										FROM    clinet_gift
										WHERE	clinet_gift.id=$client_id
											" ));
	
	return $res;
}
function GetLocalID(){
	GLOBAL $db;
	return $db->increment('clinet_gift');
}

function GetPage($res='', $number)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}

	$increm = mysql_query("	SELECT  `name`,
									`rand_name`
							FROM 	`file`
							WHERE   `incomming_call_id` = $res[id]
			");
	
	$tb				= $_REQUEST['tb'];
	$gift_d = '';
	if($tb == 1){
		$gift_d = "gift_date";
	}elseif ($tb == 2){
		$gift_d = "gift_date1";
	}elseif ($tb == 3){
		$gift_d = "gift_date2";
	}elseif ($tb == 4){
		$gift_d = "gift_date3";
	}elseif ($tb == 5){
		$gift_d = "gift_date4";
	}
	
	
	$data  .= '
	<!-- jQuery Dialog -->
    <div id="add-edit-goods-form" title="საქონელი">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 375px;">	
				<fieldset style= width: 200px;" >
			    	<legend>საჩუქარი</legend>
		
			    	<table width="100%" class="dialog-form-table">		
							<td style="width: 180px;"><label for="">თარიღი</label></td>
							<td style="width: 180px;">
								<input type="text" id="'.$gift_d.'" class="idle" onblur="this.className=\'idle\'" value="' .  $res['gift_date']. '" />
							</td>
						</tr>
						<tr>
							<td style="width: 180px;"><label for="phone">პროდუქტი</label></td>
							
							<td style="width: 180px;"><select id="gift_production_id" class="idls object">'.Get_production($res['gift_production_id']).'</select></td>
						</tr>
						<tr>
							<td><label for="person_name">თანხა</label></td>
							<td style="width: 69px;">
								<input type="text" id="gift_price" class="idle" onblur="this.className=\'idle\'"  value="' . $res['gift_price'] . '" />
							</td>
						</tr>						
					</table>
				</fieldset >
								<input type="hidden" id="id_g" value="'.$_REQUEST['id'].'"/>	
			 </div>';

	return $data;
}

?>