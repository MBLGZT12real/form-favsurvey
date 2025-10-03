<?php 
	date_default_timezone_set("Asia/Jakarta");
	
	//DB SERVER
	$db_host 	= "localhost";
	$db_user 	= "root";
	$db_pass 	= "";
	$db_name 	= "ems_2025";
	
	//DB SERVER ONLINE
	/*$db_host 	= "localhost";
	$db_user 	= "sevenems_superadmin";
	$db_pass 	= "Seven.1214!!";
	$db_name 	= "sevenems_ems_imos_25";*/
	
	//DB SERVER CLOUD
	/*$db_host 	= "sevenems.com";
	$db_user 	= "sevenems_superadmin";
	$db_pass 	= "Seven.1214!!";
	$db_name 	= "sevenems_ems_imos_25";*/
	
	//DB SERVER ONLINE
	/*$db_host 	= "localhost";
	$db_user 	= "amaraeve_superadmin";
	$db_pass 	= "Seven.1214";
	$db_name 	= "amaraeve_giias_2021";*/
	
	//FILE LOCAL
	$db_file	= "";
	
	//FILE CLOUD
	//$db_file	= "https://sevenems.com/form-favsurvey/";
	
	$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	if(mysqli_connect_errno()){
		echo 'Connection failed : '.mysqli_connect_error();
	}
?>