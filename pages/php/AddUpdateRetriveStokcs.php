<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) ) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
      RetriveStocks();
    }
    else if($action == 'save') {
      if(isset($_GET["ItemArr"])) { 
        SaveStocks($_GET["ItemArr"]);
      }
      else {
        print 'No updates to save';
      }
    }
  }
}

function RetriveStocks() {
  $stockdata = GetProductStocks();
  $stockdata_array = array();
  if($stockdata) {
    if(mysql_num_rows($stockdata) >= 1) {
      while($stock = mysql_fetch_assoc($stockdata)) {
        $stockdata_array[] = array('ProductID' => $stock['ProductID'] ,'ProductName' => $stock['ProductName'] , 'Qty' => $stock['Qty'] == NULL ? 0 : $stock['Qty'] );
      }
    }
  }
  print json_encode($stockdata_array);
}

function SaveStocks($stockArray) {
  $stockArray  = json_decode($stockArray);
  chromephp::log("stockArray to be updated");
  chromephp::log($stockArray);
  $i = 0;
  foreach($stockArray as $stock) {
    //Fire Add stock entry Query
    $i += AddStockEntry($stock);
    chromephp::log("Item Id " . $stock->ProductID); 
  }

  print $i;
}
