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
	    

	    $allResult = mysql_query("	SELECT
													object.id 		as `id`,
													object.`name`	as `name`,
													COUNT(incomming_call.connect) AS `allCall`
									FROM incomming_call 
									RIGHT JOIN object ON object.id = incomming_call.redirect
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')
									GROUP BY object.`name`
									ORDER BY object.id");
	    $yesResult = mysql_query("
									SELECT
													object.id 		as `id`,
													object.`name`	as `name`,
													COUNT(incomming_call.connect) AS `yesCall`
									FROM incomming_call 
									RIGHT JOIN object ON object.id = incomming_call.redirect
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end') && incomming_call.connect = 1
									GROUP BY object.`name`
									ORDER BY object.id");
	    
	    $noResult = mysql_query("
									SELECT
													object.id 		as `id`,
													object.`name`	as `name`,
													COUNT(incomming_call.connect) AS `noCall`
									FROM incomming_call 
									RIGHT JOIN object ON object.id = incomming_call.redirect
									WHERE	DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end') && incomming_call.connect = 0
									GROUP BY object.`name`
									ORDER BY object.id");

		$data = array(
			"aaData"	=> array()
		);
		$Nocallsum = '0';
		$yescallsum = '0';
		$allcallsum = '0';
		$no		= mysql_fetch_array($noResult);
		$yes	= mysql_fetch_array($yesResult);		
		while ( $all = mysql_fetch_array($allResult) )
		{
			$row = array();
			$row[] = $all[0];
			$row[] = $all[1];
			if($all[0] == $yes[0] && $all[1] == $yes[1] ){
				$row[] = $yes[2];
				$yescallsum = (int) $yescallsum + (int)$yes[2];
				$yes  = mysql_fetch_array($yesResult);
				
			}else {
				$row[] = '0';
			}
			if($all[0] == $no[0] && $all[1] == $no[1] ){
				$row[] = $no[2];
				$Nocallsum = (int) $Nocallsum + (int)$no[2];
				$no  = mysql_fetch_array($noResult);
			}else {
				$row[] = '0';
			}
			$row[] = $all[2];
			$allcallsum = (int)$allcallsum + (int)$all[2];
			$data['aaData'][] = $row;
		}
		$row = array();
		$row[] = '0';
		$row[] = '<p style = "float: right;">ჯამში</p>';
		$row[] = $yescallsum;
		$row[] = $Nocallsum ;
		$row[] = $allcallsum ;
		$data['aaData'][] = $row;
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