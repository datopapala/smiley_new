<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$object_id		= $_REQUEST['id'];
		$page		= GetPage(Getobject($object_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	object.id,
										object.`name`
							    FROM 	object
							    WHERE 	object.actived=1");

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
	case 'save_object':
		$object_id 		= $_REQUEST['id'];
		$object_name    = $_REQUEST['name'];



		if($object_name != ''){
			if(!CheckobjectExist($object_name, $object_id)){
				if ($object_id == '') {
					Addobject( $object_id, $object_name);
				}else {
					Saveobject($object_id, $object_name);
				}

			} else {
				$error = '"' . $object_name . '" უკვე არის სიაში!';

			}
		}

		break;
	case 'disable':
		$object_id	= $_REQUEST['id'];
		Disableobject($object_id);

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

function Addobject($object_id, $object_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`object`
									(`user_id`,`name`)
					VALUES 		('$user_id','$object_name')");
}

function Saveobject($object_id, $object_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `object`
					SET    `user_id`='$user_id',
							 `name` = '$object_name'
					WHERE	`id` = $object_id");
}

function Disableobject($object_id)
{
	mysql_query("	UPDATE `object`
					SET    `actived` = 0
					WHERE  `id` = $object_id");
}

function CheckobjectExist($object_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `object`
											WHERE  `name` = '$object_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getobject($object_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `object`
											WHERE   `id` = $object_id" ));

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
					<td style="width: 170px;"><label for="CallType">სახელი</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>

			</table>
			<!-- ID -->
			<input type="hidden" id="object_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
