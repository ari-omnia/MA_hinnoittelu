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
	  header("Location: pricings.php?$returnCode" );
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
   $sql = "SELECT * from pricings where product_code = $POST[product_code]";


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

    $kentat = array ('supplier_file', 'manufacturer', 'supplier_code', 'product_code', 'product_desc',
    'ean_code',	'category',	'subcat1', 'subcat2', 'supplier_purchase_price', 'new_purchase_price',
    'grouping_code', 'price_group_code');

    !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    $supplier_file = $_POST["supplier_file"];
    $manufacturer = $_POST["manufacturer"];
    $supplier_code = $_POST["supplier_code"];
    $product_code = $_POST["product_code"];
    $product_desc = $_POST["product_desc"];
    $ean_code = $_POST["ean_code"];
    $category = $_POST["category"];
    $subcat1 = $_POST["subcat1"];
    $subcat2 = $_POST["subcat2"];
    $supplier_purchase_price = $_POST["supplier_purchase_price"];
    $new_purchase_price = $_POST["new_purchase_price"];
    $grouping_code= $_POST["grouping_code"];
    $price_group_code = $_POST["price_group_code"];

    // prepare
    $kentat = implode(",",$kentat);
  	$id = $_POST['id'];

  	if ($mode == 'Add') {
      $sql = "INSERT INTO pricing($kentat)
      VALUES ('$supplier_file',
          '$manufacturer',
          '$supplier_code',
          '$product_code',
          '$product_desc',
          '$ean_code',
          '$category',
          '$subcat1',
          '$subcat2',
          '$supplier_purchase_price',
          '$new_purchase_price',
          '$grouping_code',
          '$price_group_code')";
    } elseif ($mode == 'Update') {
  	  $id = $_POST["id"];
      $sql = "UPDATE pricing SET
      supplier_file = '$supplier_file',
      manufacturer = '$manufacturer',
      supplier_code = '$supplier_code',
      product_code = '$product_code',
      product_desc = '$product_desc',
      ean_code = '$ean_code',
      category = '$category',
      subcat1 = '$subcat1',
      subcat2 = '$subcat2',
      supplier_purchase_price = '$supplier_purchase_price',
      new_purchase_price = '$new_purchase_price',
      grouping_code = '$grouping_code',
      price_group_code = '$price_group_code'
      WHERE id = $id";
      }
      echo $sql;
  return $sql;
  }


  //  DELETE
  function itemDelete() {
    $id = $_POST['id'];
    $sql = "DELETE FROM pricegroups WHERE id = 'id'";
    return $sql;
  }

 ?>
