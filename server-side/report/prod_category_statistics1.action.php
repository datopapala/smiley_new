<?php
require_once('../../includes/classes/core.php');

header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$result = mysql_query("SELECT	COUNT(*) AS `count1`,
								CONCAT('ნაპასუხები ',' ',COUNT(*),' ','ზარი') AS `cause` 
								FROM	queue_stats AS qs,
								qname AS q,
								qagent AS ag,
								qevent AS ac
								WHERE qs.qname = q.qname_id
								AND qs.qagent = ag.agent_id
								AND qs.qevent = ac.event_id
								AND DATE(qs.datetime) >= '$start' AND DATE(qs.datetime) <= '$end'
								AND q.queue IN ($queuet)
								AND ac.event IN ( 'COMPLETECALLER', 'COMPLETEAGENT')");
					
$row = array();
$rows = array();
while($r = mysql_fetch_array($result)) {
	$row[0] = $r[1];
	$row[1] = (float)$r[0];
	array_push($rows,$row);
}

$row_done_blank = mysql_query(" SELECT 	COUNT(*) AS `count`,
		CONCAT('დამუშავებული',' ',COUNT(*),' ','ზარი') AS `cause1`
		FROM `incomming_call`
		WHERE DATE(date) >= '$start' AND DATE(date) <= '$end' AND phone != '' ");

$row1 = array();
while($r1 = mysql_fetch_array($row_done_blank)) {
	$row1[0] = $r1[1];
	$row1[1] = (float)$r1[0];
	array_push($rows,$row1);
}

echo json_encode($rows);

?>