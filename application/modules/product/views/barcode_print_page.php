  <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> No Of <?php
                                if (empty($qr_image)) {
                                    echo display('barcode');
                                } else {
                                    echo display('qr_code');
                                }
                                ?> 
                                <span class="productbarcode-margin"></span>
                                <?php
                                if (empty($qr_image)) {
                                    echo display('barcode');
                                } else {
                                    echo display('qr_code');
                                }
                                ?> Qunatity Each Row
                            </h4>
                                
                                <div class="row">
                               <div class="col-sm-12">
                                <div class="form-group row">
                                <form>
                                    <div class="col-sm-4">
                                    <input type="number" name="qty" class="form-control" value="<?php echo (isset($_GET["qty"])?$_GET["qty"]:"1");
                                ?>">
                                </div>
                                 <div class="col-sm-4">
                                    <input type="number" name="cqty" class="form-control" value="<?php echo (isset($_GET["cqty"])?$_GET["cqty"]:"1");
                                ?>">
                                </div>
                                 <div class="col-sm-2">
                                    <input type="submit" name="submit" class="btn btn-success" value="Generate">
                                    </div>
                                </form>
                                </div>
                                </div>
                                </div>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Cproduct/insert_product') ?>
                    <div class="panel-body">

                       
                        <div class="table-responsive">
                            <?php
                            if (isset($product_id)) {
                                $qty = (isset($_GET["qty"])?$_GET["qty"]:"1");
                                $cqty = (isset($_GET["cqty"])?$_GET["cqty"]:"1");
                                ?>
                                <div id="printableArea">
                                    <div class="paddin5ps">
                                    <table  id="" class="table-bordered">
                                        <?php
                                        $counter = 0;
                                        for ($i = 0; $i < $qty; $i++) {
                                            ?>
                                            <?php if ($counter == $cqty) { ?>
                                                <tr> 
                                                    <?php $counter = 0; ?>
                                                <?php } ?>
                                                
                                                <td class="" style="font-size: 0.7em;">
                                                    <div class=" " style="text-align: center;">
                                                        <div class="product-name-details barcode-productdetails" style="font-size: 0.8em;">
                                                            <?php echo $product_name;?>
                                                        </div>
                                                        <img
                                                            class="img-responsive center-block barcode-image"
                                                            style="height: auto; display: block;"
                                                            alt=""
                                                            src="<?= base_url('vendor/barcode.php?size=30&text='.$product_id.'&print=false') ?>"
                                                        >
                                                        <div class="price barcode-price" style="font-size: 0.9em; margin-top: 1px;">
                                                            <?php echo (($position == 0) ? "$currency  $price" : "$price $currency") ?>
                                                            <br>
                                                            <span class="product-name-details barcode-productdetails" style="font-size: 0.85em;">
                                                                <?php echo $product_model ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php if ($counter == 5) { ?>
                                                </tr> 
                                                <?php $counter = 0; ?>
                                            <?php } ?>
                                            <?php $counter++; ?>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div id="printableArea">
                                    <div class="paddin5ps">
                                    <table class="table-bordered barcode-collaps">
                                        <?php
                                        $qty = (isset($_GET["qty"])?$_GET["qty"]:"1");
                                        $cqty = (isset($_GET["cqty"])?$_GET["cqty"]:"1");

                                        $counter = 0;
                                        for ($i = 0; $i < $qty; $i++) {
                                            ?>
                                            <?php if ($counter == $cqty) { ?>
                                                <tr> 
                                                    <?php $counter = 0; ?>
                                                <?php } ?>
                                                <td class="" style="font-size: 0.7em;">
                                                    <div style="text-align: center; margin: 0; padding: 2px;">
                                                        <div class="product-name-details qrcode-productdetails" style="font-size: 0.8em;">
                                                            <?php echo $product_name ?> 
                                                        </div> 
                                                        <img
                                                            src="<?php echo base_url('my-assets/image/qr/'.$qr_image) ?>"
                                                            class=""
                                                            style="height: 65px;"
                                                        >
                                                        <div class="price barcode-price" style="font-size: 0.9em;">
                                                            <?php echo (($position == 0) ? "$currency $price" : "$price $currency") ?>
                                                            <br>
                                                            <span class="product-name-details qrcode-productdetails" style="font-size: 0.85em;">
                                                                <?php echo $product_model; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php if ($counter == 5) { ?>
                                                </tr> 
                                                <?php $counter = 0; ?>
                                            <?php } ?>
                                            <?php $counter++; ?>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                    </div>
                       <?php
                        if (!empty($product_id) || !empty($qr_image)) {
                            ?>
                            <div class="text-center">
                                <a  class="btn btn-info" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                                <a  class="btn btn-danger" href="<?php echo base_url('product_list'); ?>"><?php echo display('cancel') ?></a>
                            </div>
                            <?php
                        }
                        ?>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>