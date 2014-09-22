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
	    
	    mysql_query("SET @i = 0;");
	    $rResult = mysql_query("SELECT		`incomming_call`.`id`,
											@i := @i + 1 AS `iterator`,
											`incomming_call`.`date`,
											`incomming_call`.`phone`,
											`info_category`.`name`,
											`incomming_call`.`content`,
	    									persons.`name`
							    FROM		`incomming_call` 
	    						LEFT JOIN 	`info_category` ON	`incomming_call`.`information_sub_category_id` = `info_category`.`id`
	    						JOIN   		`users` ON  `incomming_call`.`user_id` = `users`.`id`
								JOIN  		`persons` ON  `persons`.`id` = `users`.`person_id`
	    						WHERE		DATE(date)  BETWEEN  date('$start')  And date('$end')");

		$data = array(
			"aaData"	=> array()
		);
		
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
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


?>
