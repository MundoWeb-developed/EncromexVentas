<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title ?> </h4>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="paid_CustomerList">
                    <thead>
                        <tr>
                            <th><?php echo display('sl') ?></th>
                            <th><?php echo display('customer_name') ?></th>
                            <th><?php echo display('address1'); ?></th>
                            <th><?php echo display('mobile_no') ?></th>
                            <th><?php echo display('email'); ?></th>
                            <th><?php echo display('custom_discount'); ?> (%)</th>
                            <th><?php echo display('city'); ?></th>
                            <th><?php echo display('state'); ?></th>
                            <th><?php echo display('zip'); ?></th>
                            <th><?php echo display('country'); ?></th>
                            <th><?php echo display('balance') ?></th>
                            <th width="100px;"><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody id="customer_tablebody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title ?> </h4>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="CustomerList2">
                    <thead>
                        <tr>
                            <th><?php echo display('customer_name') ?></th>
                            <th><?php echo display('mobile_no') ?></th>
                            <th>No. Compras</th>
                            <th>Monto de compras</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="customer_tablebody2">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="view_customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificar datos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url() ?>customer/customer/update_customer_data" method="post" class="form-vertical" id="update_customer_data" name="update_customer_data">
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="nombre_cliente" name="nombre_cliente">
                    <div class="form-group">
                        <label for="n_nombre_cliente">Nombre del cliente</label>
                        <input type="text" class="form-control" id="n_nombre_cliente" name="n_nombre_cliente">
                    </div>
                    <div class="form-group">
                        <label for="telefono_cliente">Teléfono del cliente</label>
                        <input type="text" class="form-control" id="telefono_cliente" name="telefono_cliente">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js" integrity="sha512-hAJgR+pK6+s492clbGlnrRnt2J1CJK6kZ82FZy08tm6XG2Xl/ex9oVZLE6Krz+W+Iv4Gsr8U2mGMdh0ckRH61Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('#telefono_cliente').mask('(000) 000-0000');
</script>