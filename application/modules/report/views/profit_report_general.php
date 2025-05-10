<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php echo form_open('profit_report_general', array('class' => 'form-inline', 'method' => 'get')) ?>
				<?php date_default_timezone_set("Asia/Dhaka");
				$today = date('Y-m-d'); ?>
				<div class="form-group">
					<label for="from_date"><?php echo display('start_date') ?>:</label>
					<input type="text" name="from_date" class="form-control datepicker" id="from_date" value="<?php echo $from ?>" placeholder="<?php echo display('start_date') ?>">
				</div>
				<div class="form-group">
					<label for="to_date"><?php echo display('end_date') ?>:</label>
					<input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $to ?>">
				</div>
				<button type="submit" class="btn btn-success">Generar</button>
				<a class="btn btn-warning" href="#" onclick="printDiv('purchase_div')"><?php echo display('print') ?></a>
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
					<span>Informe de ganancias</span>
					<span class="padding-lefttitle">
						<?php if ($this->permission1->method('todays_sales_report', 'read')->access()) { ?>
							<a href="<?php echo base_url('sales_report') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('sales_report') ?> </a>
						<?php } ?>
						<?php if ($this->permission1->method('todays_purchase_report', 'read')->access()) { ?>
							<a href="<?php echo base_url('purchase_report') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('purchase_report') ?> </a>
						<?php } ?>
						<?php if ($this->permission1->method('product_sales_reports_date_wise', 'read')->access()) { ?>
							<a href="<?php echo base_url('product_wise_sales_report') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('sales_report_product_wise') ?> </a>
						<?php } ?>
					</span>
				</div>
			</div>
			<div class="panel-body">
				<div id="purchase_div">
					<div class="paddin5ps">
						<table class="print-table paddin5ps" width="100%">
							<tr>
								<td align="left" class="print-table-tr">
									<img src="<?php echo html_escape(base_url() . $setting->logo); ?>" alt="logo">
								</td>
								<td align="center" class="print-cominfo">
									<span class="company-txt">
										<?php echo html_escape($company_info[0]['company_name']); ?>
									</span><br>
									<?php echo html_escape($company_info[0]['address']); ?>
									<br>
									<?php echo html_escape($company_info[0]['email']); ?>
									<br>
									<?php echo html_escape($company_info[0]['mobile']); ?>
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
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th><?php echo display('sales_date') ?></th>
									<th class="text-center"><?php echo display('invoice_no') ?></th>
									<th class="text-center">Monto de compra</th>
									<th class="text-center">Monto de venta</th>
									<th class="text-center"><?php echo display('total_profit') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($total_profit_report_grouped)) {
									foreach ($total_profit_report_grouped as $sucursal => $report_list) {
										// Variables para subtotales por sucursal
										$subtotal_sup = 0;
										$subtotal_sale = 0;
										$subtotal_profit = 0;
								?>
										<tr>
											<td colspan="5"><strong>Sucursal: <?php echo html_escape($sucursal); ?></strong></td>
										</tr>
										<?php
										foreach ($report_list as $profit) {
											// Acumular subtotales
											$subtotal_sup += $profit['total_supplier_rate'];
											$subtotal_sale += $profit['total_sale'];
											$subtotal_profit += $profit['total_profit'];
										?>
											<tr>
												<td><?php echo $profit['prchse_date'] ?></td>
												<td><?php echo $profit['invoice'] ?></td>
												<td class="text-right"><?php echo (($position == 0) ? $currency . ' ' . $profit['total_supplier_rate'] : $profit['total_supplier_rate'] . ' ' . $currency) ?></td>
												<td class="text-right"><?php echo (($position == 0) ? $currency . ' ' . $profit['total_sale'] : $profit['total_sale'] . ' ' . $currency) ?></td>
												<td class="text-right"><?php echo (($position == 0) ? $currency . ' ' . $profit['total_profit'] : $profit['total_profit'] . ' ' . $currency) ?></td>
											</tr>
										<?php } ?>
										<!-- Fila de subtotal por sucursal -->
										<tr style="background-color: #f5f5f5;">
											<td colspan="2" class="text-right"><strong>Subtotal <?php echo html_escape($sucursal); ?>:</strong></td>
											<td class="text-right"><strong><?php echo (($position == 0) ? $currency . ' ' . number_format($subtotal_sup, 2) : number_format($subtotal_sup, 2) . ' ' . $currency) ?></strong></td>
											<td class="text-right"><strong><?php echo (($position == 0) ? $currency . ' ' . number_format($subtotal_sale, 2) : number_format($subtotal_sale, 2) . ' ' . $currency) ?></strong></td>
											<td class="text-right"><strong><?php echo (($position == 0) ? $currency . ' ' . number_format($subtotal_profit, 2) : number_format($subtotal_profit, 2) . ' ' . $currency) ?></strong></td>
										</tr>
										<tr>
											<td colspan="5" style="border-bottom: 2px solid #000;"></td>
										</tr>
									<?php }
								} else { ?>
									<tr>
										<td colspan="5" class="text-center">Sin datos para mostrar</td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" class="text-right"><b><?php echo display('total') ?>: </b></td>
									<td class="text-right"><b><?php echo (($position == 0) ? $currency . ' ' . number_format($SubTotalSupAmnt, 2) : number_format($SubTotalSupAmnt, 2) . ' ' . $currency) ?></b></td>
									<td class="text-right"><b><?php echo (($position == 0) ? $currency . ' ' . number_format($SubTotalSaleAmnt, 2) : number_format($SubTotalSaleAmnt, 2) . ' ' . $currency) ?></b></td>
									<td class="text-right"><b><?php echo (($position == 0) ? $currency . ' ' . number_format($profit_ammount, 2) : number_format($profit_ammount, 2) . ' ' . $currency) ?></b></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>