<?php
defined('BASEPATH') OR exit('No direct script access allowed');



$route['add_purchase']         = "purchase/purchase/bdtask_purchase_form";
$route['purchase_list']        = "purchase/purchase/bdtask_purchase_list";
$route['purchase_details/(:num)'] = 'purchase/purchase/bdtask_purchase_details/$1';
$route['purchase_edit/(:num)'] = 'purchase/purchase/bdtask_purchase_edit_form/$1';


/* insumos */

$route['add_insumo']         = "purchase/purchase/bdtask_insumo_form";
$route['insumo_purchase_list']        = "purchase/purchase/bdtask_insumo_purchase_list";
$route['insumo_purchase_edit/(:num)'] = 'purchase/purchase/bdtask_insumo_purchase_edit_form/$1';

/*$route['insumo_purchase_details/(:num)'] = 'purchase/purchase/bdtask_insumo_purchase_details/$1';*/
