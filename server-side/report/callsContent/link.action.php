<?php
/* ******************************
 *	Request aJax actions
* ******************************
*/

include('../../../includes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';

switch ($action) {
	case 'get_list' :
		$count		=	$_REQUEST['count'];
		$hidden		=	$_REQUEST['hidden'];
		$start		=	$_REQUEST['start'];
		$end		=	$_REQUEST['end'];
		
		$mainResult = mysql_query("	
									SELECT 
													info_category.id,
													info_category.`name`,
													COUNT(incomming_call.information_category_id)
									FROM  	incomming_call
									LEFT JOIN info_category ON info_category.id = incomming_call.information_category_id
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')
									GROUP BY info_category.id
									ORDER BY info_category.id ");		
		
		$infoResult = mysql_query("
									SELECT 
													info_category.id,
													info_category.`name`,
													COUNT(incomming_call.information_category_id)
									FROM  incomming_call
									RIGHT JOIN info_category ON info_category.id = incomming_call.information_category_id
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end') && information_sub_category_id = 2
									GROUP BY info_category.id
									ORDER BY info_category.id");
		$claimResult = mysql_query("
									SELECT 
													info_category.id,
													info_category.`name`,
													COUNT(incomming_call.information_category_id)
									FROM  incomming_call
									RIGHT JOIN info_category ON info_category.id = incomming_call.information_category_id
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')  &&  information_sub_category_id= 1
									GROUP BY info_category.id
									ORDER BY info_category.id");
						 
		$offerResult = mysql_query("
									
									SELECT 
													info_category.id,
													info_category.`name`,
													COUNT(incomming_call.information_category_id)
									FROM  incomming_call
									RIGHT JOIN info_category ON info_category.id = incomming_call.information_category_id
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')  && information_sub_category_id = 3
									GROUP BY info_category.id
									ORDER BY info_category.id");
		$otherResult = mysql_query("
									SELECT 
													info_category.id,
													info_category.`name`,
													COUNT(incomming_call.information_category_id)
									FROM  incomming_call
									RIGHT JOIN info_category ON info_category.id = incomming_call.information_category_id
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')  && information_sub_category_id = 0
									GROUP BY info_category.id
									ORDER BY info_category.id");		
		$data = array(
				"aaData"	=> array()
		);
		$info	= mysql_fetch_array($infoResult);
		$claim	= mysql_fetch_array($claimResult);		
		$offer 	= mysql_fetch_array($offerResult);		
		$other	= mysql_fetch_array($otherResult);
		
		$othersum = '0';
		
		
		
		while ( $main = mysql_fetch_array($mainResult) )
		{
			
			$row = array();
			
			if($main[0] != ''){
				
				$row[0] = $main[0];
				$row[1] = $main[1];
				if( $info[0] == $main[0] ){
					$row[2] = $info[2];
					$info	= mysql_fetch_array($infoResult);
				}else{
					$row[2] = '0';
				}
				
				if($claim[0] == $row[0]){
					$row[3] = $claim[2];
					$claim	= mysql_fetch_array($claimResult);
				}else{
					
					$row[3] = '0';
				}				
				if($offer[0] == $row[0]){
					$row[4] = $offer[2];
					$offer 	= mysql_fetch_array($offerResult);
				}else{
					$row[4] = '0';
				}	
							
				if($other[0] == $row[0]){
					$row[5] = $other[2];
					$other	= mysql_fetch_array($otherResult);
				}else{
					$row[5] = '0';
				}
				
				if( $row[0] == '46' ){
					$row[5] =(int)$row[5] + (int)$othersum;
				}				
				$data['aaData'][] = $row;				
			}else{
				$othersum = (int)$othersum + (int)$main[2];
			}		
			

			
			
		}
		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);
?>