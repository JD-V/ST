<?php
date_default_timezone_set('Asia/Kolkata');

include 'ChromePhp.php';

ob_start();

$CDATA['CURRENT_FILE'] = @$_SERVER['SCRIPT_NAME'];
$CDATA['BASE_URL'] = "http://localhost/GMS2/GMS";

if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
{
  $http_referer = $_SERVER['HTTP_REFERER'];
}
else
{
  $http_referer = 'direct_link';
}


function ValidateLogin($Email,$userpwd){

  $checklogin = mysql_query("SELECT * FROM `user` WHERE `Email` = '$Email' ");

  if(mysql_num_rows($checklogin) == 1) {

    $user = mysql_fetch_assoc($checklogin);

    if(password_verify($userpwd, $user['Password']) ) {
      return 1;
    }
    else
      return 2;
  }
  else
    return 3;

}

function isLogin() {

  if(isset($_SESSION['Email']) && !empty($_SESSION['Email']))
  {
    return true;
  }
  else
  {
    return false;
  }
}

function getUserRole() {

  if(isset($_SESSION['Email']) && !empty($_SESSION['Email']))
  {
    $Email = $_SESSION['Email'];
    $GetRoleID = mysql_query("SELECT * FROM user WHERE Email = '$Email' ");
    if(mysql_num_rows($GetRoleID)==1) {
        $ShowData = mysql_fetch_assoc($GetRoleID);
        $RoleID  = $ShowData['RoleID'];
        $GetRoleName = mysql_query("SELECT RoleName FROM role WHERE RoleID = '$RoleID' ");
        if(mysql_num_rows($GetRoleName)==1) {

          $ShowData = mysql_fetch_assoc($GetRoleName);
          ChromePhp::log("role name" .$ShowData['RoleName']);
          echo $ShowData['RoleName'];
        }
    }
    else {
      echo "null";
    }
  }
  else {
    echo 'No User';
  }

}

function getUserRoleID() {

  if(isset($_SESSION['Email']) && !empty($_SESSION['Email']))
  {
    $Email = $_SESSION['Email'];
    $GetRoleID = mysql_query("SELECT * FROM user WHERE Email = '$Email' ");
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

    if(isset($_SESSION['Email']) && !empty($_SESSION['Email']))
    {
      $Email = $_SESSION['Email'];
      $GetName = mysql_query("SELECT * FROM user WHERE Email = '$Email' ");
      if(mysql_num_rows($GetName)==1) {
          $ShowData = mysql_fetch_assoc($GetName);
          echo $ShowData['Name'];

          }
      }
      else {
        echo "null";
      }

}


function isAdmin() {

  $Email = $_SESSION['Email'];
  if($CheckAdmin = mysql_query("SELECT * FROM user WHERE RoleID = '1' AND Email = '$Email' "))
  {

    if(mysql_num_rows($CheckAdmin) == 1)
    {
      ChromePhp::log('is Admin true');
      return true;
    }
    else
    {
      return false;
    }

  }
  else
  {
    return false;
  }

}

function GetServices() {

  if($getServiceList = mysql_query("SELECT * FROM service"))
  {
    ChromePhp::log("true");
    if(mysql_num_rows($getServiceList) >= 1)
    {
      ChromePhp::log("true1");
      return $getServiceList;
    }
  }
  else
  {
    return false;
  }
}

function GetMaxServiceInoviceNumber()
{
  if($maxInvoiceNumber = mysql_query("SELECT MAX(InvoiceNumber) as InvoiceNumber FROM service"))
    {
      if(mysql_num_rows($maxInvoiceNumber) >= 1) {

        $ShowData = mysql_fetch_assoc($maxInvoiceNumber);
        return $ShowData['InvoiceNumber'];
      }
    }
    else
    {
      return false;
    }
}

function GetNonBillables() {

  if($getNonBillablesList = mysql_query("SELECT * FROM `nonbillable`"))
  {
    ChromePhp::log("true");
    if(mysql_num_rows($getNonBillablesList) >= 1)
    {
      ChromePhp::log("true1");
      return $getNonBillablesList;
    }
  }
  else
  {
    return false;
  }

}


function GetServiceRecord($InvoiceNumber) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `service` WHERE InvoiceNumber = '$InvoiceNumber' ") ) {
    if(mysql_num_rows($GetRecordInformation) == 1) {
      return $GetRecordInformation;
    }
  }
  else {
    return false;
  }
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

  if($updateKey = mysql_query("UPDATE user SET PasswordRecovery = '$key' WHERE Email='$email'"))
  {
    return 1;
  }
  else
  {
    return 0;
  }


}

