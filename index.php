<?php
require 'pages/php/_connect.php';
require 'pages/php/_core.php';
if(!isLogin())
{

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Shankar Tires | PikesAce</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <h2>Inventory Management system&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.pikesace.com" target="_blank"><small style="color: #3c8dbc!important;">PIKES <i>ACE</i></small></a></h2>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" id="content">
    <p class="login-box-msg" id="LoginMessage">Sign in to start your session</p>

    <form id="loginMain" name="loginMain" action="index.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" id="userName" name="userName" class="form-control input-lg" placeholder="User name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="passwd" name="passwd" class="form-control input-lg" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-offset-8 col-xs-4">
          <button type="submit" name="login" id="login" class="input-lg btn btn-primary btn-block btn-flat">
            <span class="fa fa-key"></span>
            <span style="margin-left: 5px;">Sign In</span>
          </button>
        </div>
        <!--<div class="col-xs-offset-9 col-xs-4">
          <a href="#forget" onclick="forgetpassword();" id="forget">Forgot Password?</a>
        </div>-->
      </div>

      <div class="box-body" id="errorBox" name="errorBox" style="margin-top: 10px; padding: 0px;"></div>
    </form>


    <form action="" method="post" id="resetPasswd" style="display:none;">
       <div class="form-group has-feedback">
        <input type="text" id="emailrp" name="emailrp" class="form-control input-lg" placeholder="Email to reset password">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="hidden" name="action" class="form-control input-lg" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback hidden"></span>
      </div>
      <div class="row">
        <div class="col-xs-offset-8 col-xs-4">
          <button type="submit" class="input-lg btn btn-primary btn-block btn-flat">
            <span class="fa fa-key"></span>
            <span style="margin-left: 5px;">Reset Password</span>
          </button>
        </div>
      </div>
      <div class="box-body" id="errorBoxRP" name="errorBoxRP" style="margin-top: 10px; padding: 0px;"></div>
    </form>



    <div id="phpError" class="box-body" style="margin-top: 10px; padding: 0px;">
      <?php
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {
        if(isset($_POST['userName']) && !empty($_POST['userName']) && isset($_POST['passwd']) && !empty($_POST['passwd']))
        {
          $userName = FilterInput($_POST['userName']);
          $userpwd = FilterInput($_POST['passwd']);

            $Res = validateLogin($userName,$userpwd);
            if($Res == 1)
            {
              $_SESSION['userName'] = $userName;
              $_SESSION['userID'] = GetUserID($userName);
              $_SESSION['roleID'] = getUserRoleID();
              $_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
              header('Location: pages/php/dashboard.php');
              exit;
            } else if($Res == 0) {
              echo "<p class='alert alert-danger'>Error(014) ! Access denided</p><br/>";
            } else if($Res == 2) {
              echo "<p class='alert alert-danger'>Error(011) ! Invalid User ID / Password</p><br/>";
            } else if($Res == 3) {
              echo "<p class='alert alert-danger'>Error(012) ! Invalid User ID / Password</p>";
            }

        }
        else if( isset($_POST['emailrp']) && !empty($_POST['emailrp']) )
        {
          $Email = mysql_real_escape_string($_POST['emailrp']);
          if($checkemail = mysql_query("SELECT * FROM `user` WHERE `Email` = '$Email'"))
          {
            if(mysql_num_rows($checkemail) == 1)
            {
              $data = mysql_fetch_assoc($checkemail);

              //script to reset passeword
              $encrypt = generateRandomString();
              $Result = SaveForgotPasswordKey($Email,$encrypt);


              $from = "support@veeretail.com";  // create a domain email id and supply here.


              // Create email headers
              // To send HTML mail, the Content-type header must be set
              $headers  = 'MIME-Version: 1.0' . "\r\n";
              $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

              $headers .= 'From: '.$from."\r\n".
                  'Reply-To: '.$from."\r\n" .
                  'X-Mailer: PHP/' . phpversion();

              $subject="Forget Password";

              // Compose an HTML email message
              $message = '<html><body>';
              $message .= '<h1 style="color:#f40;">Hi '. $data['Name'] .'!</h1>';
              $message .= '<a href="http://www.myadmin.veeretail.com/pages/php/forgotPassword.php?key='.$encrypt.'&action=reset"><p style="color:#080;font-size:18px;">Click here to reset pasword</p></a>'; // update link here
              $message .= '</body></html>';

              if($Result == 1)
              {

                ini_set("SMTP", "mail.veeretail.com");
                ini_set("sendmail_from", "support@veeretail.com");

                  if(mail($Email,$subject,$message,$headers))
                  {
                    echo "<p class='alert alert-success'>Check your email</p>";
                  }
                  else
                  {
                      echo 'Caught exception'. ' ' . $Email.' '.$subject.' '.$message. ' '.$headers;
                  }
              }
              else
              {
                  echo "<p class='alert alert-danger'>Error while password revovery | Try Later2</p>";
              }
            }
            else
            {
              echo "<p class='alert alert-danger'>Email Doesn't exist</p>";
            }
          }
          else
          {
            echo "<p class='alert alert-danger'>Email Doesn't exist</p>";
          }

        }
        else
        {
          echo "<p class='alert alert-danger'>Error ! Invalid User ID / Password2</p>";
        }
      }
      ?>
    </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
<script src="pages/js/codefellas.js"></script>
</body>
</html>
<script type="text/javascript">
    window.addEventListener('load',formSelector());
  //document.getElementById("content").addEventListener("load", formSelector);

  function formSelector()
  {
    var emailRP = "<?php if(isset($_POST['emailrp']) && !empty($_POST['emailrp'])) echo $_POST['emailrp']; else echo '';  ?>";

    if(emailRP != '')
    {
      forgetpassword();
      $("#emailrp").val(emailRP);
    }
  }


  function forgetpassword() {
    $("#loginMain").hide();
    $("#resetPasswd").show();

    $("#LoginMessage").html("Enter email to reset password");
  }
  </script>
<?php
}
else
{
	header('Location: pages/php/dashboard.php');
}
?>
