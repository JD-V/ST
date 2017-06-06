<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin() ) {

  if( isset($_GET['action']) && isset($_GET['item']) ) {

    $action = FilterInput($_GET['action']);
    $item = FilterInput($_GET['item']);
    if($action == 'Retrive') {
        if($item == 'Invoice') {
            if( isset($_GET['Invoice']) ) {
                $InvoiceID = mysql_real_escape_string($_GET['Invoice']);
                RetriveInvoice($InvoiceID);
            }
        } else if($item == 'Products') {
            if( isset($_GET['Invoice']) ) {
                $InvoiceID = mysql_real_escape_string($_GET['Invoice']);
                RetriveInvoiceProducts($InvoiceID);
            }
        }
    }
  }
}

function RetriveInvoice($InvoiceID) {
    $InvoiceData =  GetPuchaseInvoiceByID($InvoiceID);
  
    if(is_null($InvoiceData)) {
        $InvoiceData = new stdClass();
        $InvoiceData->Status = '0';
    } else {
        $InvoiceData->Status = '1';
        $date = date_create($InvoiceData->invoiceDate);
        $InvoiceData->invoiceDate = date_format($date,'d-m-Y');
    }
     print json_encode($InvoiceData);
}


function RetriveInvoiceProducts($InvoiceID) {
    $InvoiceData = GetProductsInInvoices($InvoiceID);
  
    if(empty($InvoiceData)) {
        $InvoiceData = new stdClass();
        $InvoiceData->Status = '0';
    }
    print json_encode($InvoiceData);
}