$(document).ready(function() {

  //allowing only upto 2 decimal places
  $('body').on('keyup', '.maskedExt', function () {
       var num = $(this).attr("maskedFormat").toString().split(',');
       var regex = new RegExp("^\\d{0," + num[0] + "}(\\.\\d{0," + num[1] + "})?$");
       if (!regex.test(this.value)) {
           this.value = this.value.substring(0, this.value.length - 1);
       }
   });

  $.validator.addMethod("dateTime", function (value, element) {
      var stamp = value.split(" ");
      var validDate = !/Invalid|NaN/.test(new Date(stamp[0]).toString());
      var validTime = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(stamp[1]);
      return this.optional(element) || (validDate && validTime);
  }, "Please enter a valid date and time.");

  jQuery.validator.addMethod("validDate", function(value, element) {
    var stamp = value.split(" ");
    var validDate = !/Invalid|NaN/.test(new Date(stamp[0]).toString());
    return this.optional(element) || (validDate)
  }, "Please enter a valid date in the format DD-MM-YYYY");

  jQuery.validator.addMethod("needsSelection", function (value, element) {
         var count = $(element).find('option:selected').length;
         return count > 0;
  });

  jQuery.validator.addMethod("valueNotEquals", function(value, element, arg){
   return arg != value;
  }, "Value must not equal arg.");

    // Login Check
    $("#loginMain").submit(function (e) {
    }).validate({
      errorElement: 'p',
      rules: {
        email:
        {
          required: true,
        },
        passwd: {
          required: true,
        }
      },
      // Specify the validation error messages
      messages: {
        email:
        {
          required: "Please Enter a valid Email ID",
        },
        passwd:
        {
          required: "Please Enter a valid Password",
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "email") {
          error.appendTo("#errorBox").addClass('alert alert-danger');
          $("#phpError").remove();
        }
        else if(element.attr("name") == "passwd")
        {
          error.appendTo("#errorBox").addClass('alert alert-danger');
          $("#phpError").remove();
        }
        else error.insertAfter(element);
      },
    });

    //Reset passeword check

    $("#resetPasswd").submit(function (e) {
    }).validate({
      errorElement: 'p',
      rules: {
        emailrp:
        {
          required: true,
        }
      },
      // Specify the validation error messages
      messages: {
        emailrp:
        {
          required: "Please Enter a valid User ID",
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "emailrp") {
          error.appendTo("#errorBoxRP").addClass('alert alert-danger');
          $("#phpError").remove();
        }
        else error.insertAfter(element);
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        form.submit();
      }
  });

    //Reset Password
    $("#resetPassword").submit(function (e) {
    }).validate({
      errorElement: 'p',
      rules: {
        NewPwd:
        {
          required: true,
          minlength: 6,
        },
        RepeatPwd: {
          required: true,
          minlength: 6,
          equalTo: "#NewPwd",
        }
      },
      // Specify the validation error messages
      messages: {
        NewPwd:
        {
          required: "Invalid Password",
          minlength: "should be 6 or longer",
        },
        RepeatPwd:
        {
          required: "Both Password should match",
          minlength: "should be 6 or longer",
          equalTo: "should be the same as password"
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "NewPwd") {
          error.appendTo("#errorBox").addClass('alert alert-danger');
          $("#phpError").remove();
        }
        else if(element.attr("name") == "RepeatPwd")
        {
          error.appendTo("#errorBox").addClass('alert alert-danger');
          $("#phpError").remove();
        }
        else error.insertAfter(element);
      },
    });

    // courses
    $("#CoursesForm").submit(function (e) {
    }).validate({

      rules: {
        CourseName:
        {
          required: true,
        },
        CourseDurationDays:
        {
          required: true,
          digits: true,
        },
        CourseDurationMins:
        {
          required: true,
          digits: true,
        },
        PricePerInstruction:
        {
          required: true,
          number: true,
        },
        DurationPerInstruction:
        {
          required: true,
          number: true,
        }
      },
      // Specify the validation error messages
      messages: {
        CourseName:
        {
          required: "Please Enter a valid course name",
        },
        CourseDurationDays:
        {
          required: "Please Enter a valid Duration in days",
          digits: "Numreric values accepted only",
        },
        CourseDurationMins:
        {
          required: "Please Enter a valid duration mins",
          digits: "Numreric values accepted only",
        },
        PricePerInstruction:
        {
          required: "Please Enter a valid Price",
          digits: "Numreric values accepted only",
        },
        DurationPerInstruction:
        {
          required: "Please Enter a valid Duration",
          digits: "Numreric values accepted only",
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "CourseName") {
            error.appendTo("#errorMsgCN").addClass('error-class');

        } else if (element.attr("name") == "CourseDurationDays") {
            error.appendTo("#errorMsgCDD").addClass('error-class');

        } else if (element.attr("name") == "CourseDurationMins") {
            error.appendTo("#errorMsgCDM").addClass('error-class');

        } else if (element.attr("name") == "PricePerInstruction") {
            error.appendTo("#errorMsgPPI").addClass('error-class');

        } else if (element.attr("name") == "DurationPerInstruction") {
            error.appendTo("#errorMsgDPI").addClass('error-class');

        }
        else error.insertAfter(element);
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        form.submit();
      }
    });

    // Walk in
    $("#PaymentForm").submit(function (e) {
    }).validate({

      rules: {
        MermberName:
        {
          required: true,
        },
        PaymentDateTime:
        {
          required: true,
          dateTime: true
        },
        CourseName:
        {
          required: true,
          valueNotEquals: 'default',
        },
        PurchasedDuration:
        {
          required: true,
          digits: true,
        },
        AmtPaid:
        {
          required: true,
          number: true,
        }
      },
      // Specify the validation error messages
      messages: {
        MermberName:
        {
          required: "Please Select or enter any Existing Member",
        },
        PaymentDateTime:
        {
          required: "Please Select or enter a valid Date and Time",
        },
        CourseName:
        {
          required: "Please select any course ",
          valueNotEquals: "Please select any course!",
        },
        PurchasedDuration:
        {
          required: "Please Enter a valid duration in mins",
          digits: "Numreric values accepted only",
        },
        AmtPaid:
        {
          required: "Please Enter a valid Amount",
          number: "Numreric values accepted only",
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "MermberName") {
            error.appendTo("#errorMsgMN").addClass('error-class');

        } else if (element.attr("name") == "PaymentDateTime") {
            error.appendTo("#errorMsgPDT").addClass('error-class');

        } else if (element.attr("name") == "CourseName") {
            error.appendTo("#errorMsgCN").addClass('error-class');

        } else if (element.attr("name") == "PurchasedDuration") {
            error.appendTo("#errorMsgPD").addClass('error-class');

        } else if (element.attr("name") == "AmtPaid") {
            error.appendTo("#errorMsgAPD").addClass('error-class');

        } else error.insertAfter(element);
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        form.submit();
      }
    });

    //Reservation form
    $("#reservationForm").submit(function (e) {
    }).validate({

      rules: {
        'MermberNameArr[]':
        {
          required: true,
        },
        'CourseNameArr[]':
        {
          required: true,
          valueNotEquals: 'default',
        },
        'dateArr[]':
        {
          required: true,
        },
        'timeArr[]':
        {
          required: true,
          valueNotEquals: 'default',
        },
        ReservationDateFrm:
        {
          required: true,
          dateTime: true,
        },
        RoomName:
        {
          required: true,
          valueNotEquals: 'default',
        },
        userName:
        {
          required: true,
          valueNotEquals: 'default',
        }
      },
      // Specify the validation error messages
      messages: {
        'MermberNameArr[]':
        {
          required: "Please select exsiting memnber",
        },
        'CourseNameArr[]':
        {
          required: "Please select any course ",
          valueNotEquals: "Please select any course!",
        },
        'dateArr[]':
        {
          required: true,
        },
        'timeArr[]':
        {
          required: true,
          valueNotEquals: "Please select time!",
        },
        ReservationDateFrm:
        {
          required: "Please Select or enter a valid Date and Time",
          dateTime: "Please enter valid date/time format",
        },
        RoomName:
        {
          required: "Please select any room ",
          valueNotEquals: "Please select any room!",
        },
        userName:
        {
          required: "Please select any instructor ",
          valueNotEquals: "Please select an instructor!",
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "MermberNameArr[]") {
            error.insertAfter(element.parent().parent()).addClass('errorMessage');

        //}  else if (element.attr("name") == "CourseNameArr[]") {
        //     error.appendTo("#errorMsgCN").addClass('error-class');
        //
        // } else if (element.attr("name") == "ReservationDateFrm") {
        //     error.appendTo("#errorMsgRDF").addClass('error-class');
        //
        // } else if (element.attr("name") == "RoomName") {
        //     error.appendTo("#errorMsgRN").addClass('error-class');
        //
        // } else if (element.attr("name") == "userName") {
        //     error.appendTo("#errorMsgUN").addClass('error-class');
        //
      } else if (element.attr("name") == "CourseNameArr[]") {
          error.insertAfter($(element).parent()).addClass('errorMessage');
      } else{
           error.insertAfter($(element).parent()).addClass('errorMessage');
           //$(element).parent().val( error.addClass('errorMessage') );
         }
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        form.submit();
      }
    });

    // users
    $("#Userform").submit(function (e) {
    }).validate({

      rules: {
        UserName:
        {
          required: true,
          minlength:3
        },
        UserEmail:
        {
          required: true,
          email: true,
        },
        UserPhone:
        {
          required: true,
          digits: true,
          minlength:10,
          maxlength:10,
        },
        UserBday:
        {
          required: true,
          validDate: true,
        },
        UserAddr:
        {
          required: true,
          minlength: 5,
        },
        UserRole:
        {
          needsSelection: true,
        }
      },
      messages: {
        UserName:
        {
          required: "Please enter valid name",
          minlength: "Minimum length should be 3",
        },
        UserEmail:
        {
          required: "Please Enter a valid amount",
          email: "Invalid email address",
        },
        UserPhone:
        {
          required: "Please enter a valid phone number",
          digits: "Only digits allowed",
          minlength: "Enter 10 digit mobile number",
          maxlength: "Enter 10 digit mobile number",
        },
        UserBday:
        {
          required: "Please enter a valid birthday",
          validDate: "Please enter a valid date in DD-MM-YYYY format",
        },
        UserAddr:
        {
          required: "Please enter a valid address",
          minlength: "Please enter minimum 5 characters",
        },
        UserRole:
        {
          needsSelection: "Please select atleast one role",
        }
      },
      errorPlacement: function(error, element) {
        error.insertAfter($(element).parent()).addClass('errorMessage');
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        form.submit();
      }
    });

    $("#walkInForm").submit(function (e) {
    }).validate({

      rules: {
        RegDate:
        {
          required: true,
          dateTime: true,
        },
        MemberName:
        {
          required: true,
          minlength:5,
        },
        MemberID:
        {
          digits: true,
          minlength: 11,
        },
        MemberEmail:
        {
          email: true,
        },
        MemberPhone:
        {
          required: true,
          digits: true,
          minlength:10,
          maxlength:10,
        },
        MemberAvail:
        {
          required: true,
          maxlength:255,
        },
        MemberBDay:
        {
          validDate: true,
        },
        MemAddress:
        {
          maxlength:255,
        },
        StDate:
        {
          required:true,
          dateTime: true,
        },
        StStaff:
        {
          required:true,
        },
        StCurStat:
        {
          required:true,
        },
        StNote:
        {
          maxlength:255,
        }
      },
      messages: {
        RegDate:
        {
          required: "Please select or enter a valid Date",
          validDate: "Please enter a valid date time in YYYY-MM-DD hh:mm format",
        },
        MemberName:
        {
          required: "Please enter valid name",
          minlength: "Minimum length should be 5",
        },
        MemberID:
        {
          minlength: "Minimum length should be 11",
          digits: "Numreric values accepted only",
        },
        MemberEmail:
        {
          email: "Invalid email address",
        },
        MemberPhone:
        {
          required: "Please enter a valid phone number",
          digits: "Only digits allowed",
          minlength: "Enter 10 digit mobile number",
          maxlength: "Enter 10 digit mobile number",
        },
        MemberAvail:
        {
          required: "Please enter something",
          maxlength: "Maximum 255 characters are allowed",
        },
        MemberBDay:
        {
          validDate: "Please enter a valid date in DD-MM-YYYY format",
        },
        MemAddress:
        {
          maxlength: "Maximum 255 characters are allowed",
        },
        StDate:
        {
          required: "Please select or enter a valid Date",
          dateTime: "Please enter a valid date time in DD-MM-YYYY hh:mm format",
        },
        StStaff:
        {
          required: "Please select any staff member",
        },
        StCurStat:
        {
          required: "Please select current status",
        },
        StNote:
        {
          maxlength:"Maximum 255 characters are allowed",
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "RegDate") {
            error.appendTo("#errorMsgRDT").addClass('error-class');

        } else if (element.attr("name") == "MemberName") {
            error.appendTo("#errorMsgMN").addClass('error-class');

        } else if (element.attr("name") == "MemberID") {
            error.appendTo("#errorMsgMID").addClass('error-class');

        } else if (element.attr("name") == "MemberEmail") {
            error.appendTo("#errorMsgMEM").addClass('error-class');

        } else if (element.attr("name") == "MemberPhone") {
            error.appendTo("#errorMsgMEP").addClass('error-class');

        } else if (element.attr("name") == "MemberAvail") {
            error.appendTo("#errorMsgMAV").addClass('error-class');

        } else if (element.attr("name") == "MemberBDay") {
            error.appendTo("#errorMsgMBD").addClass('error-class');

        } else if (element.attr("name") == "MemAddress") {
            error.appendTo("#errorMsgMNT").addClass('error-class');

        } else if (element.attr("name") == "StDate") {
            error.appendTo("#errorMsgSTD").addClass('error-class');

        } else if (element.attr("name") == "StStaff") {
            error.appendTo("#errorMsgSTF").addClass('error-class');

        } else if (element.attr("name") == "StCurStat") {
            error.appendTo("#errorMsgCST").addClass('error-class');

        } else error.insertAfter(element);
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        form.submit();
      }
    });


    $("#MemRegForm").submit(function (e) {
    }).validate({

      rules: {
        RegDate:
        {
          required: true,
          dateTime: true,
        },
        MemberName:
        {
          required: true,
          minlength:5,
        },
        MemberID:
        {
          required: true,
          digits: true,
          minlength: 11,
        },
        MemberEmail:
        {
          required: true,
          email: true,
        },
        MemberPhone:
        {
          required: true,
          digits: true,
          minlength:10,
          maxlength:10,
        },
        MemberAvail:
        {
          required: true,
          maxlength:255,
        },
        MemberBDay:
        {
          required: true,
          validDate: true,
        },
        MemCity:
        {
          required: true,
        },
        MemAddress:
        {
          required: true,
          maxlength:255,
        },
        MemProf:
        {
          required: true,
          minlength: 3,
        },
        MemRef:
        {
          required: true,
        },
        StDate:
        {
          required:true,
          dateTime: true,
        },
        StStaff:
        {
          required:true,
        },
        StCurStat:
        {
          required:true,
        },
        StNote:
        {
          maxlength:255,
        }
      },
      // Specify the validation error messages
      messages: {
        RegDate:
        {
          required: "Please select or enter a valid Date",
          validDate: "Please enter a valid date time in DD-MM-YYYY hh:mm format",
        },
        MemberName:
        {
          required: "Please enter valid name",
          minlength: "Minimum length should be 5",
        },
        MemberID:
        {
          required: "Please enter valid ID",
          minlength: "Minimum length should be 11",
          digits: "Numreric values accepted only",
        },
        MemberEmail:
        {
          required: "Please enter valid email",
          email: "Invalid email address",
        },
        MemberPhone:
        {
          required: "Please enter a valid phone number",
          digits: "Only digits allowed",
          minlength: "Enter 10 digit mobile number",
          maxlength: "Enter 10 digit mobile number",
        },
        MemberAvail:
        {
          required: "Please enter something",
          maxlength: "Maximum 255 characters are allowed",
        },
        MemberBDay:
        {
          required: "Please enter birthday",
          validDate: "Please enter a valid date in DD-MM-YYYY format",
        },
        MemCity:
        {
          required: "Please select city",
        },
        MemAddress:
        {
          required: "Please enter valid address",
          maxlength: "Maximum 255 characters are allowed",
        },
        MemProf:
        {
          required: "Please enter valid address",
          minlength: "Minimum 3 characters required",
        },
        MemRef:
        {
          required: "Please select any reference",
        },
        StDate:
        {
          required: "Please select or enter a valid Date",
          dateTime: "Please enter a valid date time in YYYY-MM-DD hh:mm format",
        },
        StStaff:
        {
          required: "Please select any staff member",
        },
        StCurStat:
        {
          required: "Please select current status",
        },
        StNote:
        {
          maxlength:"Maximum 255 characters are allowed",
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("name") == "RegDate") {
            error.appendTo("#errorMsgRDT").addClass('error-class');

        } else if (element.attr("name") == "MemberName") {
            error.appendTo("#errorMsgMN").addClass('error-class');

        } else if (element.attr("name") == "MemberID") {
            error.appendTo("#errorMsgMID").addClass('error-class');

        } else if (element.attr("name") == "MemberEmail") {
            error.appendTo("#errorMsgMEM").addClass('error-class');

        } else if (element.attr("name") == "MemberPhone") {
            error.appendTo("#errorMsgMEP").addClass('error-class');

        } else if (element.attr("name") == "MemberAvail") {
            error.appendTo("#errorMsgMAV").addClass('error-class');

        } else if (element.attr("name") == "MemberBDay") {
            error.appendTo("#errorMsgMBD").addClass('error-class');

        } else if (element.attr("name") == "MemCity") {
            error.appendTo("#errorMsgCT").addClass('error-class');

        } else if (element.attr("name") == "MemAddress") {
            error.appendTo("#errorMsgMNT").addClass('error-class');

        } else if (element.attr("name") == "MemProf") {
            error.appendTo("#errorMsgMPR").addClass('error-class');

        } else if (element.attr("name") == "MemRef") {
            error.appendTo("#errorMsgREF").addClass('error-class');

        } else if (element.attr("name") == "StDate") {
            error.appendTo("#errorMsgSTD").addClass('error-class');

        } else if (element.attr("name") == "StStaff") {
            error.appendTo("#errorMsgSTF").addClass('error-class');

        } else if (element.attr("name") == "StCurStat") {
            error.appendTo("#errorMsgCST").addClass('error-class');

        } else error.insertAfter(element);
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        form.submit();
      }
    });


});
