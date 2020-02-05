function validateForm() {

  var ffilename = document.forms["productlist"]["file_name"].value;
  if (ffilename == "") {
    alert("File name is mandatory!");
    return false;
  }

  var ffileloc = document.forms["productlist"]["file_loc"].value;
  if (ffileloc == "") {
    alert("File location path is mandatory!");
    return false;
  }

  var fcolsep = document.forms["productlist"]["file_col_sep"].value;
  if (fcolsep == "") {
    alert("File column separator is mandatory!");
    return false;
  }

}
