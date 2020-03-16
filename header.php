<?php
  // First we start a session which allow for us to store information as SESSION variables.
  session_start();

  // "require" creates an error message and stops the script. "include" creates an error and continues the script.
  require "db/db.php";
  require "common/ypflib.php";
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Mikro-Aitta Pricing</title>
    <link rel="icon" href="img/logo[1].png" type="image/png" sizes="16x16">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
  </head>

  <body>

<!--
Header
-->
    <header class="header-flex-container">
      <div>
        <a class="header-logo" href="index.php">
          <img src="img/logo[1].png" style="width:60px;height:40px;">
        </a>
      </div>
      <div style="flex-grow: 12">
        Mikro-Aitta
      </div>
      <!--div style="flex-grow: 1">
        Notifications
      </div>
      <div style="flex-grow: 1">
        Ohje
      </div>
      <div style="flex-grow: 6">
        Käyttäjän tiedot
      </div -->
      <div  class="header-login" >
        <!--
        Here is the HTML login form.
        Notice that the "method" is set to "post" because the data we send is sensitive data.
        The "inputs" I decided to have in the form include username/e-mail and password. The user will be able to choose whether to login using e-mail or username.
        Also notice that using PHP, we can choose whether or not to show the login/signup form, or to show the logout form, if we are logged in or not. We do this based on SESSION variables which I explain in more detail in the login.inc.php file!
        -->
        <?php
        if (!isset($_SESSION['id'])) {
          echo '<form action="includes/loginyp.php" method="post">
            <input type="text" name="user" placeholder="User">
            <input type="password" name="pword" placeholder="Password">
            <button type="submit" class="button button--login" name="login-submit">Login</button>
          </form>
          <a href="signup.php" class="header-signup">Signup</a>';
        }
        else if (isset($_SESSION['id'])) {
          echo '<form action="includes/logoutyp.php" method="post">
            <button type="submit" class="button button--login" name="login-submit">Logout</button>
          </form>';
        }
        ?>
      </div>
    </header>

<?php
if (isset($_SESSION['id'])) {
  require "navbar.php";
}
?>
