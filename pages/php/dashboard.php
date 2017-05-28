<?php
$CDATA['PAGE_NAME'] = 'DASHBOARD';
require '_connect.php';
require '_core.php';
if(isLogin())
{
require '_header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
<!--   <section class="content-header">
    <h1>
      <small>DASHBOARD</small>
    </h1>
  </section> -->

  <!-- Main content -->
  <section class="content">
    <div class="alert bg-gray" id="welcomeMsg">
      <h4>Welcome <?php getUserName() ?></h4>
    </div>

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo TodaysSale() ?></h3>
              <p>Today's sale</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="orders.php?date=<?php echo date('d-m-Y');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo TodaysService(); ?></h3>
              <p>Today's Service</p>
            </div>
            <div class="icon">
              <i class="fa fa-car"></i>
            </div>
            <a href="services.php?date=<?php echo date('d-m-Y');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo TodaysNonBillable() ?></h3>
              <p>Today's Non billables</p>
            </div>
            <div class="icon">
              <i class="fa fa-thumb-tack"></i>
            </div>
            <a href="nonbillable.php?date=<?php echo date('d-m-Y');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>0</h3>

              <p>Upcoming Cheques</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Quarterly Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                 
                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" style="height: 180px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                  <p class="text-center">
                    <strong>Current Quarter statstics</strong>
                  </p>

                  <div class="progress-group">
                    <span class="progress-text">Total Sale</span>
                    <span class="progress-number total-sale">0</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua total-sale-progress" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Total Service</span>
                    <span class="progress-number total-service">0</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red total-service-progress" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Total Nonbillable</span>
                    <span class="progress-number total-nonbillable">0</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green total-nonbillable-progress" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->      
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->

<script>
var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$(function () {

  'use strict';

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */

  //-----------------------
  //- MONTHLY SALES CHART -
  //-----------------------

    var areaChartCanvas = $("#salesChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas);
    var currentDate = new Date();
    var currentMonth = currentDate.getMonth();


    var ToMonth = currentMonth+ +1;
    var FromMonth = (parseInt(ToMonth/3) + +1)*3-2;
    var year = currentDate.getFullYear();
    var selectedMoths = [];

    for (var i=FromMonth; i<FromMonth+3; i++ ) {
      selectedMoths.push(months[i-1]+'-' + year);
    }
    
    var ToMonthPlus1 = FromMonth+ + 3;
    FromMonth = FromMonth < 10 ? '0' + FromMonth : FromMonth;
    ToMonthPlus1 = ToMonthPlus1 < 10 ? '0' + ToMonthPlus1 : ToMonthPlus1;
    console.log(selectedMoths);
    var fromdate = year +'-' + FromMonth +'-' + '01';
    var todate = year +'-' + ToMonthPlus1 +'-' + '01';

   $.ajax({
    url: "GetReportsData.php?action=Quarterly&fromDate="+fromdate+"&toDate="+todate ,
    method: "GET",
    async: false,
    success: function(data) {
      var reportData = jQuery.parseJSON(data);
      var saleData = [];
      //console.log(data.sale);
      var saleData = [];
      var serviceData = [];
      var nonbillableData = [];

      var saleTotal = 0;
      var serviceTotal = 0;
      var nonbillableTotal = 0;      
      
      for (var k in reportData.sale){
          if (reportData.sale.hasOwnProperty(k)) {
              saleData.push(reportData.sale[k]);
              saleTotal += +reportData.sale[k];
          }
      }

      for (var k in reportData.service){
          if (reportData.service.hasOwnProperty(k)) {
              serviceData.push(reportData.service[k]);
              serviceTotal += +reportData.service[k];
          }
      }  

      for (var k in reportData.nonbillable){
          if (reportData.nonbillable.hasOwnProperty(k)) {
              nonbillableData.push(reportData.nonbillable[k]);
              nonbillableTotal += +reportData.nonbillable[k];
          }
      }            

      var areaChartData = {
      labels: selectedMoths,
      datasets: [
        {
          label: "Service",
          fillColor: "rgb(210, 214, 222)",
          strokeColor: "rgb(210, 214, 222)",
          pointColor: "rgb(210, 214, 222)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgb(220,220,220)",
          data: serviceData
        },
        {
          label: "Sale",
          fillColor: "rgba(60,141,188,0.9)",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: saleData
        },
      ]
    };

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: false,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - Whether the line is curved between points
      bezierCurve: true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot: false,
      //Number - Radius of each point dot in pixels
      pointDotRadius: 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true
    };

    //Create the line chart
    areaChart.Line(areaChartData, areaChartOptions);
    $('.total-sale').text(saleTotal);
    $('.total-service').text(serviceTotal);
    $('.total-nonbillable').text(nonbillableTotal);

    var saleRatio = saleTotal*100/(saleTotal+serviceTotal+nonbillableTotal);
    var serviceRatio = serviceTotal*100/(saleTotal+serviceTotal+nonbillableTotal);
    var nonillableRatio = nonbillableTotal*100/(saleTotal+serviceTotal+nonbillableTotal);
    $('.total-nonbillable-progress').width(nonillableRatio+"%");
    $('.total-sale-progress').width(saleRatio+"%");
    $('.total-service-progress').width(serviceRatio+"%");
    },
    error: function(data) {
      console.log(data);
    }
  });

  //---------------------------
  //- END MONTHLY SALES CHART -
  //---------------------------
});
</script>
<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
