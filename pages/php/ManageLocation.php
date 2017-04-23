<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'MNGLOC';
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
      <div>Office
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


    <div class="box" ng-app="locApp" ng-controller="locCtrl">
      <div class="box-header with-border">
        <p class="box-title">Locations</p>
        <div class="box-tools pull-right">

          <button type="button" class="btn btn-md btn-primary" ng-click="AddLocation();">
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
              <th>Location</th>
              <!-- <th>Capacity</th> -->
              <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-times" aria-hidden="true"></i></th>
            </tr>
          </thead>
          <tr ng-repeat="loc in locations">
            <td>
            <div ng-class="{'edited': loc.locEdited, 'error' : loc.locInvalid }">
              <label ng-hide="loc.editing" >{{loc.LocName}}</label>
                <input ng-change="loc.locEdited = true;" ng-click="loc.editing = true" ng-blur="loc.editing = false; loc.locInvalid = validateInput(loc.LocName); loc.locEdited = !loc.locInvalid" type="text" ng-show="loc.editing" ng-model="loc.LocName;"  />
            </div>
            </td>
            <!-- <td>
            <div ng-class="{'edited': loc.capEdited, 'error' : loc.capInvalid}">
              <label  ng-hide="loc.editing" >{{loc.LocCapacity}}</label>
                <input ng-change="loc.capEdited = true" ng-click="loc.editing = true" ng-blur=" loc.editing = false; loc.capInvalid = validateInput(loc.LocCapacity  ); loc.capEdited = !loc.capInvalid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="loc.editing" ng-model="loc.LocCapacity"  />
            </div>
            </td> -->
            <td>
            <a href="#" ng-click="loc.editing = !loc.editing;" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="#" ng-click="RemoveLocation(loc.LocId, $index);" ><i class="fa fa-times" aria-hidden="true"></i></a>
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

  angular.module('locApp', [])
  .controller('locCtrl', function($scope, dataService) {

  // refreshing data in the table
  $scope.RefreshView = function() {
    dataService.getLocations(function(response) {
      console.log("got response"  + response.data[0]);
      $scope.locations = response.data;
    });
  };
  $scope.minlength = 3;

  // initial call
  $scope.RefreshView();

  // Remove Location action
  $scope.RemoveLocation = function($LocationId, $index) {
    console.log($LocationId);
    if($LocationId == null) {

      console.log("Removing at index : " + $index);
      $scope.locations.splice($index,1);
      document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;Location has been Removed.</div>";
          autoClosingAlert(".alert-block", 2000);
          return;
    }
    dataService.deleteLocation($LocationId, function(response) {
      if(response.data == 1 ) {
         document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;Location has been Removed.</div>";
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

  // save Location Action
  $scope.SaveAll = function() {
    console.log("Save All");
    var ItemArr = [];
    for (var i = 0; i < $scope.locations.length; i++) {
       if( $scope.locations[i].hasOwnProperty('locEdited') && $scope.locations[i].locEdited )
            ItemArr.push($scope.locations[i]);

        if( $scope.locations[i].hasOwnProperty('locInvalid') && $scope.locations[i].locInvalid )
            {
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
          green\"></i>&nbsp;&nbsp;" + response.data +" record(s) saved successfully.</div>";
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


  // Add Location action
  $scope.AddLocation = function() {
    var obj = {
      'LocName': 'New Location',
      'locEdited' : true,
      'editing' : true
    };
    $scope.locations.splice(0, 0, obj);
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
    this.getLocations = function(callback) {
      console.log("Get Locations");
      $http({
        method : "GET",
        url : "AddUpdateRetriveLocation.php?action=Retrive",
      }).then(callback)
    };

    //Delete Location Service
    this.deleteLocation = function(LocationId,callback) {
      console.log("delete Location");
      $http({
      method : "GET",
      url : "AddUpdateRetriveLocation.php?action=Remove&LocationId=" + LocationId,
      }).then(callback)
    };

    //Save Location Service
    this.saveAll = function(LocArray,callback) {

      console.log("Save All Locations");
      $http({
      method : 'GET',
      url : "AddUpdateRetriveLocation.php?action=save",
      params:{LocArr : JSON.stringify(LocArray)},
      }).then(callback)
    };

  });
  // .directive("minLimit", [function() {
  //     return {
  //         restrict: "A",
  //         link: function(scope, elem, attrs) {
  //             var limit = parseInt(attrs.minLimit);
  //             angular.element(elem).on("keypress", function(e) {
  //                 if (this.value.length < limit) e.preventDefault();
  //             });
  //         }
  //     }
  // }]);

</script>
<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
