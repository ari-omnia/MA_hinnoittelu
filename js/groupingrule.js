function validateForm() {
  var mainform = "groupingrule";

  var grouping_code = document.forms[mainform]["grouping_code"].value;
  if (grouping_code == "") {
    alert("Group code is mandatory!");
    return false;
  }

  var grouping_desc = document.forms[mainform]["grouping_desc"].value;
  if (grouping_desc == "") {
    alert("Group description is mandatory!");
    return false;
  }

  var price_group = document.forms[mainform]["price_group"].value;
  if (price_group == "") {
    alert("Price group is mandatory!");
    return false;
  }

  var grouping_SQL_selection = document.forms[mainform]["grouping_SQL_selection"].value;
  if (grouping_SQL_selection == "") {
    alert("Group selection group is mandatory!");
    return false;
  }

}
