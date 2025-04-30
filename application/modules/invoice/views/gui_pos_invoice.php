<?php session_start(); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js" integrity="sha512-hAJgR+pK6+s492clbGlnrRnt2J1CJK6kZ82FZy08tm6XG2Xl/ex9oVZLE6Krz+W+Iv4Gsr8U2mGMdh0ckRH61Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<link href="<?php echo base_url('assets/css/gui_pos.css') ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url() ?>my-assets/js/admin_js/pos_invoice.js?v=1.0.0" type="text/javascript"></script>
<script src="<?php echo base_url() ?>my-assets/js/admin_js/guibarcode.js" type="text/javascript"></script>
<script src="assets/js/perfect-scrollbar.min.js" type="text/javascript"></script>

<div class="pl-3 pr-3">
	<div class="top-bar">
		<ul class="nav nav-tabs" role="tablist">
			<li class="active">
				<a href="#home" role="tab" data-toggle="tab" class="home" id="new_sale">
					Nueva venta </a>
			</li>
			<li class="onprocessg"><a href="#saleList" role="tab" data-toggle="tab" class="ongord" id="todays_salelist">
					Ventas de hoy </a>
			</li>
			<li class="onprocessg">
				<a href="#arr_personalizado" role="tab" data-toggle="tab" class="ongord" id="tab_arrp" style="background-color: white;color: #fff !important;box-shadow: 0 3px 5px 0 rgba(255, 255, 255, 0.3);">&nbsp;</a>

			</li>
		</ul>
		<div class="tgbar d-flex">
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
				<span class="sr-only">Toggle navigation</span>
				<span class="pe-7s-keypad"></span>
			</a>
			<a href="" class="topbar-icon" id="keyshortcut" aria-hidden="true" data-toggle="modal" data-target="#cheetsheet"><i class="fa fa-keyboard-o"></i></a>
		</div>
	</div>
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane fade active in" id="home">
			<!--init row form -->
			<div class="container">

				<div class="row">
					<form class="form-inline mb-3" style="display:none;">
						<div class="form-group">
							<input type="text" id="add_item" class="form-control" placeholder="Escaneo de código de barras o código QR aquí">
						</div>
						<div class="form-group">
							<label class="mr-3 ml-3">OR</label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="add_item_m" placeholder="Ingreso manual de Código de barras">
						</div>
					</form>
					<div class="col-sm-12 col-md-12">
						<div class="row">
							<input name="url" type="hidden" id="posurl" value="<?php echo base_url("invoice/invoice/getitemlist") ?>" />
							<input name="url" type="hidden" id="posurl_productname" value="<?php echo base_url("invoice/invoice/getitemlist_byname") ?>" />
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 " id="style-3">
								<div class="row search-bar" style="margin-bottom:20px;">
									<div class="col-sm-3">
										<label>Categorías:</label>
										<select id="list_categories" class="" onchange="check_category(this.value)" style="padding-top:8px; padding-bottom:8px;">
											<option value="">- Categoría -</option>

											<?php if ($categorylist) { ?>
												<?php foreach ($categorylist as $categories) { ?>
													<option value="<?php echo $categories['category_id'] ?>"><?php echo $categories['category_name'] ?></option>
											<?php }
											} ?>

										</select>
										<script>
											jQuery(document).ready(function($) {
												$('#list_categories').val('');
											});
										</script>
									</div>
									<div class="col-sm-2">
										<label>Tipo de precio:</label>
										<select id="precio_tipo" class="form-control">
											<option value="1">Precio 1</option>
											<option value="2">Precio 2</option>
											<option value="3">Precio 3</option>
											<option value="4">Precio 4</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label>Buscar producto:</label>
										<!--<input type="text" class="form-control" id="add_item_m" placeholder="Ingreso manual de Código de barras">-->
										<select class="form-control" id="prlist">
											<option>Seleccionar opción</option>
											<?php foreach ($itemlist as $item) { ?>
												<option
													value="<?php echo $item->product_id ?>"
													data-category-id="<?php echo $item->category_id ?>">
													<?php echo html_escape($item->product_name); ?> (<?php echo html_escape($item->product_model); ?>)
												</option>
											<?php } ?>
										</select>
									</div>

									<div class="col-sm-3">
										<label>Sucursal:</label><br>
										<?php
										// Obtener la sucursal del usuario actual
										$user_branch = $this->db->select('c.branchoffice, c.id')
											->from('users a')
											->join('branchoffice c', 'a.branchoffice_id = c.id', 'left')
											->where('a.user_id', $this->session->userdata('id'))
											->get()
											->row();

										// Mostrar como texto si no hay sucursal
										if (empty($user_branch) || empty($user_branch->branchoffice)) {
											echo '<div class="form-control" style="padding-top:8px; padding-bottom:8px;">Sin sucursal asignada</div>';
										} else {
											// Mostrar el nombre de la sucursal y guardar el ID en un campo oculto
											echo '<div class="form-control" style="padding-top:8px; padding-bottom:8px;">' . $user_branch->branchoffice . '</div>';
											echo '<input type="hidden" id="boff" name="boff" value="' . $user_branch->branchoffice . '">';
											echo '<input type="hidden" name="branchoffice_id" value="' . $user_branch->id . '">';
										}
										?>
									</div>

									<div class="col-sm-3">
										<label class="mr-2 mb-0"><?php echo display('invoice_no'); ?> - <i class="text-danger"></i></label>
										<div class="d-flex align-items-center">
											<div class="invoice-no" id="gui_invoice_no">
												<?php echo html_escape($invoice_no); ?>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="margin-bottom:20px;">
									<div class="col-sm-4">
										<label>Últimos clientes:</label><br>
										<select id="list_bases" class="form-control" onchange="check_customer(this.value)" style="padding-top:8px; padding-bottom:8px; font-size:11px;">
											<option>Seleccionar opción</option>
											<?php foreach ($items_customer as $customer) { ?>
												<option value="<?php echo $customer['invoice_id']; ?>">
													Registrado: <?php echo date('d/m/Y', strtotime($customer['date'])); ?> | <?php echo $customer['nombre_cliente']; ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="product-grid" style="height: 250px;">
									<div class="row row-m-3" id="product_search">

										<div class="col-xs-6 col-sm-4 col-md-2 col-lg-2 col-p-2" style="padding-right: 5px;padding-left: 5px;">
											<a href="#arr_personalizado" role="tab" data-toggle="tab">
												<div class="product-panel overflow-hidden border-0 shadow-sm">
													<div class="item-image position-relative overflow-hidden">
														<img src="assets/img//mecanico.jpg" id="product_image" alt="" class="img-responsive">
													</div>
													<div class="panel-footer border-0 bg-white">
														<h3 class="item-details-title text-center" id="product_name_card">Personalizar</h3>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12">
						<?php echo form_open_multipart('invoice/invoice/bdtask_manual_sales_insert', array('class' => 'form-vertical', 'id' => 'gui_sale_insert', 'name' => 'insert_pos_invoice')) ?>
						<input class="form-control" type="hidden" name="invoice_no" id="invoice_no" required value="<?php echo html_escape($invoice_no); ?>" readonly />
						<input class="form-control" type="hidden" name="branchoffice" id="branchoffice" value="ENCROMEX" />
						<div class="col-sm-3" style="display:none;">
							<label>Clientes:</label>
							<div class="input-group mr-3">
								<input type="text" class="form-control customerSelection" id="customer_name" value="Venta en general<?php //echo $customer_name;
																																	?>" tabindex="3" onkeyup="customer_autocomplete()" name="customer_name">
								<?php
								$opts = '';
								if ($deliveryman) {
									foreach ($deliveryman as $dm) {
										$opts .= '<option value="' . $dm->id . '">' . $dm->first_name . ' ' . $dm->last_name . '</option>';
									}
								} else {
									$opts = '<option>--No hay repartidores--</option>';
								}

								$optfl = '';
								if ($florist) {
									foreach ($florist as $fl) {
										$optfl .= '<option value="' . $fl->id . '">' . $fl->first_name . ' ' . $fl->last_name . '</option>';
									}
								} else {
									$optfl = '<option>--No hay repartidores--</option>';
								}


								$optz = '';
								if ($zonas) {
									foreach ($zonas as $zn) {
										$optz .= '<option value="' . $zn->id . '">' . $zn->zona . '</option>';
									}
								} else {
									$optz = '<option>--No hay zonas--</option>';
								}
								?>

								<input type="hidden" class="form-control" id="opts" value='<?php echo $opts; ?>' name="opts">
								<input type="hidden" class="form-control" id="optfl" value='<?php echo $optfl; ?>' name="optfl">
								<input type="hidden" class="form-control" id="optz" value='<?php echo $optz; ?>' name="optz">

								<input id="autocomplete_customer_id" class="customer_hidden_value" type="hidden" name="customer_id" value="1<?php //echo $customer_id
																																			?>">
								<span class="input-group-btn">
									<button class="client-add-btn btn btn-success" type="button" aria-hidden="true" data-toggle="modal" data-target="#cust_info" id="customermodal-link" tabindex="4"><i class="ti-plus"></i></button>
								</span>
							</div>
						</div>
						<div class="container-fluid">
							<div class="d-flex flex-wrap align-items-center gap-3 mb-3 w-100 justify-content-between">
								<span class="fw-bold">Tipo de venta:</span>
								<div class="form-check">
									<input class="form-check-input" value="Mostrador" type="radio" name="tipo_venta" id="opt1" checked>
									<label class="form-check-label" for="opt1">Mostrador</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" value="WhatsApp" type="radio" name="tipo_venta" id="opt2">
									<label class="form-check-label" for="opt2">WhatsApp</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" value="Teléfonica" type="radio" name="tipo_venta" id="opt5">
									<label class="form-check-label" for="opt5">Teléfonica</label>
								</div>
							</div>
						</div>
						<!--section old form-->
						<input type="hidden" name="csrf_test_name" id="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>">
						<div class="table-responsive guiproductdata">
							<table class="table table-bordered table-hover table-sm nowrap gui-products-table" id="addinvoice">
								<thead>
									<tr>
										<th class="text-center gui_productname"><?php echo display('product') ?> <i class="text-danger">*</i></th>
										<!--<th class="text-center invoice_fields"><?php echo display('serial') ?></th>-->
										<!--<th class="text-center"><?php echo display('available_qnty') ?></th>-->
										<th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
										<th class="text-center"><?php echo display('rate') ?> <i class="text-danger">*</i></th>
										<th class="text-center">Descuento por producto (%)</th>
										<th class="text-center"><?php echo display('total') ?></th>
										<th class="text-center"><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody id="addinvoiceItem">
								</tbody>
							</table>
							<table class="table" id="arr_insumos">
								<tbody id="addInsumoItem"></tbody>
							</table>
						</div>
						<div class="footer">
							<div class="form-group row guifooterpanel">
								<div class="col-sm-12">
									<label for="date" class="col-sm-6 col-lg-6 col-xl-7 col-form-label">Descuento general (%):</label>
									<div class="col-sm-6 col-lg-5 col-xl-4">
										<input
											type="text"
											onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" id="invoice_discount"
											class="form-control total_discount gui-foot text-right"
											name="invoice_discount" placeholder="0.00" />
										<input type="hidden" id="txfieldnum" value="<?php echo $taxnumber ?>" />
										<input type="hidden" name="paytype" value="1" />
									</div>
								</div>
							</div>
							<div class="form-group row guifooterpanel">
								<div class="col-sm-12">
									<label for="date" class="col-sm-6 col-lg-6 col-xl-7 col-form-label"><?php echo display('total_discount') ?>:</label>
									<div class="col-sm-6 col-lg-5 col-xl-4"><input type="text" id="total_discount_ammount" class="form-control gui-foot text-right" name="total_discount" value="0.00" readonly="readonly" /></div>
								</div>
							</div>
							<div class="form-group row hiddenRow guifooterpanel" id="taxdetails">
								<?php $x = 0;
								foreach ($taxes as $taxfldt) { ?>
									<div class="col-sm-12">
										<label for="date" class="ol-sm-6 col-lg-6 col-xl-7 col-form-label"><?php echo html_escape($taxfldt['tax_name']) ?>:</label>
										<div class="col-sm-6 col-lg-5 col-xl-4">
											<input id="total_tax_ammount<?php echo $x; ?>" tabindex="-1" class="form-control gui-foot text-right valid totalTax" name="total_tax<?php echo $x; ?>" value="0.00" readonly="readonly" aria-invalid="false" type="text">
										</div>
									</div>
								<?php $x++;
								} ?>
							</div>
							<div class="form-group row guifooterpanel">
								<div class="col-sm-12">
									<label for="date" class="col-sm-6 col-lg-6 col-xl-7 col-form-label"><?php echo display('total_tax') ?>:</label>
									<div class="col-sm-6 col-lg-5 col-xl-4"><input id="total_tax_amount" tabindex="-1" class="form-control gui-foot text-right valid" name="total_tax" value="0.00" readonly="readonly" aria-invalid="false" type="text" /></div>
									<!--<a class="col-sm-1 btn btn-primary btn-sm taxbutton" data-toggle="collapse" data-target="#taxdetails" aria-expanded="false" aria-controls="taxdetails"><i class="fa fa-angle-double-up"></i></a>-->
								</div>
							</div>
							<div class="form-group row guifooterpanel">
								<div class="col-sm-12">
									<label for="date" class="col-sm-6 col-lg-6 col-xl-7 col-form-label"><?php echo display('shipping_cost') ?>:</label>
									<div class="col-sm-6 col-lg-5 col-xl-4">
										<input
											type="text"
											id="shipping_cost"
											class="form-control gui-foot text-right"
											name="shipping_cost" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" />
									</div>
								</div>
							</div>
							<div class="form-group row guifooterpanel">
								<div class="col-sm-12">
									<label for="date" class="col-sm-6 col-lg-6 col-xl-7 col-form-label"><?php echo display('grand_total') ?>:</label>
									<div class="col-sm-6 col-lg-5 col-xl-4"><input type="text" id="grandTotal" class="form-control gui-foot text-right" name="grand_total_price" value="0.00" readonly="readonly" />
										<input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" id="baseurl" />
									</div>
								</div>
							</div>
							<div class="form-group row guifooterpanel" style="display:none;">
								<div class="col-sm-12">
									<label for="date" class="col-sm-6 col-lg-6 col-xl-7 col-form-label"><?php echo display('previous'); ?>:</label>
									<div class="col-sm-6 col-lg-5 col-xl-4"><input type="text" id="previous" class="form-control gui-foot text-right" name="previous" value="0.00" readonly="readonly" /></div>
								</div>
							</div>
							<div class="form-group row guifooterpanel">
								<div class="col-sm-12">
									<label for="change" class="col-sm-6 col-lg-6 col-xl-7 col-form-label"><?php echo display('change'); ?>:</label>
									<div class="col-sm-6 col-lg-5 col-xl-4"><input type="text" id="change" class="form-control gui-foot text-right" name="change" value="0.00" readonly="readonly" /></div>
								</div>
							</div>
						</div>
						<div class="fixedclasspos">
							<div class="bottomarea">
								<div class="row">
									<div class="col-lg-9 col-xl-9">
										<div class="calculation d-lg-flex">
											<div class="cal-box d-lg-flex align-items-lg-center mr-4">
												<label class="cal-label mr-2 mb-0"><?php echo display('net_total'); ?>:</label><span class="amount" id="net_total_text">0.00</span>
												<input type="hidden" id="n_total" class="form-control text-right guifooterfixedinput" name="n_total" value="0" readonly="readonly" placeholder="" />
											</div>
											<div class="cal-box d-lg-flex align-items-lg-center mr-4">
												<div class="form-inline d-inline-flex align-items-center">
													<label class="cal-label mr-2 mb-0"><?php echo display('paid_ammount') ?>:</label>
													<input type="text" class="form-control" id="paidAmount" onkeyup="invoice_paidamount()" name="paid_amount" onkeypress="invoice_paidamount()" placeholder="0.00">
												</div>
											</div>
											<div class="cal-box d-lg-flex align-items-lg-center mr-4">
												<div class="form-inline d-inline-flex align-items-center">
													<label class="cal-label mr-2 mb-0">Tipo de pago:</label>
													<select style="padding:8px;" id="tipo_pago" name="tipo_pago">
														<option value="Tarjeta">Tarjeta</option>
														<option value="Efectivo">Efectivo</option>
														<option value="Transferencia Bancaria">Transferencia Bancaria</option>
														<option value="Credito Interno">Credito Interno</option>
														<!-- <option value="Anticipo">Anticipo</option> -->
													</select>

													<script>
														$('#tipo_pago').val('Tarjeta');
													</script>

												</div>
											</div>
											<div class="cal-box d-lg-flex align-items-lg-center mr-4" style="display:none !important;">
												<label class="cal-label mr-2 mb-0"><?php echo display('due') ?>:</label><span class="amount" id="due_text">0.00</span>
												<input type="hidden" id="dueAmmount" class="form-control text-right guifooterfixedinput" name="due_amount" value="0.00" readonly="readonly" />
											</div>

											<div class="cal-box d-lg-flex align-items-lg-center mr-4">
												<label class="cal-label mr-2 mb-0">Cambio:</label><span class="amount" id="cambio_text">0.00</span>

											</div>
										</div>
									</div>
									<div class="col-lg-3 col-xl-3 text-xl-right">
										<div class="action-btns d-flex justify-content-end">
											<!--<input type="button" id="full_paid_tab" class="btn btn-warning btn-lg mr-2" value="Totalmente pagado" tabindex="14" onClick="full_paid()"/>-->
											<input type="submit" id="add_invoice" class="btn btn-success btn-lg mr-2" name="add_invoice" value="Guardar venta">
											<a href="#" class="btn btn-info btn-lg" data-toggle="modal" id="calculator_modal" data-target="#calculator"><i class="fa fa-calculator" aria-hidden="true"></i> </a>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="">
							<h3>DATOS DE LA VENTA</h3>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<p>Datos del cliente:</p>
										<div class="col-md-6" style="margin-bottom:5px;">
											<input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" placeholder="Nombre del cliente" required>
										</div>
										<div class="col-md-6" style="margin-bottom:5px;">
											<input type="text" class="form-control phone" id="telefono_cliente" name="telefono_cliente" placeholder="Teléfono de cliente" required>
										</div>
										<div class="col-md-6" style="margin-bottom:5px;">
											<p>Fecha y hora de recogida:</p>
											<input type="text" class="form-control" id="dh_instore" name="dh_instore" required>
											<script>
												$(function() {
													$('#dh_instore').daterangepicker({
														timePicker: true,
														singleDatePicker: true,
														minDate: new Date(),
														locale: {
															format: 'YYYY-MM-DD hh:mm A',
															applyLabel: "Guardar",
															cancelLabel: "Cancelar",
															daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
															monthNames: [
																"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
																"Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"
															]
														}
													});
												});
											</script>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="mb-5">
							<!-- Checkbox para facturación con mejor espaciado -->
							<div class="form-check mb-4">
								<input class="form-check-input" type="checkbox" id="requiereFactura">
								<label class="form-check-label fw-medium" for="requiereFactura">
									Requiere Factura
								</label>
							</div>
							<div class="form-check mb-4">
								<input class="form-check-input" type="checkbox" id="cotizacion" value="1">
								<label class="form-check-label fw-medium" for="cotizacion">
									Cotización
								</label>
								<input type="hidden" name="quote" id="quote">
							</div>
						</div>

						<script>
							document.getElementById('requiereFactura').addEventListener('change', function() {
								const facturacionForm = document.getElementById('facturacionForm');
								facturacionForm.style.display = this.checked ? 'block' : 'none';

								const inputs = facturacionForm.querySelectorAll('[required]');
								inputs.forEach(input => {
									input.required = this.checked;
								});
							});
						</script>

						</form>
						<!--old tag end form -->
					</div>
				</div>
				<!--end row form -->
			</div>
			<!--newform-->
		</div>
		<div class="tab-pane fade" id="saleList">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="table-responsive padding10" id="invoic_list">
						<table id="gui_productinfo" class="table table-bordered  table-hover datatable ">
							<thead>
								<tr>
									<!--<th><?php echo display('sl') ?></th>
                                      <th><?php echo display('invoice_no') ?></th>
                                      <th><?php echo display('invoice_id') ?></th>
                                      <th><?php echo display('customer_name') ?></th>
                                      <th><?php echo display('date') ?></th>
                                      <th><?php echo display('total_amount') ?></th>
                                      <th><?php echo display('action') ?></th>-->
									<th>Factura</th>
									<th>Cliente</th>
									<th>Fecha de venta</th>
									<th>Importe venta</th>
									<th>Status</th>
									<th class="text-center">Opciones</th>
								</tr>
							</thead>
							<tbody id="gui_tbody">
								<?php
								$total = '0.00';
								$sl = 1;
								if ($todays_invoice) {
									foreach ($todays_invoice as $invoices_list) {

										$status = '';
										$this->db->select(
											'a.total_tax,
																a.*,
																b.*,
																c.*,
																d.product_id,
																d.product_name,
																d.product_details,
																d.unit,
																d.product_model,
																a.paid_amount as paid_amount,
																a.due_amount as due_amount'
										);
										$this->db->from('invoice a');
										$this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
										$this->db->join('customer_information b', 'b.customer_id = a.customer_id');
										$this->db->join('product_information d', 'd.product_id = c.product_id');
										$this->db->where('a.invoice_id', $invoices_list['invoice_id']);
										$this->db->where('c.quantity >', 0);
										$query = $this->db->get();
										$detalles =  $query->result_array();

										$ne = count($detalles);

										if ($ne > 1) {
											$status = '<table>';
											foreach ($detalles as $arreglo) {

												if ($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0') {
													$stt = '<span class="label label-danger">Pendiente</span>';
												} else if ($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0') {
													$stt = '<span class="label label-warning">En proceso de entrega</span>';
												} else if ($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1') {
													$stt = '<span class="label label-success">Entregado</span>';
												}

												$status .= '<tr>
																	<td>' . $arreglo['product_name'] . '</td>
																	<td>' . $stt . '</td>
															   <tr>';
											}
											$status .= '</table>';
										} else {

											if ($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0') {
												$status = '<span class="label label-danger">Pendiente</span>';
											} else if ($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0') {
												$status = '<span class="label label-warning">En proceso de entrega</span>';
											} else if ($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1') {
												$status = '<span class="label label-success">Entregado</span>';
											}
										}
								?>

										<tr>
											<!---<td><?php echo $sl; ?></td>-->
											<td>
												<a href="<?php echo base_url() . 'invoice_details/' . $invoices_list['invoice_id']; ?>">

													<?php echo html_escape($invoices_list['invoice']); ?>
												</a>
											</td>
											<!--<td>
                                                <a href="<?php echo base_url() . 'invoice_details/' . $invoices_list['invoice_id']; ?>">
                                                  <?php echo $invoices_list['invoice_id'] ?>  
                                                </a>
                                            </td>-->
											<td>
												<?php echo html_escape($invoices_list['customer_name']) ?>
											</td>

											<td><?php echo $invoices_list['date'] ?></td>
											<td class="text-right"><?php
																	if ($position == 0) {
																		echo $currency . $invoices_list['total_amount'];
																	} else {
																		echo $invoices_list['total_amount'] . $currency;
																	}
																	$total += $invoices_list['total_amount']; ?></td>

											<td><?php echo $status; ?></td>
											<td>
												<center>
													<?php echo form_open() ?>

													<a href="<?php echo base_url() . 'invoice_details/' . $invoices_list['invoice_id']; ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('invoice') ?>"><i class="fa fa-window-restore" aria-hidden="true"></i></a>
													<a href="<?php echo base_url() . 'invoice_pad_print/' . $invoices_list['invoice_id']; ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo 'Pad Print' ?>"><i class="fa fa-fax" aria-hidden="true"></i></a>

													<a href="<?php echo base_url() . 'pos_print/' . $invoices_list['invoice_id']; ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('pos_invoice') ?>"><i class="fa fa-fax" aria-hidden="true"></i></a>
													<?php if ($this->permission1->method('manage_invoice', 'update')->access()) { ?>

														<!--<a href="<?php echo base_url() . 'invoice_edit/' . $invoices_list['invoice_id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>-->
													<?php } ?>

													<?php echo form_close() ?>
												</center>
											</td>
										</tr>

								<?php
										$sl++;
									}
								}
								?>
							</tbody>
						</table>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="arr_personalizado">
			<div class="panel panel-default">

				<div class="panel-heading">
					<div class="panel-title">
						<h4>Producto personalizado</h4>
					</div>
				</div>
				<div class="panel-body">
					<input type="hidden" id="count_ip" value='<?php if (empty($insumos_pr)) {
																	echo 0;
																} else {
																	echo (int)count($insumos_pr);
																} ?>' name="">
					<?php echo form_open_multipart('product_form_arrp/' . $id, array('class' => 'form-vertical', 'id' => 'insert_product', 'name' => 'insert_product')) ?>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label for="product_name" class="col-sm-4 col-form-label"><?php echo display('product_name') ?> <i class="text-danger">*</i></label>
								<div class="col-sm-8">
									<input class="form-control" name="product_name" type="text" placeholder="<?php echo display('product_name') ?>" required tabindex="1">
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive product-supplier">
						<table class="table table-bordered table-hover" id="insumo_table">
							<thead>
								<tr>
									<th class="text-center">Insumo<i class="text-danger">*</i></th>
									<th class="text-center">Precio<i class="text-danger">*</i></th>
									<th class="text-center">Cantidad<i class="text-danger">*</i></th>
									<th class="text-center">Total<i class="text-danger">*</i></th>
									<th class="text-center"><?php echo display('action') ?> <i class="text-danger"></i>
										<input type="hidden" name="baseUrl" class="baseUrl" id="baseUrl" value="<?php echo base_url(); ?>" />
									</th>
								</tr>
							</thead>

							<tbody id="insumo_proudt_item">
								<tr>
									<td width="300">
										<select name="insumo_id[]" class="form-control insumo_id" serial="0" id="insumo_id_0" required>
											<option value="">Seleccionar insumo</option>
											<?php if ($insumos) { ?>
												<?php foreach ($insumos as $insumo) { ?>
													<option value="<?php echo $insumo['id'] ?>"><?php echo $insumo['product_name'] ?> (<?php echo $insumo['product_model'] ?>)</option>

											<?php }
											} ?>
										</select>
									</td>
									<td class="">
										<input type="text" tabindex="6" class="form-control text-right" name="insumo_price[]" placeholder="0.00" min="0" readonly serial="0" id="insumo_price_0" required />
									</td>
									<td class="">
										<input type="text" tabindex="6" class="form-control text-right insumo_cantidad" name="insumo_cantidad[]" placeholder="0.00" min="0" serial="0" id="insumo_cantidad_0" required />
									</td>
									<td class="">
										<input type="text" tabindex="6" class="form-control text-right insumo_total" name="insumo_total[]" placeholder="0.00" min="0" readonly serial="0" id="insumo_total_0" required />
									</td>
									<td>
										<a id="add_insumo_item" class="btn btn-info btn-sm" name="add_insumo_item" onClick="addInsumoItem('proudt_item')" tabindex="9"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
										<a class="btn btn-danger btn-sm" value="<?php echo display('delete') ?>" onclick="deleteInsumoRow(this)" tabindex="10"><i class="fa fa-trash" aria-hidden="true"></i></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group row">
								<label for="utilidad" class="col-sm-4 col-form-label">Utilidad (%) <i class="text-danger">*</i> </label>
								<div class="col-sm-8">
									<input class="form-control text-right" id="utilidad" name="utilidad" type="text" required="" placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->utilidad ?>">
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group row">
								<label for="utilidad" class="col-sm-4 col-form-label">I.V.A. (%) <i class="text-danger">*</i> </label>
								<div class="col-sm-8">
									<input class="form-control text-right" id="iva" name="iva" type="text" placeholder="0.00" tabindex="5" min="0" value="">
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group row">
								<label for="sell_price" class="col-sm-4 col-form-label"><?php echo display('sell_price') ?> <i class="text-danger">*</i> </label>
								<div class="col-sm-8">
									<input class="form-control text-right" id="sell_price_p" name="price" type="text" required="" placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->price ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<center><label for="description" class="col-form-label"><?php echo display('product_details') ?></label></center>
							<textarea class="form-control" name="description" id="description" rows="2" placeholder="<?php echo display('product_details') ?>" tabindex="2"><?php echo $product->product_details ?></textarea>
						</div>
					</div><br>
					<div class="form-group row">
						<div class="col-sm-6">

							<input type="submit" id="add-product" class="btn btn-primary btn-large" name="add-product" value="<?php echo display('save') ?>" tabindex="10" />
						</div>
					</div>
					<?php echo form_close() ?>

					<input type="hidden" id="supplier_list" value='<?php if ($supplier) { ?><?php foreach ($supplier as $suppliers) { ?><option value="<?php echo $suppliers['supplier_id'] ?>"><?php echo $suppliers['supplier_name'] ?></option><?php }
																																																											} ?>' name="">

					<input type="hidden" id="insumo_list" value='<?php if ($insumos) { ?><?php foreach ($insumos as $insumo) { ?><option value="<?php echo $insumo['id'] ?>"><?php echo $insumo['product_name'] ?> (<?php echo $insumo['product_model'] ?>)</option><?php }
																																																															} ?>' name="">

				</div>
			</div>
		</div>
	</div>
