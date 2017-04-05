

$(document).ready(function() {

  jQuery.validator.addMethod("codeCheck", function(value, element) {
   if($('input#codeInput').val() != $('input#myDataId').val()){
     return false;
   } else {
     return true;
   }
  }, 'Please enter a valid Code.');

  $(window).on('shown.bs.modal', function() {
      $('#registerModel').modal('show');
        $('#errorMsgpwd').empty();
        $("#password").val("");
  });

  $('#adminTable').DataTable({
  "paging":   false,
  "info":     false,
  "order": [[ 0, "asc" ]],
  "columnDefs": [{ "orderable": false, "targets": 10 }]
});

  $('#orgTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 3, "asc" ]]
  });

  $('#pointsTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 3, "asc" ]]
  });

  $('#RegisteredEventsAttendee').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 0, "asc" ]],
    "columnDefs": [{ "orderable": false, "targets": [ 1,2,3,5,6,7] }]

  });

 $('#AllEventsAttendee').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 0, "asc" ]],
    "columnDefs": [{ "orderable": false, "targets": [ 1,2,3,5,6,7,8] }]

  });

function AddDataTable_Plugin(){

  $('#ResgisterdUsersTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 3, "asc" ]]
  });
}
AddDataTablePlugin = AddDataTable_Plugin;


  // $( "#registerModel" ).on('shown', function(){
  //     alert("I want this to appear after the modal has opened!");
  // });

  // $("#codeForm").submit(function (e) {
  //  e.preventDefault();
  // }).validate({
  //
  // // Specify the validation rules
  // rules: {
  //     codeInput:
  //      {
  //        required: true,
  //        minlength: 8,
  //        codeCheck: true
  //      }
  // },
  // // Specify the validation error messages
  // messages: {
  //     codeInput:
  //     {
  //        required: "Please Enter a Code",
  //        minlength: "Please enter valid number of characters"
  //     }
  //    // { valueNotEquals: "Please select any of the available section" }
  // },
  // errorPlacement: function(error, element) {
  //    if (element.attr("name") == "codeInput") {
  //        error.appendTo("#errorMsgCode").addClass('');
  //      }
  //      else error.insertAfter(element);
  // },
  // //submit the form
  // submitHandler: function (form) {
  //  $.post('events_Attendee.php', $(form).serialize(), function(data) {
  //    $("#codeModal").modal('toggle');
  //    window.location.reload(true);
  //    });
  //  }
  // });

  // $("#feedForm").submit(function (e) {
  //  e.preventDefault();
  // }).validate({

  // // Specify the validation rules
  // rules: {
  //     Rating:
  //      {
  //        required: true,
  //      },
  //      comment :{
  //        required: true,
  //      }
  // },
  // // Specify the validation error messages
  // messages: {
  //     Rating:
  //     {
  //        required: "Please select a Rating",
  //     },
  //     comment:
  //     {
  //       required: "Please Enter a few Words",
  //     }
  //    // { valueNotEquals: "Please select any of the available section" }
  // },
  // errorPlacement: function(error, element) {
  //    if (element.attr("name") == "Rating") {
  //        error.appendTo("#rm").addClass('error-class');
  //      }
  //    else if(element.attr("name") == "comment")
  //      {
  //          error.appendTo("#em").addClass('error-class');
  //      }
  //      else error.insertAfter(element);
  // },
  // //submit the form
  // submitHandler: function (form) {
  //  $.post('events_Attendee.php', $(form).serialize(), function(data) {
  //    $("#feedbackModal").modal('toggle');
  //    window.location.reload(true);
  //    });
  //  }
  // });

  $("#delForm").submit(function (e) {
   e.preventDefault();
  }).validate({
  submitHandler: function (form) {
   $.post('events_Attendee.php', $(form).serialize(), function(data) {
         window.location.reload(true);
     });
  }

  });

  $("#trackIdForm").submit(function (e) {
   e.preventDefault();
  }).validate({

  // Specify the validation rules
  rules: {
      eventCode:
       {
         required: true,
         minlength: 8,
       }
  },
  // Specify the validation error messages
  messages: {
      eventCode:
      {
         required: "Please Enter a Code",
         minlength: "Please enter valid number of characters"
      }
     // { valueNotEquals: "Please select any of the available section" }
  },
  errorPlacement: function(error, element) {
     if (element.attr("name") == "eventCode") {
         error.appendTo("#errorMsg").addClass('');
       }
       else error.insertAfter(element);
  },
  //submit the form
  submitHandler: function (form) {
   $.post('track_Organizer.php', $(form).serialize(), function(data) {
     window.location.reload(true);
     });
   }
  });




