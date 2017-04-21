<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) ) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
      if(isset($_GET['item']) && $_GET['item'] == "MaxSalesInvoice")
        print GetMaxSalesInoviceNumber();
      else
        RetriveProducts();
    } else if($action == 'save') {
      if(isset($_GET["FormData"])) {
        saveOrder($_GET["FormData"]);
      }
      else {
        print 'No updates to save';
      }
    }
  }
}

function RetriveProducts() {
  $products = getProductWithStocks();
  $product_array = array();
  if($products) {
    if(mysql_num_rows($products) >= 1) {
      while($product= mysql_fetch_assoc($products)) {
        $product_array[] = array('ProductID' => $product['ProductID'] ,'ProductName' => $product['ProductName'],
        'ProductTypeName' => $product['ProductTypeName'],'BrandName' => $product['BrandName'], 'CostPrice' => $product['CostPrice'],
        'Qty' => $product['Qty'],'SellingPrice' => $product['SellingPrice'] );
      }
    }
  }
  //print 'arr';
  print json_encode($product_array);
}

function saveOrder($FormData) {
  $FormData = json_decode($FormData);
  $order = new Order();
  $order->invoiceNumber = $FormData->InvoiceNo;
  $order->invoiceDate = $FormData->SalesInvoiceDate;
  $order->customerName = $FormData->CustomerName;
  $order->customerPhone = $FormData->CustomerPhone;
  $order->vehicleNumber = $FormData->VehicleNo;
  $order->subTotal = $FormData->SubTotal;
  $order->vatAmount = $FormData->VatAmount;
  $order->DiscountAmount = $FormData->DiscountAmount;
  $order->amountPaid = $FormData->TotalAmountPaid;
  $order->notes = $FormData->Notes;

  chromephp::log($order);
  if(AddNewSalesItem($order)) {
  $i = 0;

  foreach($FormData->Products as $product) {
    $ProductInventory = new ProductInventory();
    $ProductInventory->productID = $product->ProductID;
    $ProductInventory->productName = $product->ProductName;
    $ProductInventory->qty = $product->Qty;
    $ProductInventory->costPrice = $product->CostPrice;
    $ProductInventory->sellingPrice = $product->SellingPrice;
    
      $i += AddProductToSalesInvoice($ProductInventory,$order->invoiceNumber);
      chromephp::log($product); 
  }

  print $i;
  }
  else print '';
}
