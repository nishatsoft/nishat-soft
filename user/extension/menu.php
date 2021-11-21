<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->


<!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/snfx.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <?php
            $query = mysqli_query($con,"select * from users where user_name='$userid'");
            $result = mysqli_fetch_array($query);
            ?>
        <div class="info">
          <a href="settings" class="d-block"><?php echo $result['full_name']; ?> 
          <?php
    $query = mysqli_query($con,"select * from users where user_name='$userid' && user_rank>'0' limit 1");
      if(mysqli_num_rows($query)>0){
        $i=1;

?>
          <i class="fas fa-user-edit nav-icon text-warning" title="Edit Profile"></i></a>

           <?php
  $i++;
    }
      else{
    ?>

    <?php
  }
?>
        </div>
      </div>

         <!-- Sidebar Menu -->
      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-header">Dashboard</li>
          <li class="nav-item">
            <a href="index" class="nav-link">
              <i class="nav-icon fa fa-home"></i>
              <p>
                My Home
              </p>
            </a>
          </li>

          <?php
    $query = mysqli_query($con,"select * from users where user_name='$userid' && user_rank>'0' limit 1");
      if(mysqli_num_rows($query)>0){
        $i=1;

?>
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-user text-warning"></i>
              <p>
                Client Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="client-create" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Create Client</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="client-bulk" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Create Bulk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="client-list" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>My Client List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="client-bulk-list" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>My Bulk List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="client-freeze" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Freeze Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="client-delete" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Delete Account</p>
                </a>
              </li>
            </ul>
          </li>

    <?php
  $i++;
    }
      else{
    ?>

    <?php
  }
?>
          
          

          <li class="nav-header">Staff Management</li>

          <?php
    $query = mysqli_query($con,"select * from users where user_name='$userid' && user_rank>'1' limit 1");
      if(mysqli_num_rows($query)>0){
        $i=1;

?>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-user text-info"></i>
              <p>
                Sub Reseller Selection
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="subreseller-create" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Create New Sub Reseller</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="subreseller-list" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>My Sub Reseller List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="subreseller-freeze" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Freeze Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="subreseller-voucher" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Add Voucher</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="subreseller-delete" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Delete Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="subreseller-password" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Change Password</p>
                </a>
              </li>
            </ul>
          </li>

<?php
  $i++;
    }
      else{
    ?>

    <?php
  }
?>



<?php
    $query = mysqli_query($con,"select * from users where user_name='$userid' && user_rank>'2' limit 1");
      if(mysqli_num_rows($query)>0){
        $i=1;

?>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-user text-green"></i>
              <p>
                Reseller Selection
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="reseller-create" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Create New Reseller</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reseller-list" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>My Reseller List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reseller-freeze" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Freeze Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reseller-voucher" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Add Voucher</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reseller-delete" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Delete Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reseller-password" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Change Password</p>
                </a>
              </li>
            </ul>
          </li>

<?php
  $i++;
    }
      else{
    ?>

    <?php
  }
?>

<?php
    $query = mysqli_query($con,"select * from users where user_name='$userid' && user_rank>'3' limit 1");
      if(mysqli_num_rows($query)>0){
        $i=1;

?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-user text-danger"></i>
              <p>
                Admin Selection
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="admin-create" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Create New Admin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="admin-list" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>My Admin List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="admin-freeze" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Freeze Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="admin-voucher" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Add Voucher</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="admin-delete" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Delete Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="admin-password" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Change Password</p>
                </a>
              </li>
            </ul>
          </li>

<?php
  $i++;
    }
      else{
    ?>

    <?php
  }
?>


<?php
    $query = mysqli_query($con,"select * from users where user_name='$userid' && user_rank>'3' limit 1");
      if(mysqli_num_rows($query)>0){
        $i=1;

?>
          <li class="nav-header">System Management</li>
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-globe text-info"></i>
              <p>
                Site Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="website-management" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Website Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="announcement" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Announcement</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="adsense" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Adsense Setup</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="server-script" class="nav-link">
                  <i class="fa fa-server nav-icon text-warning"></i>
                  <p>Server Installer Script</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="server-monitoring" class="nav-link">
                  <i class="fa fa-server nav-icon text-warning"></i>
                  <p>Server Monitoring Area</p>
                </a>
              </li>
            </ul>
          </li>

<?php
  $i++;
    }
      else{
    ?>

    <?php
  }
?>

          <li class="nav-item">
            <a href="exit" class="nav-link">
              <i class="nav-icon fa fa-power-off text-danger"></i>
              <p>
                Account Logout
              </p>
            </a>
          </li>



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
     
    </div>
    <!-- /.sidebar -->
</aside>