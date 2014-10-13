<?php
require_once('../../includes/classes/core.php');
include('../../includes/classes/log.class.php');
$log 		= new log();
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$source_id		= $_REQUEST['id'];
		$page		= GetPage(Getsource($source_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	surce.id,
										surce.`name`
							    FROM 	surce
							    WHERE 	surce.actived=1");

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
	case 'save_source':
		$source_id 		= $_REQUEST['id'];
		$source_name    = $_REQUEST['name'];



		if($source_name != ''){
			if(!ChecksourceExist($source_name, $source_id)){
				if ($source_id == '') {
					Addsource($source_name);
				}else {
					Savesource($source_id, $source_name);
				}

			} else {
				$error = '"' . $source_name . '" უკვე არის სიაში!';

			}
		}

		break;
	case 'disable':
		$source_id	= $_REQUEST['id'];
		Disablesource($source_id);

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

function Addsource($source_name)
{
	mysql_query("INSERT INTO 	 	`surce`
									(`name`, `actived`)
							VALUES 		
									('$source_name', '1')");
	//GLOBAL $log;
	//$log->setInsertLog('template');
}

function Savesource($source_id, $source_name)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('template', $template_id);
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `surce`
					SET    `name` = '$source_name'
					WHERE	`id` = $source_id");
	//$log->setInsertLog('template',$template_id);
}

function Disablesource($source_id)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('template', $template_id);
	mysql_query("	UPDATE `surce`
					SET    `actived` = 0
					WHERE  `id` = $source_id");
	//$log->setInsertLog('template',$template_id);
}

function ChecksourceExist($source_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `surce`
											WHERE  `name` = '$source_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getsource($source_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `surce`
											WHERE   `id` = $source_id" ));

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
			<input type="hidden" id="source_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>

