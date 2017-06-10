<?php
require '_connect.php';
require '_core.php';
if(isLogin()) {
  if( isset($_GET['action']) & isset($_GET['item']) ) {
    $action = FilterInput($_GET['action']);
    $item = FilterInput($_GET['item']);
    if($action == 'Retrive' && $item == 'InventoryProduct') {
      if(isset($_GET['BrandID']) && $_GET['Size'] && $_GET['Pattern'] && $_GET['TypeID'] ) {
        $ProductInventory = new ProductInventory();
        $ProductInventory->productID = NULL;
        $ProductInventory->brandID = FilterInput($_GET['BrandID']);
        $ProductInventory->productSize = FilterInput($_GET['Size']);
        $ProductInventory->productPattern = FilterInput($_GET['Pattern']);
        $ProductInventory->productTypeID = FilterInput($_GET['TypeID']);
        print RetriveProductID($ProductInventory);
      }
    }
  }
}

function RetriveProductID(&$ProductInventory) {
  GetInventoryProductID($ProductInventory);
  print json_encode($ProductInventory);
}
