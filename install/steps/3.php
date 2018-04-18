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
<p id="steps">Introduction &raquo; MySQL Info &raquo; Configure &raquo; <b>Database</b> &raquo; Realm Info &raquo; Finished<p>
<hr/>
<p>
    Now is the time to actually create something. The script will now: 
<ul>
    <li>Create the Website Database if it does not exist</li>
    <li>Create all tables in the Website Database</li>
    <li>Insert default data into the Website Database</li>
    <li>Write the configuration file</li>
</ul>

To prevent any database errors, please make sure that the MySQL user your specified has access to the following commands:
<ul>
    <li>INSERT</li>
    <li>INSERT IGNORE</li>
    <li>UPDATE</li>
    <li>ALTER</li>
    <li>DELETE</li>
    <li>DROP</li>
    <li>CREATE</li>
</ul>
You may remove some of these after the installation proccess has finished as they are not needed when running the CMS.
</p>
<p>
    <br/>
    <input type="submit" value="Start the proccess!" onclick="step3()">
</p>