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

function getInstructors() {

  if($getInstructorsList = mysql_query("SELECT UserID, Name FROM user WHERE RoleID = 3"))
  {
    ChromePhp::log("true");
    if(mysql_num_rows($getInstructorsList) >= 1)
    {
      ChromePhp::log("true1");
      return $getInstructorsList;
    }
  }
  else
  {
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

 function GetCityList()  {

   if($getCityList = mysql_query("SELECT * FROM city")) {
     ChromePhp::log("true");
     if(mysql_num_rows($getCityList) >= 1) {
       ChromePhp::log("true1");
       return $getCityList;
     }
   }
   else {
     return false;
   }

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


function AddCourse($CourseName,$CourseCode,$DurationDays,$DurationMins,$PPI) {

  ChromePhp::log("cour name " . $CourseName);
  ChromePhp::log("cour code  " . $CourseCode);
  ChromePhp::log("durday " . $DurationDays);
  ChromePhp::log("durmin " . $DurationMins);
  ChromePhp::log("ppi " . $PPI);
  ChromePhp::log("dpi " . $DPI);


  if($result = mysql_query("INSERT INTO course (CourseName, CourseCode, DurationDays, DurationMins, PPI)
                        VALUES ('$CourseName', '$CourseCode','$DurationDays','$DurationMins','$PPI')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      { if (false === $result) {
          echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}
}

function GetMaxInvoiceID()
{
    $GetInvoiceID = mysql_query("SELECT MAX(InvoiceID) as InvoiceID FROM purchaseinvoice");

    if(mysql_num_rows($GetInvoiceID)>=1) {
        $ShowData = mysql_fetch_assoc($GetInvoiceID);
        return $ShowData['InvoiceID'];
      }
}

function  WalkinRegister($MemberName,$MemberPhone,$RegDate,
                          $MemberAvail,$IDNumber,$MemberEmail,
                          $MemberBDay,$MemCity,$MemAddress,
                          $MemProf,$MemRef,$walkIn = 1 ) {

    if($result = mysql_query("INSERT INTO member (Name, IDNumber, RegistrationDate, Email, Phone, Availability, Birthday, CityID, Profession, ReferenceID,WalkIn)
                        VALUES ('$MemberName', '$IDNumber','$RegDate','$MemberEmail','$MemberPhone','$MemberAvail','$MemberBDay','$MemCity','$MemProf','$MemRef','$walkIn')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      { if (false === $result) {
          //echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}


}

function  AddStatus($StatusDate,$UserID,$CurrentStatusID,$Notes,$MemberID ) {

    if($result = mysql_query("INSERT INTO status (StatusDate, UserID, CurrentStatusID, Notes, MemberID)
                        VALUES ('$StatusDate', '$UserID','$CurrentStatusID','$Notes','$MemberID')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      { if (false === $result) {
          //echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}


}


function  AddPayment($PaymentDate,$CourseID,$PurchasedDuration,$PaymentType,$ToBePaid,$Paid,$Note,$MemberID) {

    if($result = mysql_query("INSERT INTO payment (PaymentDate, CourseID, PurchasedDuration, PaymentType, ToBePaid, Paid, Note, MemberID)
                        VALUES ('$PaymentDate', '$CourseID','$PurchasedDuration','$PaymentType','$ToBePaid','$Paid', '$Note', '$MemberID')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      { if (false === $result) {
          //echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}
}

function AddReservation($MemberID,$CourseID,$AllocID) {

  if($result = mysql_query("INSERT INTO reservation (MemberID, CourseID, AllocID )
                          VALUES ('$MemberID', '$CourseID','$AllocID')" ) )
    {ChromePhp::log('inserted  res'); return true;}
  else
    { if (false === $result) {
        //echo mysql_error();
    }  ChromePhp::log(' not inserted'); return false;}

}

function AllocateRoom($AllocID,$RoomID,$dateTime1,$dateTime2,$UserID) {

ChromePhp::log('datetime item');
    if($result = mysql_query("INSERT INTO roomsalloc (AllocID, RoomID, StartDateTime, EndDateTime, InstructorID )
                        VALUES ('$AllocID', '$RoomID', '$dateTime1','$dateTime2','$UserID')" ) )
      {ChromePhp::log('inserted'); return true;}
    else
      { if (false === $result) {
          //echo mysql_error();
      }  ChromePhp::log(' not inserted'); return false;}

}


function GetReferenceList(){

     if($getRefList = mysql_query("SELECT * FROM reference")) {
       ChromePhp::log("true");
       if(mysql_num_rows($getRefList) >= 1) {
         ChromePhp::log("true1");
         return $getRefList;
       }
     }
     else {
       return false;
     }

}

function GetCurrentStatusList(){

     if($getCurrentStatusList = mysql_query("SELECT * FROM currentstatus")) {
       ChromePhp::log("true");
       if(mysql_num_rows($getCurrentStatusList) >= 1) {
         ChromePhp::log("true1");
         return $getCurrentStatusList;
       }
     }
     else {
       return false;
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

function GetCourseList() {

     if($courseList = mysql_query("SELECT * FROM course")) {
       ChromePhp::log("true");
       if(mysql_num_rows($courseList) >= 1) {
         ChromePhp::log("true1");
         return $courseList;
       }
     }
     else {
       return false;
     }
}

function StoreCoursesListSession()
{
  if($getUserList = GetCourseList())
  {
    if(mysql_num_rows($getUserList) >= 1)
    {
      $user_array = array();
      while($ShowData = mysFql_fetch_assoc($getUserList))
      {
        $user_array[] = $ShowData;
        ChromePhp::log($ShowData);
      }
      $_SESSION['CourseList'] = $user_array;
    }
  }
}


function GetMemberNamesList($includeWalkin = 1) {

  if($includeWalkin == 1) {
    if($memberList = mysql_query("SELECT MemberID, Name, IDNumber FROM member")) {
      ChromePhp::log("true");
      if(mysql_num_rows($memberList) >= 1) {
        ChromePhp::log("true1");
        return $memberList;
      }
    }
    else {
      return false;
    }
  }
  else {
    if($memberList = mysql_query("SELECT MemberID, Name, IDNumber FROM member WHERE WalkIn = 0")) {
      ChromePhp::log("true");
      if(mysql_num_rows($memberList) >= 1) {
        ChromePhp::log("members");
        return $memberList;
      }
    }
    else {
      return false;
    }
  }
}

function GetCourseDuration($CourseID) {

  if($courseDur = mysql_query("SELECT DurationMins FROM course WHERE courseID = '$CourseID' ")) {
    if(mysql_num_rows($courseDur)==1) {
        $ShowData = mysql_fetch_assoc($courseDur);
        $DurationMins  = $ShowData['DurationMins'];
        echo $DurationMins;
    }
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


?>
