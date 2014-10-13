<?php
session_start();
$day=$_SESSION['day'];

?>
<?php
 include('../includes/configuration.php');	
	  		 
/*		 echo "<pre>";
		 print_r($_POST);
		 echo "</pre>";*/
		 
		
		  // print_r($LastId);
		   //die();
		   //$status = "success";
		   $date = date('y-m-d');
		   
			$exp= mktime(0, 0, 0, date("m"), date("d")+$day, date("y"));
			$exp_date = date("y-m-d", $exp); 	
			
			 foreach($_POST as $key=>$value)
		 {
		 	if($key=='custom')
			{
				$uid=$value;
			 $sql = "UPDATE `emp_register` SET `status` =  '1',`payment_date`= '".$date."',`exp_day`= '".$exp_date."' WHERE `emp_id`=".$uid;
		   mysql_query($sql);

			}
		 }

	  	  // $sql = "UPDATE `jos_users` SET `block` =  '0' WHERE id = ".$LastId."";
		  		   ?><br>
		   <?php
		   $host=$_SERVER['HTTP_HOST'];
			?>
			<h1><a href="../../index.php">Click Here and Login</a></h1>
			<?php

		   //header('location: http://localhost/Crobba/index.php');
		  //echo "</body></html>";
		  
		 // header("location:practice.php");
     

?>
