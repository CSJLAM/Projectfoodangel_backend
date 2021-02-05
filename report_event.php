<?php

$data;
include "DBConnect.php";
if (isset($_REQUEST['event'])) {
    $event = $_REQUEST['event'];
    @$dateForm = $_REQUEST['from'];
    @$dateTo = $_REQUEST['to'];

    if($dateForm==null){
        $result1=mysqli_query($link, "SELECT `Start_Date`, `End_Date` FROM `event`WHERE Event_Connect ='$event'");
        $row = mysqli_fetch_assoc($result1);
        $dateForm= $row['Start_Date'];
        $dateTo= $row['End_Date'];
    }

    $result1 = mysqli_query($link, "SELECT Repeat_Week, Event_Name FROM `event` WHERE Event_Connect ='$event'");
    $row = mysqli_fetch_assoc($result1);
    $Line[] = "活動名稱：" . $row['Event_Name'];
    $data[] = $Line;
    $Line = null;
    $Line[] = "重複：" . $row['Repeat_Week'];
    $data[] = $Line;
    $Line = null;
    $Line[] = "日期由：".$dateForm."至：".$dateTo;
    $data[] = $Line;
    $Line = null;

    $Line[]="";
    $data[] = $Line;
    $Line = null;

    $weeks = split(',', $row['Repeat_Week']);


    $result = mysqli_query($link, "SELECT event_apply.*, member.Chinese_Name, IFNULL( member_family.Chinese_Name, member.Chinese_Name ) AS Fam_Chinese_Name, IFNULL( member_family.Octopus, member.Octopus ) AS Octopus FROM `event_apply` LEFT JOIN `member` ON( `event_apply`.`Member_ID_F` = `member`.`Member_ID` AND `member`.`P_ID` = 0 ) LEFT JOIN `member_family` ON( `event_apply`.`Member_ID` = `member_family`.`Member_SID` AND `member_family`.`P_ID` = 0 ) WHERE ( `event_apply`.`Event_ID` = '$event' AND `event_apply`.`Status` = 0 AND `event_apply`.`Del` = 0 ) ORDER BY `event_apply`.`Member_ID_F` ASC");

    if ($result) {
        $Line[] = "會員號碼";
        $Line2[] = "會員名字";
        while ($row = mysqli_fetch_assoc($result)) {
            $Line[] = $row['Member_ID'];
            $Line2[] = $row['Fam_Chinese_Name'];
        }
        $data[] = $Line;
        $data[] = $Line2;
        $Line = null;
        $Line2 = null;

        $dates = $dateForm;
        $D_Check = true;
        while ($dates != $dateTo || $dates == $dateTo && $D_Check == true) {
            for ($i = 0; $i < sizeof($weeks); $i++) {
                if (date("D", strtotime("$dates")) == $weeks[$i]) {
                    $Line[] = $dates;
                    $result1 = mysqli_query($link, "SELECT IFNULL( event_attendance.Status, 'Unchecked' ) AS Attend, event_apply.*, member.Chinese_Name, IFNULL( member_family.Chinese_Name, member.Chinese_Name ) AS Fam_Chinese_Name, IFNULL( member_family.Octopus, member.Octopus ) AS Octopus FROM `event_apply` LEFT JOIN `member` ON ( `event_apply`.`Member_ID_F` = `member`.`Member_ID` AND `member`.`P_ID` = 0 ) LEFT JOIN `member_family` ON ( `event_apply`.`Member_ID` = `member_family`.`Member_SID` AND `member_family`.`P_ID` = 0 ) LEFT JOIN `event_attendance` ON ( `event_apply`.`Member_ID` = `event_attendance`.`Member_ID` AND `event_attendance`.`Event_ID` = '$event' AND `event_attendance`.`Attend_Date` = '$dates' ) WHERE `event_apply`.`Event_ID` = '$event' AND `event_apply`.`Status` = 0 AND `event_apply`.`Del` = 0");
                    while ($row = mysqli_fetch_assoc($result1)) {
                        switch ($row['Attend']) {
                            case "Checked":
                                $Line[] = "C";
                                break;
                            case "Leave":
                                $Line[] = "L";
                                break;
                            case "Unchecked":
                            default:
                                $Line[] = " ";
                                break;
                        }

                    }
                    $data[] = $Line;
                    $Line = null;
                }
            }

            if ($dates == $dateTo) {
                $D_Check = false;
            } else {
                $dates = date('Y-m-d', strtotime("$dates +1day"));
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
}
