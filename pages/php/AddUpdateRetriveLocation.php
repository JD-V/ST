<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) ) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
      RetriveLocations();
    }
    else if ($action == 'Remove') {
      if( isset($_GET['LocationId']) ) {
        $LocationId = mysql_real_escape_string($_GET['LocationId']);
        RemoveLocation($LocationId);
      }
    }
    else if($action == 'save') {
      if(isset($_GET["LocArr"])) {
        saveLocations($_GET["LocArr"]);
      }
      else {
        print 'No updates to save';

      }
    }
  }
}

function RetriveLocations() {
  $locdata = getLocations();
  $location_array = array();
  if($locdata) {
    if(mysql_num_rows($locdata) >= 1) {
      while($loc = mysql_fetch_assoc($locdata)) {
        $location_array[] = array('LocId' => $loc['LocationID'] ,'LocName' => $loc['LocationName']);
      }
    }
  }
  //print 'arr';
  print json_encode($location_array);
}

function saveLocations($locArray) {
  $locArray  = json_decode($locArray);
  chromephp::log("LocArray to be updated");
  chromephp::log($locArray);
  $i = 0;
  foreach($locArray as $loc) { //foreach element in $arr
    if(property_exists($loc, 'LocId')) {
      //Fire update Query
      $i += UpdateLocation($loc);
      chromephp::log("Loc Id " . $loc->LocId);
    }
    else {
      //Fire Insert Query
      $i += AddLocation($loc);
      chromephp::log("Loc Name " . $loc->LocName);
    }
  }

  print $i;
}
