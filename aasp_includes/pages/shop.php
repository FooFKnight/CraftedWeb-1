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


    global $GamePage, $GameServer;

    $GamePage->validatePageAccess('Shop');

    if ($GamePage->validateSubPage() == TRUE)
    {
        $GamePage->outputSubPage();
    }
    else
    {   
        $conn = $GameServer->connect();
        $GameServer->selectDB('webdb', $conn);
        $inShop     = mysqli_query($conn, "SELECT COUNT(*) FROM shopitems;");
        $purchToday = mysqli_query($conn, "SELECT COUNT(*) FROM shoplog WHERE date LIKE '%" . date('Y-m-d') . "%';");
        $getAvg     = mysqli_query($conn, "SELECT AVG(*) AS priceAvg FROM shopitems;");
        $totalPurch = mysqli_query($conn, "SELECT COUNT(*) FROM shoplog;");

        //Note: The round() function will return 0 if no value is set :)
        ?>
        <div class="box_right_title">Shop Overview</div>
        <table style="width: 100%;">
            <tr>
                <td><span class='blue_text'>Items in shop</span></td><td><?php echo round(mysqli_data_seek($inShop, 0)); ?></td>
            </tr>
            <tr>
                <td><span class='blue_text'>Purchases today</span></td>
                <td><?php echo round(mysqli_data_seek($purchToday, 0)); ?></td>
                <td><span class='blue_text'>Total purchases</span></td>
                <td><?php echo round(mysqli_data_seek($totalPurch, 0)); ?></td>
            </tr>
            <tr>
                <td><span class='blue_text'>Average item cost</span></td>
                <td><?php echo round(mysqli_data_seek($getAvg, 0)); ?></td>
            </tr>
        </table>
        <hr/>
        <a href="?p=shop&s=add" class="content_hider">Add Items</a>
        <a href="?p=shop&s=manage" class="content_hider">Manage Items</a>
        <a href="?p=shop&s=tools" class="content_hider">Tools</a>
    <?php }