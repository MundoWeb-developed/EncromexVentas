<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="panel panel-bd lobidrag">
			<div class="panel-heading">
				<div class="panel-title">
					<h4>Agregar zona</h4>
				</div>
			</div>

			<div class="panel-body">

				<?php echo form_open('savesubzone') ?>
				
				<div class="form-group row">
                    <div class="col-sm-12">
                        <label for="name" class="col-form-div">Nombre <i class="text-danger">*</i></label>
                        <input name="name" class="form-control" type="text" placeholder="nombre de la subzona" required id="name" value="">
                    </div>
				</div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="price" class="col-form-div">Costo de envío <i class="text-danger">*</i></label>
                        <input name="price" class="form-control" type="text" placeholder="Costo de envío" required id="price" value="">
                    </div>
				</div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="zona" class="col-form-div">zona a la que pertenece <i class="text-danger">*</i></label>
                        <select name="zone" id="zone" class="form-control">
                            <option value="">Seleccione la zona</option>
                            <?php foreach($zones as $zone){ ?>
                                <option value="<?php echo $zone['id'];?>"><?php echo $zone['zona'];?></option>
                            <?php } ?>
                        </select>
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
    $("#price").mask("#,##0.00", {reverse: true});
</script>