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


//task
$id		    		= $_REQUEST['id'];
$incom_id			= $_REQUEST['id'];
$person_id			= $_REQUEST['person_id'];
$problem_comment	= $_REQUEST['problem_comment'];
$comment 	     	= $_REQUEST['comment1'];
$priority_id 		= $_REQUEST['priority_id'];
$task_status 		= $_REQUEST['task_status'];
$template_id		= $_REQUEST['template_id'];
$person_id			= $_REQUEST['person_id'];
$task_type_id		= $_REQUEST['task_type_id'];
$task_date			= $_REQUEST['task_date'];
$task_status				= $_REQUEST['status'];



// file
$rand_file			= $_REQUEST['rand_file'];
$file				= $_REQUEST['file_name'];


switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		$data		= array('page'	=> $page);

		break;
	
	case 'get_edit_page':
		$page		= GetPage(Gettask($incom_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
		$user_id	= $_SESSION['USERID'];
  		$rResult = mysql_query("SELECT 	 	`task`.id,
											`task`.id,
											`users`.`username`,
											`person1`.`name` ,
											`person2`.`name` ,
											task.date
											
								FROM 		`task`			
								JOIN    users  ON task.user_id=users.id
								JOIN 		users AS `user1`			ON task.responsible_user_id=user1.id
								JOIN 		persons AS `person1`	ON user1.person_id=person1.id
								
								JOIN 		users AS `user2`			ON task.user_id=user2.id
								left JOIN 		persons AS `person2`	ON user2.person_id=person2.id
								WHERE 		task.actived=1 AND task.`task_type_id` =1 AND task.status=0  AND task.user_id=$user_id
									
	  			");
	  
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
				if($i == ($count - 1)){
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'save_incomming':
		$id		 = $_REQUEST['id'];
		if($id == ''){
			
			Addtask($person_id,  $template_id, $task_type_id, $priority_id, $comment, $problem_comment, $task_status);
					
			$task_id = mysql_insert_id();
			//if($personal_pin != 0){
			//Addsite_user($task_id, $personal_pin, $name, $personal_phone, $mail, $personal_id);
			//}
		}else {
			savetask($id,$person_id, $template_id, $task_type_id, $priority_id,  $comment, $problem_comment, $task_status);	
		}
		break;
		case 'get_responsible_person_add_page':
			$page 		= GetResoniblePersonPage();
			$data		= array('page'	=> $page);
		
			break;
		
		case 'change_responsible_person':
			$letters 			= json_decode( '['.$_REQUEST['lt'].']' );
			$responsible_person = $_REQUEST['rp'];
		
			ChangeResponsiblePerson($letters, $responsible_person);
		
			break;
		
		case 'get_add_info1':
		
			$pin_n	=	$_REQUEST['pin_n'];
			$data 	= 	array('info1' => get_addition_all_info1($pin_n));
			//get_addition_all_info1
		
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

function Addtask($person_id, $template_id, $task_type_id, $priority_id,  $comment, $problem_comment, $task_status)
{
	
	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` 
							(`user_id`, `responsible_user_id`, `incomming_call_id`, `date`, `template_id`, `task_type_id`, `priority_id`, `comment`, `problem_comment`, `status`, `actived`) 
						VALUES 
							( '$user', '$person_id', '', '$c_date', '$template_id', '$task_type_id', '$priority_id', '$comment', '$problem_comment', '$task_status', '1');");
	
	GLOBAL $log;
	$log->setInsertLog('task');
	
}
      
function savetask($id,$person_id, $template_id, $task_type_id, $priority_id,  $comment, $problem_comment, $task_status)
{
	GLOBAL $log;
	$log->setUpdateLogAfter('task', $id);
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET 
						
								`user_id`='$user', 
								`responsible_user_id`='$person_id', 
								`date`='$c_date', 
								`template_id`='$template_id', 
								`task_type_id`='$task_type_id', 
								`priority_id`='$priority_id', 
								`comment`='$comment', 
								`problem_comment`='$problem_comment', 
								`status`='$task_status', 
								`actived`='1' 
					WHERE		`id`='$id'");
	
	$log->setInsertLog('task',$id);
	

}


function Getproduction($production_id)
{
	$data = '';
	$req = mysql_query("SELECT 	production.id,
								production.`name`
						FROM  	production
						WHERE 	actived=1");


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
						FROM `info_category`
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
			FROM `info_category`
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
			FROM `info_category`
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

function Get_production($production_id)
{
	$data = '';
	$req = mysql_query("SELECT 	production.id,
								production.`name`
						FROM    production
						WHERE   actived = 1		");

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


function Get_production_category($production_category_id)
{
	$data = '';
	$req = mysql_query("SELECT 	production_category.id,
								production_category.`name`
						FROM    production_category
						WHERE   actived = 1 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_category_id){
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

function Get_reaction($reaction_id)
{
	$data = '';
	$req = mysql_query("SELECT reaction.id,
								reaction.`name`
						FROM    reaction ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $reaction_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Get_task_type($task_type_id)
{
	$data = '';
	$req = mysql_query("SELECT 	task_type.id,
								task_type.`name`
						FROM	task_type
						WHERE 	task_type.actived=1 AND task_type.id=1");

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
function Getstatus($task_status)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `status`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_status){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Get_template($template)
{
	$data = '';
	$req = mysql_query("SELECT 	template.id,
								template.`name`
						FROM    template
						WHERE 	template.actived=1");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $template){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function getCalls(){
	$db1 = new sql_db ( "212.72.155.176", "root", "Gl-1114", "asteriskcdrdb" );

	$req = mysql_query("

						SELECT  	DISTINCT
									IF(SUBSTR(cdr.src, 1, 3) = 995, SUBSTR(cdr.src, 4, 9), cdr.src) AS `src`
						FROM    	cdr
						GROUP BY 	cdr.src
						ORDER BY 	cdr.calldate DESC
						LIMIT 		12


						");

	$data = '<tr class="trClass">
					<th class="thClass">#</th>
					<th class="thClass">ნომერი</th>
					<th class="thClass">ქმედება</th>
				</tr>
			';
	$i	= 1;
	while( $res3 = mysql_fetch_assoc($req)){

		$data .= '
	    		<tr class="trClass">
					<td class="tdClass">' . $i . '</td>
					<td class="tdClass" style="width: 30px !important;">' . $res3['src'] . '</td>
					<td class="tdClass" style="font-size: 13px !important;"><button class="insert" number="' . $res3['src'] . '">დამატება</button></td>
				</tr>';
		$i++;
	}

	return $data;


}
function get_addition_all_info1($pin_n)
{
	//echo $pin_n; return 0;
	$req=mysql_query("SELECT 	client.id as c_id,	
									client.Juristic_address,
									client.`name` AS client_name,
									client.mail,
									client.phone,
									(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id) AS jami,
																							
									CASE WHEN (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>1000 
											AND
												(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<=3000
											THEN 'VIP'
									 WHEN (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>3000 
											AND
												(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<=5000
											THEN 'VIP-fold'
										WHEN (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>5000 
											AND
												(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<=10000
											THEN 'VIP-platinium'
										WHEN(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>10000 
											THEN 'VIP-priliant'
										WHEN(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<=1000 
											THEN 'ლოიალური'
									END AS `status`
																	FROM 	client
							JOIN client_sale ON client.id=client_sale.client_id
																					
							WHERE client.`code`=$pin_n
							LIMIT 1");
	$req1=mysql_query("		SELECT 	client_sale.id,
			client_sale.date,
			production.`name` AS prod_name,
			object.`name` AS obj_name,
			client_sale.price
			FROM 	client
			left JOIN client_sale ON client.id=client_sale.client_id
			left JOIN object ON client_sale.object_id=object.id
			left JOIN production ON client_sale.production_id=production.id
			WHERE client.`code`=$pin_n");

	$res = mysql_fetch_assoc($req);
	$data .= '<fieldset >
	<legend>ძირითადი ინფორმაცია</legend>
		<table style="height: 243px;">
			<tr>
				<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
				<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
			</tr>
			<tr>
				<td>'.$res['phone'].'</td>
				<td style="width: 180px;">
				<input type="text" id="personal_pin" class="idle" onblur="this.className=\'idle\'" value="' . $pin_n . '"  />
				</td>
			</tr>
			<tr>
				<td style="width: 180px; color: #3C7FB1;">კონტრაგენტი</td>
				<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
			</tr>
			<tr >
				<td style="width: 180px;">'.$res['client_name'].'</td>
				<td style="width: 180px;">'.$res['mail'].'</td>
			</tr>
			<tr>
				<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
				<td td style="width: 180px; color: #3C7FB1;">სტატუსი</td>
			</tr>
			<tr>
				<td style="width: 180px;">'.$res['Juristic_address'].'</td>
				<td td style="width: 180px;">'.$res['status'].'</td>
			</tr>

	</table>
	</fieldset>
				<fieldset>
					<legend>შენაძენი</legend>
					<table style="float: left; border: 1px solid #85b1de; width: 153px; text-align: center;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px;"></td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">ფილიალი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თარიღი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">პროდუქტი</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; color: #3C7FB1;">თანხა</td>
						</tr>
						';
	while( $res1 = mysql_fetch_assoc($req1)){
		$data .='
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">'.$res1['id'].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">'.$res1['obj_name'].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">'.$res1['date'].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">'.$res1['prod_name'].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">'.$res1['price'].'</td>
	  					</tr>			';
	};
	$data .='


					<table/>
				</fieldset>
								<!-- ID -->
		 		<input type="hidden" id="c_id1" value="' . $res['c_id'] . '" />
												';

	return $data;
}

function Gettask($id)
{
	$res = mysql_fetch_assoc(mysql_query("SELECT 	task.id,
													incomming_call.date AS incom_date,
													incomming_call.phone AS incom_phone,
													incomming_call.first_name AS first_name,
													incomming_call.information_category_id AS category_id,
													incomming_call.information_sub_category_id AS category_parent_id,
													incomming_call.production_id AS production_id,
													incomming_call.production_category_id AS production_category_id,
													incomming_call.redirect AS redirect,
													incomming_call.reaction_id AS reaction_id,
													incomming_call.connect AS connect,
													incomming_call.content AS content,
													incomming_call.production_type AS production_type,
													incomming_call.requester AS requester,
													client_sale.date AS sale_date,
													client.`code` AS personal_pin,
													task.responsible_user_id AS person_id,
													task.task_type_id AS task_type_id,
													task.template_id AS template_id,
													task.priority_id AS priority_id,
													task.status AS status,
													task.`comment` AS `comment1`,
													task.problem_comment AS problem_comment
													FROM 	task
										
											LEFT JOIN 	incomming_call 		ON incomming_call.id=task.incomming_call_id
											LEFT JOIN 	client 				ON incomming_call.client_id=client.id
											LEFT JOIN 	client_sale 		ON client.id=client_sale.client_id
											WHERE 	task.id=$id
			" ));
		
	return $res;
}


function GetPage($res='', $number, $pin)
{
	if($res['connect']==1){
		$connect0="checked";
	}else{
		$connect0="";
	}
	
	if($res['requester']==1){
		$requester0="checked";
		$requester1="";
	}elseif ($res['requester']==2){
		$requester1="checked";
		$requester0="";
	}
	if($res['production_type']==1){
		$production_type0="checked";
		$production_type1="";
	}elseif ($res['production_type']==2){
		$production_type1="checked";
		$production_type0="";
	}
	if ($res['incom_phone'] =='')
	{
$hide="style='display:none;'";
$hide1="";
	} else 
	{
$hide1="style='display:none;'";
$hide="";		
	}	
$num = 0;
	if($res[phone]==""){
	$num=$number;
	}else{
		$num=$res[phone];
		}
		$data  .= '
		<!-- jQuery Dialog -->
    <div id="add-edit-goods-form" title="საქონელი">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 800px;">
				<fieldset '.$hide.' >
			    	<legend>ძირითადი ინფორმაცია</legend>

			    	<table  width="100%" class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="">მომართვა №</label></td>
							<td style="width: 180px;"><label for="">თარიღი</label></td>
							<td style="width: 180px;"><label for="phone" >ტელეფონი</label></td>
							<td></td>
							<td><label for="person_name">აბონენტის სახელი</label></td>
						</tr>
						<tr>
							<td style="width: 180px;">
								<input type="text" id="task_id" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField user_id\'" value="' . $res['id']. '" disabled="disabled" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="incom_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField date\'" value="' .  $res['incom_date']. '" disabled="disabled" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="incom_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['incom_phone'] . '" '.$hide.'disabled="disabled" />
										</td>
							<td style="width: 69px;">
								<button class="calls"'.$hide.'>ნომრები</button>
							</td>
							<td style="width: 69px;">
								<input type="text" id="first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['first_name'] . '" '.$hide.' />
								</td>
						</tr>
					</table>
		</fieldset>								';

		$data  .= '

		<fieldset '.$hide.' style="width:318px; float:left;">
			    	<legend>მომართვის ავტორი</legend>
					<table id="additional" class="dialog-form-table" width="300px">
						<tr>
							<td style="width: 250px;"><input style="float:left;" type="radio" name = "5" value="1" '.$requester0.' disabled="disabled"><span style="margin-top:5px; display:block;">ფიზიკური</span></td>
							<td style="width: 250px;"><input style="float:left;" type="radio" name = "5" value="2" '.$requester1.' disabled="disabled" ><span style="margin-top:5px; display:block;">იურიდიული</span></td>
						</tr>
					</table>
				</fieldset>
				<fieldset '.$hide.' style="width:400px; float:left; margin-left: 15px;">
			    	<legend>ინფორმაციის კატეგორია</legend>
					<table id="additional" class="dialog-form-table" width="230px">
						<tr>
							<td style="width: 300px;"><select style="margin-left: 25px;" id="category_parent_id" class="idls object" disabled="disabled">'.   Getcategory($res['category_parent_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="category_id" class="idls object" disabled="disabled">'. Getcategory1_edit($res['category_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset '.$hide.' style="width:755px; float:left;">
			    	<legend>პროდუქტი</legend>
					<table id="additional" class="dialog-form-table" width="230px">
						<tr>
							<td style="width: 250px;"><input style="float:left;" name = "10" type="radio" value="1" '.$production_type0.' disabled="disabled"><span style="margin-top:5px; display:block;">შეძენილი</span></td>
							<td style="width: 250px;"><input style="float:left; margin-left: 20px;" type="radio" name = "10" value="2"'.$production_type1.' disabled="disabled"><span style="margin-top:5px; display:block;"">საინტერესო</span></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">შეძენის თარიღი</label></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">კატეგორია</label></td>
						</tr>
						<tr>
							<td style="width: 300px;"><label for="d_number">პროდუქტი</label></td>
							<td style="width: 300px;"><label style="margin-left: 15px;" for="d_number">ბრენდი</label></td>
							<td style="width: 250px;"><input style="margin-left: 25px;" type="text"  id="sale_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res[sale_date] . '" /></td>
							<td style="width: 250px;"><select style="margin-left: 25px;" id="production_category_id" class="idls object" disabled="disabled">'. Get_production_category($res['production_category_id']).'</select></td>
							</tr>
						<tr>
		<td style="width: 300px;"><select id="production_id" class="idls object" disabled="disabled">'.Get_production($res['production_id']).'</select></td>
		<td style="width: 300px;"><select style="margin-left: 15px;" id="production_category_id" class="idls object" disabled="disabled">'. Get_production_category($res['production_category_id']).'</select></td>
		</tr>
		</table>
				</fieldset>
				<fieldset '.$hide.' style="width:755px; float:left;">
			    	<legend>გადამისამართება</legend>
					<table id="additional" class="dialog-form-table" width="230px">
						<tr>
							<td style="width: 300px;"><label for="d_number">ქვე-განყოფილება</label></td>
							<td style="width: 300px;"><label style="margin-left: 35px;" for="d_number">კავშირი</label></td>
						</tr>
						<tr>
							<td style="width: 250px;"><select style=" width: 450px;" id="redirect" class="idls object" disabled="disabled">'. Getobject($res['redirect']).'</select></td>
							<td style="width: 250px;"><input name="rad" style="margin-left: 35px;" type="radio" value="1"'.$connect0.'disabled="disabled"></td>
						</tr>
					</table>
				</fieldset>
				<fieldset '.$hide.' style="width:160px; float:left;">
			    	<legend>რეაგირება</legend>
					<table id="additional" class="dialog-form-table" width="150px">
						<tr>
							<td style="width: 150px;"><select id="reaction_id" class="idls object" disabled="disabled">'. Get_reaction($res['reaction_id']).'</select></td>
						</tr>
						</table>
						</fieldset>
				<fieldset '.$hide.' style="width:557px; float:left; margin-left: 10px;">
			    	<legend>შინაარსი</legend>
					<table id="additional" class="dialog-form-table" width="150px">
						<tr>
							<td><textarea  style="width: 550px; resize: none;" id="content" class="idle" name="content" cols="300" disabled="disabled">' . $res['content'] . '</textarea></td>
						</tr>
					</table>
					</fieldset>
				';

		$data  .= '
		 
				<fieldset style="margin-top: 5px; width: 754px;">
		<legend>დავალების ფორმირება</legend>

			    	<table class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="d_number">დავალების ტიპი</label></td>
							<td style="width: 180px;"><label for="d_number">სცენარი</label></td>
							<td style="width: 180px;"><label for="d_number">პრიორიტეტი</label></td>
						</tr>
			    		<tr>
							<td style="width: 180px;"><select id="task_type_id" class="idls object">'.Get_task_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="template_id" class="idls object">'. Get_template($res['template_id']).'</select></td>
							<td style="width: 180px;"><select id="priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
						</tr>
						<tr>
							<td style="width: 180px;"><label for="d_number">პასუხისმგებელი პირი</label></td>
							<td '.$hide1.' style="width: 180px;"><label for="d_number">სტატუსი</label></td>
							<td style="width: 180px;"></td>
						</tr>
			    		<tr>
							<td style="width: 180px;"><select style="width: 186px;" id="person_id" class="idls object">'.Getpersons($res['person_id']).'</select></td>
							<td '.$hide1.' style="width: 180px;"><select style="width: 186px;" id="status" class="idls object">'.Getstatus($res['status']).'</select></td>
							<td style="width: 180px;"></td>
						</tr>
						<tr>
							<td style="width: 150px;"><label for="content">კომენტარი</label></td>
							<td style="width: 150px;"><label for="content"></label></td>
							<td style="width: 150px;"><label for="content"></label></td>
						</tr>
						<tr>
							<td colspan="6">
								<textarea  style="width: 747px; resize: none;" id="comment1" class="idle" name="comment1" cols="300" rows="2">' . $res['comment1'] . '</textarea>
							</td>
						</tr>
						<tr>
							<td style="width: 150px;"><label for="content">პრობლემის გადაწყვეტა</label></td>
							<td style="width: 150px;"><label for="content"></label></td>
							<td style="width: 150px;"><label for="content"></label></td>
						</tr>
						<tr>
							<td colspan="6">
								<textarea  style="width: 747px; resize: none;" id="problem_comment" class="idle" name="problem_comment" cols="300" rows="2">' . $res['problem_comment'] . '</textarea>
							</td>
						</tr>
					</table>
					</fieldset>
							</div>
			<div>
				  </fieldset>
			</div>
			<div id="info_c" style="float: right;  width: 355px;">';
				$data .= get_addition_all_info1($res['personal_pin']);
			$data .= '

					
					<fieldset>
					<legend>საუბრის ჩანაწერი</legend>
	  				<table style="float: left; border: 1px solid #85b1de; width: 250px; text-align: center; margin-left:40px;">
						<tr style="border-bottom: 1px solid #85b1de;">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; width:200px; color: #3C7FB1;">დრო</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; width:200px; color: #3C7FB1;">ჩანაწერი</td>
						</tr>
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">10:05:12 AM</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">მოსმენა</td>
	  					</tr>
					<table/>
				</fieldset>
					</div><div>
			</div>
    </div>';

	return $data;
}
function ChangeResponsiblePerson($letters, $responsible_person){
	$o_date		= date('Y-m-d H:i:s');
	foreach($letters as $letter) {

		mysql_query("UPDATE task
					SET    	task.`status`   			 = 1,
							task.`date` 			     = '$o_date',
							task.responsible_user_id     = '$responsible_person'
					WHERE  	task.id 					 = '$letter'");
	}
}

function GetPersons1(){
	$data = '';
	$req = mysql_query("SELECT 		persons.id AS `id`,
						persons.`name` AS `name`
						FROM 		`persons`");

	$data .= '<option value="' . 0 . '" selected="selected">' . '' . '</option>';

	while( $res = mysql_fetch_assoc($req)){
		$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
	}
	return $data;
}

function GetResoniblePersonPage(){
	$data = '
		<div id="dialog-form">
			<fieldset>
				<legend>ძირითადი ინფორმაცია</legend>
				<table width="100%" class="dialog-form-table" cellpadding="10px" >
					<tr>
						<th><label for="responsible_person">პასუხისმგებელი პირი</label></th>
					</tr>
					<tr>
						<th>
							<select id="responsible_person" class="idls address">'. GetPersons1() .'</select>
						</th>
					</tr>
				</table>
			</fieldset>
		</div>';
	return $data;

}
function increment($table){

	$result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
	$row   			= mysql_fetch_array($result);
	$increment   	= $row['Auto_increment'];
	$next_increment = $increment+1;
	mysql_query("ALTER TABLE '$table' AUTO_INCREMENT=$next_increment");

	return $increment;
}
?>