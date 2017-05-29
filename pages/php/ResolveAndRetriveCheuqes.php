<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin()) {

  if(isset($_GET['action'])) {

    $action = mysql_real_escape_string($_GET['action']);

    if($action == 'Retrive') {
        if(isset($_GET['item'])) {
            $item = mysql_real_escape_string($_GET['item']);
            if($item == "All")
                GetCheques(0);
            else if($item == "UnResolved")
                GetCheques(1);
        }
    } else if($action == 'Resolve') {
      if(isset($_GET["ChequeNo"]) && isset($_GET['Invoice']) && isset($_GET['Type']) ) { 
        ResolveCheque($_GET["ChequeNo"], $_GET['Invoice'], $_GET['Type'] );
      }
      
    } else {
        print 'No updates to save';
    }

  }
}

function GetCheques($UnResolved) {

  $orderCheues = GetOrderCheques($UnResolved);
  $cheues_array = array();

  if($orderCheues) {
    if(mysql_num_rows($orderCheues) >= 1) {
      while($cheque = mysql_fetch_assoc($orderCheues)) {
        $cheues_array[] = array(
         'ChequeNo' => $cheque['ChequeNo'] ,
         'ChequeDate' => $cheque['chequeDate'] ,
         'Invoice' => $cheque['InvoiceNumber'],
         'AmountPaid' => $cheque['AmountPaid'],
         'From' => $cheque['CustomerName'],
         'Resolved' => $cheque['Resolved'],
         'Type' => '1' );
      }
    }
  }

  $invoiceCheues = GetPurchaseInvoiceCheques($UnResolved);

  if($invoiceCheues) {
    if(mysql_num_rows($invoiceCheues) >= 1) {
      while($cheque = mysql_fetch_assoc($invoiceCheues)) {
        $cheues_array[] = array(
         'ChequeNo' => $cheque['ChequeNo'],
         'ChequeDate' => $cheque['ChequeDate'],
         'Invoice' => $cheque['InvoiceNumber'],
         'AmountPaid' => $cheque['TotalPaid'],
         'To' => $cheque['Company'],
         'Resolved' => $cheque['Resolved'],
         'Type' => '2' );
      }
    }
  }

  print json_encode($cheues_array);
}

function ResolveCheque($ChequeNo, $Invoice, $Type) {
    $result = new \stdClass();
    $result->status = 0;
    ChromePhp::log("Type   " . $Type);
    if($Type == '1')
        $result->status = ResolveSalesCheque($ChequeNo, $Invoice);
    else if($Type == '2')
        $result->status = ResolvePurchaseCheque($ChequeNo, $Invoice);
    print json_encode($result);
}