function updatePassword($userID,$password) {

  $hashPassword = password_hash($password, PASSWORD_DEFAULT);

  if($updatePassword = mysql_query("UPDATE user SET PasswordRecovery = NULL, Password ='$hashPassword' WHERE UserID='$userID' "))
    {
      return 1;
    }
    else
    {
      return 0;
    }

}

function checkPasswordRecoveryKey($key) {

  return mysql_query("SELECT * FROM `user` WHERE `PasswordRecovery` = '$key'");
}

function getUserInfo($user_id) {

  if($getUserInformation = mysql_query("SELECT * FROM `user` WHERE UserID = '$user_id' "))
  {
    if(mysql_num_rows($getUserInformation) == 1)
    {
      return $getUserInformation;
    }
  }
  else
  {
    return false;
  }
}

function getLocations() {

  if($getLocations = mysql_query("SELECT * FROM location"))
  {
    if(mysql_num_rows($getLocations) >= 1)
    {
      return $getLocations;
    }
  }
  else
  {
    return false;
  }
}

function GetNonBillableRecord($Record_id) {

  if($GetRecordInformation = mysql_query("SELECT * FROM `nonbillable` WHERE RecordID = '$Record_id' ") )
  {
    if(mysql_num_rows($GetRecordInformation) == 1)
    {
      return $GetRecordInformation;
    }
  }
  else
  {
    return false;
  }
}

function GetMaxAllocID(){

  if($maxAllocID = mysql_query("SELECT MAX(AllocID) as maxAllocID FROM roomsalloc"))
  {
    if(mysql_num_rows($maxAllocID) >= 1) {

      $ShowData = mysql_fetch_assoc($maxAllocID);
      return $ShowData['maxAllocID'];
    }
  }
  else
  {
    return false;
  }

}

