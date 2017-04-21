<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDSUPPLIER';
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
    <div>Add new supplier
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
        ChromePhp::log("sbumitting ");
        if( isset($_POST['SupplierName']) && !empty($_POST['SupplierName']) &&
            isset($_POST['TinNum']) && !empty($_POST['TinNum']))
          {
            $RecordId = 0;
            $SupplierName = mysql_real_escape_string(trim($_POST['SupplierName']));
            $TinNum = mysql_real_escape_string(trim($_POST['TinNum']));

            $MobileNum = '';
             if(isset($_POST['MobileNum']) && !empty($_POST['MobileNum']) )
               $MobileNum = mysql_real_escape_string(trim($_POST['MobileNum']));

            $Email = '';
            if(isset($_POST['Email']) && !empty($_POST['Email']) )
              $Email = mysql_real_escape_string(trim($_POST['Email']));

            $Address = '';
            if(isset($_POST['Address']) && !empty($_POST['Address']) )
              $Address = mysql_real_escape_string(trim($_POST['Address']));

            $ContactPerson = '';
            if(isset($_POST['ContactPerson']) && !empty($_POST['ContactPerson']) )
              $ContactPerson = mysql_real_escape_string(trim($_POST['ContactPerson']));


             ChromePhp::log("RecordId = ");
             ChromePhp::log($RecordId);
             $Result = false;
             $msg = "";
            if($RecordId == 0)
            {
               $Result = AddSupplier($SupplierName, $TinNum, $MobileNum, $Email, $Address, $ContactPerson);
               $msg = ' Record Added successfully!';

            }
            else
            {
              $Result = UpdateNonBillable($RecordId,$RecDate,$Perticulars,$AmountPaid,$Notes); 
              $msg = ' Record Updated successfully!';
            }

            if($Result)
            {
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>'. $msg . '</div>'; //needs to correct 
            }
            else
            {
              echo '<div class="alert alert-block alert-danger">
                      <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                      </button>
                      <i class="ace-icon fa fa-ban red"></i>
                      Something went wrong, please contact your system admin.
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
        <?php
          $title="Add";
          if($GetRecord = @GetNonBillableRecord($_GET['id'])) {
            ChromePhp::log("got record id ");
            $Record  = mysql_fetch_assoc($GetRecord);
            ChromePhp::log($Record);
            $title="Update";
          }
        ?>
        <h3 class="box-title"><?php echo $title; ?></h3>
       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="AddOrUpdateSupplier">
          <form class="form-horizontal"  name="Supplier" id="Supplier" action="addSupplier.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="SupplierName" class="control-label col-sm-3 lables">Supplier Name<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="text" class="form-control" name="SupplierName" placeholder = "Name" value = "<?php  if(isset($Record['SupplierName'])) echo  $Record['SupplierName']; ?>"/>
                </div>
              </div>
              <div class="form-group">
                <label for="TinNum" class="control-label col-sm-3 lables">TIN Number<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="text" class="form-control" name="TinNum" onkeypress = "return isNumberKey(event)" placeholder = "TIN Number" value = "<?php  if(isset($Record['TinNum'])) echo  $Record['TinNum']; ?>"/>
                </div>
              </div>
              
              <div class="form-group">
                <label for="MobileNum" class="control-label col-sm-3 lables">Mobile Number</label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-mobile"></span>
                    </span>
                    <input type="text" class="form-control" name="MobileNum" onkeypress="return isNumberKey(event)" value="<?php  if(isset($Record['AmountPaid'])) echo  $Record['AmountPaid']; ?>" >
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="Email" class="control-label col-sm-3 lables">Email</label>
                <div class="col-sm-4">
                <div class='input-group'>
                <span class="input-group-addon">
               <span class="fa fa-envelope"></span>
                    </span>
                    <input type="text" class="form-control" name="Email" placeholder = "Email ID"  value="<?php  if(isset($Record['AmountPaid'])) echo  $Record['AmountPaid']; ?>" >
                </div>
                </div>
              </div>
              <div class="form-group">
                <label for="Address" class="control-label col-sm-3 lables">Address</label>
                <div class="col-sm-4">
                  <textarea  class="form-control" name="Address" placeholder="Address" ><?php  if(isset($Record['Notes'])) echo  $Record['Notes']; ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="ContactPerson" class="control-label col-sm-3 lables">Contact Person Name</label>
                <div class='col-sm-4'>
                  <input type="text" class="form-control" name="ContactPerson" placeholder = "Name" value = "<?php  if(isset($Record['ContactPerson'])) echo  $Record['ContactPerson']; ?>"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

                <div class="col-sm-9">
                  <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="<?php if(isset($Record['RecordID'])) echo 'margin-left:90px'; else echo 'margin-left:50px'; ?>" />
                  <input type="hidden" name="UKey" value="1" id="ID_UKey" />
                  <input type="hidden" name="RecordId" value="<?php if(isset($Record['RecordID'])) echo $Record['RecordID'] ?>" />
                  <button type="reset" class="btn btn-sm btn-default" style="visibility:<?php if(isset($Record['RecordID'])) echo 'hidden'; else 'visible'?> ">Clear</button>
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
