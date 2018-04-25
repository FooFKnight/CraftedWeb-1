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
    global $GameServer, $GamePage;
    $conn = $GameServer->connect();

    $GameServer->selectDB('webdb', $conn);

    $GamePage->validatePageAccess('Pages');

    if ($GamePage->validateSubPage() == TRUE)
    {
        $GamePage->outputSubPage();
    }
    else
    {

        echo "<div class='box_right_title'>Pages</div>";

        if (!isset($_GET['action']))
        {

            ?><table class='center'>
                <tr>
                    <th>Name</th>
                    <th>File name</th>
                    <th>Actions</th>
                </tr>

        <?php 
            $result = mysqli_query($conn, "SELECT * FROM custom_pages ORDER BY id ASC;");
            while ($row    = mysqli_fetch_assoc($result))
            {
                $disabled = true;
                $check = mysqli_query($conn, "SELECT COUNT(filename) FROM disabled_pages WHERE filename='" . $row['filename'] . "';");
                if (mysqli_data_seek($check, 0) == 0)
                {
                    $disabled = false;
                }
                ?>
                <tr <?php
                if ($disabled)
                {
                    echo "style='color: #999;'";
                }
                ?>>
                    <td width="50"><?php echo $row['name']; ?></td>
                    <td width="100"><?php echo $row['filename']; ?>(Database)</td>
                    <td><select id="action-<?php echo $row['filename']; ?>"><?php
                            if ($disabled == true)
                            {
                                ?>
                                <option value="1">Enable</option>
                                <?php
                            }
                            else
                            {
                                ?>
                                <option value="2">Disable</option>
                        <?php } ?>
                            <option value="3">Edit</option>
                            <option value="4">Remove</option>
                        </select> &nbsp;<input type="submit" value="Save" onclick="savePage('<?php echo $row['filename']; ?>')"></td>
                </tr>
                <?php
            }

            if (is_array($GLOBALS['core_pages']) || is_object($GLOBALS['core_pages']))
            {
                foreach ($GLOBALS['core_pages'] as $k => $v)
                {
                    $filename = substr($v, 0, -4);
                    unset($check);
                    $disabled = true;
                    $check    = mysqli_query($conn, "SELECT COUNT(filename) FROM disabled_pages WHERE filename='" . $filename . "';");
                    if (mysqli_data_seek($check, 0) == 0)
                    {
                        $disabled = false;
                    }
                    ?>

                    <tr <?php
                    if ($disabled)
                    {
                        echo "style='color: #999;'";
                    }
                    ?>>
                        <td><?php echo $k; ?></td>
                        <td><?php echo $v; ?></td>
                        <td><select id="action-<?php echo $filename; ?>">
                                <?php
                                if ($disabled)
                                {
                                    ?>
                                    <option value="1">Enable</option>
                                    <?php
                                }
                                else
                                {?>
                                    <option value="2">Disable</option>
                    <?php } ?>
                            </select> &nbsp;<input type="submit" value="Save" onclick="savePage('<?php echo $filename; ?>')"></td>
                    </tr>
            <?php } ?>


            </table>

                <?php
            }
            elseif ($_GET['action'] == 'new')
            {
                
            }
            elseif ($_GET['action'] == 'edit')
            {

                if (isset($_POST['editpage']))
                {

                    $name     = mysqli_real_escape_string($conn, $_POST['editpage_name']);
                    $filename = mysqli_real_escape_string($conn, trim(strtolower($_POST['editpage_filename'])));
                    $content  = mysqli_real_escape_string($conn, htmlentities($_POST['editpage_content']));

                    if (empty($name) || empty($filename) || empty($content))
                    {
                        echo "<h3>Please enter <u>all</u> fields.</h3>";
                    }
                    else
                    {
                        mysqli_query($conn, "UPDATE custom_pages SET name='" . $name . "', filename='" . $filename . "', content='" . $content . "' WHERE filename='" . mysqli_real_escape_string($conn, $_GET['filename']) . "';");

                        echo "<h3>The page was successfully updated.</h3> <a href='" . $GLOBALS['website_domain'] . "?p=" . $filename . "' target='_blank'>View Page</a>";
                    }
                }

                $result = mysqli_query($conn, "SELECT * FROM custom_pages WHERE filename='". mysqli_real_escape_string($conn, $_GET['filename']) ."';");
                $row    = mysqli_fetch_assoc($result);
                ?>

                <h4>Editing <?php echo $_GET['filename']; ?>.php</h4>
                <form action="?p=pages&action=edit&filename=<?php echo $_GET['filename']; ?>" method="post">
                    Name<br/>
                    <input type="text" name="editpage_name" value="<?php echo $row['name']; ?>"><br/>
                    Filename<br/>
                    <input type="text" name="editpage_filename" value="<?php echo $row['filename']; ?>"><br/>
                    Content<br/>
                    <textarea cols="77" rows="14" id="wysiwyg" name="editpage_content"><?php echo $row['content']; ?></textarea>    
                    <br/>
                    <input type="submit" value="Save" name="editpage">
                </form>
                    <?php
            }
        }
    }