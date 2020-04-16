<?php
    /*
     * Get products from unifiedlists table based on SQL-queries
     * which can be found from groupingrules table. Go through every
     * record in groupinrules table.
     */
    function groupProducts($groupingCode = '')
    {
        global $conn;

        try
        {

            if($groupingCode == '')
            {
                $res = $conn->query("SELECT * FROM groupingrules");
            }
            else
            {
                $res = $conn->query("SELECT * FROM groupingrules WHERE grouping_code = '$groupingCode'");
            }


            if($res->num_rows == 0)
            {
                throw new Exception('Getting data from groupingrules table failed.<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Getting data from groupingrules table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return;
        }

        while($groupingRule = $res->fetch_assoc())
        {
            $sql = $groupingRule['grouping_SQL_selection'];

            if($sql != NULL)
            {
                // grouping_SQL_selection is not NULL so we can use SQL expression to group products.

                try
                {
                    $products = $conn->query($sql);   // Get products from unifiedlists table according to the query.

                    if($products->num_rows == 0)
                    {
                        throw new Exception('No items found with grouping rule '.$sql.' <br>', 1);
                    }
                    else
                    {
                        // Removed success log entries
                        //throw new Exception('Groupingrule '.$sql.' successful.<br>');
                    }
                }
                catch (Exception $ex)
                {
                    echo $ex->getMessage();

                    writeLog($ex->getMessage());

                    if($ex->getCode() > 0)
                    {
                        continue;
                    }
                }
            }
            else
            {
                echo 'TODO: Combine query from separate qrouping rules';
            }

            while($p = $products->fetch_assoc())
            {
                calculatePrice($p, $groupingRule);
            }
        }
    }

    function calculatePrice($product, $groupingRule)
    {
        global $conn;

        //$sql = "SELECT purchase_price_factor FROM supplierlists WHERE supplier_code = '".$product['supplier_code']."';";
        $sql = "SELECT purchase_price_factor, id, new_products_totalsum FROM supplierlists WHERE supplier_code = '".$product['supplier_code']."';";

        try
        {
            $res = $conn->query($sql);

            if($res->num_rows == 0)
            {
                throw new Exception('Getting purchase price factor with supplier code '.$product['supplier_code'].' from supplierlists table failed.<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Getting purchase price factor with supplier code '.$product['supplier_code'].' successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return;
        }

        //$supplierPriceFactor = $res->fetch_assoc();
        $supplierlistRow = $res->fetch_assoc();

        $sql = "SELECT * FROM pricegroups WHERE price_group_code = '".$groupingRule['price_group']."';";

        try
        {
            $res = $conn->query($sql);

            if($res->num_rows == 0)
            {
                throw new Exception('Getting data from pricegroups table with price group code '.$groupingRule['price_group'].' failed.<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Getting data from pricegroups table with price group code '.$groupingRule['price_group'].' successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return;
        }

        $pricingRule = $res->fetch_assoc();

        // If supplier based purchase price factor > 0, multiply supplier price by purchase price factor.
        if($supplierlistRow['purchase_price_factor'] > 0)
        {
            // newSupplierPrice = unifiedlists.supplier_purchase_price * supplierlists.purchase_price_factor
            $newSupplierPrice = $product['supplier_purchase_price'] * $supplierlistRow['purchase_price_factor'];
        }
        else
        {
            $newSupplierPrice = $product['supplier_purchase_price'];
        }

        // salesPrice = newSupplierPrice * pricegroups.sales_price_factor + pricegroups.fixed_sum_to_price
        $salesPrice = $newSupplierPrice * $pricingRule['sales_price_factor'] + $pricingRule['fixed_sum_to_price'];

        $salesPrice = round($salesPrice, 1);

        $res = findEAN('pricing', $product['ean_code']);

        if($res->num_rows == 0)
        {
            // If not in Presta, update New Products Totalsum
            $presta = 0;
            if (!existsPresta($product['ean_code'])) {
                updateNewProductsTotalAdd($supplierlistRow['id'], $supplierlistRow['new_products_totalsum']);
                $presta = true;
            }
            insertIntoPricingTable($product, $groupingRule, $newSupplierPrice, $salesPrice, $presta);
        }
        else
        {
            updatePricingTable($product, $groupingRule, $newSupplierPrice, $salesPrice);
        }
    }

    function insertIntoPricingTable($prod, $groupingRule, $newSupplierPrice, $salesPrice, $presta)
    {
        global $conn;
        $pr_supplier_file = mysqli_real_escape_string($conn, $prod['supplier_file']);
        $pr_manufacturer = mysqli_real_escape_string($conn, $prod['manufacturer']);
        $pr_supplier_code = mysqli_real_escape_string($conn, $prod['supplier_code']);
        $pr_product_code = mysqli_real_escape_string($conn, $prod['product_code']);
        $pr_product_desc = mysqli_real_escape_string($conn, $prod['product_desc']);
        $pr_ean_code = mysqli_real_escape_string($conn, $prod['ean_code']);
        $pr_category = mysqli_real_escape_string($conn, $prod['category']);
        $pr_subcat1 = mysqli_real_escape_string($conn, $prod['subcat1']);
        $pr_subcat2 = mysqli_real_escape_string($conn, $prod['subcat2']);
        $pr_purchase_price = mysqli_real_escape_string($conn, $prod['supplier_purchase_price']);
        $pr_grouping_code = mysqli_real_escape_string($conn, $groupingRule['grouping_code']);
        $pr_price_group = mysqli_real_escape_string($conn, $groupingRule['price_group']);
        $pr_target_category = mysqli_real_escape_string($conn, $groupingRule['target_category']);

        $sql = "INSERT INTO pricing
                            (
                                supplier_file,
                                manufacturer,
                                supplier_code,
                                product_code,
                                product_desc,
                                ean_code,
                                category,
                                subcat1,
                                subcat2,
                                supplier_purchase_price,
                                new_purchase_price,
                                sales_price,
                                grouping_code,
                                price_group_code,
                                target_category,
                                new_product
                            )
                VALUES
                        (
                            '$pr_supplier_file',
                            '$pr_manufacturer',
                            '$pr_supplier_code',
                            '$pr_product_code',
                            '$pr_product_desc',
                            '$pr_ean_code',
                            '$pr_category',
                            '$pr_subcat1',
                            '$pr_subcat1',
                            '$pr_purchase_price',
                            '$newSupplierPrice',
                            '$salesPrice',
                            '$pr_grouping_code',
                            '$pr_price_group',
                            '$pr_target_category',
                            '$presta'
                        )";

        try
        {
            $res = $conn->query($sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data into pricing table failed. '.$sql.'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data into pricing table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }

    function updatePricingTable($prod, $groupingRule, $newSupplierPrice, $salesPrice)
    {
        global $conn;

        $pr_grouping_code = mysqli_real_escape_string($conn, $groupingRule['grouping_code']);
        $pr_price_group_code = mysqli_real_escape_string($conn, $groupingRule['price_group']);
        $_ean_code = mysqli_real_escape_string($conn, $prod['ean_code']);


        $sql = "UPDATE pricing
                SET
                    supplier_purchase_price = '".$prod['supplier_purchase_price']."',
                    new_purchase_price = '".$newSupplierPrice."',
                    sales_price = '".$salesPrice."',
                    grouping_code = '$pr_grouping_code',
                    price_group_code = '$pr_price_group_code'
                WHERE ean_code = '$_ean_code';";

        try
        {
            $res = $conn->query($sql);

            if($res === FALSE)
            {
                throw new Exception('Updating data to pricing table failed. '.$sql.'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Updating data to pricing table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }

    function updateNewProductsTotalAdd($id, $new_products_totalsum)
    {
            global $conn;

            $sql = "UPDATE supplierlists
                    SET
                        new_products_totalsum = ($new_products_totalsum + 1)
                    WHERE id = $id;";

            try
            {
                $res = $conn->query($sql);

                if($res === FALSE)
                {
                    throw new Exception('Updating new products totalsum to supplierlists table failed. '.$sql.'<br>', 1);
                }
                else
                {
                    // Removed success log entries
                    //throw new Exception('Updating new products totalsum to supplierlists table successful.<br>');
                }
            }
            catch (Exception $ex)
            {
                echo $ex->getMessage();

                writeLog($ex->getMessage());
            }
    }

    function existsPresta($ean_code)
    {
        require 'db/db_presta.php';

        $sql = "SELECT id_product FROM ps_product WHERE ean13 = $ean_code;";
        $id = mysqli_query($prestaconn, $sql);

        if(mysqli_num_rows($id) == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
