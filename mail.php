<?php

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $address = $_POST['address'];

  $errorName = false;
  $errorAddress = false;
  $errorForm = false;

  if (empty($name)) {
    $errorName = true;
    $errorForm = true;
  }
  if (empty($address)) {
    $errorAddress = true;
    $errorForm = true;
  }

  if ($errorForm) {
    echo "<span class = 'form-error'>Correct errors!</span>";
  }
}
else {
  echo "There was an error!";
}
?>

<script>
  $("#mail-name").removeClass("input-error");
  $("#mail-address").removeClass("input-error");
  var alertText = "Virhe!\n";

  // Handle Name error
  var $errorName = "<?php echo $errorName; ?>";
  if ($errorName == true) {
    $("#mail-name").addClass("input-error");
    //document.getElementById("name-error").innerHTML = "Name is mandatory!";
    alert("Name is mandatory");
    alertText = alertText + "Name is mandatory\n";
  }

  // Handle Address error
  var $errorAddress = "<?php echo $errorAddress; ?>";
  if ($errorAddress == true) {
    $("#mail-address").addClass("input-error");
    //document.getElementById("name-error").innerHTML = "Name is mandatory!";
    alert("Address is mandatory");
    alertText = alertText + "Address is mandatory\n";
  }
  if (alertText != "Virhe!\n") {
    alert(alertText);
  }

  if ($errorName == false) {
    $("#mail-name").val("");
  }
  if ($errorAddress == false) {
    $("#mail-address").val("");
  }

</script>
