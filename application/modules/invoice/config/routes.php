<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['add_invoice']         = "invoice/invoice/bdtask_invoice_form";
$route['pos_invoice']         = "invoice/invoice/bdtask_pos_invoice";
$route['gui_pos']             = "invoice/invoice/bdtask_gui_pos";
$route['gui_pos/inv/(:any)']  = 'invoice/number_generator_sucursal/$1';
$route['invoice_list']        = "invoice/invoice/bdtask_invoice_list";

$route['invoice_list_pc']        = "invoice/invoice/bdtask_invoice_list_pc";

$route['arrangement_list']        = "invoice/invoice/bdtask_arrangement_list";
$route['arrangement_list1']        = "invoice/invoice/bdtask_arrangement_list1";

$route['invoice_details/(:num)'] = 'invoice/invoice/bdtask_invoice_details/$1';
$route['quote_details/(:num)'] = 'invoice/invoice/bdtask_quote_details/$1';
$route['invoice_details_cs/(:num)'] = 'invoice/invoice/bdtask_invoice_details_cs/$1';

$route['invoice_details_del/(:num)'] = 'invoice/invoice/bdtask_invoice_details_del/$1';

$route['edit_gui_pos/(:num)']		 = "invoice/invoice/bdtask_edit_gui_pos/$1";

$route['invoice_pad_print/(:num)'] = 'invoice/invoice/bdtask_invoice_pad_print/$1';
$route['pos_print/(:num)']    = 'invoice/invoice/bdtask_invoice_pos_print/$1';
$route['invoice_pos_print']    = 'invoice/invoice/bdtask_pos_print_direct';
$route['download_invoice/(:num)']  = 'invoice/invoice/bdtask_download_invoice/$1';
$route['invoice_edit/(:num)'] = 'invoice/invoice/bdtask_edit_invoice/$1';
$route['invoice_print'] = 'invoice/invoice/invoice_inserted_data_manual';

$route['add_deliveryman']         = "invoice/invoice/bdtask_add_deliveryman";
$route['add_deliveryman/(:num)']    = 'invoice/invoice/bdtask_add_deliveryman/$1';
$route['deliveryman_list']        = "invoice/invoice/bdtask_deliveryman_list";


$route['add_branchoffice']         = "invoice/invoice/bdtask_add_branchoffice";
$route['add_branchoffice/(:num)']    = 'invoice/invoice/bdtask_add_branchoffice/$1';
$route['branchoffice_list']        = "invoice/invoice/bdtask_branchoffice_list";

// Socios comerciales (nuevas rutas)
$route['add_partner']         = "invoice/invoice/bdtask_add_partner";
$route['add_partner/(:num)']  = 'invoice/invoice/bdtask_add_partner/$1';
$route['partner_list']        = "invoice/invoice/bdtask_partner_list";

$route['add_zona']         = "invoice/invoice/bdtask_add_zona";
$route['add_zona/(:num)']  = 'invoice/invoice/bdtask_add_zona/$1';
$route['zona_list']        = "invoice/invoice/bdtask_zona_list";

$route['add_florist']         = "invoice/invoice/bdtask_add_florist";
$route['add_florist/(:num)']    = 'invoice/invoice/bdtask_add_florist/$1';
$route['florist_list']        = "invoice/invoice/bdtask_florist_list";
$route['insumos_invoice']        = "invoice/invoice/arr_insumos_invoice";

$route['product_form_arrp']         = "invoice/invoice/bdtask_product_form_arrp";

$route['accumulated']         = "invoice/invoice/bdtask_accumulated";