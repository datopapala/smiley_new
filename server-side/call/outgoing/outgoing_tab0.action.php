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
$person_id			= $_REQUEST['person_id'];
$problem_comment	= $_REQUEST['problem_comment'];
$comment 	     	= $_REQUEST['comment1'];
$priority_id 		= $_REQUEST['priority_id'];
//$task_status 		= $_REQUEST['task_status'];
$template_id		= $_REQUEST['template_id'];
$person_id			= $_REQUEST['person_id'];
$task_type_id		= $_REQUEST['task_type_id'];
$task_date			= $_REQUEST['task_date'];
$task_status		= $_REQUEST['status'];

$question1			= $_REQUEST['question1'];
$question1_comment	= $_REQUEST['question1_comment'];
$question2_comment	= $_REQUEST['question2_comment'];


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
		$page		= GetPage(Gettask($id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
		$user_id	= $_SESSION['USERID'];
		$user		= $_SESSION['USERID'];
		$group		= checkgroup($user);
		
		$filter = '';
		if ($group != 1) {
			$res_row = mysql_fetch_assoc(mysql_query("SELECT 	users.person_id
					FROM 	`users`
					WHERE 	`users`.`id` = $user_id"));
				
			$filter = 'AND task.responsible_user_id ='. $res_row[person_id];
		}
		mysql_query("SET @i = 0;");
  		$rResult = mysql_query("SELECT		`task`.id,
											@i := @i + 1 AS `iterator`,
  											users.username,
											`user1`.`name` ,
											`person2`.`name` ,
									if(task.incomming_call_id=0, task.`date`, incomming_call.`date`) AS datee
							FROM 			`task`
							JOIN users ON users.id = task.user_id
							LEFT JOIN 	incomming_call ON task.incomming_call_id=incomming_call.id
							left JOIN 		persons AS `user1`			ON task.responsible_user_id=user1.id
							JOIN 		users AS `user2`			ON task.user_id=user2.id
							JOIN 		persons AS `person2`		ON user2.person_id=person2.id
							WHERE 		task.actived=1 and task.task_type_id= 1 AND task.`status`=0 $filter
									
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
		$id		 	= $_REQUEST['id'];
		
		if($id == ''){
			
			Addtask($person_id,  $template_id, $task_type_id, $priority_id, $comment, $problem_comment, $task_status, $question1, $question1_comment, $question2_comment);
					
			//$task_id = mysql_insert_id();
			//if($personal_pin != 0){
			//Addsite_user($task_id, $personal_pin, $name, $personal_phone, $mail, $personal_id);
			//}
		}else {
			
			savetask($id,$person_id, $template_id, $task_type_id, $priority_id,  $comment, $problem_comment, $task_status, $question1, $question1_comment, $question2_comment);	
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
			case 'sub_produqtion':
					
				$brand_id = $_REQUEST['brand_id'];
				$data  =  array('cat'=> Get_production_brand($brand_id, ''));
					
				break;
			case 'sub_produqtion1':
					
				$prod_id = $_REQUEST['prod_id'];
				$categ_id = $_REQUEST['categ_id'];
				$data  =  array('cat'=>  Get_production($prod_id, $categ_id, ''));
					
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
function checkgroup($user){
	$res = mysql_fetch_assoc(mysql_query("
			SELECT users.group_id
			FROM    users
			WHERE  users.id = $user
			"));
	return $res['group_id'];

}

function Addtask($person_id,  $template_id, $task_type_id, $priority_id, $comment, $problem_comment, $task_status, $question1, $question1_comment, $question2_comment)
{
	
	$c_id1		= $_REQUEST['c_id1'];
	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` 
							(`user_id`, `client_id`, `responsible_user_id`, `incomming_call_id`, `date`, `template_id`, `task_type_id`, `priority_id`, `comment`, `problem_comment`, `status`, `actived`, `question1`, `question1_comment`, `question2_comment`) 
						VALUES 
							( '$user', '$c_id1', '$person_id', '', '$c_date', '$template_id', '$task_type_id', '$priority_id', '$comment', '$problem_comment', '$task_status', '1', '$question1', '$question1_comment', '$question2_comment');");
	
	//GLOBAL $log;
	//$log->setInsertLog('task');
	
}
      
function savetask($id,$person_id, $template_id, $task_type_id, $priority_id,  $comment, $problem_comment, $task_status, $question1, $question1_comment, $question2_comment)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('task', $id);
	$c_id1		= $_REQUEST['c_id1'];
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET 
						
								`user_id`='$user',
								`client_id`='$c_id1', 
								`responsible_user_id`='$person_id', 
								`date`='$c_date', 
								`template_id`='$template_id', 
								`task_type_id`='$task_type_id', 
								`priority_id`='$priority_id', 
								`comment`='$comment', 
								`problem_comment`='$problem_comment', 
								`status`='$task_status', 
								`actived`='1',
								`question1`='$question1',
								`question1_comment`='$question1_comment',
								`question2_comment`='$question2_comment' 
					WHERE		`id`='$id'");
	
	//$log->setInsertLog('task',$id);
	

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

function Get_production($prod_id, $categ_id, $edit)
{
	$data = '';
	if($edit == ''){
		$req = mysql_query("SELECT DISTINCT production.`name` as id,
				production.`name`
				FROM    production
				WHERE   actived = 1 AND production.production_category='$categ_id' AND production.brand='$prod_id'");
	}else {
		$req = mysql_query("SELECT DISTINCT production.`name` as id,
				production.`name`
				FROM    production
				WHERE   actived = 1 AND production.`name`='$prod_id' ");
	}
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $prod_id){
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
	$req = mysql_query("SELECT DISTINCT production.production_category as id,
								production.`production_category`
						FROM    production
						WHERE   actived = 1 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['production_category'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['production_category'] . '</option>';
		}
	}

	return $data;
}

function Get_production_brand($brand_id, $edit)
{
	$data = '';
	if($edit == ''){
		$req = mysql_query("SELECT DISTINCT production.brand as id,
				production.`brand`
				FROM    production
				WHERE   actived = 1 AND production.production_category='$brand_id' ");
	}else {
		$req = mysql_query("SELECT DISTINCT production.brand as id,
				production.`brand`
				FROM    production
				WHERE   actived = 1 AND production.brand='$brand_id' ");
	}

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $brand_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['brand'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['brand'] . '</option>';
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
function Get_source($source_id)

{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `surce`
						WHERE actived=1 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $source_id){
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
function get_addition_all_info1($pin_n)
{
	//echo $pin_n; return 0;
	$req=mysql_query("		SELECT 	realizations.id as c_id,	
									realizations.CustomerAddress,
									realizations.`CustomerName` AS client_name,
									realizations.CustomerAddress,
									realizations.CustomerPhone,
									SUM(`nomenclature`.`Sum`)AS jami,
																							
									CASE
									 	WHEN  SUM(`nomenclature`.`Sum`)>5000 
											AND
												 SUM(`nomenclature`.`Sum`)<=7000
											THEN 'VIP-Gold'
										WHEN  SUM(`nomenclature`.`Sum`)  >7000 
											AND
												SUM(`nomenclature`.`Sum`)<=10000
											THEN 'VIP-Platinium'
										WHEN SUM(`nomenclature`.`Sum`) >10000 
											THEN 'VIP-Briliant'
										WHEN SUM(`nomenclature`.`Sum`)<=5000 and COUNT(realizations.CustomerName)>5
											THEN 'ლოიალური'
									END AS `status`
																	FROM 	realizations
							JOIN nomenclature ON realizations.id=nomenclature.realizations_id
																					
							WHERE realizations.CustomerID=$pin_n
							LIMIT 1");
	$req1=mysql_query("		SELECT 	nomenclature.id,
									realizations.Date,
									SUM(nomenclature.Sum) as Sum	
									FROM 	realizations
									JOIN nomenclature ON realizations.id=nomenclature.realizations_id
									WHERE realizations.`CustomerID`=	$pin_n
									GROUP BY realizations.Date");
	
	$res = mysql_fetch_assoc($req);													
	$data .= '<fieldset >
	<legend>ძირითადი ინფორმაცია</legend>
		<table style="height: 130px;">			
			<tr>
				<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
				<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
			</tr>
			<tr>
				<td>'.$res['CustomerPhone'].'</td>
				<td style="width: 180px;">
				<input type="text" id="personal_pin" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $pin_n . '" />
				</td>
			</tr>
			<tr>
				<td style="width: 180px; color: #3C7FB1;">კონტრაგენტი</td>
				<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
			</tr>
			<tr >
				<td style="width: 180px;">'.$res['client_name'].'</td>
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
		
	</fieldset>';	
			$data .=GetRecordingsSection($res);
			$data .='
				<fieldset>
				<legend>შენაძენები</legend>
				<div id="dt_example" class="inner-table" style="min-height: 200px !important;">
		        <div style="width:356px;" id="container" >        	
		            <div id="dynamic">
		            	<div id="button_area">
		            	</div>
		                <table class="" id="examplee_1" style="width: 100%;">
		                    <thead>
								<tr  id="datatable_header">
										
		                           <th style="display:none">ID</th>
									<th style="width:15%;">თარიღი</th>
									<th style=" width:15%;">საწყობი</th>
									<th style="width:15%;">პროდუქტი</th>
									<th style="width:15%;">თანხა</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 66px"/>
                            		</th>
									<th>
										<input style="width:94px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:66px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									
									
								</tr>
							</thead>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
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
													DATE_FORMAT(incomming_call.`date`,'%Y-%m-%d') AS record_date,
													incomming_call.phone AS incom_phone,
													incomming_call.first_name AS first_name,
													incomming_call.information_category_id AS category_id,
													incomming_call.information_sub_category_id AS category_parent_id,
													incomming_call.production_id AS production_brand_id,	
													incomming_call.production_category_id AS production_category_id,
													incomming_call.production_brand_id AS production_id,
													incomming_call.redirect AS redirect,
													incomming_call.reaction_id AS reaction_id,
													incomming_call.connect AS connect,
													incomming_call.content AS content,
													incomming_call.production_type AS production_type,
													incomming_call.requester AS requester,
													incomming_call.sale_date,
													incomming_call.source_id,
													IF(task.incomming_call_id=0,realizations.`CustomerID`,cl.`CustomerID`) AS personal_pin,
													task.responsible_user_id AS person_id,
													task.task_type_id AS task_type_id,
													task.template_id AS template_id,
													task.priority_id AS priority_id,
													task.status AS status,
													task.`comment` AS `comment1`,
													task.question1,
													task.question1_comment,
													task.question2_comment,
													task.problem_comment AS problem_comment,
													'რედაქტირება' AS `edit`
													FROM 	task
										
											LEFT JOIN 	incomming_call 		ON incomming_call.id=task.incomming_call_id
											LEFT JOIN 	realizations ON task.client_id = realizations.id
											LEFT JOIN 	realizations AS cl ON cl.id=incomming_call.client_id
											left JOIN 	surce 		ON incomming_call.source_id=surce.id
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
			<div style="float: left; width: 777px; height: 456px">
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

		<fieldset '.$hide.'  style="width:142px; float:left;height: 59px;">
			    	<legend>მომართვის ავტორი</legend>
					<table id="additional" class="dialog-form-table" width="100px">						
						<tr>
							<td style="width: 100px;"><input style="float:left;" type="radio" name = "5" value="1" '.$requester0.' ><span style="margin-top:9px; display:block;">ფიზიკური</span></td>
						</tr>
						<tr>
							<td style="width: 100px;"><input style="float:left;" type="radio" name = "5" value="2" '.$requester1.' ><span style="margin-top:9px; display:block;">იურიდიული</span></td>
						</tr>
					</table>
				</fieldset>
				<fieldset '.$hide.'  style="width:142px; float:left;margin-left: 2px;">
			    	<legend>ინფ. წყარო</legend>
					<table id="additional" class="dialog-form-table" width="100px">						
						<tr>
							<td style="width: 300px;"><select id="source_id" class="idls object">'.Get_source($res['source_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
			<fieldset '.$hide.'  style="width:399px; float:left; margin-left: 3px;">
			    	<legend>ინფორმაციის კატეგორია</legend>
					<table id="additional" class="dialog-form-table" width="230px">						
						<tr>
							<td style="width: 300px;"><select style="margin-left: 25px;" id="category_parent_id" class="idls object">'.   Getcategory($res['category_parent_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="category_id" class="idls object">'. Getcategory1_edit($res['category_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset '.$hide.' style="width:755px; float:left;">
			    	<legend>პროდუქტი</legend>
					<table id="additional" class="dialog-form-table" width="230px">
						<tr>
							<td style="width: 250px;"><input style="float:left;" name = "10" type="radio" value="1" '.$production_type0.' disabled="disabled"><span style="margin-top:9px; display:block;">შეძენილი</span></td>
							<td style="width: 250px;"><input style="float:left; margin-left: 20px;" type="radio" name = "10" value="2"'.$production_type1.' disabled="disabled"><span style="margin-top:9px; display:block;"">საინტერესო</span></td>
							<td style="width: 250px;"></td>
							<td style="width: 250px;"></td>
						</tr>
						<tr>
							<td style="width: 300px;"><label for="d_number">კატეგორია</label></td>
							<td style="width: 300px;"><label style="margin-left: 15px;" for="d_number">ბრენდი</label></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">პროდუქტი</label></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">შეძენის თარიღი</label></td>
							
						</tr>
						<tr>
							<td style="width: 300px;"><select id="production_category_id" class="idls object" disabled="disabled">'.Get_production_category($res['production_category_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="production_brand_id" class="idls object" disabled="disabled">'.Get_production_brand($res['production_brand_id'], $res['edit']).'</select></td>
							<td style="width: 250px;"><select style="margin-left: 25px;" id="production_id" class="idls object" disabled="disabled">'.Get_production($res['production_id'], '', $res['edit']).'</select></td>
							<td style="width: 250px;"><input style="margin-left: 25px;" type="text"  id="sale_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res[sale_date] . '" /></td>
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
							<td style="width: 250px;"><input name="check_" style="margin-left: 35px;" type="checkbox" value="1"disabled="disabled"'.$connect0.'></td>
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
		 
		<fieldset style="width: 754px;">
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
							<td style="width: 180px;"><select style="width: 164px;" id="person_id" class="idls object">'.Getpersons($res['person_id']).'</select></td>
							<td '.$hide1.' style="width: 180px;"><select style="width: 166px;" id="status" class="idls object">'.Getstatus($res['status']).'</select></td>
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
					<fieldset style="width: 754px;">
			    	<legend>კვლევა</legend>
				<table class="dialog-form-table">
			    		<tr>
							<td style="width:30px; font-weight:bold;">1.</td>
							<td style="font-weight:bold;">კმაყოფილი ხართ თუ არა ჩვენი სერვისით?</td>
							<td></td>
						</tr>
				</table>
				<table class="dialog-form-table">
			    		<tr>
							<td style="width:120px;"><input style="float:left;" type="radio" name="question1" value="1" '.(($res['question1']=='1')?"checked":"").'><span style="position:absolute; margin-top:9px;">კმაყოფილია</span></td>
							<td style="width:120px;"><input style="float:left;" type="radio" name="question1" value="2" '.(($res['question1']=='2')?"checked":"").'><span style="position:absolute; margin-top:9px;">უკმაყოფილოა</span></td>
							<td style="width:120px;"><input style="float:left;" type="radio" name="question1" value="3" '.(($res['question1']=='3')?"checked":"").'><span style="position:absolute; margin-top:9px;">ნეიტრალური</span></td>
						</tr>
				</table>
				<table class="dialog-form-table">
						<tr>
							<td>კომენტარი</td>
						</tr>
						<tr>
							<td><textarea  style="width: 740px; height:60px; resize: none;" id="question1_comment" class="idle">'.$res['question1_comment'].'</textarea></td>
						</tr>
				</table>
				<hr>

				<table class="dialog-form-table">
			    		<tr>
							<td style="width:30px; font-weight:bold;">2.</td>
							<td style="font-weight:bold;">რას ურჩევდით სმაილს?</td>
							<td></td>
						</tr>
				</table>
				<table class="dialog-form-table">
						<tr>
							<td>კომენტარი</td>
						</tr>
						<tr>
							<td><textarea  style="width: 740px; height:60px; resize: none;" id="question2_comment" class="idle">'.$res['question2_comment'].'</textarea></td>
						</tr>
				</table>
				</fieldset>
							</div>
			<div>
				  </fieldset>
			</div>
			<div id="info_c" style="float: right;  width: 376px;">';
				$data .= get_addition_all_info1($res['personal_pin']);
				$data .= '</div>';
				
			
				
			$data .='	
	<input type="hidden" id="h_id" value="'.$res[id].'"/> 		
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
						FROM 		`persons`
						WHERE 	actived=1");

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
function GetRecordingsSection($res)
{
	if ($res[incom_phone]==''){
		//$data .= '<td colspan="2" style="height: 20px; text-align: center;">ჩანაწერები ვერ მოიძებნა</td>';
	}else{
		$req = mysql_query("SELECT  TIME(`calldate`) AS 'time',
				`userfield`
				FROM     `cdr`
				WHERE     (`dst` = '2555655' && `userfield` != '' && DATE(`calldate`) = '$res[record_date]' && `src` LIKE '%$res[incom_phone]%')
				OR      (`dst` LIKE '%$res[incom_phone]%' && `userfield` != '' && DATE(`calldate`) = '$res[record_date]');");
	}
	$data .= '
        <fieldset style="margin-top: 10px; width: 353px; float: right;">
            <legend>ჩანაწერები</legend>

            <table style="width: 65%; border: solid 1px #85b1de; margin:auto;">
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <th style="padding-left: 10px;">დრო</th>
                    <th  style="border: solid 1px #85b1de; padding-left: 10px;">ჩანაწერი</th>
                </tr>';
	if (mysql_num_rows($req) == 0){
		$data .= '<td colspan="2" style="height: 20px; text-align: center;">ჩანაწერები ვერ მოიძებნა</td>';
	}

	while( $res2 = mysql_fetch_assoc($req)){
		$src = $res2['userfield'];
		$link = explode("/", $src);
		$data .= '
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <td>' . $res2['time'] . '</td>
                    <td><button class="download" str="' . $link[5] . '">მოსმენა</button></td>
                </tr>';
	}

	$data .= '
            </table>
        </fieldset>';

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