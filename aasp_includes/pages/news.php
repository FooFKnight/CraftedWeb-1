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

                  
	global $Server, $Page;

	$Server->selectDB('webdb');	 
	$Page->validatePageAccess('News');
	 
    if($Page->validateSubPage() == TRUE) 
    {
		$Page->outputSubPage();
	} 
	else 
	{
?>
	<div class="box_right_title">News &raquo; Post news</div>
	<div id="news_status"></div>
	<input type="text" value="Title..." id="news_title"/> <br/>
	<input type="text" value="Author..." id="news_author"/> <br/>
	<input type="text" value="Image URL..." id="news_image"/> <br/>
	<textarea cols="72" rows="7" id="news_content">Content...

	</textarea>
	<input type="submit" value="Post" onclick="postNews()"/>  <input type="submit" value="Preview" onclick="previewNews()" disabled="disabled"/>                                
<?php } ?>