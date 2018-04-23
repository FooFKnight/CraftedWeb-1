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

########################
## Scripts containing website functions will be added here. News for example.
#######################

    class Website
    {

        public static function getNews()
        {
            global $Cache, $Connect, $conn, $Website;
            if ($GLOBALS['news']['enable'] == true)
            {
                echo '<div class="box_two_title">Latest News</div>';

                if ($Cache->exists('news') == true)
                {
                    $Cache->loadCache('news');
                }
                else
                {
                    $Connect->selectDB('webdb', $conn);

                    $result = mysqli_query($conn, "SELECT * FROM news ORDER BY id DESC LIMIT " . $GLOBALS['news']['maxShown']);
                    if (mysqli_num_rows($result) == 0)
                    {
                        echo 'No news was found';
                    }
                    else
                    {
                        $output = null;
                        while ($row    = mysqli_fetch_assoc($result))
                        {
                            if (file_exists($row['image']))
                            {
                                echo $newsPT1 = '
						       <table class="news" width="100%"> 
							        <tr>
									    <td><h3 class="yellow_text">' . $row['title'] . '</h3></td>
								    </tr>
							   </table>
	                           <table class="news_content" cellpadding="4"> 
							       <tr>
							          <td><img src="' . $row['image'] . '" alt=""/></td> 
							          <td>';
                            }
                            else
                            {
                                echo $newsPT1 = '
						       <table class="news" width="100%"> 
							        <tr>
									    <td><h3 class="yellow_text">' . $row['title'] . '</h3></td>
								    </tr>
							   </table>
	                           <table class="news_content" cellpadding="4"> 
							       <tr>
							           <td>';
                            }
                            $output .= $newsPT1;
                            unset($newsPT1);

                            $text = preg_replace("
					  		#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", "'<a href=\"$1\" target=\"_blank\">http://$3</a>$4'", $row['body']
                            );

                            if ($GLOBALS['news']['limitHomeCharacters'] == true)
                            {
                                echo $Website->limit_characters($text, 200);
                                $output .= $Website->limit_characters($row['body'], 200);
                            }
                            else
                            {
                                echo nl2br($text);
                                $output .= nl2br($row['body']);
                            }
                            $result      = mysqli_query($conn, "SELECT COUNT(id) FROM news_comments WHERE newsid='" . $row['id'] . "'");
                            $commentsNum = mysqli_fetch_row($result);
                            if ($GLOBALS['news']['enableComments'] == true)
                            {
                                $comments = '| <a href="?p=news&amp;newsid=' . $row['id'] . '">Comments (' . $commentsNum[0] . ')</a>';
                            }
                            else
                            {
                                $comments = '';
                            }

                            echo $newsPT2 = '
						<br/><br/><br/>
						<i class="gray_text"> Written by ' . $row['author'] . ' | ' . $row['date'] . ' ' . $comments . '</i>
						</td> 
						</tr>
					    </table>';
                            $output  .= $newsPT2;
                            unset($newsPT2);
                        }
                        echo '<hr/><a href="?p=news">View older news...</a>';
                        $Cache->buildCache('news', $output);
                    }
                }
            }
        }

        public static function getSlideShowImages()
        {
            global $Cache, $Connect, $conn;
            if ($Cache->exists('slideshow') == true)
            {
                $Cache->loadCache('slideshow');
            }
            else
            {
                $Connect->selectDB('webdb', $conn);
                $result = mysqli_query($conn, "SELECT path, link FROM slider_images ORDER BY position ASC");
                while ($row    = mysqli_fetch_assoc($result))
                {
                    echo $outPutPT = '<a href="' . $row['link'] . '">
								  <img border="none" src="' . $row['path'] . '" alt="" class="slideshow_image">
								  </a>';
                    $output   .= $outPutPT;
                }
                $Cache->buildCache('slideshow', $output);
            }
        }

        public static function getSlideShowImageNumbers()
        {
            global $Connect, $conn;
            $Connect->selectDB('webdb', $conn);
            $result = mysqli_query($conn, "SELECT position FROM slider_images ORDER BY position ASC");
            $x      = 1;
            while ($row    = mysqli_fetch_assoc($result))
            {
                echo '<a href="#" rel="' . $x . '">' . $x . '</a>';
                $x++;
            }
            unset($x);
        }

        public static function limit_characters($str, $n)
        {
            $str = preg_replace("/<img[^>]+\>/i", "(image)", $str);

            if (strlen($str) <= $n)
            {
                return $str;
            }
            else
            {
                return substr($str, 0, $n) . '...';
            }
        }

        public static function loadVotingLinks()
        {
            global $Connect, $conn, $Account, $Website;
            $Connect->selectDB('webdb', $conn);
            $result = mysqli_query($conn, "SELECT * FROM votingsites ORDER BY id DESC");
            if (mysqli_num_rows($result) == 0)
                buildError("Couldnt fetch any voting links from the database. " . mysqli_error($conn));
            else
            {
                while ($row = mysqli_fetch_assoc($result))
                {
                    ?>
                    <div class='votelink'>
                        <table width="100%">
                            <tr>
                                <td width="20%"><img src="<?php echo $row['image']; ?>" /></td>
                                <td width="50%"><strong><?php echo $row['title']; ?></strong> (<?php echo $row['points']; ?> Vote Points)<td>
                                <td width="40%">
                                    <?php
                                    if ($Website->checkIfVoted($row['id']) == false)
                                    {
                                        ?> 
                                        <input type='submit' value='Vote'  onclick="vote('<?php echo $row['id']; ?>', this)">
                                        <?php
                                    }
                                    else
                                    {
                                        $getNext = mysqli_query($conn, "SELECT next_vote FROM " . $GLOBALS['connection']['webdb'] . ".votelog 
														 WHERE userid='" . $Account->getAccountID($_SESSION['cw_user']) . "' 
														 AND siteid='" . $row['id'] . "' ORDER BY id DESC LIMIT 1");

                                        $row  = mysqli_fetch_assoc($getNext);
                                        $time = $row['next_vote'] - time();

                                        echo 'Time until reset: ' . convTime($time);
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
            }
        }

        public static function checkIfVoted($siteid)
        {
            global $Account, $Connect, $conn;
            $siteid  = (int) $siteid;
            $acct_id = $Account->getAccountID($_SESSION['cw_user']);

            $Connect->selectDB('webdb', $conn);

            $result = mysqli_query($conn, "SELECT COUNT(id) FROM votelog WHERE userid='" . $acct_id . "' AND siteid='" . $siteid . "' AND next_vote > " . time());

            if (mysqli_data_seek($result, 0) == 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        public static function sendEmail($to, $from, $subject, $body)
        {
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . $from . "\r\n";

            mail($to, $subject, $body, $headers);
        }

        public static function convertCurrency($currency)
        {
            if ($currency == 'dp')
            {
                return $GLOBALS['donation']['coins_name'];
            }
            elseif ($currency == 'vp')
            {
                return "Vote Points";
            }
        }

    }

    $Website = new Website();
    