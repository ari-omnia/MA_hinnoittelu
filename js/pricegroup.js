function validateForm() {
  var mainform = "pricegroup";

  var price_group_code = document.forms[mainform]["price_group_code"].value;
  if (price_group_code == "") {
    alert("Price group code is mandatory!");
    return false;
  }

  var price_group_desc = document.forms[mainform]["price_group_desc"].value;
  if (price_group_desc == "") {
    alert("Group description is mandatory!");
    return false;
  }

  var sales_price_factor = document.forms[mainform]["sales_price_factor"].value;
  if (sales_price_factor == "") {
    alert("Percentage is mandatory!");
    return false;
  }

  var fixed_sum_to_price = document.forms[mainform]["fixed_sum_to_price"].value;
  if (fixed_sum_to_price == "") {
    alert("Fixed fee is mandatory!");
    return false;
  }

}
