  <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd">
                    <div id="printableArea" onload="printDiv('printableArea')">
                        <div class="panel-body">							
							
                            <div class="row print_header">					    	    
								<div class="col-sm-12">
									<p class="text-center" style="margin-bottom:10px;font-size:16px;">
                                    <img width="150" src="<?php echo base_url('assets/images/logo-on-text.jpg'); ?>" >
                                    <br>
                                    <b>Encromex</b>
									</p>
								</div>
							</div>
							
                            <div class="row print_header">
                                
                                <div class="col-sm-12 company-content">
                                    <!--<?php foreach($company_info as $company){?>
                                    <img src="<?php
                                    if (isset($setting->invoice_logo)) {
                                        echo html_escape($setting->invoice_logo);
                                    }
                                    ?>" class="img-bottom-m" alt="">-->
                                    <br>
                                    <span class="label label-success-outline m-r-15 p-10" ><?php echo display('billing_from') ?></span>
                                    <address class="margin-top10">
                                        <!--<strong class="company_name_p"><?php echo $company['company_name']?></strong><br>-->
										<p style="font-size:12px;">
										ENCROMEX<br>
										RFC:<br>
                                        <?php echo $company['address']?>
										</p>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr> <?php echo $company['mobile']?><br>
                                        <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                        <?php echo $company['email']?><br>
                                        <abbr><b><?php echo display('website') ?>:</b></abbr> 
                                        <?php echo $company['website']?><br>
                                      <?php }?>
                                         <abbr><?php echo $tax_regno?></abbr>
										
                                    </address><br>
									<span class="label label-success-outline m-r-15 p-10" >Sucursal</span>
									<address class="margin-top10">
                                        <strong class="company_name_p"><?php echo $branchoffice; ?></strong>
									</address>
									
									

                                </div>
                                
                                <div class="col-sm-12 company-content">
									<span class="label label-success-outline m-r-15 p-10" >Datos de entrega</span><br><br>
								</div>
                                <div class="col-sm-12 text-left invoice-address">
									
									
                                    <!--<h2 class="m-t-0"><?php echo display('invoice') ?></h2>-->
									<div><b><?php echo display('invoice_no') ?>:</b> <?php echo $invoice_no?></div>					
                                    <!--<div class="m-t-0"><b><?php echo display('billing_date') ?>:</b> <?php echo date("d-M-Y",strtotime($final_date));?></div>
									<div class="m-t-0"><b>Hora:</b> <?php
										if($creacion!='0000-00-00 00:00:00'){
											echo date("H:i A",strtotime($creacion));
										}else{
											echo '---';
										}
										
									?></div>-->
									
									
									
									<?php if($delivery_multiple=='1'){
	
												foreach ($data_reparto as $reparto){ ?>
									
													<address class="margin-top10">										
														<abbr><b>Destinatario:</b></abbr> <?php echo $reparto['destinatario'];?><br>
														<abbr><b>Tel. destinatario:</b></abbr> <?php echo $reparto['telefono'];?><br>
														<abbr><b>Dirección de entrega:</b></abbr> <?php echo $reparto['direccion'];?><br>
														<abbr><b>Hora de entrega:</b></abbr> <?php echo $reparto['hora_entrega'];?><br>
														<abbr><b>Descripción:</b></abbr> <?php echo $reparto['descripcion_entrega'];?><br>
														<abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime( $reparto['fecha_entrega'])); ?>
													</address>
													<hr>
												
												
												<?php }

									}else{?>
									
									<address class="margin-top10">										
                                        <abbr><b>Destinatario:</b></abbr> <?php echo $destinatario;?><br>
										<abbr><b>Tel. destinatario:</b></abbr> <?php echo $telefono;?><br>
										<abbr><b>Dirección de entrega:</b></abbr> <?php echo $direccion;?><br>
                                        <abbr><b>Hora de entrega:</b></abbr> <?php echo $hora_entrega;?><br>
                                        <abbr><b>Zona: </b></abbr> <?php echo $zona;?><br>
                                        <abbr><b>Descripción:</b></abbr> <?php echo $descripcion_entrega;?><br>
                                        <abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime($fecha_entrega)); ?>
                                    </address>
									
									
									<?php }?>
									

                                    <address class="customer_name_p">
										<abbr><b>Venta vía:</b></abbr> <?php echo $tipo_venta;?><br>
										<abbr><b>Vendedor:</b></abbr> <?php echo $seller;?><br>
										<abbr><b>Tipo de pago:</b></abbr> <?php echo $tipo_pago;?><br>
										
										<!--<strong class="c_name"><?php echo $customer_name?> </strong><br>
                                        
										<?php if ($customer_address) { ?>
                                            <?php echo $customer_address;?>
                                        <?php } ?>
                                        <br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr>
                                        <?php if ($customer_mobile) { ?>
                                            <?php echo $customer_mobile;?>
                                        <?php }if ($customer_email) {
                                            ?>
                                            <br>
                                            <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                            <?php echo $customer_email;?>
                                        <?php } ?>-->
                                    </address>
                                </div>
                            </div> 
							
							
							<div class="row margin-top50 margin-bottom50">
								
								<!--<div class="col-md-12">
									
									<h2 class="m-t-0">Reparto</h2>
									
									<?php if($delivery_multiple=='1'){
	
												foreach ($data_reparto as $reparto){ ?>
													<address class="margin-top10">
														<abbr><b>Arreglo:</b></abbr> <?php echo $reparto['arreglo'];?><br>
														<abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime( $reparto['fecha_entrega'])); ?><br>
														<abbr><b>Hora de entrega:</b></abbr> <?php echo $reparto['hora_entrega'];?><br>
														<abbr><b>Destinatario:</b></abbr> <?php echo $reparto['destinatario'];?><br>
														<abbr><b>Dirección:</b></abbr> <?php echo $reparto['direccion'];?><br>
														<abbr><b>Teléfono:</b></abbr> <?php echo $reparto['telefono'];?><br>
														<abbr><b>Descripción:</b></abbr> <?php echo $reparto['descripcion_entrega'];?><br>
													</address>
													<hr>
												
												
												<?php }

									}else{?>
									
									<address class="margin-top10">
										
                                        <abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime($fecha_entrega)); ?><br>
										<abbr><b>Hora de entrega:</b></abbr> <?php echo $hora_entrega;?><br>
                                        <abbr><b>Destinatario:</b></abbr> <?php echo $destinatario;?><br>
                                        <abbr><b>Dirección:</b></abbr> <?php echo $direccion;?><br>
                                        <abbr><b>Teléfono:</b></abbr> <?php echo $telefono;?><br>
                                        <abbr><b>Descripción:</b></abbr> <?php echo $descripcion_entrega;?><br>
                                    </address>
									
									
									<?php }?>
									
								</div>-->
								
								<!--<div class="col-md-4">
									<h2 class="m-t-0">Caja</h2>
									
									
									<?php if($delivery_multiple=='1'){
	
	
											foreach ($data_caja as $caja){ ?>
													<address class="margin-top10">                                        									
														<abbr><b>Cliente:</b></abbr> <?php echo $caja['cliente']; ?><br>
														<abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime($caja['fecha_entrega'])); ?><br>
														<abbr><b>Destinatario:</b></abbr> <?php echo $caja['destinatario']; ?><br>
														<abbr><b>Dirección:</b></abbr> <?php echo $caja['direccion']; ?><br>
														<abbr><b>Descripción:</b></abbr> <?php echo $caja['descripcion_entrega']; ?><br>
														<abbr><b>Repartidor:</b></abbr> <?php echo $caja['nombre_repartidor']; ?><br>
														<abbr><b>Atendió:</b></abbr> <?php echo $caja['atencion']; ?><br>
													</address>
													<hr>
												
												
												<?php }
	
	

										}else{?>
									
									<address class="margin-top10">                                        									
										<abbr><b>Cliente:</b></abbr> <?php echo $cliente_caja; ?><br>
										<abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime($fecha_entrega_caja)); ?><br>
										<abbr><b>Destinatario:</b></abbr> <?php echo $destinatario_caja; ?><br>
										<abbr><b>Dirección:</b></abbr> <?php echo $direccion_caja; ?><br>
										<abbr><b>Descripción:</b></abbr> <?php echo $descripcion_entrega_caja; ?><br>
										<abbr><b>Repartidor:</b></abbr> <?php echo $repartidor_caja; ?><br>
										
                                    </address>
									<?php }?>
									
								</div>
								
								<div class="col-md-4">
									<h2 class="m-t-0">Taller</h2>

									<?php if($delivery_multiple=='1'){
	
	
												foreach ($data_taller as $taller){ ?>
													<address class="margin-top10">
														<abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime($taller['fecha_entrega'])); ?><br>
														<abbr><b>Florista:</b></abbr> <?php echo $taller['nombre_florista']; ?><br>
														
														
														<abbr><b>Atendió:</b></abbr> <?php echo $taller['atencion']; ?><br>


									</address>
													<hr>											
												
												<?php }


											}else{?>
									
			<address class="margin-top10">
				<abbr><b>Fecha de entrega:</b></abbr> <?php echo date('d/m/Y', strtotime($fecha_entrega_taller)); ?><br>
				<abbr><b>Florista:</b></abbr> <?php echo $florista_taller; ?><br>
				
				
				
            </address>
									<?php }?>
									
									
								</div>-->
								
							</div>
							
							

                            <div class="table-responsive">
                                <table class="table table-striped" style="font-size:80%;">
                                    <thead>
                                                   <tr>
                                            <th class="text-center"><?php echo display('sl') ?></th>
                                            <th class="text-center"><?php echo display('product_name') ?></th>
                                              <th class="text-center"><?php if($is_unit !=0){ echo display('unit');
                                              }?></th>
                                            <th class="text-center"><?php if($is_desc !=0){ echo display('item_description');} ?></th>
                                            <th class="text-center"><?php if($is_serial !=0){ echo display('serial_no');} ?></th>
                                            <th class="text-right"><?php echo display('quantity') ?></th>
                                            <!--<?php if($is_discount > 0){ ?>
                                            <?php if ($discount_type == 1) { ?>
                                                <th class="text-right"><?php echo display('discount_percentage') ?> %</th>
                                            <?php } elseif ($discount_type == 2) { ?>
                                                <th class="text-right"><?php echo display('discount') ?> </th>
                                            <?php } elseif ($discount_type == 3) { ?>
                                                <th class="text-right"><?php echo display('fixed_dis') ?> </th>
                                            <?php } ?>
                                        <?php }else{ ?>
<th class="text-right"><?php echo ''; ?> </th>
<?php }?>
                                            <th class="text-right"><?php echo display('rate') ?></th>
                                            <th class="text-right"><?php echo display('ammount') ?></th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($invoice_all_data as $details){?>
                                        <tr>
                                            <td class="text-center"><?php echo $details['sl']?></td>
                                            <td class="text-center"><div><?php echo $details['product_name']?> - (<?php echo $details['product_model']?>)</div></td>
                                              <td class="text-center"><div><?php echo $details['unit']?></div></td>
                                            <td align="center"><?php echo $details['description']?></td>
                                            <td align="center"><?php echo $details['serial_no']?></td>
                                            <td align="right"><?php echo $details['quantity']?></td>

                                            <!--<?php if ($discount_type == 1) { ?>
                                                <td align="right"><?php echo $details['discount_per']?></td>
                                            <?php } else { ?>
                                                <td align="right"><?php echo (($position == 0) ? $currency.' '.$details['discount_per'] : $details['discount_per'].' '. $currency) ?></td>
                                            <?php } ?>

                                            <td align="right"><?php echo (($position == 0) ? $currency.' '.$details['rate'] : $details['rate'].' '. $currency) ?></td>
                                            <td align="right"><?php echo (($position == 0) ? $currency.' '.$details['total_price'] : $details['total_price'].' '. $currency) ?></td>-->
                                        </tr>
                                        <?php }?>
										
										
                                        <!--<tr>
                                            <td class="text-left" colspan="5"><b><?php echo display('grand_total') ?>:</b></td>
                                            <td align="right" ><b><?php echo number_format($subTotal_quantity,2)?></b></td>
                                            <td></td>
                                            <td></td>
                                            <td align="right" ><b><?php echo (($position == 0) ? $currency.' '.$subTotal_ammount  : $subTotal_ammount.' '. $currency) ?></b></td>
                                        </tr>-->
										
										
                                    </tbody>

                                </table>
                            </div>
							
                               <!--<div class="row">

                               
                                <div class="col-xs-12 inline-block">

                                    <table class="table" style="font-size:80%;">
                                        <?php
                                        if ($invoice_all_data[0]['total_discount'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="border-bottom-top"><?php echo display('total_discount') ?> : </th>
                                                <td class="text-right border-bottom-top"><?php echo html_escape((($position == 0) ? $currency.' '.$total_discount  : $total_discount.' '. $currency)) ?> </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($invoice_all_data[0]['total_tax'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="text-left border-bottom-top"><?php echo display('tax') ?> : </th>
                                                <td  class="text-right border-bottom-top"><?php echo html_escape((($position == 0) ? $currency.' '.$total_tax : $total_tax.' '. $currency)) ?> </td>
                                            </tr>
                                        <?php } ?>
                                         <?php if ($invoice_all_data[0]['shipping_cost'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="text-left border-bottom-top"><?php echo 'Shipping Cost' ?> : </th>
                                                <td class="text-right border-bottom-top"><?php echo html_escape((($position == 0) ? $currency.' '.$shipping_cost: $shipping_cost.' '. $currency)) ?> </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <th class="text-left grand_total"><?php echo display('previous'); ?> :</th>
                                            <td class="text-right grand_total"><?php echo html_escape((($position == 0) ? $currency.' '.$previous  :$previous.' '. $currency)) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-left grand_total"><?php echo display('grand_total') ?> :</th>
                                            <td class="text-right grand_total"><?php echo html_escape((($position == 0) ? $currency.' '.$total_amount : $total_amount.' '. $currency)) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-left grand_total border-bottom-top"><?php echo display('paid_ammount') ?> : </th>
                                            <td class="text-right grand_total border-bottom-top"><?php echo html_escape((($position == 0) ? $currency.' '.$paid_amount : $paid_amount.' '. $currency)) ?></td>
                                        </tr>                
                                        <?php
                                        if ($invoice_all_data[0]['due_amount'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="text-left grand_total"><?php echo display('due') ?> : </th>
                                                <td  class="text-right grand_total"><?php echo html_escape((($position == 0) ? $currency.' '.$due_amount : $due_amount.' '. $currency)) ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>

                                   

                                </div>
								    <div class="col-xs-12 invoicefooter-content">

                                    <p></p>
                                    <p><strong><?php echo $invoice_details?></strong></p> 
                                   
                                </div>
                            </div>-->							
							
							<!--<div class="row margin-top50">
                                <div class="col-sm-12">
									<p>Este no es un comprobante fiscal, si requiere factura por favor solicítela al  9513429409 el mismo día de su compra.</p>
                                </div>                               
                            </div>-->
							<br><br><br><br>
                            <div class="row">
                                <div class="col-sm-12">
                                 	<div class="inv-footer-left">
                                        Firma de recibido
                                    </div>
                                </div>                               
                            </div>
                           
                        </div>
                    </div>

                    <div class="panel-footer text-left">
                       
                        <button  class="btn btn-info" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button>

                    </div>
                </div>
            </div>
        </div>