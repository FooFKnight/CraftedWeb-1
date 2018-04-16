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
	 
<div class="box_right_title">Character Services</div>
<table class="center">
<tr><th>Service</th><th>Price</th><th>Currency</th><th>Status</th></tr>
<?php
$result = mysqli_query($conn, "SELECT * FROM service_prices");
while($row = mysqli_fetch_assoc($result)) { ?>
	<tr>
        <td><?php echo $row['service']; ?></td>
        <td><input type="text" value="<?php echo $row['price']; ?>" style="width: 50px;" id="<?php echo $row['service']; ?>_price" class="noremove"/></td>
        <td><select style="width: 200px;" id="<?php echo $row['service']; ?>_currency">
             <option value="vp" <?php if ($row['currency']=='vp') echo 'selected'; ?>>Vote Points</option>
             <option value="dp" <?php if ($row['currency']=='dp') echo 'selected'; ?>><?php echo $GLOBALS['donation']['coins_name']; ?></option>
        </select></td>
        <td><select style="width: 150px;" id="<?php echo $row['service']; ?>_enabled">
             <option value="true" <?php if ($row['enabled']=='TRUE') echo 'selected'; ?>>Enabled</option>
             <option value="false" <?php if ($row['enabled']=='FALSE') echo 'selected'; ?>>Disabled</option>
        </select></td>
        <td><input type="submit" value="Save" onclick="saveServicePrice('<?php echo $row['service']; ?>')"/>
    </tr>
<?php }
?>
</table>