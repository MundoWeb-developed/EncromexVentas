<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
                
                <div class="panel-body">
					
					<input type="hidden" name="baseUrl" value="<?php echo base_url(); ?>" id="baseUrl"/>
                <?php echo form_open_multipart("add_user/$user->user_id") ?>
                    
                    <?php echo form_hidden('id',$user->id) ?>
					
					
					<div class="form-group row">
                        <label for="user_type" class="col-sm-2 col-form-label text-d"><?php echo display('user_type')?> <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
							
							<label class="radio-inline">
                                <?php echo form_radio('user_type', '1', (($user->user_type==1)?true:false), 'id="user_type1" checked="true"'); ?>Administrador
                            </label>							
							
                            <label class="radio-inline">
                                <?php echo form_radio('user_type', '2',(($user->user_type==2)?true:false) , 'id="user_type2"'); ?>Gerente
                            </label>
							
							<label class="radio-inline">
                                <?php echo form_radio('user_type', '3',(($user->user_type==3)?true:false) , 'id="user_type3"'); ?>Cajero
                            </label>
							
							<!-- <label class="radio-inline">
                                <?php echo form_radio('user_type', '4',(($user->user_type==4)?true:false) , 'id="user_type4"'); ?>Repartidor
                            </label>                            -->
                        </div>
                    </div>
					<div class="form-group row">
    <label for="branchoffice_id" class="col-sm-2 col-form-label text-d">Sucursal <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <select name="branchoffice_id" class="form-control" required>
            <option value="">-- Seleccionar sucursal --</option>
            <?php foreach($sucursales as $sucursal): ?>
				<option value="<?= $sucursal->id ?>" <?= (isset($user->branchoffice_id) && $user->branchoffice_id == $sucursal->id) ? 'selected' : '' ?>>                    <?= $sucursal->branchoffice ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
					<div id="div_deliveryman" class="form-group row" style="display:none;">
                        <label for="firstname" class="col-sm-2 col-form-label text-d">Repartidores</label>
                        <div class="col-sm-8">					
							<select id="deliveryman" name="deliveryman" style="padding:8px;">
							<?php if($deliveryman){ ?>
								<option value="">--Seleccionar repartidor--</option>
								<?php foreach($deliveryman as $dm){ ?>
									<option value="<?php echo $dm->id; ?>" ><?php echo $dm->first_name; ?> <?php echo $dm->last_name; ?></option>
								<?php } ?>
							<?php }else{ ?>
								<option>--No hay repartidores--</option>
							<?php } ?>
							</select>
						</div>
                    </div>
					
					<!-- <div id="div_florist" class="form-group row" style="display:none;">
                        <label for="firstname" class="col-sm-2 col-form-label text-d">Floristas</label>
                        <div class="col-sm-8">					
							<select id="florist" name="florist" style="padding:8px;">
							<?php if($florist){ ?>
								<option value="">--Seleccionar florista--</option>
								<?php foreach($florist as $fl){ ?>
									<option value="<?php echo $fl->id; ?>" ><?php echo $fl->first_name; ?> <?php echo $fl->last_name; ?></option>
								<?php } ?>
							<?php }else{ ?>
								<option>--No hay floristas--</option>
							<?php } ?>
							</select>
						</div>
                    </div> -->
					
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-2 col-form-label text-d"><?php echo display('first_name') ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                           <input name="firstname" class="form-control" type="text" placeholder="<?php echo display('first_name') ?>" id="firstname"  value="<?php echo $user->first_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="lastname" class="col-sm-2 col-form-label text-d"><?php echo display('last_name') ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input name="lastname" class="form-control" type="text" placeholder="<?php echo display('last_name') ?>" id="lastname" value="<?php echo $user->last_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label text-d"><?php echo display('email') ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input name="email" class="form-control" type="text" placeholder="<?php echo display('email') ?>" id="email_id" value="<?php echo $user->email ?>">
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label text-d"><?php echo display('password') ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input name="password" class="form-control" type="password" placeholder="<?php echo display('password') ?>" id="password">
                            <input name="oldpassword" class="form-control" type="hidden" value="<?php echo $user->password ?>">
                        </div>
                    </div>
					
					
					
					

                    <div class="form-group row">
                    <label for="preview" class="col-sm-2 col-form-label"><?php echo display('preview') ?></label>
                    <div class="col-sm-2">
                        <img src="<?php echo base_url(!empty($user->image) ? $user->image : "./assets/img/icons/default.jpg") ?>" class="img-thumbnail" width="125" height="100">
                    </div>
                    <div class="col-sm-7">

                    </div>
                    <input type="hidden" name="old_image" id="old_image" value="<?php echo $user->image ?>">
                </div> 
                <div class="form-group row">
                    <label for="image" class="col-sm-2 col-form-label"><?php echo display('image') ?></label>
                    <div class="col-sm-9">
                        <div>
                            <input type="file" name="image" id="edit_image" class="custom-input-file" />
                           
                        </div>
                    </div>
                </div> 

          			
                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label text-d"><?php echo display('status')?> <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <label class="radio-inline">
                                <?php echo form_radio('status', '1', (($user->status==1 || $user->status==null)?true:false), 'id="status"'); ?>Active
                            </label>
                            <label class="radio-inline">
                                <?php echo form_radio('status', '0', (($user->status=="0")?true:false) , 'id="status"'); ?>Inactive
                            </label> 
                        </div>
                    </div>
         
                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                        <button type="submit"  class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>
                <?php echo form_close() ?>

            </div>
        </div>
    </div>
