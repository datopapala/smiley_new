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
	  	$rResult = mysql_query("SELECT DISTINCT	`client`.`id`,
												`client`.`id`,
												`client`.`code`,
												`legal_status`.`name`,
												`client`.`name`,
												`client`.`phone`,
												`client`.`mail`,
											  	(SELECT COUNT(`client_sale`.`client_id`)  FROM client_sale WHERE client.id=client_sale.client_id) AS mtvleli,
												(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id) AS jami,
												
								CASE WHEN (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>=5000 
										AND
											(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<7000
										THEN 'VIP Gold'
									 WHEN (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>=7000 
										AND
											(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<10000
										THEN 'VIP Platinium'
									WHEN(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>10000 
										THEN 'VIP Briliant'
									WHEN(SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<5000 
										THEN 'ლოიალური'
								END AS `status`
																						
								FROM 	`client`
								left JOIN 	`legal_status` ON `client`.`legal_status_id` = `legal_status`.`id`
								left JOIN 	client_sale ON client.id=client_sale.client_id
								WHERE (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>7000
									  	AND 
									  (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<10000");
	  
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