<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'MNUSER';
if(isLogin() && isAdmin())
{
require '_header.php'

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
    <div>Manage Users
    </div>
    </h1>
<!--     <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Layout</a></li>
      <li class="active">Fixed</li>
    </ol> -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div id ="messages">

    <?php
    //$_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
    ChromePhp::log("form");
    ChromePhp::log("UKEY" . @$_POST['UKey']);
    if(@$_POST['UKey'] == '2')
    {
      ChromePhp::log("AKEY" . $_POST['akey']);
      if(@$_POST['akey'] == $_SESSION['AUTH_KEY'])
      {
        if( isset($_POST['UserName']) && !empty($_POST['UserName']) &&
            isset($_POST['UserEmail']) && !empty($_POST['UserEmail']) &&
            isset($_POST['UserPhone']) && !empty($_POST['UserPhone']) &&
            isset($_POST['UserBday']) && !empty($_POST['UserBday']) &&
            isset($_POST['UserAddr']) && !empty($_POST['UserAddr']) &&
            isset($_POST['UserRole']) && !empty($_POST['UserRole']) &&
            isset($_POST['Status']) && !empty($_POST['Status'])  )
          {
            $UserName = mysql_real_escape_string(trim($_POST['UserName']));
            $UserEmail = mysql_real_escape_string(trim($_POST['UserEmail']));
            $UserPhone = mysql_real_escape_string(trim($_POST['UserPhone']));
            $UserBday = mysql_real_escape_string(trim($_POST['UserBday']));
            $UserAddr = mysql_real_escape_string(trim($_POST['UserAddr']));
            $UserRole = mysql_real_escape_string(trim($_POST['UserRole']));
            $Status = mysql_real_escape_string(trim($_POST['Status']));
            ChromePhp::log('role' . $UserRole);

            if($UpdateSubEvent = AddUser($UserName,$UserEmail,$UserPhone,$UserBday,$UserAddr,$UserRole,$Status) )
            {
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>
                          User Added successfully!
                        </div>';
            }
            else
            {
              echo '<div class="alert alert-block alert-danger">
                      <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                      </button>
                      <i class="ace-icon fa fa-ban red"></i>
                      Something went wrong, try again.
                    </div>';
            }
          }
          else
          {
            echo '<div class="alert alert-block alert-danger">
                    <button type="button" class="close" data-dismiss="alert">
                      <i class="ace-icon fa fa-times"></i>
                    </button>
                    <i class="ace-icon fa fa-ban red"></i>
                    Please enter all the details.
                  </div>';
          }
          /* codefellas Security Robot for re-submission of form */
          $_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
          ChromePhp::log($_SESSION['AUTH_KEY']);
          /* END */
        }
        else
        {

          echo '<div class="alert alert-block alert-danger">
                  <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                  </button>
                  <i class="ace-icon fa fa-android red"></i>
                  Our Security Robot has detected re-submission of same data or hack attempt. Please try later.
                </div>';
        }
    }

      ?>
  </div>
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo 'Add user'; ?></h3>

       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="AddOrUpdateUserForm">
          <form class="form-horizontal"  name="Userform" id="Userform" action="ManageUsers.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="UserName" class="control-label col-sm-3 lables">Name<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Name"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                </div>
                <div id="errorMsgUN" name="errorMsgUN"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserEmail" class="control-label col-sm-3 lables">Email<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="UserEmail" name="UserEmail" placeholder="Enter your Email Id"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                </div>
                <div id="errorMsgUE" name="errorMsgUE"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserPhone" class="control-label col-sm-3 lables">Phone<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" maxlength="10" id="UserPhone" name="UserPhone" placeholder="Enter your Phone Number"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                </div>
                <div id="errorMsgUPH" name="errorMsgUPH" class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserBday" class="control-label col-sm-3 lables">Birthday<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="datetime" class="form-control" name="UserBday" id="UserBday"  />
                </div>
                <div id="errorMsgBDY" name="errorMsgBDY" class="errorMessage" role="error"></div>
              </div>



              <div class="form-group">
                <label for="UserAddr" class="control-label col-sm-3 lables">Address<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <textarea  class="form-control" id="UserAddr" name="UserAddr" placeholder="Address"></textarea>
                  <!-- <input type="textarea" class="form-control" id="UserAddr" name="UserAddr" placeholder="Adrress"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" > -->
                </div>
                <div id="errorMsgAD" name="errorMsgAD"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label" for="UserRole">Role</label>
                <div class="col-sm-4">
                  <select id="UserRole" name="UserRole" class="multiselect-ui form-control" multiple="multiple">
                    <?php $roleList = GetRoles();
                      if(mysql_num_rows($roleList)!=0)
                      {
                        ChromePhp::log("yes got some data");

                        while($role = mysql_fetch_assoc($roleList))
                        {
                            //ChromePhp::log("city ". $city['CityName']);
                            // iconv("ISO-8859-1", "UTF-8", $city['CityName'])
                            echo '<option value="' . $role['RoleID'] . '">' . $role['RoleName'] . '</option>';
                        }
                      }
                    ?>
                  </select>
                </div>
                <div id="errorMsgUR" name="errorMsgUR"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserStatus" class="control-label col-sm-3 lables">Status</label>
                <div class="col-sm-4">
                    <label class="radio-inline"> <input type="radio" name="Status" id="Active" value="Active" checked>Active</label>
                    <label class="radio-inline"> <input type="radio" name="Status" id="Blocked" value="Blocked">Blocked</label>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

                <div class="col-sm-9">
                  <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="<?php if(isset($subEventData['EventCode'])) echo 'margin-left:90px'; else echo 'margin-left:50px'; ?>" />
                  <input type="hidden" name="UKey" value="1" id="ID_UKey" />
                  <!-- <input type="hidden" name="eventCode" id="eventCode" value="<?php if(isset($subEventData['EventCode'])) echo $subEventData['EventCode'] ?>" /> -->
                  <button type="reset" class="btn btn-sm btn-default" style="visibility:<?php if(isset($subEventData['EventCode'])) echo 'hidden'; else 'visible'?> ">Clear</button>
                </div>
              </div>

            </form>
              <!-- /.form -->
      </div>
        <!-- /.form div -->
      </div>
      <!-- /.box body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
