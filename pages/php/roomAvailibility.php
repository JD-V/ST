<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
    ChromePhp::log("looged in 1");
  if(isset($_GET['roomID']) && isset($_GET['action']) && isset($_GET['dateValue']) && isset($_GET['fromTime']) && isset($_GET['tillTime']) ) {
    ChromePhp::log("allowed again 1");
			$action = mysql_real_escape_string($_GET['action']);
			$roomID = mysql_real_escape_string($_GET['roomID']);
      $dateValue = mysql_real_escape_string($_GET['dateValue']);
      $fromTime = mysql_real_escape_string($_GET['fromTime']);
      $tillTime = mysql_real_escape_string($_GET['tillTime']);
      ChromePhp::log("looged in 2");
      if($action == 'retrive') {
        ChromePhp::log("looged in 3");
        $from = $dateValue. ' ' . $fromTime;
        $to = $dateValue. ' ' . $tillTime;

        $dateTime1 =  DateTime::createFromFormat('m/d/Y H:i:s', $from);
        $dateTime2 =  DateTime::createFromFormat('m/d/Y H:i:s', $to);

        $dt1 = $dateTime1->format('Y-m-d H:i:s');
        $dt2 = $dateTime2->format('Y-m-d H:i:s');
        ChromePhp::log("looged in 4");
        ChromePhp::log($roomID);
        ChromePhp::log($dt1);
        ChromePhp::log($dt2);
        echo RoomAvailibility($roomID,$dt1,$dt2);
      }
      exit();
   }
}

function RoomAvailibility($roomID,$dt1,$dt2) {

  ChromePhp::log("allowed in ar");
	echo  CheckRoomAvailibility($roomID,$dt1,$dt2);
}
