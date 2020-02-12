<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="stylea.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
      $(document).ready(function() {
        $("form").submit(function(event) {
          event.preventDefault();
          var name = $("#mail-name").val();
          var address = $("#mail-address").val();
          var submit = $("#mail-submit").val();
          $(".form-message").load("mail.php", {
            name: name,
            address: address,
            submit: submit
          });
        })
      });
    </script>
  </head>
  <body>
    <form>
      <input id="mail-name" type="text" name="name" placeholder="Full name">
      <p id="name-error"></span></p>
      <br>
      <input id="mail-address" type="text" name="address" placeholder="Street address">
      <p id="address-error"></span></p>
      <br>

      <textarea id="mail-message" name="message" placeholder="Message"></textarea>
      <br>
      <button id="mail-submit" type="submit" name="submit">Send</button>
      <p class="form-message"></p>
    </form>
  </body>
</html>
