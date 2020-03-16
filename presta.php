<?php
    function startTransfer()
    {
        date_default_timezone_set('Europe/Helsinki');
        
        $result = getData();
        
        if(!$result)
        {
            return;
        }
        
        while($row = $result->fetch_assoc())
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
            $res = $conn->query("SELECT * FROM pricing");
            
            if($res->num_rows == 0)
            {
                throw new Exception('Getting data from pricing table failed.<br>', 1);
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
        $id = $prestaconn->query($sql);

        if($id->num_rows == 0)
        {
            insertIntoPresta($product);
        }
        else
        {
            updatePresta($product, $id);
        }
    }
    
    function insertIntoPresta($product)
    {
        global $prestaconn;
        
        $time = date("Y-m-d H:i:s");

        try
        {
            $sql = "INSERT INTO ps_product (date_add, ean13, id_category_default, price, wholesale_price)
                    VALUES (
                            '".$time."',
                            '".$product['ean_code']."',
                            '".$product['target_category']."',
                            '".$product['sales_price']."', 
                            '".$product['supplier_purchase_price']."'
                           );";

            $res = $prestaconn->query($sql);
            
            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_product table failed. '.$sql.'<br>', 1);
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
                return;
        }
        
        $sql = "SELECT id_product FROM ps_product WHERE ean13 = '".$product['ean_code']."';";

        $res = $prestaconn->query($sql);
        $id = $res->fetch_assoc();

        try
        {
            $sql = "INSERT INTO ps_product_shop (id_product, date_add, id_category_default, price, wholesale_price)
                    VALUES (
                            '".$id['id_product']."',
                            '".$time."',
                            '".$product['target_category']."',
                            '".$product['sales_price']."', 
                            '".$product['supplier_purchase_price']."'
                           );";
            
            $res = $prestaconn->query($sql);
            
            if($res === FALSE)
            {
                throw new Exception('Inserting data to ps_product_shop table failed. '.$sql.'<br>', 1);
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
                return;
        }
    }

    function updatePresta($product, $id)
    {
        global $prestaconn;

        $time = date("Y-m-d H:i:s");
        
        $prestaId = $id->fetch_assoc();
        
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
            $res = $prestaconn->query($sql);
            
            if($res === FALSE)
            {
                throw new Exception('Updating data to ps_product and ps_product_shop table failed. '.$sql.'<br>', 1);
            }
            else
            {
                throw new Exception('Updating data to ps_product and ps_product_shop table successful.<br>');
            }
        } 
        catch(Exception $ex) 
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }
    