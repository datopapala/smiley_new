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
									SELECT
												production.id,
												production.`name`,
												COUNT(incomming_call.production_id)
									FROM incomming_call
									RIGHT JOIN production ON production.id = incomming_call.production_id 
									WHERE production.production_category_id = $prod && DATE(incomming_call.date)  BETWEEN  date('$start')  And date('$end')
									GROUP BY production.id");
	    $data = array(
	    		"aaData"	=> array()
	    );
	    	    
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