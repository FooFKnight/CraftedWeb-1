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

    session_start();
    define('INIT_SITE', TRUE);
    require('../configuration.php');
    require('../misc/connect.php');
    require('../classes/account.php');
    require('../classes/character.php');
    require('../classes/shop.php');

    global $Connect, $Account, $Shop, $Character;

    $conn = $Connect->connectToDB();

    if ($_POST['action'] == 'removeFromCart')
    {
        unset($_SESSION[$_POST['cart']][$_POST['entry']]);
        return;
    }

    if ($_POST['action'] == 'addShopitem')
    {
        $entry = (int) $_POST['entry'];
        $shop  = mysqli_real_escape_string($conn, $_POST['shop']);

        if (isset($_SESSION[$_POST['cart']][$entry]))
        {
            $_SESSION[$_POST['cart']][$entry]['quantity'] ++;
        }
        else
        {
            $Connect->selectDB('webdb', $conn);

            $result = mysqli_query($conn, 'SELECT entry,price FROM shopitems WHERE entry="' . $entry . '" AND in_shop="' . $shop . '";');
            if (mysqli_num_rows($result) != 0)
            {
                $row                                     = mysqli_fetch_array($result);
                $_SESSION[$_POST['cart']][$row['entry']] = array("quantity" => 1, "price" => $row['price']);
            }
        }
    }

    if ($_POST['action'] == 'clear')
    {
        unset($_SESSION['donateCart']);
        unset($_SESSION['voteCart']);
    }

    if ($_POST['action'] == 'getMinicart')
    {
        $num        = 0;
        $totalPrice = 0;

        if ($_POST['cart'] == "donateCart")
        {
            $curr = $GLOBALS['donation']['coins_name'];
        }
        else
        {
            $curr = "Vote Points";
        }

        if (!isset($_SESSION[$_POST['cart']]))
        {
            echo "<b>Show Cart:</b> 0 Items (0 " . $curr . ")";
            exit();
        }

        $Connect->selectDB('webdb', $conn);
        if (is_array($_SESSION[$_POST['cart']]) || is_object($_SESSION[$_POST['cart']]))
        {
            foreach ($_SESSION[$_POST['cart']] as $entry => $value)
            {
                $num = $num + $_SESSION[$_POST['cart']][$entry]['quantity'];

                $shop_filt = substr($_POST['cart'], 0, -4);

                $result = mysqli_query($conn, "SELECT price FROM shopitems WHERE entry='" . $entry . "' AND in_shop='" . mysqli_real_escape_string($conn, $shop_filt) . "';");
                $row    = mysqli_fetch_assoc($result);


                $totalPrice = $totalPrice + ( $_SESSION[$_POST['cart']][$entry]['quantity'] * $row['price'] );
            }
        }

        echo "<b>Show Cart:</b> " . $num . " Items (" . $totalPrice . " " . $curr . ")";
    }

    if ($_POST['action'] == 'saveQuantity')
    {
        if ($_POST['quantity'] == 0)
        {
            unset($_SESSION[$_POST['cart']][$_POST['entry']]);
        }
        else
        {
            $_SESSION[$_POST['cart']][$_POST['entry']]['quantity'] = $_POST['quantity'];
        }
    }

    if ($_POST['action'] == 'checkout')
    {
        $totalPrice = 0;

        $values = explode('*', $_POST['values']);

        $Connect->selectDB('webdb', $conn);
        require('../misc/ra.php');

        if (isset($_SESSION['donateCart']))
        {
            #####Donation Cart
            if (is_array($_SESSION['donateCart']) || is_object($_SESSION['donateCart']))
            {
                foreach ($_SESSION['donateCart'] as $entry => $value)
                {
                    $result = mysqli_query($conn, "SELECT price FROM shopitems WHERE entry='" . $entry . "' AND in_shop='donate';");
                    $row    = mysqli_fetch_assoc($result);

                    $add = $row['price'] * $_SESSION['donateCart'][$entry]['quantity'];

                    $totalPrice = $totalPrice + $add;
                }
            }


            if ($Account->hasDP($_SESSION['cw_user'], $totalPrice) == FALSE)
                die("You do not have enough " . $GLOBALS['donation']['coins_name'] . "!");

            $host      = $GLOBALS['realms'][$values[1]]['host'];
            $rank_user = $GLOBALS['realms'][$values[1]]['rank_user'];
            $rank_pass = $GLOBALS['realms'][$values[1]]['rank_pass'];
            $ra_port   = $GLOBALS['realms'][$values[1]]['ra_port'];

            if (is_array($_SESSION['donateCart']) || is_object($_SESSION['donateCart']))
            {
                foreach ($_SESSION['donateCart'] as $entry => $value)
                {
                    if ($_SESSION['donateCart'][$entry]['quantity'] > 12)
                    {
                        $num = $_SESSION['donateCart'][$entry]['quantity'];

                        while ($num > 0)
                        {
                            if ($num > 12)
                            {
                                $command = "send items " . $Character->getCharname($values[0], $values[1]) . " \"Your requested item\" \"Thanks for supporting us!\" " . $entry . ":12 ";
                            }
                            else
                            {
                                $command = "send items " . $Character->getCharname($values[0], $values[1]) . " \"Your requested item\" \"Thanks for supporting us!\" " . $entry . ":" . $num . " ";
                            }
                            $Shop->logItem("donate", $entry, $values[0], $Account->getAccountID($_SESSION['cw_user']), $values[1], $num);
                            sendRA($command, $rank_user, $rank_pass, $host, $ra_port);

                            $num = $num - 12;
                        }
                    }
                    else
                    {
                        $command = "send items " . $Character->getCharname($values[0], $values[1]) . " \"Your requested item\" \"Thanks for supporting us!\" " . $entry . ":" . $_SESSION['donateCart'][$entry]['quantity'] . " ";
                        $Shop->logItem("donate", $entry, $values[0], $Account->getAccountID($_SESSION['cw_user']), $values[1], $_SESSION['donateCart'][$entry]['quantity']);
                        sendRA($command, $rank_user, $rank_pass, $host, $ra_port);
                    }
                }
            }

            $Account->deductDP($Account->getAccountID($_SESSION['cw_user']), $totalPrice);
            unset($_SESSION['donateCart']);
        }
        ######

        if (isset($_SESSION['voteCart']))
        {
            #####Donation Cart
            if (is_array($_SESSION['voteCart']) || is_object($_SESSION['voteCart']))
            {
                foreach ($_SESSION['voteCart'] as $entry => $value)
                {
                    $result = mysqli_query($conn, "SELECT price FROM shopitems WHERE entry='" . $entry . "' AND in_shop='vote';");
                    $row    = mysqli_fetch_assoc($result);

                    $add = $row['price'] * $_SESSION['voteCart'][$entry]['quantity'];

                    $totalPrice = $totalPrice + $add;
                }
            }

            if ($Account->hasVP($_SESSION['cw_user'], $totalPrice) == FALSE)
            {
                die("You do not have enough Vote Points!");
            }

            $host      = $GLOBALS['realms'][$values[1]]['host'];
            $rank_user = $GLOBALS['realms'][$values[1]]['rank_user'];
            $rank_pass = $GLOBALS['realms'][$values[1]]['rank_pass'];
            $ra_port   = $GLOBALS['realms'][$values[1]]['ra_port'];

            if (is_array($_SESSION['voteCart']) || is_object($_SESSION['voteCart']))
            {
                foreach ($_SESSION['voteCart'] as $entry => $value)
                {
                    if ($_SESSION['voteCart'][$entry]['quantity'] > 12)
                    {
                        $num = $_SESSION['voteCart'][$entry]['quantity'];

                        while ($num > 0)
                        {
                            if ($num > 12)
                            {
                                $command = "send items " . $Character->getCharname($values[0], $values[1]) . " \"Your requested item\" \"Thanks for supporting us!\" " . $entry . ":12 ";
                            }
                            else
                            {
                                $command = "send items " . $Character->getCharname($values[0], $values[1]) . " \"Your requested item\" \"Thanks for supporting us!\" " . $entry . ":" . $num . " ";
                            }
                            $Shop->logItem("vote", $entry, $values[0], $Account->getAccountID($_SESSION['cw_user']), $values[1], $num);
                            sendRA($command, $rank_user, $rank_pass, $host, $ra_port);
                            $num = $num - 12;
                        }
                    }
                    else
                    {
                        $command = "send items " . $Character->getCharname($values[0], $values[1]) . " \"Your requested item\" \"Thanks for supporting us!\" " . $entry . ":" . $_SESSION['voteCart'][$entry]['quantity'] . " ";
                        $Shop->logItem("vote", $entry, $values[0], $Account->getAccountID($_SESSION['cw_user']), $values[1], $_SESSION['voteCart'][$entry]['quantity']);
                        sendRA($command, $rank_user, $rank_pass, $host, $ra_port);
                    }
                }
            }
            $Account->deductVP($Account->getAccountID($_SESSION['cw_user']), $totalPrice);
            unset($_SESSION['voteCart']);
        }
        ######
        echo TRUE;
    }

    if ($_POST['action'] == 'removeItem')
    {
        if ($Account->isGM($_SESSION['cw_user']) == FALSE)
        {
            exit();
        }

        $entry = (int) $_POST['entry'];
        $shop  = mysqli_real_escape_string($conn, $_POST['shop']);

        $Connect->selectDB('webdb', $conn);
        mysqli_query($conn, "DELETE FROM shopitems WHERE entry='" . $entry . "' AND in_shop='" . $shop . "'");
    }

    if ($_POST['action'] == 'editItem')
    {
        if ($Account->isGM($_SESSION['cw_user']) == FALSE)
        {
            exit();
        }

        $entry = (int) $_POST['entry'];
        $shop  = mysqli_real_escape_string($conn, $_POST['shop']);
        $price = (int) $_POST['price'];

        $Connect->selectDB('webdb', $conn);

        if ($price > 0)
        {
            mysqli_query($conn, "UPDATE shopitems SET price='" . $price . "' WHERE entry='" . $entry . "' AND in_shop='" . $shop . "'");
        }
    }