<?php
    $y = mysqli_query($con,"select user_name from users where user_id='$userid'");
    $t = mysqli_fetch_array($y);
?>
<nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row" style="background-color: <?php include('extension/theme.php'); ?>;">
  <!-- logo -->
  <div class="text-left navbar-brand-wrapper">
    <a class="navbar-brand brand-logo" href="index"><h3 style="font-family: 'Monotype Corsiva'; font-weight: 900; color: <?php include('extension/theme_text.php'); ?>;"><?php include('extension/title.php'); ?></h3></a>
    <a class="navbar-brand brand-logo-mini" href="index"><img src="<?php include('extension/logo.php'); ?>" alt=""></a>
  </div>
  <!-- Top bar left -->
  <ul class="nav navbar-nav mr-auto">
    <li class="nav-item">
      <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu ti-align-right" style="color: <?php include('extension/theme_text.php'); ?>;"></i></a>
    </li>
  </ul>
  <!-- top bar right -->
  <ul class="nav navbar-nav ml-auto">
    <li class="nav-item fullscreen">
      <a id="btnFullscreen" href="#" class="nav-link" ><i class="ti-fullscreen" style="color: <?php include('extension/theme_text.php'); ?>;"></i></a>
    </li>
    <li class="nav-item dropdown ">
      <a class="nav-link top-nav" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true"> <i class=" ti-view-grid" style="color: <?php include('extension/theme_text.php'); ?>;"></i> </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-big">
        <div class="dropdown-header">
          <strong>Quick Links</strong>
        </div>
        <div class="dropdown-divider"></div> 
        <div class="nav-grid">
          <a href="create_user" class="nav-grid-item"><i class="fa fa-user-plus text-primary"></i><h5>Create User</h5></a>
          <a href="create_xpuser" class="nav-grid-item"><i class="fa fa-user-plus text-success"></i><h5>Create Export</h5></a>
        </div>
        <div class="nav-grid">
          <a href="create_reseller" class="nav-grid-item"><i class="fa fa-user-plus text-warning"></i><h5>Create Reseller</h5></a>
          <a href="transfer_credit" class="nav-grid-item"><i class="fa fa-credit-card-alt text-danger "></i><h5>Transfer Credit</h5></a>
        </div>
      </div>
    </li>
    <li class="nav-item dropdown mr-30">
      <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <img src="/assets/images/profile.png" alt="avatar">
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header">
          <div class="media">
            <div class="media-body">
              <h5 class="mt-0 mb-0">Welcome</h5>
              <span><?php echo $t['user_name']; ?></span>
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="logout.php"><i class="text-danger ti-unlock"></i>Logout</a>
      </div>
    </li>
  </ul>
</nav>