<?php
require_once('../../includes/classes/core.php');
header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$result = mysql_query("SELECT	COUNT(*) AS `count1`,
								CONCAT('ნაპას',' ',COUNT(*),' ','ზარი') AS `cause` 
								FROM	queue_stats AS qs,
								qname AS q,
								qagent AS ag,
								qevent AS ac
								WHERE qs.qname = q.qname_id
								AND qs.qagent = ag.agent_id
								AND qs.qevent = ac.event_id
								AND DATE(qs.datetime) >= '$start' AND DATE(qs.datetime) <= '$end'
								AND q.queue IN ($queuet)
								AND ac.event IN ( 'COMPLETECALLER', 'COMPLETEAGENT')
						UNION ALL
						SELECT 	COUNT(*) AS `count`,
								CONCAT('უპასუხო',' ',COUNT(*),' ','ზარი') AS `cause`
								FROM	queue_stats AS qs,
										qname AS q,
										qagent AS ag,
										qevent AS ac
								WHERE qs.qname = q.qname_id
								AND qs.qagent = ag.agent_id
								AND qs.qevent = ac.event_id
								AND DATE(qs.datetime) >= '$start' 
								AND DATE(qs.datetime) <= '$end'
								AND q.queue IN ($queuet)
								AND ac.event IN ('ABANDON', 'EXITWITHTIMEOUT')");


$row = array();
$rows = array();
while($r = mysql_fetch_array($result)) {
	$row[0] = $r[1];
	$row[1] = (float)$r[0];
	array_push($rows,$row);
}

//mysql_close();
//require_once('../../includes/classes/core.php');

//$row_done_blank = mysql_query(" SELECT 	COUNT(*) AS `count`,
		//CONCAT('დამუშავებული-',COUNT(*)) AS `cause1`
		//FROM `incomming_call`
		//WHERE DATE(date) >= '$start' AND DATE(date) <= '$end' AND phone != '' ");

//$row1 = array();
//while($r1 = mysql_fetch_array($row_done_blank)) {
	//$row1[0] = $r1[1];
	//$row1[1] = $r1[0];
	//array_push($rows,$row1);
//}

echo json_encode($rows);

?>