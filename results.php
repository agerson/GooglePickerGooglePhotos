<?php

$elements = $_POST['elements'];
$elements = explode(',', $elements);

$url = $elements[0];
$img = 'gp_img/11'.time().'.gif';
file_put_contents($img, file_get_contents($url));

$url = $elements[1];
$img = 'gp_img/22'.time().'.gif';
file_put_contents($img, file_get_contents($url));

?>