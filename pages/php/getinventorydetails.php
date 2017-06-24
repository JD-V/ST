<?php
require '_connect.php';
require '_core.php';

if(isLogin()) {

  if( isset($_GET['action']) && isset($_GET['item']) ) {

    $action = mysql_real_escape_string($_GET['action']);
    $item = mysql_real_escape_string($_GET['item']);

    if($action == 'retrive') {
        if($item == 'inventoryproducts')
            RetriveInventoryProducts();
        else if($item == 'producttypes')
            RetriveProductTypes();
    }
  }
}

function RetriveInventoryProducts() {
  $productInventory = GetProductInventory2();
  print json_encode($productInventory);
}

function RetriveProductTypes() {
  $prodcutTypes = GetProdcutTypes2();
  print json_encode($prodcutTypes);
}