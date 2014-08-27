<style type='text/css'>
#box-table-b
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	text-align: center;
	border-collapse: collapse;
	border-top: 7px solid #9baff1;
	border-bottom: 7px solid #9baff1;
}
#box-table-b th
{
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #e8edff;
	border-right: 1px solid #9baff1;
	border-left: 1px solid #9baff1;
	color: #039;
}
#box-table-b td
{
	padding: 8px;
	background: #e8edff; 
	border-right: 1px solid #aabcfe;
	border-left: 1px solid #aabcfe;
	color: #669;
}
</style>

<?php

mysql_connect('212.72.155.176', 'root', 'Gl-1114');
mysql_select_db('stats');

//require_once '../../includes/classes/asteriskcore.php';
mysql_query("SET @i=0;");
$res = mysql_query("SELECT 		@i := @i + 1 AS `id`,
								qname.queue,
								COUNT(*) AS `quant`,
								ROUND((COUNT(*) / (SELECT COUNT(*) FROM queue_stats WHERE queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()) * 100), 2) AS `percent`
					FROM 		`queue_stats`
					JOIN 		qname ON queue_stats.qname = qname.qname_id
					WHERE 		queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()
					GROUP BY 	queue_stats.qname"); 

$res1 = mysql_query("SELECT 	@i := @i + 1 AS `iterator`,
								qagent.agent,
								COUNT(*) AS `quant`,
								ROUND((COUNT(*) / (SELECT COUNT(*) FROM queue_stats WHERE queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()) * 100), 2) AS `percent`
					FROM 		`queue_stats`
					JOIN 		qagent ON queue_stats.qagent = qagent.agent_id
					WHERE 		queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()
					GROUP BY 	queue_stats.qagent");


$queue = '';
while ($row = mysql_fetch_assoc($res)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}
	
	$queue .= '<tr '. $odd .'>
			 		<th style="width: 80px;">'.$row[queue].'</th>
		 			<th style="width: 80px;">'.$row[quant].'</th>
		 			<th style="width: 80px;">'.$row[percent].'</th>
		 		</tr>';
}

$agent = '';
while ($row = mysql_fetch_assoc($res1)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}

	$agent .= '<tr '. $odd .'>
			 		<th style="width: 80px;">'.$row[agent].'</th>
		 			<th style="width: 80px;">'.$row[quant].'</th>
		 			<th style="width: 80px;">'.$row[percent].'</th>
		 		</tr>';
}

 $data = '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">რიგი</th>
		 			<th style="width: 80px;">ზარები</th>
		 			<th style="width: 80px;">%</th>
		 		</tr>
 			<thead>
 			<tbody>'.
 				$queue	
	 		.'<tbody>
 		</table>';
 
 $data .= '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">ოპერატორი</th>
		 			<th style="width: 80px;">ზარები</th>
		 			<th style="width: 80px;">%</th>
		 		</tr>
 			<thead>
 			<tbody>'.
  					$agent
  			.'<tbody>
 		</table>';
 
 echo  $data;