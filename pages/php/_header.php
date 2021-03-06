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

<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js"></script>

<!--  loads mdp -->
<!--<script type="text/javascript" src="../../plugins/MultiDatesPicker/jquery-ui.multidatespicker.js"></script>-->

</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition fixed sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IMS</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Pikes <i>Ace</i></b></span>
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
        <script type="text/javascript">
          $.ajax({
            dataType: "json",
            type: "GET",
            url: "AddUpdateRetriveStokcs.php?action=Retrive",
            success: function(result) {
              var count =0;
              var StockArray= result;
              //console.log(StockArray);
              $.each(StockArray, function( key, value ) {
                var Qty= parseInt(value.Qty)
                if(Qty != NaN && Qty>3) { 
                  //console.log( value.ProductName + " : green : " + value.Qty );
                }
                else if(Qty != NaN && Qty>0) {
                  count++;
                  // console.log( value.ProductName + " : yellow : " + value.Qty );
                  $('.notifications').append(
                    $('<li/>').append(
                      $('<a/>', {'href': 'manageStock.php?product='+value.ProductDisplay}).append(
                        $('<i/>', {'class': 'fa fa-warning text-yellow'})
                      ).append(value.ProductDisplay+' has only ' + value.Qty + ' Nos Left')
                    )
                );             
                }
                else {
                  count++;
                  // console.log( value.ProductName + " : red : " + value.Qty );
                  $('.notifications').append(
                    $('<li/>').append(
                      $('<a/>', {'href': 'manageStock.php?product='+value.ProductDisplay}).append(
                        $('<i/>', {'class': 'fa fa-ban text-red'})
                      ).append(value.ProductDisplay +' is out of stock')
                    )
                );    
                }
              });

              $('.notification-count-message').html('You have ' + count + ' notificaitons');
              $('.notification-count').html(count);
            }
          });
                
        </script>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning notification-count">0</span>
            </a>
            <ul class="dropdown-menu" style="width:400px">
              <li class="header notification-count-message">You have 0  notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu notifications">
                </ul>
              </li>
              <li class="footer"><a href="notifications.php">View all</a></li>
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
          <a href="dashboard.php">
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
          if($userRoleID <=2 )  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'MNUSER' || $CDATA['PAGE_NAME'] == 'USERS' ) {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
        ?>

        <li class="treeview <?php echo $isActiveTV ?>" >
          <a href="#">
            <i class="fa fa-user"></i>
            <span>Manage Users</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <?php $isActive = ""; if($CDATA['PAGE_NAME'] == 'USERS'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"users.php\" >
                      <i class=\"fa fa-user\"></i>
                      <span>Users</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'MNUSER') { $isActive =  'active';  }

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"ManageUsers.php\" >
                        <i class=\"fa fa-plus-square\"></i>
                        <span>Add User</span>
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
          $isActiveTV = "";
          if($userRoleID <=2 )  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'MNGLOC' || $CDATA['PAGE_NAME'] == 'MNSRVS' || $CDATA['PAGE_NAME']   == 'MNGBRAND') {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
        ?>
        <li class="treeview <?php echo $isActiveTV ?>" >
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Manage</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <?php 

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

                     $isActive = "";
           if($CDATA['PAGE_NAME'] == 'MNGBRAND') { $isActive =  'active';  }
           ChromePhp::log('isa active '. $CDATA['PAGE_NAME'] );

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"ManageBrand.php\" >
                        <i class=\"fa fa-tags\"></i>
                        <span>Brands</span>
                        <span class=\"pull-right-container\">
                        <i class=\"fa fa-angle-right pull-right\"></i>
                        </span>
                      </a>
                    </li>";


            $isActive = "";
           if($CDATA['PAGE_NAME'] == 'MNSRVS') { $isActive =  'active';  }
           ChromePhp::log('isa active '. $CDATA['PAGE_NAME'] );

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"manageservicable.php\" >
                        <i class=\"fa fa-crosshairs\"></i>
                        <span>Manage Servicable</span>
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
          //$userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID <=2 )  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'SUPPLIER' || $CDATA['PAGE_NAME'] == 'ADDSUPPLIER' ) {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
          ?>

        <li class="treeview <?php echo $isActiveTV ?>" >
            <a href="#">
              <i class="fa fa-industry"></i>
              <span>Suppliers</span>
              <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
            <?php
            
            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'SUPPLIER'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"supplier.php\" >
                      <i class=\"fa fa-list\"></i>
                      <span>Supplier</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";
            
            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'ADDSUPPLIER'){ $isActive =  'active'; }
            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"addsupplier.php\" >
                      <i class=\"fa fa-plus-square\"></i>
                      <span>Add Supplier</span>
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
          //$userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID <=2 )  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'INVOICE' || $CDATA['PAGE_NAME'] == 'ADDINV' ) {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
        ?>

        <li class="treeview <?php echo $isActiveTV ?>" >
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Purchase Invoices</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <?php $isActive = ""; if($CDATA['PAGE_NAME'] == 'INVOICE'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"invoices.php\" >
                      <i class=\"fa fa-list\"></i>
                      <span>Invoices</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'ADDINV') { $isActive =  'active';  }
           ChromePhp::log('isa active '. $CDATA['PAGE_NAME'] );

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"addInvoice.php\" >
                        <i class=\"fa fa-plus-square\"></i>
                        <span>Add Invoice</span>
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
          //$userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID <=2 )  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'ADDPRD' || $CDATA['PAGE_NAME'] == 'INVPRDS') {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
        ?>

        <li class="treeview <?php echo $isActiveTV ?>" >
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Products Inventory</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <?php $isActive = ""; if($CDATA['PAGE_NAME'] == 'INVPRDS'){ $isActive =  'active'; }

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'INVPRDS') { $isActive =  'active'; ChromePhp::log('isa active '. $CDATA['PAGE_NAME'] );  }
          
           echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"inventory.php\" >
                      <i class=\"fa fa-list\"></i>
                      <span>Products</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'ADDPRD') { $isActive =  'active';  }
           ChromePhp::log('isa active '. $CDATA['PAGE_NAME'] );

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"addproduct.php\" >
                        <i class=\"fa fa-plus-square\"></i>
                        <span>Add Product</span>
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
          //$userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID <=2 )  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'MNSTOCK' || $CDATA['PAGE_NAME'] == 'STOCKENT' || $CDATA['PAGE_NAME'] == 'ADDSTOCK') {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
        ?>

        <li class="treeview <?php echo $isActiveTV ?>" >
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Manage Stocks</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

          <?php $isActive = ""; if($CDATA['PAGE_NAME'] == 'MNSTOCK'){ $isActive =  'active'; }

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'MNSTOCK') { $isActive =  'active'; }
          
           echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"manageStock.php\" >
                      <i class=\"fa fa-th-large\"></i>
                      <span>Available Stock</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'STOCKENT') { $isActive =  'active';  }

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"transactions.php\" >
                        <i class=\"fa fa-list\"></i>
                        <span>Transactions</span>
                        <span class=\"pull-right-container\">
                        <i class=\"fa fa-angle-right pull-right\"></i>
                        </span>
                      </a>
                    </li>";
                    
          // $isActive = "";
          // if($CDATA['PAGE_NAME'] == 'ADDSTOCK') { $isActive =  'active';  }
  
          //       echo "<li class=\"hover " . $isActive . " \">
          //               <a href=\"addstock.php\" >
          //                 <i class=\"fa fa-plus\"></i>
          //                 <span>Add Stock</span>
          //                 <span class=\"pull-right-container\">
          //                 <i class=\"fa fa-angle-right pull-right\"></i>
          //                 </span>
          //               </a>
          //             </li>";                                                      
            ?>

          </ul>
        </li>
        <?php 
        } else {
           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'MNSTOCK') { $isActive =  'active'; }
          
           echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"manageStock.php\" >
                      <i class=\"fa fa-th-large\"></i>
                      <span>Available Stock</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";
        
          }
      ?>

      <?php
          //$userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID <=2 || $userRoleID ==3)  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'ADSLSREC' || $CDATA['PAGE_NAME'] == 'ORDERS') {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
        ?>

        <li class="treeview <?php echo $isActiveTV ?>" >
          <a href="#">
            <i class="fa fa-sellsy"></i>
            <span>Sales</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

          <?php

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'ADSLSREC') { $isActive =  'active'; }
          
           echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"addneworder.php\" >
                      <i class=\"fa fa-plus-square\"></i>
                      <span>Add new Order</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";

           $isActive = "";
           if($CDATA['PAGE_NAME'] == 'ORDERS') { $isActive =  'active';  }
           ChromePhp::log('isa active '. $CDATA['PAGE_NAME'] );

              echo "<li class=\"hover " . $isActive . " \">
                      <a href=\"orders.php\" >
                        <i class=\"fa fa-list\"></i>
                        <span>Orders</span>
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
          //$userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID <=2 || $userRoleID ==3)  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'ADSRVREC' || $CDATA['PAGE_NAME'] == 'SERVICES' ) {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
          ?>
          <li class="treeview <?php echo $isActiveTV ?>" >
            <a href="#">
              <i class="fa fa-car"></i>
              <span>Services</span>
              <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

            <?php $isActive = ""; if($CDATA['PAGE_NAME'] == 'SERVICES'){ $isActive =  'active'; }
        

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"services.php\" >
                      <i class=\"fa fa-list\"></i>
                      <span>Services</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";
                  
                  
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
          ?>
          </ul>
        </li>
        <?php } ?>

         <?php
          //$userRoleID = getUserRoleID();
          $isActiveTV = "";
          if($userRoleID <=2 || $userRoleID ==3)  // Admin role 1
          {
            if($CDATA['PAGE_NAME'] == 'ADNONBILLREC' || $CDATA['PAGE_NAME'] == 'NONBILLREC' ) {
              $isActiveTV =  'active';
            } else {
              $isActiveTV = "";
            }
          ?>
          <li class="treeview <?php echo $isActiveTV ?>" >
            <a href="#">
              <i class="fa fa-thumb-tack"></i>
              <span>NonBillables</span>
              <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
            <?php
            
            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'NONBILLREC'){ $isActive =  'active'; }

            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"nonbillable.php\" >
                      <i class=\"fa fa-list\"></i>
                      <span>Non billable</span>
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
          ?>
        
          </ul>
        </li>
      <?php } ?>
      <?php
        if($userRoleID <=2)  // Admin role 1
          {
            echo "<li class=\"header\">REPORTS</li>";
            $isActive = "";
            if($CDATA['PAGE_NAME'] == 'REPORTS'){ $isActive =  'active'; }
            echo "<li class=\"hover " . $isActive . " \">
                    <a href=\"reports.php\" >
                      <i class=\"fa fa-file-text\"></i>
                      <span>Reports</span>
                      <span class=\"pull-right-container\">
                      <i class=\"fa fa-angle-right pull-right\"></i>
                      </span>
                    </a>
                  </li>";
          }
        ?>
       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
