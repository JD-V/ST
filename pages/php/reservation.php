<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'RESERVE';
if(isLogin() && isAdmin())
{
require '_header.php';
$courseList = GetCourseList();
?>
<script type="text/javascript">
  var keyVal = new Array();
  var $fieldsetmain ='';
  var courseDuration = 0;
  $(document).ready(function(){
      $fieldsetmain = $('#memberFieldset');
      $('#simpliest-usage').multiDatesPicker();
      AddMembers($fieldsetmain);

    $('#simpliest-usage').multiDatesPicker({

	     onSelect: function() {
         var dates = $('#simpliest-usage').multiDatesPicker('getDates');
         console.log(dates);

         for (var i = 0; i < dates.length; i++) {
           var $t = $('#timeGrps label:contains('+dates[i]+')')
           if($t.length == 1)
             continue;
          $childCopy  = $('#TimeGroup').clone(); //clone child
          $( $childCopy ).find( "label" ).html(dates[i]); // update lable
          $( $childCopy ).find( "input" ).val(dates[i]); // update lable
          $( $childCopy ).css('display', 'block'); // update lable
          $('#TimeGroup').parent().append($childCopy);
        }

        $('#timeGrps').find('label').each(function(){
            var t = $(this).html().substring(0, 10);
            if(dates.indexOf(t) == -1 && t != "00/00/0000")
              $(this).parent().remove();
            //$(this).unbind('click');
        });
        //resetFormValidator('reservationForm');

       }

    });
    $('#simpliest-usage').multiDatesPicker('resetDates', 'picked');
  });

  // $("reservationForm").on("click", ".add_member", function (e) {
  //     e.preventDefault();
  //     var tplData = {
  //         i: counter
  //     };
  //     $("#word_exp_area").append(tpl(tplData));
  //     counter += 1;
  //     $('.work_emp_name').each(function () {
  //         $(this).rules("add", {
  //             required: true
  //         });
  //     });
  // });

function AddMembers($fieldset) {
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
    // destroy
    $('.typeahead').typeahead('destroy');
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

}

function fillTillTime(e) {
  var value = e.options[e.selectedIndex].value;
  var $p = $(e).parent().parent().find('.tillTimeclass');
  var newInd  = e.selectedIndex + courseDuration/15 + (courseDuration%15 !=0 ?1 :0);
  $p.prop('selectedIndex', newInd);
  var $s = $(e);
  //check availibilty
  var fromTime = e.options[e.selectedIndex].value;
  var tillTime = $p[0].options[newInd].value;


  var $dateitem = $(e).parent().parent().find('.dateClass');
  var date1 = $dateitem[0].value;
  checkAvailibility($s,$p,date1, fromTime, tillTime);

    //(p).next().next().html('');   //remove error message if exists
}

function checkAvailibility($s, $p, date1, fromTime, tillTime) {

  var e = document.getElementById("RoomName");
  var roomID = e.options[e.selectedIndex].value;
  if(roomID == 'default') {
    $p.parent().next().html('');
    $p.prop('selectedIndex', 0);
    $s.prop('selectedIndex', 0);
    $('<p><i class="ace-icon fa fa-hand-paper-o"></i> please select room first </p>').insertAfter($p.parent()).addClass('warningMessage');
    return;
  }
  $.ajax({
    type: "GET",
    url: "roomAvailibility.php?action=retrive&roomID="+roomID +"&dateValue="+date1 +"&fromTime="+ fromTime +"&tillTime="+ tillTime,
    success: function(result) {
      $p.parent().next().html('');   //remove error message if exists
      if(result == '1')
        $('<p><i class="ace-icon fa fa-times"></i> Not availalbe </p>').insertAfter($p.parent()).addClass('failureMessage');
      else
      $('<p><i class="ace-icon fa fa-check green"></i> Availalbe </p>').insertAfter($p.parent()).addClass('noramlMessage');
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
}

function SetDuration(e) {
  var courseID = e.options[e.selectedIndex].value;
  $.ajax({
    dataType: "json",
    type: "GET",
    url: "GetCourseDuration.php?action=retrive&CourseID="+courseID,
    success: function(result) {
      courseDuration= result;
      $('#maxCourseDuration').val(courseDuration);
    }
  });
}

function objectKeyByValue (obj, val) {
  return Object.entries(obj).find(i => i[1] === val);
}

function populateCourses(e) {

  var $fieldsetParent = $(e).closest( 'fieldset' ); //parent fieldset

  var memberName = e.value;
  var memberID = objectKeyByValue(keyVal,memberName);
  if(memberID == undefined) {
    $(e).next().html('');   //remove error message if exists
    $('<p>Please select from the list</p>').insertAfter($(e)).addClass('errorMessage');
    $fieldsetParent.find('#CourseName').html('');
    //jQuery('#CourseName').html('');
    //document.getElementById("errorMsgMN").innerHTML = "";
    return;
  }
  var $p =$(e).parent().parent().parent().next();   //storing member id
  $p.val(memberID[0]);
  //jQuery('#memberID').val(memberID[0]);
  $(e).next().html('');
  //document.getElementById("errorMsgMN").innerHTML = "";
  var courses=new Array();
  $fieldsetParent.find('#CourseName').html('');
  $.ajax({
    dataType: "json",
    type: "GET",
    url: "GetPaidCourses.php?MemberID="+memberID[0]+"&WalkIn=0",
    success: function(result) {
      var courses= result;
      //var CourseNameNode = document.getElementById('CourseName');
      $fieldsetParent.find('#CourseName').html('');
      $fieldsetParent.find('#CourseName').next().html('');  //removing message
      if(courses.length==0) {
        $('<p>No paid courses available</p>').insertAfter($fieldsetParent.find('#CourseName')).addClass('errorMessage');
      }
      else {
        $fieldsetParent.find('#CourseName').append( "<option disabled=\"disabled\" selected=\"true\" style=\"display: none;\" value=\"default\" >Pick any course</option>" );
        for (var i = 0; i < courses.length; i++) {
          $fieldsetParent.find('#CourseName').append( "<option value="+courses[i].courseID+">"+ courses[i].CourseName +"</option>" );
        }
      }
    }
  });
}

function resetFormValidator(formId) {

  $(formId).removeData('validator');
   $(formId).removeData('unobtrusiveValidation');
   $.validator.unobtrusive.parse(formId);

}

function AddSection2(e){

  var $fieldsetmain = $(e).closest( 'fieldset' );
  var $fieldset =  $(e).closest( 'fieldset' ).clone();
  $( $fieldset ).find( "input" ).val('');   //clears out input field

  $( $fieldset ).find( "select" ).html(''); //clears out select field
  $( $fieldset ).find( "input" ).parent().parent().html('<input type="text" id="MermberName" name="MermberNameArr[]" class="form-control typeahead tt-query" autocomplete="on" spellcheck="false" onblur="populateCourses(this)" >'); //clears out the typeahead

  $fieldsetmain.parent().append($fieldset);
  AddMembers($fieldset);

  // $("#reservationForm").validate(); //sets up the validator
  // $("input[name*=MermberNameArr[]").rules("add", "required");
// var i=0;
// $("#reservationForm").validate();
// $("#sections .classToValidate").each(function () {
//
//     $(this).rules('add',{
//         required: true,
//     });
// });
  // $fieldset.rules( "add", {
  // required: true,
  // messages: {
  //   required: "Required input",
  // }
//});

  //resetFormValidator('reservationForm');

  //var $template = $('#memberFieldset'),
  //  $clone    = $template
  //                  .clone()
  //                  .removeClass('hide')
  //                  .removeAttr('id')
  //                  .insertBefore($template),
 //    $option   = $clone.find('[name="MermberNameArr[]"]');
 //
 // // Add new field
 // $('#reservationForm').formValidation('addField', $option);


}

function RemoveSection2(e) {

  $(e).parent().fadeOut(300, function(){
       //remove parent element (main section)
       $(e).closest( 'fieldset' ).remove();
       return false;
   });
}



</script>

<script type="text/javascript">


</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Reservation
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
        ChromePhp::log("memberID " . isset($_POST['memberIDArr']) );
        ChromePhp::log("CourseNameArr " . isset($_POST['CourseNameArr']) );
        ChromePhp::log("dateArr " . isset($_POST['dateArr']));
        ChromePhp::log("timeArr " . isset($_POST['timeArr']));
        ChromePhp::log("RoomName " . $_POST['RoomName']);
        ChromePhp::log("userName " . $_POST['userName']);
        ChromePhp::log("tillTimeArr " .  isset($_POST['tillTimeArr']));
        $tt = $_POST['tillTimeArr'];
        for ($i=0; $i < count($tt); $i++) {
            ChromePhp::log("till time -  " . $tt[$i]);
        }


        if( isset($_POST['memberIDArr']) && !empty($_POST['memberIDArr']) &&
            isset($_POST['CourseNameArr']) && !empty($_POST['CourseNameArr']) &&
            isset($_POST['dateArr']) && !empty($_POST['dateArr']) &&
            isset($_POST['timeArr']) && !empty($_POST['timeArr']) &&
            isset($_POST['RoomName']) && !empty($_POST['RoomName']) &&
            isset($_POST['tillTimeArr']) && !empty($_POST['tillTimeArr']) &&
            isset($_POST['userName']) && !empty($_POST['userName']) )
          {
            $dateArr = $_POST['dateArr'];
            $timeArr = $_POST['timeArr'];
            $CourseID = $_POST['CourseNameArr'];
            $tillTimeArr = $_POST['tillTimeArr'];
            $RoomID = mysql_real_escape_string(trim($_POST['RoomName']));
            $UserID = mysql_real_escape_string(trim($_POST['userName']));
            $MemberID = $_POST['memberIDArr'];
            $succesCount = 0;
            $failureCount = 0;
            array_shift($dateArr);
            $maxAllocID = GetMaxAllocID();
            $maxAllocIDCopy =  $maxAllocID;
            ChromePhp::log("maxAllocID  " . $maxAllocID);
            $maxAllocID++;
            // for ($i=0; $i < count($dateArr); $i++) {
            //     ChromePhp::log("datestignArr  " . $dateArr[$i]);
            // }
            //
            // for ($i=0; $i < count($timeArr); $i++) {
            //     ChromePhp::log("timeArr  " . $timeArr[$i]);
            // }

            //Fil room allocations table
            for ($i=0; $i < count($dateArr); $i++) {
              // if($dateArr[$i] == '0' || $timeArr[$i] == '')
              //   continue;
              $s = $dateArr[$i]. ' ' . $timeArr[$i];
              $t = $dateArr[$i]. ' ' . $tillTimeArr[$i];
              ChromePhp::log("datestign  " . $s);
              ChromePhp::log("datestign  " . $t);
              $dateTime1 =  DateTime::createFromFormat('m/d/Y H:i:s', $s);
              $dateTime2 =  DateTime::createFromFormat('m/d/Y H:i:s', $t);
              $date1 = $dateTime1->format('Y-m-d H:i:s');
              $date2 = $dateTime2->format('Y-m-d H:i:s');
              ChromePhp::log($dateTime1);
              ChromePhp::log("date in format   " . $date1);
              if($allocateRoom = AllocateRoom($maxAllocID,$RoomID,$date1,$date2,$UserID) ) {
                $succesCount++;
                $maxAllocID++;
              }
              else {
                $failureCount++;
              }
            }

            $succesCount1 = 0;
            $failureCount1 = 0;
            $maxAllocIDCopy++;
            if($succesCount == count($dateArr)) {
              for ($i=0; $i < count($MemberID); $i++) {
                ChromePhp::log("member id values");

                  ChromePhp::log($maxAllocID);
                  ChromePhp::log($maxAllocIDCopy);
                  for ($j=$maxAllocIDCopy; $j <$maxAllocID; $j++) {
                    ChromePhp::log("listing values");
                    ChromePhp::log($MemberID[$i]);
                    ChromePhp::log($CourseID);
                    ChromePhp::log($j);
                    if($AddReservation = AddReservation($MemberID[$i],$CourseID[$i],$j) ) {
                      $succesCount1++;
                    }
                    else {
                      $failureCount1++;
                    }
                }
              }
            }
            else {
              ChromePhp::log("somehting wrong");
            }
            if($succesCount1 > 0 )
            {
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>
                          '.$succesCount1/$succesCount.' member(s) Reservation created for '.$succesCount.' periodic day(s) successfully!
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
        <h3 class="box-title"><?php echo 'Reserve'; ?></h3>

       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="Reservation">
          <form class="form-horizontal"  role="form" name="reservationForm" id="reservationForm" action="reservation.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
            <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" required="true">
            <input type="hidden" value="0" name="maxCourseDuration" id="maxCourseDuration"  >
            <div id= "sections">
              <fieldset id="memberFieldset">
                  <legend>Member<span class="mandatoryLabel">*</span></legend>

                  <div class="form-group">
                    <label for="MermberName" class="control-label col-sm-3 lables" >Member Name<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" id="MermberName" name="MermberNameArr[]" class="form-control typeahead tt-query" autocomplete="on" spellcheck="false" onblur="populateCourses(this)" >
                    </div>
                    <!-- <div id="errorMsgMN" name="errorMsgMN" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div> -->
                  </div>
                  <input type="hidden" value="0" name="memberIDArr[]" id="memberID" >

                  <div class="form-group">
                    <label for="CourseName" class="control-label col-sm-3 lables">Course<span class="mandatoryLabel">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control" id="CourseName" name="CourseNameArr[]" onchange="SetDuration(this)" ></select>
                    </div>
                    <!-- <input type="hidden" value="0" name="courseduration[]" > -->
                    <!-- <div id="errorMsgCN" name="errorMsgCN" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div> -->
                  </div>

                  <div class="form-group">
                    <label for="eventName" class="control-label col-sm-3 lables"></label>
                    <div class="col-sm-4">
                      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#DefineNewCourse">click to Define new course</button>
                    </div>
                    <!-- <div id="errorMsgEN" name="errorMsgEN" style="font:10px Tahoma,sans-serif;margin-left:15px;display:inline;color:red;" role="error"></div> -->
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>
                    <div class="col-sm-4 col-sm-offset-3">
                      <button type="button" name="Addmember" id="AddMember1" class="btn btn-sm btn-primary add_member" style = "margin-left:-30px" onclick="AddSection2(this)">Add</button>
                      <button type="button" name="Removemember" id="RemoveStatus1" class="btn btn-sm btn-danger" style="margin-left:5px"  onclick="RemoveSection2(this)">Remove</button>
                    </div>
                  </div>

                </fieldset>
              </div>

              <div class="form-group">
                <label for="RoomName" class="control-label col-sm-3 lables">Room<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <select class="form-control" id="RoomName" name="RoomName">
                    <option selected="true" disabled="disabled" style="display: none;" value="default">Pick a Room!</option>
                    <?php
                    $roomsList = getLocations();
                      if(mysql_num_rows($roomsList)!=0)
                      {
                        ChromePhp::log("yes got some roons data");

                        while($room = mysql_fetch_assoc($roomsList))
                        {
                            echo '<option value="' . $room['RoomID'] . '">' . $room['RoomName'] . '</option>';
                        }
                      }
                    ?>
                </select>
                </div>
                <div id="errorMsgRN" name="errorMsgRN" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div>
              </div>

              <div class="form-group">
                <label for="userName" class="control-label col-sm-3 lables">Instructor<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <select class="form-control" id="userName" name="userName">
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Pick any instructor!</option>
                      <?php
                      $instructorList = getInstructors();
                        if(mysql_num_rows($instructorList)!=0)
                        {
                          ChromePhp::log("yes got some roons data");

                          while($instructor = mysql_fetch_assoc($instructorList))
                          {
                              echo '<option value="' . $instructor['UserID'] . '">' . $instructor['Name'] . '</option>';
                          }
                        }
                    ?>
                </select>
                </div>
                <div id="errorMsgUN" name="errorMsgUN" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div>
              </div>


              <div class="form-group">
                <label for="ReservationDateFrm" class="control-label col-sm-3 lables" >Reservation Date(s)<span class="mandatoryLabel">*</span></label>
                 <div class='col-sm-4' id="simpliest-usage">
                   <!-- <input type="datetime" class="form-control" name="ReservationDateFrm" id="ReservationDateFrm" value=""  onblur="" /> -->
                 </div>
                <div id="errorMsgRDF" name="errorMsgRDF" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div>
              </div>

              <div id= "timeGrps">
               <div class="form-group" id="TimeGroup" style="display:none">
                 <label for="time" class="control-label col-sm-3 lables">00/00/0000<span class="mandatoryLabel">*</span></label>
                 <input type="hidden" class="dateClass" value="0" name="dateArr[]" id="dateID" >
                 <div class="col-sm-2">
                   <select class="form-control fromTimeclass" id="time" name="timeArr[]" onchange="fillTillTime(this)" >
                     <option selected="true" disabled="disabled" style="display: none" value="default" >select time</option>
                     <?php
                     for ($i=6; $i < 23; $i++) {
                       for ($j=0; $j <60 ; $j+=15) {
                         echo '<option value="'. str_pad($i, 2, '0', STR_PAD_LEFT) .':'. str_pad($j, 2, '0', STR_PAD_LEFT) . ':00">'. str_pad($i, 2, '0', STR_PAD_LEFT) .':'. str_pad($j, 2, '0', STR_PAD_LEFT) . '</option>';
                       }
                     }
                     ?>
                   </select>
                 </div>

                 <div class="col-sm-2">
                   <select class="form-control tillTimeclass" id="tillTime" name="tillTimeArr[]" onchange="checkAvailibility(this)">
                     <option selected="true" disabled="disabled" style="display: none" value="default"  >Till time</option>
                     <?php
                     for ($i=6; $i < 23; $i++) {
                       for ($j=0; $j <60 ; $j+=15) {
                         echo '<option value="'. str_pad($i, 2, '0', STR_PAD_LEFT) .':'. str_pad($j, 2, '0', STR_PAD_LEFT) . ':00">'. str_pad($i, 2, '0', STR_PAD_LEFT) .':'. str_pad($j, 2, '0', STR_PAD_LEFT) . '</option>';
                       }
                     }
                     ?>
                   </select>
                 </div>

                 <!-- <div role="error"></div> -->
               </div>
             </div>
                 <!-- <div class="form-group">
                   <label for="eventName" class="control-label col-sm-3 lables"></label>
                   <div class="col-sm-4">
                     <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#selectTimeModal">click to selct time</button>
                   </div>

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

<div class="modal fade" id="DefineNewCourse" tabindex="-1" role="dialog" aria-labelledby="DefineNewCourse">
       <div class="modal-dialog" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="heading" id="myModalLabel">Add new Course</h4>
           </div>
             <form role="form" class="form-horizontal" id="newcourseForm" name="newcourseForm" method="post" action="reservation.php">
               <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" required="true">

               <div class="modal-body">
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


             </div>
             <div class="modal-footer">
               <!-- <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button> -->
               <div class="form-group">
                 <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

                 <div class="col-sm-9">
                   <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="<?php if(isset($subEventData['EventCode'])) echo 'margin-left:90px'; else echo 'margin-left:50px'; ?>" />
                   <input type="hidden" name="UKey" value="1" id="ID_UKey" />
                   <!-- <input type="hidden" name="eventCode" id="eventCode" value="<?php if(isset($subEventData['EventCode'])) echo $subEventData['EventCode'] ?>" /> -->
                   <button type="reset" class="btn btn-sm btn-default" style="visibility:<?php if(isset($subEventData['EventCode'])) echo 'hidden'; else 'visible'?> ">Clear</button>
                 </div>
               </div>
             </div>
             </form>
         </div>
       </div>
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
