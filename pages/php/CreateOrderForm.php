<?php
require '_connect.php';
require '_core.php';
if(isLogin() && isAdmin() ) {
  if( isset($_GET['action']) ) {
    $action = mysql_real_escape_string($_GET['action']);
    if($action == 'Retrive') {
      if(isset($_GET['item']) && $_GET['item'] == "MaxSalesInvoice") {
        print GetMaxSalesInoviceNumber();
      }
      else if ( isset($_GET['BrandID'])) {
          RetriveProducts($_GET['BrandID']);
      }
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
function RetriveProducts($BrandID) {
  $products = getProductWithStocks($BrandID);
  $product_array = array();
  if($products) {
    if(mysql_num_rows($products) >= 1) {
      while($product= mysql_fetch_assoc($products)) {
        $product_array[] = array('ProductID' => $product['ProductID'] ,'ProductSize' => $product['ProductSize'], 'ProductPattern' => $product['ProductPattern'], 
        'ProductDisplay'=> $product['ProductSize'] . ' ' . $product['ProductPattern'], 'ProductTypeName' => $product['ProductTypeName'],'BrandName' => $product['BrandName'], 'CostPrice' => $product['CostPrice'],
        'Qty' => $product['Qty'],'MinSellPrice' => $product['MinSellPrice'], 'MaxSellPrice' => $product['MaxSellPrice'] );
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
  
  $date = date_create($FormData->SalesInvoiceDate); 
  $order->invoiceDate = date_format($date, 'Y-m-d H:i');
  $order->customerName = $FormData->CustomerName;
  $order->customerPhone = $FormData->CustomerPhone;
  $order->vehicleNumber = $FormData->VehicleNo;
  $order->vehicleMileage = $FormData->VehicleMileage;
  $order->basic = $FormData->BasicAmount;
  $order->vatAmount = $FormData->VatAmount;
  $order->discount = $FormData->DiscountAmount;
  $order->amountPaid = $FormData->TotalAmountPaid;
  $order->paymentMethod = $FormData->PaymentMethod;
  $order->notes = $FormData->Notes;
  $order->address = $FormData->Address;
   if( $order->paymentMethod == '3') {
        $dateC = date_create($FormData->ChequeDate); 
        $order->chequeDate = date_format($dateC, 'Y-m-d H:i');
        $order->chequeNo = $FormData->ChequeNo;
      }
  chromephp::log($order);
  if(AddNewSalesItem($order)) {
  $i = 0;
  foreach($FormData->Products as $product) {
      $ProductInventory = new ProductInventory();
      $ProductInventory->productID = $product->ProductID;
      $ProductInventory->brandName = $product->BrandName;
      $ProductInventory->productSize = $product->ProductSize;
      $ProductInventory->productPattern = $product->ProductPattern;
      $ProductInventory->productType = $product->ProductTypeName;
      $ProductInventory->qty = $product->Qty;
      $ProductInventory->costPrice = $product->CostPrice;
      $ProductInventory->maxSellingPrice = $product->SellPrice;
      
      $i += AddProductToSalesInvoice($ProductInventory,$order->invoiceNumber);
      chromephp::log($product); 
      $stock = new Stock();
      $stock->ProductID = $product->ProductID;
      $stock->Qty= -$product->Qty;
      $stock->TansactionTypeID = 4; //4=>Sale
      AddStockEntry($stock);
    }
    print $i;  
  }
  else print '';
}