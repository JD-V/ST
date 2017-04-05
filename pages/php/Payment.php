<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'PAYMENT';
if(isLogin() && isAdmin())
{
require '_header.php';
$courseList = GetCourseList();
StoreCoursesListSession();
?>

<script type = "text/javascript" >
var Jarray = <?php echo json_encode($_SESSION['CourseList']); ?>

function calcualteAmount(e) {
  if (e == undefined)
    e = document.getElementById("CourseName");
  //console.log(Jarray);
  var selectedCourse = e.options[e.selectedIndex].value;
  console.log("Sel " + selectedCourse);

  var tobePaidE = document.getElementById("AmtTobePaid");
  var paidE = document.getElementById("AmtPaid");
  var PurchasedDurationE = document.getElementById("PurchasedDuration");

  var item = Jarray.find(x => x.courseID == selectedCourse);
  console.log(item);

  if (document.getElementById('credit').checked) {
    tobePaidE.value = +((item.PPI * PurchasedDurationE.value * 1.18).toFixed(2));
  }
  else {
    tobePaidE.value = +((item.PPI * PurchasedDurationE.value).toFixed(2));
  }
}

function calualteBasic(e) {
    calcualteAmount(e);
}

var keyVal = new Array();
$(document).ready(function(){

  $.ajax({dataType: "json", type: "GET", url: "GetMembers.php?action=retrive&WalkIn=1", success: function(result){
    var members= result.map(function(a) {return a.Name + "******** | " + a.IDNumber.substring(8,11)});
    for (var i = 0; i < result.length; i++) {
      keyVal[result[i].MemberID] = result[i].Name + " | ********" + result[i].IDNumber.substring(8,11)
    }
    var members = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: keyVal
    });
    // Initializing the typeahead
    $('.typeahead').typeahead({
        hint: true,
        highlight: true, /* Enable substring highlighting */
        minLength: 2 /* Specify minimum characters required for showing result */
    },
    {
        name: 'members',
        source: members
    });
  }});
});

function objectKeyByValue (obj, val) {
  return Object.entries(obj).find(i => i[1] === val);
}

function setMemberID(e) {
  var memberName = e.value;
  var memberID = objectKeyByValue(keyVal,memberName);
    jQuery('#memberID').val(memberID[0]);
}

