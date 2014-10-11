<?php
include('../../includes/classes/core.php');
$start  	= $_REQUEST['start'];
$end    	= $_REQUEST['end'];
$count 		= $_REQUEST["count"];
$action 	= $_REQUEST['act'];
$departament= $_REQUEST['departament'];
$type       = $_REQUEST['type'];
$category   = $_REQUEST['production_category_id'];
$s_category = $_REQUEST['sub_category'];
$done 		= $_REQUEST['done']%2;
$name 		= $_REQUEST['name'];
$title 		= $_REQUEST['title'];
$text[0] 	= "პრეტენზიები ბრენდების  მიხედვით";
$text[1] 	= "პროდუქტები '$category' მიხედვით";
$text[2] 	= "'$departament'- შემოსული  '$category' მიხედვით";
$text[3] 	= "'$departament'- შემოსული  ქვე–კატეგორიის მიხედვით";
//------------------------------------------------query-------------------------------------------
switch ($done){
	case  1:
		$result = mysql_query("	SELECT 	incomming_call.`production_id` AS c_name,
										COUNT(*),
										CONCAT(ROUND(COUNT(*)/(
											SELECT COUNT(*) FROM 		incomming_call
											WHERE information_sub_category_id=37  AND production_brand_id='$category' and DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
											GROUP BY c_name
													)*100,2),'%')

								FROM 		incomming_call
								WHERE information_sub_category_id=37  AND production_brand_id='$category' and DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
										GROUP BY c_name");
		$text[0]=$text[1];
	break;
	/* case 2:
			$result = mysql_query("SELECT info_category.`name`,
											COUNT(*),
											CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*)
												FROM 	incomming_call
												JOIN 	info_category ON info_category.id=incomming_call.information_category_id
												JOIN department ON incomming_call.department_id=department.id
												WHERE (incomming_call.call_type_id=$c) AND department.`name`='$departament' and DATE(`incomming_call`.`date`) >= '$start' and  DATE(`incomming_call`.`date`) <= '$end'
												)*100,2),'%')
									FROM 	incomming_call
									JOIN 	info_category ON info_category.id=incomming_call.information_category_id
									JOIN department ON incomming_call.department_id=department.id
									WHERE (incomming_call.call_type_id=$c) AND department.`name`='$departament' and DATE(`incomming_call`.`date`) >= '$start' and  DATE(`incomming_call`.`date`) <= '$end'
									GROUP BY info_category.`name`");
		$text[0]=$text[2];
	break;
	case 3:
		$result = mysql_query("SELECT 	info_category.`name` as c_name,
										COUNT(*),
										CONCAT(ROUND(
										COUNT(*)/(SELECT COUNT(*)
											FROM incomming_call
											JOIN info_category AS inf1 ON inf1.`name`='$category'
											JOIN info_category ON info_category.id=incomming_call.information_sub_category_id AND info_category.parent_id=inf1.id
											JOIN department ON incomming_call.department_id=department.id
											WHERE (incomming_call.call_type_id=$c) and DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$departament'
											)*100,2),'%')
								FROM 	incomming_call
								JOIN 	info_category AS inf1 ON inf1.`name`='$category'
								JOIN 	info_category ON info_category.id=incomming_call.information_sub_category_id AND info_category.parent_id=inf1.id
								JOIN 	department ON incomming_call.department_id=department.id
								WHERE (incomming_call.call_type_id=$c) and DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$departament'
								GROUP BY c_name");
		$text[0]=$text[3];
		break; */
	default:
		$result = mysql_query("SELECT 	incomming_call.`production_brand_id` AS c_name,
										COUNT(*),
										CONCAT(ROUND(COUNT(*)/(
											SELECT COUNT(*) FROM incomming_call
											WHERE incomming_call.information_sub_category_id=37 and DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
												)*100,2),'%')
							FROM 		incomming_call

							WHERE incomming_call.information_sub_category_id=37 and DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
							GROUP BY c_name");

		break;
}
///----------------------------------------------act------------------------------------------
switch ($action) {
	case "get_list":
		$data = array("aaData"	=> array());
		while ( $aRow = mysql_fetch_array( $result ) )
		{	$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				$row[0] = '0';

				$row[$i+1] = $aRow[$i];
			}
			$data['aaData'][] =$row;
		}
		echo json_encode($data); return 0;
		break;
	case 'get_category' :
		$rows = array();
		while($r = mysql_fetch_array($result)) {
			$row[0] = $r[0];
			$row[1] = (float) $r[1];
			$rows['data'][]=$row;
		}
		$rows['text']=$text[0];
		echo json_encode($rows);
		break;
	default :
		echo "Action Is Null!";
		break;

}



?>