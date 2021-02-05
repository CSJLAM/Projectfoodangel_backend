<?php

$data;
include "DBConnect.php";

           
if (isset($_REQUEST['member'])) {
                $member = $_REQUEST['member'];
               $result=mysqli_query($link,"SHOW COLUMNS FROM member");
               while ($row = mysqli_fetch_array($result)) { 
                $line[]=$row['Field'];
                
                }
                $data[]=$line;
                $result = mysqli_query($link, "SELECT * FROM `member` WHERE `Member_Type` ='$member' and P_ID=0 and end=0 ORDER by `Member_ID`");
             //   print "<br>SELECT `member`.`Chinese_Name`, `event_attendance`.*, `event`.`Event_Name`, `event`.`Repeat_Week` FROM `event_attendance`,`event_apply`,`event`, `member` WHERE `event_attendance`.`Attend_Date` >='$dateForm' AND `event_attendance`.`Attend_Date` <='$dateTo' and `event`.`Event_Connect`='$event' and `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_apply`.`Event_ID`='$event' and `event_attendance`.`Event_ID`='$event' and `member`.`Member_ID` = `event_apply`.`Member_ID` ORDER BY Member_ID <br>";
                if ($result) {
                    while ($row = mysqli_fetch_array($result)) { 

                        for($i=0;$i<sizeof($line);$i++){
                            $Line[]=$row[$line[$i]];
                        }
                        $data[]=$Line;
                        $Line=null;
                        
                         if($member=='2'){
                            $result1 = mysqli_query($link, "SELECT `Member_SID`,`Chinese_Name`,`English_Name`,`Gender`,`DOB`,`Career`,`Octopus` FROM `member_family` WHERE`Master_member`='$row[Member_ID]' and `P_ID`='0'");
                            while($row1 = mysqli_fetch_array($result1)){
                                $Line[]="";
                                $Line[]=$row1['Member_SID'];
                                $Line[]=$row1['Octopus'];
                                $Line[]="";
                                $Line[]=$row1['Chinese_Name'];
                                $Line[]=$row1['English_Name'];
                                $Line[]=$row1['Gender'];
                                $Line[]=$row1['DOB'];
                                $Line[]="";
                                $Line[]="";
                                $Line[]="";
                                $Line[]=$row1['Career'];
                            }
                            $data[]=$Line;
                            $Line=null;
                         }else{}
                    //$data[]=$row;
                    //print_r($data);
                    }
                }
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
          

?>
