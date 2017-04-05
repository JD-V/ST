<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() )
{
  if( isset($_GET['action']) ){

    $action = mysql_real_escape_string($_GET['action']);

    if($action != 'Retrive')
    {
      if( isset($_GET['AssociateID']) )
      {
        $AssociateID = mysql_real_escape_string($_GET['AssociateID']);

        if($action == 'Add')
          AddOrganizer($AssociateID);
        else if($action == 'Remove')
          RemoveOrganizer($AssociateID);
      }
    }
    else
      RetriveOrganizers();
   }
}

function RetriveOrganizers()
{
  $orgdata = getOrganizers();
  $user_array = array();
  chromephp::log('$orgdata = ' .$orgdata);
  if($orgdata)
  {
    if(mysql_num_rows($orgdata) >= 1)
    {
      while($org = mysql_fetch_assoc($orgdata))
      {
        $user_array[] = array('userid' => $org['userid'] , 'userName' => $org['userName']);
      }
    }
  }

  print json_encode($user_array);
}