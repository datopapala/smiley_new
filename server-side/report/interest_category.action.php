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
	    $prod		=	$_REQUEST['prod'];
	    $Result = mysql_query("		
									SELECT		production.id,
												production.`name`,
												COUNT(incomming_call.id)
									from 		incomming_call
									LEFT JOIN 	production  ON production.id = incomming_call.production_id
	    							WHERE 		DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')
									GROUP BY 	production.id");
	    $data = array(
	    		"aaData"	=> array()
	    );
	    
	    $aRow = mysql_fetch_array( $Result );
	    $sum ='';
	    if($aRow[0] == ''){
	    	$sum = $aRow[2];
	    }
	    
	    while ( $aRow = mysql_fetch_array( $Result ) )
	    {
	    	$row = array();
	    	for ( $i = 0 ; $i < $count ; $i++ )
	    	{
	    		/* General output */
	    		$row[] = $aRow[$i];
	    	}
	    	$data['aaData'][] = $row;
	    }
	    if($sum!= ''){
	    	$rowOther = array();
	    	$rowOther[] = 0;
	    	$rowOther[] = 'სხვა';
	    	$rowOther[] = $sum;
	    	$data['aaData'][] = $rowOther;
	    }
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