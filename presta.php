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
                throw new Exception('Getting data from pricing table successful.<br>');
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

        try
        {
            $sql = "INSERT INTO ps_product (date_add,    id_category_default,               ean13,                      price,                         wholesale_price,                           id_shop_default, on_sale, online_only, ecotax,     quantity, minimal_quantity, unit_price_ratio, additional_shipping_cost, width,      height,     depth,      weight,     out_of_stock, quantity_discount, customizable, uploadable_files, text_fields, active, redirect_type, id_product_redirected, available_for_order, available_date, show_price, indexed, visibility, cache_is_pack, cache_has_attachments, is_virtual, advanced_stock_management, pack_stock_type, icecatcode, eancode)
                    VALUES                 ('".$time."', '".$product['target_category']."', '".$product['ean_code']."', '".$product['sales_price']."', '".$product['supplier_purchase_price']."', 1,               0,       0,           0.000000,   0,        1,                0.000000,         0.00,                     0.000000,   0.000000,   0.000000,   0.000000,   2,            0,                 0,            0,                0,           0,      '',            0,                     1,                   '0000-00-00',   1,          0,       'both',     0,             0,                     0,          0,                         3,               '',         '');";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_product table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                throw new Exception('Inserting data to ps_product table successful.<br>');
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
            $sql = "INSERT INTO ps_product_shop (id_product,     date_add,    id_category_default,               price,                         wholesale_price,                           id_shop, on_sale, online_only, ecotax,     minimal_quantity, unit_price_ratio, additional_shipping_cost, customizable, uploadable_files, text_fields, active, redirect_type, id_product_redirected, available_for_order, available_date, show_price, indexed, visibility, advanced_stock_management, pack_stock_type)
                    VALUES                      ('".$last_id."', '".$time."', '".$product['target_category']."', '".$product['sales_price']."', '".$product['supplier_purchase_price']."', '1',     '0',     '0',         '0.000000', '1',              '0.000000',       '0.00',                   '0',          '0',              '0',         '0',    '',            '0',                   '1',                 '0000-00-00',   '1',        '0',     'both',     '0',                       '3');";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_product_shop table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                throw new Exception('Inserting data to ps_product_shop table successful.<br>');
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
            $sql = "INSERT INTO ps_product_lang (id_product, id_shop, id_lang, description, name, available_now)
                    VALUES (
                            '$last_id',
                            '1',
                            '1',
                            '".$product['product_desc']."',
                            '".$product['product_desc']."',
                            'Saatavilla'
                           );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting lang 1 data to ps_product_lang table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                throw new Exception('Inserting lang data to ps_product_lang table successful.<br>');
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
            $sql = "INSERT INTO ps_product_lang (id_product, id_shop, id_lang, description, name, available_now)
                    VALUES (
                            '$last_id',
                            '1',
                            '2',
                            '".$product['product_desc']."',
                            '".$product['product_desc']."',
                            'Saatavilla'
                           );";

            $res = mysqli_query($prestaconn, $sql);

            if($res === FALSE)
            {
                throw new Exception('Inserting lang 2 data to ps_product_lang table failed. '.$sql.'<br> '.mysqli_error($prestaconn).'<br>', 1);
            }
            else
            {
                throw new Exception('Inserting lang 2 data to ps_product_lang table successful.<br>');
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
                throw new Exception('Updating data to ps_product and ps_product_shop tables successful.<br>');
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
                throw new Exception('Deleting item from pricing table with EAN code '.$ean.' successful.<br>');
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }
