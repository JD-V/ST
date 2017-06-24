<?php
date_default_timezone_set('Asia/Kolkata');
$_SESSION['VatFactor'] = 14.5;
include 'ChromePhp.php';

ob_start();

$CDATA['CURRENT_FILE'] = @$_SERVER['SCRIPT_NAME'];
$CDATA['BASE_URL'] = "http://localhost/GMS2/GMS";

function DisableAutoCommit() {
  mysql_query('SET AUTOCOMMIT=0');
}

function EnableAutoCommit() {
  mysql_query('SET AUTOCOMMIT=1');
}

function PerformRollback() {
  mysql_query('ROLLBACK');
}

if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
{
  $http_referer = $_SERVER['HTTP_REFERER'];
}
else
{
  $http_referer = 'direct_link';
}


function ValidateLogin($userName,$userpwd){

  $checklogin = mysql_query("SELECT * FROM `user` WHERE `Name` = '$userName' ");

  if(mysql_num_rows($checklogin) == 1) {
    $user = mysql_fetch_assoc($checklogin);

    if($user['Status'] == '0') {
      return 0;
    } else if(password_verify($userpwd, $user['Password']) ) {
      return 1;
    } else {
      return 2;
    }
  }
  else
    return 3;
}

function isLogin() {

  if(isset($_SESSION['userName']) && !empty($_SESSION['userName'])) {
    return true;
  } else {
    return false;
  }
}

function getUserRole() {

  if(isset($_SESSION['userName']) && !empty($_SESSION['userName'])) {
    $userName = $_SESSION['userName'];
    $GetRoleName = mysql_query("SELECT r.RoleName FROM user u JOIN role r ON u.RoleID = r.RoleID WHERE u.Name = '$userName' ");

    if(mysql_num_rows($GetRoleName)==1) {
        $RoleName = mysql_fetch_assoc($GetRoleName);
        echo $RoleName['RoleName'];
    } else {
      echo "null";
    }
  } else {
    echo 'No User';
  }
}

function getUserRoleID() {

  if(isset($_SESSION['userName']) && !empty($_SESSION['userName'])) {
    $userName = $_SESSION['userName'];
    $GetRoleID = mysql_query("SELECT * FROM user WHERE Name = '$userName' ");
    if(mysql_num_rows($GetRoleID)==1) {
        $ShowData = mysql_fetch_assoc($GetRoleID);
        $RoleID  = $ShowData['RoleID'];
        return $RoleID;
    }
    else {
      return 0;
    }
  }
  else {
    return 0;
  }
}

function getUserName() {

    if(isset($_SESSION['userName']) && !empty($_SESSION['userName'])) {
      echo $_SESSION['userName'];
    } else echo 'null';
}


function isAdmin() {

  $userName = $_SESSION['userName'];

  if($CheckAdmin = mysql_query("SELECT * FROM user WHERE RoleID <= '2' AND Name = '$userName' ")) {
    if(mysql_num_rows($CheckAdmin) == 1) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function GetServices() {

  if($getServiceList = mysql_query("SELECT * FROM service")) {
    ChromePhp::log("true");
    if(mysql_num_rows($getServiceList) >= 1) {
      ChromePhp::log("true1");
      return $getServiceList;
    }
  } else {
    return false;
  }
}

function GetServices2() {
  $SRArray = array();
  if($getServiceList = mysql_query("SELECT * FROM service")) {
    ChromePhp::log("true");
    if(mysql_num_rows($getServiceList) >= 1) {
      while ($service = mysql_fetch_assoc($getServiceList)) {
        $ServiceRecord = new ServiceRecord();
        $ServiceRecord->invoiceNumber = $service['InvoiceNumber'];
        $date = date_create($service['InvoiceDateTime']);
        $ServiceRecord->invoiceDate = date_format($date, 'd-m-Y H:i');
        $ServiceRecord->customerName = $service['CustomerName'];
        $ServiceRecord->customerPhone = $service['CustomerPhone'];
        $ServiceRecord->vehicleNumber = $service['VehicleNumber'];
        $ServiceRecord->vehicleMileage = $service['VehicleMileage'];
        $ServiceRecord->subTotal = $service['SubTotal'];
        $ServiceRecord->discountAmount = $service['Discount'];
        $ServiceRecord->amountPaid = $service['AmountPaid'];
        $ServiceRecord->address = $service['Address'];
        $ServiceRecord->notes = $service['Note'];

        $SRArray[] = $ServiceRecord;
      }
    }
  }
  return $SRArray; 
}


function GetOrders() {

  if($getOrdersList = mysql_query("SELECT * FROM sales")) {
      return $getOrdersList;
  } else {
    return false;
  }
}

function GetMaxServiceInoviceNumber()
{
  if($maxInvoiceNumber = mysql_query("SELECT MAX(InvoiceNumber) as InvoiceNumber FROM service")) {
      if(mysql_num_rows($maxInvoiceNumber) >= 1) {
        $ShowData = mysql_fetch_assoc($maxInvoiceNumber);
        return $ShowData['InvoiceNumber'];
      }
    } else {
      return false;
    }
}

function GetMaxProductInventoryID()
{
  if($maxProductID = mysql_query("SELECT MAX(ProductID) as ProductID FROM productinvetory")) {
      if(mysql_num_rows($maxProductID) >= 1) {
        $ShowData = mysql_fetch_assoc($maxProductID);
        return $ShowData['ProductID'];
      }
    } else {
      return false;
    }
}


function GetMaxSalesInoviceNumber()
{
  if($maxInvoiceNumber = mysql_query("SELECT MAX(InvoiceNumber) as InvoiceNumber FROM sales")) {
      if(mysql_num_rows($maxInvoiceNumber) >= 1) {
        $ShowData = mysql_fetch_assoc($maxInvoiceNumber);
        return $ShowData['InvoiceNumber'];
      }
    } else {
      return false;
    }
}

function GetNonBillables() {

  if($getNonBillablesList = mysql_query("SELECT * FROM `nonbillable`")) {
    if(mysql_num_rows($getNonBillablesList) >= 1) {
      return $getNonBillablesList;
    }
  } else {
    return false;
  }
}


function GetServiceRecord($InvoiceNumber) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `service` WHERE `InvoiceNumber` = '$InvoiceNumber' ") ) {
    if(mysql_num_rows($GetRecordInformation) == 1) {
      return $GetRecordInformation;
    }
  }
  else {
    return false;
  }
}

function GetProductsSalesInInvoice($InvoiceNumber) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `salesproducts` WHERE `InvoiceNumber` = '$InvoiceNumber' ") ) {
    if(mysql_num_rows($GetRecordInformation) >= 1) {
      return $GetRecordInformation;
    }
  }
  else {
    return false;
  }
}


function GetSaleInvoice($InvoiceNumber) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `sales` WHERE `InvoiceNumber` = '$InvoiceNumber' ") ) {
    if(mysql_num_rows($GetRecordInformation) == 1) {
      return $GetRecordInformation;
    }
  }
  else {
    return false;
  }

}

