<?php
$CDATA['PAGE_NAME'] = 'REPORTS';
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
      <div>Reports
      <small></small><div>
    </h1>
  </section>


  <!-- Main content -->
  <section class="content">
    <div class="callout bg-gray">
      <h4>Analytics !</h4>
    </div>

    <div class="row" >
    
      <div class="col-md-6">

        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Sale / Service / Non billable Report</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            
             <div id="Report1">
              <form class="form-horizontal"  role="form" name="reportsForm" id="reportsForm" action="AsyncGenerateReports.php"  method="post" >
                <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" required="true">

                <div class="form-group">
                  <label for="reportType" class="control-label col-sm-3 lables">Report Type<span class="mandatoryLabel">*</span></label>
                  <div class="col-sm-4">
                    <select class="form-control" id="reportType" name="reportType">
                      <option selected="true" disabled="disabled" style="display: none" value="default" >Select Report type</option>
                      <option value="1" >Sale</option>
                      <option value="2" >Service</option>
                      <option value="3" >Non Billable</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                   <label for="FormDate" class="control-label col-sm-3 lables" >From Date<span class="mandatoryLabel">*</span></label>
                    <div class='col-sm-4'>
                      <input type="datetime" class="form-control" name="FromDate" id="FromDate" value="" />
                    </div>
                </div>
                 

                <div class="form-group">
                    <label for="ToDateTime" class="control-label col-sm-3 lables" >To Date<span class="mandatoryLabel">*</span></label>
                     <div class='col-sm-4'>
                       <input type="datetime" class="form-control" name="ToDate" id="ToDate" value=""/>
                     </div>
                </div>
            
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>
                    <div class="col-sm-9">
                        <input type="submit" name="nc_submit" value="download" id="ID_Sub" class="btn btn-sm btn-success" />
                        <input type="hidden" name="UKey" value="1" id="ID_UKey" />
                    </div>
                </div>

              </form>
            </div>
            
          </div>
          <!-- /.box-body -->
        </div>

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Donut Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="pieChartContainer">
              <canvas id="pieChart" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>        
        <!-- /.box -->

      </div>

      <div class="col-md-6">

        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Graphical Representation</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <div class="box-body">
             <div id="Report1">
                <form class="form-horizontal"  role="form" name="GraphForm" id="GraphForm" >
                  <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" required="true">

                <div class="form-group">
                  <label for="year" class="control-label col-sm-3 lables">year<span class="mandatoryLabel">*</span></label>
                  <div class="col-sm-4">
                    <select class="form-control" id="year" name="year">
                      <option value="2016-2017" >2016-17</option>
                      <option value="2017-2018" selected>2017-18</option>
                      <option value="2018-2019" >2018-19</option>
                      <option value="2019-2020" >2019-20</option>
                      <option value="2020-2021" >2020-21</option>
                      <option value="2021-2022" >2021-22</option>
                      <option value="2022-2023" >2022-23</option>
                      <option value="2023-2024" >2023-24</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                   <label for="FormMonth" class="control-label col-sm-3 lables" >From Month<span class="mandatoryLabel">*</span></label>
                    <div class='col-sm-4'>
                      <select class="form-control" id="FormMonth" name="FormMonth">
                        <option value="01" >Jan</option>
                        <option value="02" >Feb</option>
                        <option value="03" >Mar</option>
                        <option value="04" selected >Apr</option>
                        <option value="05" >May</option>
                        <option value="06" >Jun</option>
                        <option value="07" >Jul</option>
                        <option value="08" >Aug</option>
                        <option value="09" >Sep</option>
                        <option value="10" >Oct</option>
                        <option value="11" >Nov</option>
                        <option value="12" >Dec</option>
                      </select>
                    </div>
                </div>
                 

                <div class="form-group">
                    <label for="ToMonth" class="control-label col-sm-3 lables" >To Month<span class="mandatoryLabel">*</span></label>
                     <div class='col-sm-4'>
                      <select class="form-control" id="ToMonth" name="ToMonth">
                        <option value="01" >Jan</option>
                        <option value="02" >Feb</option>
                        <option value="03" selected>Mar</option>
                        <option value="04" >Apr</option>
                        <option value="05" >May</option>
                        <option value="06" >Jun</option>
                        <option value="07" >Jul</option>
                        <option value="08" >Aug</option>
                        <option value="09" >Sep</option>
                        <option value="10" >Oct</option>
                        <option value="11" >Nov</option>
                        <option value="12" >Dec</option>
                      </select>
                     </div>
                </div>
            
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>
                    <div class="col-sm-9">
                        <input type="button" name="viewCharts" value="View" id="viewCharts" class="btn btn-sm btn-success" />
                        <input type="hidden" name="UKey" value="1" id="ID_UKey" />
                    </div>
                </div>

              </form>
            </div>
            
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Area Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="areachartContainer">
                <canvas id="areaChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
      </div>      

    </div>
    
    <div class="row" >
      <!-- BAR CHART -->
      <div class="col-md-12">
      <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Bar Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="barchartContainer">
                <canvas id="barChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          </div>
    </div>
  </section>
  <!-- /.content -->

