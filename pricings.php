<?php
require "header.php";
require "includes/logged.php";

  //!! define how many results you want per page
  $results_per_page = 10;
  // find out the number of results stored in database
  $sql='SELECT * FROM pricing';
  $result = mysqli_query($conn, $sql);
  $number_of_results = mysqli_num_rows($result);
  // determine number of total pages available
  $number_of_pages = ceil($number_of_results/$results_per_page);
  // determine which page number visitor is currently on
  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }

  // determine the sql LIMIT starting number for the results on the displaying page
  $this_page_first_result = ($page-1)*$results_per_page;
  //!!
?>

    <!-- Actual Page DIV -->
    <section class="function-container">
      <div>
        <input class="button button--add" type="submit" value="Lisää" onclick="window.location.href='pricing.php?mode=1'">
        <?php
		if(isset($_GET['return']))
        {
          if($_GET['return'] == 'addSuccess') {
           echo "Tietue lisätty onnistuneesti";
          }if($_GET['return'] == 'updateSuccess') {
           echo "Tietue päivitetty onnistuneesti";
          }if($_GET['return'] == 'deleteSuccess') {
           echo "Tietue poistettu onnistuneesti";
          }
          if($_GET['return'] == 'alreadyExists') {
           echo "Pricing specs löytyy jo kannasta";
          }
	    	}
         ?>
      </div>
    </section>


    <section class="listcontainer">
      <!--form action="lista_chk.php" method="post"-->
        <div class="row">
          <div class="col-25">

            <?php
              $sql = "SELECT * from pricing LIMIT " . $this_page_first_result . "," .  $results_per_page;
              $result = mysqli_query($conn, $sql);
              if(mysqli_num_rows($result) > 0) {
                // output data columns
            		echo "<table class='listtable'>
                  <thead><tr>
					          <th>Val</th>
                    <th>Id</th>
                    <th>Price upd. file</th>
                    <th>Prod. Manufacturer</th>
                    <th>Product</th>
                    <th>EAN code</th>
                    <th>Category</th>
                    <th>Sub cat. 1</th>
                    <th>Sub cat. 2</th>
                    <th>Pur. price</th>
                  </tr></thead>";
                // output data of each row
            		while($row = mysqli_fetch_assoc($result)) {
						$id = $row["prcupd_id"];
            			echo "<tr>
          				  <form name='pricingrow' action='pricing.php?mode=0' method='post'>
                            <td><input class='button button--rowselect' type='submit' name='update' value=''>
                            <input type='hidden' name='id' value=$id></td>
                            <td>".$row["prcupd_id"]."</td>
                            <td>".$row["prcupd_file"]."</td>
          				          <td>".$row["prcupd_manufacturer"]."</td>
                            <td>".$row["prcupd_prod"]."</td>
                            <td>".$row["prcupd_EAN"]."</td>
                            <td>".$row["prcupd_category"]."</td>
                            <td>".$row["prcupd_subcat1"]."</td>
                            <td>".$row["prcupd_subcat2"]."</td>
                            <td>".$row["prcupd_purch_price"]."</td>
          				  </form>
                  </tr>";
            		}
            		echo "</table>";
            	} else {
            		echo "0 results";
            	}
              mysqli_close($conn);
             ?>

          </div>
        </div>
    </section>
    <section class='pagination-container'>
      <div>
      </div>
      <div>
        <?php
        // !!display the links to the pages
        for ($page=1;$page<=$number_of_pages;$page++) {
          //echo "<button class='pagination-button' type='button'>$page</button>";
          //echo '<a href="kalustoluetteloList.php?page=' . $page . '">' . $page . '</a> ';
          echo '<a class="pagination-button" href="pricings.php?page=' . $page . '">' . $page . '</a> ';
        }
        //!!
        ?>
      </div>
      <div>
      </div>
    </section>



<?php
  require "footer.php";
?>