<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../includes/classes/core.php');
include('../../includes/classes/log.class.php');
$log 		= new log();
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

//incomming
$incom_id						= $_REQUEST['id'];
$incom_date						= $_REQUEST['incom_date'];
$incom_phone					= $_REQUEST['incom_phone'];
$requester_type					= $_REQUEST['requester_type'];
$prod_status					= $_REQUEST['prod_status'];
$first_name						= $_REQUEST['first_name'];
$category_id					= $_REQUEST['category_id'];
$production_brand_id			= $_REQUEST['production_brand_id'];
$information_sub_category_id	= $_REQUEST['category_parent_id'];
$category_parent_id				= $_REQUEST['category_parent_id'];
$production_category_id			= $_REQUEST['production_category_id'];
$production_id 					= $_REQUEST['production_id'];
$redirect						= $_REQUEST['redirect'];
$reaction_id					= $_REQUEST['reaction_id'];
$content						= $_REQUEST['content'];
$person_id						= $_REQUEST['person_id'];
$connect						= $_REQUEST['connect'];
$sale_date						= $_REQUEST['sale_date'];
$source_id						= $_REQUEST['source_id'];

//task
$task_type_id		= $_REQUEST['task_type_id'];
$template_id		= $_REQUEST['template_id'];
$priority_id		= $_REQUEST['priority_id'];
$comment			= $_REQUEST['comment'];

