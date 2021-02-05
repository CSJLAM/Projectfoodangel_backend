<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Food Angel Report Page</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <!-- <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();

          $.ajax({
            type: 'post',
            url: 'report1.php',
            data: $('form').serialize(),
            success: function () {
              alert('form was submitted');
            }
          });

        });

      });
    </script> -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    var dateFormat = "yy-mm-dd",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 3,
          dateFormat : "yy-mm-dd"
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        dateFormat  : "yy-mm-dd"
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });

    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }

      return date;
    }
  } );
  </script>
  <!-- <script>
function showHint() {
   
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "report1.php?jsonDoc="+document.getElementById("jsonDoc")+"&event="+document.getElementById("event")+"&from="+document.getElementById("from")+"&to="+document.getElementById("to"), true);
        xmlhttp.send();
    }
}
</script> -->

</head>
<body>
<?php
include "DBConnect.php";

if (isset($_REQUEST['jsonDoc'])) {
    $jsonDoc = $_REQUEST['jsonDoc'];

    $result = mysqli_query($link, "SELECT `staff`.`Staff_ID`, `staff`.`Name`, `staff`.`Staff_Hash`,`staff`.`Staff_Dept`,`login`.`Session` FROM `staff` ,`login` WHERE (`login`.`Session`='$jsonDoc' and `login`.`Staff_ID`=`staff`.`Staff_ID` and `login`.`Logout`='0' AND `staff`.`P_ID` ='0')");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            print "Staff ID: $row[Staff_ID] Staff Name $row[Name]<br>";
            ?>
 <h2>Report selection</h2>
 <!-- <form method="post" action="report1.php?<?php echo "jsonDoc=$jsonDoc"; ?>"> -->
 <form method="post" action="report_event.php?<?php echo "jsonDoc=$jsonDoc"; ?>">
 <!-- <form > -->
 <input type="hidden" id=jsonDoc value="<?php echo $jsonDoc; ?>">
        活動名稱(常駐）：
        <select name="event" id="event">
        <?php
$result = mysqli_query($link, "SELECT * FROM `event` WHERE `Event_Cate` =1 AND`Deleted` =0");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    print "<option value=$row[Event_Connect]>$row[Event_Name]</option>";
                }
            }
            ?>
        </select>
        <br><br>
        <label for="from">日期範圍：</label>
        <input type="text" id="from" name="from" required>
        <label for="to">至</label>
        <input type="text" id="to" name="to" required>
        <br><br>
        <input type="submit" name="submit" value="Submit">

</form>
<form method="post" action="report_event.php?<?php echo "jsonDoc=$jsonDoc"; ?>">
 <!-- <form > -->
 <input type="hidden" id=jsonDoc value="<?php echo $jsonDoc; ?>">
        活動名稱(一般）：
        <select name="event" id="event">
        <?php
$result = mysqli_query($link, "SELECT * FROM `event` WHERE `Event_Cate` !=1 AND`Deleted` =0");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    print "<option value=$row[Event_Connect]>$row[Event_Name]</option>";
                }
            }
            ?>
        </select>
        <br><br>
        <input type="submit" name="submit" value="Submit">

</form>
<form method="post" action="report_member.php?<?php echo "jsonDoc=$jsonDoc"; ?>">
 <!-- <form > -->
 
        會員表：
        <select name="member" id="member">
                    <option value="1">長者</option>
                    <option value="2">家庭</option>
              
        </select>
        <input type="submit" name="submit" value="Submit">

