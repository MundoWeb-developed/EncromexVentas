<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="" class="table table-bordered table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo display('sl') ?></th>
                                <th class="text-center">Socio comercial</th>
                                <th class="text-center"><?php echo display('phone') ?></th>
                                <th class="text-center"><?php echo display('email') ?></th>
                                <th class="text-center">Dirección</th>
                                <th class="text-center">% Comisión</th>
                                <th class="text-center"><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($partner_list)) { // Cambié employee_list por partner_list
                            ?>
                                <?php
                                $sl = 1;
                                foreach ($partner_list as $partner) { // Cambié employee por partner
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $sl; ?></td>
                                        <td class="text-center"><?php echo html_escape($partner['partner_name']); ?></td> <!-- Cambié employee por partner -->
                                        <td class="text-center"><?php echo html_escape($partner['phone']); ?></td> <!-- Cambié employee por partner -->
                                        <td class="text-center"><?php echo html_escape($partner['email']); ?></td> <!-- Cambié employee por partner -->
                                        <td class="text-center"><?php echo html_escape($partner['address']); ?></td> <!-- Cambié employee por partner -->
                                        <td class="text-center"><?php echo html_escape($partner['comision']) . '%'; ?></td> <!-- Cambié employee por partner -->
                                        <td>
                                            <center>
                                                <?php echo form_open() ?>
                                                <?php if ($this->permission1->method('manage_employee', 'update')->access()) { ?>
                                                    <a href="<?php echo base_url() . 'add_partner/' . $partner['id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                <?php } ?>
                                                <?php if ($this->permission1->method('manage_employee', 'delete')->access()) { ?>
                                                    <a href="<?php echo base_url('invoice/invoice/bdtask_delete_partner/' . $partner['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure') ?>')" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                <?php } ?>
                                                <?php echo form_close() ?>
                                            </center>
                                        </td>
                                    </tr>
                            <?php
                                    $sl++;
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
