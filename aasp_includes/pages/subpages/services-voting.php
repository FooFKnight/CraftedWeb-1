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
<?php 
global $Page, $Server, $Account, $conn;
?> 
<div class="box_right_title">Voting Links</div>
<table class="center">
<tr><th>Title</th><th>Points</th><th>Image</th><th>Url</th><th>Actions</th></tr>
<?php
$server->selectDB('webdb');
$result = mysqli_query($conn, "SELECT * FROM votingsites ORDER BY id ASC");
while($row = mysqli_fetch_assoc($result)) { ?>
	     <tr>
              <td><?php echo $row['title']; ?></td>
              <td><?php echo $row['points']; ?></td>
              <td><img src="<?php echo $row['image']; ?>"></td>
              <td><?php echo $row['url']; ?></td>
              <td><a href="#" onclick="editVoteLink('<?php echo $row['id']; ?>','<?php echo $row['title']; ?>','<?php echo $row['points']; ?>',
              '<?php echo $row['image']; ?>','<?php echo $row['url']; ?>')">Edit</a> 
              <br/> <a href="#" onclick="removeVoteLink('<?php echo $row['id']; ?>')">Remove</a><br />
              </td>   
          </tr>
  <?php 
  }
?>
</table>
<br/>
<a href="#" class="content_hider" onclick="addVoteLink()">Add a new voting site</a>