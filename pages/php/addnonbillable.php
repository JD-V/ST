<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADNONBILLREC';
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
    <div>Add Non Billable record
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
        if( isset($_POST['RecDate']) && !empty($_POST['RecDate']) &&
            isset($_POST['Perticulars']) && !empty($_POST['Perticulars']) &&
            isset($_POST['AmountPaid']) && !empty($_POST['AmountPaid']) )
          {
            $dateStr = mysql_real_escape_string(trim($_POST['RecDate']));
            $date = DateTime::createFromFormat('d-m-Y H:i', $dateStr);

            $RecDate = $date->format('Y-m-d H:i:s');
            $Perticulars = mysql_real_escape_string(trim($_POST['Perticulars']));
            $AmountPaid = mysql_real_escape_string(trim($_POST['AmountPaid']));
            $Notes = '';
             if(isset($_POST['Notes']) && !empty($_POST['Notes']) )
               $Notes = mysql_real_escape_string(trim($_POST['Notes']));
            $RecordId = 0;
            if(isset($_POST['RecordId']) && !empty($_POST['RecordId']) )
               $RecordId = mysql_real_escape_string(trim($_POST['RecordId']));

             ChromePhp::log("RecordId = ");
             ChromePhp::log($RecordId);
             $Result = false;
             $msg = "";
            if($RecordId == 0)
            {
               $Result = AddNonBillable($RecDate,$Perticulars,$AmountPaid,$Notes);
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
        <h3 class="box-title"><?php echo 'Add'; ?></h3>
        <?php
          if($GetRecord = @GetNonBillableRecord($_GET['id']))
          {
            ChromePhp::log("got record id ");
            $Record  = mysql_fetch_assoc($GetRecord);
            ChromePhp::log($Record);
          }
        ?>
       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="AddOrUpdateNonBillableRecord">
          <form class="form-horizontal"  name="NonBillable" id="NonBillable" action="addnonbillable.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="RecDate" class="control-label col-sm-3 lables">Bill Date<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <?php 
                    $dateVal = "";
                    if(isset($Record['RecordDate'])) {
                      $dateStr = $Record['RecordDate']; 
                      $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateStr); 
                      $dateVal =   $date->format('d-m-Y H:i'); 
                    }
                  ?>
                  <input type="text" class="form-control" name="RecDate" value = "<?php echo $dateVal ?>"/>
                </div>
              </div>

              <div class="form-group">
                <label for="Perticulars" class="control-label col-sm-3 lables">Perticulars</label>
                <div class="col-sm-4">
                  <textarea  class="form-control" name="Perticulars" placeholder="Perticulars"><?php  if(isset($Record['Perticulars'])) echo  $Record['Perticulars']; ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label for="AmountPaid" class="control-label col-sm-3 lables">Amount paid<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control amount" name="AmountPaid" onkeypress="return isNumberKey(event)" placeholder="00" value="<?php  if(isset($Record['AmountPaid'])) echo  $Record['AmountPaid']; ?>" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="Notes" class="control-label col-sm-3 lables">Notes</label>
                <div class="col-sm-4">
                  <textarea  class="form-control" name="Notes" placeholder="Notes" ><?php  if(isset($Record['Notes'])) echo  $Record['Notes']; ?></textarea>
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
