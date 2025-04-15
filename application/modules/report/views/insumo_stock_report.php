<input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" id="baseUrl"/>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-bd lobidrag">

			<div class="panel-body">
				<div>

					<div  id="printableArea">
						<div class="table-responsive paddin5ps">
							<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="checkListStockList_insumo">
								<thead>
									<tr>											
										<th class="text-center">Última fecha de compra</th>
										<th class="text-center">Nombre del insumo</th>
										<th class="text-center">Modelo</th>
										<th class="text-center">Precio de compra</th>
										<th class="text-center">Utilizados</th>
										<th class="text-center">Existencias</th>
										<th class="text-center">Opciones</th>


									</tr>
								</thead>
								<tbody>
								</tbody>

							</table>
						</div>
					</div>
				</div>
				<input type="hidden" id="currency" value="<?php echo $currency?>" name="">
				<input type="hidden" id="total_stock" value="<?php echo $totalnumber;?>" name="">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="view_insumo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Compras asociadas al insumo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="render_purchases_insumo">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <a href="<?php echo base_url() ?>add_insumo" class="btn btn-primary">Nueva compra</a>
      </div>
    </div>
  </div>
</div>




