
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="" class="table table-bordered table-striped table-hover datatable">
                                <thead>
                                    <tr>
                        <th class="text-center"><?php echo display('sl') ?></th>
                        <th class="text-center">Zona</th>
                        <th class="text-center">Dirección</th>
                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($employee_list) {
                                        ?>
                                       
                                        <?php
                                        $sl = 1;
                                         foreach($employee_list as $employees){?>
                                        <tr>
                                <td class="text-center"><?php echo $sl;?></td>
                                <td class="text-center"><?php echo html_escape($employees['zona']);?></td>
								<td class="text-center"><?php echo html_escape($employees['descripcion']);?></td>
                                <td>
                                    <center>
                                        <?php echo form_open() ?>
                              
                                        <a href="<?php echo base_url() . 'add_zona/'.$employees['id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        
                                        <a href="<?php echo base_url('invoice/invoice/bdtask_delete_zona/'.$employees['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure') ?>')" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            <?php echo form_close() ?>
                                    </center>
                                    </td>
                                    </tr>
                                   
                                    <?php
                                    $sl++;
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