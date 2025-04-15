var count_insumo = $('#count_ip').val();
var count_insumo = parseInt(count_insumo);
var count = 2;


function calcular_precio(){
	
	var total_ip = 0;
	var p_util = $('#utilidad').val();
	
	p_util = parseFloat(p_util);
	var utilidad = (p_util/100)+1
	
	$( ".insumo_total" ).each(function( index ) {
		var tip = parseFloat($(this).val());
		total_ip+=tip;		
	});
	
	
	
	var precio_final = total_ip * utilidad;
	precio_final= Math.round(precio_final);
	
	if(!isNaN(precio_final)){
		$('#sell_price_p').val(precio_final);
	}else{
		$('#sell_price_p').val(0);
	}
	
	
	

}






setTimeout(function() {
	calcular_precio();
}, 1000);


	
    var limits = 500;
   
    "use strict";
    //Add purchase input field
    function addpruduct(e) {
		
         var supplier = $("#supplier_list").val();
        var t = '<td><select name="supplier_id[]" class="form-control" required=""><option value=""> select Supplier</option>'+supplier+'</select> </td><td class=""><input type="text"  class="form-control text-right" name="supplier_price[]" placeholder="0.00" required=""  min="0"/></td><td> <a  id="add_purchase_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addpruduct('+"proudt_item"+')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm"  value="" onclick="deleteRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
        count == limits ? alert("You have reached the limit of adding " + count + " inputs") : $("tbody#proudt_item").append("<tr>" + t + "</tr>")
        $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
    }









function addInsumoItem(e) {
	
	count_insumo++;
	
	var insumos = $("#insumo_list").val();
	
	var t = '<td width="300"><select name="insumo_id[]" class="form-control insumo_id" serial="'+count_insumo+'" id="insumo_id_'+count_insumo+'" required><option value="">Seleccionar insumo</option>'+insumos+'</select></td><td class=""><input type="text" tabindex="6" class="form-control text-right" name="insumo_price[]" placeholder="0.00"  min="0" readonly  serial="'+count_insumo+'" id="insumo_price_'+count_insumo+'" required/></td><td class=""><input type="text" tabindex="6" class="form-control text-right insumo_cantidad" name="insumo_cantidad[]" placeholder="0.00"  min="0"  serial="'+count_insumo+'" id="insumo_cantidad_'+count_insumo+'" required/></td><td class=""><input type="text" tabindex="6" class="form-control text-right insumo_total" name="insumo_total[]" placeholder="0.00"  min="0" readonly  serial="'+count_insumo+'" id="insumo_total_'+count_insumo+'" required/></td><td><a  id="add_insumo_item" class="btn btn-info btn-sm" name="add_insumo_item" onClick="addInsumoItem('+"proudt_item"+')"  tabindex="9"><i class="fa fa-plus-square" aria-hidden="true"></i></a><a class="btn btn-danger btn-sm"  value="" onclick="deleteInsumoRow(this)" tabindex="10"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
        count_insumo == limits ? alert("You have reached the limit of adding " + count_insumo + " inputs") : $("tbody#insumo_proudt_item").append("<tr>" + t + "</tr>")
	
	$("select.form-control:not(.dont-select-me)").select2({
		placeholder: "Select option",
		allowClear: true
	});
	
	calcular_precio();
	
}







function addinsumo(e) {
         var supplier = $("#supplier_list").val();
        var t = '<td><select name="supplier_id[]" class="form-control" required=""><option value=""> select Supplier</option>'+supplier+'</select> </td><td class="" style="display:none;"><input type="text"  class="form-control text-right" name="supplier_price[]" placeholder="0.00"  min="0"/></td><td> <a  id="add_purchase_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addinsumo('+"proudt_item"+')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm"  value="" onclick="deleteRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
        count == limits ? alert("You have reached the limit of adding " + count + " inputs") : $("tbody#proudt_item").append("<tr>" + t + "</tr>")
        $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
    }



        "use strict";
      function deleteRow(e) {
        var t = $("#product_table > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
       
    }



	function deleteInsumoRow(e) {
        var t = $("#insumo_table > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
		
		calcular_precio();       
    }



          window.onload = function () {
        var text_input = document.getElementById('product_id');
        text_input.focus();
        text_input.select();
    }


	$(document).on("keyup","#product_id",function() {
		var text = $(this).val();
		text = text.toUpperCase()
        $(this).val(text);
    });


	$(document).on("keyup","#sell_price_i",function() {
		var text = $(this).val();
        $('.supplier_price_insumo').val(text);
    });




	
	$(document).on('keyup','.insumo_cantidad',function(){
		var serial = $(this).attr('serial');
	  	var cantidad = $(this).val();
		var precio =  $('#insumo_price_'+serial).val();		
		var total = parseFloat(cantidad)*parseFloat(precio);		
		if(!isNaN(total)){
			$('#insumo_total_'+serial).val(total);
		}else{
			$('#insumo_total_'+serial).val(0);
		}
		calcular_precio();		
	});


	$(document).on('keyup','#utilidad',function(){
		calcular_precio();
	});


	$(document).on('change','.insumo_id',function(){
		var serial = $(this).attr('serial');
	  	var value = $(this).val();		
		var base_url = $('#base_url').val();	  	
		if(value!=''){
			 $.ajax({
                type: "POST",
                url: base_url+"product/product/bdtask_get_insumo_data",
                 data: {insumo:value},
                cache: false,
                success: function(data)
                {
                   obj = JSON.parse(data);
                   $('#insumo_price_'+serial).val(obj.price);
                } 
            });			
		}else{
			$('#insumo_price_'+serial).val(0);
			$('#insumo_cantidad_'+serial).val('');
			$('#insumo_total_'+serial).val(0);			
		}
		
		calcular_precio();
	});
