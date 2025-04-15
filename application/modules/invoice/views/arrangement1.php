        <!-- Manage Invoice report -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

		<input type="hidden" name="baseUrl" value="<?php echo base_url(); ?>" id="baseUrl"/>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span><?php echo display('manage_invoice') ?></span>
                            <span class="padding-lefttitle"> 
                <?php if($this->permission1->method('new_invoice','create')->access()){ ?>
                    <a href="<?php echo base_url('add_invoice') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('new_invoice') ?> </a>
                <?php }?>
            
                
               <?php if($this->permission1->method('gui_pos','create')->access()){ ?>
                    <a href="<?php echo base_url('gui_pos') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('pos_invoice') ?> </a>
                <?php }?>
                          </span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive" >
                            <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="ArrangementList1"> 
                                <thead>
                                    <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th>Factura</th>
                                    <th>Vendedor</th>
                                    <th>Fecha de compra</th>
                                    <th>Recibe</th>
                                    <th>Fecha de entrega</th>
									<th>Dirección</th>
									<th>Florista</th>
									<th>Tipo de pago</th>
									<th>Status</th>
                                    <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
             
                                </tbody>
                            </table>
                            
                        </div>
                       

                    </div>
                </div>
                <input type="hidden" id="total_invoice" value="<?php echo $total_invoice;?>" name="">
                
            </div>
        </div>