<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) ) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
      RetriveServiceable();
    }
    else if ($action == 'Remove') {
      if( isset($_GET['ItemID']) ) {
        $ItemID = mysql_real_escape_string($_GET['ItemID']);
        if(DeleteServiceable($ItemID))
            print 1;
        else
            print 0;
      }
    }
    else if($action == 'save') {
      if(isset($_GET["ItemArr"])) { 
        saveServiceables($_GET["ItemArr"]);
      }
      else {
        print 'No updates to save';

      }
    }
  }
}

function RetriveServiceable() {
  $srvdata = getServiceable(0);
  $serviceable_array = array();
  if($srvdata) {
    if(mysql_num_rows($srvdata) >= 1) {
      while($srv = mysql_fetch_assoc($srvdata)) {
        $serviceable_array[] = array('ItemID' => $srv['ItemID'] ,'Item' => $srv['Item'] , 'Price' => $srv['Price']);
      }
    }
  }
  print json_encode($serviceable_array);
}

function saveServiceables($srvArray) {
  $srvArray  = json_decode($srvArray);
  chromephp::log("srvArray to be updated");
  chromephp::log($srvArray);
  $i = 0;
  foreach($srvArray as $srv) { //foreach element in $arr
    if(property_exists($srv, 'ItemID')) {
      //Fire update Query
      $i += UpdateServiceable($srv);
      chromephp::log("Item Id " . $srv->ItemID);
    }
    else {
      //Fire Insert Query
      $i += AddServiceable($srv);
      chromephp::log("Item Name " . $srv->Item);
    }
  }

  print $i;
}

function DeleteServiceable($ItemID) {
     return MarkServiceableDepricate($ItemID);    
}