</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Payment
      <small></small><div>
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
        ChromePhp::log("name" . $_POST['PaymentDateTime']);
        ChromePhp::log("id " . $_POST['CourseName']);
        ChromePhp::log("email" . $_POST['PurchasedDuration']);
        ChromePhp::log("ph" . $_POST['paymentType']);
        ChromePhp::log("city" . $_POST['AmtPaid']);
        ChromePhp::log("bd" . $_POST['AmtTobePaid']);


        if( isset($_POST['memberID']) && !empty($_POST['memberID']) &&
            isset($_POST['PaymentDateTime']) && !empty($_POST['PaymentDateTime']) &&
            isset($_POST['CourseName']) && !empty($_POST['CourseName']) &&
            isset($_POST['PurchasedDuration']) && !empty($_POST['PurchasedDuration']) &&
            isset($_POST['paymentType']) && !empty($_POST['paymentType']) &&
            isset($_POST['AmtTobePaid']) && !empty($_POST['AmtTobePaid']) &&
            isset($_POST['AmtPaid']) && !empty($_POST['AmtPaid']) )
          {
            $PaymentDateTime = mysql_real_escape_string(trim($_POST['PaymentDateTime']));
            $CourseID = mysql_real_escape_string(trim($_POST['CourseName']));
            $PurchasedDuration = mysql_real_escape_string(trim($_POST['PurchasedDuration']));
            $paymentType = mysql_real_escape_string(trim($_POST['paymentType']));
            $AmtTobePaid = mysql_real_escape_string(trim($_POST['AmtTobePaid']));
            $AmtPaid = mysql_real_escape_string(trim($_POST['AmtPaid']));
            $MemberID = mysql_real_escape_string(trim($_POST['memberID']));
            $Note = "";
            if(isset($_POST['Note']) && !empty($_POST['Note']))
              $Note = mysql_real_escape_string(trim($_POST['Note']));

            if($UpdateSubEvent = AddPayment($PaymentDateTime,$CourseID,$PurchasedDuration,$paymentType,$AmtTobePaid,$AmtPaid,$Note,$MemberID) )
            {
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>
                          Payment Added successfully!
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
        <h3 class="box-title"><?php echo 'Record Payment'; ?></h3>

       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="CreateOrUpdatePaymentForm">
          <form class="form-horizontal"  role="form" name="PaymentForm" id="PaymentForm" action="Payment.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
                  <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" required="true">

                  <div class="form-group">
                    <label for="MermberName" class="control-label col-sm-3 lables" >Member Name<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" id="MermberName" name="MermberName" class="form-control typeahead tt-query " autocomplete="on" spellcheck="false" onblur="setMemberID(this)">
                    </div>
                    <div id="errorMsgMN" name="errorMsgMN" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div>
                  </div>

                  <input type="hidden" value="0" name="memberID" id="memberID" >

                  <div class="form-group">
                    <label for="PaymentDateTime" class="control-label col-sm-3 lables" >Payment Date Time<span class="mandatoryLabel">*</span></label>
                     <div class='col-sm-4'>
                       <input type="datetime" class="form-control" name="PaymentDateTime" id="PaymentDateTime" value=""  onblur="" />
                     </div>
                      <div id="errorMsgPDT" name="errorMsgPDT" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div>
                   </div>

                  <div class="form-group">
                    <label for="CourseName" class="control-label col-sm-3 lables">Course<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control" id="CourseName" name="CourseName"  onchange="calcualteAmount(this);" onfocus="calualteBasic(this);" >
                        <option selected="true" disabled="disabled" style="display: none" value="default" >Pick any course!</option>
                        <?php
                          if(mysql_num_rows($courseList)!=0)
                          {
                            ChromePhp::log("yes got some data");

                            while($curse = mysql_fetch_assoc($courseList))
                            {
                                echo '<option value="' . $curse['courseID'] . '">' . $curse['CourseName'] . '</option>';
                            }
                          }
                        ?>
                      </select>
                    </div>
                    <div id="errorMsgCN" name="errorMsgCN" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="PurchasedDuration" class="control-label col-sm-3 lables">Purchased Duration<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="PurchasedDuration" name="PurchasedDuration" placeholder="Purchased Duration" value="" onchange = calcualteAmount() >
                    </div>
                    <div id="errorMsgPD" name="errorMsgPD" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-sm-3 lables">Payment Type<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <label class="radio-inline"> <input type="radio" name="paymentType" id="credit" value="1" onclick ="calcualteAmount()"> Credit card </label>
                      <label class="radio-inline"> <input type="radio" name="paymentType" id="cash" value="2" onclick ="calcualteAmount()" > Cash </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="AmtTobePaid" class="control-label col-sm-3 lables">To be Paid</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="AmtTobePaid" name="AmtTobePaid" placeholder="0.00" >
                    </div>
                    <!-- <div id="errorMsgPD" name="errorMsgTBP" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div> -->
                  </div>

                  <div class="form-group">
                    <label for="AmtPaid" class="control-label col-sm-3 lables">Paid<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control maskedExt" id="AmtPaid" maskedFormat="3,2" name="AmtPaid" placeholder="0.00" value="0.00" >
                    </div>
                    <div id="errorMsgAPD" name="errorMsgAPD" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
                  </div>

                  <div class="form-group">
                    <label for="Note" class="control-label col-sm-3 lables">Notes</label>
                     <div class="col-sm-4">
                       <textarea  class="form-control" id="Note" name="Note" placeholder="Note"></textarea>
                     </div>
                     <div id="errorMsgNT" name="errorMsgNT" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div>
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
