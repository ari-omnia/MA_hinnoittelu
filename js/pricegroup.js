function validateForm() {
  var mainform = "pricegroup";

  var fprcgrpcde = document.forms[mainform]["prcgrp_code"].value;
  if (fprcgrpcde == "") {
    alert("Price group code is mandatory!");
    return false;
  }
  else {
    alert("aa");
    checkPriceGroup(fprcgrpcde);
    var result = document.getElementById("txtHint").textContent;
    alert(result);

  }

  var fprcgrpname = document.forms[mainform]["prcgrp_name"].value;
  if (fprcgrpname == "") {
    alert("Group description is mandatory!");
    return false;
  }

  var fprcgrpaddpercentage = document.forms[mainform]["prcgrp_add_percentage"].value;
  if (fprcgrpaddpercentage == "") {
    alert("Percentage is mandatory!");
    return false;
  }

  var fprcgrpaddpfixedfee = document.forms[mainform]["prcgrp_add_fixedfee"].value;
  if (fprcgrpaddpfixedfee == "") {
    alert("Fixed fee is mandatory!");
    return false;
  }

}

function checkPriceGroup(str) {
  var xhttp;
  if (str == "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("txtHint").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "checkPriceGroup.php?q="+str, true);
  xhttp.send();

}
