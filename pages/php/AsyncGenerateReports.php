<?php
//$_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
require '_connect.php';
require '_core.php';

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once realpath(dirname(__FILE__) . '/../../dist/php') . '/PHPExcel.php';

if(isLogin() && isAdmin())
{
    if( isset($_POST['reportType']) && !empty($_POST['reportType']) &&
        isset($_POST['FromDate']) && !empty($_POST['FromDate']) &&
        isset($_POST['ToDate']) && !empty($_POST['ToDate']) )
      {
        $dateStr = mysql_real_escape_string(trim($_POST['FromDate']));
        $date = DateTime::createFromFormat('d-m-Y', $dateStr);

        $FromDateTime = $date->format('Y-m-d') . '00:00:00'; 

        $dateStr = mysql_real_escape_string(trim($_POST['ToDate']));
        $date = DateTime::createFromFormat('d-m-Y', $dateStr);

        $ToDateTime = $date->format('Y-m-d') . '23:59:59'; 

        $ReportTypeID = mysql_real_escape_string(trim($_POST['reportType']));


        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        if($ReportTypeID == 1) {
            //sale
            $ReportType = "sale";
            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'From')
                        ->setCellValue('B1', $_POST['FromDate'])
                        ->setCellValue('A2', 'To')                    
                        ->setCellValue('B2', $_POST['ToDate'])
                        ->setCellValue('A3', 'Report type')
                        ->setCellValue('B3', $ReportType)
                        ->setCellValue('A4', 'Total')
                        ->setCellValue('A6', 'Invoice Number')
                        ->setCellValue('B6', 'DateTime')
                        ->setCellValue('C6', 'Customer Name')
                        ->setCellValue('D6', 'Phone')
                        ->setCellValue('E6', 'Address')
                        ->setCellValue('F6', 'Customer TIN')
                        ->setCellValue('G6', 'Customer PAN')
                        ->setCellValue('H6', 'Vehicle No.')
                        ->setCellValue('I6', 'Mileage')
                        ->setCellValue('J6', 'Basic Amount')
                        ->setCellValue('K6', 'Discount')
                        ->setCellValue('L6', 'Vat')
                        ->setCellValue('M6', 'Amount Paid')
                        ->setCellValue('N6', 'Payment Type')
                        ->setCellValue('O6', 'Cheque No.')
                        ->setCellValue('P6', 'cheque Date')
                        ->setCellValue('Q6', 'Notes')
                        ->setCellValue('R6', 'Brand ')
                        ->setCellValue('S6', 'Size')
                        ->setCellValue('T6', 'Pattern')
                        ->setCellValue('U6', 'Type')
                        ->setCellValue('V6', 'Qty')
                        ->setCellValue('W6', 'Cost Price')
                        ->setCellValue('X6', 'Sell Price');

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('sale');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            //end of printing column names
            //start while loop to get data
            $result = GetSalesReport($FromDateTime, $ToDateTime);
            $array = array();
            
            $printData= false;
            if(mysql_num_rows($result)>0) 
                $printData= true;
            while ($row = mysql_fetch_assoc($result)) {
                foreach ($row as $key => $value) {
                    $array[$key][] = $value;
                }
            }

            if($printData) {
                for($i=0; $i<sizeof($array['InvoiceNumber']); $i++) {
                    $arrVals = array_values($array['InvoiceNumber']);
                    $arrVals[$i] = '';
                    $index = array_search($array['InvoiceNumber'][$i], $arrVals);

                    while($index != NULL){                    
                        $array['InvoiceNumber'][$index] = NULL;

                        $arrVals[$index] = '';
                        $indexNew = array_search($array['InvoiceNumber'][$i], $arrVals);
                        
                        if($indexNew == $index)
                            break;
                        else $index = $indexNew;
                    }
                }

                //end of printing column names  
                //start while loop to get data
                $previousInvoice = NULL;
                $currentRow = 7;
                $count = 1;
                $total = 0;
                for($i=0; $i<sizeof($array['InvoiceNumber']); $i++)
                {
                    $paymentType ='';
                    if( $previousInvoice == NULL || $previousInvoice != $array['InvoiceNumber'][$i] ) {
                        //merge cells  if count >1
                        if($count > 1) {
                            ChromePhp::log('A'. ($currentRow-1-$count) .':A'.($currentRow-1) );
                            // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'. ($currentRow-1-$count) .':A'.($currentRow-1) );
                        }
                        $count = 1;
                    }
                    else
                        $previousInvoice = $array['InvoiceNumber'][$i];
                        
                    if( $array['InvoiceNumber'][$i] == NULL) {
                        $count++;

                        if(isset($array['BrandName'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('R'.$currentRow, $array['BrandName'][$i]);
                        }

                        if(isset($array['Productsize'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('S'.$currentRow, $array['Productsize'][$i]);
                        }

                        if(isset($array['Pattern'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('T'.$currentRow, $array['Pattern'][$i]);
                        }

                        if(isset($array['ProductType'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('U'.$currentRow, $array['ProductType'][$i]);
                        }

                        if(isset($array['Qty'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('V'.$currentRow, $array['Qty'][$i]);
                        }

                        if(isset($array['CostPrice'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('W'.$currentRow, $array['CostPrice'][$i]);
                        }

                        if(isset($array['SalePrice'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('X'.$currentRow, $array['SalePrice'][$i]);
                        }
                        $currentRow++;
                        continue;
                    }
                        

                    if(isset($array['InvoiceNumber'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$currentRow, $array['InvoiceNumber'][$i]);
                    }
                    
                    if(isset($array['InvoiceDateTime'][$i])) {
                        $date = date_create($array['InvoiceDateTime'][$i]);
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('B'.$currentRow, date_format($date,'d-m-y H:i'));
                    }

                    if(isset($array['CustomerName'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('C'.$currentRow, $array['CustomerName'][$i]);
                    }

                    if(isset($array['CustomerPhone'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('D'.$currentRow, $array['CustomerPhone'][$i]);
                    }

                    if(isset($array['Address'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('E'.$currentRow, $array['Address'][$i]);
                    }

                    if(isset($array['CustomerTIN'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('F'.$currentRow, $array['CustomerTIN'][$i]);
                    }

                    if(isset($array['CustomerPAN'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('G'.$currentRow, $array['CustomerPAN'][$i]);
                    }

                    if(isset($array['VehicleNumber'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.$currentRow, $array['VehicleNumber'][$i]);
                    }

                    if(isset($array['VehicleMileage'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('I'.$currentRow, $array['VehicleMileage'][$i]);
                    }

                    if(isset($array['BasicAmount'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('J'.$currentRow, $array['BasicAmount'][$i]);
                    }

                    if(isset($array['Discount'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('K'.$currentRow, $array['Discount'][$i]);
                    }

                    if(isset($array['Vat'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('L'.$currentRow, $array['Vat'][$i]);
                    }

                    if(isset($array['AmountPaid'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('M'.$currentRow, $array['AmountPaid'][$i]);
                        $total += $array['AmountPaid'][$i];
                    }

                    if(isset($array['PaymentType'][$i])){
                        if($array['PaymentType'][$i] == 1) {
                            $paymentType = 'Cash';
                        } else if($array['PaymentType'][$i] == 2) {
                            $paymentType = 'Card';
                        } else if($array['PaymentType'][$i] == 3) {
                            $paymentType = 'Cheque';
                        }                    
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('N'.$currentRow, $paymentType);
                    }

                    if($paymentType == 'Cheque') {                
                        if(isset($array['ChequeNo'][$i])){
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('O'.$currentRow, $array['ChequeNo'][$i]);
                        }

                        if(isset($array['chequeDate'][$i])){
                            $date = date_create($array['chequeDate'][$i]);
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('P'.$currentRow, date_format($date,'d-m-Y'));
                        }
                    }

                    if(isset($array['Notes'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('Q'.$currentRow, $array['Notes'][$i]);
                    }

                    if(isset($array['BrandName'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('R'.$currentRow, $array['BrandName'][$i]);
                    }

                    if(isset($array['Productsize'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('S'.$currentRow, $array['Productsize'][$i]);
                    }

                    if(isset($array['Pattern'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('T'.$currentRow, $array['Pattern'][$i]);
                    }

                    if(isset($array['ProductType'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('U'.$currentRow, $array['ProductType'][$i]);
                    }

                    if(isset($array['Qty'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('V'.$currentRow, $array['Qty'][$i]);
                    }

                    if(isset($array['CostPrice'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('W'.$currentRow, $array['CostPrice'][$i]);
                    }

                    if(isset($array['SalePrice'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('X'.$currentRow, $array['SalePrice'][$i]);
                    }

                    $currentRow++;
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B4', $total);
            } else {
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B4', 0);
            }


        } else if($ReportTypeID == 2) {
            //service
            $ReportType = "Service";
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'From')
                        ->setCellValue('B1', $_POST['FromDate'])
                        ->setCellValue('A2', 'To')                    
                        ->setCellValue('B2', $_POST['ToDate'])
                        ->setCellValue('A3', 'Report type')
                        ->setCellValue('B3', $ReportType)
                        ->setCellValue('A4', 'Total')
                        ->setCellValue('A6', 'Invoice Number')
                        ->setCellValue('B6', 'DateTime')
                        ->setCellValue('C6', 'Customer Name')
                        ->setCellValue('D6', 'Phone')
                        ->setCellValue('E6', 'Address')
                        ->setCellValue('F6', 'Vehicle No.')
                        ->setCellValue('G6', 'Mileage')
                        ->setCellValue('H6', 'Basic Amount')
                        ->setCellValue('I6', 'Discount')
                        ->setCellValue('J6', 'Amount Paid')
                        ->setCellValue('K6', 'Notes')
                        ->setCellValue('L6', 'Particulars')
                        ->setCellValue('M6', 'Qty')
                        ->setCellValue('N6', 'Sell Price');

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('sale');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            //end of printing column names
            //start while loop to get data
            $result = GetServiceReport($FromDateTime, $ToDateTime);
            $array = array();
            $printData= false;
            if(mysql_num_rows($result)>0) 
                $printData= true;
                
            // if(mysql_num_rows($result) == 0) {
            //     return;
            // }

            while ($row = mysql_fetch_assoc($result)) {
                foreach ($row as $key => $value) {
                    $array[$key][] = $value;
                }
            }

            if($printData) {
                for($i=0; $i<sizeof($array['InvoiceNumber']); $i++) {
                    $arrVals = array_values($array['InvoiceNumber']);
                    $arrVals[$i] = '';
                    $index = array_search($array['InvoiceNumber'][$i], $arrVals);

                    while($index != NULL){                    
                        $array['InvoiceNumber'][$index] = NULL;

                        $arrVals[$index] = '';
                        $indexNew = array_search($array['InvoiceNumber'][$i], $arrVals);
                        
                        if($indexNew == $index)
                            break;
                        else $index = $indexNew;
                    }
                }

                //end of printing column names  
                //start while loop to get data
                $previousInvoice = NULL;
                $currentRow = 7;
                $count = 1;
                $total = 0;
                for($i=0; $i<sizeof($array['InvoiceNumber']); $i++)
                {
                    $paymentType ='';
                    if( $previousInvoice == NULL || $previousInvoice != $array['InvoiceNumber'][$i] ) {
                        //merge cells  if count >1
                        if($count > 1) {
                            ChromePhp::log('A'. ($currentRow-1-$count) .':A'.($currentRow-1) );
                            // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'. ($currentRow-1-$count) .':A'.($currentRow-1) );
                        }
                        $count = 1;
                    }
                    else
                        $previousInvoice = $array['InvoiceNumber'][$i];
                        
                        if( $array['InvoiceNumber'][$i] == NULL) {
                            $count++;

                        if(isset($array['ServiceableDispName'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('L'.$currentRow, $array['ServiceableDispName'][$i]);
                        }

                        if(isset($array['Qty'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('M'.$currentRow, $array['Qty'][$i]);
                        }

                        if(isset($array['Price'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('N'.$currentRow, $array['Price'][$i]);
                        }
                        $currentRow++;
                        continue;
                    }


                    if(isset($array['InvoiceNumber'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$currentRow, $array['InvoiceNumber'][$i]);
                    }
                    
                    if(isset($array['InvoiceDateTime'][$i])) {
                        $date = date_create($array['InvoiceDateTime'][$i]);
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('B'.$currentRow, date_format($date,'d-m-y H:i'));
                    }

                    if(isset($array['CustomerName'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('C'.$currentRow, $array['CustomerName'][$i]);
                    }

                    if(isset($array['CustomerPhone'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('D'.$currentRow, $array['CustomerPhone'][$i]);
                    }

                    if(isset($array['Address'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('E'.$currentRow, $array['Address'][$i]);
                    }

                    if(isset($array['VehicleNumber'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('F'.$currentRow, $array['VehicleNumber'][$i]);
                    }

                    if(isset($array['VehicleMileage'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('G'.$currentRow, $array['VehicleMileage'][$i]);
                    }

                    if(isset($array['SubTotal'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.$currentRow, $array['SubTotal'][$i]);
                    }

                    if(isset($array['Discount'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('I'.$currentRow, $array['Discount'][$i]);
                    }

                    if(isset($array['AmountPaid'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('J'.$currentRow, $array['AmountPaid'][$i]);
                        $total += $array['AmountPaid'][$i];
                    }

                    if(isset($array['Notes'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('K'.$currentRow, $array['Notes'][$i]);
                    }

                    if(isset($array['ServiceableDispName'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('L'.$currentRow, $array['ServiceableDispName'][$i]);
                    }

                    if(isset($array['Qty'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('M'.$currentRow, $array['Qty'][$i]);
                    }

                    if(isset($array['Price'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('N'.$currentRow, $array['Price'][$i]);
                    }

                    $currentRow++;
                }

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('B4', $total);
            } else {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('B4', 0);
            }

        } else if($ReportTypeID == 3) {
            //non billable
            $ReportType = "Non Billable";
           
            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'From')
                        ->setCellValue('B1', $_POST['FromDate'])
                        ->setCellValue('A2', 'To')                    
                        ->setCellValue('B2', $_POST['ToDate'])
                        ->setCellValue('A3', 'Report type')
                        ->setCellValue('B3', $ReportType)
                        ->setCellValue('A4', 'Total')
                        ->setCellValue('A6', 'Record ID')
                        ->setCellValue('B6', 'Date')
                        ->setCellValue('C6', 'particulars')
                        ->setCellValue('D6', 'Amount Paid')
                        ->setCellValue('E6', 'Notes');           

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('nonbillable');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            //end of printing column names
            //start while loop to get data
            $result = GetNonBillableReport($FromDateTime, $ToDateTime);
            $currentRow = 7;
            $total = 0;
            while ($Record = mysql_fetch_assoc($result)) {

                if(isset($Record['RecordID'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$currentRow, $Record['RecordID']);
                }
                
                if(isset($Record['RecordDate'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('B'.$currentRow, $Record['RecordDate']);
                }

                if(isset($Record['Perticulars'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('C'.$currentRow, $Record['Perticulars']);
                }

                if(isset($Record['AmountPaid'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('D'.$currentRow, $Record['AmountPaid']);
                    $total += $Record['AmountPaid'];
                }

                if(isset($Record['Notes'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('E'.$currentRow, $Record['Notes']);
                }

                $currentRow++;
            }
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('B4', $total);            
        } else if($ReportTypeID == 4) {
            //sale
            $ReportType = "Purchase Invoices";
            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'From')
                        ->setCellValue('B1', $_POST['FromDate'])
                        ->setCellValue('A2', 'To')                    
                        ->setCellValue('B2', $_POST['ToDate'])
                        ->setCellValue('A3', 'Report type')
                        ->setCellValue('B3', $ReportType)
                        ->setCellValue('A4', 'Total')
                        ->setCellValue('A6', 'ID')
                        ->setCellValue('B6', 'Invoice Number')
                        ->setCellValue('C6', 'Invoice Date')
                        ->setCellValue('D6', 'Supplier')
                        ->setCellValue('E6', 'Tin Number')
                        ->setCellValue('F6', 'Sub Total')
                        ->setCellValue('G6', 'Vat')
                        ->setCellValue('H6', 'Total PAid')
                        ->setCellValue('I6', 'Payment Type')
                        ->setCellValue('J6', 'Cheque No')
                        ->setCellValue('K6', 'Cheque Date')
                        ->setCellValue('L6', 'Resolved')
                        ->setCellValue('M6', 'Notes')
                        ->setCellValue('O6', 'Brand')
                        ->setCellValue('P6', 'Size')
                        ->setCellValue('Q6', 'Pattern')
                        ->setCellValue('R6', 'Product Type')
                        ->setCellValue('S6', 'Qty')
                        ->setCellValue('T6', 'Price')
                        ->setCellValue('U6', 'Amount');

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Purchase');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            //end of printing column names
            //start while loop to get data
            $result = GetPurchaseReport($FromDateTime, $ToDateTime);
            $array = array();
            
            $printData= false;
            if(mysql_num_rows($result)>0) 
                $printData= true;
            while ($row = mysql_fetch_assoc($result)) {
                foreach ($row as $key => $value) {
                    $array[$key][] = $value;
                }
            }

            if($printData) {
                for($i=0; $i<sizeof($array['InvoiceID']); $i++) {
                    $arrVals = array_values($array['InvoiceID']);
                    $arrVals[$i] = '';
                    $index = array_search($array['InvoiceID'][$i], $arrVals);

                    while($index != NULL){                    
                        $array['InvoiceID'][$index] = NULL;

                        $arrVals[$index] = '';
                        $indexNew = array_search($array['InvoiceID'][$i], $arrVals);
                        
                        if($indexNew == $index)
                            break;
                        else $index = $indexNew;
                    }
                }

                //end of printing column names  
                //start while loop to get data
                $previousInvoice = NULL;
                $currentRow = 7;
                $count = 1;
                $total = 0;
                for($i=0; $i<sizeof($array['InvoiceID']); $i++)
                {
                    $paymentType ='';
                    if( $previousInvoice == NULL || $previousInvoice != $array['InvoiceID'][$i] ) {
                        //merge cells  if count >1
                        if($count > 1) {
                            //ChromePhp::log('A'. ($currentRow-1-$count) .':A'.($currentRow-1) );
                            // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'. ($currentRow-1-$count) .':A'.($currentRow-1) );
                        }
                        $count = 1;
                    }
                    else
                        $previousInvoice = $array['InvoiceID'][$i];
                        
                    // if( $array['InvoiceID'][$i] != NULL) {                        
                    //     $currentRow++;
                    // }

                    if(isset($array['BrandName'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('O'.$currentRow, $array['BrandName'][$i]);
                    }

                    if(isset($array['ProductSize'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('P'.$currentRow, $array['ProductSize'][$i]);
                    }

                    if(isset($array['Pattern'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('Q'.$currentRow, $array['Pattern'][$i]);
                    }

                    if(isset($array['ProductTypeName'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('R'.$currentRow, $array['ProductTypeName'][$i]);
                    }

                    if(isset($array['ProductQty'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('S'.$currentRow, $array['ProductQty'][$i]);
                    }

                    if(isset($array['UnitPrice'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('T'.$currentRow, $array['UnitPrice'][$i]);
                    }

                    if(isset($array['Amount'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('U'.$currentRow, $array['Amount'][$i]);
                    }

                    if( $array['InvoiceID'][$i] == NULL) {                        
                        $currentRow++;
                        continue;
                    }
                    


                    if(isset($array['InvoiceID'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$currentRow, $array['InvoiceID'][$i]);
                    }                        

                    if(isset($array['InvoiceNumber'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('B'.$currentRow, $array['InvoiceNumber'][$i]);
                    }
                    
                    if(isset($array['InvoiceDate'][$i])) {
                        $date = date_create($array['InvoiceDate'][$i]);
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('C'.$currentRow,date_format($date,'d-m-Y'));
                    }

                    if(isset($array['SupplierName'][$i])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('D'.$currentRow, $array['SupplierName'][$i]);
                    }

                    if(isset($array['TinNumber'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('E'.$currentRow, $array['TinNumber'][$i]);
                    }

                    if(isset($array['SubTotal'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('F'.$currentRow, $array['SubTotal'][$i]);
                    }

                    if(isset($array['VatAmount'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('G'.$currentRow, $array['VatAmount'][$i]);
                    }

                    if(isset($array['TotalPaid'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H'.$currentRow, $array['TotalPaid'][$i]);
                        $total+=$array['TotalPaid'][$i];
                    }

                    if(isset($array['PaymentType'][$i])){
                        if($array['PaymentType'][$i] == 1) {
                            $paymentType = 'Cash';
                        } else if($array['PaymentType'][$i] == 2) {
                            $paymentType = 'Card';
                        } else if($array['PaymentType'][$i] == 3) {
                            $paymentType = 'Cheque';
                        }                    
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('I'.$currentRow, $paymentType);
                    }

                    if($paymentType == 'Cheque') {                
                        if(isset($array['ChequeNo'][$i])) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('J'.$currentRow, $array['ChequeNo'][$i]);
                        }

                        if(isset($array['ChequeDate'][$i])) {
                            $date = date_create($array['ChequeDate'][$i]);

                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValue('K'.$currentRow, date_format($date,'d-m-Y'));
                        }

                        if(isset($array['Resolved'][$i])){
                            if($array['Resolved'][$i] == 0)
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValue('L'.$currentRow, 'No');
                            else
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValue('L'.$currentRow, 'Yes');                            
                        }
                    }

                    if(isset($array['Notes'][$i])){
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('M'.$currentRow, $array['Notes'][$i]);
                    }

                    $currentRow++;
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B4', $total);
            } else {
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B4', 0);
            }

        } else if($ReportTypeID == 5) {
            //non billable
            $ReportType = "Inventory";
           
            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'Report type')
                        ->setCellValue('B1', 'Invnentory')
                        ->setCellValue('A3', 'Product ID')
                        ->setCellValue('B3', 'Type')
                        ->setCellValue('C3', 'Brand')
                        ->setCellValue('D3', 'Size')
                        ->setCellValue('E3', 'Pattern')
                        ->setCellValue('F3', 'Quantity');           

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Inventory');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            //end of printing column names
            //start while loop to get data

            $result = GetProductStocks();
            $currentRow = 4;
            $total = 0;
            while ($Record = mysql_fetch_assoc($result)) {

                if(isset($Record['ProductID'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$currentRow, $Record['ProductID']);
                }
                
                if(isset($Record['ProductTypeName'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('B'.$currentRow, $Record['ProductTypeName']);
                }

                if(isset($Record['BrandName'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('C'.$currentRow, $Record['BrandName']);
                }

                if(isset($Record['ProductSize'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('D'.$currentRow, $Record['ProductSize']);
                }

                if(isset($Record['ProductPattern'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('E'.$currentRow, $Record['ProductPattern']);
                }

                if(isset($Record['Qty'])) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('F'.$currentRow, $Record['Qty']);
                }

                $currentRow++;
            }
        } else {
            $output = json_encode(array('success' => false));
        }

        // // Redirect output to a client’s web browser (OpenDocument)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if($ReportTypeID == 5)
            header('Content-Disposition: attachment;filename="' . $ReportType . '.xlsx"');
        else
            header('Content-Disposition: attachment;filename="'. $_POST['FromDate'] .' to '. $_POST['ToDate']. ' '. $ReportType . '.xlsx"');

        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

      }
      else
      {
        $output = json_encode(array('success' => false));
        die($output);
      }
      /* END */
    }
    else {
      //unathorized access
      $output = json_encode(array('success' => false));
      die($output);
    }

  ?>
