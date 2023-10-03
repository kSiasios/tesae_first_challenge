<?php
class Products
{

    private $xml_file_path = '';

    public function __construct($xml_file_path = '')
    {
        $this->xml_file_path = $xml_file_path;
    }

    /**
     * This function prints an HTML table with all the products as read from the xml file
     * @return void 
     */
    public function print_html_table_with_all_products()
    {
        //TODO 1:Θα πρέπει να συμπληρώσουμε την συνάρτηση ώστε να κάνει print το HTML table με τα προϊόντα του xml
        $xmldata = simplexml_load_file($this->xml_file_path) or die("Failed to load");
        $xml_data = $xmldata->children();

        echo "<table class='products_table'>";
        echo "<thead>";
        echo "<tr>";

        // PRINT OUT THE COLUMN HEADERS ACCORDING TO THE ATTRIBUTES NAME ON THE XML FILE 

        foreach ($xml_data[1] as $record) {
            foreach ($record as $key => $value) {
                echo ("<th>" . $key . "</th>");
            }
            break;
        }

        echo "</tr>";
        echo "</thead>";

        foreach ($xml_data->PRODUCTS->PRODUCT as $key => $prod) {
            $this->print_html_of_one_product_line($prod);
        }
        echo "</table>";
    }

    /**
     * This function prints an HTML tr for a given product
     * @param mixed $prod It is the product object as retrieved from the xml file
     * @return void 
     */
    private function print_html_of_one_product_line($prod)
    {
        //TODO 2: Θα πρέπει να συμπληρώσουμε τη συνάρτηση ώστε να κάνει print τα tr με τα στοιχεία του ενός προϊόντος
        echo "<tr>";

        foreach ($prod as $key => $prod_attr) {
            if (!strcmp($key, "BARCODE")) {
                // if we have a barcode
                echo "<td class='barcode'>" . $prod_attr . "</td>";
                continue;
            }
            echo "<td>" . $prod_attr . "</td>";
        }
        echo "</tr>";
    }
}