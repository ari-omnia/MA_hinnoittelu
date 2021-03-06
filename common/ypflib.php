<?php

// Convert screen values to db-values
function ypCnvNbrFormDb ($nbrForm, $nbrOFDec) {
  if(empty($nbrForm)) {
    $nbrDb = "0";
    if($nbrOFDec > 0) {
      $nbrDB .= ".";
      for ($i = 1; $i < $nbrOFDec; $i++) {
        $nbrDB .= "0";
      }
    }
  } else {
    $nbrDb = str_replace(",", ".", $nbrForm);
  }
  return $nbrDb;
}

// Convert db-values to screen values
function ypCnvNbrDbForm ($nbr){
  // First we could have a check from browser language settings to determine to show decimal point or comma.
  //For now the default is comma according Finnish standard.
  $nbrstr = str_replace(".", ",", $nbr);
  if(empty($nbrstr)) {
    $nbrstr = "0,00";
  }
  return $nbrstr;
}

function ypYnDbForm ($code) {
  if($code == 1) {
    return "K";
  } else {
    return "E";
  }
}

function isValueNumeric ($nbr) {
	return is_numeric($nbr);
}

/*function isRightDecimal ($nbr, $nbrOFDec) {
    $decimals = substr($nbr, (strpos($nbr, ".") + 1));
    if (strlen((string)$decimals) > $nbrOFDec) {
        return false;
    }
    return true;
}*/
function isRightDecimal ($nbr, $nbrOfDec) {
    if (strpos($nbr, ".", 0) > 0) {
        $decimals = substr($nbr, strpos($nbr, ".", 0)+1);

        if (strlen((string)$decimals) > $nbrOfDec) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }

}
//
// CONVERT DATE VALUES
// ** Convert DB date yyyy-mm-dd to dd.mm.yyyy
function ypCnvDateDbForm ($date) {
  if($date != "") {
    $dateReturn = strtotime($date);
    return date('d.m.Y', $dateReturn);
  }
  return "";
}

 ?>
