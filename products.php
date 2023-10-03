<!DOCTYPE html>
<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Extended+Text&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="global.css" rel="stylesheet">
</head>

<body>
    <div class="page_content">
        <div class="theme_changer">
            <button class="theme_toggle" onclick="changeTheme()">
                <span class="material-symbols-outlined">
                    light_mode
                </span>
            </button>
            <div class="promo">
                First Challenge completed by <a href="https://linkedin.com/in/konstantinos-siasios"
                    target="_blank">Konstantinos
                    Siasios</a>
            </div>
        </div>
        <div class="product_list_container">
            <h1 id="page_header">List of products</h1>

            <?php
            require_once("./lib.php");
            $productsList = new Products("./products.xml");
            $productsList->print_html_table_with_all_products();

            // CHECK IF THE FORM WAS SUBMITTED,
            //      IF SO, WE NEED TO CREATE A NEW PRODUCT WITH THE NEW DATA
            if (isset($_POST['submit'])) {

                $xml_file = new DOMDocument("1.0", "UTF-8");
                $xml_file->load("./products.xml");

                $products_list = $xml_file->getElementsByTagName("PRODUCTS")->item(0);

                $p_name = $_POST["p_name"];

                if (!isset($p_name) || !strcmp($p_name, "")) {
                    // IN CASE THAT THE NAME WAS NOT PROVIDED, WE CANNOT PROCEED TO THE PRODUCT ADDITION
                    echo "<div class='warning'>The field NAME is mandatory!!!</div>";
                } else {
                    // GET THE DATA FOR EVERY PRODUCT ATTRIBUTE
                    $p_price = $_POST["p_price"];
                    $p_quantity = $_POST["p_quantity"];
                    $p_category = $_POST["p_category"];
                    $p_manufacturer = $_POST["p_manufacturer"];
                    $p_barcode = $_POST["p_barcode"];
                    $p_weight = $_POST["p_weight"];
                    $p_instock = $_POST["p_instock"];
                    $p_availability = $_POST["p_availability"];

                    // PREPARE OBJECT TO BE WRITTEN TO THE XML FILE
                    $new_product = $xml_file->createElement("PRODUCT");
                    $name = $xml_file->createElement("NAME");
                    $name->appendChild($xml_file->createCDATASection($p_name));

                    $price = $xml_file->createElement("PRICE", $p_price);
                    $quantity = $xml_file->createElement("QUANTITY", $p_quantity);
                    $category = $xml_file->createElement("CATEGORY", $p_category);
                    $manufacturer = $xml_file->createElement("MANUFACTURER", $p_manufacturer);

                    $barcode = $xml_file->createElement("BARCODE");
                    $barcode->appendChild($xml_file->createCDATASection($p_barcode));

                    $weight = $xml_file->createElement("WEIGHT");
                    $weight->appendChild($xml_file->createCDATASection($p_weight . "kg"));

                    $instock = $xml_file->createElement("INSTOCK", $p_instock);

                    $avail_val = "";
                    switch ($p_availability) {
                        case '1':
                            $avail_val = "Άμεσα Διαθέσιμο";
                            break;
                        case '2':
                            $avail_val = "2-4 Εργάσιμες Μέρες";
                            break;
                        case '3':
                            $avail_val = "4-10 Εργάσιμες Μέρες";
                            break;
                        default:
                            $avail_val = "Κατόπιν Παραγγελίας";
                            break;
                    }
                    $availability = $xml_file->createElement("AVAILABILITY", $avail_val);

                    $new_product->appendChild($name);
                    $new_product->appendChild($price);
                    $new_product->appendChild($quantity);
                    $new_product->appendChild($category);
                    $new_product->appendChild($manufacturer);
                    $new_product->appendChild($barcode);
                    $new_product->appendChild($weight);
                    $new_product->appendChild($instock);
                    $new_product->appendChild($availability);

                    $products_list->appendChild($new_product);

                    $xml_file->save("./products.xml");

                    // REFRESH FOR THE CHANGES TO BE SEEN
                    header("Refresh:0");
                }
            }

            ?>

        </div>
        <div class="form_container">
            <form class="product_form" action="/first_challenge/products.php" method="POST">
                <h1 id="page_header">New Product Form</h1>
                <label for="p_name">NAME:</label>
                <input type="text" id="name" name="p_name">
                <label for="p_price">PRICE:</label>
                <input type="number" step="0.01" id="price" name="p_price">
                <label for="p_quantity">QUANTITY:</label>
                <input type="number" min="0" id="quantity" name="p_quantity">
                <label for="p_category">CATEGORY:</label>
                <input type="text" id="category" name="p_category" value="BOARDS-&gt;Complete Skateboards">
                <label for="p_manufacturer">MANUFACTURER:</label>
                <input type="text" id="manufacturer" name="p_manufacturer">
                <label for="p_barcode">BARCODE:</label>
                <input type="text" id="barcode" name="p_barcode">
                <label for="p_weight">WEIGHT:</label>
                <input type="number" step="0.01" id="weight" name="p_weight">
                <label for="p_instock">IN STOCK:</label>
                <select name="p_instock" id="instock" value="Y">
                    <option value="Y">Yes</option>
                    <option value="N">No</option>
                </select>

                <label for="p_availability">AVAILABILITY:</label>
                <select name="p_availability" id="availability" value="1">
                    <option value="1">Άμεσα Διαθέσιμο</option>
                    <option value="2">2-4 Εργάσιμες Μέρες</option>
                    <option value="3">4-10 Εργάσιμες Μέρες</option>
                    <option value="4">Κατόπιν Παραγγελίας</option>
                </select>

                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>

    <script>
    // A SCRIPT TO SET AND CHANGE THE THEME OF THE PAGE USING THE CSS ATTRIBUTE "theme" OF THE ROOT ELEMENT
    // THE THEME PREFERENCE IS SAVED IN THE LOCALSTORAGE
    const rootElem = document.documentElement;

    let theme = localStorage.getItem("preferredTheme");

    if (!theme) {
        theme = "dark";
    }

    setTheme(theme);

    function changeTheme() {
        switch (theme) {
            case "light":
                setTheme("dark");
                break;
            default:
                setTheme("light");
                break;
        }
    }

    function setTheme(val) {
        const btn = document.getElementsByClassName("theme_toggle")[0];
        rootElem.setAttribute("theme", val);
        btn.innerHTML = `<span class="material-symbols-outlined">${val}_mode</span>`;
        localStorage.setItem("preferredTheme", val);
        theme = val;
    }
    </script>
</body>

</html>