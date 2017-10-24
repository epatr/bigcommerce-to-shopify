<?php
error_reporting(-1);

require("config.php");

$xml = simplexml_load_file($config_xml_file,"SimpleXMLElement",LIBXML_NOCDATA);

if($xml ===  FALSE) {
    echo "Error loading XML file.";
} else {
    //print_r($xml);

//    $stringify = simplexml_load_string($xml);
//    print_r($stringify);

//    foreach ($xml->products as $product) {
//        print_r($product);
//

    $csv = "Handle,Title,Body (HTML),Vendor,Type,Tags,Published,Option1 Name,Option1 Value,Option2 Name,Option2 Value,Option3 Name,Option3 Value,Variant SKU,Variant Grams,Variant Inventory Tracker,Variant Inventory Qty,Variant Inventory Policy,Variant Fulfillment Service,Variant Price,Variant Compare At Price,Variant Requires Shipping,Variant Taxable,Variant Barcode,Image Src,Image Alt Text,Gift Card,Google Shopping / MPN,Google Shopping / Age Group,Google Shopping / Gender,Google Shopping / Google Product Category,SEO Title,SEO Description,Google Shopping / AdWords Grouping,Google Shopping / AdWords Labels,Google Shopping / Condition,Google Shopping / Custom Product,Google Shopping / Custom Label 0,Google Shopping / Custom Label 1,Google Shopping / Custom Label 2,Google Shopping / Custom Label 3,Google Shopping / Custom Label 4,Variant Image,Variant Weight Unit
";

    foreach($xml->product as $product) {

        $array = [];

        $array[] = $product->Product_URL;
        $array[] = str_replace('"', "''", $product->Product_Name);
        $array[] = "\"" . html_entity_decode(str_replace("\"", "\"\"", $product->Description)) . "\"";
        $array[] = "BigCommerce"; // $product->Brand;
        $array[] = $product->Category_Details->item->Category_Name;
        $array[] = "BigCommerce"; // tags
        $array[] = "TRUE"; // $product->published;
        $array[] = "Title"; // $product->option1_name;
        $array[] = "Default Title"; // $product->option1_value;
        $array[] = $product->option2_name;
        $array[] = $product->option2_value;
        $array[] = $product->option3_name;
        $array[] = $product->option3_value;
        $array[] = $product->variant_sku;
        $array[] = "0.0"; // $product->variant_grams;
        $array[] = $product->variant_inventory_tracker;
        $array[] = "5"; // $product->variant_inventory_qty;
        $array[] = "continue"; // $product->variant_inventory_policy;
        $array[] = "manual"; // $product->variant_fulfillment_service;
        $array[] = $product->Price;
        $array[] = $product->variant_compare_at_price;
        $array[] = "TRUE"; // $product->variant_requires_shipping;
        $array[] = $product->variant_taxable;
        $array[] = $product->variant_barcode;
        $array[] = $product->Product_Images->item->Product_Image_URL;
        $array[] = $product->image_alt_text;
        $array[] = "FALSE"; // $product->gift_card;
        $array[] = $product->google_shopping_mpn;
        $array[] = $product->GPS_Age_Group;
        $array[] = $product->GPS_Gender;
        $array[] = $product->GPS_Category;
        $array[] = "\"" . $product->Page_Title . "\"";
        $array[] = "\"" . $product->Meta_Description . "\"";
        $array[] = $product->google_shopping_adwords_grouping;
        $array[] = $product->google_shopping_adwords_labels;
        $array[] = $product->google_shopping_condition;
        $array[] = $product->google_shopping_custom_product;
        $array[] = $product->google_shopping_custom_label_0;
        $array[] = $product->google_shopping_custom_label_1;
        $array[] = $product->google_shopping_custom_label_2;
        $array[] = $product->google_shopping_custom_label_3;
        $array[] = $product->google_shopping_custom_label_4;
        $array[] = $product->variant_image;
        $array[] = "lb"; // $product->variant_weight_unit;

        // Iterate through the array and append each item to the csv variable
        foreach ($array as $product_csv) {
            $csv .= $product_csv . ",";
        }
        $csv .= "\r\n";

        // Add new lines for additional images
        foreach ($product->Product_Images->item as $image) {
            $producthandle = $product->Product_URL;
            $producttitle = str_replace('"', "''", $product->Product_Name);
            $imgurl = $image->Product_Image_URL;
            $csv .= "$producthandle,,,,,,,,,,,,,,,,,,,,,,,,$imgurl,,,,,,,,,,,,,,,,,,,,\r\n";
        }


    }

    $f = fopen('shopify.csv', 'w');
    $fwrite = fwrite($f, $csv);
    if ($fwrite === false) {
        echo "ERROR!";
    }

    echo $csv;
}