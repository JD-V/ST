<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
    ChromePhp::log("looged in");
  if(isset($_GET['CourseID']) && isset($_GET['action']) ) {
    ChromePhp::log("allowed again 1");
			$action = mysql_real_escape_string($_GET['action']);
			$CourseID = mysql_real_escape_string($_GET['CourseID']);
      if($action == 'retrive')
      echo CourseDuration($CourseID);
      exit();
   }
}

function CourseDuration($CourseID)
{
  ChromePhp::log("allowed in ar");
	return  GetCourseDuration($CourseID);
}