function RemoveLocation($LocationId)
{
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

function AddLocation($Location)
{
  ChromePhp::log("LocName : " .$Location->LocName);


  $addLocaiton = mysql_query(" INSERT INTO `location` (`LocationName`) VALUES ( '$Location->LocName')" );

  ChromePhp::log("ADD LOC qrY : " .$addLocaiton);

  if($addLocaiton)
    return 1;
  else
    return 0;

}

function UpdateLocation($Location) {

  $updateLocation = mysql_query(" UPDATE `location` SET `LocationName` = '$Location->LocName' WHERE `LocationID` = $Location->LocId " );

   if(-1 == mysql_affected_rows())
       return 0;
     else
       return 1;
 }

 function GetRoles()
 {
   if($getRoleList = mysql_query("SELECT * FROM role"))
   {
     ChromePhp::log("true");
     if(mysql_num_rows($getRoleList) >= 1)
     {
       ChromePhp::log("true1");
       return $getRoleList;
     }
   }
   else  {
     return false;
   }
 }

function AddUser($UserName,$UserEmail,$UserPhone,$UserBday,$UserAddr,$UserRole,$Status) {

  ChromePhp::log("name" . $UserName);
  ChromePhp::log("id " . $UserID);
  ChromePhp::log("email" . $UserEmail);
  ChromePhp::log("ph" . $UserPhone);
  ChromePhp::log("bd" . $UserBday);
  ChromePhp::log("city" . $UserCity);
  ChromePhp::log("addr" . $UserAddr);
  ChromePhp::log("role" . $UserRole);
  ChromePhp::log("status" . $Status);

  if($result = mysql_query("INSERT INTO user (Email, Name, Password, RoleID, UserPhone, Birthday, Address, Status)
                        VALUES ('$UserEmail', '$UserName','nu','$UserRole','$UserPhone','$UserBday','$UserAddr' ,'$Status')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      {if (false === $result) {
          //echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}

}

function AddNonBillable($RecDate,$Perticulars,$AmountPaid,$Notes){

  ChromePhp::log("RecDate" . $RecDate);
  ChromePhp::log("Perticulars " . $Perticulars);
  ChromePhp::log("AmountPaid" . $AmountPaid);
  ChromePhp::log("Notes" . $Notes);

  if($result = mysql_query("INSERT INTO nonbillable (RecordDate, Perticulars, AmountPaid, Notes)
                        VALUES ('$RecDate', '$Perticulars','$AmountPaid','$Notes')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      {if (false === $result) {
          //echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}

}

function UpdateNonBillable($RecordId,$RecDate,$Perticulars,$AmountPaid,$Notes){

ChromePhp::log("RecordId" . $RecordId);
  ChromePhp::log("RecDate" . $RecDate);
  ChromePhp::log("Perticulars " . $Perticulars);
  ChromePhp::log("AmountPaid" . $AmountPaid);
  ChromePhp::log("Notes" . $Notes);

  if($updateKey = mysql_query("UPDATE nonbillable SET RecordDate   = '$RecDate' , Perticulars = '$Perticulars', AmountPaid = '$AmountPaid',
   Notes = '$Notes' WHERE RecordID='$RecordId'"))
  {
    return true;
  }
  else
  {
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
  }
  else
  {
    return false;
  }
}

function Addservicerecord($ServiceRecord) {

  if($result = mysql_query("INSERT INTO service (InvoiceDateTime,CustomerName,CustomerPhone, VehicleNumber, AmountPaid, Note)
      VALUES ('$ServiceRecord->invoiceDate','$ServiceRecord->customerName','$ServiceRecord->customerPhone',
      '$ServiceRecord->vehicleNumber', '$ServiceRecord->amountPaid', '$ServiceRecord->notes')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      { if (false === $result) {
          echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}
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




function CheckRoomAvailibility($roomID,$dt1,$dt2){

  if( $getLoc = mysql_query(" SELECT b.* FROM roomsalloc b
                WHERE b.RoomID = '$roomID' AND ( (b.EndDateTime > '$dt1' AND b.StartDateTime < '$dt2' ) OR
                (b.EndDateTime < '$dt1' AND b.StartDateTime > '$dt2') )" ) )
      {
        ChromePhp::log("yes query success" );
        if(mysql_num_rows($getLoc)>0) {
          ChromePhp::log("res 1" );
          echo '1';
        }
        else{
          echo '0';
          ChromePhp::log("res 0" );
        }
      }
      else {
        ChromePhp::log("query failed" );
      }

}

function AddInvoice($Invoice){

ChromePhp::log('InvoiceDate ' . $Invoice->invoiceDate );
ChromePhp::log('InvoiceNumber ' . $Invoice->invoiceNumber );
ChromePhp::log('Company ' . $Invoice->companyName );
ChromePhp::log('TinNumber '. $Invoice->tinNumber);
ChromePhp::log('SubTotal ' . $Invoice->subTotalAmount);
ChromePhp::log('DiscountRs ' . $Invoice->discountsAmount);
ChromePhp::log('DiscountPer ' . $Invoice->discountPer);
ChromePhp::log('VatPer '. $Invoice->vatPer );
ChromePhp::log('VatAmount ' . $Invoice->vatAmount);
ChromePhp::log('Rounding ' . $Invoice->rounding);
ChromePhp::log('TotalPaid ' . $Invoice->totalAmount);
ChromePhp::log('Notes' .$Invoice->invoiceNotes);


  $addInvoice = mysql_query("INSERT INTO `purchaseinvoice` (`InvoiceDate`, `InvoiceNumber`, `Company`, `TinNumber`, `SubTotal`, 
    `DiscountRs`, `DiscountPer`, `VatPer`, `VatAmount`, `Rounding`, `TotalPaid`, `Notes` ) VALUES 
    ( '$Invoice->invoiceDate','$Invoice->invoiceNumber', '$Invoice->companyName',  '$Invoice->tinNumber', '$Invoice->subTotalAmount', 
    '$Invoice->discountsAmount', '$Invoice->discountPer',  '$Invoice->vatPer', '$Invoice->vatAmount', '$Invoice->rounding', 
    '$Invoice->totalAmount', '$Invoice->invoiceNotes' )" );

  if($addInvoice)
    return 1;
  else
    echo mysql_error();
    return 0;
}


function AddProduct($Product){

ChromePhp::log('InvoiceID ' . $Product->invoiceID );
ChromePhp::log('productSize ' . $Product->productSize );
ChromePhp::log('brand ' . $Product->brand );
ChromePhp::log('units '. $Product->units);
ChromePhp::log('rate ' . $Product->rate);
ChromePhp::log('discountsAmount ' . $Product->discountsAmount);
ChromePhp::log('DiscountPer ' . $Product->discountPer);
ChromePhp::log('VatPer '. $Product->vatPer );
ChromePhp::log('TotalPaid ' . $Product->subtotal);

  $addProduct = mysql_query("INSERT INTO `products` (`InvoiceID`, `ProductSize`, `ProductBrand`, 
    `ProductQty`, `UnitPrice`, `DiscountRs`, `DiscountPer`, `VatPer`, `SubTotal` ) VALUES 
    ( '$Product->invoiceID','$Product->productSize', '$Product->brand',  '$Product->units', '$Product->rate', 
    '$Product->discountsAmount', '$Product->discountPer',  '$Product->vatPer', '$Product->subtotal' )" );

  if($addProduct)
    return 1;
  else
    echo mysql_error();
    return 0;
}


function GetInvoices(){

   if($getInvoices = mysql_query("SELECT * FROM `purchaseinvoice`")) {
    ChromePhp::log("true");
    if(mysql_num_rows($getInvoices) >= 1)
    {
      ChromePhp::log("true1");
      return $getInvoices;
    }
  }
  else
  {
    return false;
  }

}

function GetProducts() {

  if($getProducts = mysql_query("SELECT p.*, pi.InvoiceNumber FROM products p JOIN purchaseinvoice pi ON pi.InvoiceID = p.InvoiceID")) {
    ChromePhp::log("true");
    if(mysql_num_rows($getProducts) >= 1)
    {
      ChromePhp::log("true1");
      return $getProducts;
    }
  }
  else
  {
    return false;
  }

}

 class Product
 {
   public $invoiceID;
   public $productSize;
   public $brand;
   public $units;
   public $rate;
   public $amount;
   public $vatPer;
   public $discountPer;
   public $discountsAmount;
   public $subtotal;
 }

 class Invoice
 {
    public $invoiceDate;
    public $companyName;
    public $invoiceNumber;
    public $tinNumber;
    public $vatAmount;
    public $vatPer;
    public $subTotalAmount;
    public $discountsAmount = 0.00;
    public $discountPer = 0.00;
    public $rounding = 0.00;
    public $totalAmount;
    public $invoiceNotes = "";
}

class ServiceRecord
{
  public $invoiceNumber;
  public $invoiceDate;
  public $customerName;
  public $customerPhone;
  public $vehicleNumber;
  public $amountPaid=0.00;
  public $notes="";
  
}


?>
