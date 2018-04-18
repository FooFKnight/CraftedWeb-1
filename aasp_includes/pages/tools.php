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
?>
<?php
    global $GameServer, $GamePage;
    $GameServer->selectDB('webdb', $conn);

    if ($GamePage->validateSubPage() == TRUE)
    {
        $GamePage->outputSubPage();
    }
    else
    {
        ?>
        <div class='box_right_title'>Hey! You shouldn't be here!</div>

        <pre>The script might have redirected you wrong. Or... did you try to HACK!? Anyways, good luck.</pre>

        <a href="?p=tools&s=tickets" class="content_hider">Tickets</a>
        <a href="?p=tools&s=accountaccess" class="content_hider">Account Access</a>
        <?php
    }
?>