<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-bd lobidrag">
			<div class="panel-heading">
				<div class="panel-title">
					<h4>Datos de florista</h4>
				</div>
			</div>

			<div class="panel-body">
				

				<?php echo form_open_multipart('add_florist/'.$employee->id,'id="validate"') ?>
				<input type="hidden" name="id" id="id" value="<?php echo $employee->id?>">
				<div class="form-group row">
					<label for="first_name" class="col-sm-2 col-form-div"><?php echo display('first_name') ?> <i class="text-danger">*</i></label>
					<div class="col-sm-4">
						<input name="first_name" class="form-control" type="text" placeholder="<?php echo display('first_name') ?>" required id="first_name" value="<?php echo $employee->first_name?>">
						<input type="hidden" name="old_first_name" value="<?php echo $employee->first_name?>">
					</div>
					<label for="last_name" class="col-sm-2 col-form-div"><?php echo display('last_name') ?><i class="text-danger">*</i></label>
					<div class="col-sm-4">
						<input name="last_name" class="form-control" type="text" placeholder="<?php echo display('last_name') ?>" required id="last_name" value="<?php echo $employee->last_name?>">
						<input type="hidden" name="old_last_name" value="<?php echo $employee->last_name?>">
					</div>
				</div>
				
				<div class="form-group row">					
					<label for="phone" class="col-sm-2 col-form-div"><?php echo display('phone') ?> <i class="text-danger">*</i></label>
					<div class="col-sm-4">
						<input name="phone" class="form-control" type="text" placeholder="<?php echo display('phone') ?>" id="phone" required  value="<?php echo $employee->phone?>">
					</div>
					
					<label for="email" class="col-sm-2 col-form-div"><?php echo display('email') ?></label>
					<div class="col-sm-4">
						<input name="email" class="form-control" type="email" placeholder="<?php echo display('email') ?>" id="email" value="<?php echo $employee->email?>">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="address_line_1" class="col-sm-2 col-form-div">Dirección</label>
					<div class="col-sm-4">
						<textarea name="address" class="form-control" placeholder="Dirección" id="address"><?php echo $employee->address?></textarea> 
					</div>
					
					
					<label for="address_line_1" class="col-sm-2 col-form-div">Sucursal</label>
					<div class="col-sm-4">
						<select id="branchoffice" name="branchoffice" style="padding:8px;">
						<?php if($branchoffice){ ?>
							<?php foreach($branchoffice as $bo){ ?>
								<option value="<?php echo $bo->id; ?>"><?php echo $bo->branchoffice; ?></option>
							<?php } ?>
						<?php }else{ ?>
							<option>No hay sucursales</option>
						<?php } ?>
						</select>
						
						<script>
							var bo = '<?php echo $employee->branchoffice; ?>';
							$('#branchoffice').val(bo);
						</script>
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
<script>$('#phone').mask('(000) 000-0000');</script>