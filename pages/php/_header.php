<?php
// if(@$_SESSION['thumbnailphoto'] != "")
//   $userimage = "data:image/jpeg;base64," . base64_encode($_SESSION['thumbnailphoto']);
// else
  $userimage = "../../dist/img/avatar04.png"
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inventory Management System</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
	<!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Angular js 1.4.8 -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>


<!-- loads jquery and jquery ui -->
<!-- -->
<script type="text/javascript" src="../../plugins/MultiDatesPicker/js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../../plugins/MultiDatesPicker/js/jquery-ui-1.11.1.js"></script>
<script type="text/javascript" src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!--
<script type="text/javascript" src="js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.11.1.js"></script>-->

<!--  loads mdp -->
<script type="text/javascript" src="../../plugins/MultiDatesPicker/jquery-ui.multidatespicker.js"></script>


<!-- Bootstrap multiselect -->
<!-- <script src="../../dist/css/bootstrap-multiselect.css"></script> -->

<script type="text/javascript">


  // function DispalayEventHeader()
  // {
  //   var xhttp = new XMLHttpRequest();
  //       xhttp.onreadystatechange = function() {
  //        if (this.readyState == 4 && this.status == 200)
  //         document.getElementById("MainEventInfo").innerHTML  = this.responseText;
  //       };
  //        xhttp.open("GET", "storeOrGetCurrentEventId.php?for=Pages", true);
  //        xhttp.send();
  // }

  // window.addEventListener('load',DispalayEventHeader());


</script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition fixed sidebar-mini" onload="clearOnly()">

  <!-- attend now modal -->
  <!-- <div class="modal fade" id="attendNowModal" tabindex="-1" role="dialog" aria-labelledby="attendNowModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="attendNowModalLabel">Attend Now</h4>
        </div>

        <div class="modal-body">
          <form class='attendNow'>
            <div class="form-group">
              <label for="eventCode" class="control-label">Event Code</label>
              <input type="text" class="form-control" id="eventCode" name="eventCode" placeholder="Event Code"
                required="true" maxlength="10" />
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='attendNowSubmit'>Submit</button>
        </div>
      </div>
    </div>
  </div> -->

<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IMS</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Inventory</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- <li>
            <a href='#' data-toggle="modal" data-target="#attendNowModal">
              <i class='glyphicon glyphicon-flag' style="margin-right: 2px;"></i>
              Attend Now
            </a>
          </li> -->

          <!-- Notifications: style can be found in dropdown.less -->
<li class="dropdown notifications-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-bell-o"></i>
    <span class="label label-warning">10</span>
  </a>
  <ul class="dropdown-menu">
    <li class="header">You have 10 notifications</li>
    <li>
      <!-- inner menu: contains the actual data -->
      <ul class="menu">
        <li>
          <a href="#">
            <i class="fa fa-users text-aqua"></i> 5 new members joined today
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
            page and may cause design problems
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-users text-red"></i> 5 new members joined
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-shopping-cart text-green"></i> 25 sales made
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-user text-red"></i> You changed your username
          </a>
        </li>
      </ul>
    </li>
    <li class="footer"><a href="#">View all</a></li>
  </ul>
</li>


          <li>
            <a href="logout.php">
              <i class="glyphicon glyphicon-off" style="margin-right: 2px;" ></i>
              Logoff
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src=<?php echo "\"" . $userimage  . "\""?> class="img-circle" alt="User Image" />
        </div>
        <div class="pull-left info">
            <p><?php getUserRole() ?></p>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>

        <li class="<?php if($CDATA['PAGE_NAME'] == 'DASHBOARD'){ echo 'active'; } ?> hover">
          <a href="../../index.php">
            <i class="fa fa-tachometer"></i>
            <span> Dashboard </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
        </li>
        <?php
          $userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID ==1 )  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'MNUSER' || $CDATA['PAGE_NAME'] == 'MNGLOC' ) {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
        ?>
        <li class="treeview <?php echo $isActiveTV ?>" >
          <a href="#">
            <i class="fa fa-cogs"></i>
            <span>Manage</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <?php $isActive = ""; if($CDATA['PAGE_NAME'] == 'MNUSER'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"ManageUsers.php\" >
                      <i class=\"fa fa-user\"></i>
                      <span>Users</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

            $isActive = "";
           if($CDATA['PAGE_NAME'] == 'MNGLOC') { $isActive =  'active';  }
           ChromePhp::log('isa active '. $CDATA['PAGE_NAME'] );

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"ManageLocation.php\" >
                        <i class=\"fa fa-location-arrow\"></i>
                        <span>Office Locations</span>
                        <span class=\"pull-right-container\">
                        <i class=\"fa fa-angle-right pull-right\"></i>
                        </span>
                      </a>
                    </li>";
            ?>

          </ul>
        </li>
        <?php } ?>

        <?php
          $userRoleID = getUserRoleID();
          if($userRoleID == 1)	// Admin role 1
          {
            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'ADSRVREC'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"addservicerecord.php\" >
                      <i class=\"fa fa-plus-square\"></i>
                      <span>Add Service Record</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'ADNONBILLREC'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"addnonbillable.php\" >
                      <i class=\"fa fa-plus-square\"></i>
                      <span>Add Non billable record</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'SERVICES'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"services.php\" >
                      <i class=\"fa fa-user\"></i>
                      <span>Services</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'NONBILLREC'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"nonbillable.php\" >
                      <i class=\"fa fa-user\"></i>
                      <span>Non billable</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

          }
          ?>


        <li class="header">REPORTS</li>

        <li class="<?php if($CDATA['PAGE_NAME'] == 'SUMMARY'){ echo 'active'; } ?> hover">
            <a href="
							<?php
								$userRoleID = getUserRoleID();
											if($userRoleID == 1)	// Admin role 1
												echo "summary_Admin.php";
											else                      // Associate role 4
												echo "summaryAssociate.php";
											?>" >
                <i class="fa fa-file-text"></i>
                <span>Summary</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-right pull-right"></i>
                </span>
            </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