</div>
<script>
	
	$(document).ready(function() {		
		var typur = '<?php echo $user->user_type; ?>';
		var deli = '<?php echo $user->deliveryman; ?>';
		var flor = '<?php echo $user->florist; ?>';		
		// if(typur =='3'){
		// 	$('#div_florist').attr('style', 'display:block;');
		// 	$('#florist').val(flor);		
		// }
		if(typur =='4'){
			$('#div_deliveryman').attr('style', 'display:block;');
			$('#deliveryman').val(deli);
		}
	});

	$('#deliveryman').val("");
	$('#florist').val("");
	
	$('#firstname').removeAttr("readonly");
	$('#lastname').removeAttr("readonly");
	
	//$('#firstname').val("");
	//$('#lastname').val("");
	
	$("[name=user_type]").change(function(event) {		
		
		$('#firstname').val("");
		$('#lastname').val("");
		
		$('#firstname').removeAttr("readonly");
		$('#lastname').removeAttr("readonly");
		
		$('#deliveryman').val("");
		$('#florist').val("");
		
		var tipo = $(this).val();
		$('#div_deliveryman').attr('style', 'display:none;');
		$('#div_florist').attr('style', 'display:none;');
		// if(tipo == '3'){
		// 	$('#div_florist').attr('style', 'display:block;');
		// 	$('#firstname').attr("readonly", "true");
		// 	$('#lastname').attr("readonly", "true");
		// }		
		if(tipo == '4'){
			$('#div_deliveryman').attr('style', 'display:block;');
			$('#firstname').attr("readonly", "true");
			$('#lastname').attr("readonly", "true");
		}
	});	
	
	
	$('#deliveryman').change(function(event) {
		var base_url = $("#baseUrl").val();
		var deliveryman = $(this).val();
		if(deliveryman!=""){
			$.ajax({
				url: base_url + 'invoice/invoice/bdtask_getdata_deliveryman',
				type: 'POST',
				dataType: 'json',
				data: {deliveryman: deliveryman},
			})
			.done(function(response) {
				$('#firstname').val(response.first_name);
				$('#lastname').val(response.last_name);
			})
			.fail(function() {
				console.log("error");
			});
		}	
	});
	
	
	$('#florist').change(function(event) {
		var base_url = $("#baseUrl").val();
		var florist = $(this).val();
		if(florist!=""){
			$.ajax({
				url: base_url + 'invoice/invoice/bdtask_getdata_florist',
				type: 'POST',
				dataType: 'json',
				data: {florist: florist},
			})
			.done(function(response) {
				$('#firstname').val(response.first_name);
				$('#lastname').val(response.last_name);
			})
			.fail(function() {
				console.log("error");
			});
		}	
	});
	
</script>