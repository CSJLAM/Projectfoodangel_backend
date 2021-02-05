<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. testing
 * http://localhost:8080/Match_your_own_Tutors/getPost.php?jsonDoc=%7B%0A%20%20%22FindType%22%3A%202%2C%0A%20%20%22IsFind%22%3A%200%0A%7D
 * update use UPDATE `post` SET `LastUpdate` = CURRENT_TIME() WHERE `Autoid` = 1
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$jsonDoc = '';
if (isset($_REQUEST['jsonDoc'])) {
    $jsonDoc = $_REQUEST['jsonDoc'];
}

//echo $jsonDoc;

$output['result'] = false;

if ($jsonDoc != '') {
    $obj = json_decode($jsonDoc, true);
    if ($obj) {
        @$passcode = $obj['Passcode'];
        @$Function = $obj['Function'];
        @$info = $obj['info'];
        @$info2 = $obj['info2'];
        @$info3 = $obj['info3'];
        include "DBConnect.php";
        //if ($FindType != '' && $IsFind != '') {
        if ($passcode == "GetInfo") {
            //$link = mysqli_connect("localhost", "root", "", "tutors");
            //mysqli_query($link,"SET NAMES 'UTF8'")
            //$rows=[];
            switch ($Function) {
                case "HomePerm":
                    $result = mysqli_query($link, "SELECT `event`.`ID`, `event`.`Event_Name`,`campus_room`.`Room` FROM `event` LEFT JOIN `campus_room` on (`event`.`Event_Location` = `campus_room`.ID ) where (`Deleted`=0 AND `Repeat_Week` like '%" . date("D") . "%' AND (`Start_Date` is NULL or `Start_Date`='0000-00-00'))");
                    break;
                case "Home_Today":
                    $aday = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT `event`.`ID`, `event`.`Event_Name`,`campus_room`.`Room` FROM `event` LEFT JOIN `campus_room` on (`event`.`Event_Location` = `campus_room`.ID ) where (`Deleted`=0 AND `Start_Date` = '$aday')");
                    break;
                case "Load_Event_Attend":
                    $aday = date("Y-m-d");
                    //$result = mysqli_query($link, "SELECT ifnull( event_attendance.Status, 'Unchecked') as Attend, event_apply.* , member.Chinese_Name, member.Octopus  FROM `event_apply` LEFT JOIN `member` on (`event_apply`.`Member_ID_F` = `member`.`Member_ID` and `member`.`P_ID`=0) LEFT JOIN `event_attendance` on ( `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_attendance`.`Event_ID`='$info' and `event_attendance`.`Attend_Date`='$aday') WHERE `event_apply`.`Event_ID`='$info' AND `event_apply`.`Status`=0 and `event_apply`.`Del`=0");
                    $output['sql'] = "SELECT ifnull( event_attendance.Status, 'Unchecked') as Attend, event_apply.* , member.Chinese_Name, member.Octopus  FROM `event_apply` LEFT JOIN `member` on (`event_apply`.`Member_ID_F` = `member`.`Member_ID` and `member`.`P_ID`=0) LEFT JOIN `event_attendance` on ( `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_attendance`.`Event_ID`='$info' and `event_attendance`.`Attend_Date`='$aday') WHERE `event_apply`.`Event_ID`='$info' AND `event_apply`.`Status`=0 and `event_apply`.`Del`=0";
                    //$output['sql'] = "SELECT ifnull( event_attendance.Status, 'Unchecked') as Attend, event_apply.* , member.Chinese_Name  FROM `event_apply` LEFT JOIN `member` on (`event_apply`.`Member_ID` = `member`.`Member_ID`) LEFT JOIN `event_attendance` on ( `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_attendance`.`Event_ID`='$info') WHERE `event_apply`.`Event_ID`='$info' AND `event_apply`.`Status`=0";
                    $result = mysqli_query($link, "SELECT IFNULL( event_attendance.Status, 'Unchecked' ) AS Attend, event_apply.*, member.Chinese_Name, IFNULL( member_family.Chinese_Name ,member.Chinese_Name)AS Fam_Chinese_Name, IFNULL(member_family.Octopus, member.Octopus) AS Octopus FROM `event_apply` LEFT JOIN `member` ON( `event_apply`.`Member_ID_F` = `member`.`Member_ID` AND `member`.`P_ID` = 0 ) LEFT JOIN `member_family` ON( `event_apply`.`Member_ID` = `member_family`.`Member_SID` AND `member_family`.`P_ID`=0 ) LEFT JOIN `event_attendance` ON( `event_apply`.`Member_ID` = `event_attendance`.`Member_ID` AND `event_attendance`.`Event_ID` = '$info' AND `event_attendance`.`Attend_Date` = '$aday' ) WHERE `event_apply`.`Event_ID` = '$info' AND `event_apply`.`Status` = 0 AND `event_apply`.`Del` = 0");
                    //$output['sql'] = "SELECT IFNULL( event_attendance.Status, 'Unchecked' ) AS Attend, event_apply.*, member.Chinese_Name, IFNULL( member_family.Chinese_Name ,member.Chinese_Name)AS Fam_Chinese_Name, IFNULL(member_family.Octopus, member.Octopus) AS Octopus FROM `event_apply` LEFT JOIN `member` ON( `event_apply`.`Member_ID_F` = `member`.`Member_ID` AND `member`.`P_ID` = 0 ) LEFT JOIN `member_family` ON( `event_apply`.`Member_ID` = `member_family`.`Member_SID` AND `member_family`.`P_ID`=0 ) LEFT JOIN `event_attendance` ON( `event_apply`.`Member_ID` = `event_attendance`.`Member_ID` AND `event_attendance`.`Event_ID` = '$info' AND `event_attendance`.`Attend_Date` = '$aday' ) WHERE `event_apply`.`Event_ID` = '$info' AND `event_apply`.`Status` = 0 AND `event_apply`.`Del` = 0";
                    $output['sql'] = "SELECT IFNULL( event_attendance.Status, 'Unchecked' ) AS Attend, event_apply.*, member.Chinese_Name, IFNULL( member_family.Chinese_Name ,member.Chinese_Name)AS Fam_Chinese_Name, IFNULL(member_family.Octopus, member.Octopus) AS Octopus FROM `event_apply` LEFT JOIN `member` ON( `event_apply`.`Member_ID_F` = `member`.`Member_ID` AND `member`.`P_ID` = 0 ) LEFT JOIN `member_family` ON( `event_apply`.`Member_ID` = `member_family`.`Member_SID` AND `member_family`.`P_ID`=0 ) LEFT JOIN `event_attendance` ON( `event_apply`.`Member_ID` = `event_attendance`.`Member_ID` AND `event_attendance`.`Event_ID` = '$info' AND `event_attendance`.`Attend_Date` = '$aday' ) WHERE `event_apply`.`Event_ID` = '$info' AND `event_apply`.`Status` = 0 AND `event_apply`.`Del` = 0";
                    break;
                case "Load_All_Perm_Event":
                    $result = mysqli_query($link, "SELECT `event`.* ,`campus_room`.`Room` FROM `event` LEFT JOIN `campus_room` on (`event`.`Event_Location` = `campus_room`.ID ) where (`Deleted`=0 AND (`Start_Date` is NULL or `Start_Date`='0000-00-00'))");
                    break;
                case "Load_All_Single_Event":
                    $aday = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT `event`.* , `campus_room`.`Room` FROM `event` LEFT JOIN `campus_room` on (`event`.`Event_Location` = `campus_room`.ID ) where (`Deleted`=0 AND `Start_Date` >= '$aday' )order by `Start_Date` Asc");
                    break;
                case "Load_All_Outdated_Event":
                    $aday = date("Y-m-d", strtotime("-2 months"));
                    $eday = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT `event`.* , `campus_room`.`Room` FROM `event` LEFT JOIN `campus_room` on (`event`.`Event_Location` = `campus_room`.ID ) where (`Deleted`=0 AND `Start_Date` >= '$aday' and `Start_Date` < '$eday' )order by `Start_Date` Asc");

                    break;
                case "Load_Weekly_Perm_Setting":
                    $aday = date("Y-m-d", strtotime('monday next week'));
                    $eday = date("Y-m-d", strtotime('sunday next week'));
                    $result = mysqli_query($link, "SELECT * FROM `event_attendance` WHERE `Member_ID`='$info' and `Attend_Date` >='$aday' and`Attend_Date` <='$eday'");
                    $output['sql'] = "SELECT * FROM `event_attendance` WHERE `Member_ID`='$info' and `Attend_Date` >='$aday' and`Attend_Date` <='$eday'";
                    $output['result'] = true;
                    break;
                case "Load_Event_Preference":
                    $result = mysqli_query($link, "SELECT * FROM `perm_preference` WHERE `Member_ID`='$info' and `Event_ID`='$info2[Event_Connect]' ");
                    //$output['sql'] = "SELECT * FROM `perm_preference` WHERE `Member_ID`='$info' and `Event_ID`='$info2[Event_Connect]' ";
                    $output['result'] = true;
                    break;
                case "Calendar":
                    $aday = date("Y-m-d");
                    $eday = date("Y-m-t");
                    $result = mysqli_query($link, "SELECT * FROM `event` WHERE `Event_Cate` !='1' and `Start_Date` >= '$aday'  and `Start_Date` <= '$eday' and `deleted`=0 order by `Start_Date` Asc");
                    break;
                case "Get_Event_For":
                    $result = mysqli_query($link, "SELECT * FROM `event_cate` WHERE `Event_Cate`.`ID` = $info");
                    break;
                case "Get_Campus":
                    $result = mysqli_query($link, "SELECT campus_room.ID , campus_room.Room, campus.ID as Campus_ID, campus.Location FROM campus_room , campus WHERE campus_room.Campus_ID=campus.ID");
                    break;
                case "Get_Event_Cate":
                    $result = mysqli_query($link, "SELECT `event_cate`.`ID`, `event_cate`.`Name`, `event_cate`.`Type`,`event_cate`.`Event_For`, `member_cate`.`Name` as Cate_Name from `event_cate`,`member_cate` where `event_cate`.`Event_For`=`member_cate`.`ID` and `event_cate`.`P_ID`='0'");
                    break;
                case "Get_Addend":
                    $result = mysqli_query($link, "SELECT ifnull( event_attendance.Status, 'Unchecked') as Attend, event_apply.* , member.Chinese_Name , member_family.Chinese_Name FROM`member_Family`, `event_apply` LEFT JOIN `member` on (`event_apply`.`Member_ID` = `member`.`Member_ID`) LEFT JOIN `event_attendance` on ( `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_attendance`.`Event_ID`='$info[ID]') WHERE `event_apply`.`Event_ID`='$info[ID]' and (`member`.`Octopus`='$info[Octopus]' or `member_family`.`Octopus`='$info[OCtopus]')and `eveny_apply`");
                    break;
                case "Get_event_info":
                    $result = mysqli_query($link, "SELECT * FROM `event` WHERE Deleted=0 AND Event_Connect = '$info'");
                    break;
                case "Get_Suggestion":
                    $result = mysqli_query($link, "SELECT `event_apply`.*, `member`.`Chinese_Name`, `member_family`.`Chinese_Name` as fam_member FROM `event_apply` LEFT JOIN `member` ON( `member`.`Member_ID` = `event_apply`.`Member_ID_F`and `member`.`P_ID`=0 ) LEFT JOIN `member_family` ON( `member_family`.`Member_SID` = `event_apply`.`Member_ID` and `member_family`.`P_ID`=0) WHERE `Del` = 0 AND `Status` = 1 AND `Event_ID` =$info");
                    break;
                case "Get_Confirm_List":
                    $result = mysqli_query($link, "SELECT `event_apply`.*, `member`.`Chinese_Name`, `member_family`.`Chinese_Name` as fam_member FROM `event_apply` LEFT JOIN `member` ON( `member`.`Member_ID` = `event_apply`.`Member_ID_F` and `member`.`P_ID`=0) LEFT JOIN `member_family` ON( `member_family`.`Member_SID` = `event_apply`.`Member_ID` and `member_family`.`P_ID`=0 ) WHERE `Del` = 0 AND `Status` = 0 AND `Event_ID` =$info");
                    break;
                case "Get_Waiting_List":
                    $result = mysqli_query($link, "SELECT `event_apply`.*, `member`.`Chinese_Name`, `member_family`.`Chinese_Name` as fam_member FROM `event_apply` LEFT JOIN `member` ON( `member`.`Member_ID` = `event_apply`.`Member_ID_F` and `member`.`P_ID`=0) LEFT JOIN `member_family` ON( `member_family`.`Member_SID` = `event_apply`.`Member_ID` and `member_family`.`P_ID`=0 ) WHERE `Del` = 0 AND `Status` = 2 AND `Event_ID` =$info");
                    break;
                case "Load_Event_Name":
                    $result = mysqli_query($link, "SELECT * from `event` where Event_Connect='$info' and Deleted=0");
                    break;
                case "Load_Permission_Cate":
                    $result = mysqli_query($link, "SELECT `permission`.*, IFNULL(`dept`.`Dept_Name`, `staff`.`Name` )AS Name FROM `permission` LEFT JOIN `dept` on `dept`.`Dept_Hash`=`permission`.`Dept` AND `dept`.`P_ID`=0 LEFT JOIN `staff` on `staff`.`Staff_Hash`=`permission`.`Dept` and `staff`.`P_ID`=0 WHERE `permission`.`Page`='$info'");
                    break;
                case "Load_Permission_UN_dept":
                    $result = mysqli_query($link, "SELECT`dept`.`Dept_Name` as Name, `dept`.`Dept_Hash` as Dept, '$info' as Page FROM `dept` LEFT join `permission` on `permission`.`Dept`=`dept`.`Dept_Hash` and `permission`.`Page`='$info' WHERE `dept`.`P_ID`=0 and `permission`.`Dept` is null");

                    break;
                case "Load_Permission_UN_user":
                    $result = mysqli_query($link, "SELECT `staff`.`Name` as Name, `staff`.`Staff_Hash` as Dept, '$info'as Page FROM `staff` LEFT join `permission` on `permission`.`Dept`=`staff`.`Staff_Hash` and `permission`.`Page`='$info' WHERE `staff`.`P_ID`=0 and `permission`.`Dept` is null");

                    break;
                case "Check_Permission":
                    $result = mysqli_query($link, "SELECT * FROM `permission` WHERE `Dept`='$info' or `Dept`='$info2'");
                    break;
                case "VER":
                    $result = mysqli_query($link, "SELECT * from `version_control` ");
                    break;
                default:
                    break;
                    //$result = mysqli_query($link, "SELECT * FROM post WHERE IsFind= $IsFind ORDER BY Autoid DESC");
            }
            //$result = mysqli_query($link, "SELECT * FROM post ORDER BY Autoid ASC");

            while ($row = mysqli_fetch_assoc($result)) {
                //$output['some'] = $row;
                $output['info'][] = $row;
                $rows[] = $row;
                $output['result'] = true;
            }

        } else if ($passcode == "SetInfo") {
            switch ($Function) {
                case "MarkAttend":
                    $aday = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT * from `event_attendance` WHERE `Attend_Date` = '$aday' and `Status`= 'Checked' and `Member_ID` = '$info'  AND `Event_ID` ='$info2'");
                    $row = mysqli_fetch_assoc($result);
                    if (count($row) == 0) {
                        $result = mysqli_query($link,
                            "INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`,  `Attend_Date` ,`Status`) VALUES ( '$info2', '$info',  '$aday', 1)");
                        $output['test'] = "INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`,  `Attend_Date` ,`Status`) VALUES ( '$info2', '$info',  '$aday', 1)";
                        $output['info'] = true;
                    } else {
                        $output['info'] = false;
                    }

                    break;
                case "MarkAttends":
                    $aday = date("Y-m-d");
                    for ($temp = 0; $temp < sizeof($info); $temp++) {
                        if ($info[$temp]['Checked'] == true) {
                            $result = mysqli_query($link, "SELECT * from `event_attendance` WHERE `Attend_Date` = '$aday' and `Status`= 'Checked' and `Member_ID` = '" . $info[$temp]['Member_ID'] . "'  AND `Event_ID` ='$info2'");
                            //$output['sql'] .= "SELECT * from `event_attendance` WHERE `Attend_Date` = '$aday' and `Status`= 'Checked' and `Member_ID` = '" . $info[$temp]['Member_ID'] . "'  AND `Event_ID` ='$info2'";
                            $row = mysqli_fetch_assoc($result);
                            if (count($row) == 0) {
                                $result = mysqli_query($link,
                                    "INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`, `Member_ID_F`, `Attend_Date` ,`Status`) VALUES ( '$info2', '" . $info[$temp]['Member_ID'] . "', '" . $info[$temp]['Member_ID_F'] . "',  '$aday', 1)");
                                //$output['test'] = "INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`,  `Attend_Date` ,`Status`) VALUES ( '$info2', '" . $info[$temp]['Member_ID'] . "',  '$aday', 1)";
                                $output['info'] = true;
                            } else {
                                $output['info'] = false;
                            }
                        }
                    }

                    break;
                case "Set_Perm_leave":
                    for ($temp = 0; $temp < sizeof($info); $temp++) {
                        if ($info[$temp]['Checked'] == false && $info[$temp]['GetData'] == "false") {
                            $result = mysqli_query($link, "INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`,  `Attend_Date` ,`Status`) VALUES ( '$info3[Event_Connect]', '$info2',  '" . $info[$temp]['Day'] . "', 2)");
                            //$output['data']="INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`,  `Attend_Date` ,`Status`) VALUES ( '$info3[Event_Connect]', '$info2',  '".$info[$temp]['Day']."', 3)";
                        } else if ($info[$temp]['Checked'] == true && $info[$temp]['GetData'] == "false") {

                        } else if ($info[$temp]['Checked'] == false && $info[$temp]['GetData'] == "true") {

                        } else if ($info[$temp]['Checked'] == true && $info[$temp]['GetData'] == "true") {
                            $result = mysqli_query($link, "DELETE FROM `event_attendance` WHERE `Event_ID`='$info3[Event_Connect]' AND `Member_ID`='$info2' AND `Attend_Date` ='" . $info[$temp]['Day'] . "' AND `Status`='Leave'");
                            $output['data'] = "DELETE FROM `event_attendance` WHERE `Event_ID`='$info3[Event_Connect]' AND `Member_ID`='$info2' AND `Attend_Date` ='" . $info[$temp]['Day'] . "' AND `Status`='Leave'";
                        }
                    }
                    break;
                case "Set_Perference":
                @$result = mysqli_query($link,"SELECT * FROM `perm_preference` WHERE `Event_ID` ='$info3[Event_Connect]' and `Member_ID` ='$info2'");
                $row = mysqli_fetch_assoc($result);   
                $output['resultA']=$row;
                if($row!=null){
                    @$result = mysqli_query($link,"UPDATE `perm_preference` SET `Week` = '$info' WHERE `perm_preference`.`ID` ='$info3[Event_Connect]' and `perm_preference`.`Member_ID` ='$info2'");
                    //@$output['sql']="UPDATE `perm_preference` SET `Week` = '$info' WHERE `perm_preference`.`ID` ='$info3[Event_Connect]' and `perm_preference`.`Member_ID` ='$info2'";
                }else{
                    @$result = mysqli_query($link,"INSERT INTO `perm_preference` (`Event_ID`, `Member_ID`, `Week`, `Deleted`) VALUES ( '$info3[Event_Connect]', '$info2', '$info', '0')");
                    $output['SQLA']="INSERT INTO `perm_preference` (`Event_ID`, `Member_ID`, `Week`, `Deleted`) VALUES ( '$info3[Event_Connect]', '$info2', '$info', '0')";
                }
                break;
                case "Permission_Delete":
                    $result = mysqli_query($link, "DELETE FROM `permission` WHERE `Page` ='$info' and `Dept`='$info2'");
                    break;
                case "Permission_Add":
                    $result = mysqli_query($link, "INSERT INTO `permission` (`Page`, `Dept`) VALUES ('$info', '$info2')");
                    break;
            }
            $output['result'] = true;
        } else {
            $output['errorCode'] = 'e2 passcode error';
        }

    }
} else {
    $output['errorCode'] = 'e1 No information';
}
// $aday = date("Y-m-d", strtotime('last sunday'));
// $eday = date("Y-m-d", strtotime('this saturday'));
// $result = mysqli_query($link, "SELECT * FROM `perm_perference_update` WHERE`DateUpdate` >='$aday' and `DateUpdate` <='$eday");
// //$output['sql']="SELECT * FROM `perm_perference_update` WHERE`DateUpdate` >='$aday' and `DateUpdate` <='$eday";
// if (!$result) {
//     $result = mysqli_query($link, "SELECT * FROM `event` WHERE `Event_Cate` =1");
//     while ($row = mysqli_fetch_assoc($result)) {
//         $weeks = split(',', $row['Repeat_Week']);
//         $result = mysqli_query($link, "SELECT * FROM `perm_preference` WHERE `Event_ID`=$row[Event_Connect]");
//         while ($row2 = mysqli_fetch_assoc($result)) {
//             $MWeeks = split(',', $row2['Week']);
//             for($temp2= 0;$temp2<sizeof($MWeeks);$temp2++){
//             for ($temp = 0; $temp < sizeof($weeks); $temp++) {
//                 if( $MWeeks[$temp2]==$weeks[$temp]){
//                     switch( $MWeeks[$temp2]){
//                         case "Mon":
//                         $nday =  date("Y-m-d", strtotime('next monday'));
//                         break;
//                         case "Tue":
//                         $nday =  date("Y-m-d", strtotime('next tuesday'));
//                         break;
//                         case "Wed":
//                         $nday =  date("Y-m-d", strtotime('next wednesday'));
//                         break;
//                         case "Thu":
//                         $nday =  date("Y-m-d", strtotime('next thursday'));
//                         break;
//                         case "Fri":
//                         $nday =  date("Y-m-d", strtotime('next friday'));
//                         break;
//                         case "Sat":
//                         $nday =  date("Y-m-d", strtotime('next saturday'));
//                         break;
//                         case "Sun":
//                         $nday =  date("Y-m-d", strtotime('this Sunday'));
//                         break;
//                       }

//                     $result = mysqli_query($link, "INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`,  `Attend_Date` ,`Status`) VALUES ( '$row[Event_Connect]', '$row2[Member_ID]',  '$nday', 2)");
//                     $output['sql']="INSERT INTO `event_attendance` ( `Event_ID`, `Member_ID`,  `Attend_Date` ,`Status`) VALUES ( '$row[Event_Connect]', '$row2[Member_ID]',  '$nday', 2)";

//                 }
//             }
//         }
//         }
//     }
//     $result = mysqli_query($link, "INSERT INTO `perm_perference_update` ( `updated`) VALUES ( '1')");

// }

echo json_encode($output);
//print"<BR><BR><BR>";
//echo json_encode($rows);
