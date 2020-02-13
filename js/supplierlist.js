function validateForm() {

  var supplier_file = document.forms["supplierlist"]["supplier_file"].value;
  if (supplier_file == "") {
    alert("File name is mandatory!");
    return false;
  }

  var data_start_row = document.forms["supplierlist"]["data_start_row"].value;
  if (data_start_row == "") {
    alert("File first data row is mandatory!");
    return false;
  }

  var file_path = document.forms["supplierlist"]["file_path"].value;
  if (file_path == "") {
    alert("File location path is mandatory!");
    return false;
  }

  var file_column_separator = document.forms["supplierlist"]["file_column_separator"].value;
  if (file_column_separator == "") {
    alert("File column separator is mandatory!");
    return false;
  }

}
