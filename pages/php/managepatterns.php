<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'MNGBRAND';
if(isLogin() && isAdmin())
{
require '_header.php';
?>
<style type="text/css">

.edited label:after {
  content: " edited";
  text-transform: uppercase;
  color: #a7b9c4;
  font-size : 10px;
  padding-left: 5px;
}

.error label:after {
  content: " error";
  text-transform: uppercase;
  color: #ff0000;
  font-size : 10px;
  padding-left: 5px;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <div>Brand
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
    <!-- <div class="callout callout-info">
      <h4>Tip!</h4>

      <p>Add the fixed class to the body tag to get this layout. The fixed layout is your best option if your sidebar
        is bigger than your content because it prevents extra unwanted scrolling.</p>
    </div> -->
    <div id="messages">
    </div>
    <!-- Default box -->


    <div class="box" ng-app="PatternApp" ng-controller="PatternCtrl">
      <div class="box-header with-border">
        <p class="box-title">Pattern</p>
        <div class="box-tools pull-right">

          <button type="button" class="btn btn-md btn-primary" ng-click="AddPattern();">
            <i class="fa fa-plus"></i>&nbsp;
            Add
          </button>&nbsp;
          <button type="button" class="btn btn-md btn-success" ng-click="SaveAll();">
            <i class="fa fa-floppy-o"></i>&nbsp;
            Save All
          </button>
        </div>
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12">
        <table class="table table-striped table-hover" >
          <thead>
            <tr>
              <th>Patterns</th>
              <!-- <th>Capacity</th> -->
              <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-times" aria-hidden="true"></i></th>
            </tr>
          </thead>
          <tr ng-repeat="Pattern in Patterns">
            <td>
            <div ng-class="{'edited': Pattern.PatternEdited, 'error' : Pattern.PatternInvalid }">
              <label ng-hide="Pattern.editing" >{{Pattern.Pattern}}</label>
                <input ng-change="Pattern.PatternEdited = true;" ng-click="Pattern.editing = true" 
                ng-blur="Pattern.editing = false; Pattern.PatternInvalid = validateInput(Pattern.Pattern); 
                Pattern.PatternEdited = !Pattern.PatternInvalid" type="text" ng-show="Pattern.editing" ng-model="Pattern.Pattern;"  />
            </div>
            </td>

            <td>
            <a ng-click="Pattern.editing = !Pattern.editing;" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
            <a ng-click="RemovePattern(Pattern.PatternID, $index);" ><i class="fa fa-times" aria-hidden="true"></i></a>
            </td>
          </tr>
        </table>
      </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">

  angular.module('PatternApp', [])
  .controller('PatternCtrl', function($scope, dataService) {

  // refreshing data in the table
  $scope.RefreshView = function() {
    dataService.getPatterns(function(response) {
      $scope.Patterns = response.data;
    });
  };
  $scope.minlength = 3;

  // initial call
  $scope.RefreshView();

  // Remove Location action
  $scope.RemovePatterns = function($PatternID, $index) {
    console.log($PatternID);
    if($PatternID == null) {

      console.log("Removing at index : " + $index);
      $scope.Brands.splice($index,1);
      document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;Pattern has been Removed.</div>";
          autoClosingAlert(".alert-block", 2000);
          return;
    }
    dataService.deleteBrand($BrandID, function(response) {
      if(response.data == 1 ) {
         document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;Pattern has been added.</div>";
          $scope.RefreshView();
          autoClosingAlert(".alert-block", 2000);
      }
      else {
          document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">  \
          <i class=\"ace-icon fa fa-times\"></i> </button><i class=\"ace-icon fa fa-ban \
          red\"></i> &nbsp; &nbsp;" + response.data + " </div>";
          autoClosingAlert(".alert-block", 4000);
      }

    });
  };
  // over

  // save Brand Action
  $scope.SaveAll = function() {
    console.log("Save All");
    var ItemArr = [];
    for (var i = 0; i < $scope.Brands.length; i++) {
       if( $scope.Brands[i].hasOwnProperty('BrandEdited') && $scope.Brands[i].BrandEdited ) {
         var BrandItem = { };
         if($scope.Brands[i].hasOwnProperty('BrandID')){
            BrandItem.BrandID = $scope.Brands[i].BrandID;
         }          
         BrandItem.BrandName = $scope.Brands[i].BrandName;
         ItemArr.push(BrandItem);
       }

      if( $scope.Brands[i].hasOwnProperty('BrandInvalid') && $scope.Brands[i].BrandInvalid ) {
            document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
            alert-warning\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
            <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-hand-paper-o\"></i>&nbsp;&nbsp; Please correct the errors.</div>";
            autoClosingAlert(".alert-block", 2000);
            return;
          }
    }

    if(ItemArr.length == 0) {

      document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-warning\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-ban\"></i>&nbsp;&nbsp; Nothing to update.</div>";
          autoClosingAlert(".alert-block", 2000);
          return;
    }

    dataService.saveAll(ItemArr, function(response) {
      if(parseInt(response.data, 10)) {
         document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;" + response.data +" Brand(s) saved successfully.</div>";
          $scope.RefreshView();
          autoClosingAlert(".alert-block", 2000);
      }
      else {
          document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">  \
          <i class=\"ace-icon fa fa-times\"></i> </button><i class=\"ace-icon fa fa-ban \
          red\"></i> &nbsp; &nbsp;" + response.data + " </div>";
          autoClosingAlert(".alert-block", 4000);
      }
    });
  };
  // Over


  // Add Brand action
  $scope.AddBrand = function() {
    var obj = {
      'BrandName': 'New Brand',
      'BrandEdited' : true,
      'editing' : true
    };
    $scope.Brands.splice(0, 0, obj);
  };
  // over

  $scope.validateInput = function($inputValue) {
    if($inputValue == "") {
      document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">  \
          <i class=\"ace-icon fa fa-times\"></i> </button><i class=\"ace-icon fa fa-ban \
          red\"></i> &nbsp; &nbsp;can not be blank</div>";
          autoClosingAlert(".alert-block", 2000);
          return true;
    }
    else
      return false;
  };

  })
  .service('dataService', function($http) {

    //get Location Service
    this.getBrands = function(callback) {
      console.log("Get Brands");
      $http({
        method : "GET",
        url : "AddUpdateRetriveBrand.php?action=Retrive", //upate page
      }).then(callback)
    };

    //Delete Brands Service
    this.deleteBrand = function(BrandID,callback) {
      console.log("delete Brand");
      $http({
      method : "GET",
      url : "AddUpdateRetriveBrand.php?action=Remove&BrandID=" + BrandID,
      }).then(callback)
    };

    //Save Brand Service
    this.saveAll = function(BrandArray,callback) {

      console.log("Save All Brands");
      $http({
      method : 'GET',
      url : "AddUpdateRetriveBrand.php?action=save",
      params:{BrandArr : JSON.stringify(BrandArray)},
      }).then(callback)
    };

  });
  
  function autoClosingAlert(selector, delay) {
   var alert = $(selector).alert();
   window.setTimeout(function() { alert.alert('close') }, delay);
  }

</script>
<?php
require '_footer.php';
}
else
{
  header('Brand: ../../index.php');
}
?>
