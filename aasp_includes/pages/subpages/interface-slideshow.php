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
<?php global $Page, $Server, $conn; ?>
<div class="box_right_title"><?php echo $Page->titleLink(); ?> &raquo; Slideshow</div>
<?php 
if($GLOBALS['enableSlideShow'] == true)
{
	$status = 'Enabled';
}
else
{
	$status = 'Disabled';
}

$Server->selectDB('webdb');
$count = mysqli_query($conn, "SELECT COUNT(*) FROM slider_images");
?>
The slideshow is <b><?php echo $status; ?></b>. You have <b><?php echo round(mysqli_data_seek($count,0)); ?></b> images in the slideshow.
<hr/>
<?php 
if(isset($_POST['addSlideImage']))
{
	$Page->addSlideImage($_FILES['slideImage_upload'], $_POST['slideImage_path'], $_POST['slideImage_url']);
}
?>
<a href="#addimage" onclick="addSlideImage()" class="content_hider">Add image</a>
<div class="hidden_content" id="addSlideImage">
<form action="" method="post" enctype="multipart/form-data">
Upload an image:<br/>
<input type="file" name="slideImage_upload"><br/>
or enter image URL: (This will override your uploaded image)<br/>
<input type="text" name="slideImage_path"><br/>
Where should the image redirect? (Leave empty if no redirect)<br/>
<input type="text" name="slideImage_url"><br/>
<input type="submit" value="Add" name="addSlideImage">
</form>
</div>
<br/>&nbsp;<br/>
<?php 
$Server->selectDB('webdb');
$result = mysqli_query($conn, "SELECT * FROM slider_images ORDER BY position ASC");
if(mysqli_num_rows($result) == 0) 
{
	echo "You don't have any images in the slideshow!";
}
else 
{
	echo '<table>';
	$c = 1;
	while($row = mysqli_fetch_assoc($result))
	{
		echo '<tr class="center">';
		echo '<td><h2>&nbsp; '.$c.' &nbsp;</h2><br/>
		<a href="#remove" onclick="removeSlideImage('.$row['position'].')">Remove</a></td>';
		echo '<td><img src="../'.$row['path'].'" alt="'.$c.'" class="slide_image" maxheight="200"/></td>';
		echo '</tr>';
		$c++;
	}
	  echo '</table>';
}
?>

