<?php
/* ___           __ _           _ __    __     _     
  / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__  
 / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \ 
/ /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
\____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/ 

		-[ Created by ©Nomsoft
		  `-[ Original core by Anthony (Aka. CraftedDev)

				-CraftedWeb Generation II-                  
			 __                           __ _   							   
		  /\ \ \___  _ __ ___  ___  ___  / _| |_ 							   
		 /  \/ / _ \| '_ ` _ \/ __|/ _ \| |_| __|							   
		/ /\  / (_) | | | | | \__ \ (_) |  _| |_ 							   
		\_\ \/ \___/|_| |_| |_|___/\___/|_|  \__|	- www.Nomsoftware.com -	   
                  The policy of Nomsoftware states: Releasing our software   
                  or any other files are protected. You cannot re-release    
                  anywhere unless you were given permission.                 
                  © Nomsoftware 'Nomsoft' 2011-2012. All rights reserved.  */

define('INIT_SITE', TRUE);
include('../../includes/misc/headers.php');
include('../../includes/configuration.php');
include('../functions.php');
global $Server, $Account, $conn;

$Server->selectDB('webdb');

###############################
if($_POST['action'] == 'addsingle') 
{
	$entry 	= (int)$_POST['entry'];
	$price 	= (int)$_POST['price'];
	$shop 	= mysqli_real_escape_string($conn, $_POST['shop']);
	
	if(empty($entry) || empty($price) || empty($shop))
	{
		die("Please enter all fields.");
	}

	$Server->selectDB('worlddb');
	$get = mysqli_query($conn, "SELECT name,displayid,ItemLevel,quality,AllowableRace,AllowableClass,class,subclass,Flags
	FROM item_template WHERE entry='".$entry."'")or die('Error whilst getting item data from the database. Error message: '.mysqli_error($conn));
	$row = mysqli_fetch_assoc($get);
	
	$Server->selectDB('webdb');
	
	if($row['AllowableRace'] == "-1")
	{
		$faction = 0;
	}
	elseif($row['AllowableRace'] == 690)
	{
		$faction = 1;
	}
	elseif($row['AllowableRace'] == 1101)
	{
		$faction = 2;
	}
	else
	{
		$faction = $row['AllowableRace'];
	}

	mysqli_query($conn, "INSERT INTO shopitems (entry,name,in_shop,displayid,type,itemlevel,quality,price,class,faction,subtype,flags) VALUES (
	'".$entry."','".mysqli_real_escape_string($conn, $row['name'])."','".$shop."','".$row['displayid']."','".$row['class']."','".$row['ItemLevel']."'
	,'".$row['quality']."','".$price."','".$row['AllowableClass']."','".$faction."','".$row['subclass']."','".$row['Flags']."'
	)")or die('Error whilst adding items to the database. Error message: '.mysqli_error($conn));
	
	$Server->logThis("Added ".$row['name']." to the ".$shop." shop");
	
	echo 'Successfully added item';
}
###############################
if($_POST['action'] == 'addmulti') 
{
	$il_from 	= (int)$_POST['il_from'];
	$il_to 		= (int)$_POST['il_to'];
	$price 		= (int)$_POST['price'];
	$quality 	= mysqli_real_escape_string($conn, $_POST['quality']);
	$shop 		= mysqli_real_escape_string($conn, $_POST['shop']);
	$type 		= mysqli_real_escape_string($conn, $_POST['type']);
	
	if(empty($il_from) || empty($il_to) || empty($price) || empty($shop))
	{
		die("Please enter all fields.");
	}
		
	$advanced = "";
	if($type != "all") 
	{
		if($type == "15-5" || $type == "15-5")  
		{
			//Mount or pet
			$type = explode('-',$type);
			
			$advanced.= "AND class='".$type[0]."' AND subclass='".$type[1]."'";
		} 
		else
		{
			$advanced.= "AND class='".$type."'";
		}
	} 	

	if($quality != "all")
	{
		$advanced .= " AND quality='".$quality."'";
	}
	        
	$Server->selectDB('worlddb');
	$get = mysqli_query($conn, "SELECT entry,name,displayid,ItemLevel,quality,class,AllowableRace,AllowableClass,subclass,Flags
	 FROM item_template WHERE itemlevel>='".$il_from."'
	AND itemlevel<='".$il_to."' ".$advanced) or die('Error whilst getting item data from the database. Error message: '.mysqli_error());
	
	$Server->selectDB('webdb');
	
	$c = 0;
	while($row = mysqli_fetch_assoc($get)) 
	{
		$faction = 0;
		
		if($row['AllowableRace'] == 690)
		{
			$faction = 1;
		}
		elseif($row['AllowableRace'] == 1101)
		{
			$faction = 2;
		}
		else
		{
			$faction = $row['AllowableRace'];
		}
	
		mysqli_query($conn, "INSERT INTO shopitems (entry,name,in_shop,displayid,type,itemlevel,quality,price,class,faction,subtype,flags) VALUES (
		'".$row['entry']."','".mysqli_real_escape_string($row['name'])."','".$shop."','".$row['displayid']."','".$row['class']."','".$row['ItemLevel']."'
		,'".$row['quality']."','".$price."','".$row['AllowableClass']."','".$faction."','".$row['subclass']."','".$row['Flags']."'
		)")or die('Error whilst adding items to the database. Error message: '.mysqli_error());
		
		$c++;
	}
	
	$Server->logThis("Added multiple items to the ".$shop." shop");
	echo 'Successfully added '.$c.' items';
}
###############################
if($_POST['action']=='clear') 
{
	$shop = (int)$_POST['shop'];
	
	if($shop == 1)
	{
		$shop = "vote";
	}
	elseif($shop == 2)
	{
		$shop = "donate";
	}
	
	mysqli_query($conn, "DELETE FROM shopitems WHERE in_shop='".$shop."';");
	mysqli_query($conn, "TRUNCATE shopitems;");
	return;
}
###############################
if($_POST['action'] == 'modsingle') 
{
	$entry = (int)$_POST['entry'];
	$price = (int)$_POST['price'];
	$shop = mysqli_real_escape_string($conn, $_POST['shop']);
	
	if(empty($entry) || empty($price) || empty($shop))
	{
		die("Please enter all fields.");
	}
	
	mysqli_query($conn, "UPDATE shopitems SET price='".$price."' WHERE entry='".$entry."' AND in_shop='".$shop."';");
	echo 'Successfully modified item';
}
###############################
if($_POST['action']=='delsingle') 
{
	$entry = (int)$_POST['entry'];
	$shop = mysqli_real_escape_string($conn, $_POST['shop']);
	
	if(empty($entry) || empty($shop))
		die("Please enter all fields.");
	
	mysqli_query($conn, "DELETE FROM shopitems WHERE entry='".$entry."' AND in_shop='".$shop."'");
	echo 'Successfully removed item';
}
###############################
if($_POST['action']=='modmulti') 
{
	$il_from = (int)$_POST['il_from'];
	$il_to = (int)$_POST['il_to'];
	$price = (int)$_POST['price'];
	$quality = mysqli_real_escape_string($conn, $_POST['quality']);
	$shop = mysqli_real_escape_string($conn, $_POST['shop']);
	$type = mysqli_real_escape_string($conn, $_POST['type']);
	
	if(empty($il_from) || empty($il_to) || empty($price) || empty($shop))
		die("Please enter all fields.");
		
	$advanced = "";
	if($type!="all") 
	{
		if($type=="15-5" || $type=="15-5")  
		{
			//Mount or pet
			$type = explode('-',$type);
			
			$advanced.= "AND type='".$type[0]."' AND subtype='".$type[1]."'";
		} 
		else	
			$advanced.= "AND type='".$type."'";
	} 	

	if($quality!="all")
		$advanced .= "AND quality='".$quality."'";
		
	$count = mysqli_query($conn, "COUNT(*) FROM shopitems WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);
		
	mysqli_query($conn, "UPDATE shopitems SET price='".$price."' WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);	
	echo 'Successfully modified '.$count.' items!';	
}
###############################
if($_POST['action']=='delmulti') 
{
	$il_from = (int)$_POST['il_from'];
	$il_to = (int)$_POST['il_to'];
	$quality = mysqli_real_escape_string($_POST['quality']);
	$shop = mysqli_real_escape_string($_POST['shop']);
	$type = mysqli_real_escape_string($_POST['type']);
	
	if(empty($il_from) || empty($il_to) || empty($shop))
	{
		die("Please enter all fields.");
	}
		
	$advanced = "";
	if($type!="all") 
	{
		if($type=="15-5" || $type=="15-5")  
		{
			//Mount or pet
			$type = explode('-',$type);
			
			$advanced.= "AND type='".$type[0]."' AND subtype='".$type[1]."'";
		} 
		else	
			$advanced.= "AND type='".$type."'";
	} 	

	if($quality!="all")
		$advanced .= "AND quality='".$quality."'";
	
	$count = mysqli_query($conn, "COUNT(*) FROM shopitems WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);
		
	mysqli_query($conn, "DELETE FROM shopitems WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);
	echo 'Successfully removed '.$count.' items!';	
}
###############################
?>