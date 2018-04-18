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

    global $Account, $Connect;
?>
<div class='box_two_title'>Forgot Password</div>
<?php
    $Account->isLoggedIn();
    if (isset($_POST['forgotpw']))
        $Account->forgotPW($_POST['forgot_username'], $_POST['forgot_email']);

    if (isset($_GET['code']) || isset($_GET['account']))
    {
        if (!isset($_GET['code']) || !isset($_GET['account']))
            echo "<b class='red_text'>Link error, one or more required values are missing.</b>";
        else
        {
            $Connect->selectDB('webdb');
            $code    = mysqli_real_escape_string($conn, $_GET['code']);
            $account = mysqli_real_escape_string($conn, $_GET['account']);
            $result  = mysqli_query($conn, "SELECT COUNT('id') FROM password_reset WHERE code='" . $code . "' AND account_id='" . $account . "'");
            if (mysqli_data_seek($result, 0) == 0)
                echo "<b class='red_text'>The values specified does not match the ones in the database.</b>";
            else
            {
                $newPass      = RandomString();
                echo "<b class='yellow_text'>Your new password is: " . $newPass . " <br/><br/>Please sign in and change your password.</b>";
                mysqli_query($conn, "DELETE FROM password_reset WHERE account_id = '" . $account . "'");
                $account_name = $Account->getAccountName($account);

                $Account->changePassword($account_name, $newPass);

                $ignoreForgotForm = true;
            }
        }
    }
    if (!isset($ignoreForgotForm))
    {
        ?> 
        To reset your password, please type your username & the Email address you registered with. An email will be sent to you, containing a link to reset your password. <br/><br/>

        <form action="?p=forgotpw" method="post">
            <table width="80%">
                <tr>
                    <td align="right">Username:</td> 
                    <td><input type="text" name="forgot_username" /></td>
                </tr>
                <tr>
                    <td align="right">Email:</td> 
                    <td><input type="text" name="forgot_email" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><hr/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="OK!" name="forgotpw" /></td>
                </tr>
            </table>
        </form> <?php } ?>