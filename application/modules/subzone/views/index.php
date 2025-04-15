
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="" class="table table-bordered table-striped table-hover datatable">
                        <thead>
                            <tr>
                <!--<th class="text-center">ID</th>-->
                <th class="text-center">Nombre</th>
                <th class="text-center">Costo</th>
                <th class="text-center">Zona</th>
                <th class="text-center"><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($subzones) {
                                ?>
                                
                                <?php
                            
                                    foreach($subzones as $subzone){?>
                                <tr>
                        <td class="text-center"><?php echo html_escape($subzone['name']);?></td>
                        <td class="text-center">$ <?php echo html_escape($subzone['price']);?></td>
                        <td class="text-center"><?php echo html_escape($subzone['zona']);?></td>
                        <td>
                            <center>
                                <?php echo form_open() ?>
                        
                                <a href="<?php echo base_url('editzone/'.$subzone['id']) ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                <a href="<?php echo base_url('deletesubzone/'.$subzone['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure') ?>')" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    <?php echo form_close() ?>
                            </center>
                            </td>
                            </tr>
                            
                            <?php
                        }}
                        ?>
                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>