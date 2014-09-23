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

//action
$action_id			= $_REQUEST['id'];
$action_id1			= $_REQUEST['id1'];
$action_name		= $_REQUEST['action_name'];
$start_date			= $_REQUEST['start_date'];
$end_date			= $_REQUEST['end_date'];
$action_content	    = $_REQUEST['action_content'];
$person_id			= $_REQUEST['person_id'];
$hidden_inc			= $_REQUEST['hidden_inc'];
$edit_id			= $_REQUEST['edit_id'];
$delete_id			= $_REQUEST['delete_id'];


//task
$comment			= $_REQUEST['comment'];
$task_type_id		= $_REQUEST['task_type_id'];
$priority_id		= $_REQUEST['priority_id'];
$template_id		= $_REQUEST['template_id'];
$person_id			= $_REQUEST['person_id'];





// file
$rand_file			= $_REQUEST['rand_file'];
$file				= $_REQUEST['file_name'];

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
									WHERE 			action.actived=1 AND action.end_date >= NOW()");
	  
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
	case 'save_action':
			$task_type_id			= $_REQUEST['task_type_id'];
		if($action_id == ''){			
			Addaction($action_id1,  $action_name,  $start_date, $end_date, $action_content);
			$task_id = mysql_insert_id();
			if($task_type_id != 0){
			Addtask($person_id,$task_id, $task_type_id,  $priority_id, $template_id,  $comment);
			}
		}else {			
			saveaction($action_id, $action_name,  $start_date, $end_date, $action_content);
			Savetask($action_id,$person_id, $task_type_id,  $priority_id, $template_id,  $comment);
			
		}
		break;
		
		case 'delete_file':
		
			mysql_query("DELETE FROM file WHERE id = $delete_id");
		
			$increm = mysql_query("	SELECT  `name`,
					`rand_name`,
					`id`
					FROM 	`file`
					WHERE   `action_id` = $edit_id
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
				`action_id`,
				`name`,
				`rand_name`
				)
				VALUES
				(	'$user',
				'$edit_id',
				'$file',
				'$rand_file'
				);");
				//GLOBAL $log;
				//$log->setInsertLog('file');
				}
		
				$increm = mysql_query("	SELECT  `name`,
				`rand_name`,
				`id`
				FROM 	`file`
				WHERE   `action_id` = $edit_id
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
	
		
	
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
* ******************************
*/

function Addaction($action_id1,  $action_name,  $start_date, $end_date, $action_content){
	$rand_file			= $_REQUEST['rand_file'];
	$file				= $_REQUEST['file_name'];
	$hidden_inc			= $_REQUEST['hidden_inc'];
	$user		= $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `action` 
							(`id`,`user_id`, `name`, `start_date`, `end_date`, `content`, `actived`) 
						VALUES
							 ('$action_id1','$user', '$action_name', '$start_date', '$end_date', '$action_content', '1');
	");
	
	//GLOBAL $log;
	//$log->setInsertLog('action');
	if($rand_file != ''){
		mysql_query("INSERT INTO 	`file`
		( 	`user_id`,
		`task_id`,
		`name`,
		`rand_name`
		)
		VALUES
		(	'$user',
		'$hidden_inc',
		'$file',
		'$rand_file'
		);");
		//GLOBAL $log;
		//$log->setInsertLog('file');
	}
	
}

function Addtask($person_id,$task_id, $task_type_id,  $priority_id, $template_id,  $comment)
{
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO	`task` 
									(`user_id`,
									`responsible_user_id`,
									`action_id`,
									 `task_type_id`,
									 `priority_id`,
									 `template_id`,
									 `comment`
									)
						VALUES
									('$user',
									'$person_id',
									'$task_id',
									'$task_type_id',
									'$priority_id',
								    '$template_id',
								  	'$comment'
								   )");
	
	
}


				
function saveaction($action_id,  $action_name,  $start_date, $end_date, $action_content)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('action', $action_id);
	$user		= $_SESSION['USERID'];
	mysql_query("UPDATE `action` SET 
									`user_id`='$user',
									`name`='$action_name',
									`start_date`='$start_date', 
									`end_date`='$end_date', 
									`content`='$action_content', 
									`actived`='1' 
				WHERE 				`id`='$action_id'");
	
	//$log->setInsertLog('action',$action_id);
	
}       
function Savetask($action_id,$person_id, $task_type_id,  $priority_id, $template_id,  $comment)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('task', $task_id);
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET  	 `user_id`='$user',
										`responsible_user_id`='$person_id',
									 	 `task_type_id`='$task_type_id',
										 `priority_id`='$priority_id', 
										 `template_id`='$template_id', 
										 `comment`='$comment' 
										  WHERE (`action_id`='$action_id');");
	//$log->setInsertLog('task',$task_id);
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
										LEFT JOIN task ON task.action_id=action.id
										WHERE 	action.id=$action_id
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
			`rand_name`,
			`id`
			FROM 	`file`
			WHERE   `action_id` = $res[id]
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
									<input  type="text" id="action_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['action_name']. '"  />
								</td>
							</tr>
							<tr>
								<td style="width: 150px;"><label for="d_number">პერიოდი</label></td>
								<td>
									<input type="text" id="start_date" class="idle" onblur="this.className=\'idle\'" value="' . $res['start_date']. '" />
								</td>
								<td style="width: 150px;"><label for="d_number">-დან</label></td>
								<td>
									<input type="text" id="end_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['end_date']. '"  />
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
									<textarea  style="width: 530px; height: 100px; resize: none;" id="action_content" class="idle" name="content" cols="100" rows="2">' . $res['action_content'] . '</textarea>
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
							<td style="width: 180px;"><select id="task_type_id" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
							<td style="width: 180px;"><select id="template_id" class="idls object">'. Gettemplate($res['template_id']).'</select></td>
							<td style="width: 180px;"><select id="priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
						</tr>
						<tr>
							<td style="width: 180px;"><label for="d_number">ასუხისმგებელი პირი</label></td>
							<td style="width: 180px;"></td>
							<td style="width: 180px;"></td>
						</tr>		
						<tr>
							<td style="width: 180px;"><select style="width: 186px;" id="person_id" class="idls object">'.Getpersons($res['responsible_user_id']).'</select></td>
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
								<textarea  style="width: 530px; height: 80px; resize: none;" id="comment" class="idle" name="content" cols="100" rows="2">' . $res['comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div style="float: right;  width: 461px;">
				</fieldset>
				<fieldset style="float: right;  width: 440px;">
					<legend>აქციის პროდუქტები</legend>
										
			<div id="dt_example" class="inner-table">
		        <div style="width:440px;" id="container" >        	
		            <div id="dynamic">
		            	<div id="button_area">
		            		<button id="add_button_p">დამატება</button>
	        			</div>
		                <table class="" id="example3" style="width: 100%;">
		                    <thead>
								<tr  id="datatable_header">
										
		                           <th style="display:none">ID</th>
									<th style="width:7%;">#</th>
									<th style="width:25%; word-break:break-all;">ფილიალი</th>
									<th style="width:20%; word-break:break-all;">თარიღი</th>
									<th style="width:33%; word-break:break-all;">პროდუქტი</th>
									<th style="width:15%; word-break:break-all;">თანხა</th>
								</tr>
							</thead>
					</table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
				</fieldset>
				<fieldset>		
					<legend>ფაილი</legend>
					<table style="float: right; border: 1px solid #85b1de; width: 150px; text-align: center;">
     <tr>
      <td>
       <div class="file-uploader">
        <input id="choose_file" type="file" name="choose_file" class="input" style="display: none;">
        <button id="choose_button" class="center">აირჩიეთ ფაილი</button>
        <input id="hidden_inc" type="text" value="'. increment('action') .'" style="display: none;">
       </div>
      </td>
     </tr>
    </table>
        <table style="float: right; border: 1px solid #85b1de; width: 150px; text-align: center;">
             <tr style="border-bottom: 1px solid #85b1de;">
              <td colspan="3">მიმაგრებული ფაილი</td>
             </tr>
    </table>
    <table id="file_div" style="float: right; border: 1px solid #85b1de; width: 150px; text-align: center;">';
     
     while($increm_row = mysql_fetch_assoc($increm)) { 
      $data .=' 
                <tr style="border-bottom: 1px solid #85b1de;">
                  <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>              
                  <td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
                  <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
                </tr>';
     }
            
  $data .= '
    </table>				
						<input type="hidden" id="action_id" value="'.(($res['id']!='')?$res['id']:increment('action')).'"/>
	  			
			</div>
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