<?php
require '_connect.php';
require '_core.php';

if(isLogin())
{
  if(isset($_GET['pwd']) && isset($_GET['for'])){
    ChromePhp::log("inside");
      if($_GET['for'] == 'Attendee')
        {
            AuthenticatePassword($_GET['pwd']);

        }

   }
}

function AuthenticatePassword($password)
{
    $userid = $_SESSION['userid'];
    $userDetail = mysql_query("SELECT * from user WHERE userid='$userid'");
    ChromePhp::log($password);

    if(mysql_num_rows($userDetail) == 1)
    {
      ChromePhp::log("got data");
      $user = mysql_fetch_assoc($userDetail);
      if($user['userpwd'] == $password)
      {ChromePhp::log("true");  echo '1'; }
      else
      {ChromePhp::log("false");  echo '0';}
    }
}
