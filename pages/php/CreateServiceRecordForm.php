<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) ) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
      if(isset($_GET['item']) && $_GET['item'] == "MaxServiceInvoice")
        print GetMaxServiceInoviceNumber();
    } else if($action == 'save') {
      if(isset($_GET["FormData"])) {
        saveSeriveRecord($_GET["FormData"]);
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

function saveSeriveRecord($FormData) {
  $FormData = json_decode($FormData);
  $ServiceRecord = new ServiceRecord();
  $ServiceRecord->invoiceNumber = $FormData->InvoiceNo;
  $date = date_create($FormData->ServiceInvoiceDate); 
  $ServiceRecord->invoiceDate = date_format($date, 'Y-m-d H:i');
  $ServiceRecord->customerName = $FormData->CustomerName;
  $ServiceRecord->customerPhone = $FormData->CustomerPhone;
  $ServiceRecord->vehicleMileage = $FormData->VehicleMileage;
  $ServiceRecord->vehicleNumber = $FormData->VehicleNo;
  $ServiceRecord->subTotal = $FormData->SubTotal;
  $ServiceRecord->discountAmount = $FormData->DiscountAmount;
  $ServiceRecord->amountPaid = $FormData->TotalAmountPaid;
  $ServiceRecord->address = $FormData->Address;
  $ServiceRecord->notes = $FormData->Notes;

  chromephp::log($ServiceRecord);
  if(AddNewSeriveRecord($ServiceRecord)) {
  $i = 0;

  foreach($FormData->Products as $product) {
    $serviceable = new serviceable();
    $serviceable->serviceableID = $product->ItemID;
    $serviceable->serviceableName = $product->Item;
    $serviceable->qty = $product->Qty;
    $serviceable->price = $product->Price;
    
      $i += AddItemToServiceInvoice($serviceable,$ServiceRecord->invoiceNumber);
      chromephp::log($serviceable); 
  }

  print $i;
  }
  else print '';
}
