<?php
/* ******************************
 *	Request aJax actions
* ******************************
*/

include('../../includes/classes/core.php');
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
									DATE(incomming_call.date)
									FROM 	incomming_call
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')
									GROUP BY DATE(incomming_call.date)");		
			
		$infoResult = mysql_query("
									SELECT DATE(incomming_call.date),
									COUNT(incomming_call.information_sub_category_id)
									FROM incomming_call
									WHERE DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end') &&  information_sub_category_id=2
									GROUP BY DATE(incomming_call.date)");
		$claimResult = mysql_query("
									SELECT DATE(incomming_call.date),
									COUNT(incomming_call.information_sub_category_id)
									FROM incomming_call
									WHERE DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end') &&  information_sub_category_id=1
									GROUP BY DATE(incomming_call.date)");
						 
		$offerResult = mysql_query("
									SELECT DATE(incomming_call.date),
									COUNT(incomming_call.information_sub_category_id)
									FROM incomming_call
									WHERE DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end') &&  information_sub_category_id=3
									GROUP BY DATE(incomming_call.date)");
		$otherResult = mysql_query("
								
									SELECT DATE(incomming_call.date),
									COUNT(incomming_call.information_sub_category_id)
									FROM incomming_call
									WHERE DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end') &&  information_sub_category_id=4
									GROUP BY DATE(incomming_call.date)");		
		$data = array(
				"aaData"	=> array()
		);
		$info	= mysql_fetch_array($infoResult);
		$claim	= mysql_fetch_array($claimResult);		
		$offer 	= mysql_fetch_array($offerResult);		
		$other	= mysql_fetch_array($otherResult);
		
		$infoSum = '0';
		$claimSum = '0';
		$offerSum = '0';
		$otherSum = '0';
		$sumSum  = '0';
		while ( $main = mysql_fetch_array($mainResult) )
		{
			
			$row = array();
			$sum = '0';
			$row[] = $main[0];
			$row[] = $main[0];
			
			if($info[0] == $main[0]){
				$row[] = $info[1];
				$sum = (int)$sum + (int)$info[1];
				$infoSum = (int)$infoSum + (int)$info[1];
				$info	= mysql_fetch_array($infoResult);
			}else{
				$row[] = '0';
			}
			
			if($claim[0] == $main[0]){
				$row[] = $claim[1];
				$sum = (int)$sum + (int)$claim[1];
				$claimSum = (int)$claimSum + (int)$claim[1];
				$claim	= mysql_fetch_array($claimResult);
			}else{
				$row[] = '0';
			}

			if($offer[0] == $main[0]){
				$row[] = $offer[1];
				$sum = (int)$sum + (int)$offer[1];
				$offerSum = (int)$offerSum + (int)$offer[1];
				$offer 	= mysql_fetch_array($offerResult);
			}else{
				$row[] = '0';
			}
			
			if($other[0] == $main[0]){
				$row[] =$other[1];
				$sum = (int)$sum + (int)$other[1];
				$otherSum = (int)$otherSum + (int)$other[1];				
				$other	= mysql_fetch_array($otherResult);
			}else{
				$row[] = '0';
			}
			
			$row[] = $sum;
			$sumSum = (int)$sumSum + (int)$sum;
			$data['aaData'][] = $row;
		}
		$row = array();
		$row[] = '0';
		$row[] = '<p style = "float: right;">ჯამში</p>';
		$row[] = $infoSum;
		$row[] = $claimSum ;
		$row[] = $offerSum ;
		$row[] = $otherSum ;
		$row[] = $sumSum ;				
		$data['aaData'][] = $row;
		
		

		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);
?>