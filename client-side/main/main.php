<?php
$user_id = $_SESSION['USERID'];

$result = mysql_query("
						SELECT 		`menu_detail`.page_id,
									`menu_detail`.title,
									`menu_detail`.metro_icon,
									`menu_detail`.metro_tile_type
						FROM 		`users`
						LEFT JOIN   `group` ON `group`.id = `users`.`group_id`
						LEFT JOIN   `group_permission` ON `group`.id = `group_permission`.`group_id`
						LEFT JOIN   `menu_detail` ON `group_permission`.`page_id` = `menu_detail`.`page_id`
						WHERE 		`users`.`id` = $user_id AND metro_tile_type != 0
						ORDER BY	`menu_detail`.metro_tile_type DESC
					");
function randomColor() {
	$possibilities = array(1, 2, 3, 4, 5, 6, 7, 8, 9, "A", "B", "C", "D", "E", "F" );
	shuffle($possibilities);
	$color = "#";
	for($i=1;$i<=6;$i++){
		$color .= $possibilities[rand(0,14)];
	}
	return $color;
}

?>

<html>
<head>
		<link href="media/css/main/header.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/mainpage.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/tooltip.css" rel="stylesheet" type="text/css" />
</head>
<body onselectstart='return false;'>
    <div id="ContentHolder">
    <div class="content">
        <table class="tiles">
            <tbody>
                <tr>
                    <td>
                    <?php
						$count =  round(mysql_num_rows($result) / 3);

						for ($i = 1; $i < $count - 2; $i++) {
							$row = mysql_fetch_assoc($result);
							if ($row[metro_tile_type] == 1) {
								echo '
									<div  class="tile_small" style="background: #'.dechex(rand(0x220000, 0xFFFFFF)).'; box-shadow: 2px 5px 5px #ccc;" onclick="location.href=\'index.php?pg='.$row[page_id].'\'">
			                            <p style="font-size: 14px;">'.$row[title].'</p>
			                        </div>
									';
							}elseif ($row[metro_tile_type] == 2) {
								echo '
									<div  class="tile_large"  style="background: #'.dechex(rand(0x200000, 0xFFFFFF)).'; box-shadow: 2px 5px 5px #ccc;" onclick="location.href=\'index.php?pg='.$row[page_id].'\'">
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/main/'.$row[metro_icon].'" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">'.$row[title].'</p>
									</div>
									';
							}
						}
                    ?>
                    </td>

                    <td>
                    <?php

						for ($i = $count; $i < $count + $count +2; $i++) {
							$row = mysql_fetch_assoc($result);
							if ($row[metro_tile_type] == 1) {
								echo '
									<div  class="tile_small" style="background: #'.dechex(rand(0x200000, 0xFFFFFF)).'; box-shadow: 2px 5px 5px #ccc;" onclick="location.href=\'index.php?pg='.$row[page_id].'\'">
			                            <p style="font-size: 14px;">'.$row[title].'</p>
			                        </div>
									';
							}elseif ($row[metro_tile_type] == 2) {
								echo '
									<div  class="tile_large"  style="background:#'.dechex(rand(0x200000, 0xFFFFFF)).'; box-shadow: 2px 5px 5px #ccc;" onclick="location.href=\'index.php?pg='.$row[page_id].'\'">
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/main/'.$row[metro_icon].'" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">'.$row[title].'</p>
									</div>
									';
							}
						}
                    ?>
                    </td>

                    <td>
                    <?php
						for ($i = $count + $count + 2; $i <= mysql_num_rows($result); $i++) {
							$row = mysql_fetch_assoc($result);
							if ($row[metro_tile_type] == 1) {
								echo '
									<div  class="tile_small" style="background:#'.dechex(rand(0x200000, 0xFFFFFF)).'; box-shadow: 2px 5px 5px #ccc;" onclick="location.href=\'index.php?pg='.$row[page_id].'\'">
			                            <p style="font-size: 14px;">'.$row[title].'</p>
			                        </div>
									';
							}elseif ($row[metro_tile_type] == 2) {
								echo '
									<div  class="tile_large"  style="background: #'.dechex(rand(0x200000, 0xFFFFFF)).'; box-shadow: 2px 5px 5px #ccc;" onclick="location.href=\'index.php?pg='.$row[page_id].'\'">
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/main/'.$row[metro_icon].'" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">'.$row[title].'</p>
									</div>
									';
							}
						}
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
        </div>
</body>
</html>
