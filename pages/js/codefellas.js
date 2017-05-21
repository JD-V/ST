$(document).ready(function () {

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

  $.validator.addMethod("dateonly", function (value, element) {
    var validDate = !/Invalid|NaN/.test(new Date(value.split("-").reverse().join("-")).toString());
    return this.optional(element) || validDate;
  }, "Please enter a valid date in the format DD-MM-YYYY");

  jQuery.validator.addMethod("validDate", function (value, element) {
    var stamp = value.split(" ");
    var validDate = !/Invalid|NaN/.test(new Date(stamp[0]).toString());
    return this.optional(element) || (validDate)
  }, "Please enter a valid date in the format DD-MM-YYYY");

  jQuery.validator.addMethod("needsSelection", function (value, element) {
    var count = $(element).find('option:selected').length;
    return count > 0;
  });

  jQuery.validator.addMethod("valueNotEquals", function (value, element, arg) {
    return arg != value;
  }, "Value must not equal arg.");

  // Login Check
  $("#loginMain").submit(function (e) {}).validate({
    errorElement: 'p',
    rules: {
      userName: {
        required: true,
      },
      passwd: {
        required: true,
      }
    },
    // Specify the validation error messages
    messages: {
      userName: {
        required: "Please Enter a valid user name",
      },
      passwd: {
        required: "Please Enter a valid Password",
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") == "userName") {
        error.appendTo("#errorBox").addClass('alert alert-danger');
        $("#phpError").remove();
      } else if (element.attr("name") == "passwd") {
        error.appendTo("#errorBox").addClass('alert alert-danger');
        $("#phpError").remove();
      } else error.insertAfter(element);
    },
  });

  //Reset passeword check

  $("#resetPasswd").submit(function (e) {}).validate({
    errorElement: 'p',
    rules: {
      emailrp: {
        required: true,
      }
    },
    // Specify the validation error messages
    messages: {
      emailrp: {
        required: "Please Enter a valid User ID",
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") == "emailrp") {
        error.appendTo("#errorBoxRP").addClass('alert alert-danger');
        $("#phpError").remove();
      } else error.insertAfter(element);
    },
    submitHandler: function (form) {
      $('input[name="UKey"]').val('2');
      form.submit();
    }
  });

  //Reset Password
  $("#resetPassword").submit(function (e) {}).validate({
    errorElement: 'p',
    rules: {
      NewPwd: {
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
      NewPwd: {
        required: "Invalid Password",
        minlength: "should be 6 or longer",
      },
      RepeatPwd: {
        required: "Both Password should match",
        minlength: "should be 6 or longer",
        equalTo: "should be the same as password"
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") == "NewPwd") {
        error.appendTo("#errorBox").addClass('alert alert-danger');
        $("#phpError").remove();
      } else if (element.attr("name") == "RepeatPwd") {
        error.appendTo("#errorBox").addClass('alert alert-danger');
        $("#phpError").remove();
      } else error.insertAfter(element);
    },
  });

  //Reservation form
  $("#AddorUpdateInvoice").submit(function (e) {}).validate({
    rules: {
      InvoiceDate: {
        required: true,
        dateonly: true,
      },
      CompanyName: {
        required: true,
      },
      InvoiceNumber: {
        required: true,
      },
      TinNumber: {
        required: true,
      },
      'ProductSize[]': {
        required: true,
      },
      'Brand[]': {
        required: true,
        valueNotEquals: 'default',
      },
      'Quantity[]': {
        required: true,
        digits: true,
      },
      'Rate[]': {
        required: true,
        valueNotEquals: 'default',
      },
      'Amount[]': {
        required: true,
      },
      'Subtotal[]': {
        required: true,
      },
      SubTotalAmount: {
        required: true,
      },
      VatAmount: {
        required: true,
      },
      TotalAmount: {
        required: true,
      }
    },
    // Specify the validation error messages
    messages: {
      InvoiceDate: {
        required: "Please select Invoice date",
        datetime: "please enter date in proper format"
      },
      CompanyName: {
        required: "Please specify Company name",
      },
      InvoiceNumber: {
        required: "Please enter Invoice number",
      },
      TinNumber: {
        required: "Please enter Tin number",
      },
      'ProductSize[]': {
        required: "Please specify Product size",
      },
      'Brand[]': {
        required: "Please specify Brand name",
      },
      'Quantity[]': {
        required: "Please specify Product quantity",
        digits: "please enter digits only",
      },
      'Amount[]': {
        required: "Please enter Product amount",
      },
      'Subtotal[]': {
        required: "Please enter Subtotal amount",
      },
      FinalSubTotalAmount: {
        required: "Please enter Subtotal amount",
      },
      VatAmount: {
        required: "Please enter Vat amount",
      },
      TotalAmount: {
        required: "Please enter Total amount ",
      }
    },
    errorPlacement: function (error, element) {
      if ($(element).hasClass("currency"))
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

// Add product Form
$("#AddorUpdateProduct").submit(function (e) {}).validate({

  rules: {
    SupplierID: {
      required: true,
    },    
    BrandID: {
      required: true,
    },
    ProductSize: {
      required: true,
    },
    ProductPattern: {
      required: true,
    },
    ProductTypeID: {
      required: true,
    },
    CostPrice: {
      required: true,
      number: true,
    },
    MinSellingPrice: {
      required: true,
      number: true,
    },
    MaxSellingPrice: {
      required: true,
      number: true,
    }    
  },
  // Specify the validation error messages
  messages: {
    SupplierID: {
      required: "Please select supplier ",
    },
    BrandID: {
      required: "Please select brand ",
    },
    ProductSize: {
      required: "Please provide product size",
    },
    ProductPattern: {
      required: "Please Provide product pattern",
    },    
    ProductTypeID: {
      required: "Please select product type ",
    },
    CostPrice: {
      required: "Please Enter a valid Price",
      number: "Numreric values accepted only",
    },
    MinSellingPrice: {
      required: "Please Enter a valid Price",
      number: "Numreric values accepted only",
    },
    MaxSellingPrice: {
      required: "Please Enter a valid Price",
      number: "Numreric values accepted only",
    }
  },
  errorPlacement: function (error, element) {
    if ($(element).hasClass("currency"))
      error.insertAfter($(element).parent().parent()).addClass('errorMessage');
    else
      error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});

$("#NonBillable").submit(function (e) {}).validate({

  rules: {
    RecDate: {
      required: true,
    },
    AmountPaid: {
      required: true,
    }
  },
  // Specify the validation error messages
  messages: {
    RecDate: {
      required: "Please select a valid date",
    },
    AmountPaid: {
      required: "Please Enter a valid Amount",
    }
  },
  errorPlacement: function (error, element) {
    if ($(element).hasClass("amount"))
      error.insertAfter($(element).parent().parent()).addClass('errorMessage');
    else
      error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});


$("#Supplier").submit(function (e) {}).validate({

  rules: {
    SupplierName: {
      required: true,
    },
    TinNum: {
      required: true,
    },
    Email: {
      email: true,
    },
    MobileNum: {
      digits: true,
      minlength: 10,
      maxlength: 10,
    }
  },
  // Specify the validation error messages
  messages: {
    SupplierName: {
      required: "Please supplier name",
    },
    TinNum: {
      required: "Please Enter Vaild TIN number",
    },
    Email: {
      email: "Please Enter Valid Email ID",
    },
    MobileNum: {
      digits: "Only digits allowed",
      minlength: "Enter 10 digit mobile number",
      maxlength: "Enter 10 digit mobile number",
    }
  },
  errorPlacement: function (error, element) {
    if ($(element).hasClass("mobile") || $(element).hasClass("email"))
      error.insertAfter($(element).parent().parent()).addClass('errorMessage');
    else
      error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});


// this is static form validation you can refer this to validate any static forms
$("#Userform").submit(function (e) {}).validate({

  rules: {
    UserName: {
      required: true,
      minlength: 3,
    },
    UserPhone: {
      required: true,
      digits: true,
      minlength: 10,
      maxlength: 10,      
    },
    UserAddr: {
      required: true,
    },
    UserRole: {
      required: true,
    },
    UserPassword: {
      required: true,
      minlength: 6,      
    },
    UserStatus: {
      required: true,
    }
  },
  // Specify the validation error messages
  messages: {
    UserName: {
      required: "Please Enter a valid user name",
      minlength: "User name should be atleast 3 character long",
    },
    UserPhone: {
      required: "Please enter a valid phone number",
      digits: "Only digits allowed",
      minlength: "Enter 10 digit mobile number",
      maxlength: "Enter 10 digit mobile number",
    },
    UserAddr: {
      required: "Please Enter a valid address",
    },
    UserRole: {
      required: "Please select user role",
    },
    UserPassword: {
      required: "Please enter a valid password",
      minlength: "Passeword must be atleast 6 character long",      
    },    
    UserStatus: {
      required: "Please select user status",
    }
  },
  errorPlacement: function (error, element) {
    error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});


// this is static form validation you can refer this to validate any static forms
$("#CoursesForm").submit(function (e) {}).validate({

  rules: {
    CourseName: {
      required: true,
    },
    CourseDurationDays: {
      required: true,
      digits: true,
    },
    CourseDurationMins: {
      required: true,
      digits: true,
    },
    PricePerInstruction: {
      required: true,
      number: true,
    },
    DurationPerInstruction: {
      required: true,
      number: true,
    }
  },
  // Specify the validation error messages
  messages: {
    CourseName: {
      required: "Please Enter a valid course name",
    },
    CourseDurationDays: {
      required: "Please Enter a valid Duration in days",
      digits: "Numreric values accepted only",
    },
    CourseDurationMins: {
      required: "Please Enter a valid duration mins",
      digits: "Numreric values accepted only",
    },
    PricePerInstruction: {
      required: "Please Enter a valid Price",
      digits: "Numreric values accepted only",
    },
    DurationPerInstruction: {
      required: "Please Enter a valid Duration",
      digits: "Numreric values accepted only",
    }
  },
  errorPlacement: function (error, element) {
    error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});


$("#NonBillable").submit(function (e) {}).validate({

  rules: {
    RecDate: {
      required: true,
    },
    AmountPaid: {
      required: true,
    }
  },
  // Specify the validation error messages
  messages: {
    RecDate: {
      required: "Please select a valid date",
    },
    AmountPaid: {
      required: "Please Enter a valid Amount",
    }
  },
  errorPlacement: function (error, element) {
    if ($(element).hasClass("amount"))
      error.insertAfter($(element).parent().parent()).addClass('errorMessage');
    else
      error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});

$("#Service").submit(function (e) {}).validate({

  rules: {
    InvoiceNo: {
      required: true,
    },
    ServiceInvoiceDate: {
      required: true,
    },
    CustomerName: {
      required: true,
    },
    CustomerPhone: {
      required: true,
      digits: true,
      minlength: 10,
      maxlength: 10,
    },
    VehicleNo: {
      required: true,
    },
    AmountPaid: {
      required: true,
    }
  },
  // Specify the validation error messages
  messages: {
    InvoiceNo: {
      required: "please provide invoice number",
    },
    ServiceInvoiceDate: {
      required: "Please select a valid date/time",
    },
    CustomerName: {
      required: "Please enter customer name",
    },
    CustomerPhone: {
      required: "Please enter a valid phone number",
      digits: "Only digits allowed",
      minlength: "Enter 10 digit mobile number",
      maxlength: "Enter 10 digit mobile number",
    },
    VehicleNo: {
      required: "Please enter vehicle number",
    },
    AmountPaid: {
      required: "Please Enter a valid Amount",
    }
  },
  errorPlacement: function (error, element) {
    if ($(element).hasClass("amount") || $(element).hasClass("phone"))
      error.insertAfter($(element).parent().parent()).addClass('errorMessage');
    else
      error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});


$("#addstock").submit(function (e) {}).validate({

  rules: {
    BrandID: {
      required: true,
    },
    productSize: {
      required: true,
    },
    Pattern: {
      required: true,
    },
    Qty: {
      required: true,
      digits: true
    }
  },
  // Specify the validation error messages
  messages: {
    BrandID: {
      required: "Please select a valid brand",
    },
    productSize: {
      required: "Please select valid size",
    },
    Pattern: {
      required: "Please select valid pattern",
    },
    Qty: {
      required: "Please enter valid Quantity",
      digits: "please enter digits only",
    }
  },
  errorPlacement: function (error, element) {
      error.insertAfter($(element).parent()).addClass('errorMessage');
  },
  submitHandler: function (form) {
    $('input[name="UKey"]').val('2');
    form.submit();
  }
});

$("#reportsForm").submit(function (e) {          
    }).validate({

      rules: {
        reportType:
        {
          required: true,
        },
        FromDate:
        {
          required: true,
          dateonly: true,
        },
        ToDate:
        {
          required: true,
          dateonly: true,
        }
      },
      // Specify the validation error messages
      messages: {
        reportType:
        {
          required: "Please select Report type",
        },
        FromDate:
        {
          required: "Please select or enter a valid Date",
          dateonly: "Please select or enter a valid Date in DD-MM-YYY format",
        },
        ToDate:
        {
          required: "Please select or enter a valid Date",
          dateonly: "Please select or enter a valid Date in DD-MM-YYY format",
        }
      },
      errorPlacement: function(error, element) {
        error.insertAfter($(element).parent()).addClass('errorMessage');
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

function addMultiInputNamingRulesSkipFirst(form, alt, field, rules) {
  $(form).find(field).each(function (index) {

    $(this).attr('alt', alt);
    $(this).attr('name', index + $(this).attr('name'));
    $(form).validate();
    if (index != 0) {
      $(this).rules('add', rules);
    }
  });
}

function addMultiInputNamingRules(form, alt, field, rules) {
  $(form).find(field).each(function (index) {

    $(this).attr('alt', alt);
    $(this).attr('name', index + $(this).attr('name'));
    $(form).validate();
    $(this).rules('add', rules);
  });
}

function removeMultiInputNamingRules(form, field) {
  $(form).find(field).each(function (index) {
    $(this).attr('name', $(this).attr('alt'));
    $(this).removeAttr('alt');
  });
}

function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if ((charCode < 48 || charCode > 57))
    return false;

  return true;
}
  
  function autoClosingAlert(selector, delay) {
   var alert = $(selector).alert();
   window.setTimeout(function() { alert.alert('close') }, delay);
  }

function MessageTemplate(MessageType, text) {

  if(MessageType == 0) {
    return "<div class=\"alert alert-block \
            alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
            <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
            green\"></i>&nbsp;&nbsp;" + text +"</div>";
  } else if(MessageType ==1) {
    return "<div class=\"alert alert-block \
            alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">  \
            <i class=\"ace-icon fa fa-times\"></i> </button><i class=\"ace-icon fa fa-ban \
            red\"></i>&nbsp;&nbsp;" + text + " </div>";
  }
}