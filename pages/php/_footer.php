<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<!--<a href="https://www.facebook.com/jrvaghasiya" target="_blank"><i class="ace-icon fa fa-facebook-square text-primary pikesAceFooterIcons"></i></a>-->
	</div>
	<span>
		<a href="http://www.pikesace.com" target="_blank">
		<span class="pikesAceFooter">
			<strong>PIKES <i>ACE</i> </strong>
		</span>
		</a>&copy; <?php echo date('Y')  ?>
	</span> 
</footer>

<!-- Add the sidebar's background. This div must be placed
		 immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<!--for date time picker-->
<script type="text/javascript" src="../../dist/js/jquery.simple-dtpicker.js"></script>
<script type="text/javascript" src="../../dist/js/typeahead.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validation-unobtrusive/3.2.6/jquery.validate.unobtrusive.min.js"></script>
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="../../plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- <script src="../js/dashboardJS.js"></script>
 -->
<script type="text/javascript" src="../js/dashboardJS.js"></script>
<script src="../js/codefellas.js"></script>
<!-- <script src="../../dist/js/jquery.searchabledropdown-1.0.8.min.js"></script> -->
<script src="../js/Forms.js"></script>
<!-- <script src="../js/multiselect.js"></script> -->
<!--for date time picker-->
<link type="text/css" href="../../dist/css/jquery.simple-dtpicker.css" rel="stylesheet" />

<!-- time picker -- >
<link type="text/css" href="../../plugins/timepicker/bootstrap-timepicker.min.css" />

<!-- custom styles -->
<link rel="stylesheet" type="text/css" href="../css/mainCSS.css">

<!-- custom styles -->
<!--<link rel="stylesheet" type="text/css" href="../css/timelineBS.css">-->

<!--multiple date selector -->
<!--<link rel="stylesheet" type="text/css" href="../../plugins/MultiDatesPicker/css/mdp.css">
<link rel="stylesheet" type="text/css" href="../../plugins/MultiDatesPicker/css/prettify.css">
<script type="text/javascript" src="../../plugins/MultiDatesPicker/js/prettify.js"></script>
<script type="text/javascript" src="../../plugins/MultiDatesPicker/js/lang-css.js"></script>-->

<script>
$(document).ready(function() {
	var roleN = "<?php echo getUserRoleID(); ?>";
	$("body").removeClass (function (index, css) {
    return (css.match (/(^|\s)skin-\S+/g) || []).join(' ');
	});
	if(roleN == 1){
		$("body").addClass("skin-yellow");
	} else if (roleN == 2) {
		$("body").addClass("skin-purple");
	} else if (roleN == 3) {
		$("body").addClass("skin-green");
	}
});
</script>
</body>
</html>
