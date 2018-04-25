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
  $conn = $GameServer->connect();
  $GameServer->selectDB('webdb', $conn);
?>
<div class="box_right_title">Plugins</div>
<table>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Author</th>
        <th>Created</th>
        <th>Status</th>
    </tr>
    <?php
        $bad = array('.', '..', 'index.html');

        $folder = scandir('../plugins/');

        if (is_array($folder) || is_object($folder))
        {
            foreach ($folder as $folderName)
            {
                if (!in_array($folderName, $bad))
                {
                    if (file_exists('../plugins/' . $folderName . '/info.php'))
                    {
                        include('../plugins/' . $folderName . '/info.php');
                        ?> <tr class="center" onclick="window.location = '?p=interface&s=viewplugin&plugin=<?php echo $folderName; ?>'"> <?php
                            echo '<td><a href="?p=interface&s=viewplugin&plugin=' . $folderName . '">' . $title . '</a></td>';
                            echo '<td>' . substr($desc, 0, 40) . '</td>';
                            echo '<td>' . $author . '</td>';
                            echo '<td>' . $created . '</td>';
                            $chk = mysqli_query($conn, "SELECT COUNT(*) FROM disabled_plugins WHERE foldername='" . mysqli_real_escape_string($conn, $folderName) . "';");
                            if (mysqli_data_seek($chk, 0) > 0)
                                echo '<td>Disabled</td>';
                            else
                                echo '<td>Enabled</td>';
                            echo '</tr>';
                        }
                    }
                }
            }


            if ($count == 0)
            {
                $_SESSION['loaded_plugins'] = NULL;
            }
            else
            {
                $_SESSION['loaded_plugins'] = $loaded_plugins;
            }
        ?>
</table>