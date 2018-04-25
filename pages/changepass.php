<?php
#   ___           __ _           _ __    __     _     
#  / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__  
# / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \ 
#/ /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
#\____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/ 
#
#		-[ Created by �Nomsoft
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
#                  � Nomsoftware 'Nomsoft' 2011-2012. All rights reserved.    
?>
<div class='box_two_title'>Change Password</div>
<?php
	global $Account;
    $Account->isNotLoggedIn();
    if (isset($_POST['change_password']))
    {
        $Account->changePass($_POST['current_password'], $_POST['new_password'], $_POST['new_password_repeat']);
    }
?>
<form method="POST">
    <table width="70%">
    	<tr>
            <td>Current password:</td> 
            <td><input type="password" name="current_password" class="input_text"/></td>
        </tr> 

        <tr>
            <td></td> 
            <td><hr/></td>
        </tr> 

        <tr>
            <td>New password:</td> 
            <td><input type="password" name="new_password" class="input_text"/></td>
        </tr> 
        <tr>
            <td>Repeat new password:</td> 
            <td><input type="password" name="new_password_repeat" class="input_text"/></td>
        </tr>
        
         
        <tr>
            <td></td> 
            <td><input type="submit" value="Change Password" name="change_password" /></td>
        </tr>                
    </table>                 
</form>