</div>
<!-- /.content-wrapper -->
<!-- ChartJS 1.0.1 -->
<script src="../../plugins/chartjs/Chart.min.js"></script>
<!-- page script -->
<script>
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  $('#viewCharts').on('click',function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    // $('#areaChart').remove(); // this is my <canvas> element
    // $('#areachartContainer').append('<canvas id="areaChart" style="height:250px"><canvas>');

    // $('#barChart').remove(); // this is my <canvas> element
    // $('#barchartContainer').append('<canvas id="barChart" style="height:250px"><canvas>');

    // $('#pieChart').remove(); // this is my <canvas> element
    // $('#pieChartContainer').append('<canvas id="pieChart" style="height:250px"><canvas>');

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas);

    var FromMonth = $("#FormMonth option:selected").val();
    var ToMonth = $("#ToMonth option:selected").val();
    var year = $("#year option:selected").val().split('-');
    var selectedMoths = [];

    for (var i=FromMonth; i<=12; i++ ) {
      selectedMoths.push(months[i-1]);
    }
    for (var i=1; i<=ToMonth; i++ ) {
      selectedMoths.push(months[i-1]);
    }
    ToMonthPlus1 = parseInt(ToMonth)+1;
    ToMonthPlus1 = ToMonthPlus1 < 10 ? '0' + ToMonthPlus1 : ToMonthPlus1;
    console.log(selectedMoths);
    var fromdate = year[0] +'-' + FromMonth +'-' + '01';
    var todate = year[1] +'-' + ToMonthPlus1 +'-' + '01';


    $.ajax({
    url: "GetReportsData.php?action=Retrive&fromDate="+fromdate+"&toDate="+todate ,
    method: "GET",
    async: false,
    success: function(data) {
      var data = jQuery.parseJSON(data);
      var saleData = [];
      console.log(data.sale);
      // for (var i=FromMonth; i<=12; i++ ) {
      //   saleData.push(data.sale.year[0] +'-' + FromMonth);
      // }
      // for (var i=1; i<=ToMonth; i++ ) {
      //   saleData.push(months[i-1]);
      // }

      // console.log(data.sale);
      var areaChartData = {
      labels: selectedMoths,
      datasets: [
        {
          label: "Non billable",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data: [23, 23, 45, 61, 36, 25, 44]
        },
        {
          label: "Service",
          fillColor: "rgba(152,251,152, 0.6)",
          strokeColor: "rgba(152,251,152, 0.5)",
          pointColor: "rgba(152,251,152, 1)",
          pointStrokeColor: "#98FB98",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(152,251,152,1)",
          data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
          label: "Sale",
          fillColor: "rgba(60,141,188,0.5)",
          strokeColor: "rgba(60,141,188,0.4)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: [28, 48, 40, 19, 86, 27, 90]
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

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
      {
        value: 700,
        color: "#f56954",
        highlight: "#f56954",
        label: "Sale"
      },
      {
        value: 500,
        color: "#00a65a",
        highlight: "#00a65a",
        label: "Service"
      },
      {
        value: 400,
        color: "#f39c12",
        highlight: "#f39c12",
        label: "Nonbillable"
      }
    ];
    var pieOptions = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      //String - The colour of each segment stroke
      segmentStrokeColor: "#fff",
      //Number - The width of each segment stroke
      segmentStrokeWidth: 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps: 100,
      //String - Animation easing effect
      animationEasing: "easeOutBounce",
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);

     //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas);
    var barChartData = areaChartData;
    barChartData.datasets[1].fillColor = "#00a65a";
    barChartData.datasets[1].strokeColor = "#00a65a";
    barChartData.datasets[1].pointColor = "#00a65a";
    var barChartOptions = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: true,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - If there is a stroke on each bar
      barShowStroke: true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth: 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing: 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing: 1,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to make the chart responsive
      responsive: true,
      maintainAspectRatio: true
    };

    barChartOptions.datasetFill = false;
    barChart.Bar(barChartData, barChartOptions);
 
    },
    error: function(data) {
      console.log(data);
    }
  });

    
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
