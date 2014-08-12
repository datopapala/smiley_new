<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

//incomming
$incom_id						= $_REQUEST['id'];
$incom_date						= $_REQUEST['incom_date'];
$incom_phone					= $_REQUEST['incom_phone'];
$first_name						= $_REQUEST['first_name'];
$category_id					= $_REQUEST['category_id'];
$information_sub_category_id	= $_REQUEST['category_parent_id'];
$category_parent_id				= $_REQUEST['category_parent_id'];
$production_category_id			= $_REQUEST['production_category_id'];
$production_id 					= $_REQUEST['production_id'];
$redirect						= $_REQUEST['redirect'];
$reaction_id					= $_REQUEST['reaction_id'];
$content						= $_REQUEST['content'];


//task
$task_type_id		= $_REQUEST['task_type_id'];
$template_id		= $_REQUEST['template_id'];
$priority_id		= $_REQUEST['priority_id'];
$problem_comment	= $_REQUEST['problem_comment'];

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
	  	$rResult = mysql_query("SELECT incomming_call.id,
										incomming_call.id,
										incomming_call.date,
										info_category.`name`,
										incomming_call.phone,
										incomming_call.content
								FROM 	incomming_call
								JOIN  	info_category ON incomming_call.information_category_id=info_category.id
								WHERE 	incomming_call.actived=1");
	  
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
		if($incom_id == ''){
			
			Addincomming($incom_phone, $first_name, $category_id, $information_sub_category_id, $production_id, $production_category_id, $redirect, $reaction_id, $content);
			$incomming_call_id = mysql_insert_id();
		
			Addtask($incomming_call_id, $template_id, $task_type_id,  $priority_id,  $problem_comment);
			
			//Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id);
		
		}else {
			
			saveincomming($incom_id,$incom_phone, $first_name, $category_id, $information_sub_category_id, $production_id, $production_category_id, $redirect, $reaction_id, $content);
			
			 savetask($incom_id, $template_id, $task_type_id,  $priority_id,  $problem_comment);
			
			//Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id);
			
		}
		break;
		case 'sub_category':
		
			$cat_id = $_REQUEST['cat_id'];
			$data  =  array('cat'=>Getcategory1($cat_id));
		
			break;
	case 'get_calls':
	
		$data		= array('calls' => getCalls());
	
		break;
		case 'get_add_info1':
		
			$personal_id	=	$_REQUEST['pin'];
			$data 	= 	array('info1' => GetPage(get_addition_all_info1($personal_id)));
		
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

function Addincomming($incom_phone, $first_name, $category_id, $information_sub_category_id, $production_id, $production_category_id, $redirect, $reaction_id, $content){
	
	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `incomming_call` 
				(`user_id`, `client_id`, `date`, `phone`, `first_name`, `requester`, `information_category_id`, `information_sub_category_id`, `production_id`, `production_category_id`, `production_brand_id`, `redirect`, `connect`, `reaction_id`, `content`, `actived`) 
				VALUES 
				('$user', '8', '$c_date', '$incom_phone', '$first_name', '0', '$category_id', '$information_sub_category_id', '$production_id', '$production_category_id', '1', '$redirect', '9', '$reaction_id','$content', '1');");
	}

function Addtask($incomming_call_id, $template_id, $task_type_id,  $priority_id,  $problem_comment)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` 
							( `user_id`, `incomming_call_id`,`template_id`, `task_type_id`, `priority_id`, `problem_comment`, `status`, `actived`) 
							VALUES 
							('2', '$incomming_call_id', $template_id, '$task_type_id', '$priority_id','$problem_comment', '0', '1');");
	
	
}

function Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `site_user` 	(`incomming_call_id`, `site`, `pin`, `friend_pin`, `name`, `phone`, `mail`, `personal_id`, `user`)
						           		 VALUES 
											( '$incomming_call_id', '243', '$personal_pin', '$friend_pin', '3414', 12412341, '124124124', '$personal_id', '$user')");

}
				
function saveincomming($incom_id,$incom_phone, $first_name, $category_id, $information_sub_category_id, $production_id, $production_category_id, $redirect, $reaction_id, $content)
{
	//// echo  "$incom_id, $incom_phone , $first_name , $category_id , $information_sub_category_id, $production_id, $production_category_id, $redirect, $reaction_id, $content";
	$user		= $_SESSION['USERID'];
	$c_date		= date('Y-m-d H:i:s');
	mysql_query("	UPDATE `incomming_call` SET 
											
											`user_id`='$user', 
											`client_id`='8', 
											`phone`='$incom_phone', 
											`first_name`='$first_name', 
											`requester`='0', 
											`information_category_id`='$category_id', 
											`information_sub_category_id`='$information_sub_category_id', 
											`production_id`='$production_id', 
											`production_category_id`='$production_category_id', 
											`redirect`='$redirect', 
											`reaction_id`='$reaction_id', 
											`content`='$content', 
											`actived`='1' 
					WHERE					`id`='$incom_id'
					");
	

}       
function  savetask($incomming_call_id, $template_id, $task_type_id,  $priority_id,  $problem_comment){
	//echo "$incomming_call_id, $template_id, $task_type_id,  $priority_id,  $problem_comment";

	$user  = $_SESSION['USERID'];
	mysql_query("	UPDATE`task` SET 
								`user_id`				='$user', 
								`template_id`			='$template_id', 
								`task_type_id`			='$task_type_id', 
								`priority_id`			='$priority_id', 
								`problem_comment`		='$problem_comment', 
								`status`				='0', 
								`actived`				='1' 
					WHERE		`incomming_call_id`		='$incomming_call_id'
					");

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
function get_addition_all_info1($pin)
{
	$res = mysql_fetch_assoc(mysql_query("		SELECT 	client.phone  AS client_phone,
														client.mail AS mail,
														client.Juristic_address AS adress
												FROM 	client
												WHERE 	client.`code`=$pin"));
													

	
	return $res;
}

function Getincomming($incom_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	incomming_call.id,
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
												client_sale.date AS sale_date,
												client.`code` AS personal_pin,
												task.task_type_id AS task_type_id,
												task.template_id AS template_id,
												task.priority_id AS priority_id,
												task.problem_comment AS problem_comment
										FROM 	incomming_call
		
										JOIN 		task 		ON incomming_call.id=task.incomming_call_id
										JOIN 		client 		ON incomming_call.client_id=client.id
										LEFT JOIN 	client_sale ON incomming_call.id=client_sale.client_id
										WHERE 	incomming_call.id=$incom_id
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
			<div style="float: left; width: 800px;">	
				<fieldset >
			    	<legend>ძირითადი ინფორმაცია</legend>
		
			    	<table width="100%" class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="">მომართვა №</label></td>
							<td style="width: 180px;"><label for="">თარიღი</label></td>
							<td style="width: 180px;"><label for="phone">ტელეფონი</label></td>
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
								<input type="text" id="incom_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['incom_phone'] . '" />
							</td>
							<td style="width: 69px;">
								<button class="calls">ნომრები</button>
							</td>
							<td style="width: 69px;">
								<input type="text" id="first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['first_name'] . '" />
							</td>
						</tr>						
					</table>';
										
		$data  .= '
				
				<fieldset style="width:318px; float:left;">
			    	<legend>მომართვის ავტორი</legend>
					<table id="additional" class="dialog-form-table" width="300px">						
						<tr>
							<td style="width: 250px;"><input style="float:left;" type="radio" name = "5" value="1"><span style="margin-top:5px; display:block;">ფიზიკური</span></td>
							<td style="width: 250px;"><input style="float:left;" type="radio" name = "5" value="2"><span style="margin-top:5px; display:block;">იურიდიული</span></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:400px; float:left; margin-left: 15px;">
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
							<td style="width: 250px;"><input style="float:left;" name = "10" type="radio" value="1"><span style="margin-top:5px; display:block;">შეძენილი</span></td>
							<td style="width: 250px;"><input style="float:left; margin-left: 20px;" type="radio" name = "10" value="2"><span style="margin-top:5px; display:block;"">საინტერესო</span></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">შეძენის თარიღი</label></td>
							<td style="width: 250px;"><label style="margin-left: 25px;" for="d_number">კატეგორია</label></td>
						</tr>
						<tr>
							<td style="width: 300px;"><label for="d_number">პროდუქტი</label></td>
							<td style="width: 300px;"><label style="margin-left: 15px;" for="d_number">ბრენდი</label></td>
							<td style="width: 250px;"><input style="margin-left: 25px;" type="text"  id="sale_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res[sale_date] . '" /></td>
							<td style="width: 250px;"><select style="margin-left: 25px;" id="production_category_id" class="idls object">'. Get_production_category($res['production_category_id']).'</select></td>
						</tr>				
						<tr>
							<td style="width: 300px;"><select id="production_id" class="idls object">'.Get_production($res['production_id']).'</select></td>
							<td style="width: 300px;"><select style="margin-left: 15px;" id="production_category_id" class="idls object">'. Get_production_category($res['production_category_id']).'</select></td>
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
							<td style="width: 250px;"><input style="margin-left: 35px;" type="radio" value="1"></td>
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
				<fieldset style="width:557px; float:left; margin-left: 10px;">
			    	<legend>შინაარსი</legend>
					<table id="additional" class="dialog-form-table" width="150px">	
						<tr>
							<td><textarea  style="width: 550px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
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
							<td style="width: 150px;"><label for="content">კომენტარი</label></td>
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
			<div style="float: right;  width: 355px;">
				 <fieldset>
					<legend>მომართვის ავტორი</legend>
					<table style="height: 243px;">						
						<tr>
							<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
							<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
						</tr>
						<tr>
							<td>'.$res[client_phone].'</td>
							<td style="width: 180px;">
								<input type="text" id="personal_pin" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_pin'] . '" />
							</td>					
						</tr>
						<tr>
							<td style="width: 180px; color: #3C7FB1;">კონტრაგენტი</td>
							<td style="width: 180px; color: #3C7FB1;">'.$res[mail].'</td>
						</tr>
						<tr >
							<td style="width: 180px;">ზაზა მესხი</td>
							<td style="width: 180px;">z.mesxi@yahoo.com</td>			
						</tr>
						<tr>
							<td td style="width: 180px; color: #3C7FB1;">'.$res[adress].'</td>
							<td td style="width: 180px; color: #3C7FB1;">სტატუსი</td>
						</tr>
						<tr>
							<td style="width: 180px;">ყვარლის 149</td>
							<td td style="width: 180px;">VIP კლიენტი</td>
						</tr>
					</table>
				</fieldset>
				<div id="additional_info">
	  		</div>
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
						<tr style="border-bottom: 1px solid #85b1de; ">
							<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">1</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">gelaaaaaaaaaaaaaaaa</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">2014-07-01</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">fssdgsd</td>
	  						<td style="border-right: 1px solid #85b1de; padding: 3px 9px; word-break:break-all">145$</td>
							
						</tr>
						
					<table/>
				</fieldset>
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
			</div>
    </div>';

	return $data;
}




?>