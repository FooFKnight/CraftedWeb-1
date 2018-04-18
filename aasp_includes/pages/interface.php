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

    global $Server, $Page, $conn;
    $Server->selectDB('webdb');
    $Page = new page;

    $Page->validatePageAccess('Interface');

    if ($Page->validateSubPage() == TRUE)
    {
        $Page->outputSubPage();
    }
    else
    {
        ?>
        <div class="box_right_title">Template</div>          

        Here you can choose which template that should be active on your website. This is also where you install new themes for your website.<br/><br/>
        <h3>Choose Template</h3>
        <select id="choose_template">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM template ORDER BY id ASC;");
            while ($row    = mysqli_fetch_assoc($result))
            {
                if ($row['applied'] == 1)
                {
                    echo "<option selected='selected' value='" . $row['id'] . "'>[Active] ";
                }
                else
                {
                    echo "<option value='" . $row['id'] . "'>";
                }

                echo $row['name'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Save" onclick="setTemplate()"/><hr/><p/>

        <h3>Install a new template</h3>
        <a href="#" onclick="templateInstallGuide()">How to install new templates on your website</a><br/><br/><br/>
        Path to the template<br/>
        <input type="text" id="installtemplate_path"/><br/>
        Choose a name<br/>
        <input type="text" id="installtemplate_name"/><br/>
        <input type="submit" value="Install" onclick="installTemplate()"/>
        <hr/>
        <p/>

        <h3>Uninstall a template</h3>
        <select id="uninstall_template_id">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM template ORDER BY id ASC;");
            while ($row    = mysqli_fetch_assoc($result))
            {
                if ($row['applied'] == 1)
                {
                    echo "<option selected='selected' value='" . $row['id'] . "'>[Active] ";
                }
                else
                {
                    echo "<option value='" . $row['id'] . "'>";
                }

                echo $row['name'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Uninstall" onclick="uninstallTemplate()"/> 
    <?php } ?>