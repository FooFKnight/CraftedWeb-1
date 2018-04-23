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

    global $Page, $Server, $Account, $Character, $conn; ?>
<div class="box_right_title"><?php echo $Page->titleLink(); ?> &raquo; Manage Character</div>
Selected character:  <?php echo $Account->getCharName($_GET['guid'], $_GET['rid']); ?>
<?php
    $Server->connectToRealmDB($_GET['rid']);

    $usersTotal = mysqli_query($conn, "SELECT name,race,account,class,level,money,leveltime,totaltime,online,latency,gender FROM characters WHERE guid='" . $_GET['guid'] . "'");
    $row        = mysqli_fetch_assoc($usersTotal);
?>
<hr/>
<table style="width: 100%;">
    <tr>
        <td>Character Name</td>
        <td><input type="text" value="<?php echo $row['name']; ?>" class="noremove" id="editchar_name"/></td>
    </tr>
    <tr>
        <td>Account</td>
        <td><input type="text" value="<?php echo $Account->getAccName($row['account']); ?>" class="noremove" id="editchar_accname"/>
            <a href="?p=users&s=manage&user=<?php echo strtolower($Account->getAccName($row['account'])); ?>">View</a></td>
    </tr>
    <tr>
        <td>Race</td>
        <td>
            <select id="editchar_race">
                <option <?php if ($row['race'] == 1) echo 'selected'; ?> value="1">Human</option>
                <option <?php if ($row['race'] == 3) echo 'selected'; ?> value="3">Dwarf</option>
                <option <?php if ($row['race'] == 4) echo 'selected'; ?> value="4">Night Elf</option>
                <option <?php if ($row['race'] == 7) echo 'selected'; ?> value="7">Gnome</option>
                <option <?php if ($row['race'] == 11) echo 'selected'; ?> value="11">Dranei</option>
                <?php if ($GLOBALS['core_expansion'] >= 3)  ?>
                <option <?php if ($row['race'] == 22) echo 'selected'; ?> value="22">Worgen</option>
                <option <?php if ($row['race'] == 2) echo 'selected'; ?> value="2">Orc</option>
                <option <?php if ($row['race'] == 6) echo 'selected'; ?> value="6">Tauren</option>
                <option <?php if ($row['race'] == 8) echo 'selected'; ?> value="8">Troll</option>
                <option <?php if ($row['race'] == 5) echo 'selected'; ?> value="5">Undead</option>
                <option <?php if ($row['race'] == 10) echo 'selected'; ?> value="10">Blood Elf</option>
                <?php if ($GLOBALS['core_expansion'] >= 3)  ?>
                <option <?php if ($row['race'] == 9) echo 'selected'; ?> value="9">Goblin</option>
                <?php if ($GLOBALS['core_expansion'] >= 4)  ?>
                <option <?php if ($row['race'] == NULL) echo 'selected'; ?> value="NULL">Pandaren</option>
            </select>
        </td>
    </tr>
    <tr>   
        <td>Class</td>
        <td>
            <select id="editchar_class">
                <option <?php if ($row['class'] == 1) echo 'selected'; ?> value="1">Warrior</option>
                <option <?php if ($row['class'] == 2) echo 'selected'; ?> value="2">Paladin</option>
                <option <?php if ($row['class'] == 11) echo 'selected'; ?> value="11">Druid</option>
                <option <?php if ($row['class'] == 3) echo 'selected'; ?> value="3">Hunter</option>
                <option <?php if ($row['class'] == 5) echo 'selected'; ?> value="5">Priest</option>
                <?php if ($GLOBALS['core_expansion'] >= 2)  ?>
                <option <?php if ($row['class'] == 6) echo 'selected'; ?> value="6">Death Knight</option>
                <option <?php if ($row['class'] == 9) echo 'selected'; ?> value="9">Warlock</option>
                <option <?php if ($row['class'] == 7) echo 'selected'; ?> value="7">Shaman</option>
                <option <?php if ($row['class'] == 4) echo 'selected'; ?> value="4">Rogue</option>
                <option <?php if ($row['class'] == 8) echo 'selected'; ?> value="8">Mage</option>
                <?php if ($GLOBALS['core_expansion'] >= 4)  ?>
                <option <?php if ($row['class'] == 12) echo 'selected'; ?> value="12">Monk</option>
                <?php if ($GLOBALS['core_expansion'] >= 5)  ?>
                <option <?php if ($row['class'] == 13) echo 'selected'; ?> value="13">Demon Hunter</option>
            </select>
        </td>
    </tr>
    <tr>   
        <td>Gender</td>
        <td>
            <select id="editchar_gender">
                <option <?php if ($row['gender'] == 0) echo 'selected'; ?> value="0">Male</option>
                <option <?php if ($row['gender'] == 1) echo 'selected'; ?> value="1">Female</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Level</td>
        <td><input type="text" value="<?php echo $row['level']; ?>" class="noremove" id="editchar_level"/></td>
    </tr>
    <tr>    
        <td>Money (Gold)</td>
        <td><input type="text" value="<?php echo floor($row['money'] / 10000); ?>" class="noremove" id="editchar_money"/></td>
    </tr>
    <tr>
        <td>Leveling Time</td>
        <td><input type="text" value="<?php echo $row['leveltime']; ?>" disabled="disabled"/></td>
    </tr>
    <tr>    
        <td>Total Time</td>
        <td><input type="text" value="<?php echo $row['totaltime']; ?>" disabled="disabled"/></td>
    </tr>
    <tr>
        <td>Status</td>
        <td>
            <?php
                if ($row['online'] == 0)
                {
                    echo '<input type="text" value="Offline" disabled="disabled"/>';
                }
                else
                {
                    echo '<input type="text" value="Online" disabled="disabled"/>';
                }
            ?>              
        </td>
    </tr>
    <tr>    
        <td>Latency</td>
        <td><input type="text" value="<?php echo $row['latency']; ?>" disabled="disabled"/></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Save" onclick="editChar('<?php echo $_GET['guid']; ?>', '<?php echo $_GET['rid']; ?>')"/> 
            <i>* Note</i>: You may not edit any data if the character is online.</td>
    </tr>
</table>
<hr/>