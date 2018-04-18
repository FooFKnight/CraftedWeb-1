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
    if ($_POST['function'] == 'post')
    {
        if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['content']))
        {
            die('<span class="red_text">Please enter all fields.</span>');
        }

        mysqli_query($conn, "INSERT INTO news (title,body,author,image,date) VALUES
	('" . mysqli_real_escape_string($conn, $_POST['title']) . "','" . mysqli_real_escape_string($conn, $_POST['content']) . "',
	'" . mysqli_real_escape_string($conn, $_POST['author']) . "','" . mysqli_real_escape_string($conn, $_POST['image']) . "',
	'" . date("Y-m-d H:i:s") . "');");

        $Server->logThis("Posted a news post");
        echo "Successfully posted news.";
    }
################################
    elseif ($_POST['function'] == 'delete')
    {
        if (empty($_POST['id']))
            die('No ID specified. Aborting...');

        mysqli_query($conn, "DELETE FROM news WHERE id='" . mysqli_real_escape_string($conn, $_POST['id']) . "';");
        mysqli_query($conn, "DELETE FROM news_comments WHERE id='" . mysqli_real_escape_string($conn, $_POST['id']) . "';");
        $Server->logThis("Deleted a news post");
    }
##############################
    elseif ($_POST['function'] == 'edit')
    {
        $id      = (int) $_POST['id'];
        $title   = mysqli_real_escape_string($conn, ucfirst($_POST['title']));
        $author  = mysqli_real_escape_string($conn, ucfirst($_POST['author']));
        $content = mysqli_real_escape_string($conn, $_POST['content']);

        if (empty($id) || empty($title) || empty($content))
        {
            die("Please enter both fields.");
        }
        else
        {
            mysqli_query($conn, "UPDATE news SET title='" . $title . "', author='" . $author . "', body='" . $content . "' WHERE id='" . $id . "';");
            $Server->logThis("Updated news post with ID: <b>" . $id . "</b>");
            return;
        }
    }
#############################
    elseif ($_POST['function'] == 'getNewsContent')
    {
        $result  = mysqli_query($conn, "SELECT * FROM news WHERE id='" . (int) $_POST['id'] . "'");
        $row     = mysqli_fetch_assoc($result);
        $content = str_replace('<br />', "\n", $row['body']);

        echo "Title: <br/><input type='text' id='editnews_title' value='" . $row['title'] . "'><br/>Content:<br/><textarea cols='55' rows='8' id='editnews_content'>"
        . $content . "</textarea><br/>Author:<br/><input type='text' id='editnews_author' value='" . $row['author'] . "'><br/><input type='submit' value='Save' onclick='editNewsNow(" . $row['id'] . ")'>";
    }
?>