// file
$rand_file	= $_REQUEST['rand_file'];
$file		= $_REQUEST['file_name'];


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
		$page		= GetPage(Getincomming($incom_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
		$start		=	$_REQUEST['start'];
		$end		=	$_REQUEST['end'];
		$status		= 	$_REQUEST['status'];
		
		if($status != 'undefined'){
			$checker = "AND incomming_call.information_sub_category_id = $status";
		}
	  	$rResult = mysql_query("SELECT incomming_call.id,
										incomming_call.id,
										incomming_call.date,
										info_category.`name`,
										incomming_call.phone,
										incomming_call.content
								FROM 	incomming_call
								JOIN  	info_category ON incomming_call.information_category_id=info_category.id
								WHERE 	incomming_call.actived=1 and DATE(date)  BETWEEN  date('$start')  And date('$end') $checker");
	  
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
		
			saveincomming($incom_id,$incom_phone, $first_name, $requester_type, $category_id, $information_sub_category_id, $prod_status, $production_id, $production_category_id,$production_brand_id, $redirect, $connect, $reaction_id, $content);
			
			 savetask($incom_id, $person_id, $template_id, $task_type_id,  $priority_id,  $comment);			

	
		break;
		case 'sub_category':
		
			$cat_id = $_REQUEST['cat_id'];
			$data  =  array('cat'=>Getcategory1($cat_id));
		
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
	case 'get_calls':
	
		$data		= array('calls' => getCalls());
	
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
function saveincomming($incom_id,$incom_phone, $first_name, $requester_type, $category_id, $information_sub_category_id, $prod_status, $production_id, $production_category_id,$production_brand_id, $redirect, $connect, $reaction_id, $content)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('incomming_call', $incom_id);
	$c_id1		= $_REQUEST['c_id1'];
	$user		= $_SESSION['USERID'];
	$c_date		= date('Y-m-d H:i:s');
	mysql_query("	UPDATE `incomming_call` SET 
											
											`user_id`='$user', 
											`client_id`='$c_id1',
											`phone`='$incom_phone', 
											`first_name`='$first_name', 
											`requester`='$requester_type', 
											`information_category_id`='$category_id', 
											`information_sub_category_id`='$information_sub_category_id',
											`production_type`= '$prod_status',
											`production_id`='$production_id', 
											`production_category_id`='$production_category_id',
											`production_brand_id` = '$production_brand_id',
											`redirect`='$redirect', 
											`connect` = '$connect',
											`reaction_id`='$reaction_id', 
											`content`='$content', 
											`actived`='1' 
					WHERE					`id`='$incom_id'
					");
	
	//$log->setInsertLog('incomming_call',$incom_id);
}       
function  savetask($incomming_call_id, $person_id, $template_id, $task_type_id,  $priority_id,  $comment){
	//echo "$incomming_call_id, $template_id, $task_type_id,  $priority_id,  $problem_comment";
	//GLOBAL $log;
	//$log->setUpdateLogAfter('task', $incomming_call_id);
	$user  = $_SESSION['USERID'];
	mysql_query("	UPDATE`task` SET 
								`user_id`				='$user',
								`responsible_user_id` 	='$person_id',
								`template_id`			='$template_id', 
								`task_type_id`			='$task_type_id', 
								`priority_id`			='$priority_id', 
								`comment`				='$comment', 
								`status`				='0', 
								`actived`				='1' 
					WHERE		`incomming_call_id`		='$incomming_call_id'
					");
	//$log->setInsertLog('task',$incomming_call_id);
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

function Get_production_brand($production_brand_id)
{
	$data = '';
	$req = mysql_query("SELECT 	brand.id,
								brand.`name`
						FROM    brand
						WHERE   actived = 1 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_brand_id){
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
						WHERE 	task_type.actived=1");

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
		<table style="height: 243px;">			
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
				<td style="width: 180px;">'.$res[''].'</td>
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
							<td style="border-right: 1px solid #85b1de; padding: -2px 22px; word-break:break-all">'.$res1['id'].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: -2px 9px; word-break:break-all">'.$res1[''].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: -2px 9px; word-break:break-all">'.$res1['Date'].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: -2px 9px; word-break:break-all">'.$res1[''].'</td>
	  						<td style="border-right: 1px solid #85b1de; padding: -2px 9px; word-break:break-all">'.$res1['Sum'].'</td>
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

function Getincomming($incom_id)
{
$res = mysql_fetch_assoc(mysql_query("SELECT 	incomming_call.id,
												incomming_call.date AS incom_date,
												DATE_FORMAT(incomming_call.`date`,'%Y-%m-%d') AS record_date,
												incomming_call.phone AS incom_phone,
												incomming_call.first_name AS first_name,
												incomming_call.information_category_id AS category_id,
												incomming_call.information_sub_category_id AS category_parent_id,
												incomming_call.production_id AS production_brand_id,	
												incomming_call.production_category_id AS production_category_id,
												incomming_call.redirect AS redirect,
												incomming_call.reaction_id AS reaction_id,
												incomming_call.connect AS connect,
												incomming_call.content AS content,
												incomming_call.production_type AS production_type,
												incomming_call.production_type AS production_type,
												incomming_call.production_brand_id AS produqtion_id,
												incomming_call.sale_date,
												incomming_call.source_id,
												incomming_call.requester AS requester,
												realizations.`CustomerID` AS personal_pin,
												task.responsible_user_id AS person_id,
												task.task_type_id AS task_type_id,
												task.template_id AS template_id,
												task.priority_id AS priority_id,
												task.`comment` AS `comment`,
												'რედაქტირება' AS `edit`
										FROM 	incomming_call
										
										LEFT JOIN 	task 		ON incomming_call.id=task.incomming_call_id
										LEFT JOIN 	realizations 		ON incomming_call.client_id=realizations.id
										left JOIN 	surce 		ON incomming_call.source_id=surce.id
						 				
										WHERE 	incomming_call.id=$incom_id
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
	$num = 0;
	if($res[incom_phone]==""){
		$num=$number;
	}else{ 
		$num=$res[incom_phone]; 
	}	
	$data  .= '
	<!-- jQuery Dialog -->
    <div id="add-edit-goods-form" title="საქონელი">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 800px;">	
				<fieldset >
			    	<legend>ძირითადი ინფორმაცია</legend>
		
			    	<table width="100%" class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="">მომართვა №</label></td>
							<td style="width: 180px;"><label for="">თარიღი</label></td>
							<td style="width: 180px;"><label for="">ტელეფონი</label></td>
							<td></td>
							<td><label for="person_name">აბონენტის სახელი</label></td>
						</tr>
						<tr>
							<td style="width: 180px;">
								<input type="text" id="id" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField user_id\'" value="' . $res['id']. '" disabled="disabled" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="incom_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField date\'" value="' .  $res['incom_date']. '" disabled="disabled" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="incom_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="'  . $num .'" />
							</td>
							<td style="width: 69px;z-index: 99999999 !important;">
								<button style="" id="button_calls" class="calls">ნომრები</button>
							</td>
							<td style="width: 69px;">
								<input type="text" id="first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['first_name'] . '" />
							</td>
						</tr>						
					</table>';
										
		$data  .= '
				
				<fieldset style="width:142px; float:left;height: 59px;">
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
				<fieldset style="width:142px; float:left;margin-left: 2px;">
			    	<legend>ინფ. წყარო</legend>
					<table id="additional" class="dialog-form-table" width="100px">						
						<tr>
							<td style="width: 300px;"><select id="source_id" class="idls object">'.Get_source($res['source_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:399px; float:left; margin-left: 4px;">
			    	<legend>ინფორმაციის კატეგორია</legend>
					<table id="additional" class="dialog-form-table" width="230px">						
						<tr>
							<td style="width: 300px;"><select style="margin-left: 25px;" id="category_parent_id" class="idls object">'.   Getcategory($res['category_parent_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="category_id" class="idls object">'. Getcategory1_edit($res['category_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:755px; float:left;">
			    	<legend>პროდუქტი</legend>
					<table id="additional" class="dialog-form-table" width="230px">		
						<tr>
							<td style="width: 250px;"><input style="float:left;" name = "10" type="radio" value="1" '.$production_type0.'><span style="margin-top:9px; display:block;">შეძენილი</span></td>
							<td style="width: 250px;"><input style="float:left; margin-left: 20px;" type="radio" name = "10" value="2"'.$production_type1.'><span style="margin-top:9px; display:block;"">საინტერესო</span></td>
							<td style="width: 250px;"></td>
							<td style="width: 250px;"></td>
						</tr>
						<tr>
							<td style="width: 300px;"><label for="d_number">კატეგორია</label></td>
							<td style="width: 300px;"><label style="margin-left: 15px;" for="d_number">ბრენდი</label></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">პროდუქტი</label></td>
							<td style="width: 250px;"><label class="'.($res['production_type']==1?"":"hidden").'" style="margin-left: 25px;" for="d_number">შეძენის თარიღი</label></td>
						</tr>				
						<tr>
							<td style="width: 300px;"><select id="production_category_id" class="idls object">'.Get_production_category($res['production_category_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="production_id" class="idls object">'. Get_production_brand($res['production_brand_id'], $res['edit']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 25px;" id="production_brand_id" class="idls object">'. Get_production($res['produqtion_id'], '', $res['edit']).'</select>
							<td style="width: 300px;"><div id="sale_date_div" class="'.($res['production_type']==1?"":"hidden").'"><input style="margin-left: 25px;" type="text"  id="sale_date" class="idls object '.($res['production_type']==1?"":"hidden").'" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['sale_date'] . '" /></div></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:755px; float:left;">
			    	<legend>გადამისამართება</legend>
					<table id="additional" class="dialog-form-table" width="230px">		
						<tr>
							<td style="width: 300px;"><label for="d_number">ქვე-განყოფილება</label></td>
							<td style="width: 300px;"><label style="margin-left: 35px;" for="d_number">კავშირი</label></td>
						</tr>
						<tr>
							<td style="width: 250px;"><select style=" width: 450px;" id="redirect" class="idls object">'. Getobject($res['redirect']).'</select></td>
							<td style="width: 250px;"><input name="check_" style="margin-left: 35px;" type="checkbox" value="1"'.$connect0.'></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:160px; float:left;">
			    	<legend>რეაგირება</legend>
					<table id="additional" class="dialog-form-table" width="150px">	
						<tr>
							<td style="width: 150px;"><select id="reaction_id" class="idls object">'. Get_reaction($res['reaction_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:557px; float:left; margin-left: 9px;">
			    	<legend>შინაარსი</legend>
					<table id="additional" class="dialog-form-table" width="150px">	
						<tr>
							<td><textarea  style="width: 550px; resize: none;" id="content" class="idle" name="contentfsfs" cols="300" >' . $res['content'] . '</textarea></td>
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
							<td style="width: 180px;"><select id="task_type_id" class="idls object">'.Get_task_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="template_id" class="idls object">'. Get_template($res['template_id']).'</select></td>
							<td style="width: 180px;"><select id="priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
						</tr>
						<tr>
							<td style="width: 180px;"><label for="d_number">პასუხისმგებელი პირი</label></td>
							<td style="width: 180px;"></td>
							<td style="width: 180px;"></td>
						</tr>
			    		<tr>
							<td style="width: 215px;"><select style="width: 186px;" id="person_id" class="idls object">'.Getpersons($res['person_id']).'</select></td>
							<td style="width: 180px;"></td>
							<td style="width: 180px;"></td>
						</tr>
						<tr>
							<td style="width: 150px;"><label for="content">კომენტარი</label></td>
							<td style="width: 150px;"><label for="content"></label></td>
							<td style="width: 150px;"><label for="content"></label></td>
						</tr>
						<tr>
							<td colspan="6">
								<textarea  style="width: 747px; resize: none;" id="comment" class="idle" name="comment" cols="300" rows="2">' . $res['comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div>
				  </fieldset>
			</div>
			<div id="info_c" style="float: right;  width: 371px;">';
				$data .= get_addition_all_info1($res['personal_pin']);
			$data .= '</div>';
				$data .=GetRecordingsSection($res);
			$data .='	
	  		
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
        <fieldset style="margin-top: 10px; width: 333px; float: right;">
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
?>