<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'COURSES';
if(isLogin() && isAdmin())
{
require '_header.php';
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>Courses</h1>
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
        if( isset($_POST['CourseName']) && !empty($_POST['CourseName']) &&
            isset($_POST['CourseDurationDays']) && !empty($_POST['CourseDurationDays']) &&
            isset($_POST['CourseDurationMins']) && !empty($_POST['CourseDurationMins']) &&
            isset($_POST['PricePerInstruction']) && !empty($_POST['PricePerInstruction']) )
          {
            $CourseName = mysql_real_escape_string(trim($_POST['CourseName']));
            if(isset($_POST['CourseCode']) && !empty($_POST['CourseCode']))
              $CourseCode = mysql_real_escape_string(trim($_POST['CourseCode']));
            else
              $CourseCode = "";
            $CourseDurationDays = mysql_real_escape_string(trim($_POST['CourseDurationDays']));
            $CourseDurationMins = mysql_real_escape_string(trim($_POST['CourseDurationMins']));
            $PricePerInstruction = mysql_real_escape_string(trim($_POST['PricePerInstruction']));


            if($UpdateSubEvent = AddCourse($CourseName,$CourseCode,$CourseDurationDays,$CourseDurationMins,$PricePerInstruction) )
            {
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>
                          Course Added successfully!
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
        <h3 class="box-title"><?php echo 'Add Course'; ?></h3>

       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="CreateOrUpdateCourseForm">
          <form class="form-horizontal" name="CoursesForm" id="CoursesForm" action="Courses.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
                  <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" required="true">

                  <div class="form-group">
                    <label for="CourseName" class="control-label col-sm-3 lables">Course Name<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="CourseName" name="CourseName" placeholder="Course" value="" >
                    </div>
                    <div id="errorMsgCN" name="errorMsgCN" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="CourseCode" class="control-label col-sm-3 lables">Course Code&nbsp;&nbsp;</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="CourseCode" name="CourseCode" placeholder="code" value="" >
                    </div>
                    <div id="errorMsgCC" name="errorMsgCC" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="CourseDurationDays" class="control-label col-sm-3 lables">Course Duration (Days)<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="CourseDurationDays" name="CourseDurationDays" placeholder="Duration in days" value="00" >
                    </div>
                    <div id="errorMsgCDD" name="errorMsgCDD" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="CourseDurationMins" class="control-label col-sm-3 lables">Course Duration (Mins)<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="CourseDurationMins" name="CourseDurationMins" placeholder="Duration in mins"  value="00" >
                    </div>
                    <div id="errorMsgCDM" name="errorMsgCDM" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="PricePerInstruction" class="control-label col-sm-3 lables">Price per instruction<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control maskedExt" id="PricePerInstruction" maskedFormat="3,2" name="PricePerInstruction" placeholder="0.00"  value="0.00" >
                    </div>
                    <div id="errorMsgPPI" name="errorMsgPPI" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div>

                  <!-- <div class="form-group">
                    <label for="DurationPerInstruction" class="control-label col-sm-3 lables">Duration per instruction<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="DurationPerInstruction" name="DurationPerInstruction" placeholder="00"  value="" >
                    </div>
                    <div id="errorMsgDPI" name="errorMsgDPI" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div> -->

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