</div>
<div id="detailsmodal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<strong>
					<center> <?php echo display('product_details') ?></center>
				</strong>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="panel panel-bd">

							<div class="panel-body">
								<span id="modalimg"></span><br>
								<h4><?php echo display('product_name') ?> :<span id="modal_productname"></span></h4>
								<h4><?php echo display('product_model') ?> :<span id="modal_productmodel"></span></h4>
								<h4><?php echo display('price') ?> :<span id="modal_productprice"></span></h4>
								<h4><?php echo display('unit') ?> :<span id="modal_productunit"></span></h4>
								<h4><?php echo display('stock') ?> :<span id="modal_productstock"></span></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>
<div class="modal fade" id="printconfirmodal" tabindex="-1" role="dialog" aria-labelledby="printconfirmodal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<a href="" class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
				<h4 class="modal-title" id="myModalLabel"><?php echo display('print') ?></h4>
			</div>
			<div class="modal-body">
				<?php echo form_open('invoice_pos_print', array('class' => 'form-vertical', 'id' => '', 'name' => '')) ?>
				<div id="outputs" class="hide alert alert-danger"></div>
				<h3> <?php echo display('successfully_inserted') ?> </h3>
				<h4><?php echo display('do_you_want_to_print') ?> ??</h4>
				<input type="hidden" name="invoice_id" id="inv_id">
				<input type="hidden" name="url" value="<?php echo base_url('gui_pos'); ?>">
			</div>
			<div class="modal-footer">
				<button type="button" onclick="cancelprint()" class="btn btn-default" data-dismiss="modal"><?php echo display('no') ?></button>
				<button type="submit" class="btn btn-primary" id="yes"><?php echo display('yes') ?></button>
				<?php echo form_close() ?>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cheetsheet" tabindex="-1" role="dialog" aria-labelledby="cheetsheet" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<a href="" class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
				<h4 class="modal-title">Keyboard Shortcut</h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Event</th>
							<th>key</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">Submit Invoice</td>
							<td class="text-center">ctrl+s</td>
						</tr>
						<tr>
							<td class="text-center">Add New Customer</td>
							<td class="text-center">shif+c</td>
						</tr>
						<tr>
							<td class="text-center">Full Paid</td>
							<td class="text-center">shif+f</td>
						</tr>
						<tr>
							<td class="text-center">Today's Sale List</td>
							<td class="text-center">shif+l</td>
						</tr>
						<tr>
							<td class="text-center">New Sale</td>
							<td class="text-center">shif+n</td>
						</tr>
						<tr>
							<td class="text-center">Open Calculator</td>
							<td class="text-center">alt+c</td>
						</tr>
						<tr>
							<td class="text-center">Search Old Customer</td>
							<td class="text-center">alt+n</td>
						</tr>
						<tr>
							<td class="text-center">Invoice Discount</td>
							<td class="text-center">ctrl+d</td>
						</tr>
						<tr>
							<td class="text-center">Shipping Cost</td>
							<td class="text-center">alt+s</td>
						</tr>
						<tr>
							<td class="text-center">Paid Amount</td>
							<td class="text-center">alt+p</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url() ?>assets/js/perfect-scrollbar.min.js"></script>
