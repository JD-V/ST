<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) && isset($_GET['userID']) ) {

    $action = mysql_real_escape_string($_GET['action']);
    $userID = mysql_real_escape_string($_GET['userID']);

    if($action == 'Block') {
      BlockUser($userID);
    } else if ($action == 'UnBlock') {
      UnBlockUser($userID);
    }
  }
}

function BlockUser($ID) {
    $result = new \stdClass();
    $result->status = BlockUserByID($ID);
    print json_encode($result);
}

function UnBlockUser($ID) {
    $result = new \stdClass();
    $result->status = UnBlockUserByID($ID);
    print json_encode($result);
}
