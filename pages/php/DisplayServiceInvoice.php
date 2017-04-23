<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
  include('./phpinvoice.php');

  //ChromePhp::log(isset($_GET['id']));
  if($getServiceRecord = GetServiceRecord($_GET['id'])) {
    ChromePhp::log("got record id ");
    $ServiceRecord  = mysql_fetch_assoc($getServiceRecord);
    ChromePhp::log($ServiceRecord); 
  } else {
    echo 'Not Found';
      return;
  }
  $invoice = new phpinvoice();
  /* Header Settings */
  $invoice->setLogo("../res/sample1.jpg");
  $invoice->setColor("#007fff");
  $invoice->setType("SERVICE INVOICE");
  $invoice->setReference($ServiceRecord['InvoiceNumber']); // get it from db
  $date = date_create($ServiceRecord['InvoiceDateTime']);
  $invoice->setDate(date_format($date, 'd-m-Y')); // get it from db
  $invoice->setTime(date_format($date, 'H:i')); // get ti from db
  $invoice->setDue($ServiceRecord['VehicleNumber']); //setDue(date('M dS ,Y',strtotime('+3 months')));
  $invoice->setFrom(array("Shankar Enterprises","# 4 & 5, Vasu Complex","New Bel Road Bangalore-54","PH-23600699, 20603173, 9845973001"));
  $address = explode("\n", $ServiceRecord['Address']);
  $addressLines = count($address);
  if($addressLines<=1) {
   
   if(trim($address[0]) == '' )  
      $invoice->setTo(array($ServiceRecord['CustomerName'],'MO: '.$ServiceRecord['CustomerPhone'],"","")); //get it from db
    else
      $invoice->setTo(array($ServiceRecord['CustomerName'],$address[0],'MO: '.$ServiceRecord['CustomerPhone'],"")); //get it from db
  }
  else if($addressLines>1 && $addressLines<=2)
    $invoice->setTo(array($ServiceRecord['CustomerName'],$address[0],$address[1],'MO: '.$ServiceRecord['CustomerPhone'])); //get it from db
  else if($addressLines>2 && $addressLines<=3) {
    for($i=3;$i<$addressLines; $i++) {
      $address[2] .= ' '. $address[$i];
    }
    $invoice->setTo(array($ServiceRecord['CustomerName'],$address[0],$address[1],$address[2] .'. MO: '. $ServiceRecord['CustomerPhone'])); //get it from db
  }
  /* Adding Items in table */
  if($getServiceRecordItems = GetServiceRecordItems($_GET['id'])) {
ChromePhp::log('true');
    while($item = mysql_fetch_assoc($getServiceRecordItems)) {
      ChromePhp::log($item);
      $invoice->addItem($item['ServiceableDispName'],' ',$item['Qty'],0,$item['Price'],0,$item['Qty']*$item['Price']);
    }
  } else ChromePhp::log('false');
  
  
  //  $invoice->addItem("PDC-E5300","2.6GHz/1GB/320GB/SMP-DVD/FDD/VB",4,0,645,0,2580);
  // $invoice->addItem('LG 18.5" WLCD',"",10,0,230,0,2300);
  // $invoice->addItem("HP LaserJet 5200","",1,0,1100,0,1100);
  /* Add totals */
  $invoice->addTotal("Sub Total",$ServiceRecord['SubTotal']);
  // $invoice->addTotal("VAT 14.5%",$ServiceRecord['Vat']);
  $invoice->addTotal("Discount",$ServiceRecord['Discount']);
  $invoice->addTotal("Grand Total",$ServiceRecord['AmountPaid'],true);
  /* Set badge */ 
  // $invoice->addBadge("Payment Paid");
  /* Add title */
  $invoice->addTitle("");
  /* Add Paragraph */
  $invoice->addParagraph("E. & O.E                                      TIN:29950053082                                                                                         For. Shankar Enterprises\nKST NO.: 7180850-4                   Dt.12.02.1993\nCST NO.: 718585507");
  /* Set footer note */
  $invoice->setFooternote("Shankar Enterprises");
  /* Render */
  $invoice->render('Invoice.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
}
else
  header('Location: ../../index.php');
?>