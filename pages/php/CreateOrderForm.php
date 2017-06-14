<?php
require '_connect.php';
require '_core.php';
if(isLogin()) {
  if( isset($_GET['action']) ) {
    $action = mysql_real_escape_string($_GET['action']);
    if($action == 'Retrive') {

      if(isset($_GET['item']) && $_GET['item'] == "MaxSalesInvoice") {
        print GetMaxSalesInoviceNumber();
      } else if(isset($_GET['item']) && $_GET['item'] == "CutomerDetails" && isset($_GET['vehicleNo']) )  {

        $VehicleNumber = FilterInput($_GET['vehicleNo']);
        print CustomerDetails($VehicleNumber);
      } else if(isset($_GET['item']) && $_GET['item'] == "SaleOrderData" && isset($_GET['InvoiceNumber'])) {
        $InvoiceNumber = FilterInput($_GET['InvoiceNumber']);
        print GetSaleOrderData($InvoiceNumber);
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
    } else if($action == 'update') {
      if(isset($_GET["FormData"])) {
        modifySalesInvoice($_GET["FormData"]);
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

function CustomerDetails($VehicleNumber) {
  $customerDetails = GetCustomerDetailsByVehicleNumber($VehicleNumber);
  $customerData = NULL;
  if($customerDetails == NULL)
    $customerData = array('data' => '0');
  else {
    $customer = mysql_fetch_assoc($customerDetails);
    $customerData = array('data' => '1', 'CustomerName' => $customer['CustomerName'] ,'CustomerPhone' => $customer['CustomerPhone'], 'CustomerTIN' => $customer['CustomerTIN'], 
    'CustomerPAN'=> $customer['CustomerPAN'], 'VehicleMileage' => $customer['VehicleMileage'],'Address' => $customer['Address'] );
  }
    print json_encode($customerData);
}

function saveOrder($FormData) {
  DisableAutoCommit();  //Disable auto commit
  $i = 0;
  $error = false;
  $FormData = json_decode($FormData);
  $order = new Order();
  $order->invoiceNumber = $FormData->InvoiceNo;
  
  $date = date_create($FormData->SalesInvoiceDate);
  $order->invoiceDate = date_format($date, 'Y-m-d H:i');
  $order->customerName = $FormData->CustomerName;
  $order->customerPhone = $FormData->CustomerPhone;
  $order->vehicleNumber = $FormData->VehicleNo;
  $order->vehicleMileage = $FormData->VehicleMileage;
  $order->customerPAN = $FormData->CustomerPAN;
  $order->customerTIN = $FormData->CustomerTIN;
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
  // chromephp::log($order);

  if(AddNewSalesItem($order) == 1) {  //add sa;es invoice.

  $SaleID = GetMaxSaleID() + 1;
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

      if(AddProductToSalesInvoice($ProductInventory,$order->invoiceNumber,$SaleID) == 1) {  //add product inside invoice
         $i++;
      } else {  //if error, set the flag and  break out of the loop.
         $error = true;
         break;
      }

      $stock = new Stock();
      $stock->ProductID = $product->ProductID;
      $stock->Qty= -$product->Qty;
      $stock->TansactionTypeID = 4; //4=>Sale
      $stock->SaleID = $SaleID;
      if(AddStockEntry($stock) == 1) {  // Add stock entry for the same product.
        $SaleID++;
      } else {  //if error, set the flag and  break out of the loop.
         $error = true;
         break;
      }
    }
  } else {
     $error = true;
  }

  if($error) {  // if any of the operation in loop fails, perform rollback() immediately.
    PerformRollback();
    print '';
  } else {
    print $i;
  }

  EnableAutoCommit(); // enable auto commit at the end.
}

function GetSaleOrderData ($InvoiceNumber) {

  $SaleOrderData =  new \stdClass();
  $Products_array = array();
  $InvoiceDataRow = GetSaleInvoice($InvoiceNumber);
  if(mysql_num_rows($InvoiceDataRow)==1) {
    
    $Products = GetProductsSalesInInvoice($InvoiceNumber);
    if(mysql_num_rows($Products)>=1) {
       while($Product= mysql_fetch_assoc($Products)) {

        $Products_array[] = array('SaleID' => $Product['SaleID'] ,'ProductID' => $Product['ProductID'],
        'BrandName' => $Product['BrandName'], 'ProductSize' => $Product['Productsize'], 'ProductPattern' => $Product['Pattern'],
        'ProductTypeName'=>$Product['ProductType'], 'Qty' => $Product['Qty'], 'CostPrice' => $Product['CostPrice'], 
        'SellPrice'=>$Product['SalePrice'], 'InvoiceNumber' => $Product['InvoiceNumber'] );
      }
    }

    $Invoice = mysql_fetch_assoc($InvoiceDataRow);
        // $date = date_create($Invoice['InvoiceDate']);
      $date = date_create($Invoice['InvoiceDateTime']);
      $fromatedDate = date_format($date, 'd-m-Y H:i');
      $ServiceRecordData = array('status' => '1', 'InvoiceNumber' => $Invoice['InvoiceNumber'], 'InvoiceDateTime' => $fromatedDate, 
      'InvoicDateUnfomrmatted' => $Invoice['InvoiceDateTime'], 'CustomerName' => $Invoice['CustomerName'], 'CustomerPhone' => $Invoice['CustomerPhone'], 
      'CustomerTIN'=>$Invoice['CustomerTIN'], 'CustomerPAN'=>$Invoice['CustomerPAN'], 'VehicleNumber' => $Invoice['VehicleNumber'], 
      'VehicleMileage' => $Invoice['VehicleMileage'], 'Basic' => $Invoice['BasicAmount'], 'Discount' => $Invoice['Discount'], 'Vat' =>$Invoice['Vat'],
      'AmountPaid' => $Invoice['AmountPaid'], 'PaymentType' => $Invoice['PaymentType'], 'ChequeNo'=> $Invoice['ChequeNo'], 
      'chequeDate'=>$Invoice['chequeDate'], 'Resolved' => $Invoice['Resolved'],
      'Address' => $Invoice['Address'], 'Notes' => $Invoice['Notes'], 'UserID' =>  $Invoice['UserID'],
      'products'=> $Products_array );
  
  } else {
    $ServiceRecordData->status=0;
  }
  
  print json_encode($ServiceRecordData);
}

function modifySalesInvoice($FormData) {
  DisableAutoCommit();  //Disable auto commit
  $i = 0;
  $error = false;  
  $FormData = json_decode($FormData);
  $order = new Order();
  $order->invoiceNumber = $FormData->InvoiceNoActual;
  
  $date = date_create($FormData->SalesInvoiceDate);
  $order->invoiceDate = date_format($date, 'Y-m-d H:i');
  $order->customerName = $FormData->CustomerName;
  $order->customerPhone = $FormData->CustomerPhone;
  $order->vehicleNumber = $FormData->VehicleNo;
  $order->vehicleMileage = $FormData->VehicleMileage;
  $order->customerPAN = $FormData->CustomerPAN;
  $order->customerTIN = $FormData->CustomerTIN;
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

  // chromephp::log($order);
  if(UpdateSaleInvoice($order) == 1) {

    foreach($FormData->Products as $product) {
      if(property_exists($product, "SaleID") && $product->SaleID != NULL && $product->SaleID != '') {
        // Existing product
        $ProductInventory = new ProductInventory();
        $ProductInventory->productID = $product->ProductID;
        $ProductInventory->brandName = $product->BrandName;
        $ProductInventory->productSize = $product->ProductSize;
        $ProductInventory->productPattern = $product->ProductPattern;
        $ProductInventory->productType = $product->ProductTypeName;
        $ProductInventory->qty = $product->Qty;
        $ProductInventory->costPrice = $product->CostPrice;
        $ProductInventory->maxSellingPrice = $product->SellPrice;
        // updating existing product
        if(UpdateProductInSalesInvoice($ProductInventory,$product->SaleID) == 1) {

          $stock = new Stock();
          $stock->ProductID = $product->ProductID;
          $stock->Qty= -$product->Qty;
          $stock->SaleID = $product->SaleID;
          // update stock entry as well.
          if(UpdateStockEntry($stock) == 1 ) {
            $i++;
          } else {
            $error = true;
            break;
          }
        } else {
          $error = true;
          break;
        }        
      } else {
        // New Product
        $SaleID = GetMaxSaleID() + 1;

        $ProductInventory = new ProductInventory();
        $ProductInventory->productID = $product->ProductID;
        $ProductInventory->brandName = $product->BrandName;
        $ProductInventory->productSize = $product->ProductSize;
        $ProductInventory->productPattern = $product->ProductPattern;
        $ProductInventory->productType = $product->ProductTypeName;
        $ProductInventory->qty = $product->Qty;
        $ProductInventory->costPrice = $product->CostPrice;
        $ProductInventory->maxSellingPrice = $product->SellPrice;
        
        if(AddProductToSalesInvoice($ProductInventory, $order->invoiceNumber, $SaleID) == 1) {
         

          $stock = new Stock();
          $stock->ProductID = $product->ProductID;
          $stock->Qty= -$product->Qty;
          $stock->TansactionTypeID = 4; //4=>Sale
          $stock->SaleID = $SaleID;
          if(AddStockEntry($stock) == 1) {  // Add stock entry for the same product.
            $i++;
          } else {  //if error, set the flag and  break out of the loop.
            PerformRollback();
            $error = true;
            break;
          }
        } else {
          $error = true;
          break;
        }
      }
    }    
  }
  else {  //if error, set the flag and  break out of the loop.
    $error = true;
  }

  if($error) {  // if any of the operation in loop fails, perform rollback() immediately.
    PerformRollback();
    print '';
  } else {
    print $i;
  }
  EnableAutoCommit(); // enable auto commit at the end.
}
