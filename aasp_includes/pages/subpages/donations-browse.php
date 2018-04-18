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
?>
<?php global $Page, $conn, $Server, $Account; ?>
<div class="box_right_title"><?php echo $Page->titleLink(); ?> &raquo; Browse</div>
<?php
    $per_page = 20;

    $pages_query = mysqli_query($conn, "SELECT COUNT(*) FROM payments_log;");
    $pages       = ceil(mysqli_data_seek($pages_query, 0) / $per_page);

    if (mysqli_data_seek($pages_query, 0) == 0)
    {
        echo "Seems like the donation log was empty!";
    }
    else
    {

        $page   = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
        $start  = ($page - 1) * $per_page;
        ?>
        <table class="center">
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
            <?php
            $Server->selectDB('webdb');
            $result = mysqli_query($conn, "SELECT * FROM payments_log ORDER BY id DESC LIMIT " . $start . "," . $per_page . ";");
            while ($row    = mysqli_fetch_assoc($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['datecreation']; ?></td>
                    <td><?php echo $Account->getAccName($row['userid']); ?></td>
                    <td><?php echo $row['buyer_email']; ?></td>
                    <td><?php echo $row['mc_gross']; ?></td>
                    <td><?php echo $row['paymentstatus']; ?></td>
                </tr>
        <?php } ?>
        </table>
        <hr/>
        <?php
        if ($pages >= 1 && $page <= $pages)
        {
            if ($page > 1)
            {
                $prev = $page - 1;
                echo '<a href="?p=donations&s=browse&page=' . $prev . '" title="Previous">Previous</a> &nbsp;';
            }
            for ($x = 1; $x <= $pages; $x++)
            {
                if ($page == $x)
                    echo '<a href="?p=donations&s=browse&page=' . $x . '" title="Page ' . $x . '"><b>' . $x . '</b></a> ';
                else
                    echo '<a href="?p=donations&s=browse&page=' . $x . '" title="Page ' . $x . '">' . $x . '</a> ';
            }

            if ($page < $x - 1)
            {
                $next = $page + 1;
                echo '&nbsp; <a href="?p=donations&s=browse&page=' . $next . '" title="Next">Next</a> &nbsp; &nbsp;';
            }
        }
    }
?>
