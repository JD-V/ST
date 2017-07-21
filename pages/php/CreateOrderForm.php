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
        } else if ( isset($_GET['BrandID'])) {
            RetriveProducts($_GET['BrandID']);
        } else if(isset($_GET['item']) && $_GET['item'] == "orders") {
          print RetriveOrders();
        }

      } else if($action == 'save') {
        // if(isset($_GET["FormData"])) {
          saveOrder();
        // }
        // else {
        //   print 'No updates to save';
        // }
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

  function RetriveOrders() {
    $orders = GetOrders();
    $orders_array = array();
    while ($order = mysql_fetch_assoc($orders)) {
      $orderItem  = new Order();
      $orderItem->invoiceNumber = $order['InvoiceNumber'];
      $orderItem->invoiceDate = $order['InvoiceDateTime'];
      $orderItem->customerName = $order['CustomerName'];
      $orderItem->vehicleMileage = $order['VehicleMileage'];
      $orderItem->customerPhone = $order['CustomerPhone'];    
      $orderItem->vehicleNumber = $order['VehicleNumber'];
      $orderItem->basic = $order['BasicAmount'];
      $orderItem->vatAmount = $order['Vat'];
      $orderItem->discount = $order['Discount'];
      $orderItem->amountPaid = $order['AmountPaid'];
      $orderItem->address = $order['Address'];
      $orderItem->notes = $order['Notes'];
      $orderItem->timeStamp = $order['TimeStamp'];
      $orders_array[] = $orderItem;
    }

    return json_encode($orders_array);
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

  function saveOrder() {

    // Handling post request
    $order = new Order();
    $incompleteData = false;
    if(isset($_POST['SalesInvoiceDate'])) {
      $date = date_create($_POST['SalesInvoiceDate']);
      $order->invoiceDate = date_format($date, 'Y-m-d H:i');
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['InvoiceNo'])) {
        $order->invoiceNumber = $_POST['InvoiceNo'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['CustomerName'])) {
        $order->customerName = $_POST['CustomerName'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['CustomerPhone'])) {
        $order->customerPhone = $_POST['CustomerPhone'];
    } else {
        $incompleteData = true;
    }  

    if(isset($_POST['VehicleNo'])) {
        $order->vehicleNumber = $_POST['VehicleNo'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['CustomerPAN'])) {
        $order->customerPAN = $_POST['CustomerPAN'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['CustomerTIN'])) {
        $order->customerTIN = $_POST['CustomerTIN'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['BasicAmount'])) {
        $order->basic = $_POST['BasicAmount'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['VatAmount'])) {
        $order->vatAmount = $_POST['VatAmount'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['DiscountAmount'])) {
        $order->discount = $_POST['DiscountAmount'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['TotalAmountPaid'])) {
        $order->amountPaid = $_POST['TotalAmountPaid'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['PaymentMethod'])) {
        $order->paymentMethod = $_POST['PaymentMethod'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['Notes'])) {
        $order->notes = $_POST['Notes'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['Address'])) {
        $order->address = $_POST['Address'];
    } else {
        $incompleteData = true;
    }

    if(isset($_POST['Products'])) {
        $products = $_POST['Products'];
    } else {
        $incompleteData = true;
    }
    //end



    $i = 0;
    $inError = false;
    $message = "";

    if( $order->paymentMethod == '3') {
      if(isset($_POST['ChequeDate']) && isset($_POST['ChequeNo']) ) {
        $dateC = date_create($_POST['ChequeDate']);
        $order->chequeDate = date_format($dateC, 'Y-m-d H:i');
        $order->chequeNo = $_POST['ChequeNo'];
      } else {
          $incompleteData = true;
      }
    }
    // chromephp::log($order);

    if($incompleteData) {
      $message = "Incomplete Data Received. Error Code (CO09)";
      $inError = true;
    } else {
      DisableAutoCommit();  //Disable auto commit
    }

    if(!$inError) {
      if(AddNewSalesItem($order) == 1) {  //add sales invoice.
        $SaleID = GetMaxSaleID() + 1;

        for($j=0; $j<count($products); $j++) {

          $ProductInventory = new ProductInventory();
          $ProductInventory->productID = $products[$i]['ProductID'];
          $ProductInventory->brandName = $products[$i]['BrandName'];
          $ProductInventory->productSize = $products[$i]['ProductSize'];
          $ProductInventory->productPattern = $products[$i]['ProductPattern'];
          $ProductInventory->productType = $products[$i]['ProductTypeName'];
          $ProductInventory->qty = $products[$i]['Qty'];
          $ProductInventory->maxSellingPrice = $products[$i]['SellPrice'];
          
          if(AddProductToSalesInvoice($ProductInventory,$order->invoiceNumber,$SaleID) == 1) {
            // Add product inside invoice
            $i++;

            $stock = new Stock();
            $stock->ProductID = $ProductInventory->productID;
            $stock->Qty= -$ProductInventory->qty;
            $stock->TansactionTypeID = 4; //4=>Sale
            $stock->SaleID = $SaleID;
            if(AddStockEntry($stock) == 1) {  // Add stock entry for the same product.
              $SaleID++;
            } else {  //if error, set the flag and  break out of the loop.
              $inError = true;
              $message = "Something went wrong. Please contact your system admin. Error Code (CO10)";
              break;
            }
          } else {  //if error, set the flag and  break out of the loop.
            $inError = true;
            $message = "Something went wrong. Please contact your system admin. Error Code (CO11)";
            break;
          }
        }
      } else {
        $inError = true;
        $message = "Something went wrong. Please contact your system admin. Error Code (CO12)";
      }
    }

    if($inError) {  // if any of the operation in loop fails, perform rollback() immediately.
      PerformRollback();
    } else {
      $message = "Sales invoice has been added with ". $i ." products";
    }

    EnableAutoCommit(); // enable auto commit at the end.
    
    $output = json_encode(array('succees'=> $inError == true ? 0 : 1 ,'message'=>$message));
    die($output);  
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
