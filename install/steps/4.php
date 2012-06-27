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
?>
<p id="steps"><b>Introduction</b> &raquo; MySQL Info &raquo; Configure &raquo; <b>Database</b> &raquo; Realm Info &raquo; Finished<p>
<hr/>
<p>
	After scanning your updates folder, we found the following database updates: 
    <ul>
    	<?php
			$files = scandir('sql/updates/');
			foreach($files as $value) {
				if(substr($value,-3,3)=='sql')
				{
					echo '<a href="#">'.$value.'</a><br/>';	
					$found = true;
				}
			}
		?>
    </ul>
    <?php
	if(!isset($found))
				echo '<code>No updates was found in your /updates folder. <a href="?st=5">Click here to continue</a></code>';
	?>
    <i>* Tip: Click on them to get additional information about them.</i>
</p>
<p>
Click the button below to apply all of these updates. If you do not wish to have these updates, just click <a href="?st=5">here</a>. You can install them anytime you want manually by exporting them into your database with any database software of your choise. (HeidiSQL, SQLyog, etc)
</p>
<p>
	<br/>
	<input type="submit" value="Apply all database updates" onclick="step4()">
</p>