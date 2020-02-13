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
	  header("Location: pricegroups.php?$returnCode" );
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
   $sql = "SELECT * from pricegroups where price_group_code = $POST[price_group_code]";


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

    $kentat = array ('price_group_code',	'price_group_desc',	'sales_price_factor', 'fixed_sum_to_price');

    $price_group_code = $_POST["price_group_code"];
    $price_group_desc = $_POST["price_group_desc"];
    $sales_price_factor = $_POST["sales_price_factor"];
    $fixed_sum_to_price = $_POST["fixed_sum_to_price"];


    // prepare
    $kentat = implode(",",$kentat);
  	$id = $_POST['id'];

  	if ($mode == 'Add') {
      $sql = "INSERT INTO pricegroups($kentat) VALUES ('$price_group_code',	'$price_group_desc',	'$sales_price_factor', '$fixed_sum_to_price')";
    } elseif ($mode == 'Update') {
  	  $id = $_POST["id"];
      $sql = "UPDATE pricegroups SET
      price_group_code = '$price_group_code',
      price_group_desc = '$price_group_desc',
      sales_price_factor = '$sales_price_factor',
      fixed_sum_to_price = '$fixed_sum_to_price'
      WHERE id = $id";
      }
      echo $sql;
  return $sql;
  }


  //  DELETE
  function itemDelete() {
    $id = $_POST['id'];
    $sql = "DELETE FROM pricegroups WHERE id = '$id'";
    return $sql;
  }

 ?>
