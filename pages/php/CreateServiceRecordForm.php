<?php
require '_connect.php';
require '_core.php';

if(isLogin()) {

  if( isset($_GET['action']) ) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
      if(isset($_GET['item']) && $_GET['item'] == "MaxServiceInvoice") {
        print GetMaxServiceInoviceNumber();
      } else if(isset($_GET['item']) && $_GET['item'] == "GetServiceRecordData" && isset($_GET['InvoiceNumber']) ) {
        print GetServiceRecordData($_GET['InvoiceNumber']);
      }
    } else if($action == 'save') {
      if(isset($_GET["FormData"])) {
        saveSeriveRecord($_GET["FormData"]);
      }
      else {
        print 'No updates to save';
      }
    } else if($action == 'update') {
      if(isset($_GET["FormData"])) {
        modifySeriveRecord($_GET["FormData"]);
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

function modifySeriveRecord($FormData) {
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
  if(UpdateSeriveRecord($ServiceRecord) ==1) {
    $i = 0;
    RemoveAllItemsFromServiceInvoice($ServiceRecord->invoiceNumber);
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

function GetServiceRecordData($InvoiceNumber) {
  $ServiceRecordData =  new \stdClass();
  $Serviceable_array = array();
  $InvoiceDataRow = GetServiceInvoice($InvoiceNumber);
  if(mysql_num_rows($InvoiceDataRow)==1) {
    
    $Serviceables = GetServiceablesInInvoice($InvoiceNumber);
    if(mysql_num_rows($Serviceables)>=1) { 
       while($Serviceable= mysql_fetch_assoc($Serviceables)) {
        $Serviceable_array[] = array('ItemID' => $Serviceable['ServiceItemID'] ,'ServiceableID' => $Serviceable['ServiceableID'],
        'Item' => $Serviceable['ServiceableDispName'],'Qty' => $Serviceable['Qty'], 
        'Price' => $Serviceable['Price'], 'InvoiceNumber' => $Serviceable['InvoiceNumber'] );
      }
    }

    $Invoice = mysql_fetch_assoc($InvoiceDataRow);
        // $date = date_create($Invoice['InvoiceDate']);


      $date = date_create($Invoice['InvoiceDateTime']);
      $fromatedDate = date_format($date, 'd-m-Y H:i');
      $ServiceRecordData = array('status' => '1', 'InvoiceNumber' => $Invoice['InvoiceNumber'], 'InvoiceDateTime' => $fromatedDate, 
      'InvoicDateUnfomrmatted' => $Invoice['InvoiceDateTime'], 'CustomerName' => $Invoice['CustomerName'], 'CustomerPhone' => $Invoice['CustomerPhone'], 
      'VehicleNumber' => $Invoice['VehicleNumber'],
      'VehicleMileage' => $Invoice['VehicleMileage'], 'SubTotal' => $Invoice['SubTotal'], 'Discount' => $Invoice['Discount'],
      'AmountPaid' => $Invoice['AmountPaid'], 'Address' => $Invoice['Address'], 'Note' => $Invoice['Note'], 'UserID' =>  $Invoice['UserID'],
      'Serviceables'=> $Serviceable_array );
  
  } else {
    $ServiceRecordData->status=0;
  }
  
  print json_encode($ServiceRecordData);
}