<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin()) {
  if( isset($_GET['action']) && isset($_GET['fromDate']) && isset($_GET['toDate'])) {

    $action = FilterInput($_GET['action']);
    $fromDate = FilterInput($_GET['fromDate']);
    $toDate = FilterInput($_GET['toDate']);
    if($action == 'Retrive') {
      RetriveReportData($fromDate,$toDate);
    } else if ($action == 'Quarterly') {
        RetriveReportDataQuartely($fromDate,$toDate);
    }
  }
}

function RetriveReportData($fromDate,$toDate) {
    $dataArray= array();
    $selectedMoths = array();
    
    $fdate = DateTime::createFromFormat("Y-m-d", $fromDate);
    $fromMon =  $fdate->format("m");
    $fromYear =  $fdate->format("Y");

    $tdate = DateTime::createFromFormat("Y-m-d", $toDate);
    $toMon =  $tdate->format("m");    
    $toYear =  $tdate->format("Y");

    for ($i=$fromMon; $i<=12; $i++ ) {
        $suffix = sprintf("%02d", $i);
        $selectedMoths[$suffix .'-' . $fromYear] = 0;
    }
    for ($i=1; $i<$toMon; $i++ ) {
        $suffix = sprintf("%02d", $i);
        $selectedMoths[$suffix . '-'. $toYear] = 0;
    }

    $dataArray['sale'] = $selectedMoths;
    $saleData = MonthWiseSale($fromDate,$toDate);
    
    if(mysql_num_rows($saleData) >= 1) {
        while($sale = mysql_fetch_assoc($saleData)) {
            $dataArray['sale'][$sale['Month']] = $sale['Total'];
        }
    }
    //$dataArray['sale'] = $saleArray;



    $dataArray['service'] = $selectedMoths;
    $serviceData = MonthWiseService($fromDate,$toDate);
    if(mysql_num_rows($serviceData) >= 1) {
        while($service = mysql_fetch_assoc($serviceData)) {
            $dataArray['service'][$service['Month']] = $service['Total'];
        }
    }
    //$dataArray['service'] = $serviceData;


    $dataArray['nonbillable'] = $selectedMoths;
    $nonbillableData = MonthWiseNonBillable($fromDate,$toDate);
    if(mysql_num_rows($nonbillableData) >= 1) {
        while($nonbillable = mysql_fetch_assoc($nonbillableData)) {
            $dataArray['nonbillable'][$nonbillable['Month']] = $nonbillable['Total'];
        }
    }
    //$dataArray['nonbillable'] = $nonbillableArray;


  print json_encode($dataArray);
}


function RetriveReportDataQuartely($fromDate,$toDate) {
    $dataArray= array();
    $selectedMoths = array();
    
    $fdate = DateTime::createFromFormat("Y-m-d", $fromDate);
    $fromMon =  $fdate->format("m");
    $fromYear =  $fdate->format("Y");

    $tdate = DateTime::createFromFormat("Y-m-d", $toDate);
    $toMon =  $tdate->format("m");    
    $toYear =  $tdate->format("Y");

    for ($i=$fromMon; $i<$toMon; $i++ ) {
        $suffix = sprintf("%02d", $i);
        $selectedMoths[$suffix .'-' . $fromYear] = 0;
    }

    $dataArray['sale'] = $selectedMoths;
    $saleData = MonthWiseSale($fromDate,$toDate);
    
    if(mysql_num_rows($saleData) >= 1) {
        while($sale = mysql_fetch_assoc($saleData)) {
            $dataArray['sale'][$sale['Month']] = $sale['Total'];
        }
    }
    //$dataArray['sale'] = $saleArray;



    $dataArray['service'] = $selectedMoths;
    $serviceData = MonthWiseService($fromDate,$toDate);
    if(mysql_num_rows($serviceData) >= 1) {
        while($service = mysql_fetch_assoc($serviceData)) {
            $dataArray['service'][$service['Month']] = $service['Total'];
        }
    }
    //$dataArray['service'] = $serviceData;


    $dataArray['nonbillable'] = $selectedMoths;
    $nonbillableData = MonthWiseNonBillable($fromDate,$toDate);
    if(mysql_num_rows($nonbillableData) >= 1) {
        while($nonbillable = mysql_fetch_assoc($nonbillableData)) {
            $dataArray['nonbillable'][$nonbillable['Month']] = $nonbillable['Total'];
        }
    }
    //$dataArray['nonbillable'] = $nonbillableArray;


  print json_encode($dataArray);
}
