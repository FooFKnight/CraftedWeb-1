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
?>
<?php require('includes/loader.php'); 
    global $GameServer;
    $conn = $GameServer->connect();
?>
<!DOCTYPE>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $GLOBALS['website_title']; ?> Admin Panel</title>
        <link rel="stylesheet" href="../aasp_includes/styles/default/style.css" />
        <link rel="stylesheet" href="../aasp_includes/styles/wysiwyg.css" />
        <script type="text/javascript" src="../javascript/jquery.js"></script>
    </head>

    <body>
        <div id="overlay"></div>
        <div id="loading"><img src="../aasp_includes/styles/default/images/ajax-loader.gif" /></div>
        <div id="leftcontent">
            <div id="menu_left">
                <ul>
                    <li id="menu_head">Menu</li>
                    <li>Dashboard</li>
                    <ul class="hidden" <?php activeMenu('dashboard'); ?>>
                        <a href="?p=dashboard">Dashboard</a>
                        <!--<a href="?p=updates">Updates</a>-->
                    </ul>
                    <li>Pages</li>
                    <ul class="hidden" <?php activeMenu('pages'); ?>>
                        <a href="?p=pages">All Pages</a>
                        <a href="?p=pages&s=new">Add New</a>
                    </ul>
                    <li>News</li>
                    <ul class="hidden" <?php activeMenu('news'); ?>>
                        <a href="?p=news">Post news</a>
                        <a href="?p=news&s=manage">Manage news</a>
                    </ul>     
                    <li>Shop</li>
                    <ul class="hidden" <?php activeMenu('shop'); ?>>
                        <a href="?p=shop">Overview</a>
                        <a href="?p=shop&s=add">Add items</a>
                        <a href="?p=shop&s=manage">Manage items</a>
                        <a href="?p=shop&s=tools">Tools</a>
                    </ul> 
                    <li>Donations</li>
                    <ul class="hidden" <?php activeMenu('donations'); ?>>
                        <a href="?p=donations">Overview</a>
                        <a href="?p=donations&s=browse">Browse</a>
                    </ul> 
                    <li>Logs</li>
                    <ul class="hidden" <?php activeMenu('logs'); ?>>
                        <a href="?p=logs&s=voteshop">Vote shop</a>
                        <a href="?p=logs&s=donateshop">Donation shop</a>
                        <a href="?p=logs&s=admin">Admin Panel</a>
                    </ul> 
                    <li>Interface</li>
                    <ul class="hidden" <?php activeMenu('interface'); ?>>
                        <a href="?p=interface">Template</a>
                        <a href="?p=interface&s=menu">Menu</a>
                        <a href="?p=interface&s=slideshow">Slideshow</a>
                        <a href="?p=interface&s=plugins">Plugins</a>
                    </ul> 
                    <li>Users</li>
                    <ul class="hidden" <?php activeMenu('users'); ?>>
                        <a href="?p=users">Overview</a>
                        <a href="?p=users&s=manage">Manage Users</a>
                    </ul> 
                    <li>Realms</li>
                    <ul class="hidden" <?php activeMenu('realms'); ?>>
                        <a href="?p=realms">New realm</a>
                        <a href="?p=realms&s=manage">Manage realm(s)</a>
                    </ul> 
                    <li>Services</li>
                    <ul class="hidden" <?php activeMenu('services'); ?>>
                        <a href="?p=services&s=voting">Voting Links</a>
                        <a href="?p=services&s=charservice">Character Services</a>
                    </ul> 
                    <li>Tools</li>
                    <ul class="hidden" <?php activeMenu('tools'); ?>>
                        <a href="?p=tools&s=tickets">Tickets</a>
                        <a href="?p=tools&s=accountaccess">Account Access</a>
                    </ul>      
                </ul>
            </div>
        </div>

        <div id="header">
            <div id="header_text">
                <?php if (isset($_SESSION['cw_admin']))
                    {
                        ?> Welcome  
                        <b><?php echo $_SESSION['cw_admin']; ?> </b> 
                        <a href="?p=logout"><i>(Log out)</i></a> &nbsp; | &nbsp;
                        <a href="../">Back to the website</a>
                        <?php
                    }
                    else
                    {
                        echo "<a href='../'>Back to your website</a> | Please log in.";
                    }
                ?>
            </div>
        </div>


        <div id="wrapper">
            <div id="middlecontent">
                <?php if (!isset($_SESSION['cw_admin']))
                    {
                        ?>  
                        <br/>
                        <center>
                            <h2>Please log in</h2>
                            <input type="text" placeholder="Username" id="login_username" style="border: 1px solid #ccc;"/><br/> 
                            <input type="password" placeholder="Password" id="login_password" style="border: 1px solid #ccc;"/><br/>
                            <input type="submit" value="Log in" onclick="login('admin')"/> <br/>
                            <div id="login_status"></div>
                        </center>
                        <?php
                    }
                    else
                    {
                        ?>
                        <div class="box_right">
                            <?php
                            if (!isset($_GET['p']))
                                $page = "dashboard";
                            else
                            {
                                $page = $_GET['p'];
                            }
                            $pages = scandir('../aasp_includes/pages');
                            unset($pages[0], $pages[1]);

                            if (!file_exists('../aasp_includes/pages/' . $page . '.php'))
                            {
                                include('../aasp_includes/pages/404.php');
                            }
                            elseif (in_array($page . '.php', $pages))
                            {
                                include('../aasp_includes/pages/' . $page . '.php');
                            }
                            else
                            {
                                include('../aasp_includes/pages/404.php');
                            }
                        }
                    ?>
            <?php if ($GLOBALS['forum']['type'] == 'phpbb' && $GLOBALS['forum']['autoAccountCreate'] == TRUE && $page == 'dashboard')
    {
        ?>
                            <div class="box_right">
                                <div class="box_right_title">Recent forum activity</div>
                                <table width="100%">
                                    <tr>
                                        <th>Account</th>
                                        <th>Topic</th>
                                        <th>Message</th>
                                        <th>Topic</th>
                                    </tr>
                                    <?php
                                    $GameServer->selectDB($GLOBALS['forum']['forum_db']);
                                    $result = mysqli_query($conn, "SELECT poster_id,post_text,post_time,topic_id FROM phpbb_posts ORDER BY post_id DESC LIMIT 10");
                                    while ($row    = mysqli_fetch_assoc($result))
                                    {
                                        $string   = $row['post_text'];
                                        //Lets get the username     
                                        $getUser  = mysqli_query($conn, "SELECT username FROM phpbb_users WHERE user_id='" . $row['poster_id'] . "'");
                                        $user     = mysqli_fetch_assoc($getUser);
                                        //Get topic
                                        $getTopic = mysqli_query($conn, "SELECT topic_title FROM phpbb_topics WHERE topic_id='" . $row['topic_id'] . "'");
                                        $topic    = mysqli_fetch_assoc($getTopic);
                                        ?>
                                        <tr class="center">
                                            <td><a href="http://heroic-wow.net/forum/memberlist.php?mode=viewprofile&u=<?php echo $row['poster_id']; ?>" title="View profile" 
                                                   target="_blank"><?php echo $user['username']; ?></a></td>
                                            <td><?php echo $topic['topic_title']; ?></td>
                                            <td><?php echo limit_characters(strip_tags($string), 75); ?>...</td>
                                            <td><a href="<?php echo $GLOBALS['website_domain'] . substr($GLOBALS['forum']['forum_path'], 1); ?>viewtopic.php?t=<?php echo $row['topic_id'] ?>" 
                                                   title="View this topic" target="_blank">
                                                    View topic</a></td>
                                        </tr>
                            <?php } ?>
                                </table>
                            </div> 
                <?php } ?>
                </div>

            </div>
            <?php if (isset($_SESSION['cw_admin']))
            {?>
                <div id="rightcontent">
                    <div class="box_right">
                        <div class="box_right_title">Server Status</div>
                        <?php $GameServer->serverStatus(); ?>
                    </div>    

                    <div class="box_right">
                        <div class="box_right_title">Website Configuration</div>
                        <table>
                            <tr valign="top">
                                <td>

                                    <tr>
                                        <td>MySQL Host: </td>
                                        <td>MySQL User: </td>
                                        <td>MySQL Password: </td>
                                    </tr>

                                </td>
                                <td>

                                    <tr style="font-weight: bold;">
                                        <td><?php echo $GLOBALS['connection']['host']; ?></td>
                                        <td><?php echo $GLOBALS['connection']['user']; ?></td>
                                        <td><?php echo substr($GLOBALS['connection']['password'], 0, 4); ?>****<br/></td>
                                    </tr>

                                </td>
                                <td>

                                    <tr>
                                        <td>Logon Database: </td>
                                        <td>Website Database: </td>
                                        <td>World Database: </td>
                                        <td>Database Revision: </td>
                                    </tr>

                                </td>
                                <td>
                                    <tr style="font-weight: bold;">
                                        
                                        <td><?php echo $GLOBALS['connection']['logondb']; ?></td>
                                        <td><?php echo $GLOBALS['connection']['webdb']; ?></td>
                                        <td><?php echo $GLOBALS['connection']['worlddb']; ?></td>
                                        <td>
                                            <?php
                                                $GameServer->selectDB('webdb', $conn);
                                                $get = mysqli_query($conn, "SELECT version FROM db_version;");
                                                $row = mysqli_fetch_assoc($get);
                                                echo $row['version'];
                                            ?>
                                        </td>

                                    </tr>
                                </td>
                            </tr>
                        </table>
                    </div>          
                </div>         
        <?php } ?>
        </div>               
    </div> 
    <?php
        include("../aasp_includes/javascript_loader.php");
        if (!isset($_SESSION['cw_admin']))
        {
            ?>
            <script type="text/javascript">
                document.onkeydown = function (event)
                {
                    var key_press = String.fromCharCode(event.keyCode);
                    var key_code = event.keyCode;
                    if (key_code == 13)
                    {
                        login('admin')
                    }
                }
            </script>
    <?php } ?>
</body>
</html>