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
$output['return'] = false;

if ($jsonDoc != '') {
    $obj = json_decode($jsonDoc, true);
    if ($obj) {
        @$passcode = $obj['Passcode'];
        @$Function = $obj['Function'];
        @$info = $obj['info'];
        @$info2 = $obj['info2'];
        @$info3 = $obj['info3'];
        @$info4 = $obj['info4'];
        include "DBConnect.php";
        //if ($FindType != '' && $IsFind != '') {
        if ($passcode == "GetMember") {
            //$link = mysqli_connect("localhost", "root", "", "tutors");
            //mysqli_query($link,"SET NAMES 'UTF8'")
            //$rows=[];
            switch ($Function) {
                case "List_Member_Type":
                    $result = mysqli_query($link, "SELECT `ID`,`Name` FROM `member_cate`");
                    break;
                // case "List_Member_Type_Event_Type":
                //     $result = mysqli_query($link,"SELECT `ID`, `Name`,`Type`,`Event_For` FROM `event_cate` WHERE `Event_For`=$info");
                // break;
                case "List_Member_Type_Perm_Event_Type":
                    $result = mysqli_query($link, "SELECT `ID`, `Name`,`Type`,`Event_For` FROM `event_cate` WHERE `Event_For`=$info and `Type`=1");
                    break;
                case "List_Member":
                    $result = mysqli_query($link, "SELECT Member_ID, Chinese_Name FROM `member` where `P_ID`='0' and `End`='0' and `Member_Type` =$info order by `Member_ID` ASC");
                    break;
                case "List_Applyed_Event":
                    $aday = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT `event_apply`.`Event_ID`, `event`.`Event_Name` FROM  `event_apply` LEFT JOIN `event` ON (`event`.`Event_Connect`= `event_apply`.`Event_ID`) WHERE  `event_apply`.`Member_ID`='$info' and  `event`.`Start_Date`>='$aday' and `Status`='0' ");
                    //$output['test']="SELECT `event_apply`.`Event_ID`, `event`.`Event_Name` FROM  `event_apply` LEFT JOIN `event` ON (`event`.`Event_Connect`= `event_apply`.`Event_ID`) WHERE  `event_apply`.`Member_ID`='$info' and  `event`.`Start_Date`>='$aday'";
                    break;
                case "List_Pass_Event":
                    $aday = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT `event_apply`.`Event_ID`, `event`.`Event_Name` FROM `event_apply` LEFT JOIN `event` ON (`event`.`ID`= `event_apply`.`Event_ID`) WHERE `event_apply`.`Member_ID`='$info' and `event`.`Start_Date`<'$aday' and `event`.`Start_Date`>'2000-1-1'");
                    //  $output['test']="SELECT `event_apply`.`Event_ID`, `event`.`Event_Name` FROM `event_apply` LEFT JOIN `event` ON (`event`.`ID`= `event_apply`.`Event_ID`) WHERE `event_apply`.`Member_ID`='$info' and `event`.`Start_Date`<'$aday' and `event`.`Start_Date`>'2000-1-1'";
                    break;
                case "Apply_Get_Perm_Event":
                    $result = mysqli_query($link, "SELECT `Event_Connect`, `Event_Name` from `event` where Event_Type = $info and Event_Cate = 1");
                    break;
                case "Get_Member_Info_by_ID":
                    $result = mysqli_query($link, "SELECT * FROM `member` WHERE `Member_ID` ='$info' and `P_ID`=0");
                    break;
                case "Get_Member_Family_Info_by_ID":
                    $result = mysqli_query($link, "SELECT * FROM `member_family` WHERE `Master_member`='$info' and `P_ID`=0");
                    break;
                case "Get_Member_Family_Info_by_Octopus":
                    $result = mysqli_query($link, "SELECT * FROM `member_family` WHERE `Master_member`=(SELECT `Master_member` FROM `member_family` WHERE `Octopus`='$info' and `P_ID`=0) and `P_ID`=0");
                    break;
                case "Get_Member_Urgent_Info_by_ID":
                    $result = mysqli_query($link, "SELECT * FROM `member_urgent` WHERE `Member_ID` ='$info'");
                    break;
                case "Get_Member_Info_by_Octopus":
                    $result = mysqli_query($link, "SELECT * FROM `member` WHERE `Octopus` ='$info' and `P_ID`=0");
                    break;
                case "Get_Fam_Info_by_Octopus":
                    $result = mysqli_query($link, "SELECT * FROM `member_family` WHERE `Master_member`=(SELECT `Master_member` FROM `member_family` WHERE `Octopus`='$info') and `P_ID`='0'");
                    break;
                case "Get_Member_Perm_Event":
                    //$result = mysqli_query($link, "SELECT * FROM `event` LEFT JOIN `event_apply` ON (`event_apply`.`Member_ID`='$info') WHERE `event`.`Event_Type`='$info2' AND `event`.`ID`=`event_apply`.`Event_ID`");
                    //$result = mysqli_query($link, "SELECT * FROM `event` LEFT JOIN `event_apply` ON (`event_apply`.`Member_ID`='$info' AND `event`.`ID`=`event_apply`.`Event_ID` and `event`.`Deleted`=0) WHERE `event`.`Event_Type`='$info2' ");
                    $result = mysqli_query($link, "SELECT `event`.`Event_Connect`, `event`.`Event_Name` ,`event_apply`.`ID`as EID, `event_apply`.`Member_ID` FROM `event` LEFT JOIN `event_apply` ON (`event_apply`.`Member_ID`='$info' AND `event`.`ID`=`event_apply`.`Event_ID` and `event`.`Deleted`=0 AND `event_apply`.`Del`=0) WHERE `event`.`Event_Type`='$info2'");
                    //$output['sql'] = "SELECT `event`.`Event_Connect`, `event`.`Event_Name` ,`event_apply`.`ID`as EID, `event_apply`.`Member_ID` FROM `event` LEFT JOIN `event_apply` ON (`event_apply`.`Member_ID`='$info' AND `event`.`ID`=`event_apply`.`Event_ID` and `event`.`Deleted`=0) WHERE `event`.`Event_Type`='$info2'";
                    break;
                case "Get_Appled_Member_Perm_Event":
                    $result = mysqli_query($link, "SELECT `event`.`Event_Connect`, `event`.`Repeat_Week`,`event`.`Event_Name` ,`event_apply`.`ID`as EID, `event_apply`.`Member_ID` FROM `event`, `event_apply`  WHERE `event`.`Event_Type`='$info2' and `event_apply`.`Member_ID`='$info' AND `event`.`ID`=`event_apply`.`Event_ID` and `event`.`Deleted`=0 and `event_apply`.`del`=0");
                    $output['sql']="SELECT `event`.`Event_Connect`, `event`.`Repeat_Week`,`event`.`Event_Name` ,`event_apply`.`ID`as EID, `event_apply`.`Member_ID` FROM `event`, `event_apply`  WHERE `event`.`Event_Type`='$info2' and `event_apply`.`Member_ID`='$info' AND `event`.`ID`=`event_apply`.`Event_ID` and `event`.`Deleted`=0 and `event_apply`.`del`=0";
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
            $output['return'] = true;

        } else if ($passcode == "SetMember") {
            switch ($Function) {
                case "Apply_New_Member":
                    $result = mysqli_query($link, "SELECT CONVERT( SUBSTRING(`Member_ID`, 2),UNSIGNED INTEGER) as ID ,SUBSTRING(`Member_ID`, 1,2) as Head  FROM `member` WHERE `Member_Type`=$info[Member_Type] ORDER BY `member`.`Member_ID` DESC LIMIT 1");
                    $row = mysqli_fetch_assoc($result);
                    $row['ID']++;
                    $row['ID'] = "$row[ID]";
                    $temp = "0000";
                    $temp = substr($temp, 0, -strlen($row['ID'])) . $row['ID'];
                    $MemberID = $row['Head'] . $temp;
                    $result = mysqli_query($link,
                        "INSERT INTO `member`( `Member_ID`, `Octopus`, `Member_Type`, `Chinese_Name`, `English_Name`, `Gender`, `DOB`, `HKID`, `Address`, `Marriage`, `Occupation`, `Year_In_HK`, `Contact_1`, `Contact_2`, `Gov_CSSA`, `Family_Income`, `Elderly_Income`, `Old_Age_Allowance`, `Disability_Allowance`, `Pension`, `Family_Support`, `E_Num_Son`, `E_Life_Tgt`, `Photo_Auth`, `Declaration_1`, `Declaration_2`,`Remark`,`Reason`)
                VALUES ('$MemberID','$info[Octopus]','$info[Member_Type]','$info[Chinese_Name]','$info[English_Name]','$info[Gender]','$info[DOB]','$info[HKID]','$info[Address]','$info[Marriage]','$info[Occupation]','$info[Year_In_HK]','$info[Contact_1]','$info[Contact_2]','$info[Gov_CSSA]','$info[Family_Income]','$info[Elderly_Income]','$info[Old_Age_Allowance]','$info[Disability_Allowance]','$info[Pension]','$info[Family_Support]','$info[E_Num_Son]','$info[E_Life_Tgt]','$info[Photo_Auth]','$info[Declaration_1]','$info[Declaration_2]', '$info[Remark]','$info[Reason]')");
                    $New_id = mysqli_insert_id($link);
                    for ($temp = 0; $temp < sizeof($info2); $temp++) {
                        $result = mysqli_query($link,
                            "INSERT INTO `member_urgent` ( `Member_ID`, `Name`, `Phone`, `Relationship`) VALUES ( '$MemberID', '" . $info2[$temp]['Name'] . "', '" . $info2[$temp]['Phone'] . "', '" . $info2[$temp]['Relationship'] . "')");
                    }
                    if ($info['Member_Type'] == 2) {
                        $result = mysqli_query($link,
                            "INSERT INTO `member_family`( `Master_member`, `Member_SID`, `Chinese_Name`, `English_Name`, `Gender`, `Relationship`, `Live_Together`, `DOB`, `Career`, `Income`, `Remark`, `Octopus`)
                        VALUES ('$MemberID','$MemberID','$info[Chinese_Name]','$info[English_Name]','$info[Gender]','','是','$info[DOB]','$info[Occupation]','$info[Family_Income]','','$info[Octopus]')"
                        );
                    }
                    // if ($info['Member_Type'] == 1) {
                    //     for ($temp = 0; $temp < sizeof($info4); $temp++) {
                    //         if ($info4[$temp]['Check'] == true) {
                    //             $result = mysqli_query($link,
                    //                 "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`)
                    //     VALUES ('" . $info4[$temp]['Event_Connect'] . "','$MemberID')");
                    //         }
                    //     }
                    // } else {
                    for ($temp = 0; $temp < sizeof($info4); $temp++) {
                        if ($info4[$temp]['Check'] == true) {
                            $result = mysqli_query($link,
                                "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`,`Member_ID_F`)
                        VALUES ('" . $info4[$temp]['Event_Connect'] . "','$MemberID','$MemberID')");
                        }
                    }
                    // }
                    for ($temp = 0; $temp < sizeof($info3); $temp++) {
                        $tempID = substr($MemberID, 0, 1);
                        $tempID .= $temp + 1;
                        $tempID = $tempID . substr($MemberID, 2, 6);
                        $result = mysqli_query($link,
                            "INSERT INTO `member_family`( `Master_member`, `Member_SID`, `Chinese_Name`, `English_Name`, `Gender`, `Relationship`, `Live_Together`, `DOB`, `Career`, `Income`, `Remark`, `Octopus`)
                                VALUES ('$MemberID','$tempID','" . $info3[$temp]['Chinese_Name'] . "','" . $info3[$temp]['English_Name'] . "','" . $info3[$temp]['Gender'] . "','" . $info3[$temp]['Relationship'] . "','" . $info3[$temp]['Live_Together'] . "','" . $info3[$temp]['DOB'] . "','" . $info3[$temp]['Career'] . "','" . $info3[$temp]['Income'] . "','" . $info3[$temp]['Remark'] . "','" . $info3[$temp]['Octopus'] . "')"
                        );
                        if ($info['Member_Type'] == 2) {
                            for ($temp1 = 0; $temp1 < sizeof($info4); $temp1++) {
                                if ($info4[$temp1]['Check'] == true) {
                                    $result = mysqli_query($link,
                                        "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`,`Member_ID_F`)
                                 VALUES ('" . $info4[$temp1]['Event_Connect'] . "','$tempID','$MemberID')");
                                }
                            }
                        }
                    }

                    $output['result'] = true;
                    $output['return'] = true;
                    $output['info'] = $MemberID . "---" . $New_id;

                    break;
                case "Update_Member":
                    $result = mysqli_query($link, "SELECT * FROM `member` WHERE `Member_ID`='$info[Member_ID]' AND`P_ID`=0");
                    $org_row = mysqli_fetch_assoc($result);
                    //Update the member info
                    $result = mysqli_query($link,
                        "INSERT INTO `member`( `Member_ID`, `Octopus`, `Member_Type`, `Chinese_Name`, `English_Name`, `Gender`, `DOB`, `HKID`, `Address`, `Marriage`, `Occupation`, `Year_In_HK`, `Contact_1`, `Contact_2`, `Gov_CSSA`, `Family_Income`, `Elderly_Income`, `Old_Age_Allowance`, `Disability_Allowance`, `Pension`, `Family_Support`, `E_Num_Son`, `E_Life_Tgt`, `Photo_Auth`, `Declaration_1`, `Declaration_2`,`Remark`,`Reason`)
                VALUES ('$org_row[Member_ID]','$info[Octopus]','$info[Member_Type]','$info[Chinese_Name]','$info[English_Name]','$info[Gender]','$info[DOB]','$info[HKID]','$info[Address]','$info[Marriage]','$info[Occupation]','$info[Year_In_HK]','$info[Contact_1]','$info[Contact_2]','$info[Gov_CSSA]','$info[Family_Income]','$info[Elderly_Income]','$info[Old_Age_Allowance]','$info[Disability_Allowance]','$info[Pension]','$info[Family_Support]','$info[E_Num_Son]','$info[E_Life_Tgt]','$info[Photo_Auth]','$info[Declaration_1]','$info[Declaration_2]', '$info[Remark]','$info[Reason]')");
                    $New_id = mysqli_insert_id($link);
                    $result = mysqli_query($link, "UPDATE `member` SET P_ID='$New_id' WHERE `ID`='$org_row[ID]'");
                    //update urgent info
                    $result = mysqli_query($link, "UPDATE `member_urgent` SET Member_ID='X$org_row[Member_ID]' WHERE Member_ID='$org_row[Member_ID]'");
                    for ($temp = 0; $temp < sizeof($info2); $temp++) {
                        $result = mysqli_query($link,
                            "INSERT INTO `member_urgent` ( `Member_ID`, `Name`, `Phone`, `Relationship`) VALUES
                        ( '$org_row[Member_ID]', '" . $info2[$temp]['Name'] . "', '" . $info2[$temp]['Phone'] . "', '" . $info2[$temp]['Relationship'] . "')");
                    }
                    //update event
                    for ($temp = 0; $temp < sizeof($info4); $temp++) {
                        //$result = mysqli_query($link, "SELECT * from `event_apply` WHERE `Event_ID`='" . $info4[$temp]['Event_Connect'] . "' and `Member_ID`='$org_row[Member_ID]'");
                        $result = mysqli_query($link, "SELECT * from `event_apply` WHERE `Event_ID`='" . $info4[$temp]['Event_Connect'] . "' and `Member_ID`='$org_row[Member_ID]' and `del`=0");
                        $output['gogo'][] = "SELECT * from `event_apply` WHERE `Event_ID`='" . $info4[$temp]['Event_Connect'] . "' and `Member_ID`='$org_row[Member_ID]'";
                        if ($info4[$temp]['Check'] == true) {
                            if ($result) {
                                @$rowo = mysqli_fetch_assoc($result);
                                if ($rowo['ID'] == null) {
                                    $result = mysqli_query($link,
                                        "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`,`Member_ID_F`)
                        VALUES ('" . $info4[$temp]['Event_Connect'] . "','$org_row[Member_ID]','$org_row[Member_ID]')");
                                }
                            }
                        } else {
                            if ($result) {
                                @$rowo = mysqli_fetch_assoc($result);
                                if ($rowo['ID'] != null) {
                                    $result = mysqli_query($link,
                                        "UPDATE `event_apply` SET `Del`=1 Where `Event_ID`='" . $info4[$temp]['Event_Connect'] . "' and `Member_ID`='$org_row[Member_ID]'");
                                }
                            }
                        }
                    }
                    //update member in family
                    $result = mysqli_query($link, "SELECT * FROM `Member_family` WHERE `Master_member`='$org_row[Member_ID]' order by `ID` DESC LIMIT 1");

                    $row = mysqli_fetch_assoc($result);
                    $result = mysqli_query($link, "UPDATE `Member_family` SET P_ID=-1 WHERE `Master_member`='$org_row[Member_ID]' and P_ID=0");
                    $SID_add = (int) substr($row['Member_SID'], 1, 2);
                    for ($temp = 0; $temp < sizeof($info3); $temp++) {

                        if (substr($info3[$temp]['ID'], 0, 1) == 'D') {
                            $tempID = substr($org_row['Member_ID'], 0, 1);
                            $SID_add++;
                            $tempID .= $SID_add;
                            $tempID = $tempID . substr($org_row['Member_ID'], 2, 6);
                            $result = mysqli_query($link,
                                "INSERT INTO `member_family`( `Master_member`, `Member_SID`, `Chinese_Name`, `English_Name`, `Gender`, `Relationship`, `Live_Together`, `DOB`, `Career`, `Income`, `Remark`, `Octopus`)
                            VALUES ('$org_row[Member_ID]','$tempID','" . $info3[$temp]['Chinese_Name'] . "','" . $info3[$temp]['English_Name'] . "','" . $info3[$temp]['Gender'] . "','" . $info3[$temp]['Relationship'] . "','" . $info3[$temp]['Live_Together'] . "','" . $info3[$temp]['DOB'] . "','" . $info3[$temp]['Career'] . "','" . $info3[$temp]['Income'] . "','" . $info3[$temp]['Remark'] . "','" . $info3[$temp]['Octopus'] . "')"
                            );
                            if ($info['Member_Type'] == 2) {
                                for ($temp1 = 0; $temp1 < sizeof($info4); $temp1++) {
                                    if ($info4[$temp1]['Check'] == true) {
                                        $result = mysqli_query($link,
                                            "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`,`Member_ID_F`)
                                 VALUES ('" . $info4[$temp1]['Event_Connect'] . "','$tempID','$org_row[Member_ID]')");
                                    }
                                }
                            }
                        } else {
                            // $result = mysqli_query($link, "SELECT * FROM `member_family` WHERE `ID`='" . $info3[$temp]['ID'] . "'");
                            // $rows = mysqli_fetch_assoc($result);

                            //     $result = mysqli_query($link,"SELECT * FROM `member_family` WHERE `Member_SID`='$info[Member_ID]' AND`P_ID`=0");
                            // $org_U_row= mysqli_fetch_assoc($result);
                            $result = mysqli_query($link,
                                "INSERT INTO `member_family`( `Master_member`, `Member_SID`, `Chinese_Name`, `English_Name`, `Gender`, `Relationship`, `Live_Together`, `DOB`, `Career`, `Income`, `Remark`, `Octopus`)
                            VALUES ('$org_row[Member_ID]','" . $info3[$temp]['Member_SID'] . "','" . $info3[$temp]['Chinese_Name'] . "','" . $info3[$temp]['English_Name'] . "','" . $info3[$temp]['Gender'] . "','" . $info3[$temp]['Relationship'] . "','" . $info3[$temp]['Live_Together'] . "','" . $info3[$temp]['DOB'] . "','" . $info3[$temp]['Career'] . "','" . $info3[$temp]['Income'] . "','" . $info3[$temp]['Remark'] . "','" . $info3[$temp]['Octopus'] . "')"
                            );
                            $New_id = mysqli_insert_id($link);
                            $result = mysqli_query($link, "UPDATE `member_family` SET P_ID='$New_id' WHERE `ID`='" . $info3[$temp]['ID'] . "'");
                            if ($info['Member_Type'] == 2) {
                                for ($temp1 = 0; $temp1 < sizeof($info4); $temp1++) {
                                    $result = mysqli_query($link, "SELECT * from `event_apply` WHERE `Event_ID`='" . $info4[$temp1]['Event_Connect'] . "' and `Member_ID`='" . $info3[$temp]['Member_SID'] . "'");
                                    if ($info4[$temp1]['Check'] == true) {
                                        if ($result) {
                                            @$rowo = mysqli_fetch_assoc($result);
                                            if ($rowo['ID'] == null) {
                                                $result = mysqli_query($link,
                                                    "INSERT INTO `event_apply`( `Event_ID`, `Member_ID`,`Member_ID_F`)
                                 VALUES ('" . $info4[$temp1]['Event_Connect'] . "','" . $info3[$temp]['Member_SID'] . "','$org_row[Member_ID]')");
                                            }
                                        }

                                    } else {
                                        if ($result) {
                                            @$rowo = mysqli_fetch_assoc($result);
                                            if ($rowo['ID'] != null) {
                                                $result = mysqli_query($link,
                                                    "UPDATE `event_apply` SET `Del`=1 Where `Event_ID`='" . $info4[$temp1]['Event_Connect'] . "' and `Member_ID`='" . $info3[$temp]['Member_SID'] . "'");
                                            }
                                        }
                                    }
                                }
                            }

                        }

                    }
                    $output['result'] = true;
                    $output['return'] = true;

                    break;
                case "Testing":
                    $output['result'] = true;
                    $output['return'] = true;
                    //$output['info']=$info[0]['Event_Connect'];
                    $output['info'] = $info[1];
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
//print"<BR><BR><BR>";
//echo json_encode($rows);
