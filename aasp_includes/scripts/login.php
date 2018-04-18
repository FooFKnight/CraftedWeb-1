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

    define('INIT_SITE', TRUE);
    include('../../includes/misc/headers.php');
    include('../../includes/configuration.php');
    include('../functions.php');

    global $GameServer;

    $conn = $GameServer->connect();

###############################
    if (isset($_POST['login']))
    {
        if (empty($_POST['username']) || empty($_POST['password']) && !isset($_POST['username']) || !isset($_POST['password']))
        {
          die("Please enter both fields.");
        }

        $username     = mysqli_real_escape_string($conn, strtoupper(trim($_POST['username'])));
        $password     = mysqli_real_escape_string($conn, strtoupper(trim($_POST['password'])));
        $passwordHash = sha1("" . $username . ":" . $password . "");

        if(mysqli_select_db($conn, $GLOBALS['connection']['logondb']) == false)
        {
          die("Database Error.");
        }

        $result = mysqli_query($conn, "SELECT COUNT(id) FROM account WHERE username='" . $username . "' AND sha_pass_hash = '" . $passwordHash . "';");

        $getId    = mysqli_query($conn, "SELECT id FROM account WHERE username='" . $username . "';");
        $row      = mysqli_fetch_assoc($getId);
        $uid      = $row['id'];
        $result   = mysqli_query($conn, "SELECT gmlevel FROM account_access WHERE id='$uid' AND gmlevel>='" . $GLOBALS[$_POST['panel'] . 'Panel_minlvl'] . "';");

        if (mysqli_num_rows($result) == 0)
        {
          die("The specified account does not have access to log in!");
        }

        $rank = mysqli_fetch_assoc($result);

        $_SESSION['cw_' . $_POST['panel']]            = ucfirst(strtolower($username));
        $_SESSION['cw_' . $_POST['panel'] . '_id']    = $uid;
        $_SESSION['cw_' . $_POST['panel'] . '_level'] = $rank['gmlevel'];

        if (empty($_SESSION['cw_' . $_POST['panel']]) || empty($_SESSION['cw_' . $_POST['panel'] . '_id']) || empty($_SESSION['cw_' . $_POST['panel'] . '_level']))
        {
          die('The scripts encountered an error. (1 or more Sessions was set to NULL)');
        }

        sleep(1);
        die(TRUE);        
    }
###############################  
?>