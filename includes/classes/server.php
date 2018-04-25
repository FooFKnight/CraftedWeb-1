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

    class Server
    {

        public function getRealmId($char_db)
        {
            global $Connect, $conn;
            $Connect->selectDB('webdb', $conn);
            $get = mysqli_query($conn, "SELECT id FROM realms WHERE char_db='" . mysqli_real_escape_string($conn, $char_db) . "'");
            $row = mysqli_fetch_assoc($get);
            return $row['id'];
        }

        public function getRealmName($char_db)
        {
            $Connect->selectDB('webdb', $conn);
            $get = mysqli_query($conn, "SELECT name FROM realms WHERE char_db='" . mysqli_real_escape_string($conn, $char_db) . "'");
            $row = mysqli_fetch_assoc($get);
            return $row['name'];
        }

        public static function serverStatus($realm_id)
        {

            global $Connect, $conn;
            //Get status
            $fp = fsockopen($GLOBALS['realms'][$realm_id]['host'], $GLOBALS['realms'][$realm_id]['port'], $errno, $errstr, 1);
            if (!$fp)
            {
                echo $status = '<h4 class="realm_status_title_offline">' . $GLOBALS['realms'][$realm_id]['name'] . ' -  Offline</h4>';
            }
            else
            {
                echo $status = '<h4 class="realm_status_title_online">' . $GLOBALS['realms'][$realm_id]['name'] . ' - Online</h4>';

                echo '<span class="realm_status_text">';

                /* Players online bar */
                if ($GLOBALS['serverStatus']['factionBar'] == TRUE)
                {
                    $Connect->connectToRealmDB($realm_id);
                    $getChars     = mysqli_query($conn, "SELECT COUNT(online) FROM characters WHERE online=1;");
                    $total_online = mysqli_data_seek($getChars, 0);

                    $getAlliance = mysqli_query($conn, "SELECT COUNT(online) FROM characters WHERE online=1 AND race IN('3','4','7','11','1','22');");
                    $alliance    = mysqli_data_seek($getAlliance, 0);

                    $getHorde = mysqli_query($conn, "SELECT COUNT(online) FROM characters WHERE online=1 AND race IN('2','5','6','8','10','9');");
                    $horde    = mysqli_data_seek($getHorde, 0);

                    if ($total_online == 0)
                    {
                        $per_alliance = 50;
                        $per_horde    = 50;
                    }
                    else
                    {
                        if ($alliance == 0)
                        {
                            $per_alliance = 0;
                        }
                        else
                        {
                            $per_alliance = round(($alliance / $total_online) * 100);
                        }

                        if ($horde == 0)
                        {
                            $per_horde = 0;
                        }
                        else
                        {
                            $per_horde = round(($horde / $total_online) * 100);
                        }
                    }

                    if ($per_alliance + $per_horde > 100)
                    {
                        $per_horde = $per_horde - 1;
                    }
                    ?>
                    <div class='srv_status_po'>
                        <div class='srv_status_po_alliance' style="width: <?php echo $per_alliance; ?>%;"></div>
                        <div class='srv_status_po_horde' style="width: <?php echo $per_horde; ?>%;"></div>
                        <div class='srv_status_text'>Alliance: <?php echo $alliance; ?> &nbsp;  Horde: <?php echo $horde; ?></div>
                    </div>
                    <?php
                }

                echo '<table width="100%"><tr>';
                //Get players online
                if ($GLOBALS['serverStatus']['playersOnline'] == TRUE)
                {
                    $Connect->connectToRealmDB($realm_id);
                    $getChars = mysqli_query($conn, "SELECT COUNT(online) FROM characters WHERE online=1");
                    $pOnline  = mysqli_data_seek($getChars, 0);
                    echo '<td>
						<b>', $pOnline, '</b> Players online
					  </td>';
                }

                //Get uptime
                if ($GLOBALS['serverStatus']['uptime'] == TRUE)
                {
                    $Connect->selectDB('logondb', $conn);
                    $getUp = mysqli_query($conn, "SELECT starttime FROM uptime WHERE realmid='" . $realm_id . "' ORDER BY starttime DESC LIMIT 1");
                    $row   = mysqli_fetch_assoc($getUp);

                    $time   = time();
                    $uptime = $time - $row['starttime'];

                    echo '
					<td>
						   <b>' . convTime($uptime) . '</b> uptime
					</td>
					</tr>';
                }
            }
            if ($GLOBALS['serverStatus']['nextArenaFlush'] == TRUE)
            {
                //Arena flush
                $Connect->connectToRealmDB($realm_id);
                $getFlush = mysqli_query($conn, "SELECT value FROM worldstates WHERE comment='NextArenaPointDistributionTime'");
                $row      = mysqli_fetch_assoc($getFlush);
                $flush    = date('d M H:i', $row['value']);

                echo '<tr>
			 	   <td>
				   	   Next arena flush: <b>' . $flush . '</b>
				   </td>';
            }
            echo '</tr>
		      </table>
			  </span>';
        }

    }

    $Server = new Server();
    