<?php
    /* 
     * Get products from unifiedlists table based on SQL-queries
     * which can be found from groupingrules table. Go through every
     * record in groupinrules table.
     */
    function groupProducts()
    {
        global $conn;
        
        try
        {
            $res = $conn->query("SELECT * FROM groupingrules");
            
            if($res->num_rows == 0)
            {
                throw new Exception('Getting data from groupingrules table failed.<br>', 1);
            }
            else
            {
                throw new Exception('Getting data from groupingrules table successful.<br>');
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
                        throw new Exception('Groupingrule '.$sql.' successful.<br>');
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
        
        $sql = "SELECT purchase_price_factor FROM supplierlists WHERE supplier_code = '".$product['supplier_code']."';";
        
        try
        {
            $res = $conn->query($sql);

            if($res->num_rows == 0)
            {
                throw new Exception('Getting purchase price factor with supplier code '.$product['supplier_code'].' from supplierlists table failed.<br>', 1);
            }
            else
            {
                throw new Exception('Getting purchase price factor with supplier code '.$product['supplier_code'].' successful.<br>');
            }
        } 
        catch (Exception $ex) 
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return;
        }
        
        $supplierPriceFactor = $res->fetch_assoc();

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
                throw new Exception('Getting data from pricegroups table with price group code '.$groupingRule['price_group'].' successful.<br>');
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
        if($supplierPriceFactor['purchase_price_factor'] > 0)
        {
            // newSupplierPrice = unifiedlists.supplier_purchase_price * supplierlists.purchase_price_factor
            $newSupplierPrice = $product['supplier_purchase_price'] * $supplierPriceFactor['purchase_price_factor'];
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
            insertIntoPricingTable($product, $groupingRule, $newSupplierPrice, $salesPrice);
        }
        else
        {
            updatePricingTable($product, $groupingRule, $newSupplierPrice, $salesPrice);
        }
    }

    function insertIntoPricingTable($prod, $groupingRule, $newSupplierPrice, $salesPrice)
    {
        global $conn;
        
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
                                target_category
                            )
                VALUES 
                        (
                            '".$prod['supplier_file']."', 
                            '".$prod['manufacturer']."', 
                            '".$prod['supplier_code']."',
                            '".$prod['product_code']."',
                            '".$prod['product_desc']."',
                            '".$prod['ean_code']."',
                            '".$prod['category']."',
                            '".$prod['subcat1']."',
                            '".$prod['subcat2']."',
                            '".$prod['supplier_purchase_price']."',
                            '".$newSupplierPrice."',
                            '".$salesPrice."',
                            '".$groupingRule['grouping_code']."',
                            '".$groupingRule['price_group']."',
                            '".$groupingRule['target_category']."'
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
                throw new Exception('Inserting data into pricing table successful.<br>');
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

        $sql = "UPDATE pricing 
                SET
                    supplier_purchase_price = '".$prod['supplier_purchase_price']."',
                    new_purchase_price = '".$newSupplierPrice."',
                    sales_price = '".$salesPrice."',
                    grouping_code = '".$groupingRule['grouping_code']."',
                    price_group_code = '".$groupingRule['price_group']."'
                WHERE ean_code = '".$prod['ean_code']."';";
 
        try
        {
            $res = $conn->query($sql);
            
            if($res === FALSE)
            {
                throw new Exception('Updating data to pricing table failed. '.$sql.'<br>', 1);
            }
            else
            {
                throw new Exception('Updating data to pricing table successful.<br>');
            }
        } 
        catch (Exception $ex) 
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }