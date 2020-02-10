function validateForm() {
  var mainform = "pricing";

  var fprcupdfile = document.forms[mainform]["prcupd_file"].value;
  if (fprcupdfile == "") {
    alert("Pricing file is mandatory!");
    return false;
  }

  var fprcupdamanuf = document.forms[mainform]["prcupda_manufacturer"].value;
  if (fprcupdamanuf == "") {
    alert("Manufacturer is mandatory!");
    return false;
  }

  var fprcupdsupp = document.forms[mainform]["prcupd_supplier"].value;
  if (fprcupdsupp == "") {
    alert("Supplieris mandatory!");
    return false;
  }

  var fprcupdprod = document.forms[mainform]["prcupd_prod"].value;
  if (fprcupdprod == "") {
    alert("Product is mandatory!");
    return false;
  }

  var fprcupdean = document.forms[mainform]["prcupd_EAN"].value;
  if (fprcupdean == "") {
    alert("EAN is mandatory!");
    return false;
  }

  var fprcupdcat = document.forms[mainform]["prcupd_category"].value;
  if (fprcupdsupp == "") {
    alert("Category is mandatory!");
    return false;
  }

  var fprcupdsubcat1 = document.forms[mainform]["prcupd_subcat1"].value;
  if (fprcupdsubcat1 == "") {
  alert("Category is mandatory!");
  return false;
  }

  var fprcupdsubcat2 = document.forms[mainform]["prcupd_subcat2"].value;
  if (fprcupdsubcat2 == "") {
  alert("Category is mandatory!");
  return false;
  }

  var fprcupdpurcprice = document.forms[mainform]["prcupd_purch_price"].value;
  if (fprcupdpurcprice == "") {
  alert("Purchase price is mandatory!");
  return false;
  }

  



}
