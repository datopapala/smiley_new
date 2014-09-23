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
		$brand_id		= $_REQUEST['id'];
		$page		= GetPage(Get_brand($brand_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	brand.id,
										brand.`name`
							    FROM 	brand
							    WHERE 	brand.actived=1");

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
	case 'save_brand':
		$brand_id 		= $_REQUEST['id'];
		$brand_name    = $_REQUEST['name'];



		if($brand_name != ''){
			if(!Checkbrand_Exist($brand_name, $brand_id)){
				if ($brand_id == '') {
					Addbrand( $brand_id, $brand_name);
				}else {
					Savebrand($brand_id, $brand_name);
				}

			} else {
				$error = '"' . $brand_name . '" უკვე არის სიაში!';

			}
		}

		break;
	case 'disable':
		$brand_id	= $_REQUEST['id'];
		Disablebrand($brand_id);

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

function Addbrand($brand_id, $brand_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`brand`
									(`user_id`,`name`)
					VALUES 		('$user_id','$brand_name')");
	//GLOBAL $log;
	//$log->setInsertLog('pay_aparat');
}

function Savebrand($brand_id, $brand_name)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('pay_aparat', $brand_id);
	$user_id	= $_SESSION['USERID'];
	
	mysql_query("	UPDATE `brand`
					SET     `user_id`='$user_id',
							`name` = '$brand_name'
					WHERE	`id` = $brand_id");
	//$log->setInsertLog('pay_aparat',$payaparat_id);
}

function Disablebrand($brand_id)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('pay_aparat', $brand_id);
	mysql_query("	UPDATE `brand`
					SET    `actived` = 0
					WHERE  `id` = $brand_id");
	//$log->setInsertLog('pay_aparat',$payaparat_id);
}

function Checkbrand_Exist($brand_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `brand`
											WHERE  `name` = '$brand_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Get_brand($brand_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `brand`
											WHERE   `id` = $brand_id" ));

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
			<input type="hidden" id="brand_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
