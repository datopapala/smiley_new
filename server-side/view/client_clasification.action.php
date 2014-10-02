<?php
require_once('../../includes/classes/core.php');
include('../../includes/classes/log.class.php');

$log 		= new log();
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';
$id 			= $_REQUEST['id'];
$name    		= $_REQUEST['name'];
$amount_end    	= $_REQUEST['amount_end'];
$amount_start   = $_REQUEST['amount_start'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$object_id		= $_REQUEST['id'];
		$page		= GetPage(Getclass($id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
			$rResult = mysql_query("SELECT client_clasification.id,
											client_clasification.`name`,
											client_clasification.amount_start,
											client_clasification.amount_end
								FROM 		client_clasification
								WHERE		client_clasification.actived=1	");

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
	case 'save_clasification':
	
		if($name != ''){
			if(!Checkclass_Exist($name, $id)){
				if ($id == '') {
					Addclass($name, $amount_start, $amount_end);
				}else {
					Saveclass($id, $name,$amount_start,$amount_end);
				}

			} else {
				$error = '"' . $name . '" უკვე არის სიაში!';

			}
		}
		
		break;
	case 'disable':
		$id	= $_REQUEST['id'];
		Disableclass($id);

		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Category Functions
* ******************************
*/

function Addclass($name, $amount_start, $amount_end)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`client_clasification`
									(`user_id`,`name`, `amount_start`,`amount_end`, `actived`)
					VALUES 		
									('$user_id','$name', '$amount_start','$amount_end', 1)");
	//GLOBAL $log;
	//$log->setInsertLog('object');
}

function Saveclass($id, $name,$amount_start,$amount_end)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('object', $object_id);
	$user_id	= $_SESSION['USERID'];
		mysql_query("	UPDATE client_clasification 
						SET		`user_id`		='$user_id',
								`name`			='$name',
								amount_start	='$amount_start',
								amount_end		='$amount_end',
								actived			=1
						WHERE 	id=$id");
	//$log->setInsertLog('object',$object_id);
}

function Disableclass($id)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('object', $object_id);
	mysql_query("	UPDATE `client_clasification`
					SET    `actived` = 0
					WHERE  `id` = $id");
	//$log->setInsertLog('object',$object_id);
}

function Checkclass_Exist($name, $id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `client_clasification`
											WHERE  `name` = '$name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getclass($id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`,
													`amount_start`,
													`amount_end`
											FROM	client_clasification
											WHERE   `id` = $id" ));

	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>

	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 170px;"><label for="CallType">დასახელება</label></td>
					<td>
						<input style="width: 227px  !important;" type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="CallType">თანხა</label></td>
					<td>
						<input onkeypress="{if (event.which != 8 &amp;&amp; event.which != 0 &amp;&amp; event.which!=46 &amp;&amp; (event.which < 48 || event.which > 57)) {$(\'#errmsg\').html(\'მხოლოდ რიცხვი\').show().fadeOut(\'slow\'); return false;}}" style="width: 107px !important; type="text" id="amount_start" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['amount_start'] . '" />
						<span id="errmsg" style="color: red; display: none;">მხოლოდ რიცხვი</span>
					</td>
					<td style="width: 107px !important;"><label style="position: relative; left: -116px;" for="CallType">-დან</label></td>
				</tr>
				<tr>
					<td style="width: 107px !important;"><label for="CallType">თანხა</label></td>
					<td>
						<input onkeypress="{if (event.which != 8 &amp;&amp; event.which != 0 &amp;&amp; event.which!=46 &amp;&amp; (event.which < 48 || event.which > 57)) {$(\'#errmsg\').html(\'მხოლოდ რიცხვი\').show().fadeOut(\'slow\'); return false;}}" style="width: 107px !important; type="text" id="amount_end" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['amount_end'] . '" />
						<span id="errmsg" style="color: red; display: none;">მხოლოდ რიცხვი</span>
					</td>
				<td ><label  style="position: relative; left: -116px;"  for="CallType">-მდე</label></td>
				</tr>

			</table>
			<!-- ID -->
			<input type="hidden" id="id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
