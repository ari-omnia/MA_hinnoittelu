function validateForm() {

    var mainform = "pricing";

    var supplier_file = document.forms[mainform]["supplier_file"].value;
    if (supplier_file == "") {
        alert("Pricing file is mandatory!");
        return false;
    }

    var manufacturer = document.forms[mainform]["manufacturer"].value;
    if (manufacturer == "") {
        alert("Manufacturer is mandatory!");
        return false;
    }

    var supplier_code = document.forms[mainform]["supplier_code"].value;
    if (supplier_code == "") {
        alert("Supplieris mandatory!");
        return false;
    }

    var product_code = document.forms[mainform]["product_code"].value;
    if (product_code == "") {
        alert("Product is mandatory!");
        return false;
    }

    var product_desc = document.forms[mainform]["product_desc"].value;
    if (product_desc == "") {
        alert("Product is mandatory!");
        return false;
    }

    var ean_code = document.forms[mainform]["ean_code"].value;
    if (ean_code == "") {
        alert("EAN is mandatory!");
        return false;
    }

    var category = document.forms[mainform]["category"].value;
    if (category == "") {
        alert("Category is mandatory!");
        return false;
    }

    var subcat1 = document.forms[mainform]["subcat1"].value;
    if (subcat1 == "") {
        alert("Category is mandatory!");
    return false;
    }

    var subcat2 = document.forms[mainform]["subcat2"].value;
    if (subcat2 == "") {
        alert("Category is mandatory!");
    return false;
    }

    var supplier_purchase_price = document.forms[mainform]["supplier_purchase_price"].value;
    if (supplier_purchase_price == "") {
        alert("Purchase price is mandatory!");
    return false;
    }

    var new_purchase_price = document.forms[mainform]["new_purchase_price"].value;
    if (new_purchase_price == "") {
        alert("Purchase price is mandatory!");
    return false;
    }

    var sales_price = document.forms[mainform]["sales_price"].value;
    if (sales_price == "") {
        alert("Purchase price is mandatory!");
    return false;
    }





}
