<?php session_start(); ?>
<?php require_once(dirname(__FILE__) . '/config.php'); ?>

<?php if ($_SESSION['USERID'] == ''): ?>
	<?php require_once(ABSPATH . 'includes/login.php');?>
<?php else: ?>
	<?php require_once(ABSPATH . 'includes/functions.php');?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>CC Palitra Media</title>

			<link rel="shortcut icon" type="image/ico" href="media/images/palitra.ico" />
			<link rel="stylesheet" type="text/css" media="screen" href="media/css/reset/reset.css" />
			<!--[if IE]>
				<link rel="stylesheet" type="text/css" media="screen" href="css/reset/ie.css" />
			<![endif]-->

			<?php echo GetJs();?>
			<?php echo GetCss();?>

			<script type="text/javascript">
				$(document).ready(function (){
					AjaxSetup();
				});
			</script>


    <link href="css/mbExtruder.css" media="all" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="inc/jquery.hoverIntent.min.js"></script>
    <script type="text/javascript" src="inc/jquery.mb.flipText.js"></script>
    <script type="text/javascript" src="inc/mbExtruder.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#links").buildMbExtruder({
                position:"right",
                width:300,
                extruderOpacity:0.4
            });
        });

    </script>
 <div id="links" style="background: #FFF !imortant;text-align: center;" class="{title:'ბმულები ', url:'parts/extruderRight.html'}"></div>
		</head>
		<body style="background: #E2F0FD url(media/images/bg.png);">
			<div id="npm"></div>
			<?php require_once(ABSPATH . 'includes/pages.php'); ?>
			<div class="clear high"></div>
		</body>
	</html>
<?php endif;?>