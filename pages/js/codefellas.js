$(document).ready(function() {

  //allowing only upto 2 decimal places
  $('body').on('keyup', '.currency', function () {
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

  $.validator.addMethod("dateonly", function(value, element) {
        var validDate = !/Invalid|NaN/.test(new Date(value.split("-").reverse().join("-")).toString());
        return this.optional(element) || validDate;
    }, "Please enter a valid date in the format DD-MM-YYYY");

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

    //Reservation form
    $("#AddorUpdateProduct").submit(function (e) {
    }).validate({
      rules: {
        InvoiceDate :
        {
          required: true,
          dateonly: true,
        },
        CompanyName :
        {
          required: true,
        },
        InvoiceNumber :
        {
          required: true,
        },
        TinNumber :
        {
          required: true,
        },
        'ProductSize[]':
        {
          required: true,
        },
        'Brand[]':
        {
          required: true,
          valueNotEquals: 'default',
        },
        'Quantity[]':
        {
          required: true,
          digits: true,
        },
        'Rate[]':
        {
          required: true,
          valueNotEquals: 'default',
        },
        'Amount[]':
        {
          required: true,
        },
        'Subtotal[]':
        {
          required: true,
        },
        SubTotalAmount:
        {
          required: true,
        },
        VatAmount:
        {
          required: true,
        },
        TotalAmount:
        {
          required: true,
        }
      },
      // Specify the validation error messages
      messages: {
        InvoiceDate:
        {
          required: "Please select Invoice date",
          datetime: "please enter date in proper format"
        },
        CompanyName:
        {
          required: "Please specify Company name",
        },
        InvoiceNumber:
        {
          required: "Please enter Invoice number",
        },
        TinNumber:
        {
          required: "Please enter Tin number",
        },
        'ProductSize[]':
        {
          required: "Please specify Product size",
        },
        'Brand[]':
        {
          required: "Please specify Brand name",
        },
        'Quantity[]':
        {
          required: "Please specify Product quantity",
          digits: "please enter digits only",
        },
        'Amount[]':
        {
          required: "Please enter Product amount",
        },
        'Subtotal[]':
        {
          required: "Please enter Subtotal amount",
        },
        FinalSubTotalAmount:
        {
          required: "Please enter Subtotal amount",
        },
        VatAmount:
        {
          required: "Please enter Vat amount",
        },
        TotalAmount:
        {
          required: "Please enter Total amount ",
        }
      },
      errorPlacement: function(error, element) {
        if($(element).hasClass( "currency" ))
          error.insertAfter($(element).parent().parent()).addClass('errorMessage');
        else
        error.insertAfter($(element).parent()).addClass('errorMessage');
      },
      submitHandler: function (form) {
        $('input[name="UKey"]').val('2');
        removeMultiInputNamingRules(form, 'input[alt="ProductSize[]"]');
        removeMultiInputNamingRules(form, 'input[alt="Brand[]"]');
        removeMultiInputNamingRules(form, 'input[alt="Quantity[]"]');
        removeMultiInputNamingRules(form, 'input[alt="Rate[]"]');
        removeMultiInputNamingRules(form, 'input[alt="Amount[]"]');
        removeMultiInputNamingRules(form, 'input[alt="Discount[]"]');
        removeMultiInputNamingRules(form, 'input[alt="DiscountRs[]"]');
        removeMultiInputNamingRules(form, 'input[alt="Subtotal[]"]');
        form.submit();
      }
    });
});

// this is static form validation you can refer this to validate any static forms
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
    error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});

$("#NonBillable").submit(function (e) {
}).validate({

  rules: {
    RecDate:
    {
      required: true,
    },
    AmountPaid:
    {
      required: true,
      digits: true,
    }
  },
  // Specify the validation error messages
  messages: {
    RecDate:
    {
      required: "Please select a valid date",
    },
    AmountPaid:
    {
      required: "Please Enter a valid Amount",
      digits: "Numreric values accepted only",
    }
  },
  errorPlacement: function(error, element) {
    if($(element).hasClass( "amount" ))
      error.insertAfter($(element).parent().parent()).addClass('errorMessage');
    else     
      error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});


function addMultiInputNamingRulesSkipFirst(form,alt, field, rules) {
    $(form).find(field).each(function(index){

        $(this).attr('alt', alt);
        $(this).attr('name', index + $(this).attr('name'));
        $(form).validate();
        if(index != 0) {
          $(this).rules('add', rules);
        }
    });
}

function addMultiInputNamingRules(form,alt, field, rules) {
    $(form).find(field).each(function(index){

      $(this).attr('alt', alt);
      $(this).attr('name', index + $(this).attr('name'));
      $(form).validate();
      $(this).rules('add', rules);
    });
}

function removeMultiInputNamingRules(form, field) {
    $(form).find(field).each(function(index){
    $(this).attr('name', $(this).attr('alt'));
    $(this).removeAttr('alt');
  });
}

function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if ( (charCode < 48 || charCode > 57))
     return false;

  return true;
}
