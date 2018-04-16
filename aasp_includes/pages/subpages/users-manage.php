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
<?php global $Page, $Server, $Account, $conn; ?>
<div class="box_right_title"><?php echo $Page->titleLink(); ?> &raquo; Manage Users</div>

<?php
if(isset($_GET['char']))
{
	echo 'Search results for <b>'.$_GET['char'].'</b><pre>';
	$result = mysqli_query($conn, "SELECT name, id FROM realms");
	while($row = mysqli_fetch_assoc($result)) 
	{
		$Server->connectToRealmDB($row['id']);
		$get 	= mysqli_query($conn, "SELECT account,name FROM characters WHERE name='".mysqli_real_escape_string($conn, $_GET['char'])."' OR guid='".(int)$_GET['char']."'");
		$rows 	= mysqli_fetch_assoc($get);
		echo '<a href="?p=users&s=manage&user='.$rows['account'].'">'.$rows['name'].' - '.$row['name'].'</a><br/>';
	}
	echo '</pre><hr/>';
}

if(isset($_GET['user']))  {
	
	$Server->selectDB('logondb');
	$value 	= mysqli_real_escape_string($conn, $_GET['user']);
	$result = mysqli_query($conn, "SELECT * FROM account WHERE username='".$value."' OR id='".$value."'");
	if(mysqli_num_rows($result) == 0) 
	{
		echo "<span class='red_text'>No results found!</span>";
	} 
	else 
	{
		$row = mysqli_fetch_assoc($result);?>
		<table width="100%">
			<tr>
			<td><span class='blue_text'>Account name</span></td><td> <?php echo ucfirst(strtolower($row['username']));?> (<?php echo $row['last_ip']; ?>)</td>
			<td><span class='blue_text'>Joined</span></td><td><?php echo $row['joindate']; ?></td>
			</tr>
			<tr>
				<td><span class='blue_text'>Email adress</span></td><td><?php echo $row['email'];?></td>
				<td><span class='blue_text'>Vote Points</span></td><td><?php  echo $Account->getVP($row['id']); ?></td>
			</tr>
			<tr>
				<td><span class='blue_text'>Account Status</span></td><td><?php echo $Account->getBan($row['id']); ?></td>
				<td><span class='blue_text'><?php echo $GLOBALS['donation']['coins_name']; ?></span></td><td><?php echo $Account->getDP($row['id']); ?></td>
			</tr>
			<tr><td><a href='?p=users&s=manage&getlogs=<?php echo $row['id']; ?>'>Account payments- & Shop logs</a><br />
            <a href='?p=users&s=manage&getslogs=<?php echo $row['id']; ?>'>Service logs</a></td>
			<td></td>
			<td><a href='?p=users&s=manage&editaccount=<?php echo $row['id']; ?>'>Edit Account information</a></tr>
			</table>
            <hr/>
            <b>Characters</b><br/>
            <table>
            <tr>
            	<th>Guid</th>
                <th>Name</th>
                <th>Level</th>
                <th>Class</th>
                <th>Race</th>
                <th>Realm</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
			 $Server->selectDB('webdb');
			 $result = mysqli_query($conn, "SELECT name,id FROM realms");
			 while($row = mysqli_fetch_assoc($result))
			 {
				$acct_id = $Account->getAccID($_GET['user']);
				$Server->connectToRealmDB($row['id']);
				$result = mysqli_query($conn, "SELECT name,guid,level,class,race,gender,online FROM characters WHERE account='".(int)$_GET['user']."'
				OR account='".$acct_id."'");
				 
				while($rows = mysqli_fetch_assoc($result))
				{
					?>
                    <tr class="center">
                    	<td><?php echo $rows['guid']; ?></td>
                        <td><?php echo $rows['name']; ?></td>
                        <td><?php echo $rows['level']; ?></td>
                        <td><img src="../styles/global/images/icons/class/<?php echo $rows['class']; ?>.gif" /></td>
                        <td><img src="../styles/global/images/icons/race/<?php echo $rows['race'].'-'.$rows['gender']; ?>.gif" /></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                        <?php
						if($rows['online']==1)
							echo '<font color="#009900">Online</font>';
						else
							echo '<font color="#990000">Offline</font>';	
						?>
                        </td>
                        <td><a href="#" onclick="characterListActions('<?php echo $rows['guid']; ?>','<?php echo $row['id']; ?>')">List actions</a></td>
                    </tr>
                    <?php 
				}
			 }
			 ?>
             </table>
            <hr/>
		<?php
	}
 }
elseif(isset($_GET['getlogs'])) {
	?>
	Account selected: <a href='?p=users&s=manage&user=<?php echo $_GET['getlogs']; ?>'><?php echo $Account->getAccName($_GET['getlogs']); ?></a><p />
	
	<h4 class='payments' onclick='loadPaymentsLog(<?php echo (int)$_GET['getlogs']; ?>)'>Payments log</h4>
	<div class='hidden_content' id='payments'></div>
	<hr/>
	<h4 class='payments' onclick='loadDshopLog(<?php echo (int)$_GET['getlogs']; ?>)'>Donation shop log</h4>
	<div class='hidden_content' id='dshop'></div>
	<hr/>
	<h4 class='payments' onclick='loadVshopLog(<?php echo (int)$_GET['getlogs']; ?>)'>Vote shop log</h4>
	<div class='hidden_content' id='vshop'></div>
	<?php
}
elseif(isset($_GET['editaccount'])) 
{
   ?>Account selected: <a href='?p=users&s=manage&user=<?php echo $_GET['editaccount']; ?>'><?php echo $Account->getAccName($_GET['editaccount']); ?></a><p />
	<table width="100%">
		<input type="hidden" id="account_id" value="<?php echo $_GET['editaccount']; ?>" />
	   	<tr>
			<td>Email</td>
			<td><input type="text" id="edit_email" class='noremove' value="<?php echo $Account->getEmail($_GET['editaccount']); ?>"/>
		</tr> 
	   	<tr>
	   		<td>Set Password</td>
	   		<td><input type="text" id="edit_password" class='noremove'/></td>
	   	</tr>
	   	<tr>
	   		<td>Vote Points</td>
	   		<td><input type="text" id="edit_vp" value="<?php echo $Account->getVP($_GET['editaccount']); ?>" class='noremove'/> 
	   	</tr>
	   	<tr>
	   		<td><?php echo $GLOBALS['donation']['coins_name']; ?></td> 
			<td><input type="text" id="edit_dp" value="<?php echo $Account->getDP($_GET['editaccount']); ?>" class='noremove'/></td>
		</tr>
	   	<tr>
	   		<td></td>
	   		<td><input type="submit" value="Save" onclick="save_account_data()"/></td>
	   	</tr>
   </table>
   <hr/>
<?php } 
elseif(isset($_GET['getslogs'])) 
{
	?>
	Account selected: <a href='?p=users&s=manage&user=<?php echo $_GET['getslogs']; ?>'><?php echo $Account->getAccName($_GET['getslogs']); ?></a><p />
	<table>
    	<tr>
        	<th>Service</th>
            <th>Description</th>
            <th>Realm</th>
            <th>Date</th>
        </tr>
        <?php
		$Server->selectDB('webdb');
		$result = mysqli_query($conn, "SELECT * FROM user_log WHERE account='".(int)$_GET['getslogs']."'");
		if(mysqli_num_rows($result) == 0)
		{
			echo 'No logs was found for this account!';
		}
		else
		{
			while($row = mysqli_fetch_assoc($result))
			{
				echo '<tr class="center">';
					echo '<td>'.$row['service'].'</td>';
					echo '<td>'.$row['desc'].'</td>';
					echo '<td>'.$Server->getRealmName($row['realmid']).'</td>';
					echo '<td>'.date('Y-m-d H:i',$row['timestamp']).'</td>';
				echo '</tr>';
			}
		}
		?>
    </table>
    <hr/>
<?php
}
?>
<table width="100%">
	<tr>
    	<td>Username or ID: </td>	
        <form action="" method="get">
        	<input type="hidden" name="p" value="users">
        	<input type="hidden" name="s" value="manage">
        <td><input type="text" name="user"></td>
        <td><input type="submit" value="Go"></td>
    </tr></form>
            
    <tr>
        <td>Character name or GUID: </td>	
        <form action="" method="get">
        	<input type="hidden" name="p" value="users">
        	<input type="hidden" name="s" value="manage">
        <td><input type="text" name="char"></td>
        <td><input type="submit" value="Go"></td>
   	</tr></form>
</table>