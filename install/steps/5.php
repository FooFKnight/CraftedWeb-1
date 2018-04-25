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
<p id="steps"><b>Introduction</b> &raquo; MySQL Info &raquo; Configure &raquo; Database &raquo; <b>Realm Info</b> &raquo; Finished<p>
<hr/>
<table cellpadding="10" cellspacing="5">
    <tr>
        <td>Realm ID:</td>
        <td><input type="text" placeholder="Default: 1" id="addrealm_id"></td>

        <td>Realm Name:</td>
        <td><input type="text" placeholder="Default: Sample Realm" id="addrealm_name"></td>

        <td>MySQL Host:</td>
        <td><input type="text" placeholder="Default: 127.0.0.1" id="addrealm_m_host"></td>
    </tr>
    <tr>   
        <td>Description (Not needed):</td>
        <td><input type="text" placeholder="Default: Blizzlike 1x" id="addrealm_desc"></td>

        <td>Host:</td>
        <td><input type="text" placeholder="Default: 127.0.0.1" id="addrealm_host"></td>

        <td>MySQL Username:</td>
        <td><input type="text" placeholder="Default: root" id="addrealm_m_user"></td>
    </tr>
    <tr>   
        <td>Port:</td>
        <td><input type="text" placeholder="Default: 8085" id="addrealm_port"></td> 

        <td>Character Database:</td>
        <td><input type="text" placeholder="Default: characters" id="addrealm_chardb"></td>

        <td>MySQL Password:</td>
        <td><input type="text" placeholder="Default: ascent" id="addrealm_m_pass"></td>
    </tr>
    <tr>    
        <td>Authorized Account username:</td>
        <td><input type="text" placeholder="Default: admin" id="addrealm_a_user"></td> 

        <td>Authorized Account password:</td>
        <td><input type="text" placeholder="Default: adminpass" id="addrealm_a_pass"></td>        
    </tr>
    <tr>
        <td>Remote Console:</td>
        <td>
            <select id="addrealm_sendtype">
                <option value="ra">RA</option>
                <option value="soap">SOAP</option>
            </select>
        </td>

        <td>RA Port (Ignore if you've chosen SOAP):</td>
        <td><input type="text" placeholder="Default: 3443" id="addrealm_raport"></td>

        <td>SOAP Port (Ignore if you've chosen RA):</td>
        <td><input type="text" placeholder="Default: 7878" id="addrealm_soapport"></td>

    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Finished" onclick="step5()"></td>
    </tr>
</table>