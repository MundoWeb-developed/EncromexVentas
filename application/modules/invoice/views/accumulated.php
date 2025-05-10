<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-10">
                    <b>Filtrar por fecha y vendedor:</b>
                    <?php echo form_open('', array('class' => 'form-inline', 'method' => 'get')) ?>
                    <?php
                    $today = date('Y-m-d');
                    ?>
                    <div class="form-group">
                        <label class="" for="from_date"><?php echo display('start_date') ?></label>
                        <input type="text" name="from_date" class="form-control datepicker" id="from_date" value="" placeholder="<?php echo display('start_date') ?>">
                    </div>
                    <div class="form-group">
                        <label class="" for="to_date"><?php echo display('end_date') ?></label>
                        <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="">
                    </div>
                    <div class="form-group">
                        <label class="" for="seller">Cajero</label>
                        <select id="seller" name="seller" style="padding-top:8px; padding-bottom:8px;">
                            <option value="Todos">-- Todo --</option>
                            <?php if ($sellers) {
                                foreach ($sellers as $seller) { ?>
                                    <option value="<?php echo $seller['seller']; ?>"><?php echo $seller['seller']; ?></option>
                            <?php   }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" id="btn-filter-acumulated" class="btn btn-success"><?php echo display('find') ?></button>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
</div>
<!-- Manage Invoice report -->
<input type="hidden" name="baseUrl" value="<?php echo base_url(); ?>" id="baseUrl" />
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <span>Acumulado del día</span>
                    <span class="padding-lefttitle">

                        <button class="btn btn-info" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button>
                        <!-- <?php if ($this->permission1->method('new_invoice', 'create')->access()) { ?>
								<a href="<?php echo base_url('add_invoice') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('new_invoice') ?> </a>
							<?php } ?> -->
                        <?php if ($this->permission1->method('gui_pos', 'create')->access()) { ?>
                            <a href="<?php echo base_url('gui_pos') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('pos_invoice') ?> </a>
                        <?php } ?>

                    </span>
                </div>
            </div>
            <div id="printableArea" onload="printDiv('printableArea')">
                <div class="panel-body">
                    <div class="table-responsive">
                        <h3>Ventas en efectivo</h3>
                        <hr>
                        <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="InvList_efectivo">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <!--<th>Cliente</th>-->
                                    <th>Fecha de venta</th>
                                    <th>Importe venta</th>
                                    <!--<th>Tipo de pago</th>
                                    <th>Status</th>
                                    <th class="text-center"><?php echo display('action') ?></th>-->

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th colspan="2" class="text-right"><?php echo display('total') ?>:</th>
                                <th></th>
                                <!--<th></th>
									<th></th>
									<th></th>-->
                            </tfoot>
                        </table>
                        <br>
                        <h3>Ventas con tarjeta</h3>
                        <hr>
                        <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="InvList_tarjeta">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <!--<th>Cliente</th>-->
                                    <th>Fecha de venta</th>
                                    <th>Importe venta</th>
                                    <!--<th>Tipo de pago</th>
                                    <th>Status</th>
                                    <th class="text-center"><?php echo display('action') ?></th>-->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th colspan="2" class="text-right"><?php echo display('total') ?>:</th>
                                <th></th>
                                <!--<th></th>
									<th></th>
									<th></th>-->
                            </tfoot>
                        </table>
                        <br>
                        <h3>Ventas con transferencia bancaria</h3>
                        <hr>
                        <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="InvList_transferencia">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <!--<th>Cliente</th>-->
                                    <th>Fecha de venta</th>
                                    <th>Importe venta</th>
                                    <!--<th>Tipo de pago</th>
                                    <th>Status</th>
                                    <th class="text-center"><?php echo display('action') ?></th>-->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th colspan="2" class="text-right"><?php echo display('total') ?>:</th>
                                <th></th>
                                <!--<th></th>
									<th></th>
									<th></th>-->
                            </tfoot>
                        </table>
                        <!-- <br>
							<h3>Ventas con anticipo</h3>
							<hr>
							<table class="table table-hover table-bordered" cellspacing="0" width="100%" id="InvList_anticipo"> 
                                <thead>
                                    <tr>
									<th>Factura</th>
                                    <!--<th>Cliente</th>
                                    <th>Fecha de venta</th>
                                    <th>Importe venta</th>
									<!--<th>Tipo de pago</th>
                                    <th>Status</th>
                                    <th class="text-center"><?php echo display('action') ?></th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                    				<th colspan="2" class="text-right"><?php echo display('total') ?>:</th>                
                  					<th></th>
                  					<!--<th></th>
									<th></th>
									<th></th>
                                </tfoot>
                            </table> -->
                        <br>
                        <h3>Ventas por credito interno</h3>
                        <hr>
                        <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="InvList_porcobrar">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <!--<th>Cliente</th>-->
                                    <th>Fecha de venta</th>
                                    <th>Importe venta</th>
                                    <!--<th>Tipo de pago</th>
                                    <th>Status</th>
                                    <th class="text-center"><?php echo display('action') ?></th>-->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th colspan="2" class="text-right"><?php echo display('total') ?>:</th>
                                <th></th>
                                <!--<th></th>
									<th></th>
									<th></th>-->
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    @media print {
        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>