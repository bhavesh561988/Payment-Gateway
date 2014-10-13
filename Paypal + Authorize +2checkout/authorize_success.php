<?php
 	session_start();
	$day=$_SESSION['day'];
	$sid=$_SESSION['sid'];
	$id=$_GET['id'];

	include('../includes/configuration.php');	
	
		  $date = date('y-m-d');
		   
			$exp= mktime(0, 0, 0, date("m"), date("d")+$day, date("y"));
			$exp_date = date("y-m-d", $exp); 	

		  $sql = "UPDATE `emp_register` SET `status` =  '1',`payment_date`= '".$date."',`subscribe_id`= '".$sid."',`exp_day`= '".$exp_date."' WHERE `emp_id`=".$id;
		   mysql_query($sql);
		   ?>
	 	  
		   
		   <?php
		   $host=$_SERVER['HTTP_HOST'];
			?>
			<h1><a href="../../index.php">Click Here and Login</a></h1>
			<?php


?>
