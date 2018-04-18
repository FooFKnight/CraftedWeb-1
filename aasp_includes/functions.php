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
      \_\ \/ \___/|_| |_| |_|___/\___/|_|  \__| - www.Nomsoftware.com -
      The policy of Nomsoftware states: Releasing our software
      or any other files are protected. You cannot re-release
      anywhere unless you were given permission.
      � Nomsoftware 'Nomsoft' 2011-2012. All rights reserved. */

    if (!isset($_SESSION))
        session_start();

    if (isset($_SESSION['cw_staff']) && !isset($_SESSION['cw_staff_id']))
    {
        exit('Seems like you\'re missing 1 or more sessions. You\'ve been logged out due to security reasons.');
        session_destroy();
    }

    if (isset($_SESSION['cw_admin']) && !isset($_SESSION['cw_admin_id']))
    {
        exit('Seems like you\'re missing 1 or more sessions. You\'ve been logged out due to security reasons.');
        session_destroy();
    }

    class GameServer
    {

        public function getConnections()
        {
            global $conn;
            $this->selectDB('logondb');

            $result = mysqli_query($conn, "SELECT COUNT(id) FROM account WHERE online='1';");
            return mysqli_data_seek($result, 0);
        }

        public function getPlayersOnline($rid)
        {
            global $conn;
            $this->connectToRealmDB($rid);
            $result = mysqli_query($conn, "SELECT COUNT(guid) FROM characters WHERE online='1';");
            return round(mysqli_data_seek($result, 0));
        }

        public function getUptime($rid)
        {
            global $conn;
            $this->selectDB('logondb');

            $getUp = mysqli_query($conn, "SELECT starttime FROM uptime WHERE realmid='" . (int) $rid . "' ORDER BY starttime DESC LIMIT 1;");
            $row   = mysqli_fetch_assoc($getUp);

            $time   = time();
            $uptime = $time - $row['starttime'];

            if ($uptime < 60)
                $string = 'Seconds';
            elseif ($uptime > 60)
            {
                $uptime = $uptime / 60;
                $string = 'Minutes';
                if ($uptime > 60)
                {
                    $string = 'Hours';
                    $uptime = $uptime / 60;
                    if ($uptime > 24)
                    {
                        $string = 'Days';
                        $uptime = $uptime / 24;
                    }
                }
                $uptime = ceil($uptime);
            }
            return $uptime . ' ' . $string;
        }

        public function getServerStatus($rid)
        {
            global $conn;
            $this->selectDB('webdb');

            $result = mysqli_query($conn, "SELECT host,port FROM realms WHERE id='" . (int) $rid . "'");
            $row    = mysqli_fetch_assoc($result);

            $fp = fsockopen($row['host'], $row['port'], $errno, $errstr, 1);
            if (!$fp)
                return '<font color="#990000">Offline</font>';
            else
                return 'Online';
        }

        public function getGMSOnline()
        {
            global $conn;
            $this->selectDB('logondb');
            $result = mysqli_query($conn, "SELECT COUNT(id) FROM account WHERE username IN ( select username FROM account WHERE online IN ('1')) AND id IN (SELECT id FROM account_access WHERE gmlevel>'1');");

            return mysqli_data_seek($result, 0);
        }

        public function getAccountsCreatedToday()
        {
            global $conn;
            $this->selectDB('logondb');
            $result = mysqli_query($conn, "SELECT COUNT(id) FROM account WHERE joindate LIKE '%" . date("Y-m-d") . "%';");
            return mysqli_data_seek($result, 0);
        }

        public function getActiveAccounts()
        {
            global $conn;
            $this->selectDB('logondb');
            $result = mysqli_query($conn, "SELECT COUNT(id) FROM account WHERE last_login LIKE '%" . date("Y-m") . "%';");
            return mysqli_data_seek($result, 0);
        }

        public function getActiveConnections()
        {
            global $conn;
            $this->selectDB('logondb');
            $result = mysqli_query($conn, "SELECT COUNT(id) FROM account WHERE online=1;");
            return mysqli_data_seek($result, 0);
        }

        public function getFactionRatio($rid)
        {
            global $conn;
            $this->selectDB('webdb');
            $result = mysqli_query($conn, "SELECT id FROM realms;");
            if (mysqli_num_rows($result) == 0)
            {
                $this->faction_ratio = "Unknown";
            }
            else
            {
                $t   = 0;
                $a   = 0;
                $h   = 0;
                while ($row = mysqli_fetch_assoc($result))
                {
                    $this->connectToRealmDB($row['id']);
                    $result = mysqli_query($conn, "SELECT COUNT(*) FROM characters");
                    $t      = $t + mysqli_data_seek($result, 0);

                    $result = mysqli_query($conn, "SELECT COUNT(*) FROM characters WHERE race IN('3','4','7','11','1','22')");
                    $a      = $a + mysqli_data_seek($result, 0);

                    $result = mysqli_query($conn, "SELECT COUNT(*) FROM characters WHERE race IN('2','5','6','8','10','9')");
                    $h      = $h + mysqli_data_seek($result, 0);
                }
                $a = ($a / $t) * 100;
                $h = ($h / $t) * 100;
                return '<font color="#0066FF">' . round($a) . '%</font> &nbsp; <font color="#CC0000">' . round($h) . '%</font>';
            }
        }

        public function getAccountsLoggedToday()
        {
            global $conn;
            $this->selectDB('logondb');

            $result = mysqli_query($conn, "SELECT COUNT(*) FROM account WHERE last_login LIKE '%" . date('Y-m-d') . "%'");
            return mysqli_data_seek($result, 0);
        }

        public function connect()
        {
            return mysqli_connect($GLOBALS['connection']['host'], $GLOBALS['connection']['user'], $GLOBALS['connection']['password']);
        }

        public function connectToRealmDB($realmid)
        {
            global $conn;
            $this->selectDB('webdb');
            $getRealmData = mysqli_query($conn, "SELECT mysqli_host,mysqli_user,mysqli_pass,char_db FROM realms WHERE id='" . (int) $realmid . "'");
            if (mysqli_num_rows($getRealmData) > 0)
            {
                $row = mysqli_fetch_assoc($getRealmData);
                if ($row['mysqli_host'] != $GLOBALS['connection']['host'] || $row['mysqli_user'] != $GLOBALS['connection']['user'] || $row['mysqli_pass'] != $GLOBALS['connection']['password'])
                {
                    mysqli_connect($row['mysqli_host'], $row['mysqli_user'], $row['mysqli_pass'])or
                            buildError("<b>Database Connection error:</b> A connection could not be established to Realm. Error: " . mysqli_error($conn), NULL);
                }
                else
                {
                    $this->connect();
                }

                mysqli_select_db($conn, $row['char_db'])or
                        buildError("<b>Database Selection error:</b> The realm database could not be selected. Error: " . mysqli_error($conn), NULL);
            }
        }

        public function selectDB($db)
        {
            $this->connect();

            switch ($db)
            {
                default:
                    mysqli_select_db($conn, $db);
                    break;
                case('logondb'):
                    mysqli_select_db($conn, $GLOBALS['connection']['logondb']);
                    break;
                case('webdb'):
                    mysqli_select_db($conn, $GLOBALS['connection']['webdb']);
                    break;
                case('worlddb'):
                    mysqli_select_db($conn, $GLOBALS['connection']['worlddb']);
                    break;
            }
        }

        public function getItemName($id)
        {
            $this->selectDB('worlddb');

            $result = mysqli_query($conn, "SELECT name FROM item_template WHERE entry='" . $id . "'");
            $row    = mysqli_fetch_assoc($result);
            return $row['name'];
        }

        public function getAddress()
        {
            return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        public function logThis($action, $extended = NULL)
        {
            global $conn;
            $this->selectDB('webdb');
            $url = $this->getAddress();

            if (isset($_SESSION['cw_admin']))
                $aid = (int) $_SESSION['cw_admin_id'];
            elseif (isset($_SESSION['cw_staff']))
                $aid = (int) $_SESSION['cw_staff_id'];

            mysqli_query($conn, "INSERT INTO admin_log VALUES ('','" . mysqli_real_escape_string($conn, $url) . "','" . $_SERVER['REMOTE_ADDR'] . "',
        '" . time() . "','" . mysqli_real_escape_string($conn, $action) . "','" . $aid . "','" . mysqli_real_escape_string($conn, $extended) . "')");
        }

        public function addRealm($id, $name, $desc, $host, $port, $chardb, $sendtype, $rank_user, $rank_pass, $ra_port, $soap_port, $m_host, $m_user, $m_pass)
        {
            global $conn;
            $id        = (int) $id;
            $name      = mysqli_real_escape_string($conn, $name);
            $desc      = mysqli_real_escape_string($conn, $desc);
            $host      = mysqli_real_escape_string($conn, $host);
            $port      = mysqli_real_escape_string($conn, $port);
            $chardb    = mysqli_real_escape_string($conn, $chardb);
            $sendtype  = mysqli_real_escape_string($conn, $sendtype);
            $rank_user = mysqli_real_escape_string($conn, $rank_user);
            $rank_pass = mysqli_real_escape_string($conn, $rank_pass);
            $ra_port   = mysqli_real_escape_string($conn, $ra_port);
            $soap_port = mysqli_real_escape_string($conn, $soap_port);
            $m_host    = mysqli_real_escape_string($conn, $m_host);
            $m_user    = mysqli_real_escape_string($conn, $m_user);
            $m_pass    = mysqli_real_escape_string($conn, $m_pass);

            if (empty($name) || empty($host) || empty($port) || empty($chardb) || empty($rank_user) || empty($rank_pass))
                echo "<pre><b class='red_text'>Please enter all required fields!</b></pre><br/>";
            else
            {
                if (empty($m_host))
                    $m_host = $GLOBALS['connection']['host'];
                if (empty($m_user))
                    $m_host = $GLOBALS['connection']['user'];
                if (empty($m_pass))
                    $m_pass = $GLOBALS['connection']['password'];

                if (empty($ra_port))
                    $ra_port   = 3443;
                if (empty($soap_port))
                    $soap_port = 7878;

                $this->selectDB('webdb');
                mysqli_query($conn, "INSERT INTO realms VALUES ('" . $id . "','" . $name . "','" . $desc . "','" . $chardb . "','" . $port . "',
          '" . $rank_user . "','" . $rank_pass . "','" . $ra_port . "','" . $soap_port . "','" . $host . "','" . $sendtype . "','" . $m_host . "',
          '" . $m_user . "','" . $m_pass . "')");

                $this->logThis("Added the realm " . $name . "<br/>");

                echo '<pre><h3>&raquo; Successfully added the realm ' . $name . '!</h3></pre><br/>';
            }
        }

        public function getRealmName($realm_id)
        {
            global $conn;
            $this->selectDB('webdb');

            $result = mysqli_query($conn, "SELECT name FROM realms WHERE id='" . (int) $realm_id . "'");
            $row    = mysqli_fetch_assoc($result);

            if (empty($row['name']))
                return '<i>Unknown</i>';
            else
                return $row['name'];
        }

        public function checkForNotifications()
        {
            /* Not used! */
            $this->selectDB('webdb');



            //Check for old votelogs
            $old    = time() - 2592000;
            $result = mysqli_query($conn, "SELECT COUNT(*) FROM votelog WHERE `timestamp` <= " . $old . "");

            if (mysqli_data_seek($result, 0) > 1)
            {
                echo '<div class="box_right">
                  <div class="box_right_title">Notifications</div>';
                echo 'You have ' . mysqli_data_seek($conn, $result, 0) . ' votelog records that are 30 days or older. Since these are not really needed in general. 
                     We suggest you clear these. ';
                echo '</div>';
            }
        }

        public function serverStatus()
        {
            if (!isset($_COOKIE['presetRealmStatus']))
            {
                $this->selectDB('webdb');
                $getRealm = mysqli_query($conn, 'SELECT id FROM realms ORDER BY id ASC LIMIT 1');
                $row      = mysqli_fetch_assoc($getRealm);

                $rid = $row['id'];
            }
            else
                $rid = $_COOKIE['presetRealmStatus'];

            echo 'Selected realm: <b>' . $this->getRealmName($rid) . '</b> <a href="#" onclick="changePresetRealmStatus()">(Change Realm)</a><hr/>';
            ?>
            <table>
                <tr valign="top">
                    <td width="70%">
                        Server Status: <br/>
                        Uptime: <br/>
                        Players online: <br/>
                    </td>
                    <td>
                        <b>
                            <?php echo $this->getServerStatus($rid); ?><br/>
                            <?php echo $this->getUptime($rid); ?><br/>
                            <?php echo $this->getPlayersOnline($rid); ?><br/>
                        </b>
                    </td>
                </tr>
            </table>
            <hr/>
            <b>General Status:</b><br/>
            <table>
                <tr valign="top">
                    <td width="70%">
                        Active connections: <br/>
                        Accounts created today: <br/>
                        Active accounts (This month)
                    </td>
                    <td>
                        <b>
                            <?php echo $this->getActiveConnections(); ?><br/>
                            <?php echo $this->getAccountsCreatedToday(); ?><br/>
                            <?php echo $this->getActiveAccounts(); ?><br/>
                        </b>
                    </td>
                </tr>
            </table>

            <?php
        }

    }

    class GameAccount
    {

        public function getAccID($user)
        {
            $server = new server;
            $server->selectDB('logondb');

            global $conn;

            $user   = mysqli_real_escape_string($conn, $user);
            $result = mysqli_query($conn, "SELECT id FROM account WHERE username='" . mysqli_real_escape_string($conn, $user) . "'");
            $row    = mysqli_fetch_assoc($result);

            return $row['id'];
        }

        public function getAccName($id)
        {
            global $conn;
            $server = new server;
            $server->selectDB('logondb');

            $result = mysqli_query($conn, "SELECT username FROM account WHERE id='" . (int) $id . "'");
            $row    = mysqli_fetch_assoc($result);

            if (empty($row['username']))
                return '<i>Unknown</i>';
            else
                return ucfirst(strtolower($row['username']));
        }

        public function getCharName($id, $realm_id)
        {
            global $conn;
            $server = new server;

            $server->connectToRealmDB($realm_id);

            $result = mysqli_query($conn, "SELECT name FROM characters WHERE guid='" . (int) $id . "'");
            if (mysqli_num_rows($result) == 0)
                return '<i>Unknown</i>';
            else
            {
                $row = mysqli_fetch_assoc($result);
                if (empty($row['name']))
                    return '<i>Unknown</i>';
                else
                    return $row['name'];
            }
        }

        public function getEmail($id)
        {
            global $conn;
            $server = new server;
            $server->selectDB('logondb');

            $result = mysqli_query($conn, "SELECT email FROM account WHERE id='" . (int) $id . "'");
            $row    = mysqli_fetch_assoc($result);
            return $row['email'];
        }

        public function getVP($id)
        {
            global $conn;
            $server = new server;
            $server->selectDB('webdb');

            $result = mysqli_query($conn, "SELECT vp FROM account_data WHERE id='" . (int) $id . "'");
            if (mysqli_num_rows($result) == 0)
                return 0;

            $row = mysqli_fetch_assoc($result);
            return $row['vp'];
        }

        public function getDP($id)
        {
            $server = new server;
            $server->selectDB('webdb');

            $result = mysqli_query($conn, "SELECT dp FROM account_data WHERE id='" . (int) $id . "'");
            if (mysqli_num_rows($result) == 0)
                return 0;

            $row = mysqli_fetch_assoc($result);
            return $row['dp'];
        }

        public function getBan($id)
        {
            $server = new server;
            $server->selectDB('logondb');

            $result = mysqli_query($conn, "SELECT * FROM account_banned WHERE id='" . (int) $id . "' AND active = 1 ORDER by bandate DESC LIMIT 1");
            if (mysqli_num_rows($result) == 0)
                return "<span class='green_text'>Active</span>";

            $row  = mysqli_fetch_assoc($result);
            if ($row['unbandate'] < $row['bandate'])
                $time = "Never";
            else
                $time = date("Y-m-d H:i", $row['unbandate']);

            return
                    "<font size='-4'><b class='red_text'>Banned</b><br/>
        Unban date: <b>" . $time . "</b><br/>
        Banned by: <b>" . $row['bannedby'] . "</b><br/>
        Reason: <b>" . $row['banreason'] . "</b></font>
        ";
        }

        private function downloadFile($url, $path)
        {
            /* Not used! */
            $newfname = $path;
            $file     = fopen($url, "rb");
            if ($file)
            {
                $newf = fopen($newfname, "wb");

                if ($newf)
                {
                    while (!feof($file))
                    {
                        fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                    }
                }
            }

            if ($file)
                fclose($file);

            if ($newf)
                fclose($newf);
        }

    }

    class GamePage
    {

        public function validateSubPage()
        {
            if (isset($_GET['s']) && !empty($_GET['s']))
                return TRUE;
            else
                return FALSE;
        }

        public function validatePageAccess($page)
        {
            if (isset($_SESSION['cw_staff']) && !isset($_SESSION['cw_admin']))
            {
                if ($GLOBALS['staffPanel_permissions'][$page] != true)
                {
                    header("Location: ?p=notice&e=<h2>Not authorized!</h2>
                    You are not allowed to view this page!");
                }
            }
        }

        public function outputSubPage($panel)
        {
            $page    = $_GET['p'];
            $subpage = $_GET['s'];
            $pages   = scandir('../aasp_includes/pages/subpages');
            unset($pages[0], $pages[1]);

            if (!file_exists('../aasp_includes/pages/subpages/' . $page . '-' . $subpage . '.php'))
                include('../aasp_includes/pages/404.php');
            elseif (in_array($page . '-' . $subpage . '.php', $pages))
                include('../aasp_includes/pages/subpages/' . $page . '-' . $subpage . '.php');
            else
                include('../aasp_includes/pages/404.php');
        }

        public function titleLink()
        {
            return '<a href="?p=' . $_GET['p'] . '" title="Back to ' . ucfirst($_GET['p']) . '">' . ucfirst($_GET['p']) . '</a>';
        }

        public function addSlideImage($upload, $path, $url)
        {
            global $conn;
            $path = mysqli_real_escape_string($conn, $path);
            $url  = mysqli_real_escape_string($conn, $url);

            if (empty($path))
            {
                //No path set, upload image.
                if ($upload['error'] > 0)
                {
                    echo "<span class='red_text'><b>Error:</b> File uploading was not successfull!</span>";
                    $abort = true;
                }
                else
                {
                    if ((($upload["type"] == "image/gif") || ($upload["type"] == "image/jpeg") || ($upload["type"] == "image/pjpeg") || ($upload["type"] == "image/png")))
                    {
                        if (file_exists("../styles/global/slideshow/images/" . $upload["name"]))
                        {
                            unlink("../styles/global/slideshow/images/" . $upload["name"]);
                            move_uploaded_file($upload["tmp_name"], "../styles/global/slideshow/images/" . $upload["name"]);
                            $path = "../styles/global/slideshow/images/" . $upload["name"];
                        }
                        else
                        {
                            move_uploaded_file($upload["tmp_name"], "../styles/global/slideshow/images/" . $upload["name"]);
                            $path = "styles/global/slideshow/images/" . $upload["name"];
                        }
                    }
                    else
                        $abort = true;
                }
            }
            else
                $path = $path;

            if (!isset($abort))
            {
                $server = new server;
                $server->selectDB('webdb');
                mysqli_query($conn, "INSERT INTO slider_images VALUES('','" . $path . "','" . $url . "')");
            }
        }

    }

    class GameCharacter
    {

        public static function getRace($value)
        {
            switch ($value)
            {
                default:
                    return "Unknown";
                    break;
                #######
                case(1):
                    return "Human";
                    break;
                #######      
                case(2):
                    return "Orc";
                    break;
                #######
                case(3):
                    return "Dwarf";
                    break;
                #######
                case(4):
                    return "Night Elf";
                    break;
                #######
                case(5):
                    return "Undead";
                    break;
                #######
                case(6):
                    return "Tauren";
                    break;
                #######
                case(7):
                    return "Gnome";
                    break;
                #######
                case(8):
                    return "Troll";
                    break;
                #######
                case(9):
                    return "Goblin";
                    break;
                #######
                case(10):
                    return "Blood Elf";
                    break;
                #######
                case(11):
                    return "Dranei";
                    break;
                #######
                case(22):
                    return "Worgen";
                    break;
                #######
            }
        }

        public static function getGender($value)
        {
            if ($value == 1)
                return "Female";
            elseif ($value == 0)
                return "Male";
            else
                return "Unknown";
        }

        public static function getClass($value)
        {
            switch ($value)
            {
                default:
                    return "Unknown";
                    break;
                #######
                case(1):
                    return "Warrior";
                    break;
                #######
                case(2):
                    return "Paladin";
                    break;
                #######
                case(3):
                    return "Hunter";
                    break;
                #######
                case(4):
                    return "Rogue";
                    break;
                #######
                case(5):
                    return "Priest";
                    break;
                #######
                case(6):
                    return "Death Knight";
                    break;
                #######
                case(7):
                    return "Shaman";
                    break;
                #######
                case(8):
                    return "Mage";
                    break;
                #######
                case(9):
                    return "Warlock";
                    break;
                #######
                case(11):
                    return "Druid";
                    break;
                ####### 
                #######
                case(12):
                    return "Monk";
                    break;
                ####### 
            }
        }

    }

    $GameServer    = new GameServer();
    $GameAccount   = new GameAccount();
    $GamePage      = new GamePage();
    $GameCharacter = new GameCharacter();

    function activeMenu($p)
    {
        if (isset($_GET['p']) && $_GET['p'] == $p)
            echo "style='display:block;'";
    }

    function limit_characters($str, $n)
    {
        $str = preg_replace("/<img[^>]+\>/i", "(image) ", $str);
        if (strlen($str) <= $n)
            return $str;
        else
            return substr($str, 0, $n) . '';
    }

    function stripBBCode($text_to_search)
    {
        $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
        $replace = '';
        return preg_replace($pattern, $replace, $text_to_search);
    }
    