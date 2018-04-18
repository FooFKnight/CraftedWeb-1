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
<?php
    global $Account;
    $Account->isNotLoggedIn();
?>
<div class='box_two_title'>My Account</div>
<table style="width: 100%; margin-top: -15px;">
    <tr>
        <td><span class='blue_text'>Account name</span></td><td> <?php echo ucfirst(strtolower($_SESSION['cw_user'])); ?></td>
        <td><span class='blue_text'>Joined</span></td><td><?php echo $Account->getJoindate($_SESSION['cw_user']); ?></td>
    </tr>
    <tr>
        <td><span class='blue_text'>Email adress</span></td><td><?php echo $Account->getEmail($_SESSION['cw_user']); ?></td>
        <td><span class='blue_text'>Vote Points</span></td><td><?php echo $Account->loadVP($_SESSION['cw_user']); ?></td>
    </tr>
    <tr>
        <td><span class='blue_text'>Account Status</span></td><td><?php echo $Account->checkBanStatus($_SESSION['cw_user']); ?></td>
        <td><span class='blue_text'><?php echo $GLOBALS['donation']['coins_name']; ?></span></td><td><?php echo $Account->loadDP($_SESSION['cw_user']); ?></td>
    </tr>
    <br/>
</table>
</div>
<div class='box_two'>      
    <div class='box_two_title'>Services</div>
    <div id="account_func_placeholder">
        <div class='account_func' onclick="acct_services('character')">Character Services</div>
        <div class='account_func' onclick="acct_services('account')">Account Services</div>        
        <div class='account_func' onclick="acct_services('settings')">Settings</div>

        <div class='hidden_content' id='character'>
            <?php if ($GLOBALS['service']['unstuck']['status'] == 'TRUE')
                { ?>
                    <div class='service' onclick='redirect("?p=unstuck")'>
                        <div class='service_icon'><img src='styles/global/images/icons/character_migration.png'></div>
                        <h3>Unstuck</h3>
                        <div class='service_desc'>Unstuck your character.</div>
                    </div>
                <?php } ?>

            <?php if ($GLOBALS['service']['revive']['status'] == 'TRUE')
                { ?>
                    <div class='service' onclick='redirect("?p=revive")'>
                        <div class='service_icon'><img src='styles/global/images/icons/revive.png'></div>
                        <h3>Revive</h3>
                        <div class='service_desc'>Revive your character.</div> 
                    </div>
                <?php } ?>

<?php if ($GLOBALS['service']['teleport']['status'] == 'TRUE')
    { ?>
                    <div class='service' onclick='redirect("?p=teleport")'>
                        <div class='service_icon'><img src='styles/global/images/icons/transfer.png'></div>
                        <h3>Teleport</h3>
                        <div class='service_desc'>Teleport your character.</div> 
                    </div>
                <?php } ?>

<?php if ($GLOBALS['service']['appearance']['status'] == 'TRUE')
    { ?>
                    <div class='service' onclick='redirect("?p=service&s=appearance")'>
                        <div class='service_icon'><img src='styles/global/images/icons/appearance.png'></div>
                        <h3>Appearance change</h3>
                        <div class='service_desc'>Customize your character's appearance (optional name change included)</div> 
                    </div>
    <?php } ?>

<?php if ($GLOBALS['service']['race']['status'] == 'TRUE')
    { ?>
                    <div class='service' onclick='redirect("?p=service&s=race")'>
                        <div class='service_icon'><img src='styles/global/images/icons/race_change.png'></div>
                        <h3>Race change</h3>
                        <div class='service_desc'>Change your character's race (within your current faction)</div> 
                    </div>
    <?php } ?>

<?php if ($GLOBALS['service']['name']['status'] == 'TRUE')
    { ?>
                    <div class='service' onclick='redirect("?p=service&s=name")'>
                        <div class='service_icon'><img src='styles/global/images/icons/name_change.png'></div>
                        <h3>Name change</h3>
                        <div class='service_desc'>Change your character's name</div> 
                    </div>
    <?php } ?>

            <?php if ($GLOBALS['service']['faction']['status'] == 'TRUE')
                { ?>
                    <div class='service' onclick='redirect("?p=service&s=faction")'>
                        <div class='service_icon'><img src='styles/global/images/icons/factions.png'></div>
                        <h3>Faction change</h3>
                        <div class='service_desc'>Change your character's faction (Horde to Alliance or Alliance to Horde)</div> 
                    </div>
    <?php } ?>
        </div>
        <div class='hidden_content' id='account'>

            <div class='service' onclick='redirect("?p=vote")'>
                <div class='service_icon'><img src='styles/global/images/icons/character_migration.png'></div>
                <h3>Vote</h3>
                <div class='service_desc'>Vote & recieve rewards.</div> 
            </div>

            <div class='service' onclick='redirect("?p=donate")'>
                <div class='service_icon'><img src='styles/global/images/icons/visa.png'></div>
                <h3>Donate</h3>
                <div class='service_desc'>Donate & recieve rewards.</div> 
            </div>

            <div class='service' onclick='redirect("?p=voteshop")'>
                <div class='service_icon'><img src='styles/global/images/icons/raf.png'></div>
                <h3>Vote Shop</h3>
                <div class='service_desc'>Claim your rewards!</div> 
            </div>

            <div class='service' onclick='redirect("?p=donateshop")'>
                <div class='service_icon'><img src='styles/global/images/icons/raf.png'></div>
                <h3>Donation Shop</h3>
                <div class='service_desc'>Claim your rewards!</div> 
            </div>

        </div>

        <div class='hidden_content' id='settings'>

            <div class='service' onclick='redirect("?p=changepass")'>
                <div class='service_icon'><img src='styles/global/images/icons/arena.png'></div>
                <h3>Change Password</h3>
                <div class='service_desc'>Change your account password.</div>
            </div>

            <div class='service' onclick='redirect("?p=settings")'>
                <div class='service_icon'><img src='styles/global/images/icons/ptr.png'></div>
                <h3>Change Email</h3>
                <div class='service_desc'>Change the email adress associated with your account.</div> 
            </div>

        </div>
    </div>