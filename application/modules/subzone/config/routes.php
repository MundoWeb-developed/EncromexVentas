<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['subzones'] = "subzone/subzone/index";
$route['addsubzone'] = "subzone/subzone/create";
$route['savesubzone'] = "subzone/subzone/store";
$route['editzone/(:num)'] = "subzone/subzone/edit/$1";
$route['updatesubzone/(:num)'] = "subzone/subzone/update/$1";
$route['deletesubzone/(:num)'] = "subzone/subzone/destroy/$1";
$route['subzones/getByZone'] = "subzone/subzone/getByZone";


