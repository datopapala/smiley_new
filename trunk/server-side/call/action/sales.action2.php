<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

//action1
$action_detail_id	= $_REQUEST['id'];
$production_id		= $_REQUEST['production_id'];
$object_id			= $_REQUEST['object_id'];
$price				= $_REQUEST['price'];
$date    			= $_REQUEST['date'];

$pay_type_id			= $_REQUEST['pay_type_id'];
$bank_id				= $_REQUEST['bank_id'];
$card_type_id			= $_REQUEST['card_type_id'];
$pay_aparat_id			= $_REQUEST['pay_aparat_id'];



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
		mysql_query("			UPDATE `action`
									    SET `actived`=0
										WHERE `id`=$action_id ");
		break;
	case 'get_edit_page':
		$page		= GetPage(Getaction_1($action_detail_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("	SELECT  
											action_detail.id,
											object.`name`,
											action_detail.date,
											production.`name`,
											action_detail.price
									FROM 	action_detail
									JOIN 	object ON action_detail.object_id=object.id
									JOIN    production ON action_detail.production_id= production.id
									WHERE   action_detail.actived =1");
												  
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
			
			Addaction_1( $object_id,  $date, $production_id,   $price );
			
			
		}else {
			
			saveaction_1( $action_detail_id,  $object_id, $date, $production_id,  $price);
			
			
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

function Addaction_1( $object_id,  $date, $production_id,   $price ){
	
	$user		= $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `action_detail` 
							( `user_id`, `action_id`, `object_id`, `date`, `production_id`, `price`, `actived`)
						VALUES 
							( '$user', '', '$object_id', '$date', '$production_id', '$price', '1')
								");
	
	
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


				
function saveaction_1( $action_detail_id,  $object_id, $date, $production_id,  $price)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("UPDATE `action_detail` 
					SET 
						`object_id`='$object_id', 
						`date`='$date', 
						`production_id`='$production_id,', 
						`price`='$price'
						WHERE action_detail.`id`=  $action_detail_id,");
	

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


function Getaction_1($action_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	action_detail.id,
												action_detail.production_id AS production_id,
												action_detail.object_id AS object_id,
												action_detail.price AS price,
												action_detail.date AS date
										from  	action_detail
										WHERE 	action_detail.id=$action_id
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
	<div id="dialog-form">
		<fieldset>
    		<legend>საკონტაქტო ინფორმაცია</legend>
    		<table class="dialog-form-table">
    			<tr>
    				<td style="width: 170px;"><label for="trans_obj">ფილიალები</label></td>
    				<td style="width: 180px;" id="object_id"><select id="object_id" class="idls object">'.Getobject($res['object_id']).'</select></td>
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
	<input style="display: none;"  id="bank_person_id" value="' . $res['id'] . '" />';

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