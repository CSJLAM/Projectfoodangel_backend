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
        if ($passcode == "CheckME") {
            include "DBConnect.php";
            //$output['whereiam']="here1";
            switch ($Function) {
                case "Default_Login":
                    $info2 = md5($info2);
                    $aday = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT `staff`.`Staff_ID` FROM `staff`WHERE `Username`='$info' and `Password`='$info2' and `P_ID`=0 and `Staff_Join`<='$aday' and (`Staff_End`>='$aday' or `Staff_End`='0000-00-00') ");
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        if ($row) {
                            $info2 = $row['Staff_ID'];
                            $info = MD5(date("Y-m-d H:i:s"));
                            $result = mysqli_query($link, "INSERT INTO `login` ( `Staff_ID`, `Session`, `Logout`) VALUES ( '$info2', '$info', '0')");
                        }
                    }

                case "Hash_Login":
                    $result = mysqli_query($link, "SELECT `staff`.`Staff_ID`, `staff`.`Name`, `staff`.`Staff_Hash`,`staff`.`Staff_Dept`,`login`.`Session` FROM `staff` ,`login` WHERE (`login`.`Session`='$info' and `login`.`Staff_ID`=`staff`.`Staff_ID` and `login`.`Logout`='0' AND `staff`.`P_ID` ='0')");
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        if ($row) {
                            $output['Staff'] = $row;
                            $output['result'] = true;
                        }
                    }
                    break;

                case "My_Logout":
                    $result = mysqli_query($link, "UPDATE `login` SET `Logout` = '1' WHERE `login`.`Session` = '$info'");
                    $output['result'] = true;
                    break;

                case "Change_PW":
                    $info2 = md5($info2);
                    $info3 = md5($info3);
                    $result = mysqli_query($link, "SELECT * FROM `staff` where `Staff_ID` = '$info' and `Password`='$info2' and `P_ID`='0' ");
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        

                        if ($row) {
                            $result = mysqli_query($link, "UPDATE `login` SET `Logout` = '1' WHERE `login`.`staff_ID` = '$info'");

                            $result = mysqli_query($link, "INSERT INTO `staff`" .
                                "( `Staff_ID`, `Staff_Hash`, `Username`, `Name`, `Password`, `Staff_Dept`, `Staff_Join`, `Staff_End`, `Create_By`) VALUES " .
                                "( '$row[Staff_ID]','$row[Staff_Hash]','$row[Username]','$row[Name]','$info3','$row[Staff_Dept]','$row[Staff_Join]','$row[Staff_End]','$info')");
                            $New_id = mysqli_insert_id($link);
                            
                            $result = mysqli_query($link, "UPDATE `staff` SET `P_ID` = '$New_id' WHERE `staff`.`ID` = '$row[ID]' and `staff`.`P_ID` = '0'");
                            
                                               
                            $info2 = $row['Staff_ID'];
                            $info = MD5(date("Y-m-d H:i:s"));
                            $result = mysqli_query($link, "INSERT INTO `login` ( `Staff_ID`, `Session`, `Logout`) VALUES ( '$info2', '$info', '0')");
                            //$result = mysqli_query($link, "SELECT `staff`.`Staff_ID`, `staff`.`Name`, `staff`.`Staff_Hash`,`staff`.`Staff_Dept` FROM `staff` LEFT JOIN `login` ON (`login`.`Session`='$info' and `login`.`Staff_ID`=`staff`.`Staff_ID` and `login`.`Logout`='0')");
                            $result = mysqli_query($link, "SELECT `staff`.`Staff_ID`, `staff`.`Name`, `staff`.`Staff_Hash`,`staff`.`Staff_Dept`,`login`.`Session` FROM `staff` ,`login` WHERE (`login`.`Session`='$info' and `login`.`Staff_ID`=`staff`.`Staff_ID` and `login`.`Logout`='0' AND `staff`.`P_ID` ='0')");
                            if ($result) {
                                $row = mysqli_fetch_assoc($result);
                                if ($row) {
                                    $output['Staff'] = $row;
                                    $output['result'] = true;
                                }
                            }

                        }
                    }
                    break;

                case "Show_Staff":
                    $A_date = date("Y-m-d");
                    $result = mysqli_query($link, "SELECT `ID`,`Staff_ID`,`Name`,`Staff_Hash` from staff where `P_ID`='0' and (`Staff_End` >= '$A_date' or `Staff_End` is null or `Staff_End` = '0000-00-00')");
                    while ($row = mysqli_fetch_assoc($result)) {
                        //$output['some'] = $row;
                        $output['info'][] = $row;
                        $rows[] = $row;
                        $output['result'] = true;
                    }
                    break;
                case "Show_Dept":

                    $result = mysqli_query($link, "SELECT `ID`,`Dept_Name`,`Dept_Hash`,`Deleted` from dept where `P_ID`='0' and `Deleted`='0'");
                    while ($row = mysqli_fetch_assoc($result)) {
                        //$output['some'] = $row;
                        $output['info'][] = $row;
                        $rows[] = $row;
                        $output['result'] = true;
                    }
                    break;

                case "Add_Staff":
                    $s_h = MD5(date("Y-m-d H:i:s"));
                    $info['Password'] = MD5($info['Password']);
                    $result = mysqli_query($link, "INSERT INTO `staff`( `Staff_ID`,`Staff_Hash`, `Username`, `Name`, `Password`, `Staff_Dept`, `Staff_Join`, `Staff_End`, `Create_By` )
                SELECT MAX( ID ) + 1, '$s_h', '$info[Username]','$info[Name]','$info[Password]','$info[Staff_Dept]','$info[Staff_Join]','$info[Staff_End]','$info2' FROM `staff`");
                    $New_id = mysqli_insert_id($link);
                    if ($New_id > 0) {
                        $output['result'] = true;
                    }

                    break;
                case "Edit_Staff":
                    $result = mysqli_query($link, "SELECT  `ID`, `Staff_ID`, `Staff_Hash`, `Username`, `Name`, `Password`, `Staff_Dept`, `Staff_Join`, `Staff_End` from staff where `P_ID`='0'  and `Staff_Hash`='$info'");
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);

                        if ($row) {
                            if (strlen($info2['Password']) > 6) {
                                $result = mysqli_query($link, "UPDATE `login` SET `Logout` = '1' WHERE `login`.`staff_ID` = '$row[Staff_ID]'");
                                $info2['Password'] = MD5($info2['Password']);
                            } else {
                                $info2['Password'] = $row['Password'];
                            }
                            $result = mysqli_query($link, "INSERT INTO `staff`" .
                                "( `Staff_ID`, `Staff_Hash`, `Username`, `Name`, `Password`, `Staff_Dept`, `Staff_Join`, `Staff_End`, `Create_By`) VALUES " .
                                "( '$row[Staff_ID]','$row[Staff_Hash]','$info2[Username]','$info2[Name]','$info2[Password]','$info2[Staff_Dept]','$info2[Staff_Join]','$info2[Staff_End]','$info3')");
                            $New_id = mysqli_insert_id($link);

                            $result = mysqli_query($link, "UPDATE `staff` SET `P_ID` = '$New_id' WHERE `staff`.`ID` = '$row[ID]'");
                            if ($result) {
                                $output['data']=$New_id;
                                $output['result'] = true;

                            }
                        }
                    }
                    break;
                case "Load_Staff_Info":
                $result = mysqli_query($link, "SELECT  `ID`, `Staff_ID`, `Staff_Hash`, `Username`, `Name`,`Staff_Dept`, `Staff_Join`, `Staff_End` from staff where `P_ID`='0' and `Staff_Hash`='$info'");
                // $output['data']="SELECT  `ID`, `Staff_ID`, `Staff_Hash`, `Username`, `Name`,`Staff_Dept`, `Staff_Join`, `Staff_End` from staff where `P_ID`='0' and `Staff_Hash`='$info'";
                if($result){
              // while ($row = mysqli_fetch_assoc($result)) {
                    $row = mysqli_fetch_assoc($result);
                    $output['info'] = $row;
                    
                    $output['result'] = true;
                }
                //}
                break;
                case "Add_Dept":
                    $info2 = md5($info2);
                    $result = mysqli_query($link, "INSERT INTO `dept`" .
                        "( `Dept_Name`, `Dept_Hash`, `Create_By`) VALUES " .
                        "( '$info','$info2','$info3')");
                    $New_id = mysqli_insert_id($link);

                    // $result = mysqli_query($link,"UPDATE `staff` SET `P_ID` = '$New_id' WHERE `staff`.`ID` = '$row[ID]'");
                    if ($New_id > 0) {
                        // $row = mysqli_fetch_assoc($result);
                        // if ($row) {
                        //     $output['dept'] = $row;
                        $output['result'] = true;
                        // }
                    }
                    break;
                case "Edit_Dept":
                    $result = mysqli_query($link, "SELECT `ID`,`Dept_Name`,`Dept_Hash` from dept where `P_ID`='0' and `Deleted`='0' and `Dept_Hash`='$info'");
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                    }

                    $result = mysqli_query($link, "INSERT INTO `dept`" .
                        "( `Dept_Name`, `Dept_Hash`, `Create_By`) VALUES " .
                        "( '$info2','$row[Dept_Hash]','$info3')");
                    $New_id = mysqli_insert_id($link);

                    $result = mysqli_query($link, "UPDATE `dept` SET `P_ID` = '$New_id' WHERE `dept`.`ID` = '$row[ID]'");
                    if ($New_id > 0) {
                        // $row = mysqli_fetch_assoc($result);
                        // if ($row) {
                        //     $output['dept'] = $row;
                        $output['result'] = true;
                        // }
                    }
                    break;
                case "Delete_Dept":
                    $result = mysqli_query($link, "SELECT `ID`,`Dept_Name`,`Dept_Hash` from dept where `P_ID`='0' and `Deleted`='0' and `Dept_Hash`='$info'");
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                    }

                    $result = mysqli_query($link, "INSERT INTO `dept`" .
                        "( `Dept_Name`, `Dept_Hash`, `Create_By`,`Deleted`) VALUES " .
                        "( '$row[Dept_Name]','$row[Dept_Hash]','$info3','1')");
                    $New_id = mysqli_insert_id($link);

                    $result = mysqli_query($link, "UPDATE `dept` SET `P_ID` = '$New_id' WHERE `dept`.`ID` = '$row[ID]'");
                    if ($New_id > 0) {
                        // $row = mysqli_fetch_assoc($result);
                        // if ($row) {
                        //     $output['dept'] = $row;
                        $output['result'] = true;
                        // }
                    }
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
