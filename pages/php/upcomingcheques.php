<?php
$CDATA['PAGE_NAME'] = 'UPCHEQUE';
require '_connect.php';
require '_core.php';

if(isLogin())
{
require '_header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Cheques History
      <small></small><div>
    </h1>
<!--     <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Layout</a></li>
      <li class="active">Fixed</li>
    </ol> -->
  </section>

  <!-- Main content -->
  <section class="content" ng-app="chequeApp" ng-controller="chequeCtrl">
    <!-- <div class="callout callout-info">
      <h4>Tip!</h4>

      <p>Add the fixed class to the body tag to get this layout. The fixed layout is your best option if your sidebar
        is bigger than your content because it prevents extra unwanted scrolling.</p>
    </div> -->
    <div id="messages">
    </div>
    <!-- Default box -->
    <div class="box" >
      <div class="box-header with-border">
        <h3 class="box-title">Upcoming</h3>

        <div class="box-tools pull-right">
           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> 
        </div>
      </div>
      <div class="box-body">
        <form>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-search"></i></div>
              <input type="text" class="form-control" placeholder="Search upcoming cheque" ng-model="searchUpcomingCheque">
            </div>      
          </div>
        </form>      
      <div class="col-sm-12" >
          <table class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Cheque No.</th>
                <th>Cheque Date</th>
                <th>From</th>
                <th>To</th>
                <th>Type</th>
                <th>Amount Paid</th>
                <th>Resolve</i></th>
              </tr>
            </thead>
            <tbody>
                  <tr ng-repeat="cheque in unResolvedCheques | orderBy:sortType:sortReverse | filter:searchUpcomingCheque ">
                    <th>{{cheque.Invoice}}</th>
                    <td>{{cheque.ChequeNo}}</td>
                    <td>{{cheque.ChequeDate}} </td>
                    <td>{{cheque.From}} </td>
                    <td>{{cheque.To}} </td>
                    <td><lable class="label" ng-class="{'label-danger': cheque.Type==2, 'label-success' : cheque.Type==1 }">{{cheque.Type == 1 ? "Incoming" : "Outgoing"}}</lable> </td>
                    <td>{{cheque.AmountPaid}} </td>
                    <th><input type="button" class="btn btn-sm btn-info" value="Resolve" ng-click="Resolve(cheque.ChequeNo,cheque.Invoice,cheque.Type)" ></th>
                 
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.box-body -->
    </div>

    <div class="box" >
      <div class="box-header with-border">
        <h3 class="box-title">All</h3>

        <div class="box-tools pull-right">
           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> 
        </div>
      </div>
      <div class="box-body">
        <form>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-search"></i></div>
              <input type="text" class="form-control" placeholder="Search all cheques" ng-model="searchAllCheques">
            </div>      
          </div>
        </form>       
      <div class="col-sm-12" >
          <table class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Cheque No.</th>
                <th>Cheque Date</th>
                <th>From</th>
                <th>To</th>
                <th>Type</th>
                <th>Amount Paid</th>
                <th>Resolution</i></th>
              </tr>
            </thead>
            <tbody>
                  <tr ng-repeat="cheque in allCheques | orderBy:sortType:sortReverse | filter:searchAllCheques ">
                    <th>{{cheque.Invoice}}</th>
                    <td>{{cheque.ChequeNo}} </td>
                    <td>{{cheque.ChequeDate}} </td>
                    <td>{{cheque.From}} </td>
                    <td>{{cheque.To}} </td>
                    <td>
                    <lable class="label" ng-class="{'label-danger': cheque.Type==2, 'label-success' : cheque.Type==1 }">{{cheque.Type == 1 ? "Incoming" : "Outgoing"}}</lable></td>
                    <td>{{cheque.AmountPaid}} </td>
                    <th><lable class="label" ng-class="{'label-warning': cheque.Resolved==0, 'label-primary' : cheque.Resolved==1 }">{{cheque.Resolved == 1 ? "Resolved" : "UnResolved"}}</lable></th>
                 
            </tbody>
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

  angular.module('chequeApp', [])
  .filter('getById', function() {
  return function(input, id) {
    var i=0, len=input.length;
    for (; i<len; i++) {
      if (+input[i].ProductID == +id) {
        return input[i];
      }
    }
    return null;
  }
})
  .controller('chequeCtrl', function($scope,$filter, dataService) {
  $scope.searchUpcomingCheque = "";
  $scope.searchAllCheques = "";
  // refreshing data in the table
  $scope.RefreshView = function() {
    dataService.getUnResolvedCheques(function(response) {
      $scope.unResolvedCheques = response.data;
    });
    dataService.getAllCheques(function(response) {
      $scope.allCheques = response.data;
    });    
  };
  // initial call
  $scope.RefreshView();

  // save Stocks Action
  $scope.Resolve = function(ChequeNo, Invoice, Type) { 
    dataService.resolveCheque(ChequeNo, Invoice, Type, function(response) {
      if(response.data.status == 1) {
         document.getElementById("messages").innerHTML = MessageTemplate(0, "Cheque "+ ChequeNo + " has been marked as resolved");
          $scope.RefreshView();
          autoClosingAlert(".alert-block", 2000);
      }
      else {
          document.getElementById("messages").innerHTML = MessageTemplate(1, "Unable to perform the operation");
          autoClosingAlert(".alert-block", 4000);
      }
    });
  };
  // Over

  })
  .service('dataService', function($http) {

    // Get Cheque Service
    this.getAllCheques = function(callback) {
      $http({
        method : "GET",
        url : "ResolveAndRetriveCheuqes.php?action=Retrive&item=All",
      }).then(callback)
    };

    this.getUnResolvedCheques = function(callback) {
      $http({
        method : "GET",
        url : "ResolveAndRetriveCheuqes.php?action=Retrive&item=UnResolved",
      }).then(callback)
    };

    // Resolve Cheque Service
    this.resolveCheque = function(ChequeNo, Invoice, Type, callback) {
      $http({
        method : 'GET',
        url : "ResolveAndRetriveCheuqes.php?action=Resolve&ChequeNo="+ChequeNo+"&Invoice="+Invoice+"&Type="+Type,
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
  header('Location: ../../index.php');
}
?>
