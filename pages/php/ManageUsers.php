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
      <!-- 
      <ol class="breadcrumb">
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
      if($_POST['akey'] == $_SESSION['AUTH_KEY'])
      {
        if( isset($_POST['UserName']) && !empty($_POST['UserName']) &&
            isset($_POST['UserPhone']) && !empty($_POST['UserPhone']) &&
            isset($_POST['UserAddr']) && !empty($_POST['UserAddr']) &&
            isset($_POST['UserRole']) && !empty($_POST['UserRole']) &&
            isset($_POST['UserPassword']) && !empty($_POST['UserPassword']) &&
            isset($_POST['UserStatus']) && !empty($_POST['UserStatus'])  )
          {
            $user = new user();
            $user->userID = GetMaxUserID() + 1;
            $user->userName = FilterInput($_POST['UserName']);
            $user->userPhone = FilterInput($_POST['UserPhone']);
            $user->userAddr = FilterInput($_POST['UserAddr']);
            $user->userRoleID = FilterInput($_POST['UserRole']);
            $user->status = FilterInput($_POST['UserStatus']);
            
            if($user->status == 'Active')
              $user->status = 1;
            else $user->status = 0;

            $user->password = FilterInput($_POST['UserPassword']);

            if(AddUser($user)) {
                  $target_dir = realpath(dirname(__FILE__) . '/../uploads');
                  $message = "";
                  // echo $target_dir;
                  $target_file = $target_dir .'/' . basename($_FILES["userIdProof"]["name"]);

                  $uploadOk = 1;
                  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                  // Allow certain file formats
                  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                  && $imageFileType != "pdf" ) {
                      $message = "[only JPG, JPEG, PNG & PDF files are allowed]";
                      $uploadOk = 0;
                  } else {
                      $target_file = $target_dir .'/' . 'user'.$user->userID . '.' .$imageFileType;
                  }

                  $check = getimagesize($_FILES["userIdProof"]["tmp_name"]);
                  if($check !== false) {
                      $uploadOk = 1;
                  } else {
                      $message =  " [File is not an image]";
                      $uploadOk = 0;
                  }

                  // Check if file already exists
                  if (file_exists($target_file)) {
                      $message = " [file already exists]";
                      $uploadOk = 0;
                  }
                  // Check file size
                  if ($_FILES["userIdProof"]["size"] > 500000) {
                      $message = "[Too large file]";
                      $uploadOk = 0;
                  }

                  // Check if $uploadOk is set to 0 by an error
                  if ($uploadOk == 0) {
                      echo MessageTemplate(MessageType::Success, "User has been added, but unable to save document due to following error " . $message);
                  // if everything is ok, try to upload file
                  } else {
                      if (move_uploaded_file($_FILES["userIdProof"]["tmp_name"], $target_file)) {
                          //echo "The file ". basename( $_FILES["userIdProof"]["name"]). " has been uploaded.";
                          echo MessageTemplate(MessageType::Success, "User Added successfully!");
                      } else {
                        echo MessageTemplate(MessageType::Success, "User has been added, but unable to save document !");
                           $$message = "Sorry, there was an error uploading your file.";
                      }
                  }
               
            }
            else {
              echo MessageTemplate(MessageType::Failure, "Something went wrong, try again.");
            }
          } else {
            echo MessageTemplate(MessageType::Failure, "Please enter all the details.");
          }
          /* codefellas Security Robot for re-submission of form */
          $_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
          ChromePhp::log($_SESSION['AUTH_KEY']);
          /* END */
        } else {
          echo MessageTemplate(MessageType::RoboWarning, "");
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
          <form class="form-horizontal" enctype="multipart/form-data"  name="Userform" id="Userform" action="ManageUsers.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="UserName" class="control-label col-sm-3 lables">Name<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Name"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                </div>
                <div id="errorMsgUN" name="errorMsgUN"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserPhone" class="control-label col-sm-3 lables">Phone<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" maxlength="10" id="UserPhone" name="UserPhone" placeholder="Enter your Phone Number"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                </div>
                <div id="errorMsgUPH" name="errorMsgUPH" class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserAddr" class="control-label col-sm-3 lables">Address<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <textarea  class="form-control" id="UserAddr" name="UserAddr" placeholder="Address"></textarea>
                </div>
                <div id="errorMsgAD" name="errorMsgAD"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label" for="UserRole">Role</label>
                <div class="col-sm-4">
                  <select  class="form-control" id="UserRole" name="UserRole" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >select role</option>
                    <?php $roleList = GetRoles();
                      if(mysql_num_rows($roleList)!=0) {
                        while($role = mysql_fetch_assoc($roleList)) {
                            echo '<option value="' . $role['RoleID'] . '">' . $role['RoleName'] . '</option>';
                        }
                      }
                    ?>
                  </select>
                </div>
                <div id="errorMsgUR" name="errorMsgUR"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserPassword" class="control-label col-sm-3 lables">Password<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="UserPassword" name="UserPassword" placeholder="password" >
                </div>
                <div id="errorMsgUP" name="errorMsgUP"  class="errorMessage" role="error"></div>
              </div>

              <div class="form-group">
                <label for="UserStatus" class="control-label col-sm-3 lables">Status</label>
                <div class="col-sm-4">
                    <label class="radio-inline"> <input type="radio" name="UserStatus" id="Active" value="Active" checked>Active</label>
                    <label class="radio-inline"> <input type="radio" name="UserStatus" id="Blocked" value="Blocked">Blocked</label>
                </div>
              </div>

              <div class="form-group">
                <label for="userIdProof" class="control-label col-sm-3 lables">ID Proof</label>
                <div class="col-sm-4">
                  <input id = "fileupload"  class="btn btn-sm btn-default" type = "file" name="userIdProof" required/>
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
