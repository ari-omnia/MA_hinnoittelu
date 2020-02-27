<?php
    /* 
     * Get products from unifiedlists table based on SQL-queries
     * which can be found from groupingrules table. Go through every
     * record in groupinrules table.
     */
    function groupProducts()
    {
        global $conn;
        
        $res = $conn->query("SELECT * FROM groupingrules");

        while($groupingRule = $res->fetch_assoc())
        {
            $query = $groupingRule['grouping_SQL_selection'];
            
            if($query != NULL)
            {
                // grouping_SQL_selection is not NULL so we can use SQL expression to group products.
                
                $products = $conn->query($query);   // Get products from unifiedlists table according to the query.
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
        
        $query = "SELECT purchase_price_factor FROM supplierlists WHERE supplier_code = '".$product['supplier_code']."';";
        
        $res = $conn->query($query);
        $supplierPriceFactor = $res->fetch_assoc();

        $query = "SELECT * FROM pricegroups WHERE price_group_code = '".$groupingRule['price_group']."';";
        
        $res = $conn->query($query);
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
        
        $ins = "INSERT INTO pricing
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
 
        $conn->query($ins);
    }
    
    function updatePricingTable($prod, $groupingRule, $newSupplierPrice, $salesPrice)
    {
        global $conn;

        $upd = "UPDATE pricing 
                SET
                    supplier_purchase_price = '".$prod['supplier_purchase_price']."',
                    new_purchase_price = '".$newSupplierPrice."',
                    sales_price = '".$salesPrice."',
                    grouping_code = '".$groupingRule['grouping_code']."',
                    price_group_code = '".$groupingRule['price_group']."'
                WHERE ean_code = '".$prod['ean_code']."';";
  
        $conn->query($upd);
    }