  <?php
  require '_connect.php';
  require '_core.php';

  if(isLogin()) {
    if( isset($_GET['action']) ) {
      $action = mysql_real_escape_string($_GET['action']);
      if($action == 'retrive') {

        if(isset($_GET['item']) && $_GET['item'] == "services") {
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
