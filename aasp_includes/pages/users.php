<?php
    /* ___           __ _           _ __    __     _     
      / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__
      / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \
      / /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
      \____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/

      -[ Created by �Nomsoft
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
      � Nomsoftware 'Nomsoft' 2011-2012. All rights reserved. */


    global $GameServer, $GamePage;
    $conn = $GameServer->connect();

    $GameServer->selectDB('webdb', $conn);

    $GamePage->validatePageAccess('Users');

    if ($GamePage->validateSubPage() == TRUE)
    {
      $GamePage->outputSubPage();
    }
    else
    {
        $GameServer->selectDB('logondb', $conn);
        $usersTotal       = mysqli_query($conn, "SELECT COUNT(*) FROM account;");
        $usersToday       = mysqli_query($conn, "SELECT COUNT(*) FROM account WHERE joindate LIKE '%" . date("Y-m-d") . "%';");
        $usersMonth       = mysqli_query($conn, "SELECT COUNT(*) FROM account WHERE joindate LIKE '%" . date("Y-m") . "%';");
        $usersOnline      = mysqli_query($conn, "SELECT COUNT(*) FROM account WHERE online=1;");
        $usersActive      = mysqli_query($conn, "SELECT COUNT(*) FROM account WHERE last_login LIKE '%" . date("Y-m") . "%';");
        $usersActiveToday = mysqli_query($conn, "SELECT COUNT(*) FROM account WHERE last_login LIKE '%" . date("Y-m-d") . "%';");
        ?>
        <div class="box_right_title">Users Overview</div>
        <table style="width: 100%;">
            <tr>
                <td><span class='blue_text'>Total users</span></td>
                <td><?php echo round(mysqli_data_seek($usersTotal, 0)); ?></td>
                <td><span class='blue_text'>New users today</span></td>
                <td><?php echo round(mysqli_data_seek($usersToday, 0)); ?></td>
            </tr>
            <tr>
                <td><span class='blue_text'>New users this month</span></td>
                <td><?php echo round(mysqli_data_seek($usersMonth, 0)); ?></td>
                <td><span class='blue_text'>Users online</span></td>
                <td><?php echo round(mysqli_data_seek($usersOnline, 0)); ?></td>
            </tr>
            <tr>
                <td><span class='blue_text'>Active users (this month)</span></td>
                <td><?php echo round(mysqli_data_seek($usersActive, 0)); ?></td>
                <td><span class='blue_text'>Users logged in today</span></td>
                <td><?php echo round(mysqli_data_seek($usersActiveToday, 0)); ?></td>
            </tr>
        </table>
        <hr/>
        <a href="?p=users&s=manage" class="content_hider">Manage Users</a>
    <?php } ?>