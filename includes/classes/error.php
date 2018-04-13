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
 
function buildError($error,$num) 
{
	if ($GLOBALS['useDebug'] == false)
	{
		log_error($error,$num);
	}
	else
	{
		errors($error,$num);
	}
}

function errors($error,$num) 
{
	log_error(strip_tags($error),$num);
	die("<center><b>Website error</b>  <br/>
		The website script encountered an error and died. <br/><br/>
		<b>Error message: </b>".$error."  <br/>
		<b>Error number: </b>".$num."
		<br/><br/><br/><i>Powered by CraftedWeb
		<br/><font size='-2'>Professionally developed with love.</font></i></center>
		");
}

function log_error($error,$num) 
{
	error_log("*[".date("d M Y H:i")."] ".$error, 3, "error.log");
}

function loadCustomErrors() 
{
	set_error_handler("customError");   
}

function customError($errno, $errstr)
{
    if ($errno != 8 && $errno != 2048 && $GLOBALS['useDebug'] == TRUE)
    {
		error_log("*[".date("d M Y H:i")."]<i>".$errstr."</i>", 3, "error.log");
    }
}
