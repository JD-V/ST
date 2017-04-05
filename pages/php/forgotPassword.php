<?php
require '_connect.php';
require '_core.php';
if(!isLogin())
{
  if(isset($_GET['key']) && isset($_GET['action'])) 
  {
    $key = mysql_real_escape_string($_GET['key']);
    $action = mysql_real_escape_string($_GET['action']);

    if($action != 'reset')
    {
      echo 'Error (039) | Invalid URL';
      exit;
    }

    if($checkKey = checkPasswordRecoveryKey($key)) 
    {
      if(mysql_num_rows($checkKey) != 1)
      {
        echo 'Error (037) | Invalid URL';
        exit;
      }
    }
    else
    {
      echo 'Error (038) | Invalid URL';
      exit;
    }
  }
  else
  {
    echo 'Error (040) | Invalid URL';
      exit;
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reset Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.css">
  
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
    <a href="index.php"><b>Reset Password</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" id="content">
    <form id="resetPassword" name="resetPassword" action="forgotPassword.php?key=<?php echo $key; ?>&action=<?php echo $action ?>" method="post">
      <input type="hidden" name="encrypt" value="<?php echo $key; ?>" />
      <div class="form-group has-feedback">
        <input type="password" id="NewPwd" name="NewPwd" class="form-control input-lg" placeholder="New Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="RepeatPwd" name="RepeatPwd" class="form-control input-lg" placeholder="Retype Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-offset-8 col-xs-4">
          <button type="submit" name="login" id="login" class="input-lg btn btn-primary btn-block btn-flat">
            <span class="fa fa-key"></span>
            <span style="margin-left: 5px;">Submit</span>
          </button>
        </div>
        
      </div>

      <div class="box-body" id="errorBox" name="errorBox" style="margin-top: 10px; padding: 0px;"></div>
    </form>

    <div id="phpError" class="box-body" style="margin-top: 10px; padding: 0px;">
      <?php
      if($_SERVER["REQUEST_METHOD"] == "POST")  
      {
        if(isset($_POST['NewPwd']) && !empty($_POST['NewPwd']) && isset($_POST['encrypt']) && !empty($_POST['encrypt']) ) 
        {
          $NewPwd = mysql_real_escape_string($_POST['NewPwd']);
          $RepeatPwd = mysql_real_escape_string($_POST['RepeatPwd']);
          $encrypt = mysql_real_escape_string($_POST['encrypt']);

          if($checkKey = checkPasswordRecoveryKey($encrypt)) 
          {
            if(mysql_num_rows($checkKey) == 1)
            {
              $user = mysql_fetch_assoc($checkKey);

              if(updatePassword($user['UserID'],$NewPwd) == 1)
              { 
                echo "<p class='alert alert-success'>Password Successfully Restored</p>";

                header( "refresh:1;url=../../index.php" );
              }
              else
              {
                echo "<p class='alert alert-danger'>Error (041) ! Unable to procced. Please try later</p>";
              }
            }
            else 
            {
              echo "<p class='alert alert-danger'>Error (042) ! Invalid URL/Key</p>";
            }
          }
          else 
          {
            echo "<p class='alert alert-danger'>Error (043)! Invalid URL/Key</p>";
          }
          
        }
        else 
        {
          echo "<p class='alert alert-danger'>Error (044) ! Invalid URL/Key</p>";
        }
      }
      ?>
    </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
<script src="../../pages/js/codefellas.js"></script>
</body>
</html>

<?php
}
else
{
	header('Location: dashboard.php');
}
?>
