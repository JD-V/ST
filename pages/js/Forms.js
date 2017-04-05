// javascript

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function AddDatePicker() {
$('*[name=StDate]').appendDtpicker();
}

function AddSection(sectionID) {
  //Statusx
  var ID = sectionID[sectionID.length -1];

  var elem = document.getElementById(sectionID);
  var newNode = document.createElement('fieldset');
  ID++;
  newNode.innerHTML  = "<fieldset id=\"Status"+ID+"\"> \
      <legend>Status<span class=\"mandatoryLabel\">*</span></legend> \
      <div class=\"form-group\"> \
       <label for=\"Status"+ID+"\" class=\"control-label col-sm-3 lables\">Date<span class=\"mandatoryLabel\">*</span></label> \
        <div class='col-sm-4'> \
           <input type=\"datetime\" class=\"form-control\" name=\"StDate\" id=\"StDate1"+ID+"\" /> \
        </div> \
        <div id=\"errorMsgEDT\" name=\"errorMsgEDT\" style=\"font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;\" role=\"error\"></div> \
     </div> \
     <div class=\"form-group\">\
       <label for=\"StStaff"+ID+"\" class=\"control-label col-sm-3 lables\">Staff<span class=\"mandatoryLabel\">*</span></label> \
       <div class=\"col-sm-4\"> \
         <select class=\"form-control\" id=\"StStaff1\" name=\"StStaff\"> \
         </select> \
       </div> \
     </div> \
     <div class=\"form-group\"> \
       <label for=\"StCurStat"+ID+"\" class=\"control-label col-sm-3 lables\">Current Status<span class=\"mandatoryLabel\">*</span></label> \
       <div class=\"col-sm-4\"> \
         <select class=\"form-control\" id=\"StCurStat1\" name=\"StCurStat\"> \
       </select> \
       </div> \
     </div> \
     <div class=\"form-group\"> \
       <label for=\"StNote1"+ID+"\" class=\"control-label col-sm-3 lables\">Note</label> \
       <div class=\"col-sm-4\"> \
         <textarea  class=\"form-control\" id=\"StNote1\" name=\"StNote\" placeholder=\"Notes\"></textarea> \
       </div> \
     </div> \
     <div class=\"form-group\"> \
       <label class=\"col-sm-3 control-label no-padding-right\" for=\"form-field-1\"> </label> \
       <div class=\"col-sm-4\"> \
         <button type=\"button\" name=\"AddStatus\" id=\"AddStatus"+ID+"\" class=\"btn btn-sm btn-primary\" style = \"margin-left:0px\" onclick=\"AddSection('Status"+ID +"')\">Add</button> \
         <button type=\"button\" name=\"RemoveStatus\" id=\"RemoveStatus"+ID+"\" class=\"btn btn-sm btn-danger\" style=\"margin-left:5px\"  onclick=\"RemoveSection('Status"+ID+"')\">Remove</button> \
       </div> \
     </div> ";
  insertAfter(newNode,elem);
  AddDatePicker();

}


function RemoveSection(sectionID) {
  if(sectionID != 'Status1') {
    var elem = document.getElementById(sectionID);
    return elem.parentNode.removeChild(elem);
  }
}
