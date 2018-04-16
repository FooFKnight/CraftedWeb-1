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

  global $Server, $Account, $conn;
?>
<div class="box_right_title">Dashboard</div>
<table style="width: 605px;">
<tr>
<td><span class='blue_text'>Active Connections</span></td><td><?php echo $Server->getActiveConnections(); ?></td>
<td><span class='blue_text'>Active accounts(This month)</span></td><td><?php echo $Server->getActiveAccounts(); ?></td>
</tr>
<tr>
     <td><span class='blue_text'>Account logged in today</span></td><td><?php echo $Server->getAccountsLoggedToday(); ?></td> 
    <td><span class='blue_text'>Accounts created today</span></td><td><?php echo $Server->getAccountsCreatedToday(); ?></td>
</tr>
</table>
</div>

<?php
  $Server->checkForNotifications();
?>

<div class="box_right">
  <div class="box_right_title">Admin Panel log</div>
  <?php
  $Server->selectDB('webdb');
  $result = mysqli_query($conn, "SELECT * FROM admin_log ORDER BY id DESC LIMIT 10;");
  if(mysqli_num_rows($result) == 0) 
  {
      echo "The admin log was empty!";
  } 
  else 
  { ?>
  <table class="center">
    <tr>
      <th>Date</th>
      <th>User</th>
      <th>Action</th>
    </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo date("Y-m-d H:i:s",$row['timestamp']); ?></td>
        <td><?php echo $Account->getAccName($row['account']); ?></td>
        <td><?php echo $row['action']; ?></td>
      </tr>
    <?php } ?>
  </table><br/>
  <a href="?p=logs&s=admin" title="Get more logs">Older logs...</a>
  <?php } ?>
</div>