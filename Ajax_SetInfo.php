<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * http://localhost:8080/Match_your_own_Tutors/checkUser.php?jsonDoc=%7B%0A%20%20%22phone%22%3A%2090091190%2C%0A%20%20%22password%22%3A%20123456%0A%7D
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//$link = mysqli_connect("localhost", "root", "", "p10");
$jsonDoc = '';
if (isset($_REQUEST['jsonDoc'])) {
    $jsonDoc = $_REQUEST['jsonDoc'];
}

$output['result'] = false;

if ($jsonDoc != '') {
    $obj = json_decode($jsonDoc, true);
    if ($obj) {
        @$passcode = $obj['Passcode'];
        @$Function = $obj['Function'];
        @$info = $obj['info'];
        @$info2 = $obj['info2'];
        @$info3 = $obj['info3'];
        if ($passcode == "SetIt") {
            include "DBConnect.php";
            //$output['whereiam']="here1";
            switch ($Function) {

                case "Set_Event":
                    $result = mysqli_query($link, "INSERT INTO `event` ( `Event_Connect`, `Event_Location`, `Event_Name`, `Event_Info`, `Event_Limit`, `Event_Cate`, `Event_Type`, `Start_Date`, `End_Date`, `Start_Time`, `End_Time`, `Repeat_Week`, `Create_By`)  SELECT MAX( ID ) + 1,'$info[Event_Location]','$info[Event_Name]','$info[Event_Info]', '$info[Event_Limit]', '$info[Event_Cate]', '$info[Event_Type]', '$info[Start_Date]', '$info[End_Date]', '$info[Start_Time]', '$info[End_Time]', '$info[Repeat_Week]','$info2' From `event`");
                    //$output['data']="INSERT INTO `event` ( `Event_Connect`, `Event_Location`, `Event_Name`, `Event_Info`, `Event_Limit`, `Event_Cate`, `Event_Type`, `Start_Date`, `End_Date`, `Start_Time`, `End_Time`, `Repeat_Week`, `Create_By`)  SELECT MAX( ID ) + 1,'$info[Event_Location]','$info[Event_Name]','$info[Event_Info]', '$info[Event_Limit]', '$info[Event_Cate]', '$info[Event_Type]', '$info[Start_Date]', '$info[End_Date]', '$info[Start_Time]', '$info[End_Time]', '$info[Repeat_Week]','$info2' From `event`";
                    $New_id = mysqli_insert_id($link);
                    if ($New_id > 0) {
                        $output['info'] = true;
                        $output['result'] = true;

                        if ($info['Event_Cate'] == 2) {
                            $result = mysqli_query($link, "SELECT * FROM `event_cate` WHERE `ID` = '$info[Event_Type]'");
                            $row = mysqli_fetch_assoc($result);
                            $smt = $row['list'];
                            $event_for = $row['Event_For'];
                            switch ($smt) {
                                case 1: //生日
                                    $temp = substr($info['Start_Date'], 5, 2);
                                    switch ($temp) {
                                        case '01':
                                        case '02':
                                        case '03':
                                            $day = array("01", "02", "03");
                                            break;
                                        case '04':
                                        case '05':
                                        case '06':
                                            $day = array("04", "05", "06");
                                            break;
                                        case '07':
                                        case '08':
                                        case '09':
                                            $day = array("07", "08", "09");
                                            break;
                                        case '10':
                                        case '11':
                                        case '12':
                                            $day = array("10", "11", "12");
                                            break;
                                    }
                                    if ($event_for == 1) {
                                        $result = mysqli_query($link, "SELECT * FROM `member` WHERE `P_ID`=0 and `End` =0 and `Member_Type`='1' and (`DOB`LIKE '%-$day[0]-%'or `DOB` LIKE '%-$day[1]-%' or `DOB` LIKE '%-$day[2]-%' ) ORDER by `DOB` ASC ");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row[Member_ID]', '$row[Member_ID]', '1' )");
                                        }

                                    } else if ($event_for == 2) {
                                        $result = mysqli_query($link, "SELECT member.`Chinese_Name`, member_family.* , COUNT(member_family.Master_member)as num FROM `member`, `member_family` WHERE Member_Type=2 and Member_ID=member_family.Master_member and member.`End` =0 and member.`P_ID` =0 and (`member_family`.`DOB` LIKE '%-$day[0]-%'or `member_family`.`DOB` LIKE '%-$day[1]-%' or `member_family`.`DOB` LIKE '%-$day[2]-%' ) GROUP by `member_family`.`Master_member` ORDER BY `member_family`.`DOB` ASC");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $getlist = mysqli_query($link, "SELECT * FROM `member_family` WHERE`P_ID` =0 and `Master_member`='$row[Master_member]'");
                                            while ($row1 = mysqli_fetch_assoc($getlist)) {
                                                $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row1[Member_SID]', '$row1[Master_member]', '1' )");
                                            }
                                        }
                                    }

                                    break;
                                case 2: //出生年份
                                    if ($event_for == 1) {
                                        $result = mysqli_query($link, "SELECT * FROM `member` WHERE `P_ID`=0 and `End` =0 and `Member_Type`='1' ORDER by `DOB` ASC LIMIT $info[Event_Limit]");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row[Member_ID]', '$row[Member_ID]', '1' )");
                                        }

                                    } else if ($event_for == 2) {
                                        $result = mysqli_query($link, "SELECT member.`Chinese_Name`, member_family.* , COUNT(member_family.Master_member)as num FROM `member`, `member_family` WHERE Member_Type=2 and Member_ID=member_family.Master_member and member.`End` =0 and member.`P_ID` =0 GROUP by `member_family`.`Master_member` ORDER BY `member_family`.`DOB` ASC LIMIT $info[Event_Limit]");
                                        $temp = $info['Event_Limit'];
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if ($temp > 0) {
                                                $getlist = mysqli_query($link, "SELECT * FROM `member_family` WHERE`P_ID` =0 and `Master_member`='$row[Master_member]'");
                                                while ($row1 = mysqli_fetch_assoc($getlist)) {
                                                    $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row1[Member_SID]', '$row1[Master_member]', '1' )");
                                                    $temp--;
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case 3: //參加次數
                                    $aday = date('Y-m-d', strtotime(date("Y-m-d", mktime()) . " - 365 day"));
                                    $output['date'] = $aday;
                                    if ($event_for == 1) {
                                        $result = mysqli_query($link, "SELECT `member`.*, `member`.`Member_ID` FROM `member` WHERE `member`.`P_ID` = 0 AND `member`.`End` = 0 AND `member`.`Member_Type` = 1 AND `member`.`Member_ID` NOT IN( SELECT `event_apply`.`Member_ID` FROM `event_apply` WHERE `event_apply`.`Event_ID` IN( SELECT `event`.`Event_Connect` FROM `event` WHERE `event`.`Event_Type` = 3 AND `event`.`Start_Date`>='$aday' ) GROUP BY `event_apply`.`Member_ID` ORDER BY COUNT(`event_apply`.`Member_ID`) ASC ) limit $info[Event_Limit]");
                                        $temp = $info['Event_Limit'];
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row[Member_ID]', '$row[Member_ID]', '1' )");
                                            $temp--;
                                        }
                                        $result = mysqli_query($link, "SELECT `member`.*, `member`.`Member_ID` FROM `member` WHERE `member`.`P_ID` = 0 AND `member`.`End` = 0 AND `member`.`Member_Type` = 1 AND `member`.`Member_ID`  IN( SELECT `event_apply`.`Member_ID` FROM `event_apply` WHERE `event_apply`.`Event_ID` IN( SELECT `event`.`Event_Connect` FROM `event` WHERE `event`.`Event_Type` = 3 AND `event`.`Start_Date`>='$aday' ) GROUP BY `event_apply`.`Member_ID` ORDER BY COUNT(`event_apply`.`Member_ID`) ASC ) limit $temp");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row[Member_ID]', '$row[Member_ID]', '1' )");
                                            $temp--;
                                        }
                                    } else if ($event_for == 2) {
                                        $result = mysqli_query($link, "SELECT `member`.*, `member`.`Member_ID` FROM `member` WHERE `member`.`P_ID` = 0 AND `member`.`End` = 0 AND `member`.`Member_Type` = 2 AND `member`.`Member_ID` NOT IN( SELECT `event_apply`.`Member_ID` FROM `event_apply` WHERE `event_apply`.`Event_ID` IN( SELECT `event`.`Event_Connect` FROM `event` WHERE `event`.`Event_Type` = 4 AND `event`.`Start_Date`>='$aday' ) GROUP BY `event_apply`.`Member_ID` ORDER BY COUNT(`event_apply`.`Member_ID`) ASC ) limit $info[Event_Limit]");
                                        $temp = $info['Event_Limit'];
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if ($temp > 0) {
                                                $getlist = mysqli_query($link, "SELECT * FROM `member_family` WHERE`P_ID` =0 and `Master_member`='$row[Member_ID]'");
                                                while ($row1 = mysqli_fetch_assoc($getlist)) {
                                                    $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row1[Member_SID]', '$row1[Master_member]', '1' )");
                                                    $temp--;
                                                }
                                            }
                                        }
                                        $result = mysqli_query($link, "SELECT `member`.*, `member`.`Member_ID` FROM `member` WHERE `member`.`P_ID` = 0 AND `member`.`End` = 0 AND `member`.`Member_Type` = 2 AND `member`.`Member_ID`  IN( SELECT `event_apply`.`Member_ID` FROM `event_apply` WHERE `event_apply`.`Event_ID` IN( SELECT `event`.`Event_Connect` FROM `event` WHERE `event`.`Event_Type` = 4 AND `event`.`Start_Date`>='$aday' ) GROUP BY `event_apply`.`Member_ID` ORDER BY COUNT(`event_apply`.`Member_ID`) ASC ) limit $temp");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if ($temp > 0) {
                                                $getlist = mysqli_query($link, "SELECT * FROM `member_family` WHERE`P_ID` =0 and `Master_member`='$row[Member_ID]'");
                                                while ($row1 = mysqli_fetch_assoc($getlist)) {
                                                    $do = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$New_id', '$row1[Member_SID]', '$row1[Master_member]', '1' )");
                                                    $temp--;
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }

                        }
                    }

                    break;
                case "Update_Event":
                    $result = mysqli_query($link, "UPDATE `event` SET `Event_Connect`='$info[Event_Connect]', `Event_Location`='$info[Event_Location]', `Event_Name`='$info[Event_Name]', `Event_Info`='$info[Event_Info]', `Event_Limit`='$info[Event_Limit]', `Event_Cate`='$info[Event_Cate]', `Event_Type`='$info[Event_Type]', `Start_Date`='$info[Start_Date]', `End_Date`='$info[End_Date]', `Start_Time`='$info[Start_Time]', `End_Time`='$info[End_Time]', `Repeat_Week`='$info[Repeat_Week]', `Create_Date`='$info[Create_Date]', `Create_By`='$info[Create_By]', `Deleted`='$info[Deleted]' WHERE `ID`='$info[ID]'");
                    $output['info'] = true;
                    $output['result'] = true;
                    $output['Data']="UPDATE `event` SET `Event_Connect`='$info[Event_Connect]', `Event_Location`='$info[Event_Location]', `Event_Name`='$info[Event_Name]', `Event_Info`='$info[Event_Info]', `Event_Limit`='$info[Event_Limit]', `Event_Cate`='$info[Event_Cate]', `Event_Type`='$info[Event_Type]', `Start_Date`='$info[Start_Date]', `End_Date`='$info[End_Date]', `Start_Time`='$info[Start_Time]', `End_Time`='$info[End_Time]', `Repeat_Week`='$info[Repeat_Week]', `Create_Date`='$info[Create_Date]', `Create_By`='$info[Create_By]', `Deleted`='$info[Deleted]' WHERE `ID`='$info[ID]'";
                    break;
                case "Delete_Event":
                $result = mysqli_query($link, "UPDATE `event` SET `Deleted`='1' WHERE `ID`='$info'");
                $output['info'] = true;
                $output['result'] = true;
                break;
                case "Apply_Event":
                    $result = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$info[Event_Connect]', '$info2[Member_ID]', '$info2[Member_ID]', '2' )");
                    break;
                case "Apply_Events":
                $output['info'] = true;
                    $output['result'] = true;
                    for ($temp = 0; $temp < sizeof($info2); $temp++) {
                        if ($info2[$temp]['Checked'] == true) {
                            $result = mysqli_query($link, "INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$info[Event_Connect]', '".$info2[$temp]['Member_SID']."', '".$info2[$temp]['Master_member']."', '2' )");
                            $output['sql']="INSERT INTO `event_apply` ( `Event_ID`, `Member_ID`, `Member_ID_F`,  `Status`) VALUES ('$info[Event_Connect]', '".$info2[$temp]['Member_SID']."', '".$info2[$temp]['Master_member']."', '2' )";
                            //     $result = mysqli_query($link,
                            //         "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`,`Member_ID_F`)
                            // VALUES ('" . $info[$temp]['Event_ID'] . "','" . $info[$temp]['Member_ID'] . "','" . $info[$temp]['Member_ID_F'] . "')");
                        }
                    }
                    
                    break;
                case "Attened_Evnet":
                    $result = mysqli_query($link, "Select member.Chinese_Name, member_family.Chinese_Name, member.Member_ID,member_family.Member_SID from `member` left join `member_family` on (`member`.Member_ID = `member_family`.Master_Member ) where member.Octopus='$info' or member_family.Octopus='$info'");
                    $output['data'] = "Select member.Chinese_Name, member_family.Chinese_Name, member.Member_ID,member_family.Member_SID from `member` left join `member_family` on (`member`.Member_ID = `member_family`.Master_Member ) where member.Octopus='$info' or member_family.Octopus='$info'";
                    break;
                case "suggestion_to_confirm":
                    $output['info'] = true;
                    $output['result'] = true;
                    for ($temp = 0; $temp < sizeof($info); $temp++) {
                        if ($info[$temp]['Check'] == true) {
                            $result = mysqli_query($link, "UPDATE `event_apply` SET `Status` = '0' WHERE `event_apply`.`ID` ='" . $info[$temp]['ID'] . "'");
                            $output['data'] = "UPDATE `event_apply` SET `Status` = '0' WHERE `event_apply`.`ID` ='" . $info[$temp]['ID'] . "'";
                            //     $result = mysqli_query($link,
                            //         "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`,`Member_ID_F`)
                            // VALUES ('" . $info[$temp]['Event_ID'] . "','" . $info[$temp]['Member_ID'] . "','" . $info[$temp]['Member_ID_F'] . "')");
                        }
                    }
                    break;
                case "Waiting_to_confirm":
                    $output['info'] = true;
                    $output['result'] = true;
                    $result = mysqli_query($link, "UPDATE `event_apply` SET `Status` = '0' WHERE `event_apply`.`ID` ='" . $info . "'");
                    break;
                case "confirm_to_suggeestion":
                    $output['info'] = true;
                    $output['result'] = true;
                    $result = mysqli_query($link, "UPDATE `event_apply` SET `Status` = '1' WHERE `event_apply`.`ID` ='" . $info . "'");
                    break;
                case "waiting_to_delete":
                    $output['info'] = true;
                    $output['result'] = true;
                    $result = mysqli_query($link, "DELETE FROM `event_apply` WHERE `event_apply`.`ID` = $info");
                    break;

            }

        } else {
            $output['errorCode'] = 'e2 passcode error';
        }

    }
} else {
    $output['errorCode'] = 'e1 No information';
}

echo json_encode($output);

/*elseif ( $login!=''&&$Username !=''&&$check==11) {
//   $output['whereiam']="here4";
//$link = mysqli_connect("localhost", "root", "", "tutors");
//mysqli_query($link,"SET NAMES 'UTF8'");
if ($link) {
$result = mysqli_query($link, "Select * from users where Autoid='$login'");

if ($result) {
$row = mysqli_fetch_assoc($result);
if ($row) {
$output['user'] = $row;
$output['result'] = TRUE;
}
}
} else
$output['errorCode'] = 'e4.1';}
elseif ($password != '' && $new != '' && $login != '') {

//$link = mysqli_connect("localhost", "root", "", "tutors");
//mysqli_query($link,"SET NAMES 'UTF8'");
if ($link) {
$result = mysqli_query($link, "SELECT Autoid  FROM users WHERE Autoid='$login' AND password= '$password'");
if ($result) {
$row = mysqli_fetch_assoc($result);
if ($row) {
$result = mysqli_query($link, "UPDATE users SET password='$new' WHERE Autoid='$login'");
if ($result) {

$output['result'] = TRUE;
}
}
}
} else
$output['errorCode'] = 'e4.1';}*/
