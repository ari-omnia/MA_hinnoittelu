<?php
  require "header.php";
?>

    <!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
    <div class="container">
      <p>Tervetuloa nnnnnn yhdistyksen jäsenrekisterin ja</p>
      <p>Kalustoluettolon ylläpitoon</p>
      <?php
        if (!isset($_SESSION['id'])) {
          echo '<p class="login-status">You are logged out!</p>';
        }
        else if (isset($_SESSION['id'])) {
          echo '<p class="login-status">You are logged in!</p>';
        }
      ?>
    </div>

<?php
  require "footer.php";
?>
