<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
//include('../../../includes/classes/log.class.php');
//$log 		= new log();
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

//action1
$action_detail_id	= $_REQUEST['id'];
$production_id		= $_REQUEST['production_id'];
$object_id			= $_REQUEST['object_id'];
$price				= $_REQUEST['price'];
$date    			= $_REQUEST['date'];
$action_id    		= $_REQUEST['action_id'];


switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		mysql_query("			UPDATE `action`
									    SET `actived`=0
										WHERE `id`=$action_id ");
		break;
	case 'get_edit_page':
		$page		= GetPage(Getaction_1($action_detail_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count 			= $_REQUEST['count'];
		$hidden		 	= $_REQUEST['hidden'];
		$action_idd   	= $_REQUEST['action_idd'];
	  	$rResult = mysql_query("	SELECT  
											action_detail.id,
											object.`name`,
											action_detail.date,
											production.`name`,
											action_detail.price
									FROM 	action_detail
									JOIN 	object ON action_detail.object_id=object.id
									JOIN    production ON action_detail.production_id= production.id
									WHERE   action_detail.actived =1 AND action_detail.action_id=$action_idd");
												  
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
	case 'save_action_1':
		
		if($action_detail_id == ''){
			
			Addaction_1($action_id, $object_id,  $date, $production_id,   $price );
			
			
		}else {
			
			saveaction_1( $action_detail_id,  $object_id, $date, $production_id,  $price);
			
			
		}
		break;
		
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

function Addaction_1($action_id, $object_id,  $date, $production_id,   $price ){
	
	$user		= $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `action_detail` 
							( `user_id`, `action_id`, `object_id`, `date`, `production_id`, `price`, `actived`)
						VALUES 
							( '$user', '$action_id', '$object_id', '$date', '$production_id', '$price', '1')
								");
	
	///GLOBAL $log;
	///$log->setInsertLog('action_detail');
}
				
function saveaction_1( $action_detail_id,  $object_id, $date, $production_id,  $price)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('action_detail', $action_detail_id);
	$user		= $_SESSION['USERID'];
	mysql_query("UPDATE `action_detail` 
					SET 
						`object_id`='$object_id', 
						`date`='$date', 
						`production_id`='$production_id', 
						`price`='$price'
						WHERE action_detail.`id`='$action_detail_id'");
	
	//$log->setInsertLog('action_detail',$action_detail_id);
}  
     
function Getproduction($production_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `production`
					    WHERE actived=1 ");
	

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getobject($object_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `object`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getaction_1($action_detail_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	action_detail.id,
												action_detail.production_id AS production_id,
												action_detail.object_id AS object_id,
												action_detail.price AS price,
												action_detail.date AS date
										from  	action_detail
										WHERE 	action_detail.id=$action_detail_id
									" ));
	
	return $res;
}
function GetLocalID(){
	GLOBAL $db;
	return $db->increment('action_detail');
}
function GetPage($res='', $number)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}
	
	$data  .= '
	<div id="dialog-form">
		<fieldset>
    		<legend>საკონტაქტო ინფორმაცია</legend>
    		<table class="dialog-form-table">
    			<tr>
    				<td style="width: 170px;"><label for="trans_obj">ფილიალები</label></td>
    				<td style="width: 180px;"><select id="object_id" class="idls object">'.Getobject($res['object_id']).'</select></td>
    			</tr>
    			<tr>
    				<td style="width: 170px;"><label for="trans_address">თარიღი</label></td>
    				<td><input type="text" id="date" class="idle " onblur="this.className=\'idle \'" onfocus="this.className=\'activeField \'" value="' . $res['date'] . '" /></td>
    			</tr>
    			<tr>
    				<td style="width: 170px;"><label for="trans_obj">პროდუქტი</label></td>
    				<td style="width: 180px;"><select id="production_id" class="idls object">'.  Getproduction($res['production_id']).'</select></td>
    			</tr>
    			<tr>
    				<td style="width: 170px;"><label for="trans_address">თანხა</label></td>
    				<td><input type="text" id="price" class="idle " onblur="this.className=\'idle \'" onfocus="this.className=\'activeField \'" value="' . $res['price'] . '" /></td>
    			</tr>
    		</table>
    	</fieldset>
    	</div>
    <!-- ID -->
	<input style="display: none;"  id="id" value="' . $res['id'] . '" />';

	return $data;
}

?>