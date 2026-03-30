<?php
$images = glob("uploads/*.{jpg,jpeg,png}", GLOB_BRACE);
echo count($images);
?>