$("#newEventForm").submit(function (e) {
  e.preventDefault();
}).validate({

// Specify the validation rules
rules: {
     eventName:
      {
        required: true,
        minlength: 3
      },
      eventDescription:
      {
        required: true,
        minlength: 10
      },
      TimeFrame:
      {
        required: true,
        digits: true
      },
      AttendanceLimit:
      {
        required: true,
        digits: true
      }
},
// Specify the validation error messages
messages: {
     eventName:
     {
       required: "Please Enter the Event Name",
       minlength: "Event Name must be atleast 3 characters long"
     },
     eventDescription:
     {
       required: "Please Enter the Event Description",
       minlength: "Event Description be atleast 10 characters long"
     },
     TimeFrame:
     {
      required: "Please enter Valid Time Frame for attendance Count",
      digits: "Numreric values accepted only"
     },
     AttendanceLimit:
     {
      required: "Please enter Valid attendance Limit",
      digits: "Numreric values accepted only"
     }
    //{ valueNotEquals: "Please select any of the available section" }
},
errorPlacement: function(error, element) {
    if (element.attr("name") == "eventName") {
        error.appendTo("#errorMsgEN").addClass('error-class');
      }
    else if(element.attr("name") == "eventDescription")
      {
          error.appendTo("#errorMsgED").addClass('error-class');
      }
    else if(element.attr("name") == "TimeFrame")
      {
          error.appendTo("#errorMsgTF").addClass('error-class');
      }
    else if(element.attr("name") == "AttendanceLimit")
      {
          error.appendTo("#errorMsgAL").addClass('error-class');
      }
    else error.insertAfter(element);
},
//submit the form
submitHandler: function (form) {
  $('input[name="UKey"]').val('2');
  form.submit();

}

});



$("#newMainEventEventForm").submit(function (e) {
  e.preventDefault();
}).validate({

// Specify the validation rules
rules: {
     eventName:
      {
        required: true,
        minlength: 3
      },
      eventDescription:
      {
        required: true,
        minlength: 10
      }
},
// Specify the validation error messages
messages: {
     eventName:
     {
       required: "Please Enter the Event Name",
       minlength: "Event Name must be atleast 3 characters long"
     },
     eventDescription:
     {
       required: "Please Enter the Event Description",
       minlength: "Event Description be atleast 10 characters long"
     }
    //{ valueNotEquals: "Please select any of the available section" }
},
errorPlacement: function(error, element) {
    if (element.attr("name") == "eventName") {
        error.appendTo("#errorMsgEN").addClass('error-class');
      }
    else if(element.attr("name") == "eventDescription")
      {
          error.appendTo("#errorMsgED").addClass('error-class');
      }
    else error.insertAfter(element);
},
//submit the form
submitHandler: function (form) {
   $('input[name="UKey"]').val('2');
  form.submit();

}

});

    $.validator.addMethod("dateTime", function (value, element) {
        var stamp = value.split(" ");
        var validDate = !/Invalid|NaN/.test(new Date(stamp[0]).toString());
        var validTime = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(stamp[1]);
        return this.optional(element) || (validDate && validTime);
    }, "Please enter a valid date and time.");


$("#ScheduleEvent").submit(function (e) {
  e.preventDefault();
}).validate({
ignore: [],
// Specify the validation rules
rules: {
     EventDateTime:
      {
        required: true,
        dateTime: true
      },
      // presenterCount:
      // {
      //   required: true,
      //   range: [1, 3]
      // },
      LocationAvalibility:
      {
        required: true
      }
},
// Specify the validation error messages
messages: {
     EventDateTime:
     {
       required: "Please selecet valid date time ",
       USDate: "Invalid date time format"
     },
     // presenterCount:
     // {
     //   required: "Atleast One presenter required",
     //   range: "Atleast One presenter required"
     // },
     LocationAvalibility:
     {
      required: ""
     }
    //{ valueNotEquals: "Please select any of the available section" }
},
errorPlacement: function(error, element) {
    if (element.attr("name") == "EventDateTime") {
        error.appendTo("#errorMsgEDT").addClass('error-class');
      }
    // else if(element.attr("name") == "EventDuration")
    //   {
    //       error.appendTo("#errorMsgEDR").addClass('error-class');
    //   }
    else if(element.attr("name") == "presenterCount")
      {
          error.appendTo("#errorMsgEP").addClass('error-class');
      }
    else error.insertAfter(element);
},
//submit the form
submitHandler: function (form) {
   $('input[name="UKey"]').val('2');
  form.submit();

}

});






