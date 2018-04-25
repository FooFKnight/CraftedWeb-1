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


    require('../ext_scripts_class_loader.php');

    if (isset($_POST['siteid']))
    {

        global $Connect, $Account, $Website, $conn;

        $siteid = (int) $_POST['siteid'];

        $Connect->selectDB('webdb', $conn);

        if ($Website->checkIfVoted($siteid, $GLOBALS['connection']['webdb']) == TRUE)
        {
            die("?p=vote");
        }

        $Connect->selectDB('webdb', $conn);
        $check = mysqli_query($conn, "SELECT COUNT(*) FROM votingsites WHERE id='" . $siteid . "'");
        if (mysqli_data_seek($check, 0) == 0)
        {
            die("?p=vote");
        }

        if ($GLOBALS['vote']['type'] == 'instant')
        {
            $acct_id = $Account->getAccountID($_SESSION['cw_user']);

            if (empty($acct_id))
            {
                exit();
            }

            $next_vote = time() + $GLOBALS['vote']['timer'];

            $Connect->selectDB('webdb', $conn);

            mysqli_query($conn, "INSERT INTO votelog (siteid,userid,timestamp,next_vote,ip)
		VALUES('" . $siteid . "','" . $acct_id . "','" . time() . "','" . $next_vote . "','" . $_SERVER['REMOTE_ADDR'] . "')");

            $getSiteData = mysqli_query($conn, "SELECT points,url FROM votingsites WHERE id='" . $siteid . "'");
            $row         = mysqli_fetch_assoc($getSiteData);

            //Update the points table.
            $add = $row['points'] * $GLOBALS['vote']['multiplier'];
            mysqli_query($conn, "UPDATE account_data SET vp=vp + " . $add . " WHERE id=" . $acct_id);

            echo $row['url'];
        }
        elseif ($GLOBALS['vote']['type'] == 'confirm')
        {
            $Connect->selectDB('webdb', $conn);
            $getSiteData = mysqli_query($conn, "SELECT points,url FROM votingsites WHERE id='" . (int) $_POST['siteid'] . "'");
            $row         = mysqli_fetch_assoc($getSiteData);


            $_SESSION['votingUrlID'] = (int) $_POST['siteid'];

            echo $row['url'];
        }
        else
        {
            die("Error!");
        }
    }