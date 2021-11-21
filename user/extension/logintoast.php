<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>

<?php
      if(isset($status2)){
          echo "<script>".$status2.";setTimeout(function() {
  window.location.href = 'dashboard';
});</script>";


          
    
      }
      ?>