<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';


switch ($action) {
	
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
		$rResult = mysql_query("SELECT DISTINCT `realizations`.`id`,
												`realizations`.`id`,
												`realizations`.`CustomerID`,
	  											`realizations`.`Customer1CCode`,
												`realizations`.`CustomerName`,
												`realizations`.`CustomerPhone`,
												`realizations`.`CustomerAddress`,
												COUNT(realizations.CustomerName),
											  	SUM(`nomenclature`.`Sum`) AS jami,
									CASE WHEN SUM(`nomenclature`.`Sum`)>=5000 
											AND
										SUM(`nomenclature`.`Sum`)<7000
											THEN 'VIP Gold'
										WHEN SUM(`nomenclature`.`Sum`)>=7000 
											AND
										SUM(`nomenclature`.`Sum`)<10000
											THEN 'VIP Platinium'
										WHEN SUM(`nomenclature`.`Sum`)>10000 
											THEN 'VIP Briliant'
										WHEN SUM(`nomenclature`.`Sum`)<5000 
											THEN 'ლოიალური'
									END AS `status`
												
									FROM 	`realizations`
									JOIN 	nomenclature ON realizations.id=nomenclature.realizations_id
									GROUP BY realizations.CustomerName
	  								HAVING SUM(`nomenclature`.`Sum`)>10000	
	  										");
	  	
	  
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