<script>
	$(document).on('keyup keypress', 'input', function(e) {
		if (e.which == 13) {
			e.preventDefault();
			return false;
		}
	});
	var arrp = '<?php echo $_SESSION["personalizado"]; ?>';
	console.log(arrp);
	if (arrp != '') {
		setTimeout(function() {
			onselectimage(arrp);
		}, 1000);
	}
	$('#prlist').change(function(event) {
		var id_product = $(this).val();
		if (id_product != null) {
			onselectimage(id_product);
			// getProductById(id_product);
			// Nueva línea para aplicar el descuento por categoría
			setTimeout(function() {
				applyCategoryDiscount(id_product);
			}, 500);
		}
	});
	$(document).ready(function() {
		$('#branchoffice').val('ENCROMEX');
	});
	$('#boff').change(function(event) {
		var value = $(this).val();
		$('#branchoffice').val(value);
	});
	$('.phone').mask('(000) 000-0000');

	$('.product-grid').each(function() {
		const ps = new PerfectScrollbar($(this)[0]);
	});
	$(document).on('change', '#zona', function() {
		$.ajax({
			type: "POST",
			method: "POST",
			dataType: "JSON",
			url: "<?php echo base_url() ?>subzones/getByZone",
			data: {
				id: $(this).val()
			},
			success: function(subzones) {
				if (subzones.length > 0) {
					$("#subzone").attr("disabled", false)
					$("#subzone").empty()
					$("#subzone").append("<option value='' selected='' disabled=''>Seleccione una subzona</option>")
					subzones.forEach(function(subzone) {
						$("#subzone").append("<option data-price='" + subzone.price + "' value='" + subzone.id + "'>" + subzone.name + "</option>")
					})
				}
			},
			error: function() {}
		});
	});
	$(document).on('change', '#subzone', function() {
		let price = $("#subzone option:selected").attr("data-price")
		$("#shipping_cost").val(price)
		quantity_calculate(1);
	});

	$(document).on('change', '#cotizacion', function(e) {
		if ($('#cotizacion').prop('checked')) {
			console.log('xd');
			$('#add_invoice').val('Guardar cotización');

			//$('#gui_sale_insert').val('<?php echo base_url("invoice/invoice/bdtask_manual_sales_insert") ?>');
			$('#quote').val(1);
		} else {
			$('#quote').val(0);
		}
	});
	$(document).ready(function() {
		let sucursal = $('#boff').val();
		$.ajax({
			type: "POST",
			method: "POST",
			dataType: "JSON",
			url: "<?php echo base_url() ?>gui_pos/inv/" + sucursal,
			data: {
				id: $(this).val()
			},
			success: function(data) {
				if (data.invoice_no > 0) {
					$('#gui_invoice_no').html(data.invoice_no);
				}
			},
			error: function() {}
		});
	});

	$(document).on('change', '#boff', function() {
		let sucursal = $('#boff').val();
		sucursal = sucursal.replace(' ', '_');
		$.ajax({
			type: "POST",
			method: "POST",
			dataType: "JSON",
			url: "<?php echo base_url() ?>gui_pos/inv/" + sucursal,
			data: {
				id: $(this).val()
			},
			success: function(data) {
				if (data.invoice_no > 0) {
					$('#gui_invoice_no').html(data.invoice_no);
				}
			},
			error: function() {}
		});
	});
	// Comportamiento invertido MEJORADO
	$(document).ready(function() {
		// 1. Inicialización con estado invertido (como si estuviera checked)
		$('#instore').prop('checked', true);
		$('.instore').hide();
		$('#fh_entrega_tienda').show();

		// 2. Manejador de eventos robusto
		$('#instore').change(function() {
			$('.instore').toggle(!this.checked);
			$('#fh_entrega_tienda').toggle(this.checked);
		});

		// 3. Protección contra recargas de AJAX/DOM
		$(document).ajaxComplete(function() {
			var isChecked = $('#instore').is(':checked');
			$('.instore').toggle(!isChecked);
			$('#fh_entrega_tienda').toggle(isChecked);
		});
	});

	// 4. Parche para onselectimage (CORREGIDO)
	var originalOnselectimage = onselectimage;
	onselectimage = function(id_product) {
		if ($('#instore').length > 0) { // <-- Solo si existe #instore
			var instoreState = $('#instore').is(':checked'); // Guardar estado
			originalOnselectimage(id_product); // Llamar a la función original
			$('#instore').prop('checked', instoreState).trigger('change'); // Restaurar estado
		} else {
			// Si no existe #instore, solo ejecuta normalmente
			originalOnselectimage(id_product);
		}
	};

	$(document).ready(function() {
		// Aplicar máscara al campo de teléfono
		$('.phone').mask('(000) 000-0000');

		// También puedes aplicar la máscara dinámicamente
		$('#telefono_cliente').on('input', function() {
			var phone = $(this).val().replace(/\D/g, '');
			if (phone.length > 3 && phone.length <= 6) {
				phone = phone.replace(/(\d{3})(\d{1,3})/, '($1) $2');
			} else if (phone.length > 6) {
				phone = phone.replace(/(\d{3})(\d{3})(\d{1,4})/, '($1) $2-$3');
			}
			$(this).val(phone);
		});
	});
</script>