function GetServiceRecordItems($InvoiceNumber) {
  return  mysql_query("SELECT * FROM `serviceitems` WHERE InvoiceNumber='$InvoiceNumber' ");
}


function GetOrderRecord($InvoiceNumber) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `sales` WHERE InvoiceNumber = '$InvoiceNumber' ") ) {
    if(mysql_num_rows($GetRecordInformation) == 1) {
      return $GetRecordInformation;
    }
  }
  else {
    return false;
  }
}

function GetOrderRecordProducts($InvoiceNumber) {

  return  mysql_query("SELECT sp.*, pt.ProductTypeName FROM salesproducts sp 
    JOIN productinvetory pi ON sp.ProductID = pi.ProductID JOIN producttype pt ON pt.ProductTypeID 
    = pi.ProductTypeID WHERE sp.InvoiceNumber='$InvoiceNumber' ");
    
}

function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function SaveForgotPasswordKey($email,$key) {

  if($updateKey = mysql_query("UPDATE user SET PasswordRecovery = '$key' WHERE Email='$email'")) {
    return 1;
  } else {
    return 0;
  }
}

function updatePassword($userID,$password) {

  $hashPassword = password_hash($password, PASSWORD_DEFAULT);

  if($updatePassword = mysql_query("UPDATE user SET PasswordRecovery = NULL, Password ='$hashPassword' WHERE UserID='$userID' ")) {
    return 1;
  } else {
    return 0;
  }
}

function checkPasswordRecoveryKey($key) {
  return mysql_query("SELECT * FROM `user` WHERE `PasswordRecovery` = '$key'");
}

function getUserInfo($user_id) {

  if($getUserInformation = mysql_query("SELECT * FROM `user` WHERE UserID = '$user_id' ")) {
    if(mysql_num_rows($getUserInformation) == 1) {
      return $getUserInformation;
    }
  } else {
    return false;
  }
}

function GetUserID($userName) {
  if($getUserID = mysql_query("SELECT UserID FROM user WHERE Name = '$userName' ")) {
    if(mysql_num_rows($getUserID) >= 1) {
      $ShowData = mysql_fetch_assoc($getUserID);
      return $ShowData['UserID'];
    }
  } else {
    return 0;
  }
}

function BlockUserByID($userID) {
  $updateUser = mysql_query("UPDATE `user` SET `Status` = 0 WHERE `UserID` = '$userID' " );

   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

function UnBlockUserByID($userID) {
    $updateUser = mysql_query("UPDATE `user` SET `Status` = 1 WHERE `UserID` = '$userID' " );

   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

function GetUsers() {
    if($getUsers = mysql_query("SELECT u.*, r.RoleName FROM user u JOIN role r ON u.RoleID = r.RoleID WHERE Hidden=0")) {
    return $getUsers;
  } else {
    return false;
  }
}

function getLocations() {

  if($getLocations = mysql_query("SELECT * FROM location")) {
    if(mysql_num_rows($getLocations) >= 1) {
      return $getLocations;
    }
  } else {
    return false;
  }
}

function GetNonBillableRecord($Record_id) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `nonbillable` WHERE RecordID = '$Record_id' ") ) {
    if(mysql_num_rows($GetRecordInformation) == 1) {
      return $GetRecordInformation;
    }
  } else {
    return false;
  }
}

function GetOrderCheques($UnResolved) {
  if($UnResolved == 0) {
    if($GetOrderCheques = mysql_query("SELECT InvoiceNumber, ChequeNo, chequeDate, CustomerName, AmountPaid, Resolved FROM `sales` WHERE chequeNo != '' ") ) {
        return $GetOrderCheques;
    }
  } else {
    if($GetOrderCheques = mysql_query("SELECT InvoiceNumber, ChequeNo, chequeDate, CustomerName, AmountPaid, Resolved  FROM `sales` WHERE chequeNo != '' AND Resolved=0") ) {
        return $GetOrderCheques;
    }
  }
}

function GetPurchaseInvoiceCheques($UnResolved) {
  if($UnResolved == 0) {
    if($GetInvoiceCheques = mysql_query("SELECT InvoiceNumber, ChequeNo, ChequeDate, Company, TotalPaid, Resolved FROM `purchaseinvoice` WHERE chequeNo != '' ") ) {
      return $GetInvoiceCheques; 
    }
  } else {
    if($GetInvoiceCheques = mysql_query("SELECT InvoiceNumber, ChequeNo, ChequeDate, Company, TotalPaid, Resolved FROM `purchaseinvoice` WHERE chequeNo != '' AND Resolved=0") ) {
      return $GetInvoiceCheques; 
    }
  }
}

function ResolveSalesCheque($ChequeNo, $Invoice) {
    ChromePhp::log("1ChequeNo" . $ChequeNo);
  ChromePhp::log("1Invoice" . $Invoice);
  $resolveSalesCheque = mysql_query("UPDATE sales SET Resolved=1 WHERE `InvoiceNumber` = '$Invoice' AND `ChequeNo` = '$ChequeNo' " );
   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

function ResolvePurchaseCheque($ChequeNo, $Invoice) {
  ChromePhp::log("2ChequeNo" . $ChequeNo);
  ChromePhp::log("2Invoice" . $Invoice);
  $resolvePurchaseCheque = mysql_query("UPDATE purchaseinvoice SET Resolved=1 WHERE `InvoiceNumber` = '$Invoice' AND `ChequeNo` = '$ChequeNo' " );
   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}


function GetSuppliersRecords($SupplierID) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `supplier` WHERE SupplierID = '$SupplierID' ") ) {
    if(mysql_num_rows($GetRecordInformation) == 1) {
      return $GetRecordInformation;
    }
  } else {
    return false;
  }
}

function GetUpcomingChequesCount() {

  $total = 0;

  if($GetInvoiceCheques = mysql_query("SELECT InvoiceNumber, ChequeNo, ChequeDate, Company, TotalPaid, Resolved FROM `purchaseinvoice` WHERE chequeNo != '' AND Resolved=0 ") ) {
      $total +=  mysql_num_rows($GetInvoiceCheques);
  }

  if($GetOrderCheques = mysql_query("SELECT InvoiceNumber, ChequeNo, chequeDate, CustomerName, AmountPaid, Resolved FROM `sales` WHERE chequeNo != '' AND Resolved=0 ") ) {
      $total +=  mysql_num_rows($GetOrderCheques);
  }
  echo $total;
}

function GetMaxAllocID() {

  if($maxAllocID = mysql_query("SELECT MAX(AllocID) as maxAllocID FROM roomsalloc")) {
    if(mysql_num_rows($maxAllocID) >= 1) {

      $ShowData = mysql_fetch_assoc($maxAllocID);
      return $ShowData['maxAllocID'];
    }
  } else {
    return false;
  }
}

function RemoveLocation($LocationId) {

  ChromePhp::log("LocID : " .$LocationId);
  $RemoveLocation =  mysql_query("DELETE FROM `location` WHERE `LocationID` = $LocationId ");
  $result = mysql_affected_rows();
  if($result == -1)
    print 'can not delete.!!  Room is Already Occupied.';
  else if ($result == 0)
    print 'Something went wrong. Try after refreshing page once.';
  else
    print 1;
}

