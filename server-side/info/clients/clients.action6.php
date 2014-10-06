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
	  								HAVING SUM(`nomenclature`.`Sum`)>5000
	  										and
	  										SUM(`nomenclature`.`Sum`)<=7000	
	  										
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