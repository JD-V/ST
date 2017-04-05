<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'WALKIN';
if(isLogin() && isAdmin())
{
  require '_header.php';
  $curStatusList = GetCurrentStatusList();
  $StaffNames = GetStaffNamesList();
?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>Walk-In registration</h1>
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
        if( isset($_POST['RegDate']) && !empty($_POST['RegDate']) &&
            isset($_POST['MemberName']) && !empty($_POST['MemberName']) &&
            isset($_POST['MemberPhone']) && !empty($_POST['MemberPhone']) &&
            isset($_POST['MemberAvail']) && !empty($_POST['MemberAvail']) &&
            isset($_POST['StDate']) && !empty($_POST['StDate']) )
          {
            $MemberName = mysql_real_escape_string(trim($_POST['MemberName']));
            $MemberPhone = mysql_real_escape_string(trim($_POST['MemberPhone']));
            $RegDate = mysql_real_escape_string(trim($_POST['RegDate']));
            $MemberAvail = mysql_real_escape_string(trim($_POST['MemberAvail']));
            $StDate = mysql_real_escape_string(trim($_POST['StDate']));
            $StStaff = mysql_real_escape_string(trim($_POST['StStaff']));
            $StCurStat = mysql_real_escape_string(trim($_POST['StCurStat']));
            $MemberID ="";
            $MemberEmail= "";
            $MemberBDay = "";
            $MemCity = 1;
            $MemAddress = "";
            $MemProf = "";
            $MemRef = 1;
            $StNote = " ";
            if(isset($_POST['MemberID']) && !empty($_POST['MemberID']) )
              $MemberID = $_POST['MemberID'];

            if(isset($_POST['MemberEmail']) && !empty($_POST['MemberEmail']) )
              $MemberEmail = $_POST['MemberEmail'];

            if(isset($_POST['MemberBDay']) && !empty($_POST['MemberBDay']) )
              $MemberBDay = $_POST['MemberBDay'];

            if(isset($_POST['MemCity']) && !empty($_POST['MemCity']) )
              $MemCity = $_POST['MemCity'];

            if(isset($_POST['MemAddress']) && !empty($_POST['MemAddress']) )
              $MemAddress = $_POST['MemAddress'];

            if(isset($_POST['MemProf']) && !empty($_POST['MemProf']) )
              $MemProf = $_POST['MemProf'];

            if(isset($_POST['MemRef']) && !empty($_POST['MemRef']) )
              $MemRef = $_POST['MemRef'];

            if(isset($_POST['StNote']) && !empty($_POST['StNote']) )
              $StNote = $_POST['StNote'];


            if($UpdateSubEvent = WalkinRegister($MemberName,$MemberPhone,$RegDate,
                                                $MemberAvail,$MemberID,$MemberEmail,
                                                $MemberBDay,$MemCity,$MemAddress,
                                                $MemProf,$MemRef ) )
            {
              $MemberID = GetMaxMemberID();

              chromephp::log("Member ID file " . $MemberID);
              // add status
              AddStatus($StDate,$StStaff,$StCurStat,$StNote,$MemberID );
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>
                          Member Added successfully!
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
        <h3 class="box-title"><?php echo 'Register'; ?></h3>

       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="WalkInRegistrationForm">
          <form class="form-horizontal"  role="form" name="walkInForm" id="walkInForm" action="WalkIn.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
                  <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

                  <div class="form-group">
                    <label for="RegDate" class="control-label col-sm-3 lables">Date<span class="mandatoryLabel">*</span></label>
                    <div class='col-sm-4'>
                        <input type="datetime" class="form-control" name="RegDate" id="RegDate" />
                    </div>
                    <div id="errorMsgRDT" name="errorMsgRDT" class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="MemberName" class="control-label col-sm-3 lables">Name<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="MemberName" name="MemberName" placeholder="Name"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                    </div>
                    <div id="errorMsgMN" name="errorMsgMN"  class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="MemberID" class="control-label col-sm-3 lables">ID Number</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="MemberID" name="MemberID" maxlength="11" placeholder="Enter a ID"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                    </div>
                    <div id="errorMsgMID" name="errorMsgMID"  class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="MemberEmail" class="control-label col-sm-3 lables">Email</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="MemberEmail" name="MemberEmail" placeholder="Email Id"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                    </div>
                    <div id="errorMsgMEM" name="errorMsgMEM"  class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="MemberPhone" class="control-label col-sm-3 lables">Phone Number<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" maxlength="10" id="MemberPhone" name="MemberPhone" placeholder="Phone Number (10 digits)"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                    </div>
                    <div id="errorMsgMEP" name="errorMsgMEP"  class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="MemberAvail" class="control-label col-sm-3 lables">Availibility<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <textarea  class="form-control" id="MemberAvail" name="MemberAvail" placeholder="Availibility"></textarea>
                    </div>
                    <div id="errorMsgMAV" name="errorMsgMAV" class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="MemberBDay" class="control-label col-sm-3 lables">Birthday</label>
                    <div class='col-sm-4'>
                        <input type="MemberBDay" class="form-control" name="MemberBDay" id="MemberBDay" />
                    </div>
                    <div id="errorMsgMBD" name="errorMsgMBD" class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="MemCity" class="control-label col-sm-3 lables">City</label>
                    <div class="col-sm-4">
                      <select class="form-control" id="MemCity" name="MemCity">
                        <option selected="true" disabled="disabled" style="display: none" value="default" >select city</option>
                        <?php $cityList = GetCityList();
                          if(mysql_num_rows($cityList)!=0)
                          {
                            ChromePhp::log("yes got some data");

                            while($city = mysql_fetch_assoc($cityList))
                            {
                                //ChromePhp::log("city ". $city['CityName']);
                                // iconv("ISO-8859-1", "UTF-8", $city['CityName'])
                                echo '<option value="' . $city['CityID'] . '">' . $city['CityName'] . '</option>';
                            }
                          }
                        ?>
                    </select>
                    </div>
                    <div id="errorMsgCT" name="errorMsgCT" class="errorMessage" role="error"></div>
                  </div>

                <div class="form-group">
                  <label for="MemAddress" class="control-label col-sm-3 lables">Address</label>
                   <div class="col-sm-4">
                     <textarea  class="form-control" id="MemAddress" name="MemAddress" placeholder="Address"></textarea>
                   </div>
                   <div id="errorMsgMNT" name="errorMsgMNT" class="errorMessage" role="error"></div>
                </div>


                <div class="form-group">
                  <label for="MemProf" class="control-label col-sm-3 lables">Profession</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="MemProf" name="MemProf" placeholder="Profession"  value="<?php  if(isset($subEventData['EventCode'])) echo  $subEventData['TimeFrame']; ?>" >
                  </div>
                </div>

                <div class="form-group">
                  <label for="MemRef" class="control-label col-sm-3 lables">Reference</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="MemRef" name="MemRef">
                      <option selected="true" disabled="disabled" style="display: none" value="default" >select reference</option>
                      <?php $refList = GetReferenceList();
                        if(mysql_num_rows($refList)!=0)  {
                          ChromePhp::log("yes got some data");

                          while($ref = mysql_fetch_assoc($refList)) {
                              //ChromePhp::log("city ". $city['CityName']);
                              // iconv("ISO-8859-1", "UTF-8", $city['CityName'])
                              echo '<option value="' . $ref['ReferenceID'] . '">' . $ref['ReferenceValue'] . '</option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <fieldset id="Status1">
                  <legend>Status<span class="mandatoryLabel">*</span></legend>

                  <div class="form-group">
                    <label for="StDate1" class="control-label col-sm-3 lables">Date<span class="mandatoryLabel">*</span></label>
                    <div class='col-sm-4'>
                        <input type="datetime" class="form-control" name="StDate" id="StDate1" />
                    </div>
                    <div id="errorMsgSTD" name="errorMsgSTD" class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="StStaff1" class="control-label col-sm-3 lables">Staff<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control" id="StStaff1" name="StStaff">
                        <option selected="true" disabled="disabled" style="display: none" value="default" >select staff</option>
                        <?php
                          if(mysql_num_rows($StaffNames)!=0) {
                            ChromePhp::log("yes got some data");
                            while($staff = mysql_fetch_assoc($StaffNames)) {
                                echo '<option value="' . $staff['UserID'] . '">' . $staff['Name'] . '</option>';
                            }
                          }
                        ?>
                      </select>
                    </div>
                    <div id="errorMsgSTF" name="errorMsgSTF" class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="StCurStat1" class="control-label col-sm-3 lables">Current Status<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control" id="StCurStat1" name="StCurStat">
                        <option selected="true" disabled="disabled" style="display: none" value="default" >select current status</option>
                          <?php
                            if(mysql_num_rows($curStatusList)!=0)
                            {
                              ChromePhp::log("yes got some data");

                              while($stat = mysql_fetch_assoc($curStatusList))
                              {
                                  echo '<option value="' . $stat['CurrentStatusID'] . '">' . $stat['CurrentStatusName'] . '</option>';
                              }
                            }
                          ?>
                        </select>
                      </select>
                    </div>
                    <div id="errorMsgCST" name="errorMsgCST" class="errorMessage" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="StNote1" class="control-label col-sm-3 lables">Note</label>
                    <div class="col-sm-4">
                      <textarea  class="form-control" id="StNote1" name="StNote" placeholder="Notes"></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>
                    <div class="col-sm-4">
                      <button type="button" name="AddStatus" id="AddStatus1" class="btn btn-sm btn-primary" style = "margin-left:0px" onclick="AddSection('Status1')">Add</button>
                      <button type="button" name="RemoveStatus" id="RemoveStatus1" class="btn btn-sm btn-danger" style="margin-left:5px"  onclick="RemoveSection('Status1')">Remove</button>
                    </div>
                  </div>

                </fieldset>

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
