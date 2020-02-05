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
	  header("Location: productlists.php?$returnCode" );
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
   $sql = "SELECT * from productlists where file_name = $POST[file_name] AND file_loc = $POST[file_loc]";


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

    $kentat = array ('file_supplier',	'file_supp_name',	'file_name',	'file_loc',	'file_col_sep',
                    'file_prod_col',	'file_prod_desc_col',	'file_EAN_col',	'file_category_col',
                    'file_subcat1_col',	'file_subcat2_col',	'file_purch_price_col');

    $file_supplier = $_POST["file_supplier"];
    $file_supp_name = $_POST["file_supp_name"];
    $file_name = $_POST["file_name"];
    $file_loc = $_POST["file_loc"];
    $file_col_sep	= $_POST["file_col_sep"];
    $file_prod_col = $_POST["file_prod_col"];
    $file_prod_desc_col	= $_POST["file_prod_desc_col"];
    $file_EAN_col	= $_POST["file_EAN_col"];
    $file_category_col	= $_POST["file_category_col"];
    $file_subcat1_col	= $_POST["file_subcat1_col"];
    $file_subcat2_col	= $_POST["file_subcat2_col"];
    $file_purch_price_col	= $_POST["file_purch_price_col"];

    // prepare
    $kentat = implode(",",$kentat);
  	$id = $_POST['id'];

  	if ($mode == 'Add') {
      $sql = "INSERT INTO productlists($kentat) VALUES ('$file_supplier',	'$file_supp_name',	'$file_name',
            '$file_loc',	'$file_col_sep',	'$file_prod_col',	'$file_prod_desc_col',	'$file_EAN_col',	'$file_category_col',
            '$file_subcat1_col',	'$file_subcat2_col',	$file_purch_price_col)";
    } elseif ($mode == 'Update') {
  	  $productlist_id = $_POST["productlist_id"];
      $sql = "UPDATE productlists SET
      file_supplier = '$file_supplier',
      file_supp_name = '$file_supp_name',
      file_name = '$file_name',
      file_loc = '$file_loc',
      file_col_sep	= '$file_col_sep',
      file_prod_col = '$file_prod_col',
      file_prod_desc_col	= '$file_prod_desc_col',
      file_EAN_col	= '$file_EAN_col',
      file_category_col	= '$file_category_col',
      file_subcat1_col	= '$file_subcat1_col',
      file_subcat2_col	= '$file_subcat2_col',
      file_purch_price_col	= $file_purch_price_col
      WHERE productlist_id = $id";
      }
      echo $sql;
  return $sql;
  }


  //  DELETE
  function itemDelete() {
    $productlist_id = $_POST['id'];
    $sql = "DELETE FROM productlists WHERE productlist_id = '$productlist_id'";
    return $sql;
  }

 ?>
