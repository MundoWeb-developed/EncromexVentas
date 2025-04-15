<?php $i=0;
          if($itemlist){
                    foreach($itemlist as $item){
                        
                        ?>
<div class="col-xs-6 col-sm-4 col-md-2 col-lg-2 col-p-2" style="padding-right: 5px;padding-left: 5px;">
	
	<a style="position: absolute;z-index: 1;" class="btn btn-xs btn-primary" id="arropt_<?php echo $item->product_id ?>" href="<?php echo !empty($item->image)?$item->image:'assets/img/icons/default.jpg'; ?>"><i class="ti-search"></i></a>
	<script>	
	$("#arropt_<?php echo $item->product_id ?>").fancybox();	
	</script>
	
<div class="product-panel overflow-hidden border-0 shadow-sm" id="image-active_<?php echo $item->product_id ?>">
    <div class="item-image position-relative overflow-hidden">
        <div class="" id="image-active_count_<?php echo $item->product_id ?>"><span id="active_pro_<?php echo $item->product_id ?>"></span></div>
        <img src="<?php echo base_url() ?><?php echo !empty($item->image)?$item->image:'assets/img/icons/default.jpg'; ?>" onclick="onselectimage('<?php echo $item->product_id ?>')" alt="" class="img-responsive">
    </div>
    <div class="panel-footer border-0 bg-white" onclick="onselectimage('<?php echo $item->product_id ?>')">
        <h3 class="item-details-title text-center"><?php echo  $text=html_escape($item->product_name);?><br><small style="font-size: 80%;"><?php echo  $text=html_escape($item->product_model);?></small></h3>
		
    </div>
</div>
</div>

<?php }}?>