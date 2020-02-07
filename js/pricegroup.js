function validateForm() {
  var mainform = "pricegroup";

  var fprcgrpcde = document.forms[mainform]["prcgrp_code"].value;
  if (fprcgrpcde == "") {
    alert("Price group code is mandatory!");
    return false;
  }

  var fprcgrpname = document.forms[mainform]["prcgrpname"].value;
  if (fprcgrpname == "") {
    alert("Group description is mandatory!");
    return false;
  }

  var fprcgrpformula = document.forms[mainform]["prcgrp_formula"].value;
  if (fprcgrpformula == "") {
    alert("Price group formula is mandatory!");
    return false;
  }

}
