<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Lista de clientes</h4>
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
                            <!-- <th><?php echo display('state'); ?></th> -->
                            <th><?php echo display('zip'); ?></th>
                            <th><?php echo display('country'); ?></th>
                            <th><?php echo display('action') ?></th>
                            <!-- <th width="100px;"><?php echo display('action') ?></th> -->
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
                    <h4>Compras realizadas</h4>
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
                        </tr>
                    </thead>
                    <tbody id="customer_tablebody2">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>