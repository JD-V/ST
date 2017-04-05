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

function GetMaxMemberID()
{
    $GetMemberID = mysql_query("SELECT MAX(MemberID) as MemberID FROM member");

    if(mysql_num_rows($GetMemberID)>=1) {
        $ShowData = mysql_fetch_assoc($GetMemberID);
        return $ShowData['MemberID'];
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
      while($ShowData = mysql_fetch_assoc($getUserList))
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
function GetMembersPaidForCourse($memberID) {
  //$cr_array = array();

    ChromePhp::log("fetching course for member-" . $memberID . "-end" );

    if($courseList = mysql_query("SELECT py.PaymentID, cr.CourseName, cr.courseID, mb.MemberID,
                      mb.IDNumber FROM payment py JOIN member mb ON  mb.MemberID = py.MemberID
                      JOIN course cr ON cr.courseID = py.CourseID  WHERE mb.MemberID = $memberID" )) {

      if(mysql_num_rows($courseList) !=0) {
          ChromePhp::log("yes got some courses");
      }
      else {
          echo mysql_error();
          ChromePhp::log("somehting wrong ");
      }
      return $courseList;

      // if(mysql_num_rows($courseList) >= 1) {
      //
      //   while($ShowData = mysql_fetch_assoc($courseList))
      //   {
      //     $cr_array[] = $ShowData;
      //     ChromePhp::log($ShowData);
      //   }
      //   return $cr_array;
      // }
    //   return courseList;
    // }
    // else {
    //   return false;
    // }
  }

  echo mysql_error();
  ChromePhp::log("unable to fetch course for member " + $memberID );
  return null;
  // else {
  //   if($courseList = mysql_query("SELECT SELECT payment.PaymentID, course.CourseName, course.courseID, member.MemberID, member.IDNumber  FROM `payment`, `member`,`course`  WHERE member.MemberID = payment.MemberID AND course.courseID = payment.CourseID AND member.MemberID = '$memberID' AND member.WalkIn = 0")) {
  //     ChromePhp::log("yes have some courses");
  //     return courseList;
  //     if(mysql_num_rows($courseList) >= 1) {
  //       while($ShowData = mysql_fetch_assoc($courseList))
  //       {
  //         $cr_array[] = $ShowData;
  //         ChromePhp::log($ShowData);
  //       }
  //       return $cr_array;
  //     }
  //   }
  //   else {
  //     return false;
  //   }
  // }
  //
  // return $cr_array;
}
 // function transliterateTurkishChars($inputText) {
 //     $search  = array('ç', 'Ç', 'ğ', 'Ğ', 'ı', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü');
 //     $replace = array('c', 'C', 'g', 'G', 'i', 'I', 'o', 'O', 's', 'S', 'u', 'U');
 //     $outputText=str_replace($search, $replace, $inputText);
 //     return $outputText;
 // }



?>
