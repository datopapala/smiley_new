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
$call_date				= $_REQUEST['call_date'];
$site_user_pin			= $_REQUEST['pin'];
$call_type_id			= $_REQUEST['call_type_id'];

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
	  	$rResult = mysql_query("select  		incomming_call.id,           
												incomming_call.id,
											  	DATE_FORMAT(incomming_call.`date`,'%d-%m-%y %H:%i:%s'),
												category.`name`,
												site_user.`pin`,
												incomming_call.phone,				
												site_user.`name`,
	  											'' AS `time`,
	  											incomming_call.call_content
								FROM 			incomming_call
								LEFT JOIN		site_user ON incomming_call.id=site_user.incomming_call_id
								LEFT JOIN 		category  ON incomming_call.call_category_id=category.id
	  							WHERE incomming_call.actived = 1");
	  
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
		}else {
			
			Saveincomming($call_type_id, $phone, $category_id, $category_parent_id, $object_id, $pay_type_id, $bank_id, $card_type_id, $pay_aparat_id,  $problem_date, $call_content,$file,$rand_file);
			
		}
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


function Getincomming($incom_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT    	incomming_call.id AS id,
													incomming_call.phone AS `phone`,
													DATE_FORMAT(incomming_call.`date`,'%d-%m-%y %H:%i:%s') AS call_date,
													incomming_call.call_type_id AS call_type_id,
													incomming_call.call_category_id AS category_id,
													IF(ISNULL(task.`status`), 3, task.`status`) AS `status`,
													incomming_call.call_subcategory_id AS category_parent_id,
													DATE_FORMAT(incomming_call.`date`,'%d-%m-%y %H:%i:%s') AS problem_date,
													incomming_call.call_content AS call_content,
													incomming_call.pay_type_id AS pay_type_id,
													incomming_call.bank_id AS bank_id,
													incomming_call.bank_object_id AS bank_object_id,
													incomming_call.card_type_id AS card_type_id,
													incomming_call.card_type_id AS card_type1_id,
													incomming_call.pay_aparat_id AS pay_aparat_id,
													incomming_call.object_id AS object_id,
													site_user.`name` AS `name`,
													site_user.mail AS mail,
													site_user.personal_id AS personal_id,
													site_user.phone AS personal_phone,
													site_user.pin AS personal_pin,
													site_user.friend_pin AS friend_pin,
													site_user.`name` AS `name1`,
													site_user.`mail` AS `mail`,
													site_user.user AS user,
													task.task_type_id AS task_type_id,
													task.responsible_user_id AS persons_id,
													task.priority_id AS priority_id,
													task.department_id AS task_department_id,
													task.`comment` AS `comment`
										FROM 	   	incomming_call
										LEFT JOIN  	site_user ON incomming_call.id=site_user.incomming_call_id
										LEFT JOIN  	task  ON incomming_call.id=task.incomming_call_id
										where      	incomming_call.id = $incom_id
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
			<div style="float: left; width: 375px;">	
				<fieldset style= width: 200px;" >
			    	<legend>sgvdgvsdgvs</legend>
		
			    	<table width="100%" class="dialog-form-table">		
							<td style="width: 180px;"><label for="">თარიღი</label></td>
							<td style="width: 180px;">
								<input type="text" id="c_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField date\'" value="' .  $res['call_date']. '" disabled="disabled" />
							</td>
						</tr>
						<tr>
							<td style="width: 180px;"><label for="phone">პროდუქტი</label></td>
							<td style="width: 180px;">
								<input type="text" id="phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $num . '" />
							</td>
						</tr>
						<tr>
							<td><label for="person_name">თანხა</label></td>
							<td style="width: 69px;">
								<input type="text" id="person_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $num . '" />
							</td>
						</tr>						
					</table>
				</fieldset >
			 </div>';

	return $data;
}

?>