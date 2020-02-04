// START FUNCTION SPECIFICATIONS
// Check price field is correct
function ypChkPrice(price) {
  //Check no other than number and decimal comma
  var regex = /[^0-9,]/g;
  if (regex.test(price)) {
    return false;
  }
  //Convert comma to point and test if numeric
  var newPrice = price.replace(",", ".");
  if (isNaN(newPrice)) {
    return false;
  }

  var decimals = newPrice.substring(newPrice.indexOf(".") + 1);
  if (decimals.length > 2) {
    return false;
  }
  return true;
}
// Format pricefield to Format.
function ypFmtPrice(price) {
  var decimals = price.substring(price.indexOf(",") + 1);
  // Price n,n
  if (decimals.length == 1) {
    return price + "0";
  }
  // Price n
  if (decimals.length == 0) {
    return price + ",00";
  }
  // Price n,nn
  return price;
}
function confDelete() {
//************************************************
//
// CHECK IF DELETE ADN SHOW CONFIRMATION BOX
//
//************************************************

  var conf = confirm("Poistetaanko tieto?");
  if (conf) {
    return true;
  } else {
    return false;
  }
}
