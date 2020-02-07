function validateForm() {
  var mainform = "groupingrule";

  var fgroupcode = document.forms[mainform]["group_code"].value;
  if (fgroupcode == "") {
    alert("Group code is mandatory!");
    return false;
  }

  var fgroupdesc = document.forms[mainform]["group_desc"].value;
  if (fgroupdesc == "") {
    alert("Group description is mandatory!");
    return false;
  }

  var fpricegrp = document.forms[mainform]["group_price_grp"].value;
  if (fpricegrp == "") {
    alert("Price group is mandatory!");
    return false;
  }

  var fgroupselection = document.forms[mainform]["group_selection_SQL"].value;
  if (fgroupselection == "") {
    alert("Group selection group is mandatory!");
    return false;
  }

}
