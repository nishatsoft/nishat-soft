<?php
    $rank_check = mysqli_query($con,"select user_rank from users where user_id='$userid'");
    $myrank = mysqli_fetch_array($rank_check);
    $user_rank = $myrank['user_rank'];
?>
<!-- Left Sidebar start-->
    <div class="side-menu-fixed">
        <div class="scrollbar side-menu-bg">
            <ul class="nav navbar-nav side-menu" id="sidebarnav">
                <!-- menu item Dashboard-->
                <li>
                  <a href="dashboard"><i class="ti-blackboard"></i><span class="right-nav-text">Dashboard</span></a>
                </li>
        
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#multi-level"><div class="pull-left"><i class="ti-layers"></i><span class="right-nav-text">Client Tools</span></div><div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                    <ul id="multi-level" class="collapse" data-parent="#sidebarnav">
              
                        <li>
                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#users">Users<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                            <ul id="users" class="collapse">
                                <li><a href="view_users">View Users</a></li>
                                <li><a href="create_user">Create User</a></li>
                            </ul>
                        </li>
              
                        <li>
                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#resellers">Resellers<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                            <ul id="resellers" class="collapse">
                                <li><a href="view_resellers">View Resellers</a></li>
                                <li><a href="create_reseller">Create Reseller</a></li>
                                <li><a href="transfer_credit">Transfer Credit</a></li>
                            </ul>
                        </li>
              
                        <li>
                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#export">Export Users<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                            <ul id="export" class="collapse">
                                <li><a href="view_xpusers">View Export Users</a></li>
                                <li><a href="create_xpuser">Create Export User</a></li>
                                <li><a href="down_xpfiles">Download Export Users</a></li>
                            </ul>
                        </li>
              
                    </ul>
                </li>
                <?php if($user_rank == 'superadmin'){ ?>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#multi-level2"><div class="pull-left"><i class="ti-layers"></i><span class="right-nav-text">Panel Tools</span></div><div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                    <ul id="multi-level2" class="collapse" data-parent="#sidebarnav">
              
                        <li>
                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#updater">Updater<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                            <ul id="updater" class="collapse">
                                <li><a href="create_update">Create Update</a></li>
                                <li><a href="view_update">View Update</a></li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#dns">DNS<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                            <ul id="dns" class="collapse">
                                <li><a href="view_dns">View DNS</a></li>
                                <li><a href="create_dns">Create DNS</a></li>
                            </ul>
                        </li>
          
                        <li>
                            <a href="servers"><i class="ti-harddrives"></i><span class="right-nav-text">Servers</span></a>
                        </li>
                        
                        <li>
                            <a href="notice"><i class="ti-bookmark-alt"></i><span class="right-nav-text">Notice</span></a>
                        </li>
                        
                        <li>
                            <a href="application"><i class="ti-tablet"></i><span class="right-nav-text">Application</span></a>
                        </li>
          
                        <li>
                            <a href="site_settings"><i class="ti-settings"></i><span class="right-nav-text">Site Settings</span></a>
                        </li>
                        
                        <li>
                            <a href="dns_settings"><i class="ti-layers-alt"></i><span class="right-nav-text">DNS Settings</span></a>
                        </li>
              
                    </ul>
                </li>
                <?php } ?>
                <li>
                    <a href="credit_log"><i class="ti-agenda"></i><span class="right-nav-text">Transaction Log</span></a>
                </li> 
                
                <li>
                    <a href="history"><i class="ti-agenda"></i><span class="right-nav-text">Activity Log</span></a>
                </li> 
          
                <li>
                    <a href="change_password"><i class="ti-user"></i><span class="right-nav-text">Change Password</span></a>
                </li>
          
            </ul>
        </div> 
    </div> 
<!-- Left Sidebar End-->