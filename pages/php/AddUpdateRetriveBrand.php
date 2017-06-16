<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) ) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
      RetriveBrands();
    }
    else if ($action == 'Remove') {
      if( isset($_GET['BrandID']) ) {
        $BrandID = mysql_real_escape_string($_GET['BrandID']);
        RemoveBrand($BrandID);
      }
    }
    else if($action == 'save') {
      if(isset($_GET["BrandArr"])) {
      //chromephp::log($_GET["BrandArr"]);
        saveBrands($_GET["BrandArr"]);
      }
      else {
        print 'No updates to save';

      }
    }
  }
}

function RetriveBrands() {
  $Branddata = getBrands();
  $Brand_array = array();
  if($Branddata) {
    if(mysql_num_rows($Branddata) >= 1) {
      while($Brand = mysql_fetch_assoc($Branddata)) {
        $Brand_array[] = array('BrandID' => $Brand['BrandID'] ,'BrandName' => $Brand['BrandName']);
      }
    }
  }
  chromephp::log($Brand_array);
  //print 'arr';
  print json_encode($Brand_array);
}

function saveBrands($BrandArray) {
  $BrandArray  = json_decode($BrandArray);
  $i=0;
  foreach($BrandArray as $Brand) { //foreach element in $arr
    if(property_exists($Brand, 'BrandID')) {
      //Fire update Query
      $i += UpdateBrand($Brand);
      // chromephp::log("Brand Id " . $Brand->BrandID);
    }
    else {
      //Fire Insert Query
      $i += AddBrand($Brand);
      // chromephp::log("Brand Name " . $Brand->BrandName);
    }
  }

  print $i;
}