$("#updateScoreForm").submit(function (e) {
  e.preventDefault();
}).validate({

// Specify the validation rules
rules: {
     PresenterPoints:
      {
        required: true,
        digits: true
      },
      AttendeePoints:
      {
        required: true,
        digits: true
      },
      OrganizerPoints:
      {
        required: true,
        digits: true
      }
},
// Specify the validation error messages
messages: {
     PresenterPoints:
     {
       required: "Please enter Valid Score",
       digits: "Numreric values accepted only",
       maxlength:1
     },
     AttendeePoints:
     {
       required: "Please enter Valid Score",
       digits: "Numreric values accepted only"

     },
     OrganizerPoints:
     {
      required: "Please enter Valid Score",
      digits: "Numreric values accepted only"
     }
    //{ valueNotEquals: "Please select any of the available section" }
},
errorPlacement: function(error, element) {
    if (element.attr("name") == "PresenterPoints") {
        error.appendTo("#errorMsgPP").addClass('error-class');
      }
    else if(element.attr("name") == "AttendeePoints")
      {
          error.appendTo("#errorMsgAP").addClass('error-class');
      }
    else if(element.attr("name") == "OrganizerPoints")
      {
          error.appendTo("#errorMsgOP").addClass('error-class');
      }
    else error.insertAfter(element);
},
//submit the form
submitHandler: function (form) {
  $('input[name="UKey"]').val('2');
  form.submit();
}

});


$("#TimeFrame").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
});

$("#AttendanceLimit").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


    $("#EventDuration").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                 // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });


});

$(function(){
    $('.rating-select .btn').on('mouseover', function(){
        $(this).removeClass('btn-default').addClass('btn-warning');
        $(this).prevAll().removeClass('btn-default').addClass('btn-warning');
        $(this).nextAll().removeClass('btn-warning').addClass('btn-default');
    });

    $('.rating-select').on('mouseleave', function(){
        active = $(this).parent().find('.selected');
        if(active.length) {
            active.removeClass('btn-default').addClass('btn-warning');
            active.prevAll().removeClass('btn-default').addClass('btn-warning');
            active.nextAll().removeClass('btn-warning').addClass('btn-default');
        } else {
            $(this).find('.btn').removeClass('btn-warning').addClass('btn-default');
        }
    });

    $('.rating-select .btn').click(function(){
        if($(this).hasClass('selected')) {
            $('.rating-select .selected').removeClass('selected');
        } else {
            $('.rating-select .selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
});


$(function () {

   $(".register").on('click', function () {
     $('input#myDataId').val($(this).closest("tr").data('id'));
   });

   $(".fdButton").on('click', function () {
    $('#valueField').text($(this).closest("tr").data('id'));
    $("textarea#feedbackData").val("");
    $("#star3").prop("checked", true);
  });

   $(".MfdButton").on('click', function () {
    $("textarea#MfeedbackData").val("");
    $("#Mstar3").prop("checked", true);
  });


  $(".fdButtonSubmit").on('click', function () {
    $('input#myEventId').val($('#valueField').text());
  });

  $('*[name=RecDate]').appendDtpicker();
  $('*[name=PaymentDateTime]').appendDtpicker();
  $('*[name=RegDate]').appendDtpicker();
  $('*[name=InvoiceDate]').appendDtpicker();
  $('*[name=StDate]').appendDtpicker();
  $('*[name=ReservationDateFrm]').appendDtpicker();
  $('*[name=ReservationDateTo]').appendDtpicker();

  $(document).on("click", ".GiveFeedback", function () {
       var eventID = $(this).data('id');
       $(".modal-body #currEventIDForFeedback").val( eventID );
  });

  $(document).on("click", ".GiveFeedbackM", function () {
       var eventID = $(this).data('id');
       $(".modal-body #EventIDForFeedback").val( eventID );
  });


 });

//  (function ( $ ) {
//
//     // put all that "wl_alert" code here
//     $('#PresnterID').multiselect({
//       enableFiltering:true
//       });
//
// }( jQuery ));
