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
<?php global $Page, $conn;
if(isset($_POST['newpage'])) {
	
	$name 		= mysqli_real_escape_string($conn, $_POST['newpage_name']);
	$filename 	= mysqli_real_escape_string($conn, trim(strtolower($_POST['newpage_filename'])));
	$content 	= mysqli_real_escape_string($conn, htmlentities($_POST['newpage_content']));
	
	if(empty($name) || empty($filename) || empty($content)) {
		echo "<h3>Please enter <u>all</u> fields.</h3>";
	} else {
		mysqli_query($conn, "INSERT INTO custom_pages VALUES ('','".$name."','".$filename."','".$content."',
		'".date("Y-m-d H:i:s")."')");

		echo "<h3>The page was successfully created.</h3> 
		<a href='".$GLOBALS['website_domain']."?p=".$filename."' target='_blank'>View Page</a><br/><br/>";
	}
} ?>
<div class="box_right_title"><?php echo $Page->titleLink(); ?> &raquo; New page</div>
<form action="?p=pages&s=new" method="post">
Name <br/>
<input type="text" name="newpage_name"><br/>
Filename <i>(This is what the ?p=FILENAME will refer to. Eg. ?p=connect where Filename is 'connect')<br/>
<input type="text" name="newpage_filename"><br/>
Content<br/>
<textarea cols="77" rows="14" id="wysiwyg" name="newpage_content">
<?php if(isset($_POST['newpage_content'])) { echo $_POST['newpage_content']; } ?></textarea>    <br/>
<input type="submit" value="Create" name="newpage">