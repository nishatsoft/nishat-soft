<div class="card-body">
      <?php
                        {
                            $query_user1 = mysqli_query($con,"select * from admin");
                            $result1 = mysqli_fetch_array($query_user1);
                            $alert_name = $result1['alert_name'];
                        }
                        ?>
      <div class="alert alert-<?php echo $alert_name; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h5><i class="icon fas fa-ban"></i> Announcement!</h5>
          <?php
                        {
                            $query_user1 = mysqli_query($con,"select * from admin");
                            $result1 = mysqli_fetch_array($query_user1);
                            $announce_name = $result1['announce_name'];
                        }
                        ?>

          <hr>
          <?php echo $announce_name; ?>
          <hr>
                  
          </div>
                
    </div>