<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

//incomming
$incom_id				= $_REQUEST['id'];

$site_user_pin			= $_REQUEST['pin'];
$call_type_id			= $_REQUEST['call_type_id'];
$phone					= $_REQUEST['phone'];
$category_id			= $_REQUEST['category_id'];
$problem_date			= $_REQUEST['problem_date'];
$call_content			= $_REQUEST['call_content'];
$category_parent_id 	= $_REQUEST['category_parent_id'];
$call_status_id			= $_REQUEST['call_status_id'];

$pay_type_id			= $_REQUEST['pay_type_id'];
$bank_id				= $_REQUEST['bank_id'];
$card_type_id			= $_REQUEST['card_type_id'];
$pay_aparat_id			= $_REQUEST['pay_aparat_id'];
$object_id				= $_REQUEST['object_id'];


// site_user
$personal_pin			= $_REQUEST['personal_pin'];
$personal_id			= $_REQUEST['personal_id'];
$personal_phone			= $_REQUEST['personal_phone'];
$mail				    = $_REQUEST['mail'];
$name				    = $_REQUEST['name'];
$friend_pin				= $_REQUEST['friend_pin'];

//task
$persons_id			    = $_REQUEST['persons_id'];
$task_type_id			= $_REQUEST['task_type_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];
$task_department_id 	= $_REQUEST['task_department_id'];
$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];


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
		$h_id				= $_REQUEST['id'];
		$page		= GetPage(Getincomming($incom_id));
		Get_sale($h_id);
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT 	realizations.id,
										realizations.id,
										realizations.Date,
										realizations.CustomerName,
										SUM(nomenclature.Sum)AS sum_sale,
										realizations.Subdivision,
										realizations.StoreHouse,
										realizations.WaybillActivationDate,
										realizations.WaybillRecieveDate,
										realizations.WaybillRecieveDate,
										realizations.WaybillNote,
										realizations.WaybillStatus
								FROM 	realizations
								JOIN 	nomenclature ON nomenclature.realizations_id=realizations.id
								GROUP BY realizations.id");
	  
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
	case 'save_incomming':
		$incom_id = $_REQUEST['id'];
		$task_type_id = $_REQUEST['task_type_id'];
		if($incom_id == ''){
			
			Addincomming( $phone,  $call_type_id, $category_id, $category_parent_id, $object_id, $pay_type_id, $bank_id, $card_type_id, $pay_aparat_id, $problem_date,  $call_content,$file,$rand_file,$hidden_inc);
			$incomming_call_id = mysql_insert_id();
			if($task_type_id != 0){
			Addtask($incomming_call_id, $persons_id, $task_type_id,  $priority_id, $task_department_id,  $comment);
			}
			Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id);
		
		}else {
			
			Saveincomming($call_type_id, $phone, $category_id, $category_parent_id, $object_id, $pay_type_id, $bank_id, $card_type_id, $pay_aparat_id,  $problem_date, $call_content,$file,$rand_file);
			
			Savetask($incom_id, $persons_id,  $task_type_id, $priority_id, $task_department_id, $comment);
			
			//Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id);
			
		}
		break;
		
	case 'get_calls':
	
		$data		= array('calls' => getCalls());
	
		break;
		
		case 'delete_file':
		
			mysql_query("DELETE FROM file WHERE id = $delete_id");
		
			$increm = mysql_query("	SELECT  `name`,
					`rand_name`,
					`id`
					FROM 	`file`
					WHERE   `incomming_call_id` = $edit_id
					");
		
			$data1 = '';
		
			while($increm_row = mysql_fetch_assoc($increm))	{
			$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
				          <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
				          <td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
						          <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
 					  </tr>';
		}
		
		$data = array('page' => $data1);
		
				break;
		
				case 'up_now':
				$user		= $_SESSION['USERID'];
				if($rand_file != ''){
				mysql_query("INSERT INTO 	`file`
				( 	`user_id`,
				`incomming_call_id`,
				`name`,
				`rand_name`
				)
				VALUES
				(	'$user',
				'$edit_id',
				'$file',
				'$rand_file'
				);");
				}
		
				$increm = mysql_query("	SELECT  `name`,
				`rand_name`,
				`id`
				FROM 	`file`
				WHERE   `incomming_call_id` = $edit_id
				");
		
				$data1 = '';
		
				while($increm_row = mysql_fetch_assoc($increm))	{
				$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
				<td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
				<td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
				          <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
						          </tr>';
		}
		
		$data = array('page' => $data1);
		
		break;
		
	case 'sub_category':
		
		$cat_id	=	$_REQUEST['cat_id'];
		$data 	= 	array('cat'=>Getcategory1($cat_id));
		
		break;	
	case 'set_task_type':
	
		$cat_id	=	$_REQUEST['cat_id'];
		$data 	= 	array('cat'=>Getbank_object($cat_id));
	
		break;
	
	case 'get_add_info':
	
		$pin	=	$_REQUEST['pin'];
		$data 	= 	array('info' => get_addition_all_info($pin));
	
		break;
		case 'get_add_info1':
		
		$personal_id	=	$_REQUEST['personal_id'];
		$data 	= 	array('info1' => get_addition_all_info1($personal_id));
	
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

function Addincomming(  $phone,  $call_type_id, $category_id, $category_parent_id, $object_id, $pay_type_id, $bank_id, $card_type_id, $pay_aparat_id, $problem_date,  $call_content,$file,$rand_file,$hidden_inc){
	
	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `incomming_call` 
			(`user_id`, `date`, `phone`, `call_type_id`, `call_category_id`, `call_subcategory_id`, `object_id`, `pay_type_id`, `bank_id`, `bank_object_id`, `card_type_id`, `pay_aparat_id`, `problem_date`, `call_content`, `actived`)
			 VALUES 
			( '$user', '$c_date', '$phone', '$call_type_id', '$category_id', '$category_parent_id', '$object_id', '$pay_type_id', '$bank_id', '', '$card_type_id', '$pay_aparat_id', '$problem_date', '$call_content', '1');");
	
	if($rand_file != ''){
		mysql_query("INSERT INTO 	`file`
		( 	`user_id`,
		`incomming_call_id`,
		`name`,
		`rand_name`
		)
		VALUES
		(	'$user',
		'$hidden_inc',
		'$file',
		'$rand_file'
		);");
	}
}

function Addtask($incomming_call_id, $persons_id, $task_type_id,  $priority_id, $task_department_id,  $comment)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO	`task` 
									(`user_id`,
									 `date`,
									 `responsible_user_id`,
									 `incomming_call_id`,
									 `task_type_id`,
									 `priority_id`,
									 `department_id`,
									 `phone`,
									 `comment`,
									 `problem_comment`)
						VALUES
									('$user',
									  NULL,
									 '$persons_id',
									 '$incomming_call_id',
									 '$task_type_id',
									 '$priority_id',
								     '$task_department_id',
								      NULL, 
								     '$comment', 
								     NULL)");
	
	
}

function Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `site_user` 	(`incomming_call_id`, `site`, `pin`, `friend_pin`, `name`, `phone`, `mail`, `personal_id`, `user`)
						           		 VALUES 
											( '$incomming_call_id', '243', '$personal_pin', '$friend_pin', '3414', 12412341, '124124124', '$personal_id', '$user')");

}
				
function Saveincomming($call_type_id, $phone, $category_id, $category_parent_id, $object_id, $pay_type_id, $bank_id, $card_type_id, $pay_aparat_id,  $problem_date, $call_content,$file,$rand_file)
{
	$incom_id	= $_REQUEST['id'];
	$user		= $_SESSION['USERID'];
	$c_date		= date('Y-m-d H:i:s');
	mysql_query("UPDATE  `incomming_call` 
				SET  
						 `user_id`				='$user', 
						 `date`					='$c_date',
						 `phone`				='$phone', 
						 `call_type_id`			='$call_type_id',
						 `call_category_id`		='$category_id',
						 `call_subcategory_id`	='$category_parent_id', 
						 `object_id`			='$object_id',
						 `pay_type_id`			='$pay_type_id',
						 `bank_id`				='$bank_id',
						`card_type_id`			='$card_type_id', 
						 `pay_aparat_id`		='$pay_aparat_id',
						 `problem_date`			='$problem_date',
						`call_content`			='$call_content',
						 `actived`				='1'
			    WHERE     `id`					='$incom_id'
							");
	

}       
function Savetask($incom_id, $persons_id,  $task_type_id, $priority_id, $task_department_id, $comment)
{

	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET  	 `user_id`='$user',
									 	 `responsible_user_id`='$persons_id',
									 	 `task_type_id`='$task_type_id',
										 `priority_id`='$priority_id', 
										 `task_department_id`='$task_department_id', 
										 `comment`='$comment' 
										  WHERE (`incomming_call_id`='$incom_id');");

}
function Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id)
{

	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE 	`site_user` 
				SET			
							`site`						='243', 
							`pin`						='$personal_pin', 
							`name`						='$name', 
							`phone`						='$personal_phone', 
							`mail`						='$mail', 
							`personal_id`				='$personal_id', 
							`user`						='$user'
							 WHERE `incomming_call_id`	='$incom_id'
							
					");

}


function Getcall_status($status)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `call_status`
						FROM 	`status`
						WHERE 	actived=1");
	

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		
		if($res['id'] == $status){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['call_status'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['call_status'] . '</option>';
		}
	}
	return $data;
}
	function Getpay_type($pay_type_id)
	{
		$data = '';
		$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`pay_type`
						WHERE 	actived=1");
	
	
		$data .= '<option value="0" selected="selected">----</option>';
		while( $res = mysql_fetch_assoc($req)){
			if($res['id'] == $pay_type_id){
				$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
			} else {
				$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
			}
		}
	
		return $data;
	}
	function Get_bank($bank_id)
	{
		$data = '';
		$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`bank`
						WHERE 	actived=1");
	
	
		$data .= '<option value="0" selected="selected">----</option>';
		while( $res = mysql_fetch_assoc($req)){
			if($res['id'] == $bank_id){
				$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
			} else {
				$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
			}
		}
	
		return $data;
	}
	
function Getcard_type($card_type_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
					FROM 	`card_type`
					WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $card_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getpay_aparat($pay_aparat_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
					FROM 	`pay_aparat`
					WHERE 	actived=1");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $pay_aparat_id){
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
	$req = mysql_query("SELECT 	`id`, `name`
					FROM 	`object`
					WHERE 	actived=1");


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
function Getcategory($category_id)

{ 			

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=0 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcategory1($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
	
}

function Getcategory1_edit($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;

}

function Getcall_type($call_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					FROM `call_type`
					WHERE actived=1");
	
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $call_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getdepartment($task_department_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `department`
					    WHERE actived=1 ");
	

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_department_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getpriority($priority_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `priority`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $priority_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Gettask_type($task_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `task_type`
					    WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getpersons($persons_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
							FROM `persons`
							WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $persons_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Get_sale($h_id)
{
	
	$req = mysql_query("SELECT	nomenclature.id as nomec_id,
													nomenclature.NomenclatureName,
													nomenclature.Sum
											from	nomenclature
											WHERE 	nomenclature.realizations_id = $h_id
			" );

$data.='	<fieldset>
	<fieldset>
					<legend>შენაძენი</legend> 
					<table style="float: left; border: 1px solid #85b1de; width: 100%; text-align: center;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">#</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ნომენკლატურა</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ფასი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">სხვა</td>
						</tr>';						 
						while( $res1 = mysql_fetch_assoc($req)){
						$data .='
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['nomec_id']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['NomenclatureName']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['Sum']. '</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">' . $res1['']. '</td>							
						</tr>
							';
						};						
						$data .='	
	
	
					<table/>
				</fieldset>
								';
						return $data; 
}

function Getincomming($incom_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	realizations.id,
												realizations.id,
												realizations.Date,
												realizations.CustomerName,
												realizations.Subdivision,
												realizations.StoreHouse,
												realizations.WaybillActivationDate,
												realizations.WaybillRecieveDate,
												realizations.WaybillRecieveDate,
												realizations.WaybillNote,
												realizations.WaybillStatus,
												realizations.CustomerID,
												realizations.CustomerName,
												realizations.CustomerAddress,
												realizations.CustomerPhone,		
												CASE WHEN SUM(`nomenclature`.`Sum`)>=5000 
														AND
														SUM(`nomenclature`.`Sum`)<7000
														THEN 'VIP Gold'
													 WHEN SUM(`nomenclature`.`Sum`)>=7000 
														AND
														SUM(`nomenclature`.`Sum`)<10000
														THEN 'VIP Platinium'
													WHEN SUM(`nomenclature`.`Sum`)>10000 
														THEN 'VIP Briliant'
													WHEN SUM(`nomenclature`.`Sum`)<5000 
														THEN 'ლოიალური'
												END AS `status`
								FROM 	realizations
										JOIN nomenclature ON nomenclature.realizations_id=realizations.id
										WHERE realizations.id = $incom_id
													" ));
	
	return $res;
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
	
	$data  .= '
	<!-- jQuery Dialog -->
    <div id="add-edit-goods-form" title="საქონელი">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 400px;">	
				<fieldset >
			    	<legend>ძირითადი ინფორმაცია</legend>
					<fieldset style="width:300px; float:left;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>ზედნადების #</td>
								<td>
									<input type="text" id="id" class="idle" onblur="this.className=\'idle\'" value="' . $res['id']. '"/>
								</td>
							</tr>
							<tr>
								<td>ქვე-განყოფილება</td>
								<td>
									<input type="text" id="Subdivision" class="idle" onblur="this.className=\'idle\'" value="' . $res['Subdivision']. '"/>
								</td>
							</tr>
							<tr>
								<td>საწყობი</td>
								<td>
									<input type="text" id="CustomerName" class="idle" onblur="this.className=\'idle\'" value="' . $res['CustomerName']. '"/>
								</td>
							</tr>	
							<tr>
								<td>სხვა</td>
								<td>
									<input type="text" id="id" class="idle" onblur="this.className=\'idle\'" value="' . $res['id']. '"/>
								</td>
							</tr>					
						</table>
					</fieldset>
					<fieldset style="width:300px; float:left; margin-left: 10px;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>შეძენის თარიღი</td>
								<td>
									<input type="text" id="Date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['Date']. '"/>
								</td>
							</tr>
							<tr>
								<td>მიტანის თარიღი</td>
								<td>
									<input type="text" id="WaybillRecieveDate" class="idle" onblur="this.className=\'idle\'" value="' . $res['WaybillRecieveDate']. '"/>
								</td>
							</tr>
							<tr>
								<td>მონტაჟის თარიღი</td>
								<td>
									<input type="text" id="id" class="idle" onblur="this.className=\'idle\'"  value="' . $res['id']. '"/>
								</td>
							</tr>
							<tr>
								<td>სტატუსი</td>
								<td>
									<input type="text" id="WaybillStatus" class="idle" onblur="this.className=\'idle\'" value="' . $res['WaybillStatus']. '"/>
								</td>
							</tr>						
						</table>
					</fieldset>						
											';
												
		$data  .= '
		   
				<fieldset style="margin-top: 5px;">
			    	<legend>დავალების ფორმირება</legend>
		
			    	<table class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="d_number">დავალების ტიპი</label></td>
							<td style="width: 180px;"><label for="d_number">სცენარი</label></td>
							<td style="width: 180px;"><label for="d_number">პრიორიტეტი</label></td>
						</tr>
			    		<tr>
							<td style="width: 180px;" id="task_type_change"><select id="task_type_id" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="task_department_id" class="idls object">'. Getdepartment($res['task_department_id']).'</select></td>
							<td style="width: 180px;"><select id="persons_id" class="idls object">'.Getpersons($res['persons_id']).'</select></td>
						</tr>
						<tr>
							<td style="width: 150px;"><label for="content">კომენტარი</label></td>
							<td style="width: 150px;"><label for="content"></label></td>
							<td style="width: 150px;"><label for="content"></label></td>
						</tr>
						<tr>
							<td colspan="6">
								<textarea  style="width: 627px; height: 80px; resize: none;" id="comment" class="idle" name="content" cols="300" rows="2">' . $res['comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div>
				  </fieldset>
			</div>
			<div style="float: right;  width: 355px;">
				 <fieldset>
					<legend>კონტრაგენტი</legend>
					<table style="height: 163px;">						
						<tr>
							<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
							<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
						</tr>
						<tr>
							<td style="width: 180px; color: #3C7FB1;">'.$res['CustomerPhone'].'</td>
							<td style="width: 180px; color: #3C7FB1;">'.$res['CustomerID'].'</td>	
						</tr>
						<tr>
							<td style="width: 180px; color: #3C7FB1;">სახელი და გვარი</td>
							<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
						</tr>
						<tr >
							<td style="width: 180px;">'.$res['CustomerName'].'</td>
							<td style="width: 180px;">'.$res['CustomerAddress'].'</td>			
						</tr>
						<tr>
							<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
							<td td style="width: 180px; color: #3C7FB1;">სტატუსი</td>
						</tr>
						<tr>
							<td style="width: 180px;">'.$res['CustomerAddress'].'</td>
							<td td style="width: 180px;">'.$res['status'].'</td>
						</tr>
					</table>
				</fieldset>
				<div id="additional_info">
	  		</div>';
		
				$data.=	Get_sale($res['id']);
						$data .='	
					<table/>
	  				<table style="float: left; width: 100%; text-align: center;">
	  					<tr>
	  						<td style="width: 10%;"></td>
	  						<td style="text-align: right; width: 49%;">ჯამი</td>
	  						<td style="width: 20%;"></td>
	  						<td style="width: 20%;"></td>
	  					</tr>
	  				<table/>
			</div>
	  		<input type="hidden" id="h_id" value="'.$res['id'].'"/>
    </div>';

	return $data;
}

?>