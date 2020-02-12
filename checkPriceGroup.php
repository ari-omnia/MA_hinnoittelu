<?php

  require 'db/db.php';

  $q = $_GET['q'];
  $sql = "SELECT * from pricegroups where prcgrp_code = '$q'";

  $result = mysqli_query($conn, $sql);

  if($result->num_rows > 0) {
    echo "Price Group Code already exists";
    ?>
    <script>
      var result = true;
    </script>
    <?php
  }
  else {
    echo " pp";
    ?>
    <script>
      var result = false;
    </script>
    <?php
  }

mysqli_close($conn);
