<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-bd lobidrag">
			<div class="panel-heading">
				<div class="panel-title">
					<h4>Datos de sucursal</h4>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open_multipart('add_branchoffice/' . $employee->id, 'id="validate"') ?>
				<input type="hidden" name="id" id="id" value="<?php echo $employee->id ?>">
				<div class="form-group row">
					<label for="first_name" class="col-sm-2 col-form-div">Sucursal <i class="text-danger">*</i></label>
					<div class="col-sm-4">
						<input name="branchoffice" class="form-control" type="text" placeholder="Sucursal" required id="branchoffice" value="<?php echo $employee->branchoffice ?>">
					</div>
					<label for="phone" class="col-sm-2 col-form-div"><?php echo display('phone') ?> <i class="text-danger">*</i></label>
					<div class="col-sm-4">
						<input name="phone" class="form-control" type="text" placeholder="<?php echo display('phone') ?>" id="phone" required value="<?php echo $employee->phone ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="email" class="col-sm-2 col-form-div"><?php echo display('email') ?></label>
					<div class="col-sm-4">
						<input name="email" class="form-control" type="email" placeholder="<?php echo display('email') ?>" id="email" value="<?php echo $employee->email ?>">
					</div>
					<label for="address_line_1" class="col-sm-2 col-form-div">Dirección</label>
					<div class="col-sm-4">
						<textarea name="address" class="form-control" placeholder="Dirección" id="address"><?php echo $employee->address ?></textarea>
					</div>
					<label for="comision" class="col-sm-2 col-form-div">% de Comisión</label>
					<div class="col-sm-4">
						<input name="comision" class="form-control" type="number" min="0" step="0.01" placeholder="%." id="comision" value="<?php echo $employee->comision ?>">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js" integrity="sha512-hAJgR+pK6+s492clbGlnrRnt2J1CJK6kZ82FZy08tm6XG2Xl/ex9oVZLE6Krz+W+Iv4Gsr8U2mGMdh0ckRH61Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
	$('#phone').mask('(000) 000-0000');
</script>