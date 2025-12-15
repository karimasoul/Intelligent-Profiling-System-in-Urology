<?php
$con = mysqli_connect("localhost","root","root","teste");

if(!$con){
    echo mysqli_connect_error();
    die();
}

echo "Database Connected Successfully";