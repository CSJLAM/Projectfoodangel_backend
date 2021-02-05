<?php

$data;
include "DBConnect.php";

// if (isset($_REQUEST['jsonDoc'])) {
//     $jsonDoc = $_REQUEST['jsonDoc'];

//     $result = mysqli_query($link, "SELECT `staff`.`Staff_ID`, `staff`.`Name`, `staff`.`Staff_Hash`,`staff`.`Staff_Dept`,`login`.`Session` FROM `staff` ,`login` WHERE (`login`.`Session`='$jsonDoc' and `login`.`Staff_ID`=`staff`.`Staff_ID` and `login`.`Logout`='0' AND `staff`.`P_ID` ='0')");
//   //  if ($result) {
//         $row = mysqli_fetch_assoc($result);
//         //if ($row) {
//             //print "Staff ID: $row[Staff_ID] Staff Name $row[Name]<br>";
            
// $result = mysqli_query($link, "SELECT * FROM `event` WHERE `Event_Cate` =1 AND`Deleted` =0");
//             // if ($result) {
//             //     while ($row = mysqli_fetch_assoc($result)) {
//             //        // print "<option value=$row[Event_Connect]>$row[Event_Name]</option>";
//             //     }
//             // }
           
if (isset($_REQUEST['event'])) {
                $event = $_REQUEST['event'];
                $dateForm = $_REQUEST['from'];
                $dateTo = $_REQUEST['to'];

                $result = mysqli_query($link, "SELECT `member`.`Chinese_Name`, `event_attendance`.*, `event`.`Event_Name`, `event`.`Repeat_Week` FROM `event_attendance`,`event_apply`,`event`, `member` WHERE `event_attendance`.`Attend_Date` >='$dateForm' AND `event_attendance`.`Attend_Date` <='$dateTo' and `event`.`Event_Connect`='$event' and `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_apply`.`Event_ID`='$event' and `event_attendance`.`Event_ID`='$event' and `member`.`Member_ID` = `event_apply`.`Member_ID` ORDER BY Member_ID");
             //   print "<br>SELECT `member`.`Chinese_Name`, `event_attendance`.*, `event`.`Event_Name`, `event`.`Repeat_Week` FROM `event_attendance`,`event_apply`,`event`, `member` WHERE `event_attendance`.`Attend_Date` >='$dateForm' AND `event_attendance`.`Attend_Date` <='$dateTo' and `event`.`Event_Connect`='$event' and `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_apply`.`Event_ID`='$event' and `event_attendance`.`Event_ID`='$event' and `member`.`Member_ID` = `event_apply`.`Member_ID` ORDER BY Member_ID <br>";
                if ($result) {
                    $result1 = mysqli_query($link, "SELECT `member`.`Chinese_Name`, `event_attendance`.*, `event`.`Event_Name`, `event`.`Repeat_Week` FROM `event_attendance`,`event_apply`,`event`, `member` WHERE `event_attendance`.`Attend_Date` >='$dateForm' AND `event_attendance`.`Attend_Date` <='$dateTo' and `event`.`Event_Connect`='$event' and `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_apply`.`Event_ID`='$event' and `event_attendance`.`Event_ID`='$event' and `member`.`Member_ID` = `event_apply`.`Member_ID` ORDER BY Member_ID");
                    $row = mysqli_fetch_assoc($result1);
                    $weeks = split('[,]', $row['Repeat_Week']);
                    // for ($a = 0; $a < sizeof($weeks); $a++) {
                    //     print "$weeks[$a]";
                    // }
                    // print "<br>$dateForm<br>";
                    // print date('Y-m-d', strtotime("$dateForm +1day "));
                    // print date('D/w', strtotime("$dateForm +1day "));
                  
        $Line[]="會員號碼";
        $Line[]="姓名";
$selector = date('Y-m-d', strtotime("$dateForm"));

                    $end = date('Y-m-d', strtotime("$dateTo +1day"));

                    while ($selector != $end) {
                        for ($repeatWeek = 0; $repeatWeek < sizeof($weeks); $repeatWeek++) {
                            if ($weeks[$repeatWeek] == date('D', strtotime("$selector"))) {
                             //   print "<th>$selector</th>";
                                $Line[]=$selector;
                            }
                        }
                        $selector = date('Y-m-d', strtotime("$selector +1day"));
                    }
                   
$MemID = "";
                    while ($row = mysqli_fetch_assoc($result)) {

                        if ($row['Member_ID'] != $MemID) {
                            while ($selector != $end) {
                                for ($repeatWeek = 0; $repeatWeek < sizeof($weeks); $repeatWeek++) {
                                    if ($weeks[$repeatWeek] == date('D', strtotime("$selector"))) {

                                       // print "<td></td>";
                                        $Line[]=" ";

                                    }
                                }
                                $selector = date('Y-m-d', strtotime("$selector +1day"));
                            }
                        
            $data[]=$Line;
            $Line=null;
//print "<td>$row[Member_ID]</td><td>$row[Chinese_Name]</td>";
$Line[]=$row['Member_ID'];
$Line[]=$row['Chinese_Name'];
                            $MemID = $row['Member_ID'];
                            $selector = date('Y-m-d', strtotime("$dateForm"));
                        }
                        $outloop = false;
                        while ($selector != $end && $outloop == false) {
                            for ($repeatWeek = 0; $repeatWeek < sizeof($weeks); $repeatWeek++) {
                                if ($weeks[$repeatWeek] == date('D', strtotime("$selector"))) {
                                    if ($row['Attend_Date'] == $selector) {
                                        switch ($row['Status']) {
                                            case "Checked":
                                                //print "<td>✓</td>";
                                                $Line[]="C";
                                                break;
                                            case "Leave":
                                               // print "<td>✖</td>";
                                                $Line[]="L";
                                                break;
                                            case "Unchecked":
                                            default:
                                                //print "<td></td>";
                                                $Line[]=" ";
                                                break;
                                        }
                                        $outloop = true;

                                    } else {
                                        //print "<td></td>";
                                        $Line[]=" ";
                                    }
                                }
                            }
                            $selector = date('Y-m-d', strtotime("$selector +1day"));
                        }

                        if ($outloop == false) {
                          
}
                    }
                    while ($selector != $end) {
                        for ($repeatWeek = 0; $repeatWeek < sizeof($weeks); $repeatWeek++) {
                            if ($weeks[$repeatWeek] == date('D', strtotime("$selector"))) {

                               // print "<td></td>";
                                $Line[]="C";
                            }
                        }
                        $selector = date('Y-m-d', strtotime("$selector +1day"));
                    }
                   // print " </tr>";
                    $data[]=$Line;
                    // print"~~~~~~~~~~~";
                     //print_r($data);
                    // print"~~~~~~~~~~~";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="sample.csv"');
$fp = fopen('php://output', 'wb');
//header('Content-type: text/tab-separated-values');
//header("Content-Disposition: attachment;filename=bingproductfeed.txt");
echo "\xEF\xBB\xBF";
foreach ($data as $datas) {
    //$datas=str_replace('"','',$datas);
    //$datas=trim($fields,'"');
    //$val = implode(",",$datas);
    //echo implode("\t",$datas);
    //print "\n";
     //$val = explode(",", $datas);
     fputcsv($fp, $datas);
     
}
fclose($fp);
                 
}
            }
            

        // } else {
        //     echo "nologin";
        // }
   // }
//}

?>
