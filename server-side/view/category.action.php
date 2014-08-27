<?php
/* ******************************
 *	Category aJax actions
 * ******************************
*/

include('../../includes/classes/core.php');
include('../../includes/classes/category.tree.class.php');
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
	    $cat_id		= $_REQUEST['id'];
		$page		= GetPage(GetCategory($cat_id));
        
        $data		= array('page'	=> $page);
        
        break;
 	case 'get_list' :
		$count	= $_REQUEST['count'];
	    $hidden	= $_REQUEST['hidden'];
	    
	    $rResult = mysql_query("SELECT	`prod`.`id`,
                                		`prod`.`name`,
                                		(SELECT `name` FROM `category` WHERE `id` = `prod`.`parent_id`)
							    FROM	`category` AS `prod`
	    						WHERE	`actived` = 1");
	    
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
    case 'save_category':
		$cat_id 		= $_REQUEST['id'];
		$par_id 		= $_REQUEST['par_id'];
		
    	$cat_name		= htmlspecialchars($_REQUEST['cat'], ENT_QUOTES);
		
		if($cat_name != '' && $cat_id == ''){
			if(!CheckCategoryExist($cat_name, $par_id)){
				AddCategory($cat_name, $par_id);
			} else {
				$error = '"' . $cat_name . '" უკვე არის სიაში!';
			}
		}else{
			SaveCategory($cat_id, $cat_name, $par_id);
		}
		
        break;
    case 'disable':
		$cat_id	= $_REQUEST['id'];
		DisableCategory($cat_id);
		
        break;
	case 'get_tree':
		$page		= GetTree();
		$data		= array('page'	=> $page);
        
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

function AddCategory($cat_name, $par_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO `category`
					(`user_id`,`name`, `parent_id`) 
				 VALUES
					('$user_id','$cat_name', $par_id)");
	GLOBAL $log;
	$log->setInsertLog('category');
}

function SaveCategory($cat_id, $cat_name, $par_id)
{
	GLOBAL $log;
	$log->setUpdateLogAfter('category', $cat_id);
	$user_id	= $_SESSION['USERID'];
	mysql_query("UPDATE
	    			`category`
				 SET `user_id`='$user_id',
				    `name` = '$cat_name',
				    `parent_id`	= $par_id
				 WHERE
					`id` = $cat_id");
	$log->setInsertLog('category',$cat_id);
}

function DisableCategory($cat_id)
{
	GLOBAL $log;
	$log->setUpdateLogAfter('category', $cat_id);
    mysql_query("UPDATE `category`
				 SET    `actived` = 0
				 WHERE	`id` = $cat_id");
    $log->setInsertLog('category',$cat_id);
}

function CheckCategoryExist($cat_name, $par_id) 
{
    $res = mysql_fetch_assoc(mysql_query("SELECT `id`
										  FROM   `category`
										  WHERE  `name` = '$cat_name' && `parent_id` = $par_id && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}

function GetParentCategory($point)
{
	$data = '';
	
	$data = '<option value="0" selected="selected"></option>';	
	$tree = new CategoryTree($point);	
	$data .= $tree->GetData();
	
	return $data;
}

function GetTree()
{
	$result=mysql_query("	SELECT	`id`,
									`name`,
									`parent_id`
							FROM `category`
							WHERE `actived` = 1");
	
	if(mysql_num_rows($result) > 0){
		$cats = array();
		while($cat =  mysql_fetch_assoc($result))
			$cats[$cat['parent_id']][] =  $cat;
		
		return build_tree($cats, 0);
	}else{		
		return '';
	}
}

function build_tree($cats, $parent_id){
	if(is_array($cats) and  count($cats[$parent_id])>0){
		$tree = '<ul>';
		foreach($cats[$parent_id] as $cat){
			$tree .= '<li>'.$cat['name'];
			$tree .=  build_tree($cats,$cat['id']);
			$tree .= '</li>';
		}
		$tree .= '</ul>';
	}
	else {
		return null;
	}

	return $tree;
}

function GetCategory($cat_id) 
{
    $res = mysql_fetch_assoc(mysql_query("SELECT `id`,
    											 `name`,
    											 `parent_id`
									      FROM   `category`
									      WHERE  `id` = $cat_id" ));
    
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
					<td style="width: 170px;"><label for="category">ქვე კატეგორია</label></td>
					<td>
						<input type="text" id="category" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="category">კატეგორია</label></td>
					<td>
						<select id="parent_id" class="idls large">' . GetParentCategory($res[parent_id]) . '</select>
					</td>
				</tr>
			</table>
			<!-- ID -->
			<input type="hidden" id="cat_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>