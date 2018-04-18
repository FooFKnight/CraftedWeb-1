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
<?php 
  global $GamePage, $GameServer;
  $conn = $GameServer->connect();
  $GameServer->selectDB('webdb', $conn);
?>
<div class="box_right_title"><?php echo $GamePage->titleLink(); ?> &raquo; Menu</div>
<table class="center">
    <tr>
        <th>Position</th>
        <th>Title</th>
        <th>Url</th>
        <th>Shown When</th>
        <th>Actions</th>
    </tr>
    <?php
        $x      = 1;
        $result = mysqli_query($conn, "SELECT * FROM site_links ORDER BY position ASC");
        while ($row    = mysqli_fetch_assoc($result))
        {
            ?>
            <tr>
                <td><?php echo $x; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['url']; ?></td>
                <td><?php
                    if ($row['shownWhen'] == 'logged')
                    {
                        echo "Logged in";
                    }
                    elseif ($row['shownWhen'] == 'notlogged')
                    {
                        echo "Not logged in";
                    }
                    else
                    {
                        echo ucfirst($row['shownWhen']);
                    }
                    ?>
                </td>
                <td>
                    <a href="#" onclick="editMenu(<?php echo $row['position']; ?>)"
                       >Edit</a> &nbsp; <a href="#" onclick="deleteLink(<?php echo $row['position']; ?>)">Delete</a>
                </td>
            </tr>
        <?php $x++;
    }
?>
</table>
<br/>
<a href="#" onclick="addLink()" class="content_hider">Add a new link</a>