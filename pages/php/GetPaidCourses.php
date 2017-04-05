<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
  if(isset($_GET['MemberID']) && isset($_GET['WalkIn']) ) {
    ChromePhp::log("allowed again");
			$MemberID = mysql_real_escape_string($_GET['MemberID']);
			$WalkIn = mysql_real_escape_string($_GET['WalkIn']);
      echo GetPaidCoursesList($MemberID,$WalkIn);
      exit();
   }
}

function GetPaidCoursesList($MemberID,$WalkIn)
{
  ChromePhp::log("allowed in ar");
  $cr_array = array();
	$courseList = GetMembersPaidForCourse($MemberID);
  if(mysql_num_rows($courseList) > 0)
  {
    ChromePhp::log("got course response");
    while($ShowData = mysql_fetch_assoc($courseList)) {
      $cr_array[] = $ShowData;
      ChromePhp::log("logging course");
      ChromePhp::log($ShowData);
    }
  }
  return json_encode($cr_array);
}
