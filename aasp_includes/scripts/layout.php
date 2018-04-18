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
    global $Server, $Account, $conn;

    $Server->selectDB('webdb');

###############################
    if ($_POST['action'] == "setTemplate")
    {
        mysqli_query($conn, "UPDATE template SET applied='0' WHERE applied='1';");
        mysqli_query($conn, "UPDATE template SET applied='1' WHERE id='" . (int) $_POST['id'] . "';");
    }
###############################
    if ($_POST['action'] == "installTemplate")
    {
        mysqli_query($conn, "INSERT INTO template VALUES('','" . mysqli_real_escape_string($conn, trim($_POST['name'])) . "','" . mysqli_real_escape_string($conn, trim($_POST['path'])) . "','0')");
        $Server->logThis("Installed the template " . $_POST['name']);
    }
###############################
    if ($_POST['action'] == "uninstallTemplate")
    {
        mysqli_query($conn, "DELETE FROM template WHERE id='" . (int) $_POST['id'] . "';");
        mysqli_query($conn, "UPDATE template SET applied='1' ORDER BY id ASC LIMIT 1;");

        $Server->logThis("Uninstalled a template");
    }
###############################
    if ($_POST['action'] == "getMenuEditForm")
    {
        $result = mysqli_query($conn, "SELECT * FROM site_links WHERE position='" . (int) $_POST['id'] . "';");
        $rows   = mysqli_fetch_assoc($result);
        ?>
        Title<br/>
        <input type="text" id="editlink_title" value="<?php echo $rows['title']; ?>"><br/>
        URL<br/>
        <input type="text" id="editlink_url" value="<?php echo $rows['url']; ?>"><br/>
        Show when<br/>
        <select id="editlink_shownWhen">
            <option value="always" <?php if ($rows['shownWhen'] == "always")
        {
            echo "selected='selected'";
        } ?>>Always</option>
            <option value="logged" <?php if ($rows['shownWhen'] == "logged")
        {
            echo "selected='selected'";
        } ?>>The user is logged in</option>
            <option value="notlogged" <?php if ($rows['shownWhen'] == "notlogged")
    {
        echo "selected='selected'";
    } ?>>The user is not logged in</option>
        </select><br/>
        <input type="submit" value="Save" onclick="saveMenuLink('<?php echo $rows['position']; ?>')">

    <?php
    }
###############################
    if ($_POST['action'] == "saveMenu")
    {
        $title     = mysqli_real_escape_string($conn, $_POST['title']);
        $url       = mysqli_real_escape_string($conn, $_POST['url']);
        $shownWhen = mysqli_real_escape_string($conn, $_POST['shownWhen']);
        $id        = (int) $_POST['id'];

        if (empty($title) || empty($url) || empty($shownWhen))
        {
            die("Please enter all fields.");
        }

        mysqli_query($conn, "UPDATE site_links SET title='" . $title . "',url='" . $url . "',shownWhen='" . $shownWhen . "' WHERE position='" . $id . "';");

        $Server->logThis("Modified the menu");

        echo TRUE;
    }
###############################
    if ($_POST['action'] == "deleteLink")
    {
        mysqli_query($conn, "DELETE FROM site_links WHERE position='" . (int) $_POST['id'] . "';");

        $Server->logThis("Removed a menu link");

        echo TRUE;
    }
###############################
    if ($_POST['action'] == "addLink")
    {
        $title     = mysqli_real_escape_string($conn, $_POST['title']);
        $url       = mysqli_real_escape_string($conn, $_POST['url']);
        $shownWhen = mysqli_real_escape_string($conn, $_POST['shownWhen']);

        if (empty($title) || empty($url) || empty($shownWhen))
        {
            die("Please enter all fields.");
        }

        mysqli_query($conn, "INSERT INTO site_links VALUES('','" . $title . "','" . $url . "','" . $shownWhen . "');");

        $Server->logThis("Added " . $title . " to the menu");

        echo TRUE;
    }
###############################
    if ($_POST['action'] == "deleteImage")
    {
        $id = (int) $_POST['id'];
        mysqli_query($conn, "DELETE FROM slider_images WHERE position='" . $id . "';");

        $Server->logThis("Removed a slideshow image");

        return;
    }
###############################
    if ($_POST['action'] == "disablePlugin")
    {
        $foldername = mysqli_real_escape_string($conn, $_POST['foldername']);

        mysqli_query($conn, "INSERT INTO disabled_plugins VALUES('" . $foldername . "');");

        include('../../plugins/' . $foldername . '/info.php');
        $Server->logThis("Disabled the plugin " . $title);
    }
###############################
    if ($_POST['action'] == "enablePlugin")
    {
        $foldername = mysqli_real_escape_string($conn, $_POST['foldername']);

        mysqli_query($conn, "DELETE FROM disabled_plugins WHERE foldername='" . $foldername . "';");

        include('../../plugins/' . $foldername . '/info.php');
        $Server->logThis("Enabled the plugin " . $title);
    }
###############################
?>