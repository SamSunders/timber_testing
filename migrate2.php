<?php
include_once 'wp-config.php';

$olddomain = $_POST['old_domain'];

$newdomain = $_POST['new_domain'];

if($olddomain && $newdomain) 
{
    $db = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Connection Error: " . mysql_error());
    mysql_select_db(DB_NAME) or die("Error connecting to db.");

    $SQL = "UPDATE ".$table_prefix."options SET option_value = REPLACE(option_value,'$olddomain','$newdomain') WHERE option_name IN ('siteurl','home','fileupload_url');";
    mysql_query($SQL);
    
    $SQL = "UPDATE `".$table_prefix."posts` SET `post_content` = REPLACE(`post_content`,'$olddomain','$newdomain');";
    mysql_query($SQL);
    
    $SQL = "UPDATE `".$table_prefix."posts` SET `guid` = REPLACE(`guid`,'$olddomain','$newdomain');";
    mysql_query($SQL);

    $SQL = "UPDATE `".$table_prefix."postmeta` SET `meta_value` = REPLACE(`meta_value`,'$olddomain','$newdomain');";
    mysql_query($SQL);
    
    echo "Migration from '$olddomain' to '$newdomain' successfull.";
}
else
    echo "Old/new domain values must not be empty. Old domain: $olddomain, New domain: $newdomain ";
?>
