<?php
require_once('../../includes/classes/core.php');
include('../../includes/classes/log.class.php');

$log 		= new log();
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

$id						= $_REQUEST['id'];
$name					= $_REQUEST['name'];
$price					= $_REQUEST['price'];
$production_category_id	= $_REQUEST['production_category_id'];
$production_brand_id	= $_REQUEST['production_brand_id'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		
		$page		= GetPage(Get_production($id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	production.id,
										production.`name`,
										production.`production_category`,
										production.`brand`,
										production.price
								FROM 	production
								WHERE 	production.actived=1");

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
	case 'save_production':
		$paytype_id 		= $_REQUEST['id'];
		$paytype_name    = $_REQUEST['name'];



		if($name != ''){
			if(!Checkproduction_Exist($name, $id)){
				if ($id == '') {
					Addproduction($name, $production_category_id,$production_brand_id,$price);
				}else {
					Saveproduction($id,$name, $production_category_id, $production_brand_id, $price);
				}

			} else {
				$error = '"' . $name . '" უკვე არის სიაში!';

			}
		}

		break;
	case 'disable':
		Disableproduction($id);

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

function Addproduction($name, $production_category_id,$production_brand_id,$price)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO `production` 
									(`user_id`, `name`, `production_category`, `brand`, `price`, `actived`)
										VALUES 
									( '$user_id', '$name', '$production_category_id', '$production_brand_id', '$price', '1');
										");
	//GLOBAL $log;
	//$log->setInsertLog('pay_type');
}

function Saveproduction($id,$name, $production_category_id, $production_brand_id, $price)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('pay_type', $paytype_id);
	$user_id	= $_SESSION['USERID'];
	mysql_query("UPDATE `production` 
				SET 	`user_id`='$user_id', 
						`name`='$name', 
						`production_category`='$production_category_id', 
						`brand`='$production_brand_id', 
						`price`='$price' 
				WHERE 	(`id`='$id');");
	//$log->setInsertLog('pay_type',$paytype_id);
}

function Disableproduction($id)
{
	//GLOBAL $log;
	//$log->setUpdateLogAfter('pay_type', $paytype_id);
	mysql_query("	UPDATE `production`
					SET    `actived` = 0
					WHERE  `id` = $id");
	//$log->setInsertLog('pay_type',$paytype_id);
}

function Checkproduction_Exist($name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `production`
											WHERE  `name` = '$name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}
function Get_production_category($production_category_id)
{
	$data = '';
	$req = mysql_query("SELECT DISTINCT production.production_category as id,
										roduction.production_category
						FROM    		production
						WHERE   		actived = 1 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['production_category'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['production_category'] . '</option>';
		}
	}

	return $data;
}

function Get_production_brand($production_brand_id)
{
	$data = '';
	$req = mysql_query("SELECT DISTINCT	production.brand as id,
										production.brand
						FROM    		production
						WHERE   		actived = 1 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_brand_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['brand'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['brand'] . '</option>';
		}
	}

	return $data;
}
function Get_production($id)
{
	$res = mysql_fetch_assoc(mysql_query("SELECT 	production.id,
													production.`name`,
													production.`production_category`,
													production.brand,
													production.price
											FROM 	production
											WHERE 	id=5=$id
							" ));
		
	return $res;
}



function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
			<legend>ძირითადი ინფორმაცია</legend>
				<table style="height: 161px;" class="dialog-form-table">
				<tr>
					<td style="width: 170px;"><label for="CallType">დასახელება</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
								
				<tr>
					<td style="width: 170px;"><label for="CallType">კატეგორია</label></td>
					<td style="width: 170px;"><select id="production_category_id" class="idle address">'. Get_production_category($res['production_category']).'</select></td>
				</tr>
							
				<tr>
					<td style="width: 170px;"><label for="CallType">ბრენდი</label></td>
					<td style="width: 170px;"><select  id="production_brand_id" class="idle address">'. Get_production_brand($res['brand']).'</select></td>
				</tr>
							
				<tr>
					<td style="width: 170px;"><label for="CallType">ფასი</label></td>
					<td>
						<input type="text" id="price" class="idle address" onblur="this.className=\'idle address\'" value="' . $res['price'] . '" />
					</td>
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
