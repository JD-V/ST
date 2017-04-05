<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
  if(isset($_GET['WalkIn']) && isset($_GET['action']) ) {
      $action = mysql_real_escape_string($_GET['action']);
			$WalkIn = mysql_real_escape_string($_GET['WalkIn']);

        if($action == 'retrive' ){
          echo GetMembersList($WalkIn);
          exit();
      }
   }
}

function GetMembersList($WalkIn)
{
  $cr_array = array();
	$MembersList = GetMemberNamesList($WalkIn);
  if(mysql_num_rows($MembersList) > 0) {
    while($Member = mysql_fetch_assoc($MembersList)) {
      $cr_array[] = $Member;//['Name'] .' | ********' .substr($Member['IDNumber'],8);
    }
  }
  return json_encode($cr_array);
}
