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

    global $GameServer, $GamePage, $GameAccount;
    $conn = $GameServer->connect();
    $GameServer->selectDB('webdb', $conn);

    $GamePage->validatePageAccess('Donations');

    if ($GamePage->validateSubPage() == TRUE)
    {
        $GamePage->outputSubPage();
    }
    else
    {
        $donationsTotal       = mysqli_query($conn, "SELECT mc_gross FROM payments_log;");
        $donationsTotalAmount = 0;
        while ($row                  = mysqli_fetch_assoc($donationsTotal))
        {
            $donationsTotalAmount = $donationsTotalAmount + $row['mc_gross'];
        }

        $donationsThisMonth       = mysqli_query($conn, "SELECT mc_gross FROM payments_log WHERE paymentdate LIKE '%" . date('Y-md') . "%';");
        $donationsThisMonthAmount = 0;
        while ($row = mysqli_fetch_assoc($donationsThisMonth))
        {
          $donationsThisMonthAmount = $donationsThisMonthAmount + $row['mc_gross'];
        }

        $q                    = mysqli_query($conn, "SELECT mc_gross, userid FROM payments_log ORDER BY paymentdate DESC LIMIT 1;");
        $row                  = mysqli_fetch_assoc($q);
        $donationLatestAmount = $row['mc_gross'];

        $donationLatest = $GameAccount->getAccName($row['userid']);
        ?>
        <div class="box_right_title">Donations Overview</div>
        <table style="width: 100%;">
            <tr>
                <td><span class='blue_text'>Total donations</span></td>
                <td><?php echo mysqli_num_rows($donationsTotal); ?></td>
                <td><span class='blue_text'>Total donation amount</span></td>
                <td><?php echo round($donationsTotalAmount, 0); ?>$</td>
            </tr>
            <tr>
                <td><span class='blue_text'>Donations this month</span></td>
                <td><?php echo mysqli_num_rows($donationsThisMonth); ?></td>
                <td><span class='blue_text'>Donation amount this month</span></td>
                <td><?php echo round($donationsThisMonthAmount, 0); ?>$</td>
            </tr>
            <tr>
                <td><span class='blue_text'>Latest donation amount</span></td>
                <td><?php echo round($donationLatestAmount);  ?>$</td>
                <td><span class='blue_text'>Latest donator</span></td>
                <td><?php echo $donationLatest; ?></td>
            </tr>
        </table>
        <hr/>
        <a href="?p=donations&s=browse" class="content_hider">Browse Donations</a>
    <?php }