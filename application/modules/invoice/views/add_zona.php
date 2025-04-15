<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-bd lobidrag">
			<div class="panel-heading">
				<div class="panel-title">
					<h4>Datos de la zona</h4>
				</div>
			</div>

			<div class="panel-body">

				<?php echo form_open_multipart('add_zona/'.$employee->id,'id="validate"') ?>
				<input type="hidden" name="id" id="id" value="<?php echo $employee->id?>">
				<div class="form-group row">
					<label for="zona" class="col-sm-2 col-form-div">Zona <i class="text-danger">*</i></label>
					<div class="col-sm-4">
						<input name="zona" class="form-control" type="text" placeholder="zona" required id="zona" value="<?php echo $employee->zona?>">
					</div>					
					<label for="descripcion" class="col-sm-2 col-form-div">Descripción</label>
					<div class="col-sm-4">
						<textarea name="descripcion" class="form-control" placeholder="Descripción" id="descripcion"><?php echo $employee->descripcion?></textarea> 
					</div>
				</div>

				<div class="form-group text-right">
					<button type="reset" class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
					<button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
				</div>
				<?php echo form_close() ?>
			</div>

		</div>
	</div>
</div>