<?php
    require_once 'fileHandler.php';

    function startTransfer()
    {
        date_default_timezone_set('Europe/Helsinki');

        $result = getData();

        if(!$result)
        {
            return;
        }

        while($row = mysqli_fetch_assoc($result))
        {
            moveData($row);
        }

    }

    /*
     * Get data from pricing table.
     */
    function getData()
    {
        global $conn;

        try
        {
            $res = mysqli_query($conn, "SELECT * FROM pricing");

            if(mysqli_num_rows($res) == 0)
            {
                throw new Exception('Getting data from pricing table failed. Table empty?<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Getting data from pricing table successful.<br>');
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        return $res;
    }

    function moveData($product)
    {
        global $prestaconn;

        $sql = "SELECT id_product FROM ps_product WHERE ean13 = '".$product['ean_code']."';";
        $id = mysqli_query($prestaconn, $sql);

        if(mysqli_num_rows($id) == 0)
        {
            $result = insertIntoPresta($product);
        }
        else
        {
            $result = updatePresta($product, $id);
        }

        if($result)
        {
            deleteFromPricing($product['ean_code']);
        }
    }

    function insertIntoPresta($product)
    {
        global $prestaconn;

        $time = date("Y-m-d H:i:s");
        $id_manufacturer = getManufacturerName($product['manufacturer']);
        //echo "supp code ".$product['supplier_code'];
        // Here we would be ready to add Manufacturer info to Presta
        if($id_manufacturer == '') {
            addManufacturer($product['manufacturer']);
        }
        try
        {
            $sql = "INSERT INTO ps_product (
                date_add,
                id_category_default,
                ean13,
                price,
                wholesale_price,
                reference,
                id_manufacturer,
                id_supplier,
                id_shop_default,
                on_sale,
                online_only,
                ecotax,
                quantity,
                minimal_quantity,
                unit_price_ratio,
                additional_shipping_cost,
                width,
                height,
                depth,
                weight,
                out_of_stock,
                quantity_discount,
                customizable,
                uploadable_files,
                text_fields,
                active,
                redirect_type,
                id_product_redirected,
                available_for_order,
                available_date,
                show_price,
                indexed,
                visibility,
                cache_is_pack,
                cache_has_attachments,
                is_virtual,
                advanced_stock_management,
                pack_stock_type,
                icecatcode,
                eancode,
                id_tax_rules_group)
                    VALUES (
                '".$time."',
                '".$product['target_category']."',
                '".$product['ean_code']."',
                '".$product['sales_price']."',
                '".$product['supplier_purchase_price']."',
                '".$product['product_code']."',
                '$id_manufacturer',
                '".$product['supplier_code']."',
                1,
                0,
                0,
                0.000000,
                0,
                1,
                0.000000,
                0.00,
                0.000000,
                0.000000,
                0.000000,
                0.000000,
                2,
                0,
                0,
                0,
                0,
                0,
                '',
                0,
                1,
                '0000-00-00',
                1,
                0,
                'both',
                0,
                0,
                0,
                0,
                3,
                '',
                '',
                1);";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_product table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_product table successful.<br>');
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        $last_id = mysqli_insert_id($prestaconn);

        try
        {
            $sql = "INSERT INTO ps_product_shop (
                id_product,
                date_add,
                id_category_default,
                price,
                wholesale_price,
                id_shop, on_sale,
                online_only, ecotax,
                minimal_quantity,
                unit_price_ratio,
                additional_shipping_cost,
                customizable,
                uploadable_files,
                text_fields,
                active,
                redirect_type,
                id_product_redirected,
                available_for_order,
                available_date,
                show_price,
                indexed,
                visibility,
                advanced_stock_management,
                pack_stock_type,
                id_tax_rules_group)
                    VALUES (
                '".$last_id."',
                '".$time."',
                '".$product['target_category']."',
                '".$product['sales_price']."',
                '".$product['supplier_purchase_price']."',
                '1',
                '0',
                '0',
                '0.000000',
                '1',
                '0.000000',
                '0.00',
                '0',
                '0',
                '0',
                '0',
                '',
                '0',
                '1',
                '0000-00-00', '1',
                '0',
                'both',
                '0',
                '3',
                '1');";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_product_shop table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_product_shop table successful.<br>');
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        try
        {
            $pr_product_desc = mysqli_real_escape_string($prestaconn, $product['product_desc']);

            $sql = "INSERT INTO ps_product_lang (id_product, id_shop, id_lang, description, name, available_now)
                    VALUES (
                            '$last_id',
                            '1',
                            '1',
                            '$pr_product_desc',
                            '$pr_product_desc',
                            'Saatavilla'
                           );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting lang 1 data to ps_product_lang table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting lang data to ps_product_lang table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }


        try
        {
            $pr_product_desc = mysqli_real_escape_string($prestaconn, $product['product_desc']);

            $sql = "INSERT INTO ps_product_lang (id_product, id_shop, id_lang, description, name, available_now)
                    VALUES (
                            '$last_id',
                            '1',
                            '2',
                            '$pr_product_desc',
                            '$pr_product_desc',
                            'Saatavilla'
                           );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting lang 2 data to ps_product_lang table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting lang 2 data to ps_product_lang table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        try
        {
            $sql = "INSERT INTO ps_stock_available (
                id_product,
                id_product_attribute,
                id_shop,
                id_shop_group,
                quantity,
                depends_on_stock,
                out_of_stock
            )
                VALUES (
                '$last_id',
                '0',
                '1',
                '0',
                '0',
                '0',
                '1'
               );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_stock_available table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_stock_available table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        try
        {
            $sql = "INSERT INTO ps_category_product (
                id_category,
                id_product,
                position
            )
                VALUES (
                '".$product['target_category']."',
                '$last_id',
                '0'
               );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_category_product table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_category_product table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        try
        {
            $sql = "INSERT INTO ps_product_supplier (
                id_product,
                id_product_attribute,
                id_supplier,
                product_supplier_reference,
                product_supplier_price_te,
                id_currency
            )
                VALUES (
                '$last_id',
                '0',
                '".$product['supplier_code']."',
                '',
                '".$product['supplier_purchase_price']."',
                '1'
               );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_product_supplier table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_product_supplier table successful.<br>');
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        return true;
    }

    function updatePresta($product, $id)
    {
        global $prestaconn;

        $time = date("Y-m-d H:i:s");

        $prestaId = mysqli_fetch_assoc($id);

        $sql = "UPDATE ps_product, ps_product_shop
                SET
                    ps_product.date_upd = '".$time."',
                    ps_product.id_category_default = '".$product['target_category']."',
                    ps_product.price = '".$product['sales_price']."',
                    ps_product.wholesale_price = '".$product['supplier_purchase_price']."',

                    ps_product_shop.date_upd = '".$time."',
                    ps_product_shop.id_category_default = '".$product['target_category']."',
                    ps_product_shop.price = '".$product['sales_price']."',
                    ps_product_shop.wholesale_price = '".$product['supplier_purchase_price']."'
                WHERE ps_product.id_product = '".$prestaId['id_product']."' AND ps_product_shop.id_product = ps_product.id_product;";

        try
        {
            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Updating data to ps_product and ps_product_shop tables failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Updating data to ps_product and ps_product_shop tables successful.<br>');
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        $sql = "UPDATE ps_product_supplier
                SET
                    ps_product_supplier.product_supplier_price_te = '".$product['supplier_purchase_price']."'
                WHERE   ps_product_supplier.id_product = '".$prestaId['id_product']."' AND
                        ps_product_supplier.id_product_attribute = '0' AND
                        ps_product_supplier.id_supplier = '".$product['supplier_code']."';";

        try
        {
            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Updating data to ps_product_supplier table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Updating data to ps_product_supplier table successful.<br>');
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        return true;
    }

    function deleteFromPricing($ean)
    {
        global $conn;

        $sql = "DELETE FROM pricing WHERE ean_code = '".$ean."';";

        try
        {
            $res = mysqli_query($conn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Deleting item from pricing table with EAN code '.$ean.' failed. <br> '.mysqli_error($conn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Deleting item from pricing table with EAN code '.$ean.' successful.<br>');
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }

    function getManufacturerName($manufacturer)
    {
        global $prestaconn;

        $sql = "SELECT * FROM ps_manufacturer WHERE name = '$manufacturer';";

        $res = mysqli_query($prestaconn, $sql);
        $row = mysqli_fetch_assoc($res);

        if($res === FALSE)
            {
                return '';
            }
            else
            {
                return $row['id_manufacturer'];
            }

    }

    function addManufacturer($manufacturer)
    {
        global $prestaconn;
        $time = date("Y-m-d H:i:s");

        // INSERT to PS_MANUFACTURER
        try
        {
            $sql = "INSERT INTO ps_manufacturer (
                name,
                date_add,
                date_upd,
                active
            )
                VALUES (
                '$manufacturer',
                '$time',
                '0000-00-00',
                '1'
               );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_manufacturer table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_manufacturer table successful.<br>');

            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }
        // Fetch and save last manufacturer_id
        $last_mfc_id = mysqli_insert_id($prestaconn);

        // INSERT to PS_MANUFACTURER_LANG 1
        try
        {
            $sql = "INSERT INTO ps_manufacturer_lang (
                id_manufacturer,
                id_lang
            )
                VALUES (
                '$last_mfc_id',
                '1'
               );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_manufacturer_lang (1) table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_manufacturer_lang (1) table successful.<br>');
                $last_mfc_id = mysqli_insert_id($prestaconn);
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        // INSERT to PS_MANUFACTURER_LANG 2
        try
        {
            $sql = "INSERT INTO ps_manufacturer_lang (
                id_manufacturer,
                id_lang
            )
                VALUES (
                '$last_mfc_id',
                '2'
               );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_manufacturer_lang (2) table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_manufacturer_lang (2) table successful.<br>');
                $last_mfc_id = mysqli_insert_id($prestaconn);
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

        // INSERT to PS_MANUFACTURER_SHOP
        try
        {
            $sql = "INSERT INTO ps_manufacturer_shop (
                id_manufacturer,
                id_shop
            )
                VALUES (
                '$last_mfc_id',
                '1'
               );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_manufacturer_shop table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                // Removed success log entries
                //throw new Exception('Inserting data to ps_manufacturer_shop table successful.<br>');
                $last_mfc_id = mysqli_insert_id($prestaconn);
            }
        }
        catch (Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return false;
        }

    }
