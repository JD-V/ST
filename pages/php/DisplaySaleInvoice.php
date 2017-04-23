<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
include('./phpinvoice.php');

          //ChromePhp::log(isset($_GET['id']));
          if($GetOrderRecord = GetOrderRecord($_GET['id'])) {
            ChromePhp::log("got record id ");
            $OrderRecord  = mysql_fetch_assoc($GetOrderRecord);
            ChromePhp::log($OrderRecord); 
          } else {
            echo 'Not Found';
             return;
          }
$invoice = new phpinvoice();
  /* Header Settings */
  $invoice->setLogo("../res/sample1.jpg");
  $invoice->setColor("#007fff");
  $invoice->setType("CASH / CREDIT BILL");
  $invoice->setReference($OrderRecord['InvoiceNumber']); // get it from db
  $date = date_create($OrderRecord['InvoiceDateTime']);
  $invoice->setDate(date_format($date, 'd-m-Y')); // get it from db
  $invoice->setTime(date_format($date, 'H:i')); // get ti from db
  $invoice->setDue($OrderRecord['VehicleNumber']); //setDue(date('M dS ,Y',strtotime('+3 months')));
  $invoice->setFrom(array("Shankar Enterprises","# 4 & 5, Vasu Complex","New Bel Road","Bangalore-54"));
  $invoice->setTo(array($OrderRecord['CustomerName'],"128 AA Juanita Ave","Glendora , CA 91740","United States of America")); //get it from db
  /* Adding Items in table */
  if($getOrderRecordProducts = GetOrderRecordProducts($_GET['id'])) {

    while($product = mysql_fetch_assoc($getOrderRecordProducts)) {
      ChromePhp::log($product);
      $invoice->addItem($product['ProductDispName'],$product['ProductTypeName'],$product['Qty'],0,$product['SalePrice'],0,$product['Qty']*$product['SalePrice']);
    }
  }
  
  
  //  $invoice->addItem("PDC-E5300","2.6GHz/1GB/320GB/SMP-DVD/FDD/VB",4,0,645,0,2580);
  // $invoice->addItem('LG 18.5" WLCD',"",10,0,230,0,2300);
  // $invoice->addItem("HP LaserJet 5200","",1,0,1100,0,1100);
  /* Add totals */
  $invoice->addTotal("Sub Total",$OrderRecord['SubTotal']);
  $invoice->addTotal("VAT 14.5%",$OrderRecord['Vat']);
  $invoice->addTotal("Discount",$OrderRecord['Discount']);
  $invoice->addTotal("Grand Total",$OrderRecord['AmountPaid'],true);
  /* Set badge */ 
  // $invoice->addBadge("Payment Paid");
  /* Add title */
  $invoice->addTitle("Important Notice");
  /* Add Paragraph */
  $invoice->addParagraph("Goods once sold cannot be taken back or exchanged. Manufacture's guarantee only.\nSubject to jurisdiction of Bangalore city court.  We don't accept repaired tires.");
  /* Set footer note */
  $invoice->setFooternote("Shankar Enterprises");
  /* Render */
  $invoice->render('Invoice.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
}
else
  header('Location: ../../index.php');
?>