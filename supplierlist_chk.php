<?php

  require 'db/db.php';
  require 'common/ypflib.php';
  if(!empty($_POST['add'])) {
    $mode = $_POST['add'];
  } elseif (!empty($_POST['update'])) {
      $mode = $_POST['update'];
  } elseif (!empty($_POST['delete'])) {
      $mode = $_POST['delete'];
  }

// Mode = ADD
  if ($mode == 'Add') {
		if (checkifItemExist($conn)=="OK") {
      $sql = itemAddUpdate();
		  $returnCode = 'return=addSuccess';
	  } else {
    $returnCode = 'return=alreadyExists';
    }

  }
// Mode = UPDATE
  if ($mode == 'Update') {
    $sql = itemAddUpdate();
    $returnCode = 'return=updateSuccess';
  }
// Mode = DELETE
  if ($mode == 'Delete') {
    $sql = itemDelete();
    $returnCode = 'return=deleteSuccess';
  }

    //Execute
    if (mysqli_query($conn, $sql)) {
	  $status = "";
      mysqli_close($conn);
	  header("Location: supplierlists.php?$returnCode" );
	  exit;
	}
    else {
	  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      $status = "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
    mysqli_close($conn);

 function checkifItemExist($conn) {
   //$jasenlaji = strip_tags($_POST["jasenlaji"]);
   //$sql = "SELECT jasenlaji from jasenlaji where jasenlaji like ".'"'."%".$_POST['jasenlaji']."%".'"';
   $sql = "SELECT * from supplierlists where supplier_file = $POST[supplier_file] AND file_path = $POST[file_path]";


   $result = mysqli_query($conn, $sql);
   if($result->num_rows > 0) {
	  return "Exist";
   } else {
	  return "OK";
   }

   mysqli_close($conn);
  }


  function itemAddUpdate() {
    global $kentat;
    global $mode;
    global $conn;

    $kentat = array ('supplier_code',	'supplier_name', 'purchase_price_factor',	'supplier_file', 'data_start_row',	'file_path',	'file_column_separator',
                    'column_manufacturer',	'column_product_code',	'column_product_desc',	'column_ean_code',	'column_category',
                    'column_subcat1',	'column_subcat2',	'column_purchase_price');

    $supplier_code = $_POST["supplier_code"];
    $supplier_name = $_POST["supplier_name"];
    $purchase_price_factor = $_POST["purchase_price_factor"];
    $supplier_file = $_POST["supplier_file"];
    $data_start_row = $_POST["data_start_row"];
    $file_path = $_POST["file_path"];
    $file_column_separator	= $_POST["file_column_separator"];
    $column_manufacturer = $_POST["column_manufacturer"];
    $column_product_code = $_POST["column_product_code"];
    $column_product_desc	= $_POST["column_product_desc"];
    $column_ean_code	= $_POST["column_ean_code"];
    $column_category	= $_POST["column_category"];
    $column_subcat1	= $_POST["column_subcat1"];
    $column_subcat2	= $_POST["column_subcat2"];
    $column_purchase_price	= $_POST["column_purchase_price"];

    // prepare
    $kentat = implode(",",$kentat);
  	$id = $_POST['id'];

  	if ($mode == 'Add') {
      $sql = "INSERT INTO supplierlists($kentat) VALUES ('$supplier_code',	'$supplier_name',	$purchase_price_factor,'$supplier_file', $data_start_row,
            '$file_path',	'$file_column_separator',	$column_manufacturer,	$column_product_code,	$column_product_desc,	$column_ean_code,	$column_category,
            $column_subcat1,	$column_subcat2,	$column_purchase_price)";
    } elseif ($mode == 'Update') {
  	  $id = $_POST["id"];
      $sql = "UPDATE supplierlists SET
      supplier_code = '$supplier_code',
      supplier_name = '$supplier_name',
      purchase_price_factor = $purchase_price_factor,
      supplier_file = '$supplier_file',
      data_start_row = $data_start_row,
      file_path = '$file_path',
      file_column_separator	= '$file_column_separator',
      column_manufacturer = $column_manufacturer,
      column_product_code = $column_product_code,
      column_product_desc	= $column_product_desc,
      column_ean_code	= $column_ean_code,
      column_category	= $column_category,
      column_subcat1	= $column_subcat1,
      column_subcat2	= $column_subcat2,
      column_purchase_price	= $column_purchase_price
      WHERE id = $id";
      }
      echo $sql;
  return $sql;
  }


  //  DELETE
  function itemDelete() {
    $id = $_POST['id'];
    $sql = "DELETE FROM supplierlists WHERE id = '$id'";
    return $sql;
  }

 ?>
