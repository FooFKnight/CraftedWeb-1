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

    global $GameServer, $GameAccount, $conn;

    $GameServer->selectDB('logondb');

###############################
    if ($_POST['action'] == 'edit')
    {
        $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
        $password = mysqli_real_escape_string($conn, trim(strtoupper($_POST['password'])));
        $vp       = (int) $_POST['vp'];
        $dp       = (int) $_POST['dp'];
        $id       = (int) $_POST['id'];
        $extended = NULL;

        $chk1 = mysqli_query($conn, "SELECT COUNT FROM account WHERE email='" . $email . "' AND id='" . $od . "';");
        if (mysqli_data_seek($chk1, 0) > 0)
        {
            $extended .= "Changed email to" . $email . "<br/>";
        }

        mysqli_query($conn, "UPDATE account SET email='" . $email . "' WHERE id='" . $id . "';");
        $GameServer->selectDB('webdb');

        mysqli_query($conn, "INSERT IGNORE INTO account_data VALUES('" . $id . "','','','');");

        $chk2 = mysqli_query($conn, "SELECT COUNT FROM account_data WHERE vp='" . $vp . "' AND id='" . $od . "';");
        if (mysqli_query($conn, $chk2, 0) > 0)
        {
            $extended .= "Updated Vote Points to " . $vp . "<br/>";
        }

        $chk3 = mysqli_query($conn, "SELECT COUNT FROM account_data WHERE dp='" . $dp . "' AND id='" . $od . "';");
        if (mysqli_query($conn, $chk3, 0) > 0)
        {
            $extended .= "Updated Donation Coins to " . $dp . "<br/>";
        }


        mysqli_query($conn, "UPDATE account_data SET vp='" . $vp . "', dp ='" . $dp . "' WHERE id='" . $id . "';");

        if (!empty($password))
        {
            $username = strtoupper(trim($GameAccount->getAccName($id)));

            $password = sha1("" . $username . ":" . $password . "");
            $GameServer->selectDB('logondb');
            mysqli_query($conn, "UPDATE account SET sha_pass_hash='" . $password . "' WHERE id='" . $id . "';");
            mysqli_query($conn, "UPDATE account SET v='0',s='0' WHERE id='" . $id . "';");
            $extended .= "Changed password<br/>";
        }


        $GameServer->logThis("Modified account information for " . ucfirst(strtolower($GameAccount->getAccName($id))), $extended);
        echo "Settings were saved.";
    }
###############################
    if ($_POST['action'] == 'saveAccA')
    {
        $id    = (int) $_POST['id'];
        $rank  = (int) $_POST['rank'];
        $realm = mysqli_real_escape_string($conn, $_POST['realm']);

        mysqli_query($conn, "UPDATE account_access SET gmlevel='" . $rank . "',RealmID='" . $realm . "' WHERE id='" . $id . "';");
        $GameServer->logThis("Modified account access for " . ucfirst(strtolower($GameAccount->getAccName($id))));
    }
###############################
    if ($_POST['action'] == 'removeAccA')
    {
        $id = (int) $_POST['id'];

        mysqli_query($conn, "DELETE FROM account_access WHERE id='" . $id . "';");
        $GameServer->logThis("Modified GM account access for " . ucfirst(strtolower($GameAccount->getAccName($id))));
    }
###############################
    if ($_POST['action'] == 'addAccA')
    {
        $user  = mysqli_real_escape_string($conn, $_POST['user']);
        $realm = mysqli_real_escape_string($conn, $_POST['realm']);
        $rank  = (int) $_POST['rank'];

        $guid = $GameAccount->getAccID($user);

        mysqli_query($conn, "INSERT INTO account_access VALUES('" . $guid . "','" . $rank . "','" . $realm . "');");
        $GameServer->logThis("Added GM account access for " . ucfirst(strtolower($GameAccount->getAccName($guid))));
    }
###############################
    if ($_POST['action'] == 'editChar')
    {
        $guid            = (int) $_POST['guid'];
        $rid             = (int) $_POST['rid'];
        $name            = mysqli_real_escape_string($conn, trim(ucfirst(strtolower($_POST['name']))));
        $class           = (int) $_POST['class'];
        $race            = (int) $_POST['race'];
        $gender          = (int) $_POST['gender'];
        $money           = (int) $_POST['money'];
        $GameAccountname = mysqli_real_escape_string($conn, $_POST['account']);
        $GameAccountid   = $GameAccount->getAccID($GameAccountname);

        if (empty($guid) || empty($rid) || empty($name) || empty($class) || empty($race))
        {
            exit('Error');
        }

        $GameServer->connectToRealmDB($rid);

        $online = mysqli_query($conn, "SELECT COUNT(*) FROM characters WHERE guid='" . $guid . "' AND online=1;");
        if (mysqli_data_seek($online, 0) > 0)
        {
            exit('The character must be online for any change to take effect!');
        }

        mysqli_query($conn, "UPDATE characters SET name='" . $name . "',class='" . $class . "',race='" . $race . "',gender='" . $gender . "', money='" . $money . "', account='" . $GameAccountid . "'	WHERE guid='" . $guid . "';");

        echo 'The character was saved!';

        $chk = mysqli_query($conn, "SELECT COUNT(*) FROM characters WHERE name='" . $name . "';");

        if (mysqli_data_seek($chk, 0) > 1)
        {
            echo '<br/><b>NOTE:</b> It seems like there more than 1 character with this name, this might force them to rename when they log in.';
        }

        $GameServer->logThis("Modified character data for " . $name);
    }
###############################
?>