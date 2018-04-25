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


    require('../ext_scripts_class_loader.php');

    global $Connect, $Server, $Character, $Account, $conn;

    if ($_POST['action'] == 'unstuck')
    {
        $guid     = (int) $_POST['guid'];
        $realm_id = $Server->getRealmId($_POST['char_db']);
        $Connect->connectToRealmDB($realm_id);

        $Character->unstuck($guid, $_POST['char_db']);
    }

    if ($_POST['action'] == 'revive')
    {
        $guid     = (int) $_POST['guid'];
        $realm_id = $Server->getRealmId($_POST['char_db']);
        $Connect->connectToRealmDB($realm_id);

        $Character->revive($guid, $_POST['char_db']);
    }

    if ($_POST['action'] == 'getLocations')
    {
        $values = explode('*', $_POST['values']);

        $char     = mysqli_real_escape_string($conn, $values[0]);
        $realm_id = $Server->getRealmId($values[1]);
        $Connect->connectToRealmDB($realm_id);

        $result   = mysqli_query($conn, "SELECT race FROM characters WHERE guid='" . $char . "'");
        $row      = mysqli_fetch_assoc($result);
        $alliance = array(1, 3, 4, 7, 11);
        if (in_array($row['race'], $alliance))
        {
            //Alliance
            $locations_name = array(
                1 => "Stormwind",
                2 => "Ironforge",
                3 => "Darnassus",
                4 => "The Exodar",
                5 => "Dalaran",
                6 => "Shattrath"
            );

            $locations_image = array(
                "Stormwind"  => "spell_arcane_teleportstormwind",
                "Ironforge"  => "spell_arcane_teleportironforge",
                "Darnassus"  => "spell_arcane_teleportdarnassus",
                "The Exodar" => "spell_arcane_teleportexodar",
                "Dalaran"    => "spell_arcane_teleportdalaran",
                "Shattrath"  => "spell_arcane_teleportshattrath"
            );
        }
        else
        {
            //Horde
            $locations_name  = array(
                1 => "Orgrimmar",
                2 => "Undercity",
                3 => "Thunder Bluff",
                4 => "Silvermoon",
                5 => "Dalaran",
                6 => "Shattrath"
            );
            $locations_image = array(
                "Orgrimmar"     => "spell_arcane_teleportorgrimmar",
                "Undercity"     => "spell_arcane_teleportundercity",
                "Thunder Bluff" => "spell_arcane_teleportthunderbluff",
                "Silvermoon"    => "spell_arcane_teleportsilvermoon",
                "Dalaran"       => "spell_arcane_teleportdalaran",
                "Shattrath"     => "spell_arcane_teleportshattrath"
            );
        }
        echo '<h3>Choose Location</h3>';
        if (is_array($locations_name) || is_object($locations_name))
        {
            foreach ($locations_name as $v)
            {
                ?>
                <div class="charBox" style="cursor:pointer;" onclick="portTo('<?php echo $v; ?>', '<?php echo $values[1]; ?>', '<?php echo $values[0]; ?>')">
                    <table width="100%">
                        <tr> <td width="15%"><img src="styles/global/images/icons/<?php echo $locations_image[$v] ?>.jpg" /></td>
                            <td align="left" width="90%"><b><?php echo $v; ?></b><br/>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
            }
        }
    }

    if ($_POST['action'] == 'teleport')
    {
        $character = mysqli_real_escape_string($conn, $_POST['character']);
        $char_db   = mysqli_real_escape_string($conn, $_POST['char_db']);
        $location  = mysqli_real_escape_string($conn, $_POST['location']);

        $realm_id = $Server->getRealmId($_POST['char_db']);
        $Connect->connectToRealmDB($realm_id);
        $result   = mysqli_query($conn, "SELECT race,account,level,online FROM characters WHERE guid='" . $character . "'");

        if (mysqli_num_rows($result) == 0)
        {
            die("<span class='alert'>The character does not exist on that account!</span>");
        }
        else
        {
            $row = mysqli_fetch_assoc($result);

            if ($row['online'] == 1)
            {
                die("Please log out before teleporting.");
            }

            $acct  = $row['account'];
            $race  = $row['race'];
            $level = $row['level'];

            if ($GLOBALS['service']['teleport']['currency'] == "vp" && $GLOBALS['service']['teleport']['price'] > 0)
            {
                if ($Account->hasVP($_SESSION['cw_user'], $GLOBALS['service']['teleport']['price']) == false)
                {
                    die("Insufficent Vote Points!");
                }
            }
            elseif ($GLOBALS['service']['teleport']['currency'] == "dp" && $GLOBALS['service']['teleport']['price'] > 0)
            {
                if ($Account->hasDP($_SESSION['cw_user'], $GLOBALS['service']['teleport']['price']) == false)
                {
                    die("Insufficent " . $GLOBALS['donation']['coins_name'] . "!");
                }
            }

            $map = $x   = $y   = $z   = null;

            switch ($location)
            {
                //stormwind
                case "Stormwind":
                    $map = "0";
                    $x   = "-8913.23";
                    $y   = "554.633";
                    $z   = "93.7944";
                    break;
                //ironforge
                case "Ironforge":
                    $map = "0";
                    $x   = "-4981.25";
                    $y   = "-881.542";
                    $z   = "501.66";
                    break;
                //darnassus
                case "Darnassus":
                    $map = "1";
                    $x   = "9951.52";
                    $y   = "2280.32";
                    $z   = "1341.39";
                    break;
                //exodar
                case "The Exodar":
                    $map = "530";
                    $x   = "-3987.29";
                    $y   = "-11846.6";
                    $z   = "-2.01903";
                    break;
                //orgrimmar
                case "Orgrimmar":
                    $map = "1";
                    $x   = "1676.21";
                    $y   = "-4315.29";
                    $z   = "61.5293";
                    break;
                //thunderbluff
                case "Thunder Bluff":
                    $map = "1";
                    $x   = "-1196.22";
                    $y   = "29.0941";
                    $z   = "176.949";
                    break;
                //undercity
                case "Undercity":
                    $map = "0";
                    $x   = "1586.48";
                    $y   = "239.562";
                    $z   = "-52.149";
                    break;
                //silvermoon
                case "Silvermoon":
                    $map = "530";
                    $x   = "9473.03";
                    $y   = "-7279.67";
                    $z   = "14.2285";
                    break;
                //shattrath
                case "Shattrath":
                    $map = "530";
                    $x   = "-1863.03";
                    $y   = "4998.05";
                    $z   = "-21.1847";
                    break;
                //dalaran
                case "Dalaran":
                    $map = "571";
                    $x   = "5812.79";
                    $y   = "647.158";
                    $z   = "647.413";
                    break;
            }

            //disallows factions to use enemy portals
            switch ($race)
            {
                //alliance
                case 1:
                case 3:
                case 4:
                case 7:
                case 11:
                    if ((($location >= 5) && ($location <= 8)) && ($location != 9))
                        die("<span class='alert'>Alliance players can <b>NOT</b> Teleport to Horde areas!</span>");
                    break;

                //horde
                case 2:
                case 5:
                case 6:
                case 8:
                case 10:
                    if ((($location >= 1) && ($location <= 4)) && ($location != 9))
                        die("<span class='alert'>Horde Players can <b>NOT</b> Teleport to Alliance areas!</span>");
                    break;

                default:
                    die("<span class='alert'>That is not a valid Race!</span>");
                    break;
            }

            if ($location == "Dalaran" && $level < 68)
            {
                die("Aborting...<br/><span class='alert'>Your character must be level 68 or higher to teleport to Northrend!</span>");
            }

            if ($GLOBALS['service']['teleport']['currency'] == "vp")
            {
                $Account->deductVP($Account->getAccountID($_SESSION['cw_user']), $GLOBALS['service']['teleport']['price']);
            }
            elseif ($GLOBALS['service']['teleport']['currency'] == "dp")
            {
                $Account->deductDP($Account->getAccountID($_SESSION['cw_user']), $GLOBALS['service']['teleport']['price']);
            }

            $Connect->connectToRealmDB($realm_id);

            //get pos x, y etc for the logs.
            $result = mysqli_query($conn, "SELECT position_x, position_y, position_z, map FROM characters WHERE guid='" . $character . "'");
            $pos    = mysqli_fetch_assoc($result);

            $char_x   = $pos['position_x'];
            $char_y   = $pos['position_y'];
            $char_z   = $pos['position_z'];
            $char_map = $pos['map'];
            $from     = "X: " . $char_x . " - Y: " . $char_y . " - Z: " . $char_z . " - MAP ID: " . $char_map;

            mysqli_query($conn, "UPDATE characters SET position_x = " . $x . ", position_y= " . $y . ", position_z = " . $z . ", map = " . $map . " WHERE account = " . $acct . " 
				     AND guid = '" . $character . "'");

            if ($GLOBALS['service']['teleport']['currency'] == "vp")
            {
                echo $GLOBALS['service']['teleport']['price'] . " Vote Points was taken from your account.";
            }
            elseif ($GLOBALS['service']['teleport']['currency'] == "dp")
            {
                echo $GLOBALS['service']['teleport']['price'] . " " . $GLOBALS['donation']['coins_name'] . " was taken from your account.";
            }
            $Account->logThis("Teleported " . $Character->getCharName($character, $realm_id) . " to " . $location, 'Teleport', $realm_id);
            echo true;
        }
    }

    if ($_POST['action'] == 'service')
    {
        $guid     = (int) $_POST['guid'];
        $realm_id = (int) $_POST['realm_id'];
        $serviceX = mysqli_real_escape_string($conn, $_POST['service']);


        if ($Character->isOnline($guid) == true)
        {
            die('<b class="red_text">Please log out your character before proceeding.');
        }

        if ($GLOBALS['service'][$serviceX]['currency'] == 'vp')
        {
            if ($Account->hasVP($_SESSION['cw_user'], $GLOBALS['service'][$serviceX]['price']) == false)
                die('<b class="red_text">Not enough Vote Points!</b>');
        }

        if ($GLOBALS['service'][$serviceX]['currency'] == 'dp')
        {
            if ($Account->hasDP($_SESSION['cw_user'], $GLOBALS['service'][$serviceX]['price']) == false)
            {
                die('<b class="red_text">Not enough ' . $GLOBALS['donation']['coins_name'] . '</b>');
            }
        }

        switch ($serviceX)
        {
            default:
                die("Unknown Error");
                break;

            case('appearance'):
                $command = "customize";
                $info    = "Character customization";
                break;

            case('name'):
                $command = "rename";
                $info    = "Character rename";
                break;

            case('faction'):
                $command = "changefaction";
                $info    = "Faction change";
                break;

            case('race'):
                $command = "changerace";
                $info    = "Race change";
                break;
        }

        $Connect->selectDB('webdb', $conn);
        $getRA = mysqli_query($conn, "SELECT sendType,host,ra_port,soap_port,rank_user,rank_pass FROM realms WHERE id='" . $realm_id . "'");
        $row   = mysqli_fetch_assoc($getRA);

        if ($row['sendType'] == 'ra')
        {
            require('../misc/ra.php');

            sendRa("character " . $command . " " . $Character->getCharname($guid, $realm_id), $row['rank_user'], $row['rank_pass'], $row['host'], $row['ra_port']);
        }
        elseif ($row['sendType'] == "soap")
        {
            require('../misc/soap.php');

            sendSoap("character " . $command . " " . $Character->getCharname($guid, $realm_id), $row['rank_user'], $row['rank_pass'], $row['host'], $row['soap_port']);
        }

        if ($GLOBALS['service'][$serviceX]['currency'] == 'vp')
        {
            $Account->deductVP($Account->getAccountID($_SESSION['cw_user']), $GLOBALS['service'][$serviceX]['price']);
        }

        if ($GLOBALS['service'][$serviceX]['currency'] == 'dp')
        {
            $Account->deductDP($Account->getAccountID($_SESSION['cw_user']), $GLOBALS['service'][$serviceX]['price']);
        }

        $Account->logThis("Performed a " . $info . " on " . $Character->getCharName($guid, $realm_id), $serviceX, $realm_id);

        echo true;
    }
?>