</form>
<?php
if (isset($_REQUEST['event'])) {
                $event = $_REQUEST['event'];
                $dateForm = $_REQUEST['from'];
                $dateTo = $_REQUEST['to'];

                $result = mysqli_query($link, "SELECT `member`.`Chinese_Name`, `event_attendance`.*, `event`.`Event_Name`, `event`.`Repeat_Week` FROM `event_attendance`,`event_apply`,`event`, `member` WHERE `event_attendance`.`Attend_Date` >='$dateForm' AND `event_attendance`.`Attend_Date` <='$dateTo' and `event`.`Event_Connect`='$event' and `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_apply`.`Event_ID`='$event' and `event_attendance`.`Event_ID`='$event' and `member`.`Member_ID` = `event_apply`.`Member_ID` ORDER BY Member_ID");
                print "<br>SELECT `member`.`Chinese_Name`, `event_attendance`.*, `event`.`Event_Name`, `event`.`Repeat_Week` FROM `event_attendance`,`event_apply`,`event`, `member` WHERE `event_attendance`.`Attend_Date` >='$dateForm' AND `event_attendance`.`Attend_Date` <='$dateTo' and `event`.`Event_Connect`='$event' and `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_apply`.`Event_ID`='$event' and `event_attendance`.`Event_ID`='$event' and `member`.`Member_ID` = `event_apply`.`Member_ID` ORDER BY Member_ID <br>";
                if ($result) {
                    $result1 = mysqli_query($link, "SELECT `member`.`Chinese_Name`, `event_attendance`.*, `event`.`Event_Name`, `event`.`Repeat_Week` FROM `event_attendance`,`event_apply`,`event`, `member` WHERE `event_attendance`.`Attend_Date` >='$dateForm' AND `event_attendance`.`Attend_Date` <='$dateTo' and `event`.`Event_Connect`='$event' and `event_apply`.`Member_ID`=`event_attendance`.`Member_ID` and `event_apply`.`Event_ID`='$event' and `event_attendance`.`Event_ID`='$event' and `member`.`Member_ID` = `event_apply`.`Member_ID` ORDER BY Member_ID");
                    $row = mysqli_fetch_assoc($result1);
                    $weeks = split('[,]', $row['Repeat_Week']);
                    for ($a = 0; $a < sizeof($weeks); $a++) {
                        print "$weeks[$a]";
                    }
                    print "<br>$dateForm<br>";
                    print date('Y-m-d', strtotime("$dateForm +1day "));
                    print date('D/w', strtotime("$dateForm +1day "));
                    ?>
        <table border="1">
        <tr>
        <th>會員號碼</th><th>姓名</th>
        <?php
$selector = date('Y-m-d', strtotime("$dateForm"));

                    $end = date('Y-m-d', strtotime("$dateTo +1day"));

                    while ($selector != $end) {
                        for ($repeatWeek = 0; $repeatWeek < sizeof($weeks); $repeatWeek++) {
                            if ($weeks[$repeatWeek] == date('D', strtotime("$selector"))) {
                                print "<th>$selector</th>";
                            }
                        }
                        $selector = date('Y-m-d', strtotime("$selector +1day"));
                    }
                    ?>
        </tr>
        <?php
$MemID = "";
                    while ($row = mysqli_fetch_assoc($result)) {

                        if ($row['Member_ID'] != $MemID) {
                            while ($selector != $end) {
                                for ($repeatWeek = 0; $repeatWeek < sizeof($weeks); $repeatWeek++) {
                                    if ($weeks[$repeatWeek] == date('D', strtotime("$selector"))) {

                                        print "<td></td>";

                                    }
                                }
                                $selector = date('Y-m-d', strtotime("$selector +1day"));
                            }
                            ?>
            <tr>
            <?php
print "<td>$row[Member_ID]</td><td>$row[Chinese_Name]</td>";
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
                                                print "<td>✓</td>";
                                                break;
                                            case "Leave":
                                                print "<td>✖</td>";
                                                break;
                                            case "Unchecked":
                                            default:
                                                print "<td></td>";
                                                break;
                                        }
                                        $outloop = true;

                                    } else {
                                        print "<td></td>";
                                    }
                                }
                            }
                            $selector = date('Y-m-d', strtotime("$selector +1day"));
                        }

                        if ($outloop == false) {
                            ?>
         </tr>
         <?php
}
                    }
                    while ($selector != $end) {
                        for ($repeatWeek = 0; $repeatWeek < sizeof($weeks); $repeatWeek++) {
                            if ($weeks[$repeatWeek] == date('D', strtotime("$selector"))) {

                                print "<td></td>";

                            }
                        }
                        $selector = date('Y-m-d', strtotime("$selector +1day"));
                    }
                    print " </tr>";
                    ?>
        </table>
        <?php
}
            }
            ?>
   <?php

        } else {
            echo "nologin";
        }
    }
}

?>
</body>