function RemoveBrand($BrandID) {

  $RemoveBrand =  mysql_query("UPDATE `brands` SET `Depricated` = 1 WHERE `BrandID` = $BrandID ");
  $result = mysql_affected_rows();
  if($result == -1)
    print mysql_error();
  else
    print 1;
}

function AddLocation($Location) {
  $addLocaiton = mysql_query(" INSERT INTO `location` (`LocationName`) VALUES ( '$Location->LocName')" );
  if($addLocaiton)
    return 1;
  else
    return 0;
}

function AddBrand($Brand) {
  $addBrand = mysql_query(" INSERT INTO `brands` (`BrandName`) VALUES ( '$Brand->BrandName')" );

  return mysql_affected_rows();
}

function UpdateLocation($Location) {

  $updateLocation = mysql_query(" UPDATE `location` SET `LocationName` = '$Location->LocName' WHERE `LocationID` = $Location->LocId " );

   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

function UpdateBrand($Brand) {

  $updateBrand = mysql_query(" UPDATE `brands` SET `BrandName` = '$Brand->BrandName' WHERE `BrandID` = '$Brand->BrandID' " );

   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

 function GetRoles() {
   if($getRoleList = mysql_query("SELECT * FROM role")) {
     if(mysql_num_rows($getRoleList) >= 1) {
       return $getRoleList;
     }
   } else  {
     return false;
   }
}

function AddUser($user) { 
    ChromePhp::log("max user id". GetMaxUserID());
    $hashPassword = password_hash($user->password, PASSWORD_DEFAULT);
    ChromePhp::log("user id".$user->userID);
    if($result = mysql_query("INSERT INTO user (`UserID`, `Name`, `Password`, `RoleID`, `UserPhone`, `Address`, `Status`)
      VALUES ('$user->userID','$user->userName','$hashPassword','$user->userRoleID','$user->userPhone','$user->userAddr','$user->status')" ) )
    {
      return true;
        ChromePhp::log("Added");
    } else {
      if (false === $result) {
        echo mysql_error();
      }
      return false;
    }
}

function AddNonBillable($RecDate,$Perticulars,$AmountPaid,$Notes){
    $userID = $_SESSION['userID'];
    if($result = mysql_query("INSERT INTO nonbillable (RecordDate, Perticulars, AmountPaid, Notes, UserID)
                        VALUES ('$RecDate', '$Perticulars','$AmountPaid','$Notes','$userID')" ) )
    {
      return true;
    } else { 
      if (false === $result) {
        //echo mysql_error();
      }
      return false;
    }
}

function AddSupplier($Name,$TinNum,$MobileNum,$Email,$Address,$ContactPerson){
    if($result = mysql_query("INSERT INTO supplier (SupplierName, TinNumber, Mobile, Email,Address, ContactPerson)
                        VALUES ('$Name', '$TinNum','$MobileNum','$Email', '$Address', '$ContactPerson')" ) )
    {
      return true;
    } else { 
      if (false === $result) {
        //echo mysql_error();
      }
      return false;
    }
}


function GetSuppliers() {
  if ($suppliers=mysql_query("SELECT * FROM `supplier`")) {
    if (mysql_num_rows($suppliers) >=1) {
      return $suppliers;
    }
  } else {
    return false;
  }
}

function UpdateSupplier($SupplierID, $SupplierName, $TinNum, $MobileNum, $Email, $Address, $ContactPerson)
{
  if($updateKey = mysql_query("UPDATE supplier SET SupplierName   = '$SupplierName' , TinNumber = '$TinNum', Mobile = '$MobileNum',
   Email = '$Email', Address = '$Address', ContactPerson = '$ContactPerson' WHERE SupplierID='$SupplierID'"))
  {
    return true;
  } else {
    return false;
  }
}



function UpdateNonBillable($RecordId,$RecDate,$Perticulars,$AmountPaid,$Notes){

  if($updateKey = mysql_query("UPDATE nonbillable SET RecordDate   = '$RecDate' , Perticulars = '$Perticulars', AmountPaid = '$AmountPaid',
   Notes = '$Notes' WHERE RecordID='$RecordId'"))
  {
    return true;
  } else {
    return false;
  }
}

function UpdateServicerecord($ServiceRecord){

  if($updateKey = mysql_query("UPDATE service SET InvoiceDateTime = '$ServiceRecord->invoiceDate',
   CustomerName = '$ServiceRecord->customerName', CustomerPhone='$ServiceRecord->customerPhone',
   VehicleNumber='$ServiceRecord->vehicleNumber',  AmountPaid='$ServiceRecord->amountPaid',
   Note='$ServiceRecord->notes' WHERE InvoiceNumber='$ServiceRecord->invoiceNumber' "))
  {
    return true;
  } else {
    return false;
  }
}

function Addservicerecord($ServiceRecord) {

  if($result = mysql_query("INSERT INTO service (InvoiceDateTime,CustomerName,CustomerPhone, VehicleNumber, AmountPaid, Note)
      VALUES ('$ServiceRecord->invoiceDate','$ServiceRecord->customerName','$ServiceRecord->customerPhone',
      '$ServiceRecord->vehicleNumber', '$ServiceRecord->amountPaid', '$ServiceRecord->notes')" ) )
    {
      return true;
    } else { 
      if (false === $result) {
        //echo mysql_error();
      }
      return false;
    }
}
function GetServiceInvoice($InvoiceNumber) {
    return mysql_query("SELECT * FROM service WHERE InvoiceNumber='$InvoiceNumber' ");
}

function GetServiceablesInInvoice($InvoiceNumber) {
    return mysql_query("SELECT * FROM serviceitems WHERE InvoiceNumber='$InvoiceNumber' ");

    
}

function GetPuchaseInvoiceByID($InvoiceID) {
  $GetInvoice = mysql_query("SELECT p.InvoiceID, p.InvoiceDate, p.InvoiceNumber, p.Company AS 
  SupplierName, p.TinNumber, p.VatAmount, p.TotalPaid, p.SubTotal, p.PaymentType, p.ChequeNo, 
  p.ChequeDate, p.SupplierID, p.Notes, s.TinNumber, s.SupplierName FROM purchaseinvoice p 
  LEFT JOIN supplier s ON s.SupplierID=p.SupplierID WHERE p.InvoiceID = '$InvoiceID' ");

  if(mysql_num_rows($GetInvoice)==1) {
    $ShowData = mysql_fetch_assoc($GetInvoice);
    $Invoice  = new Invoice();
    $Invoice->invoiceID = $ShowData['InvoiceID'];
    $Invoice->invoiceDate = $ShowData['InvoiceDate'];
    $Invoice->invoiceNumber = $ShowData['InvoiceNumber'];
    $Invoice->supplierID = $ShowData['SupplierID'];
    $Invoice->tinNumber = $ShowData['TinNumber'];
    $Invoice->vatAmount = $ShowData['VatAmount'];
    $Invoice->totalAmount = $ShowData['TotalPaid']; 
    $Invoice->subTotalAmount = $ShowData['SubTotal'];
    $Invoice->paymentType = $ShowData['PaymentType'];
    $Invoice->chequeNo=$ShowData['ChequeNo'];
    $Invoice->chequeDate=$ShowData['ChequeDate'];
    $Invoice->invoiceNotes = $ShowData['Notes'];
    $Invoice->supplierName = $ShowData['SupplierName'];
    return $Invoice;
  } else return NULL;
}


function GetProductsInInvoices($InvoiceID) {
  $GetProducts = mysql_query("SELECT p.*, pt.ProductTypeName, br.BrandName AS ProductBrand 
  FROM products p JOIN producttype pt ON p.ProductType = pt.ProductTypeID LEFT JOIN brands 
  br ON br.BrandID = p.ProductBrandID WHERE p.InvoiceID = '$InvoiceID'");
  $ProductArr = array();

  if(mysql_num_rows($GetProducts)>=1) {
    while($ShowData = mysql_fetch_assoc($GetProducts)) {
      $Product = new Product();
      $Product->invoiceID = $ShowData['InvoiceID'];
      $Product->productTypeID = $ShowData['ProductType'];
      $Product->productTypeName = $ShowData['ProductTypeName'];
      $Product->productSize = $ShowData['ProductSize'];
      $Product->brand = $ShowData['ProductBrand'];
      $Product->productPattern = $ShowData['Pattern'];
      $Product->units= $ShowData['ProductQty'];
      $Product->rate = $ShowData['UnitPrice'];
      $Product->Amount = $ShowData['Amount'];

      $ProductArr[] = $Product;
    }
  }
  return $ProductArr;
}


function GetMaxInvoiceID() {

    $GetInvoiceID = mysql_query("SELECT MAX(InvoiceID) as InvoiceID FROM purchaseinvoice");

    if(mysql_num_rows($GetInvoiceID)>=1) {
        $ShowData = mysql_fetch_assoc($GetInvoiceID);
        return $ShowData['InvoiceID'];
      }
}

function GetStaffNamesList() {

     if($getStaffNames = mysql_query("SELECT UserID, Name FROM user")) {
       ChromePhp::log("true");
       if(mysql_num_rows($getStaffNames) >= 1) {
         ChromePhp::log("true1");
         return $getStaffNames;
       }
     }
     else {
       return false;
     }
}

function GetNonBillableReport($fromDate,$toDate) {
 return mysql_query("SELECT * FROM `nonbillable` WHERE RecordDate >='$fromDate' AND RecordDate <= '$toDate' ");
}

function GetSalesReport($fromDate,$toDate) {
 return mysql_query("SELECT sa.*, sp.* FROM sales sa JOIN salesproducts sp ON sa.InvoiceNumber = sp.InvoiceNumber WHERE 
                     sa.InvoiceDateTime>='$fromDate' AND sa.InvoiceDateTime <='$toDate' 
                     ORDER BY sa.InvoiceNumber, sa.InvoiceDateTime asc");
}

function GetPurchaseReport($fromDate,$toDate) {
 return mysql_query("SELECT pi.*, p.*,s.SupplierName,s.TinNumber,b.BrandName,pt.ProductTypeName FROM purchaseinvoice pi 
                      JOIN products p ON pi.InvoiceID = p.InvoiceID INNER JOIN supplier s ON s.SupplierID = pi.SupplierID 
                      INNER JOIN brands b ON b.BrandID = p.ProductBrandID INNER JOIN producttype pt ON p.ProductType = pt.ProductTypeID WHERE 
                      pi.InvoiceDate>='$fromDate' AND pi.InvoiceDate <='$toDate' 
                      ORDER BY pi.InvoiceID, pi.InvoiceDate asc");
}


function GetServiceReport($fromDate,$toDate) {
 return mysql_query("SELECT se.*, si.* FROM service se JOIN serviceitems si ON se.InvoiceNumber = si.InvoiceNumber WHERE 
                      se.InvoiceDateTime>='$fromDate' AND se.InvoiceDateTime <='$toDate' 
                      ORDER BY se.InvoiceNumber, se.InvoiceDateTime asc");
}

function AddInvoice($Invoice){

  ChromePhp::log('InvoiceDate ' . $Invoice->invoiceDate );
  ChromePhp::log('InvoiceID ' . $Invoice->invoiceID );
  ChromePhp::log('InvoiceNumber ' . $Invoice->invoiceNumber );
  ChromePhp::log('TotalPaid ' . $Invoice->totalAmount);
  ChromePhp::log('Notes' .$Invoice->invoiceNotes);


  $addInvoice = mysql_query(
              "INSERT INTO `purchaseinvoice` (`InvoiceID`, `InvoiceDate`, `SupplierID`,
              `InvoiceNumber`,`SubTotal`, `VatAmount`, `TotalPaid`,
              `PaymentType`, `ChequeNo`, `ChequeDate`, `Notes` ) VALUES 
              ('$Invoice->invoiceID', '$Invoice->invoiceDate', '$Invoice->supplierID', 
              '$Invoice->invoiceNumber','$Invoice->subTotalAmount',
              '$Invoice->vatAmount', '$Invoice->totalAmount', '$Invoice->paymentType',
              '$Invoice->chequeNo', '$Invoice->chequeDate', '$Invoice->invoiceNotes' )" );

  if($addInvoice)
    return 1;
  else
    echo mysql_error();
    return 0;
}

function AddProduct($Product){

  ChromePhp::log('InvoiceID ' . $Product->invoiceID );
  ChromePhp::log('productSize ' . $Product->productSize );
  ChromePhp::log('brand ' . $Product->brandID );
  ChromePhp::log('productPattern ' . $Product->productPattern );
  ChromePhp::log('units '. $Product->units);
  ChromePhp::log('rate ' . $Product->rate);
  ChromePhp::log('amount ' . $Product->amount);

  $addProduct = mysql_query(
          "INSERT INTO `products` (`InvoiceID`, `ProductSize`,`ProductType`,`ProductBrandID`, 
          `Pattern`, `ProductQty`, `UnitPrice`, `Amount` ) VALUES 
          ('$Product->invoiceID','$Product->productSize','$Product->productTypeID', '$Product->brandID', 
          '$Product->productPattern', '$Product->units', '$Product->rate', 
          '$Product->amount' )" );

  // if($addProduct)

  //   return 1;
  // else
  //   echo mysql_error();
  //   return 0;
    $object = new stdClass();
    if($addProduct)
      $object->isProductAdded = 1;
    else
      $object->isProductAdded = 0;

    mysql_query("CALL InsertProductInventory('$Product->brandID',
    '$Product->productSize','$Product->productPattern','$Product->productTypeID',
    '', '1','$Product->units',@isProductAdded,@isStockAdded);");
    
    $addProductInventory = mysql_query("SELECT @isProductAdded as isProductAdded, @isStockAdded  as isStockAdded;");
   
    if($addProductInventory) {
      // ChromePhp::log("retrived result");
      
      $result = mysql_fetch_assoc($addProductInventory);
      // ChromePhp::log($result);
      $object->isInventoryProductAdded = $result['isProductAdded'];
      $object->isStockAdded = $result['isStockAdded'];
    }
    else {
      ChromePhp::log("failed to retrived result");
      $object->isInventoryProductAdded = 0;
      $object->isStockAdded = 0;

    }
    
    return $object;

}

function GetInvoices(){

   if($getInvoices = mysql_query("SELECT * FROM `purchaseinvoice`")) {
    if(mysql_num_rows($getInvoices) >= 1) {
      return $getInvoices;
    }
  } else {
    return false;
  }
}

function GetLastInsertedID() {
  if($lastInsertedID = mysql_query("SELECT LAST_INSERT_ID() AS 'ID'")) {
    if(mysql_num_rows($lastInsertedID) >= 1) {
      $ShowData = mysql_fetch_assoc($lastInsertedID);
      return $ShowData['ID'];
    }
  } else {
    return 0;
  } ;
}

function GetProducts() {

  if($getProducts = mysql_query("SELECT p.*, pi.InvoiceNumber, pt.ProductTypeName FROM products p 
      JOIN purchaseinvoice pi ON pi.InvoiceID = p.InvoiceID JOIN producttype pt ON p.ProductType = pt.ProductTypeID")) {
    if(mysql_num_rows($getProducts) >= 1) {
      return $getProducts;
    }
  } else {
    return false;
  }
}

function getServiceable($Depricated = 0) {

  if($Depricated == 0)
    return mysql_query("SELECT * FROM `serviceable` WHERE Depricated = '0'");
  else
    return mysql_query("SELECT * FROM `serviceable` ");
}

function MarkServiceableDepricate($ItemID) {

  if($updateServiceable = mysql_query("UPDATE serviceable SET Depricated = 1 WHERE ItemID='$ItemID' ")) {
    return 1;
  } else {
    return 0;
  }
}

function UpdateServiceable($srv) {
  
  $updateServiceable = mysql_query(" UPDATE `serviceable` SET `Item` = '$srv->Item', `Price` = '$srv->Price'  WHERE `ItemID` = '$srv->ItemID' " );

   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

function AddServiceable($srv) {

  $addItem = mysql_query(" INSERT INTO `serviceable` (`Item`,`Price`) VALUES ( '$srv->Item', '$srv->Price')" );

  if($addItem)
    return 1;
  else
    return 0;

}

function GetBrands() {
  
  if($getBrands = mysql_query("SELECT * FROM brands WHERE `Depricated`= 0")) {
    if(mysql_num_rows($getBrands) >= 1) {
      return $getBrands;
    }
  } else {
    return false;
  }
}


function GetProdcutTypes(){
  if($getProductTypes = mysql_query("SELECT * FROM producttype")) {
    if(mysql_num_rows($getProductTypes) >= 1) {
      return $getProductTypes;
    }
  } else {
    return false;
  }
}


function GetProdcutTypes2() {
  $ProductTypeArray = array();
  if($getProductTypes = mysql_query("SELECT * FROM producttype")) {
    if(mysql_num_rows($getProductTypes) >= 1) {
      while ($type = mysql_fetch_assoc($getProductTypes)) {
        $ProductType = new productType();
        $ProductType->productTypeID = $type['ProductTypeID'];
        $ProductType->productTypeName = $type['ProductTypeName'];
        $ProductTypeArray[] = $ProductType;
      }
    }
  }
  return $ProductTypeArray;
}

function AddProductInventory($ProductInventory) {

  mysql_query("CALL InsertProductInventory('$ProductInventory->brandID',
  '$ProductInventory->productSize','$ProductInventory->productPattern','$ProductInventory->productTypeID',
  '$ProductInventory->productNotes', '$ProductInventory->minStockAlert','$ProductInventory->qty',@isProductAdded,@isStockAdded);");
  
  $addProductInventory = mysql_query("SELECT @isProductAdded as isProductAdded, @isStockAdded  as isStockAdded;");
  $object = new stdClass();
  if($addProductInventory) {
    ChromePhp::log("retrived result");
    
    $result = mysql_fetch_assoc($addProductInventory);
    ChromePhp::log($result);
    $isProductAdded = $result['isProductAdded'];
    $isStockAdded = $result['isStockAdded'];

    $object->isProductAdded = $isProductAdded;
    $object->isStockAdded = $isStockAdded;
  }
  else {
    ChromePhp::log("failed to retrived result");
    $object->isProductAdded = 0;
    $object->isStockAdded = 0;

  }
  
  return $object;
}

function UpdateProductInventory($ProductInventory) {

  $updateProductInventory = mysql_query("UPDATE `productinvetory` SET 
    `BrandID` = '$ProductInventory->brandID', `ProductSize` = '$ProductInventory->productSize', 
    `ProductPattern` = '$ProductInventory->productPattern', `SupplierID` = '$ProductInventory->supplierID',
    `ProductTypeID`= '$ProductInventory->productTypeID', `CostPrice` = '$ProductInventory->costPrice', 
    `MinSellPrice` = '$ProductInventory->minSellingPrice', `MaxSellPrice` = '$ProductInventory->maxSellingPrice', 
    `ProductNotes` = '$ProductInventory->productNotes', `MinStockAlert` = '$ProductInventory->minStockAlert',
    `dateOfEntry` = '$ProductInventory->dateOfEntry' WHERE `ProductID` =  '$ProductInventory->productID' " );

  if($updateProductInventory)
    return 1;
  else
    echo mysql_error();
    return 0;
}

function GetProductInventory(){
  
  if($getProductInventory = mysql_query("select p.*, pt.ProductTypeName, b.BrandName, s.SupplierName from productinvetory p JOIN brands b ON p.BrandID = b.BrandID JOIN supplier s ON p.SupplierID = s.SupplierID JOIN producttype pt ON p.ProductTypeID = pt.ProductTypeID")) {
    ChromePhp::log("true got supps");
    if(mysql_num_rows($getProductInventory) >= 1) {
      return $getProductInventory;
    }
  }
  else {
    return false;
  }
}

/*
This Method retruns Array of productInvenytory Objects
*/
function GetProductInventory2(){
  
  $ProductInventoryArray = array();

  if($getProductInventory = mysql_query("select p.*, pt.ProductTypeName, b.BrandName from productinvetory p JOIN brands b ON p.BrandID = b.BrandID JOIN producttype pt ON p.ProductTypeID = pt.ProductTypeID")) {
    if(mysql_num_rows($getProductInventory) >= 1) {
      while ($product = mysql_fetch_assoc($getProductInventory)) {
        $ProductInventory = new ProductInventory();
        $ProductInventory->productID = $product['ProductID'];
        $ProductInventory->brandID = $product['BrandID'];
        $ProductInventory->brandName = $product['BrandName'];
        $ProductInventory->productSize = $product['ProductSize'];
        $ProductInventory->productPattern = $product['ProductPattern'];
        $ProductInventory->productTypeID = $product['ProductTypeID'];
        $ProductInventory->productTypeName = $product['ProductTypeName'];
        $ProductInventory->productNotes = $product['ProductNotes'];
        $ProductInventory->minStockAlert = $product['MinStockAlert'];
        $ProductInventory->dateOfEntry = $product['DateOfEntry'];
        $date = date_create($product['LastModified']);
        $ProductInventory->lastModified = date_format($date,'d-m-Y H:i');;
        $ProductInventoryArray[] = $ProductInventory;
      }
    }
  }
  return $ProductInventoryArray;
}

/*
This Method retruns productInvenytory Object
*/
function GetProductInventoryByID2($productID){
  $ProductInventory = new ProductInventory();
  
  if($getProductInventory = mysql_query("select p.*, pt.ProductTypeName, b.BrandName from productinvetory p 
      JOIN brands b ON p.BrandID = b.BrandID JOIN producttype pt ON p.ProductTypeID = pt.ProductTypeID WHERE 
      p.ProductID = '$productID'" )) {
    if(mysql_num_rows($getProductInventory) == 1) {
      while ($product = mysql_fetch_assoc($getProductInventory)) {
        $ProductInventory->productID = $product['ProductID'];
        $ProductInventory->brandID = $product['BrandID'];
        $ProductInventory->brandName = $product['BrandName'];
        $ProductInventory->productSize = $product['ProductSize'];
        $ProductInventory->productPattern = $product['ProductPattern'];
        $ProductInventory->productTypeID = $product['ProductTypeID'];
        $ProductInventory->productTypeName = $product['ProductTypeName'];        
        $ProductInventory->costPrice = $product['CostPrice'];
        $ProductInventory->minSellingPrice = $product['MinSellPrice'];
        $ProductInventory->maxSellingPrice = $product['MaxSellPrice'];
        $ProductInventory->productNotes = $product['ProductNotes'];
        $ProductInventory->minStockAlert = $product['MinStockAlert'];
        $ProductInventory->dateOfEntry = $product['DateOfEntry'];
        $ProductInventory->lastModified = $product['LastModified'];
      }
      return $ProductInventory;
    }
  }

  return NULL;
}

function GetProductInventoryByID($productID){
  
  if($getProductInventory = mysql_query("select p.*, pt.ProductTypeName, b.BrandName, s.SupplierName from productinvetory p JOIN brands b ON p.BrandID = b.BrandID JOIN supplier s ON p.SupplierID = s.SupplierID JOIN producttype pt ON p.ProductTypeID = pt.ProductTypeID WHERE p.ProductID = '$productID' ")) {
    if(mysql_num_rows($getProductInventory) >= 1) {
      return $getProductInventory;
    }
  }
  else {
    return false;
  }
}

function GetProductStocks() {

  if($getProductInventory = mysql_query("SELECT pr.ProductID, br.BrandName, pr.ProductSize, pr.ProductPattern, 
    SUM(st.Qty) AS Qty, pr.MinSellPrice,pr.MaxSellPrice, pt.ProductTypeName FROM productinvetory pr LEFT JOIN 
    stockentries st ON pr.ProductID = st.ProductID JOIN brands br ON br.BrandID = pr.BrandID JOIN producttype 
    pt ON pt.ProductTypeID = pr.ProductTypeID GROUP BY ProductID")) {

    if(mysql_num_rows($getProductInventory) >= 1) {
      return $getProductInventory;
    }
  }
  else {
    return false;
  }
}

function GetCustomerDetailsByVehicleNumber($VehicleNumber) {

  if($getCustomerDetails = mysql_query("SELECT * FROM `sales` WHERE VehicleNumber = '$VehicleNumber' ORDER BY InvoiceNumber DESC LIMIT 1")) {
    if(mysql_num_rows($getCustomerDetails) >= 1) {
      return $getCustomerDetails;
    }
  }
  return NULL;
}

function getProductWithStocks($BrandID) {
  
  if($getProductInventory = mysql_query("SELECT pr.ProductID, pr.ProductSize, pr.ProductPattern, pr.BrandID, pr.ProductTypeID,pt.ProductTypeName,
     pr.CostPrice, pr.MinSellPrice, pr.MaxSellPrice, br.BrandName, SUM(st.Qty) AS Qty FROM productinvetory pr LEFT JOIN stockentries st 
     ON pr.ProductID = st.ProductID JOIN producttype pt ON pt.ProductTypeID = pr.ProductTypeID JOIN brands br ON 
     pr.BrandID = br.BrandID WHERE pr.BrandID = '$BrandID' GROUP BY ProductID ")) {
    if(mysql_num_rows($getProductInventory) >= 1) {
      return $getProductInventory;
    }
  }
  else {
    return false;
  }
}

function AddStockEntry($stock) {
   $addStockEntry = mysql_query("INSERT INTO `stockentries` (`ProductID`, `Qty`, `TansactionTypeID`, `SaleID`) VALUES 
    ( '$stock->ProductID', '$stock->Qty',  '$stock->TansactionTypeID', '$stock->SaleID' )" );

   return mysql_affected_rows();
}

function GetStockTransactionHistory() {
  $stockTransactionArray = array();

  if($getStockTransactionHistory = mysql_query("SELECT br.BrandName, pt.ProductTypeName, pi.ProductSize, 
    pi.ProductPattern, pi.ProductID, se.Qty, se.TansactionTypeID, tt.TranasactionTypeName, se.TimeStamp 
    FROM stockentries se 
    JOIN productinvetory pi ON se.ProductID = pi.ProductID 
    JOIN tranasactiontype tt ON se.TansactionTypeID = tt.TansactionTypeID 
    JOIN brands br ON pi.BrandID = br.BrandID 
    JOIN producttype pt ON pi.ProductTypeID = pt.ProductTypeID")) {
        
    if(mysql_num_rows($getStockTransactionHistory) > 0) {
      while($transaction = mysql_fetch_assoc($getStockTransactionHistory)) {
        $stockTransaction = new stockTransaction();
        // $stockTransaction->transactionID = $transaction[''];
        $date = date_create($transaction['TimeStamp']);
        $stockTransaction->transactionDate = date_format($date,'d-m-Y H:i');
        $stockTransaction->brand = $transaction['BrandName'];
        $stockTransaction->productType = $transaction['ProductTypeName'];
        $stockTransaction->productSize = $transaction['ProductSize'];
        $stockTransaction->productPattern = $transaction['ProductPattern'];
        $stockTransaction->productID = $transaction['ProductID'];
        $stockTransaction->qty = $transaction['Qty'];
        $stockTransaction->transactionTypeID = $transaction['TansactionTypeID'];
        $stockTransaction->transactionType = $transaction['TranasactionTypeName'];

        $stockTransactionArray[] = $stockTransaction;
      }
    }
  }
  return $stockTransactionArray;
}

function GetInventoryProductID($product) {
  $GetInventoryProduct = mysql_query("SELECT * FROM `productinvetory` WHERE ProductTypeID = '$product->productTypeID' 
  AND BrandID='$product->brandID' AND ProductSize = '$product->productSize' AND ProductPattern = '$product->productPattern'");
    if( mysql_num_rows($GetInventoryProduct) == 1) {
      $showData =  mysql_fetch_assoc($GetInventoryProduct);
      $product->productID = $showData['ProductID'];
    }
}


function AddNewSalesItem($order) {
  $userID = $_SESSION['userID'];
  $AddOrder = mysql_query("INSERT INTO `sales` 
    (`InvoiceNumber`, `InvoiceDateTime`, `CustomerName`, `CustomerPhone`, `CustomerTIN`, `CustomerPAN`, `VehicleNumber`, `VehicleMileage`,
     `BasicAmount`, `Discount`, `Vat`, `AmountPaid`,`PaymentType`, `ChequeNo`, `chequeDate`, `Address`, `Notes`, `UserID` ) VALUES 
    (  '$order->invoiceNumber', '$order->invoiceDate', '$order->customerName', '$order->customerPhone', '$order->customerTIN',
       '$order->customerPAN', '$order->vehicleNumber', '$order->vehicleMileage', '$order->basic',  '$order->discount', 
       '$order->vatAmount', '$order->amountPaid', '$order->paymentMethod', '$order->chequeNo', 
       '$order->chequeDate', '$order->address', '$order->notes', '$userID' )" );

  return mysql_affected_rows();
}

function AddProductToSalesInvoice($product,$invoiceNumber,$saleID) {
  $AddProductToInvoice = mysql_query("INSERT INTO `salesproducts` 
    (`SaleID`, `ProductID`, `BrandName`, `Productsize`, `Pattern`, 
     `ProductType`, `Qty`, `CostPrice`, `SalePrice`, `InvoiceNumber` ) VALUES 
    ( '$saleID', '$product->productID', '$product->brandName', '$product->productSize', 
      '$product->productPattern', '$product->productType', '$product->qty',
      '$product->costPrice', '$product->maxSellingPrice','$invoiceNumber' )" );
    
    return mysql_affected_rows();
}

function AddNewSeriveRecord($service) {
  $userID = $_SESSION['userID'];
  $AddSR = mysql_query("INSERT INTO `service` 
    ( `InvoiceNumber`, `InvoiceDateTime`, `CustomerName`, `CustomerPhone`, `VehicleNumber`, 
      `VehicleMileage`, `SubTotal`, `Discount`, `AmountPaid`, `Address`,`Note`, `UserID` ) VALUES 
      ('$service->invoiceNumber', '$service->invoiceDate', '$service->customerName',  '$service->customerPhone',
       '$service->vehicleNumber', '$service->vehicleMileage', '$service->subTotal',  '$service->discountAmount', 
       '$service->amountPaid', '$service->address',  '$service->notes', '$userID' )" );

  if($AddSR)
    return 1;
  else
    //echo mysql_error();
    return 0;
}

function UpdateSeriveRecord($service) {
  $userID = $_SESSION['userID'];
  $UpdateSR = mysql_query("UPDATE `service` SET 
      `InvoiceDateTime`='$service->invoiceDate', `CustomerName`='$service->customerName', 
      `CustomerPhone`='$service->customerPhone', `VehicleNumber`='$service->vehicleNumber', 
      `VehicleMileage`='$service->vehicleMileage', `SubTotal`='$service->subTotal', 
      `Discount`='$service->discountAmount', `AmountPaid`='$service->amountPaid', 
      `Address`='$service->address',`Note`='$service->notes', `UserID`='$userID'
      WHERE `InvoiceNumber` = '$service->invoiceNumber' " );

   if(-1 == mysql_affected_rows())
    return 0;
  else
    return 1;
}

function UpdateSaleInvoice($order) {
  $userID = $_SESSION['userID'];

  $UpdateOrder = mysql_query("UPDATE `sales` SET `InvoiceDateTime` = '$order->invoiceDate', 
     `CustomerName` = '$order->customerName' , `CustomerPhone` = '$order->customerPhone', `CustomerTIN` = '$order->customerTIN', 
     `CustomerPAN` = '$order->customerPAN', `VehicleNumber` = '$order->vehicleNumber', `VehicleMileage` = '$order->vehicleMileage',
     `BasicAmount` = '$order->basic', `Discount` = '$order->discount', `Vat` = '$order->vatAmount', `AmountPaid` = '$order->amountPaid',
     `PaymentType` = '$order->paymentMethod', `ChequeNo` = '$order->chequeNo', `chequeDate` = '$order->chequeDate', 
     `Address` = '$order->address', `Notes` = '$order->notes', `UserID`='$userID' WHERE `InvoiceNumber` = '$order->invoiceNumber' " );

   if(!mysql_error())
    return 1;
  else
    return 0;
}

function UpdateStockEntry($stock) {

     $addStockEntry = mysql_query("UPDATE `stockentries` SET `Qty`='$stock->Qty'
      WHERE `SaleID` = '$stock->SaleID' AND `ProductID` = '$stock->ProductID' LIMIT 1 ");

   if(!mysql_error())
    return 1;
  else
    return 0;
}

function UpdateProductInSalesInvoice($product,$saleID) {
    $updateStockEntry = mysql_query("UPDATE `salesproducts` SET 
     `ProductID` = '$product->productID', `BrandName` = '$product->brandName', `Productsize` = '$product->productSize',
     `Pattern` = '$product->productPattern', `ProductType` = '$product->productType', `Qty` = '$product->qty', 
     `CostPrice` = '$product->costPrice', `SalePrice` = '$product->maxSellingPrice' WHERE `SaleID` = '$saleID' " );
    
   if(!mysql_error())
    return 1;
  else
    return 0;
}

function RemoveAllItemsFromSaleInvoice($invoiceNumber) {
    $AddProductToInvoice = mysql_query("DELETE FROM `salesproducts` WHERE `InvoiceNumber`='$invoiceNumber' ");

     if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

function GetMaxUserID() {
  
    $getMaxUserID = mysql_query("SELECT MAX(UserID) as UserID FROM user");
    if(mysql_num_rows($getMaxUserID)>=1) {
        $ShowData = mysql_fetch_assoc($getMaxUserID);
        return $ShowData['UserID'];       
    } else {
      return 0;
    }
}

function GetMaxSaleID() {
  
    $getMaxSaleID = mysql_query("SELECT MAX(SaleID) as SaleID FROM salesproducts");
    if(mysql_num_rows($getMaxSaleID)>=1) {
        $ShowData = mysql_fetch_assoc($getMaxSaleID);
        return $ShowData['SaleID'];       
    } else {
      return 0;
    }
}

function AddItemToServiceInvoice($serviceable,$invoiceNumber) {

  $AddProductToInvoice = mysql_query("INSERT INTO `serviceitems` 
    (`ServiceableID`, `InvoiceNumber`, `ServiceableDispName`, `Qty`, `Price` ) VALUES 
    ( '$serviceable->serviceableID', '$invoiceNumber', '$serviceable->serviceableName',
      '$serviceable->qty', '$serviceable->price' )" );

  if($AddProductToInvoice)
    return 1;
  else
    echo mysql_error();
    return 0;
}

function RemoveAllItemsFromServiceInvoice($invoiceNumber) {
  $AddProductToInvoice = mysql_query("DELETE FROM `serviceitems` WHERE `InvoiceNumber`='$invoiceNumber' ");

     if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
}

function TodaysSale() {
  $getTodaysSale = mysql_query("SELECT SUM(AmountPaid) As TotalSale FROM `sales` WHERE InvoiceDateTime > DATE(NOW())");
    if(mysql_num_rows($getTodaysSale)>=1) {
        $ShowData = mysql_fetch_assoc($getTodaysSale);
        if(isset($ShowData['TotalSale']))
          return $ShowData['TotalSale'];
    } else {
      return 0;
    }
    return 0;
}

function TodaysService() {
  $getTodaysService = mysql_query("SELECT SUM(AmountPaid) As TotalService FROM `service` WHERE InvoiceDateTime > DATE(NOW())");
    if(mysql_num_rows($getTodaysService)>=1) {
        $ShowData = mysql_fetch_assoc($getTodaysService);
        if(isset($ShowData['TotalService']))
          return $ShowData['TotalService'];
    } else {
      return 0;
    }
    return 0;
}

function TodaysNonBillable() {
  $getTodaysNonBillable = mysql_query("SELECT SUM(AmountPaid) As TotalAmount from nonbillable WHERE RecordDate > DATE(NOW())");
    if(mysql_num_rows($getTodaysNonBillable)>=1) {
        $ShowData = mysql_fetch_assoc($getTodaysNonBillable);
        if(isset($ShowData['TotalAmount']))
          return $ShowData['TotalAmount'];   
    } else {
      return 0;
    }
    return 0;
}

function MonthWiseService($fromDate, $toDate) {
  return  mysql_query("SELECT DATE_FORMAT(InvoiceDateTime, '%m-%Y') AS Month, SUM(AmountPaid) AS Total
                       FROM  service
                       WHERE InvoiceDateTime >= '$fromDate' AND InvoiceDateTime < '$toDate'
                       GROUP BY DATE_FORMAT(InvoiceDateTime, '%m-%Y') ");
}

function MonthWiseSale($fromDate, $toDate) {
  return  mysql_query("SELECT DATE_FORMAT(InvoiceDateTime, '%m-%Y') AS Month, SUM(AmountPaid) AS Total
                       FROM  sales
                       WHERE InvoiceDateTime >= '$fromDate' AND InvoiceDateTime < '$toDate'
                       GROUP BY DATE_FORMAT(InvoiceDateTime, '%m-%Y') ");
}

function MonthWiseNonBillable($fromDate, $toDate) {
  return  mysql_query("SELECT DATE_FORMAT(RecordDate, '%m-%Y') AS Month, SUM(AmountPaid) AS Total
                       FROM  nonbillable
                       WHERE RecordDate >= '$fromDate' AND RecordDate < '$toDate'
                       GROUP BY DATE_FORMAT(RecordDate, '%m-%Y') ");
}

function FilterInput($inputString) {
  return mysql_real_escape_string(trim($inputString));
}


function MessageTemplate($MessageType, $text) {
  
  if($MessageType == MessageType::Success) {
    ChromePhp::log("success");
    return "<div class=\"alert alert-block alert-success\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\">
            <i class=\"ace-icon fa fa-times\"></i></button>
            <i class=\"ace-icon fa fa-check green\"></i>&nbsp;&nbsp;"
            . $text ."</div>";
  } else if($MessageType == MessageType::Failure) {
    ChromePhp::log("failure");
    echo "<div class=\"alert alert-block alert-danger\">
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\">
          <i class=\"ace-icon fa fa-times\"></i> </button>
          <i class=\"ace-icon fa fa-ban red\"></i>&nbsp;&nbsp;" 
          . $text . " </div>";
  } else if($MessageType == MessageType::RoboWarning) {
    ChromePhp::log("RoboWarning");
    echo "<div class=\"alert alert-block alert-danger\">
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\">
          <i class=\"ace-icon fa fa-times\"></i></button>
          <i class=\"ace-icon fa fa-android red\"></i>&nbsp;&nbsp;
          PikesAce security Robot has detected re-submission of same data or hack attempt. Please try later.</div>";
  }
}

 class Order
 {
    public $invoiceNumber;
    public $invoiceDate;
    public $customerName;
    public $customerPhone;
    public $vehicleNumber;
    public $vehicleMileage;
    public $customerTIN;
    public $customerPAN;
    public $subTotal=0.00;
    public $basic=0.00;
    public $discount=0.00;
    public $vatAmount=0.00;
    public $amountPaid=0.00;
    public $paymentMethod;
    public $chequeNo=NULL;
    public $chequeDate=NULL;
    public $notes="";
    public $address="";
  }

 class Product
 {
   public $invoiceID;
   public $productTypeID;
   public $productTypeName;
   public $productSize;
   public $brand;
   public $brandID;
   public $productPattern;
   public $units;
   public $rate;
   public $amount;
   public $vatPer;
   public $discountPer;
   public $discountsAmount;
   public $subtotal;
 }

  class ProductInventory
 {
   public $productID;
   public $brandName;
   public $brandID;
   public $supplierName;
   public $supplierID;
   public $productType;
   public $productTypeID;
   public $productSize;
   public $productPattern;
   public $costPrice;
   public $minSellingPrice;
   public $maxSellingPrice;
   public $productNotes= "";
   public $minStockAlert = 0;
   public $dateOfEntry;
   public $lastModified;
   public $qty;
 }


class stockTransaction
 {
   public $transactionID;
   public $transactionDate;
   public $brand;
   public $productType;
   public $productSize;
   public $productPattern;
   public $productID;
   public $qty;
   public $transactionTypeID;
   public $transactionType;
 }

 class Invoice
 {
    public $invoiceID;
    public $invoiceDate;
    public $companyName;
    public $invoiceNumber;
    public $supplierID;
    public $supplierName;
    public $tinNumber ="";
    public $vatAmount;
    public $vatPer;
    public $subTotalAmount;
    public $discountsAmount = 0.00;
    public $discountPer = 0.00;
    public $rounding = 0.00;
    public $totalAmount;
    public $paymentType;
    public $chequeNo=NULL;
    public $chequeDate=NULL;
    public $invoiceNotes = "";
}

class ServiceRecord
{
  public $invoiceNumber;
  public $invoiceDate;
  public $customerName;
  public $customerPhone;
  public $vehicleNumber;
  public $vehicleMileage;
  public $subTotal;
  public $discountAmount;
  public $amountPaid=0.00;
  public $address="";
  public $notes="";
}

class serviceable
{
  public $serviceableID;
  public $serviceableName;
  public $qty;
  public $price;
}

class Stock 
{
  public $ProductID;
  public $Qty;
  public $TansactionTypeID;
  public $SaleID;
}

class user 
{
  public $userID;
  public $userName;
  public $userEmail;
  public $userPhone;
  public $userAddr;
  public $userRoleID;
  public $userRole;
  public $status;
  public $idProof;
  public $password;
}

abstract class MessageType
{
    const Success = 0;
    const Failure = 1;
    const Warning = 2;
    const RoboWarning = 3;
    // etc.
}

class productType
{
  public $productTypeID;
  public $productTypeName;
}

?>
