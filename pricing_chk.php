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
   $sql = "SELECT * from pricings where prcupd_code = $POST[prcupd_code]";


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

    $kentat = array ('prcupd_file',	'prcupd_manufacturer',	prcupd_supplier,	'prcupd_prod',
    'prcupd_EAN',	'prcupd_category',	'prcupd_subcat1',		'prcupd_subcat2', prcupd_purch_price);

    !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    $prcgrp_code = $_POST["prcgrp_code"];
    $prcgrp_name = $_POST["prcgrp_name"];
    $prcgrp_formula = $_POST["prcgrp_formula"];

    // prepare
    $kentat = implode(",",$kentat);
  	$id = $_POST['id'];

  	if ($mode == 'Add') {
      $sql = "INSERT INTO pricegroups($kentat) VALUES ('$prcgrp_code',	'$prcgrp_name',	'$prcgrp_formula')";
    } elseif ($mode == 'Update') {
  	  $prcgrp_id = $_POST["prcgrp_id"];
      $sql = "UPDATE pricegroups SET
      prcgrp_code = '$prcgrp_code',
      prcgrp_name = '$prcgrp_name',
      prcgrp_formula = '$prcgrp_formula'
      WHERE prcgrp_id = $id";
      }
      echo $sql;
  return $sql;
  }


  //  DELETE
  function itemDelete() {
    $prcgrp_id = $_POST['id'];
    $sql = "DELETE FROM pricegroups WHERE prcgrp_id = '$prcgrp_id'";
    return $sql;
  }

 ?>
