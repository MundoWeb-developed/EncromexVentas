<div class="sidebar">
    <!-- Sidebar user panel -->

    <div class="user-panel text-center">
        <div class="image">
            <?php $image = $this->session->userdata('image') ?>
            <img src="<?php echo base_url((!empty($image) ? $image : 'assets/img/icons/default.jpg')) ?>" class="img-circle" alt="User Image">
        </div>
        <div class="info">
            <p><?php echo $this->session->userdata('fullname') ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $this->session->userdata('user_level') ?></a>

        </div>
    </div>
    <!-- sidebar menu -->
    <?php
    $user_type = $this->session->userdata('user_type');
    if ($user_type == '1') {
    ?>

        <ul class="sidebar-menu">

            <li class="treeview <?php echo (($this->uri->segment(1) == "home") ? "active" : null) ?>">
                <a href="<?php echo base_url('home') ?>"> <i class="ti-dashboard"></i> <span><?php echo display('dashboard') ?></span>
                </a>
            </li>

            <!-- Invoice menu start -->
            <?php if ($this->permission1->method('new_invoice', 'create')->access() || $this->permission1->method('manage_invoice', 'read')->access() || $this->permission1->method('pos_invoice', 'create')->access() || $this->permission1->method('gui_pos', 'create')->access()) { ?>
                <li class="treeview <?php
                                    if ($this->uri->segment('1') == ("add_invoice") || $this->uri->segment('1') == ("invoice_list") || $this->uri->segment('1') == ("pos_invoice") || $this->uri->segment('1') == ("gui_pos") || $this->uri->segment('1') == ("invoice_details") || $this->uri->segment('1') == ("invoice_pad_print") || $this->uri->segment('1') == ("pos_print") || $this->uri->segment('1') == ("invoice_edit")) {
                                        echo "active";
                                    } else {
                                        echo " ";
                                    }
                                    ?>">
                    <a href="#">
                        <i class="fa fa-balance-scale"></i><span><?php echo display('invoice') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">

                        <li class="treeview <?php if ($this->uri->segment('1') == ("accumulated")) {
                                                echo "active";
                                            } else {
                                                echo " ";
                                            } ?>">
                            <a href="<?php echo base_url('accumulated') ?>">Venta acumulada</a>
                        </li>

                        <?php if ($this->permission1->method('new_invoice', 'create')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("add_invoice")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>"><a href="<?php echo base_url('add_invoice') ?>"><?php echo display('new_invoice') ?></a></li>
                        <?php } ?>


                        <?php if ($this->permission1->method('manage_invoice', 'read')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("invoice_list")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>"><a href="<?php echo base_url('invoice_list') ?>"><?php echo display('manage_invoice') ?></a></li>
                        <?php } ?>




                        <li class="treeview <?php
                                            if ($this->uri->segment('1') == ("invoice_list_pc")) {
                                                echo "active";
                                            } else {
                                                echo " ";
                                            }
                                            ?>"><a href="<?php echo base_url('invoice_list_pc') ?>">Ventas por credito interno</a></li>




                        <?php if ($this->permission1->method('pos_invoice', 'create')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("gui_pos")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>"><a href="<?php echo base_url('gui_pos') ?>"><?php echo display('pos_invoice') ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($this->permission1->method('add_customer', 'create')->access() || $this->permission1->method('manage_customer', 'read')->access() || $this->permission1->method('credit_customer', 'read')->access() || $this->permission1->method('paid_customer', 'read')->access() || $this->permission1->method('customer_ledger', 'read')->access() || $this->permission1->method('customer_advance', 'create')->access()) { ?>
                <li class="treeview <?php echo (($this->uri->segment(1) == "add_customer" || $this->uri->segment(1) == "customer_list" || $this->uri->segment(1) == "credit_customer" || $this->uri->segment(1) == "paid_customer" || $this->uri->segment(1) == "edit_customer" || $this->uri->segment(1) == "customer_ledgerdata" || $this->uri->segment(1) == "customer_ledger" || $this->uri->segment(1) == "advance_receipt" || $this->uri->segment(1) == "customer_advance") ? "active" : '') ?>">

                    <a href="javascript:void(0)">

                        <i class="metismenu-icon pe-7s-user"></i> <span><?php echo display('customer') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>

                    <ul class="treeview-menu">

                        <?php if ($this->permission1->method('add_customer', 'create')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "add_customer") ? "active" : '') ?>">
                                <a href="<?php echo base_url('add_customer') ?>" class="<?php echo (($this->uri->segment(1) == "add_customer") ? "active" : null) ?>"> <?php echo display('add_customer') ?>

                                </a>

                            </li>
                        <?php } ?>



                        <!--<?php if ($this->permission1->method('manage_customer', 'read')->access()) { ?>
            <li class="<?php echo (($this->uri->segment(1) == "customer_list") ? "active" : '') ?>">
                <a href="<?php echo base_url('customer_list') ?>">
                   
                    <?php echo display('customer_list') ?>
                   
                </a>
              
            </li>
        <?php } ?>-->



                        <?php if ($this->permission1->method('manage_customer', 'read')->access()) { ?>
                            <!-- <li class="<?php echo (($this->uri->segment(1) == "customer_list") ? "active" : '') ?>">
                                <a href="<?php echo base_url('customer_nlist') ?>">

                                    <?php echo display('customer_list') ?>

                                </a>

                            </li> -->
                        <?php } ?>




                        <?php if ($this->permission1->method('credit_customer', 'read')->access()) { ?>

                            <!-- <li class="<?php echo (($this->uri->segment(1) == "credit_customer") ? "active" : '') ?>">
                                <a href="<?php echo base_url('credit_customer') ?>">

                                    <?php echo display('credit_customer') ?>

                                </a>

                            </li> -->
                        <?php } ?>
                        <?php if ($this->permission1->method('paid_customer', 'read')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "paid_customer") ? "active" : '') ?>">
                                <a href="<?php echo base_url('paid_customer') ?>">

                                    <?php echo display('customer_list') ?>

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('customer_ledger', 'read')->access()) { ?>
                            <!-- <li class="<?php echo (($this->uri->segment(1) == "customer_ledger" || $this->uri->segment(1) == "customer_ledgerdata") ? "active" : '') ?>">
                                <a href="<?php echo base_url('customer_ledger') ?>">

                                    <?php echo display('customer_ledger') ?>

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('customer_advance', 'create')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "customer_advance") ? "active" : '') ?>">
                                <a href="<?php echo base_url('customer_advance') ?>">

                                    <?php echo display('customer_advance') ?>

                                </a>

                            </li> -->
                        <?php } ?>
                    </ul>

                </li>
            <?php } ?>

            <!-- supplier menu part -->
            <?php if ($this->permission1->method('add_supplier', 'create')->access() || $this->permission1->method('manage_supplier', 'read')->access() || $this->permission1->method('supplier_ledger', 'read')->access() || $this->permission1->method('supplier_advance', 'create')->access()) { ?>
                <li class="treeview <?php echo (($this->uri->segment(1) == "add_supplier" || $this->uri->segment(1) == "supplier_list" || $this->uri->segment(1) == "edit_supplier" || $this->uri->segment(1) == "supplier_ledgerdata" || $this->uri->segment(1) == "supplier_ledger" || $this->uri->segment(1) == "supplier_advance") ? "active" : '') ?>">

                    <a href="javascript:void(0)">

                        <i class="metismenu-icon fa fa-user-secret"></i> <span><?php echo display('supplier') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>

                    <ul class="treeview-menu">

                        <?php if ($this->permission1->method('add_supplier', 'create')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "add_supplier") ? "active" : '') ?>">
                                <a href="<?php echo base_url('add_supplier') ?>" class="<?php echo (($this->uri->segment(1) == "add_supplier") ? "active" : null) ?>"> <?php echo display('add_supplier') ?>

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('manage_supplier', 'read')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "supplier_list") ? "active" : '') ?>">
                                <a href="<?php echo base_url('supplier_list') ?>">

                                    <?php echo display('supplier_list') ?>

                                </a>

                            </li>
                        <?php } ?>

                        <?php if ($this->permission1->method('supplier_ledger', 'read')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "supplier_ledger" || $this->uri->segment(1) == "supplier_ledgerdata") ? "active" : '') ?>">
                                <a href="<?php echo base_url('supplier_ledger') ?>">

                                    <?php echo display('supplier_ledger') ?>

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('supplier_advance', 'create')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "supplier_advance") ? "active" : '') ?>">
                                <a href="<?php echo base_url('supplier_advance') ?>">

                                    <?php echo display('supplier_advance') ?>

                                </a>

                            </li>
                        <?php } ?>
                    </ul>

                </li>
            <?php } ?>

            <!-- product menu part -->
            <?php if ($this->permission1->method('create_product', 'create')->access() || $this->permission1->method('add_product_csv', 'create')->access() || $this->permission1->method('manage_product', 'read')->access() || $this->permission1->method('create_category', 'create')->access() || $this->permission1->method('manage_category', 'read')->access() || $this->permission1->method('add_unit', 'create')->access() || $this->permission1->method('manage_unit', 'read')->access()) { ?>
                <li class="treeview <?php echo (($this->uri->segment(1) == "category_form" || $this->uri->segment(1) == "category_list" || $this->uri->segment(1) == "unit_form" || $this->uri->segment(1) == "unit_list" || $this->uri->segment(1) == "product_form" || $this->uri->segment(1) == "product_list" || $this->uri->segment(1) == "barcode" || $this->uri->segment(1) == "qrcode" || $this->uri->segment(1) == "bulk_products" || $this->uri->segment(1) == "product_details") ? "active" : '') ?>">

                    <a href="javascript:void(0)">

                        <i class="metismenu-icon fa fa-cubes"></i> <span><?php echo display('product') ?>/Insumos</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>

                    <ul class="treeview-menu">

                        <?php if ($this->permission1->method('category', 'create')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "category_form") ? "active" : '') ?>">
                                <a href="<?php echo base_url('category_form') ?>"> <?php echo display('add_category') ?> de producto

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('manage_category', 'read')->access() || $this->permission1->method('manage_category', 'update')->access() || $this->permission1->method('manage_category', 'delete')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "category_list") ? "active" : '') ?>">
                                <a href="<?php echo base_url('category_list') ?>">

                                    <?php echo display('category_list') ?> de productos

                                </a>

                            </li>
                        <?php } ?>

                        <li class="<?php echo (($this->uri->segment(1) == "category_form") ? "active" : '') ?>">
                            <a href="<?php echo base_url('category_insumo_form') ?>"> <?php echo display('add_category') ?> de insumo</a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(1) == "category_list") ? "active" : '') ?>">
                            <a href="<?php echo base_url('category_insumo_list') ?>">
                                <?php echo display('category_list') ?> de insumos
                            </a>
                        </li>


                        <?php if ($this->permission1->method('unit', 'create')->access() || $this->permission1->method('unit', 'update')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "unit_form") ? "active" : '') ?>">
                                <a href="<?php echo base_url('unit_form') ?>"> <?php echo display('add_unit') ?>

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('manage_unit', 'create')->access() || $this->permission1->method('manage_unit', 'read')->access() || $this->permission1->method('manage_unit', 'delete')->access() || $this->permission1->method('manage_unit', 'update')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "unit_list") ? "active" : '') ?>">
                                <a href="<?php echo base_url('unit_list') ?>">

                                    <?php echo display('unit_list') ?>

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('create_product', 'create')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "product_form") ? "active" : '') ?>">
                                <a href="<?php echo base_url('product_form') ?>">

                                    <?php echo display('add_product') ?>

                                </a>

                            </li>
                        <?php } ?>
                        <?php if ($this->permission1->method('manage_product', 'read')->access()) { ?>
                            <li class="<?php echo (($this->uri->segment(1) == "product_list") ? "active" : '') ?>">
                                <a href="<?php echo base_url('product_list') ?>">

                                    <?php echo display('manage_product') ?>

                                </a>

                            </li>
                        <?php } ?>

                        <li class="<?php echo (($this->uri->segment(1) == "insumo_form") ? "active" : '') ?>">
                            <a href="<?php echo base_url('insumo_form') ?>">

                                <?php echo display('add_insumo') ?>

                            </a>

                        </li>
                        <li class="<?php echo (($this->uri->segment(1) == "insumo_list") ? "active" : '') ?>">
                            <a href="<?php echo base_url('insumo_list') ?>">

                                <?php echo display('manage_insumo') ?>

                            </a>

                        </li>

                    </ul>

                </li>
            <?php } ?>



            <!-- Purchase menu start -->
            <?php if ($this->permission1->method('add_purchase', 'create')->access() || $this->permission1->method('manage_purchase', 'read')->access()) { ?>
                <li class="treeview <?php
                                    if ($this->uri->segment('1') == ("add_purchase") || $this->uri->segment('1') == ("purchase_edit") || $this->uri->segment('1') == ("purchase_list") || $this->uri->segment('1') == ("purchase_details")) {
                                        echo "active";
                                    } else {
                                        echo " ";
                                    }
                                    ?>">
                    <a href="#">
                        <i class="ti-shopping-cart"></i><span><?php echo display('purchase') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($this->permission1->method('add_purchase', 'create')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("add_purchase")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>"><a href="<?php echo base_url('add_purchase') ?>"><?php echo display('add_purchase') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('manage_purchase', 'read')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("purchase_list")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>"><a href="<?php echo base_url('purchase_list') ?>"><?php echo display('manage_purchase') ?></a></li>
                        <?php } ?>

                        <li class="treeview"><a href="<?php echo base_url('add_insumo') ?>">Compra de insumo</a></li>
                        <li class="treeview"><a href="<?php echo base_url('insumo_purchase_list') ?>">Lista de compras de insumo</a></li>


                    </ul>
                </li>
            <?php } ?>
            <!-- Purchase menu end -->
            <li class="treeview <?php echo (($this->uri->segment(1) == "add_branchoffice" || $this->uri->segment(1) == "branchoffice_list" || $this->uri->segment(1) == "add_zona" || $this->uri->segment(1) == "zona_list" || $this->uri->segment(1) == "subzones" || $this->uri->segment(1) == "addsubzone") ? "active" : '') ?>">

                <a href="javascript:void(0)">
                    <i class="metismenu-icon fa fa-cubes"></i>
                    <span>Sucursales/Zona</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li class="<?php echo (($this->uri->segment(1) == "add_branchoffice") ? "active" : '') ?>">
                        <a href="<?php echo base_url('add_branchoffice') ?>">
                            Agregar sucursal
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "branchoffice_list") ? "active" : '') ?>">
                        <a href="<?php echo base_url('branchoffice_list') ?>">
                            Ver sucursales
                        </a>
                    </li>

                    <li class="<?php echo (($this->uri->segment(1) == "add_zona") ? "active" : '') ?>">
                        <a href="<?php echo base_url('add_zona') ?>">
                            Agregar zona
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "zona_list") ? "active" : '') ?>">
                        <a href="<?php echo base_url('zona_list') ?>">
                            Ver zonas
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "addsubzone") ? "active" : '') ?>">
                        <a href="<?php echo base_url('addsubzone') ?>">
                            Agregar subzona
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "subzones") ? "active" : '') ?>">
                        <a href="<?php echo base_url('subzones') ?>">
                            Ver subzonas
                        </a>
                    </li>
                </ul>

            </li>
            <!-- Stock menu start -->
            <?php if ($this->permission1->method('stock', 'read')->access()) { ?>
                <li class="treeview <?php
                                    if ($this->uri->segment('1') == ("stock")) {
                                        echo "active";
                                    } else {
                                        echo " ";
                                    }
                                    ?>">
                    <a href="#">
                        <i class="ti-bar-chart"></i><span><?php echo display('stock') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($this->permission1->method('stock_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("insumos_stock")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('insumos_stock') ?>"><?php echo display('stock_report') ?></a></li>
                        <?php } ?>

                    </ul>
                </li>
            <?php } ?>
            <!-- Stock menu end -->
            <!-- Report menu start -->
            <?php if ($this->permission1->method('add_closing', 'create')->access() || $this->permission1->method('closing_report', 'read')->access() || $this->permission1->method('todays_report', 'read')->access() || $this->permission1->method('todays_customer_receipt', 'read')->access() || $this->permission1->method('todays_sales_report', 'read')->access() || $this->permission1->method('due_report', 'read')->access() || $this->permission1->method('todays_purchase_report', 'read')->access() || $this->permission1->method('purchase_report_category_wise', 'read')->access() || $this->permission1->method('product_sales_reports_date_wise', 'read')->access() || $this->permission1->method('sales_report_category_wise', 'read')->access() || $this->permission1->method('shipping_cost_report', 'read')->access()) { ?>
                <li class="treeview <?php
                                    if ($this->uri->segment('1') == ("closing_form") || $this->uri->segment('1') == ("closing_report") || $this->uri->segment('1') == ("closing_report_search") || $this->uri->segment('1') == ("todays_report") || $this->uri->segment('1') == ("todays_customer_received") || $this->uri->segment('1') == ("todays_customerwise_received") || $this->uri->segment('1') == ("sales_report") || $this->uri->segment('1') == ("datewise_sales_report") || $this->uri->segment('1') == ("userwise_sales_report") || $this->uri->segment('1') == ("invoice_wise_due_report") || $this->uri->segment('1') == ("shipping_cost_report") || $this->uri->segment('1') == ("purchase_report") || $this->uri->segment('1') == ("purchase_report_categorywise") || $this->uri->segment('1') == ("product_wise_sales_report") || $this->uri->segment('1') == ("category_sales_report") || $this->uri->segment('1') == ("sales_return") || $this->uri->segment('1') == ("supplier_returns") || $this->uri->segment('1') == ("tax_report") || $this->uri->segment('1') == ("profit_report")) {
                                        echo "active";
                                    } else {
                                        echo " ";
                                    }
                                    ?>">
                    <a href="#">
                        <i class="ti-book"></i><span><?php echo display('report') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($this->permission1->method('add_closing', 'create')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("closing_form")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('closing_form') ?>"><?php echo display('closing') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('closing_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("closing_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('closing_report') ?>"><?php echo display('closing_report') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('todays_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("todays_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('todays_report') ?>"><?php echo display('todays_report') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('todays_customer_receipt', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("todays_customer_received")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('todays_customer_received') ?>"><?php echo display('todays_customer_receipt') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('todays_sales_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("sales_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('sales_report') ?>"><?php echo display('sales_report') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('user_wise_sales_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("userwise_sales_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('userwise_sales_report') ?>"><?php echo display('user_wise_sales_report') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('due_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("invoice_wise_due_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('invoice_wise_due_report') ?>"><?php echo display('due_report'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('shipping_cost_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("shipping_cost_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('shipping_cost_report') ?>"><?php echo display('shipping_cost_report'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('purchase_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("purchase_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('purchase_report') ?>"><?php echo display('purchase_report') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('purchase_report_category_wise', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("purchase_report_categorywise")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('purchase_report_categorywise') ?>"><?php echo display('purchase_report_category_wise') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('sales_report_product_wise', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("product_wise_sales_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('product_wise_sales_report') ?>"><?php echo display('sales_report_product_wise') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('sales_report_category_wise', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("category_sales_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('category_sales_report') ?>"><?php echo display('sales_report_category_wise') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('invoice_return', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("sales_return")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('sales_return') ?>"><?php echo display('invoice_return') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('supplier_return', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("supplier_returns")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('supplier_returns') ?>"><?php echo display('supplier_return') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('tax_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("tax_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('tax_report') ?>"><?php echo display('tax_report') ?></a></li>
                        <?php } ?>
                        <?php if ($this->permission1->method('profit_report', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("profit_report")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('profit_report') ?>"><?php echo display('profit_report') ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <!-- Report menu end -->

            <!-- Comission start -->
            <?php if ($this->permission1->method('commission', 'create')->access() || $this->permission1->method('commission', 'read')->access()) { ?>
                <li class="treeview <?php
                                    if ($this->uri->segment('1') == ("commission") || $this->uri->segment('1') == ("commission_generate")) {
                                        echo "active";
                                    } else {
                                        echo " ";
                                    }
                                    ?>">
                    <a href="#">
                        <i class="ti-layout-grid2"></i><span><?php echo display('commission') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($this->permission1->method('commission', 'read')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("commission") || $this->uri->segment('1') == ("commission_generate")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>"><a href="<?php echo base_url('commission') ?>"><?php echo display('generate_commission') ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <!-- Comission end -->

            <!-- Software Settings menu start -->
            <?php if ($this->permission1->method('manage_company', 'read')->access() || $this->permission1->method('manage_company', 'create')->access() || $this->permission1->method('add_user', 'create')->access() || $this->permission1->method('add_user', 'read')->access() || $this->permission1->method('add_language', 'create')->access() || $this->permission1->method('add_currency', 'create')->access() || $this->permission1->method('soft_setting', 'create')->access() || $this->permission1->method('add_role', 'create')->access() || $this->permission1->method('role_list', 'read')->access() || $this->permission1->method('user_assign', 'create')->access() || $this->permission1->method('sms_configure', 'create')->access()) { ?>
                <li class="treeview <?php
                                    if ($this->uri->segment('1') == ("company_list") || $this->uri->segment('1') == ("edit_company") || $this->uri->segment('1') == ("add_user") || $this->uri->segment('1') == ("user_list") || $this->uri->segment('1') == ("language") || $this->uri->segment('1') == ("currency_form") || $this->uri->segment('1') == ("currency_list") || $this->uri->segment('1') == ("settings") || $this->uri->segment('1') == ("mail_setting") || $this->uri->segment('1') == ("app_setting") || $this->uri->segment('1') == ("add_role") || $this->uri->segment('1') == ("role_list") || $this->uri->segment('1') == ("edit_role") || $this->uri->segment('1') == ("assign_role") || $this->uri->segment('1') == ("sms_setting") || $this->uri->segment('1') == ("restore") || $this->uri->segment('1') == ("db_import") || $this->uri->segment('1') == ("editPhrase") || $this->uri->segment('1') == ("phrases")) {
                                        echo "active";
                                    } else {
                                        echo " ";
                                    }
                                    ?>">
                    <a href="#">
                        <i class="ti-settings"></i><span><?php echo display('settings') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Software Settings menu start -->
                        <?php if ($this->permission1->method('manage_company', 'read')->access() || $this->permission1->method('manage_company', 'create')->access() || $this->permission1->method('add_user', 'create')->access() || $this->permission1->method('manage_user', 'read')->access() || $this->permission1->method('add_language', 'create')->access() || $this->permission1->method('add_currency', 'create')->access() || $this->permission1->method('soft_setting', 'create')->access() || $this->permission1->method('back_up', 'create')->access() || $this->permission1->method('back_up', 'read')->access() || $this->permission1->method('restore', 'create')->access() || $this->permission1->method('sql_import', 'create')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("company_list") || $this->uri->segment('1') == ("edit_company") || $this->uri->segment('1') == ("add_user") || $this->uri->segment('1') == ("user_list") || $this->uri->segment('1') == ("language") || $this->uri->segment('1') == ("currency_form") || $this->uri->segment('1') == ("currency_list") || $this->uri->segment('1') == ("settings") || $this->uri->segment('1') == ("mail_setting") || $this->uri->segment('1') == ("app_setting") || $this->uri->segment('1') == ("editPhrase") || $this->uri->segment('1') == ("phrases")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>">
                                <a href="#">
                                    <i class="ti-settings"></i> <span><?php echo display('web_settings') ?></span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if ($this->permission1->method('manage_company', 'read')->access() || $this->permission1->method('manage_company', 'update')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("company_list")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('company_list') ?>"><?php echo display('manage_company') ?></a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('add_user', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("add_user")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('add_user') ?>"><?php echo display('add_user') ?></a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('manage_user', 'read')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("user_list")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('user_list') ?>"><?php echo display('manage_users') ?> </a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('add_language', 'create')->access() || $this->permission1->method('add_language', 'update')->access()) { ?>
                                        <li class="<?php echo (($this->uri->segment(1) == "language" || $this->uri->segment('1') == ("editPhrase") || $this->uri->segment('1') == ("phrases")) ? "active" : '') ?>">
                                            <a href="<?php echo base_url('language') ?>">

                                                <?php echo display('language') ?>

                                            </a>

                                        </li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('add_currency', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("currency_form")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('currency_form') ?>"><?php echo display('currency') ?> </a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('soft_setting', 'create')->access() || $this->permission1->method('soft_setting', 'update')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("settings")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>">
                                            <a href="<?php echo base_url('settings') ?>" class="<?php echo (($this->uri->segment(1) == "settings") ? "active" : null) ?>">

                                                <?php echo display('settings') ?>

                                            </a>

                                        </li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('print_setting', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("print_setting")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('print_setting') ?>"><?php echo display('print_setting') ?> </a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('mail_setting', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("mail_setting")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('mail_setting') ?>"><?php echo display('mail_setting') ?> </a></li>
                                    <?php } ?>
                                    <li class="treeview <?php if ($this->uri->segment('1') == "app_setting") {
                                                            echo "active";
                                                        } else {
                                                            echo " ";
                                                        } ?>"><a href="<?php echo base_url('app_setting') ?>"><?php echo display('app_setting') ?> </a></li>
                                </ul>
                            </li>
                        <?php } ?>
                        <!-- Role permission start -->
                        <?php if ($this->permission1->method('add_role', 'create')->access() || $this->permission1->method('role_list', 'read')->access() || $this->permission1->method('edit_role', 'create')->access() || $this->permission1->method('assign_role', 'create')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("add_role") || $this->uri->segment('1') == ("role_list") || $this->uri->segment('1') == ("edit_role") || $this->uri->segment('1') == ("assign_role")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>">
                                <a href="#">
                                    <i class="ti-key"></i> <span><?php echo display('role_permission') ?></span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">

                                    <?php if ($this->permission1->method('add_role', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("add_role")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('add_role') ?>"><?php echo display('add_role') ?></a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('role_list', 'read')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("role_list")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('role_list') ?>"><?php echo display('role_list') ?></a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('user_assign', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("assign_role")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('assign_role') ?>"><?php echo display('user_assign_role') ?></a></li>
                                    <?php } ?>


                                </ul>
                            </li>
                        <?php } ?>
                        <!-- Role permission End -->
                        <!-- <?php if ($this->permission1->method('sms_configure', 'create')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('1') == ("sms_setting")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                } ?>">
                                    <li class="treeview <?php if ($this->uri->segment('1') == ("sms_setting")) {
                                                            echo "active";
                                                        } else {
                                                            echo " ";
                                                        } ?>"><a href="<?php echo base_url('sms_setting') ?>"><?php echo display('sms_configure'); ?></a></li>


                                </ul>
                            </li>
                        <?php } ?> -->

                        <!-- sms menu end -->
                        <!-- Synchronizer setting start -->
                        <!-- <?php if ($this->permission1->method('restore', 'create')->access() || $this->permission1->method('sql_import', 'create')->access() || $this->permission1->method('sql_import', 'create')->access()) { ?>
                            <li class="treeview <?php
                                                if ($this->uri->segment('1') == ("restore") || $this->uri->segment('1') == ("db_import")) {
                                                    echo "active";
                                                } else {
                                                    echo " ";
                                                }
                                                ?>">
                                <a href="#">
                                    <i class="ti-reload"></i> <span><?php echo display('data_synchronizer') ?></span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">

                                    <?php if ($this->permission1->method('restore', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("restore")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('restore') ?>"><?php echo display('restore') ?></a></li>
                                    <?php } ?>
                                    <?php if ($this->permission1->method('sql_import', 'create')->access()) { ?>
                                        <li class="treeview <?php if ($this->uri->segment('1') == ("db_import")) {
                                                                echo "active";
                                                            } else {
                                                                echo " ";
                                                            } ?>"><a href="<?php echo base_url('db_import') ?>"><?php echo display('import') ?></a></li>
                                    <?php } ?>

                                    <li class="treeview <?php if ($this->uri->segment('2') == ("backup_create")) {
                                                            echo "active";
                                                        } else {
                                                            echo " ";
                                                        } ?>"><a href="<?php echo base_url('dashboard/backup_restore/download') ?>"><?php echo display('backup') ?></a></li>
                                </ul>
                            </li>
                        <?php } ?> -->
                        <!-- Synchronizer setting end -->
                    </ul>
                </li>
            <?php } ?>
            <!-- Software Settings menu end -->
            <!-- custom menu start-->

            <?php
            $path = 'application/modules/';
            $map  = directory_map($path);
            $HmvcMenu   = array();
            if (is_array($map) && sizeof($map) > 0)
                foreach ($map as $key => $value) {
                    $menu = str_replace("\\", '/', $path . $key . 'config/menu.php');
                    if (file_exists($menu)) {

                        if (file_exists(APPPATH . 'modules/' . $key . '/assets/data/env')) {
                            @include($menu);
                        }
                    }
                }
            ?>

            <!-- custom menu end -->
        </ul>

    <?php } ?>

    <?php if ($user_type == '2') { ?>

        <ul class="sidebar-menu">
            <li class="treeview <?php
                                if ($this->uri->segment('1') == ("add_invoice") || $this->uri->segment('1') == ("invoice_list") || $this->uri->segment('1') == ("pos_invoice") || $this->uri->segment('1') == ("gui_pos") || $this->uri->segment('1') == ("invoice_details") || $this->uri->segment('1') == ("invoice_pad_print") || $this->uri->segment('1') == ("pos_print") || $this->uri->segment('1') == ("invoice_edit")) {
                                    echo "active";
                                } else {
                                    echo " ";
                                }
                                ?>">
                <a href="#">
                    <i class="fa fa-balance-scale"></i><span><?php echo display('invoice') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview <?php
                                        if ($this->uri->segment('1') == ("add_invoice")) {
                                            echo "active";
                                        } else {
                                            echo " ";
                                        }
                                        ?>">
                        <a href="<?php echo base_url('add_invoice') ?>"><?php echo display('new_invoice') ?></a>
                    </li>
                    <li class="treeview <?php
                                        if ($this->uri->segment('1') == ("invoice_list")) {
                                            echo "active";
                                        } else {
                                            echo " ";
                                        }
                                        ?>">
                        <a href="<?php echo base_url('invoice_list') ?>"><?php echo display('manage_invoice') ?></a>
                    </li>
                    <li class="treeview <?php
                                        if ($this->uri->segment('1') == ("gui_pos")) {
                                            echo "active";
                                        } else {
                                            echo " ";
                                        }
                                        ?>">
                        <a href="<?php echo base_url('gui_pos') ?>"><?php echo display('pos_invoice') ?></a>
                    </li>
                </ul>
            </li>
            <li class="treeview <?php echo (($this->uri->segment(1) == "add_customer" || $this->uri->segment(1) == "customer_list" || $this->uri->segment(1) == "credit_customer" || $this->uri->segment(1) == "paid_customer" || $this->uri->segment(1) == "edit_customer" || $this->uri->segment(1) == "customer_ledgerdata" || $this->uri->segment(1) == "customer_ledger" || $this->uri->segment(1) == "advance_receipt" || $this->uri->segment(1) == "customer_advance") ? "active" : '') ?>">

                <a href="javascript:void(0)">
                    <i class="metismenu-icon pe-7s-user"></i> <span><?php echo display('customer') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li class="<?php echo (($this->uri->segment(1) == "add_customer") ? "active" : '') ?>">
                        <a href="<?php echo base_url('add_customer') ?>" class="<?php echo (($this->uri->segment(1) == "add_customer") ? "active" : null) ?>"> <?php echo display('add_customer') ?>

                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "customer_list") ? "active" : '') ?>">
                        <a href="<?php echo base_url('customer_list') ?>">
                            <?php echo display('customer_list') ?>
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "credit_customer") ? "active" : '') ?>">
                        <a href="<?php echo base_url('credit_customer') ?>">
                            <?php echo display('credit_customer') ?>
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "paid_customer") ? "active" : '') ?>">
                        <a href="<?php echo base_url('paid_customer') ?>">
                            <?php echo display('paid_customer') ?>
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "customer_ledger" || $this->uri->segment(1) == "customer_ledgerdata") ? "active" : '') ?>">
                        <a href="<?php echo base_url('customer_ledger') ?>">
                            <?php echo display('customer_ledger') ?>
                        </a>
                    </li>
                    <li class="<?php echo (($this->uri->segment(1) == "customer_advance") ? "active" : '') ?>">
                        <a href="<?php echo base_url('customer_advance') ?>">
                            <?php echo display('customer_advance') ?>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    <?php } ?>





    <?php if ($user_type == '3') {


    ?>

        <ul class="sidebar-menu">


            <li class="treeview <?php
                                if ($this->uri->segment('1') == ("add_invoice") || $this->uri->segment('1') == ("invoice_list") || $this->uri->segment('1') == ("pos_invoice") || $this->uri->segment('1') == ("gui_pos") || $this->uri->segment('1') == ("invoice_details") || $this->uri->segment('1') == ("invoice_pad_print") || $this->uri->segment('1') == ("pos_print") || $this->uri->segment('1') == ("invoice_edit") || $this->uri->segment('1') == ("arrangement_list")) {
                                    echo "active";
                                } else {
                                    echo " ";
                                }
                                ?>">
                <a href="#">
                    <i class="fa fa-balance-scale"></i><span><?php echo display('invoice') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <!--<li class="treeview <?php
                                            if ($this->uri->segment('1') == ("invoice_list")) {
                                                echo "active";
                                            } else {
                                                echo " ";
                                            }
                                            ?>"><a href="<?php echo base_url('invoice_list') ?>"><?php echo display('manage_invoice') ?></a></li>-->
                    <li class="treeview <?php
                                        if ($this->uri->segment('1') == ("arrangement_list1")) {
                                            echo "active";
                                        } else {
                                            echo " ";
                                        }
                                        ?>"><a href="<?php echo base_url('arrangement_list1') ?>">Lista de ventas</a></li>

                </ul>
            </li>
        </ul>

    <?php } ?>






    <?php if ($user_type == '4') {


    ?>

        <ul class="sidebar-menu">


            <li class="treeview <?php
                                if ($this->uri->segment('1') == ("add_invoice") || $this->uri->segment('1') == ("invoice_list") || $this->uri->segment('1') == ("pos_invoice") || $this->uri->segment('1') == ("gui_pos") || $this->uri->segment('1') == ("invoice_details") || $this->uri->segment('1') == ("invoice_pad_print") || $this->uri->segment('1') == ("pos_print") || $this->uri->segment('1') == ("invoice_edit")) {
                                    echo "active";
                                } else {
                                    echo " ";
                                }
                                ?>">
                <a href="#">
                    <i class="fa fa-balance-scale"></i><span><?php echo display('invoice') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <!--<li class="treeview <?php
                                            if ($this->uri->segment('1') == ("invoice_list")) {
                                                echo "active";
                                            } else {
                                                echo " ";
                                            }
                                            ?>"><a href="<?php echo base_url('invoice_list') ?>"><?php echo display('manage_invoice') ?></a></li>-->

                    <li class="treeview <?php
                                        if ($this->uri->segment('1') == ("arrangement_list")) {
                                            echo "active";
                                        } else {
                                            echo " ";
                                        }
                                        ?>"><a href="<?php echo base_url('arrangement_list') ?>">Lista de arreglos</a></li>

                </ul>
            </li>
        </ul>

    <?php } ?>

</div> <!-- /.sidebar -->