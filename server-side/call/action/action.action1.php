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

//action
$action_id			= $_REQUEST['id'];
$action_name		= $_REQUEST['action_name'];
$start_date			= $_REQUEST['start_date'];
$end_date			= $_REQUEST['end_date'];
$action_content	    = $_REQUEST['action_content'];

//task
$persons_id			    = $_REQUEST['persons_id'];
$task_type_id			= $_REQUEST['task_type_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];
$task_department_id 	= $_REQUEST['task_department_id'];
$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];



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
		$page		= GetPage(Getaction($action_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("	SELECT 			action.id,
													action.id,
	  												action.`name`,
													action.start_date,
													action.end_date,
													action.content,
													users.username
									FROM 			action
									
									JOIN users ON action.user_id=users.id
									WHERE 			action.actived=1 AND action.end_date < NOW()");
	  
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
	
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
* ******************************
*/
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

function Gettemplate($task_department_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `template`
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

function Getaction($action_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	action.id,
												action.`name` AS action_name,
												action.start_date AS start_date,
												action.end_date AS end_date,
												action.content AS action_content,
												task.task_type_id AS task_type_id,
												task.`comment` AS `comment`,
												task.priority_id AS priority_id,
												task.responsible_user_id AS responsible_user_id,
												task.template_id AS template_id
												
	
										FROM 	action
										left JOIN task ON task.action_id=action.id
										WHERE 	action.id=$action_id
									" ));
	
	return $res;
}

function GetLocalID(){
	GLOBAL $db;
	return $db->increment('action');
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
    <div id="add-edit-goods-form" title="აქცია">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 400px;">	
				<fieldset >
			    	<legend>ინფო</legend>
					<fieldset float:left;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>დასახელება</td>
								<td style="width:20px;></td>
								
								<td colspan "5">
									<input  type="text" id="action_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['action_name']. '" disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td style="width: 150px;"><label for="d_number">პერიოდი</label></td>
								<td>
									<input type="text" id="start_date" class="idle" onblur="this.className=\'idle\'" value="' . $res['start_date']. '" disabled="disabled" />
								</td>
								<td style="width: 150px;"><label for="d_number">-დან</label></td>
								<td>
									<input type="text" id="end_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['end_date']. '" disabled="disabled" />
								</td>
								<td style="width: 150px;"><label for="d_number">-მდე</label></td>
							</tr>
						</table>
									
					</fieldset>
					<fieldset style="float: left; width: 400px;">
						<legend>აღწერა</legend>
				    		<table width="100%" class="dialog-form-table">
							<tr>
								<td colspan="5">
									<textarea  style="width: 530px; height: 100px; resize: none;" id="action_content" class="idle" name="content" cols="100" rows="2" disabled="disabled">' . $res['action_content'] . '</textarea>
								</td>
							</tr>		
							</table>
					</fieldset>	
								
					<fieldset>
			    	<legend>დავალების ფორმირება</legend>
		
			    	<table class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="d_number">დავალების ტიპი</label></td>
							<td style="width: 180px;"><label for="d_number">სცენარი</label></td>
							<td style="width: 180px;"><label for="d_number">პრიორიტეტი</label></td>
						</tr>
			    		<tr>
							<td style="width: 180px;"><select id="task_type_id" class="idls object"disabled="disabled">'.Gettask_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="template_id" class="idls object"disabled="disabled">'. Gettemplate($res['template_id']).'</select></td>
							<td style="width: 180px;"><select id="priority_id" class="idls object"disabled="disabled">'.Getpriority($res['priority_id']).'</select></td>
						</tr>
						<tr>
							<td style="width: 180px;"><label for="d_number">ასუხისმგებელი პირი</label></td>
							<td style="width: 180px;"></td>
							<td style="width: 180px;"></td>
						</tr>		
						<tr>
							<td style="width: 180px;"><select style="width: 186px;" id="person_id" class="idls object"disabled="disabled">'.Getpersons($res['responsible_user_id']).'</select></td>
							<td  style="width: 180px;"></td>
							<td style="width: 180px;"></td>
						</tr>
						<tr>
							<td style="width: 150px;"><label for="content">კომენტარი</label></td>
							<td style="width: 150px;"><label for="content"></label></td>
							<td style="width: 150px;"><label for="content"></label></td>
						</tr>
						<tr>
							<td colspan="6">
								<textarea  style="width: 530px; height: 80px; resize: none;" id="comment" class="idle" name="content" cols="100" rows="2"disabled="disabled">' . $res['comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div style="float: right;  width: 360px;">
				</fieldset>
				<fieldset style="float: right;  width: 440px;">
					<legend>აქციის პროდუქტები</legend>
										
			<div id="dt_example" class="inner-table">
		        <div style="width:440px;" id="container" >        	
		            <div id="dynamic">
		            	<div id="button_area">
		            		<button id="add_button_p1"disabled="disabled">დამატება</button>
	        			</div>
		                <table class="" id="example4" style="width: 100%;">
		                    <thead>
								<tr  id="datatable_header">
										
		                           <th style="display:none">ID</th>
									<th style="width:7%;">#</th>
									<th style="width:25%; word-break:break-all;">ფილიალი</th>
									<th style="width:20%; word-break:break-all;">თარიღი</th>
									<th style="width:35%; word-break:break-all;">პროდუქტი</th>
									<th style="width:13%; word-break:break-all;">თანხა</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:70px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:65px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:30px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>

				</fieldset>
						<input type="hidden" id="action_id" value="'.$_REQUEST['id'].'"/>
	  			
			</div>
    </div>';

	return $data;
}



?>