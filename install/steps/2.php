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
<p id="steps">Introduction &raquo; MySQL Info &raquo; <b>Configure</b> &raquo; Database &raquo; Realm Info &raquo; Finished<p>
<hr/>
<p>Now we need to test if we can write the configuration file & read the SQL file. Before we test this, make sure that:
<ul>
    <li>The CHMOD is set to 777 on both <i>'includes/configuration.php'</i> AND <i>'install/sql/CraftedWeb_Base.sql'</i> (You <b>must</b> change this back to 644 after the installation proccess has finished!)</li>
    <li>The file exists (We are not creating a new file, we're just writing onto a blank one. If the file (includes/configuration.php) does not exist, create it. You can use notepad or any other similar software, just remember to save it as <i>configuration.php</i>, NOT .TXT!</li>
</ul>
</p>
<p>
    <br/>
    <input type="submit" value="Test if file is writeable" onclick="step2()">
</p>