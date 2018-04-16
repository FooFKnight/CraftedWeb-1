<?php
#   ___           __ _           _ __    __     _     
#  / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__  
# / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \ 
#/ /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
#\____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/ 
#
#		-[ Created by ©Nomsoft
#		  `-[ Original core by Anthony (Aka. CraftedDev)
#
#				-CraftedWeb Generation II-                  
#			 __                           __ _   							   
#		  /\ \ \___  _ __ ___  ___  ___  / _| |_ 							   
#		 /  \/ / _ \| '_ ` _ \/ __|/ _ \| |_| __|							   
#		/ /\  / (_) | | | | | \__ \ (_) |  _| |_ 							   
#		\_\ \/ \___/|_| |_| |_|___/\___/|_|  \__|	- www.Nomsoftware.com -	   
#                  The policy of Nomsoftware states: Releasing our software   
#                  or any other files are protected. You cannot re-release    
#                  anywhere unless you were given permission.                 
#                  © Nomsoftware 'Nomsoft' 2011-2012. All rights reserved.    

global $Connect, $Plugins, $conn;
$Connect->selectDB('webdb');
$pages = scandir('pages');
unset($pages[0],$pages[1]);
$page = mysqli_real_escape_string($conn, $_GET['p']);

if (!isset($page)) 
{
	include('pages/home.php');
}
elseif(isset($_SESSION['loaded_plugins_pages']) && $GLOBALS['enablePlugins'] == true && !in_array($page.'.php',$pages))
{
	$Plugins->load('pages');
}
elseif(in_array($page.'.php',$pages)) 
{
	$result = mysqli_query($conn, "SELECT COUNT(filename) FROM disabled_pages WHERE filename='".$page."'");
	if(mysqli_data_seek($result,0)==0)
	{
		include('pages/'.$page.'.php');
	}
	else
	{
		include('pages/404.php'); 
	}
}
else 
{
	$result = mysqli_query($conn, "SELECT * FROM custom_pages WHERE filename='".$page."'");
	if(mysqli_num_rows($result)>0) 
	{	  
		$check = mysqli_query($conn, "SELECT COUNT(filename) FROM disabled_pages WHERE filename='".$page."'");
		if(mysqli_data_seek($check,0) == 0) 
		{
			$row = mysqli_fetch_assoc($result);
			echo html_entity_decode($row['content']); 
		} 
	} 
	else 
	{
		include('pages/404.php');
	}
}
				 
?>