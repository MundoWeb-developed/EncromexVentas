<style>
    .form-inline .form-group {
        margin-right: 15px;
    }

    label.control-label {
        text-align: right;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo form_open('datewise_sales_report', array('class' => 'form-inline', 'method' => 'get')) ?>
                <?php
                $today = date('Y-m-d');
                ?>
                <div class="form-group">
                    <label class="" for="from_date">Fecha inicio</label>
                    <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" value="<?php echo $today ?>">
                </div>
                <div class="form-group">
                    <label class="" for="to_date"><?php echo display('end_date') ?></label>
                    <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $today ?>">
                </div>

                <label for="date_filter" class="control-label"><?php echo display('filter') ?></label>
                <div class="form-group">
                    <!-- <label for="date_filter" class="control-label"><?php echo display('filter') ?></label> -->
                    <select name="date_filter" id="date_filter" class="datepicker form-control">
                        <option value="#">-- Seleccionar --</option>
                        <option value="today">Hoy</option>
                        <option value="yesterday">Ayer</option>
                        <option value="last_week">Última semana</option>
                        <option value="last_month">Último mes</option>
                        <option value="last_3_months">Últimos 3 meses</option>
                        <option value="last_6_months">Últimos 6 meses</option>
                    </select>
                </div>
                <!-- Nuevo filtro por tipo de sucursal -->
                <label for="branch_type" class="control-label">Sucursal/Socios</label>
                <div class="form-group">
                    <select name="branch_type" id="branch_type" class="form-control">
                        <option value="all">-- TODAS --</option>
                        <option value="1" <?php echo (isset($branch_type) && $branch_type == '1' ? 'selected' : ''); ?>>Sucursales</option>
                        <option value="2" <?php echo (isset($branch_type) && $branch_type == '2' ? 'selected' : ''); ?>>Socios comerciales</option>
                    </select>
                </div>
                <br>
                <br>
                <div style="display: flex; justify-content: center; gap: 5px;">
                    <button type="submit" class="btn btn-success">Generar</button>
                    <a class="btn btn-warning" href="#" onclick="printDiv('purchase_div')">Imprimir</a>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <span><?php echo display('sales_report') ?> </span>
                    <span class="padding-lefttitle"> <?php if ($this->permission1->method('all_report', 'read')->access()) { ?>
                            <a class="btn btn-primary m-b-5 m-r-2" href="<?php echo base_url('report/bdtask_datewise_sales_report?date_filter=today') ?>">Reporte del día</a>
                        <?php } ?>
                        <?php if ($this->permission1->method('todays_purchase_report', 'read')->access()) { ?>
                            <a href="<?php echo base_url('purchase_report') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('purchase_report') ?> </a>
                        <?php } ?>
                        <?php if ($this->permission1->method('product_sales_reports_date_wise', 'read')->access()) { ?>
                            <a href="<?php echo base_url('product_wise_sales_report') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('sales_report_product_wise') ?> </a>
                        <?php } ?>
                        <?php if ($this->permission1->method('todays_sales_report', 'read')->access() && $this->permission1->method('todays_purchase_report', 'read')->access()) { ?>
                            <a href="<?php echo base_url('profit_report_general') ?>" class="btn btn-warning m-b-5 m-r-2"><i class="ti-align-justify"> </i>Informe de ganancial generales</a>
                        <?php } ?></span>
                </div>
            </div>
            <div class="panel-body">
                <div id="purchase_div">
                    <div class="paddin5ps">
                        <table class="print-table " width="100%">
                            <tr>
                                <td align="left" class="print-table-tr">
                                    <img src="<?php echo base_url() . $setting->logo; ?>" alt="logo">
                                </td>
                                <td align="center" class="print-cominfo">
                                    <span class="company-txt">
                                        <?php echo $company_info[0]['company_name']; ?>

                                    </span><br>
                                    <?php echo $company_info[0]['address']; ?>
                                    <br>
                                    <?php echo $company_info[0]['email']; ?>
                                    <br>
                                    <?php echo $company_info[0]['mobile']; ?>
                                </td>
                                <td align="right" class="print-table-tr">
                                    <date>
                                        <?php echo display('date') ?>: <?php
                                                                        echo date('d-M-Y');
                                                                        ?>
                                    </date>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive paddin5ps">
                        <!-- Mantener todo el encabezado igual hasta la tabla -->

                        <?php foreach ($branchoffices as $branch): ?>
                            <h4 style="margin-top: 20px;"><?php echo $branch ?: 'Sin sucursal'; ?></h4>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sales_date') ?></th>
                                        <th><?php echo display('invoice_no') ?></th>
                                        <th><?php echo display('customer_name') ?></th>
                                        <th><?php echo display('total_amount') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $branch_total = 0;
                                    if (!empty($grouped_sales[$branch])) {
                                        foreach ($grouped_sales[$branch] as $sale) {
                                            $branch_total += $sale['total_amount'];
                                    ?>
                                            <tr>
                                                <td><?php echo $sale['sales_date'] ?></td>
                                                <td><?php echo $sale['invoice'] ?></td>
                                                <td><?php echo $sale['customer_name'] ?? 'Venta general'; ?></td>
                                                <td class="text-right">
                                                    <?php echo ($position == 0) ? "$currency " . number_format($sale['total_amount'], 2) : number_format($sale['total_amount'], 2) . " $currency"; ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No hay ventas en esta sucursal</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <!-- Total por sucursal -->
                                    <tr>
                                        <td colspan="3" class="text-right"><b>Total sucursal</b></td>
                                        <td class="text-right">
                                            <b>
                                                <?php
                                                echo ($position == 0) ? "$currency " . number_format($branch_total, 2) : number_format($branch_total, 2) . " $currency";
                                                ?>
                                            </b>
                                        </td>
                                    </tr>

                                    <!-- Desglose por categorías -->
                                    <?php if (!empty($branch_category_totals[$branch])): ?>
                                        <tr>
                                            <td colspan="4" style="padding: 0!important;">
                                                <table class="table" style="margin-bottom: 0; border: none;">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3">Ventas por categoría</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($branch_category_totals[$branch] as $category_id => $category): ?>
                                                            <tr>
                                                                <td width="60%"><?php echo $category['category_name'] ?></td>
                                                                <td class="text-right">
                                                                    <?php echo ($position == 0) ? "$currency " . number_format($category['total_sales'], 2) : number_format($category['total_sales'], 2) . " $currency"; ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php
                                                                    $commission_rate = $branch_category_commissions[$branch][$category_id]['commission_rate'] ?? 0;
                                                                    echo $commission_rate . '%';
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <!-- Comisión total por sucursal -->
                                    <tr>
                                        <td colspan="3" class="text-right"><b>Comisión Total</b></td>
                                        <td class="text-right">
                                            <b>
                                                <?php
                                                $comision_valor = $branch_comisiones[$branch] ?? 0;
                                                echo ($position == 0) ? "$currency " . number_format($comision_valor, 2) : number_format($comision_valor, 2) . " $currency";
                                                ?>
                                            </b>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endforeach; ?>
                        <!-- Mantener el resto de la vista igual -->
                        <hr>
                        <?php
                        $total_comisiones = isset($total_comisiones) ? $total_comisiones : array_sum($branch_comisiones);
                        ?>
                        <h4>Total general de ventas:
                            <?php echo ($position == 0) ? "$currency " . number_format($sales_amount, 2) : number_format($sales_amount, 2) . " $currency"; ?>
                        </h4>
                        <h4>Total de comisiones:
                            <?php echo ($position == 0) ? "$currency " . number_format($total_comisiones, 2) : number_format($total_comisiones, 2) . " $currency"; ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>