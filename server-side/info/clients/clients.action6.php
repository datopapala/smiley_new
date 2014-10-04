<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
include('../../../includes/classes/log.class.php');

$log 		= new log();
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
									WHEN  (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>10000 
										THEN 'VIP Briliant'
									WHEN  (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)<5000 
										THEN 'ლოიალური'
								END AS `status`
																		
								
								FROM 	`client`
								left JOIN 	`legal_status` ON `client`.`legal_status_id` = `legal_status`.`id`
								left JOIN 	client_sale ON client.id=client_sale.client_id
								WHERE (SELECT SUM(`client_sale`.`price`)  FROM client_sale WHERE client.id=client_sale.client_id)>10000");
									  
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


function getCalls(){
	$db1 = new sql_db ( "212.72.155.176", "root", "Gl-1114", "asteriskcdrdb" );

	$req = mysql_query("

						SELECT  	DISTINCT
									IF(SUBSTR(cdr.src, 1, 3) = 995, SUBSTR(cdr.src, 4, 9), cdr.src) AS `src`
						FROM    	cdr
						GROUP BY 	cdr.src
						ORDER BY 	cdr.calldate DESC
						LIMIT 		12


						");

	$data = '<tr class="trClass">
					<th class="thClass">#</th>
					<th class="thClass">ნომერი</th>
					<th class="thClass">ქმედება</th>
				</tr>
			';
	$i	= 1;
	while( $res3 = mysql_fetch_assoc($req)){

		$data .= '
	    		<tr class="trClass">
					<td class="tdClass">' . $i . '</td>
					<td class="tdClass" style="width: 30px !important;">' . $res3['src'] . '</td>
					<td class="tdClass" style="font-size: 13px !important;"><button class="insert" number="' . $res3['src'] . '">დამატება</button></td>
				</tr>';
		$i++;
	}

	return $data;


}


?>