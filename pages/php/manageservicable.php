<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'MNSRVS';
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
      <div>Manage Serviceabels
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
        <p class="box-title">Serviceable Items</p>
        <div class="box-tools pull-right">
          
          <button type="button" class="btn btn-md btn-primary" ng-click="AddNewItem();">
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
      <div class="table-responsive col-sm-12" id= "OrganizerList" >
        <table id="OrgTable" class="table table-striped table-hover" >
          <thead>
            <tr>
              <th>Item</th>
              <th>Price</th>
              <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-times" aria-hidden="true"></i></th>
            </tr>
          </thead>
          <tr ng-repeat="item in serviceable">
            <td>
            <div ng-class="{'edited': item.itemEdited, 'error' : item.itemInvalid }">
              <label ng-hide="item.editing" >{{item.Item}}</label>
                <input ng-change="item.itemEdited = true;" ng-click="item.editing = true" ng-blur="item.editing = false; item.itemInvalid = validateInput(item.Item); item.itemEdited = !item.itemInvalid" type="text" ng-show="item.editing" ng-model="item.Item;"  />
            </div>
            </td>
            <td>
            <div ng-class="{'edited': item.priceEdited, 'error' : item.priceInvalid}">
              <label  ng-hide="item.editing" >{{item.Price}}</label>
                <input ng-change="item.priceEdited = true" ng-click="item.editing = true" ng-blur=" item.editing = false; item.priceInvalid = validateInput(item.Price  ); item.priceEdited = !item.priceInvalid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="item.editing" ng-model="item.Price"  />
            </div>
            </td>
            <td>
            <a href="#" ng-click="item.editing = !item.editing;" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="#" ng-click="RemoveServiceable(item.ItemID, $index);" ><i class="fa fa-times" aria-hidden="true"></i></a>
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
    dataService.getServiceable(function(response) {
      console.log(response.data);
      $scope.serviceable = response.data;
    });
  };
  $scope.minlength = 3;

  // initial call
  $scope.RefreshView();

  // Remove Location action
  $scope.RemoveServiceable = function($ItemID, $index) {
    console.log($ItemID);
    if($ItemID == null) {

      console.log("Removing at index : " + $index)
      $scope.serviceable.splice($index,1);
      document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;Serviceable has been Removed.</div>";
          autoClosingAlert(".alert-block", 2000);
          return;
    }
    dataService.deleteServiceable($ItemID, function(response) {
      if(response.data == 1 ) {
         document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;Serviceable has been Removed.</div>";
          $scope.RefreshView();
          autoClosingAlert(".alert-block", 2000);
      }
      else {
          document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">  \
          <i class=\"ace-icon fa fa-times\"></i> </button><i class=\"ace-icon fa fa-ban \
          red\"></i> &nbsp; &nbsp;unable to remove serviceable.</div>";
          autoClosingAlert(".alert-block", 4000);
      }
        
    });
  };
  // over

  // save Location Action
  $scope.SaveAll = function() {
    console.log("Save All");
    var ItemArr = []; 
    for (var i = 0; i < $scope.serviceable.length; i++) {
       if(  ( $scope.serviceable[i].hasOwnProperty('itemEdited')  && 
              $scope.serviceable[i].itemEdited) || 
            ( $scope.serviceable[i].hasOwnProperty('priceEdited') && 
              $scope.serviceable[i].priceEdited) )

          ItemArr.push($scope.serviceable[i]);
        
        if( ( $scope.serviceable[i].hasOwnProperty('itemInvalid') && 
              $scope.serviceable[i].itemInvalid) ||
            ( $scope.serviceable[i].hasOwnProperty('priceInvalid') && 
              $scope.serviceable[i].priceInvalid) )

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
          green\"></i>&nbsp;&nbsp;" + response.data +" Item(s) saved successfully.</div>";
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


  // Add serviceable action
  $scope.AddNewItem = function() {
    var obj = {
      'Item': 'New Serviceable',
      'Price': '0',
      'itemEdited' : true,
      'priceEdited' : true,
      'editing' : true
    };
    $scope.serviceable.splice(0, 0, obj);
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
    this.getServiceable = function(callback) {
      console.log("Get Locations"); 
      $http({
        method : "GET",
        url : "AddUpdateRetriveServiceable.php?action=Retrive",
      }).then(callback)
    };

    //Delete Location Service
    this.deleteServiceable = function(ItemId,callback) {
      console.log("delete Location");
      $http({
        method : "GET",
        url : "AddUpdateRetriveServiceable.php?action=Remove&ItemID=" + ItemId,
      }).then(callback)
    };

    //Save Location Service
    this.saveAll = function(ItemArray,callback) {
      
      console.log("Save All Locations");
      $http({
        method : 'GET',
        url : "AddUpdateRetriveServiceable.php?action=save",
        params:{ItemArr : JSON.stringify(ItemArray)},
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
  header('Location: ../../index.php');
}
?>
