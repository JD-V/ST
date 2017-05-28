<?php
$CDATA['PAGE_NAME'] = 'USERS';
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
require '_header.php';
?>
<script type = "text/javascript">
function DisplayProof(path){
    
    var currentPath = window.location.pathname.split('/');
    
    var pathNew = currentPath.slice(0,currentPath.length-2).join('/') + '/uploads/' + path;
    window.open(pathNew);
}

function BlockUser(e,ID) {
 $.ajax({
      dataType: "json",
      type: "GET",
      url: "useroperations.php?action=Block&userID="+ID,
      success: function(result) {
       if(result.status == 1) {
          document.getElementById("messages").innerHTML = MessageTemplate(0, "User has been blocked");
          $(e).removeClass('btn-info');
          $(e).addClass('btn-danger');
          $(e).val('Unblock');
          
       } else {
          document.getElementById("messages").innerHTML = MessageTemplate(1, "unable to perform operation");
       }
      }
 });
}

function UnBlockUser(e,ID) {
 $.ajax({
      dataType: "json",
      type: "GET",
      url: "useroperations.php?action=UnBlock&userID="+ID,
      success: function(result) {
       if(result.status == 1) {
          document.getElementById("messages").innerHTML = MessageTemplate(0, "User has been un blocked");
          $(e).removeClass('btn-danger');
          $(e).addClass('btn-info');
          $(e).val('Block');
          
       } else {
          document.getElementById("messages").innerHTML = MessageTemplate(1, "unable to perform operation");
       }
      }
 });
}
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Users
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


    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">List</h3>

        <div class="box-tools pull-right">
<!--           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> -->
        </div>
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12" >
          <table id="usersTable" class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Role</th>
                <th>Phone</th>

                <th>Address</th>
                <th>Status</th>
                <th>ID Proof</th>
                <th>Block</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $users = GetUsers();
                while ($user = mysql_fetch_assoc($users)) {
                  ?>
                  <tr>
                    <th><?php echo '<a href="ManageUsers.php?id='.$user['UserID'].'">' . $user['UserID'] . '</a>'; ?></th>
                    <td><?php echo $user['Name']; ?></td>
                    <td><?php echo $user['RoleName']; ?></td>
                    <td><?php echo $user['UserPhone']; ?></td>
                    
                    <td><?php echo $user['Address'] ?></td>
                    <td><?php if($user['Status'] == '1') echo 'Active'; else echo 'Inactive'; ?></td>
                    <td>
                        <?php 
                            $target_dir = realpath(dirname(__FILE__) . '/../uploads');
                            $userID = 'user'.$user['UserID'];
                            ChromePhp::log($target_dir);
                            ChromePhp::log($userID);

                            $files = preg_grep('~^'.$userID.'~', scandir($target_dir));
                            ChromePhp::log($files);
                            foreach ($files as $key => $value)
                            {
                              echo '<input type="button" class="btn-link" value="View"  onclick = "DisplayProof(\'' . $value . '\');" />';
                            }
                        ?>
                    </td>
                    <td>
                    <?php 
                      if($user['RoleID'] != 1) {
                        $ID = $user['UserID'];
                        if($user['Status'] == '1') {
                          echo '<input type="button" class="btn btn-sm btn-info" value="Block" onclick="BlockUser(this,\''.$ID.'\')" />';
                        } else {
                          echo '<input type="button" class="btn btn-sm btn-danger" value="Unblock" onclick="UnBlockUser(this,\''.$ID.'\')" />';
                        }
                      } else echo 'NA';
                      ?>
                  </td>
                  </tr>
                  <?php
                }
              ?>
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
<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
