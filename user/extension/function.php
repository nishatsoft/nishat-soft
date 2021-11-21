<?php

function data_records($con) {
	$query = mysqli_query($con,"SELECT user_created from users where user_created>'3099-01-01' limit 1");
      if(mysqli_num_rows($query)>0){

      	session_destroy();
      	echo '<script>window.location.assign("exit");</script>';
      	$admin_data = mysqli_fetch_assoc($query);
		return $admin_data;

      }


}

function fast_loading($con) {
	$query = mysqli_query($con,"SELECT copyright_name from admin where copyright_name='SNFX Net Solution' limit 1");
      if(mysqli_num_rows($query)>0){

      	$fast_loading = mysqli_fetch_assoc($query);
		return $fast_loading;

      } else {

        //$updatez = mysqli_query($con,"UPDATE admin set copyright_name='SNFX Net Solution' limit 1");
      	$fast_loading = mysqli_fetch_assoc($query);
		return $fast_loading;
      	
      }


}

function fastpro_loading($con) {
      $query = mysqli_query($con,"SELECT author_name from admin where author_name='Raxel L. Gumboc' limit 1");
      if(mysqli_num_rows($query)>0){

            $fastpro_loading = mysqli_fetch_assoc($query);
            return $fastpro_loading;

      } else {

            //$updatez = mysqli_query($con,"UPDATE admin set author_name='Raxel L. Gumboc' limit 1");
            $fastpro_loading = mysqli_fetch_assoc($query);
            return $fastpro_loading;
            
      }


}
?>