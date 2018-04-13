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

 
class connect 
{
	
	public static $connectedTo = NULL;

    public static function connectToDB() 
	{
		if(self::$connectedTo != 'global')
		{
			if (!$conn = mysqli_connect($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']))
				buildError("<b>Database Connection error:</b> A connection could not be established. Error: ".mysqli_error($conn),NULL);
			else
				return $conn;
			self::$connectedTo = 'global';
		}
	}
	 
	public static function connectToRealmDB($realmid) 
	{ 
		self::selectDB('webdb');
		
			if($GLOBALS['realms'][$realmid]['mysqli_host'] != $GLOBALS['connection']['host'] 
			|| $GLOBALS['realms'][$realmid]['mysqli_user'] != $GLOBALS['connection']['user'] 
			|| $GLOBALS['realms'][$realmid]['mysqli_pass'] != $GLOBALS['connection']['password'])
			{
				$conn = mysqli_connect($GLOBALS['realms'][$realmid]['mysqli_host'],
							 $GLOBALS['realms'][$realmid]['mysqli_user'],
							 $GLOBALS['realms'][$realmid]['mysqli_pass'])
							 or 
							 buildError("<b>Database Connection error:</b> A connection could not be established to Realm. Error: ".mysqli_error($conn),NULL);
			}
			else
			{
				self::connectToDB();
			}
			mysqli_select_db($conn, $GLOBALS['realms'][$realmid]['chardb']) or 
			buildError("<b>Database Selection error:</b> The realm database could not be selected. Error: ".mysqli_error($conn),NULL);
			self::$connectedTo = 'chardb';

	}
	 
	 
	public static function selectDB($db) 
	{
		$conn = self::connectToDB();
		 
		switch($db) {
			default: 
				mysqli_select_db($conn, $db);
			break;
			case('logondb'):
				mysqli_select_db($conn, $GLOBALS['connection']['logondb']);
			break;
			case('webdb'):
				mysqli_select_db($conn, $GLOBALS['connection']['webdb']);
			break;
			case('worlddb'):
				mysqli_select_db($conn, $GLOBALS['connection']['worlddb']);
			break;
		 }
			return TRUE;
	}
}

/*************************/
/* Realms & service prices automatic settings
/* (Indented on purpose)
/*************************/
	$realms = array();
	$service = array();

	$conn = mysqli_connect($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
	mysqli_select_db($connection['webdb']);

	//Realms
	$getRealms = mysqli_query($conn, "SELECT * FROM realms ORDER BY id ASC");
	while($row = mysqli_fetch_assoc($conn, $getRealms)) 
	{
		$realms[$row['id']]['id']=$row['id'];
		$realms[$row['id']]['name']=$row['name'];
		$realms[$row['id']]['chardb']=$row['char_db'];
		$realms[$row['id']]['description']=$row['description'];
		$realms[$row['id']]['port']=$row['port'];
		
		$realms[$row['id']]['rank_user']=$row['rank_user'];
		$realms[$row['id']]['rank_pass']=$row['rank_pass'];
		$realms[$row['id']]['ra_port']=$row['ra_port'];
		$realms[$row['id']]['soap_host']=$row['soap_port'];
		
		$realms[$row['id']]['host']=$row['host'];
		
		$realms[$row['id']]['sendType']=$row['sendType'];
		
		$realms[$row['id']]['mysqli_host']=$row['mysqli_host'];
		$realms[$row['id']]['mysqli_user']=$row['mysqli_user'];
		$realms[$row['id']]['mysqli_pass']=$row['mysqli_pass'];
	}
		     
		 //Service prices
	$getServices = mysqli_query($conn, "SELECT enabled,price,currency,service FROM service_prices");
	while($row = mysqli_fetch_assoc($conn, $getServices)) 
	{
		$service[$row['service']]['status']=$row['enabled'];
		$service[$row['service']]['price']=$row['price'];
		$service[$row['service']]['currency']=$row['currency'];
	}
	mysqli_close($conn);

	## Unset Magic Quotes
	if (get_magic_quotes_gpc()) 
	{
		$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
				unset($process[$key][$k]);
				if (is_array($v)) {
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
				} else {
					$process[$key][stripslashes($k)] = stripslashes($v);
				}
			}
		}
		unset($process);
	}