var elems = [];
discountsByCategory = response.discounts_by_category || {};

//Add Input Field Of Row
("use strict");
function addInputField(t) {
    console.log(t);
    var row = $("#addinvoice tbody tr").length;
    var count = row + 1;
    var tab1 = 0;
    var tab2 = 0;
    var tab3 = 0;
    var tab4 = 0;
    var tab5 = 0;
    var tab6 = 0;
    var tab7 = 0;
    var tab8 = 0;
    var tab9 = 0;
    var limits = 500;
    var taxnumber = $("#txfieldnum").val();
    var tbfild = "";
    
    for (var i = 0; i < taxnumber; i++) {
        var taxincrefield =
            '<input id="total_tax' +
            i +
            "_" +
            count +
            '" class="total_tax' +
            i +
            "_" +
            count +
            '" type="hidden"><input id="all_tax' +
            i +
            "_" +
            count +
            '" class="total_tax' +
            i +
            '" type="hidden" name="tax[]">';
        tbfild += taxincrefield;
    }

    if (count == limits) {
        alert("You have reached the limit of adding " + count + " inputs");
    } else {
        var a = "product_name_" + count,
            tabindex = count * 5,
            e = document.createElement("tr");
        
        tab1 = tabindex + 1;
        tab2 = tabindex + 2;
        tab3 = tabindex + 3;
        tab4 = tabindex + 4;
        tab5 = tabindex + 5;
        tab6 = tabindex + 6;
        tab7 = tabindex + 7;
        tab8 = tabindex + 8;
        tab9 = tabindex + 9;

        e.innerHTML =
            "<td><input type='text' name='product_name' onkeypress='invoice_productList(" +
            count +
            ");' class='form-control productSelection common_product' placeholder='Product Name' id='" +
            a +
            "' required tabindex='" +
            tab1 +
            "'><input type='hidden' class='common_product autocomplete_hidden_value  product_id_" +
            count +
            "' name='product_id[]' id='SchoolHiddenId'/></td><td><input type='text' name='desc[]'' class='form-control text-right ' /></td><td><select class='form-control' id='serial_no_" +
            count +
            "' name='serial_no[]'><option></option></select></td> <td><input type='text' name='available_quantity[]' id='' class='form-control text-right common_avail_qnt available_quantity_" +
            count +
            "' value='0' readonly='readonly' /></td><td><input class='form-control text-right common_name unit_" +
            count +
            " valid' value='None' readonly='' aria-invalid='false' type='text'></td><td> <input type='text' name='product_quantity[]' required='required' onkeyup='quantity_calculate(" +
            count +
            ");' onchange='quantity_calculate(" +
            count +
            ");' id='total_qntt_" +
            count +
            "' class='common_qnt total_qntt_" +
            count +
            " form-control text-right'  placeholder='0.00' min='0' tabindex='" +
            tab2 +
            "' value='1'/></td><td><input type='text' name='product_rate[]' onkeyup='quantity_calculate(" +
            count +
            ");' onchange='quantity_calculate(" +
            count +
            ");' id='price_item_" +
            count +
            "' class='common_rate price_item" +
            count +
            " form-control text-right' required placeholder='0.00' min='0' tabindex='" +
            tab3 +
            "'/></td><td><input type='text' name='discount[]' onkeyup='quantity_calculate(" +
            count +
            ");' onchange='quantity_calculate(" +
            count +
            ");' id='discount_" +
            count +
            "' class='form-control text-right common_discount' placeholder='0.00' min='0' tabindex='" +
            tab4 +
            "' /><input type='hidden' value='' name='discount_type' id='discount_type_" +
            count +
            "'></td><td class='text-right'><input class='common_total_price total_price form-control text-right' type='text' name='total_price[]' id='total_price_" +
            count +
            "' value='0.00' readonly='readonly'/></td><td>" +
            tbfild +
            "<input type='hidden' id='all_discount_" +
            count +
            "' class='total_discount' name='discount_amount[]'/><button tabindex='" +
            tab5 +
            "' style='text-align: center;' class='btn btn-danger btn-xs' type='button'  onclick='deleteRow(this)'><i class='fa fa-close'></i></button></td>";

        document.getElementById(t).appendChild(e);
        document.getElementById(a).focus();

        // Agregar un pequeño retraso para asegurar que los valores estén listos antes de aplicar el descuento
        setTimeout(function() {
            // Obtener el ID de categoría del producto
            var categoryId = response.category_id; // Esto viene de retrieve_product_data_inv

            // Buscar el descuento correspondiente
            var discount = 0;
            if (discountsByCategory.hasOwnProperty(categoryId)) {
                discount = discountsByCategory[categoryId];
            }

            // Si existe descuento y el input del descuento está en el DOM, lo aplicamos
            var discountInput = document.getElementById("discount_" + count);
            if (discountInput) {
                discountInput.value = discount;
            }
        }, 200); // Espera 200 ms para que el DOM esté listo
        
        document
            .getElementById("add_invoice_item")
            .setAttribute("tabindex", tab6);
        document.getElementById("paidAmount").setAttribute("tabindex", tab7);
        document.getElementById("full_paid_tab").setAttribute("tabindex", tab8);
        document.getElementById("add_invoice").setAttribute("tabindex", tab9);
        count++;
    }
}

//Quantity calculat
("use strict");
function quantity_calculate(item) {
  var quantity = $("#total_qntt_" + item).val();
  var available_quantity = $(".available_quantity_" + item).val();
  var price_item = $("#price_item_" + item).val();
  var invoice_discount = $("#invoice_discount").val();
  var discount = $("#discount_" + item).val();
  var total_tax = $("#total_tax_" + item).val();
  var total_discount = $("#total_discount_" + item).val();
  var taxnumber = $("#txfieldnum").val();
  var dis_type = $("#discount_type").val();

  if (
    parseInt(available_quantity) == 0 &&
    parseInt(quantity) > parseInt(available_quantity)
  ) {
    var message = "This Item is Out of Stock";
    toastr["error"](message);
    $("table#addinvoice tr#row_" + item).remove();

    $("#image-active_" + item).removeClass("active");
    $("#image-active_count_" + item).removeClass("quantity");
    $("#active_pro_" + id).text("");

    $("#total_qntt_" + item).val(0);
    var quantity = 0;
    $("#total_price_" + item).val(0);
    for (var i = 0; i < taxnumber; i++) {
      $("#all_tax" + i + "_" + item).val(0);
      quantity_calculate(item);
    }
  }
  if (
    parseInt(available_quantity) > 0 &&
    parseInt(quantity) > parseInt(available_quantity)
  ) {
    var message = "You can Sale maximum " + available_quantity + " Items";
    toastr["error"](message);
    $("#total_qntt_" + item).val(available_quantity);
    quantity_calculate(item);
  }

  if (quantity > 0 || discount > 0) {
    if (dis_type == 1) {
      var price = quantity * price_item;
      var dis = +(price * (discount / 100));

      $("#all_discount_" + item).val(dis);
      //Total price calculate per product
      var temp = price - dis;
      var ttletax = 0;
      $("#total_price_" + item).val(temp);
      for (var i = 0; i < taxnumber; i++) {
        var tax = temp * $("#total_tax" + i + "_" + item).val();
        ttletax += Number(tax);
        $("#all_tax" + i + "_" + item).val(tax);
      }
    } else if (dis_type == 2) {
      var price = quantity * price_item;
      // Discount cal per product
      var dis = discount * quantity;

      $("#all_discount_" + item).val(dis);
      //Total price calculate per product
      var temp = price - dis;
      $("#total_price_" + item).val(temp);

      var ttletax = 0;
      for (var i = 0; i < taxnumber; i++) {
        var tax = temp * $("#total_tax" + i + "_" + item).val();
        ttletax += Number(tax);
        $("#all_tax" + i + "_" + item).val(tax);
      }
    } else if (dis_type == 3) {
      var total_price = quantity * price_item;
      var dis = discount;

      // Discount cal per product
      $("#all_discount_" + item).val(dis);
      //Total price calculate per product
      var price = total_price - dis;
      $("#total_price_" + item).val(price);

      var ttletax = 0;
      for (var i = 0; i < taxnumber; i++) {
        var tax = price * $("#total_tax" + i + "_" + item).val();
        ttletax += Number(tax);
        $("#all_tax" + i + "_" + item).val(tax);
      }
    }
  } else {
    var n = quantity * price_item;
    var c = quantity * price_item * total_tax;
    $("#total_price_" + item).val(n), $("#all_tax_" + item).val(c);
  }
  calculateSum();
  invoice_paidamount();
}
//Calculate Sum
("use strict");
function calculateSum() {
  var taxnumber = $("#txfieldnum").val();
  var t = 0,
    a = 0,
    e = 0,
    o = 0,
    p = 0,
    f = 0,
    ad = 0,
    tx = 0,
    ds = 0;
  var s_cost = $("#shipping_cost").val();

  //Total Tax
  for (var i = 0; i < taxnumber; i++) {
    var j = 0;
    $(".total_tax" + i).each(function () {
      isNaN(this.value) ||
        0 == this.value.length ||
        (j += parseFloat(this.value));
    });
    $("#total_tax_ammount" + i).val(j.toFixed(2, 2));
  }

  //Total Discount
  $(".total_discount").each(function () {
    isNaN(this.value) ||
      0 == this.value.length ||
      (p += parseFloat(this.value));
  }),
    $("#total_discount_ammount").val(p.toFixed(2, 2)),
    $(".totalTax").each(function () {
      isNaN(this.value) ||
        0 == this.value.length ||
        (f += parseFloat(this.value));
    }),
    $("#total_tax_amount").val(f.toFixed(2, 2)),
    //Total Price
    $(".total_price").each(function () {
      isNaN(this.value) ||
        0 == this.value.length ||
        (t += parseFloat(this.value));
    }),
    $(".dppr").each(function () {
      isNaN(this.value) ||
        0 == this.value.length ||
        (ad += parseFloat(this.value));
    }),
    (o = a.toFixed(2, 2)),
    (e = t.toFixed(2, 2)),
    (tx = f.toFixed(2, 2)),
    (ds = p.toFixed(2, 2));

  var invoice_discount_percent = $("#invoice_discount").val();
  var subtotal = +tx + +s_cost + +e + +ad;

  // Aplicar descuento general de la factura (si es porcentual)
  if (invoice_discount_percent > 0) {
    var discount_amount = subtotal * (invoice_discount_percent / 100);
    $("#total_discount_ammount").val(discount_amount.toFixed(2));
    subtotal -= discount_amount;
  }

  $("#grandTotal").val(subtotal.toFixed(2, 2));

  var gt = $("#grandTotal").val();
  var invdis = $("#invoice_discount").val();
  var total_discount_ammount = $("#total_discount_ammount").val();
  var ttl_discount = +total_discount_ammount;
  $("#total_discount_ammount").val(ttl_discount.toFixed(2, 2));
  var grnt_totals = gt;
  invoice_paidamount();
  $("#grandTotal").val(grnt_totals);
}

//Invoice Paid Amount
("use strict");
function invoice_paidamount() {
  var prb = parseFloat($("#previous").val(), 10);
  var pr = 0;
  var d = 0;
  var nt = 0;
  if (prb != 0) {
    pr = prb;
  } else {
    pr = 0;
  }
  var t = $("#grandTotal").val(),
    a = $("#paidAmount").val(),
    e = t - a,
    f = e + pr,
    nt = parseFloat(t, 10) + pr;
  d = a - nt;

  var valp = parseFloat(a);
  if (valp > 0) {
    $("#tipo_pago").val("Efectivo");
  } else {
    $("#tipo_pago").val("Tarjeta");
  }

  $("#n_total").val(nt.toFixed(2, 2));
  $("#net_total_text").text(nt.toFixed(2, 2));

  if (f > 0) {
    $("#dueAmmount").val(f.toFixed(2, 2));
    $("#due_text").text(f.toFixed(2, 2));
    if (a <= f) {
      $("#change").val(0);
      $("#cambio_text").html(0);
    }
  } else {
    if (a < f) {
      $("#change").val(0);
      $("#cambio_text").html(0);
    }
    if (a > f) {
      $("#change").val(d.toFixed(2, 2));
      $("#cambio_text").html(d.toFixed(2, 2));
    }
    $("#dueAmmount").val(0);
    $("#due_text").text(0);
  }
}

//Stock Limit
("use strict");
function stockLimit(t) {
  var a = $("#total_qntt_" + t).val(),
    e = $(".product_id_" + t).val(),
    o = $(".baseUrl").val();

  $.ajax({
    type: "POST",
    url: o + "Cinvoice/product_stock_check",
    data: {
      product_id: e,
    },
    cache: !1,
    success: function (e) {
      alert(e);
      if (a > Number(e)) {
        var o = "You can Sale maximum " + e + " Items";
        alert(o),
          $("#qty_item_" + t).val("0"),
          $("#total_qntt_" + t).val("0"),
          $("#total_price_" + t).val("0");
      }
    },
  });
}

("use strict");
function stockLimitAjax(t) {
  var a = $("#total_qntt_" + t).val(),
    e = $(".product_id_" + t).val(),
    o = $(".baseUrl").val();

  $.ajax({
    type: "POST",
    url: o + "Cinvoice/product_stock_check",
    data: {
      product_id: e,
    },
    cache: !1,
    success: function (e) {
      alert(e);
      if (a > Number(e)) {
        var o = "You can Sale maximum " + e + " Items";
        alert(o),
          $("#qty_item_" + t).val("0"),
          $("#total_qntt_" + t).val("0"),
          $("#total_price_" + t).val("0.00"),
          calculateSum();
      }
    },
  });
}

//Invoice full paid
("use strict");
function full_paid() {
  var grandTotal = $("#n_total").val();
  $("#paidAmount").val(grandTotal);
  invoice_paidamount();
  calculateSum();
}

//Delete a row of table
("use strict");
function deleteRow(t) {
  var a = $("#addinvoice > tbody > tr").length;
  //    alert(a);
  if (1 == a) alert("There only one row you can't delete.");
  else {
    var e = t.parentNode.parentNode;
    e.parentNode.removeChild(e), calculateSum();
    invoice_paidamount();
    var current = 1;
    $("#addinvoice > tbody > tr td input.productSelection").each(function () {
      current++;
      $(this).attr("id", "product_name" + current);
    });
    var common_qnt = 1;
    $("#addinvoice > tbody > tr td input.common_qnt").each(function () {
      common_qnt++;
      $(this).attr("id", "total_qntt_" + common_qnt);
      $(this).attr("onkeyup", "quantity_calculate(" + common_qnt + ");");
      $(this).attr("onchange", "quantity_calculate(" + common_qnt + ");");
    });
    var common_rate = 1;
    $("#addinvoice > tbody > tr td input.common_rate").each(function () {
      common_rate++;
      $(this).attr("id", "price_item_" + common_rate);
      $(this).attr("onkeyup", "quantity_calculate(" + common_qnt + ");");
      $(this).attr("onchange", "quantity_calculate(" + common_qnt + ");");
    });
    var common_discount = 1;
    $("#addinvoice > tbody > tr td input.common_discount").each(function () {
      common_discount++;
      $(this).attr("id", "discount_" + common_discount);
      $(this).attr("onkeyup", "quantity_calculate(" + common_qnt + ");");
      $(this).attr("onchange", "quantity_calculate(" + common_qnt + ");");
    });
    var common_total_price = 1;
    $("#addinvoice > tbody > tr td input.common_total_price").each(function () {
      common_total_price++;
      $(this).attr("id", "total_price_" + common_total_price);
    });
  }
  var items_p_inv = 0;
  elems = [];

  $(".item_product_invoice").each(function (index) {
    var np = $(this).attr("np");
    var idp = $(this).attr("idp");
    var qty = $(this).val();
    var e = { nombre: np, id: idp, qty: qty };
    elems.push(e);
    items_p_inv++;
  });

  if (items_p_inv > 1) {
    $("#div_deli_multiple").attr("style", "display:inline-block;");
    $("#delim").prop("checked", false);
    //render_pos_inv(0);
  } else {
    $("#delim").prop("checked", false);
    $("#div_deli_multiple").attr("style", "display:none;");
    //render_pos_inv(0);
  }

  /*$(".item_product_invoice").each(function( index ) {					 
	 	var np = $(this).attr('np');
	 	var idp = $(this).attr('idp');
	 	var e = {nombre:np, id:idp};	
	 	elems.push(e);						 
	 	items_p_inv++;						 
 	});
	
	if(items_p_inv> 1){
		$('#div_deli_multiple').attr('style', 'display:inline-block;');
		$('#delim').prop('checked', false);
		render_pos_inv(0);
	}else{
		$('#delim').prop('checked', false);
		$('#div_deli_multiple').attr('style', 'display:none;');
		render_pos_inv(0);
	}
	
	console.log(elems);	*/
}
var count = 2,
  limits = 500;
("use strict");
function customer_autocomplete(sl) {
  var customer_id = $("#customer_id").val();
  // Auto complete
  var options = {
    minLength: 0,
    source: function (request, response) {
      var customer_name = $("#customer_name").val();
      var csrf_test_name = $('[name="csrf_test_name"]').val();
      var base_url = $("#base_url").val();

      $.ajax({
        url: base_url + "invoice/invoice/bdtask_customer_autocomplete",
        method: "post",
        dataType: "json",
        data: {
          term: request.term,
          customer_id: customer_name,
          csrf_test_name: csrf_test_name,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    focus: function (event, ui) {
      $(this).val(ui.item.label);
      return false;
    },
    select: function (event, ui) {
      $(this)
        .parent()
        .parent()
        .find("#autocomplete_customer_id")
        .val(ui.item.value);
      var customer_id = ui.item.value;
      customer_due(customer_id);

      $(this).unbind("change");
      return false;
    },
  };

  $("body").on("keypress.autocomplete", "#customer_name", function () {
    $(this).autocomplete(options);
  });
}

("use strict");
function customer_due(id) {
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "invoice/invoice/previous",
    type: "post",
    data: { customer_id: id, csrf_test_name: csrf_test_name },
    success: function (msg) {
      $("#previous").val(msg);
    },
    error: function (xhr, desc, err) {
      alert("failed");
    },
  });
}

$(document).ready(function () {
  "use strict";

  $("#full_paid_tab").keydown(function (event) {
    if (event.keyCode == 13) {
      //$('#add_invoice').trigger('click');
    }
  });
});

$(document).ready(function () {
  "use strict";

  var frm = $("#gui_sale_insert");

  var frm_update = $("#gui_sale_update");

  var output = $("#output");
  var invoice_no = $("#gui_invoice_no").text();

  var nextinvoice = parseInt(invoice_no) + +1;

  frm.on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: $(this).attr("action"),
      method: $(this).attr("method"),
      dataType: "json",
      data: frm.serialize(),
      success: function (data) {
        if (data.status == true) {
          toastr["success"](data.message);
          $(".quantity").removeClass("quantity");
          $(".product-panel").removeClass("active");
          $(".active_qty").text("");

          swal(
            {
              title: "Success!",
              showCancelButton: true,
              cancelButtonText: "NO",
              cancelButtonColor: "red",
              confirmButtonText: "Si",
              confirmButtonColor: "#008000",
              text: "¿Imprimir ticket ahora?",
              type: "success",
            },
            function (inputValue) {
              if (inputValue === true) {
                render_pos_inv(0);
                $("#addinvoice tbody tr").remove();
                $("#gui_sale_insert").trigger("reset");
                $("#n_total").val("");
                $("#net_total_text").text("0.00");
                $("#dueAmmount").val("");
                $("#due_text").text("0.00");
                $("#invoice_no").val(nextinvoice);
                $("#gui_invoice_no").text(nextinvoice);

                //printRawHtml(data.details);
                if (data.quote) {
                  window.open("quote_details/" + data.invoice_id, "_blank");
                  window.open("quote_details_cs/" + data.invoice_id, "_blank");
                  window.open("quote_details_del/" + data.invoice_id, "_blank");
                } else {
                  window.open("invoice_details/" + data.invoice_id, "_blank");
                  window.open(
                    "invoice_details_cs/" + data.invoice_id,
                    "_blank"
                  );
                  window.open(
                    "invoice_details_del/" + data.invoice_id,
                    "_blank"
                  );
                }

                location.reload();
              } else {
                location.reload();

                render_pos_inv(0);
                $("#addinvoice tbody tr").remove();
                $("#gui_sale_insert").trigger("reset");
                $("#n_total").val("");
                $("#net_total_text").text("0.00");
                $("#dueAmmount").val("");
                $("#due_text").text("0.00");
                $("#invoice_no").val(nextinvoice);
                $("#gui_invoice_no").text(nextinvoice);
                invoice_no_check();
              }
            }
          );

          $("#inv_id").val(data.invoice_id);

          // $('#printconfirmodal').modal('show');
          if (data.status == true && event.keyCode == 13) {
          }
        } else {
          toastr["error"](data.exception);
        }
      },
      error: function (xhr) {
        alert("failed!");
      },
    });
  });

  frm_update.on("submit", function (e) {
    var base_url = document.getElementById("baseurl").value;

    e.preventDefault();
    $.ajax({
      url: $(this).attr("action"),
      method: $(this).attr("method"),
      dataType: "json",
      data: frm_update.serialize(),
      success: function (data) {
        if (data.status == true) {
          toastr["success"](data.message);
        } else {
          toastr["error"](data.exception);
        }
        window.location = base_url + "invoice_list/";
      },
      error: function (xhr) {
        alert("failed!");
      },
    });
  });
});

("use strict");
function onselectimage(id) {
  var items_p_inv = 0;
  elems = [];
  var product_id = id;
  var base_url = $("#base_url").val();
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var exist = $("#SchoolHiddenId_" + product_id).val();
  var qty = $("#total_qntt_" + product_id).val();
  var add_qty = parseInt(qty) + 1;

  if (product_id == exist) {
    $("#total_qntt_" + product_id).val(add_qty);
    quantity_calculate(product_id);
    calculateSum();
    invoice_paidamount();
    image_activation(product_id);
    document.getElementById("add_item").value = "";
    document.getElementById("add_item").focus();
  } else {
    $.ajax({
      type: "post",
      async: false,
      url: base_url + "invoice/invoice/gui_pos_invoice",
      data: { product_id: product_id, csrf_test_name: csrf_test_name },
      success: function (data) {
        if (data == false) {
          alert("This Product Not Found !");
          document.getElementById("add_item").value = "";
          document.getElementById("add_item").focus();
          quantity_calculate(product_id);
          calculateSum();
          invoice_paidamount();
          image_activation(product_id);
        } else {
          document.getElementById("add_item").value = "";
          document.getElementById("add_item").focus();
          $("#addinvoice tbody").append(data);
          quantity_calculate(product_id);
          calculateSum();
          invoice_paidamount();
          image_activation(product_id);
          setTimeout(function () {
            $("#hidden_tr").css("display", "none");
          }, 1000);
        }
      },
      error: function () {
        alert("Request Failed, Please check your code and try again!");
      },
    });
  }

  $(".item_product_invoice").each(function (index) {
    var np = $(this).attr("np");
    var idp = $(this).attr("idp");
    var qty = $(this).val();
    var e = { nombre: np, id: idp, qty: qty };
    elems.push(e);
    items_p_inv++;
  });

  if (items_p_inv > 1) {
    $("#div_deli_multiple").attr("style", "display:inline-block;");
    $("#delim").prop("checked", false);
    render_pos_inv(0);
  } else {
    $("#delim").prop("checked", false);
    $("#div_deli_multiple").attr("style", "display:none;");
    render_pos_inv(0);
  }

  console.log(elems);
}

("use strict");
$("body").on("keyup", "#product_name", function () {
  var product_name = $(this).val();
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var myurl = $("#posurl_productname").val();
  $.ajax({
    type: "post",
    async: false,
    url: myurl,
    data: { product_name: product_name, csrf_test_name: csrf_test_name },
    success: function (data) {
      if (data == "420") {
        $("#product_search").html(
          '<h1 class"srcalrt">Product not found !</h1>'
        );
      } else {
        $("#product_search").html(data);
      }
    },
    error: function () {
      alert("Request Failed, Please check your code and try again!");
    },
  });
});

function getslcategory(category_id) {
  var base_url = $("#base_url").val();
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var product_name = $("#product_name").val();
  var myurl = $("#posurl").val();
  $.ajax({
    type: "post",
    async: false,
    url: myurl,
    data: {
      product_name: product_name,
      category_id: category_id,
      csrf_test_name: csrf_test_name,
    },
    success: function (data) {
      if (data == "420") {
        $("#product_search").html(data);
      } else {
        $("#product_search").html(data);
      }
    },
    error: function () {
      alert("Request Failed, Please check your code and try again!");
    },
  });
}

function check_category(category) {
  if (category != "") {
    $(".product-grid").attr("style", "height: calc(100vh - 167px);");
  } else {
    $(".product-grid").attr("style", "height: 0px;");
  }

  var base_url = $("#base_url").val();
  var csrf_test_name = $("#csrf_test_name").val();
  var myurl = $("#posurl").val();

  console.log(base_url);
  console.log(csrf_test_name);
  console.log(myurl);
  console.log(category);

  $.ajax({
    type: "post",
    async: false,
    url: myurl,
    data: { category_id: category, csrf_test_name: csrf_test_name },
    success: function (data) {
      if (data == "420") {
        $("#product_search").html(data);
      } else {
        $("#product_search").html(data);
      }
    },
    error: function () {
      alert("Request Failed, Please check your code and try again!");
    },
  });
}

function image_activation(id) {
  var qty = $("#total_qntt_" + id).val();
  $("#image-active_" + id).addClass("active");
  $("#image-active_count_" + id).addClass("quantity");
  var active_product = $("#active_pro_" + id).text(qty);
}

("use strict");
function checkallCategory() {
  $("#box-0").change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".cat-check").each(function () {
        $(this).prop("checked", true);
        check_category();
      });
    } else {
      $(".cat-check").each(function () {
        $(this).prop("checked", false);
        check_category();
      });
    }
  });
}

$("body").on("click", "#search_button", function () {
  var product_name = $("#product_name").val();
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var category_id = $("#category_id").val();
  var myurl = $("#posurl").val();
  $.ajax({
    type: "post",
    async: false,
    url: myurl,
    data: {
      product_name: product_name,
      category_id: category_id,
      csrf_test_name: csrf_test_name,
    },
    success: function (data) {
      if (data == "420") {
        $("#product_search").html(
          '<h1 class"srcalrt text-center">Product not found !</h1>'
        );
      } else {
        $("#product_search").html(data);
      }
    },
    error: function () {
      alert("Request Failed, Please check your code and try again!");
    },
  });
});

("use strict");
function detailsmodal(productname, stock, model, unit, price, image) {
  $("#detailsmodal").modal("show");
  var base_url = document.getElementById("baseurl").value;
  document.getElementById("modal_productname").innerHTML = productname;
  document.getElementById("modal_productstock").innerHTML = stock;
  document.getElementById("modal_productmodel").innerHTML = model;
  document.getElementById("modal_productunit").innerHTML = unit;
  document.getElementById("modal_productprice").innerHTML = price;
  document.getElementById("modalimg").innerHTML =
    '<img src="' + image + '" alt="image" style="width:100px; height:60px;" />';
}

$(document).on("click", ".taxbutton", function (e) {
  var $this = $(this);
  var icon = $this.find("i");
  if (icon.hasClass("fa fa-angle-double-up")) {
    $this
      .find("i")
      .removeClass("fa fa-angle-double-up")
      .addClass("fa fa-angle-double-down");
  } else {
    $this
      .find("i")
      .removeClass("fa fa-angle-double-down")
      .addClass("fa fa-angle-double-up");
  }
});

$(document).ready(function () {
  $(".paymentpart").click(function () {
    var $header = $(this);
    var $content = $header.next();
    $content.slideToggle(500, function () {
      $header.html(function () {
        return $content.is(":visible")
          ? "<span  class='btn btn-warning'><i class='fa fa-angle-double-down'></i></span>"
          : "<span  class='btn btn-warning'><i class='fa fa-angle-double-up'></i></span>";
      });
    });
  });
});

("use strict");
function bank_info_show(payment_type) {
  if (payment_type.value == "1") {
    document.getElementById("bank_info_hide").style.display = "none";
  } else {
    document.getElementById("bank_info_hide").style.display = "block";
  }
}

("use strict");
function active_customer(status) {
  if (status == "payment_from_2") {
    document.getElementById("payment_from_2").style.display = "none";
    document.getElementById("payment_from_1").style.display = "block";
    document.getElementById("myRadioButton_2").checked = false;
    document.getElementById("myRadioButton_1").checked = true;
  } else {
    document.getElementById("payment_from_1").style.display = "none";
    document.getElementById("payment_from_2").style.display = "block";
    document.getElementById("myRadioButton_2").checked = false;
    document.getElementById("myRadioButton_1").checked = true;
  }
}

$(document).ready(function () {
  "use strict";
  $("#addinvoice .toggle").on("click", function () {
    $("#addinvoice .hideableRow").toggleClass("hiddenRow");
  });
  $(".bankpayment").css("width", "100%");
});

("use strict");
function bank_paymet(val) {
  if (val == 2) {
    var style = "block";
    document.getElementById("bank_id").setAttribute("required", true);
  } else {
    var style = "none";
    document.getElementById("bank_id").removeAttribute("required");
  }

  document.getElementById("bank_div").style.display = style;
}

/*pos invoice part*/
$(document).ready(function () {
  "use strict";
  var barcodeScannerTimerP;
  var barcodeStringPos = "";
  $("#add_item_m_p").keydown(function (e) {
    if (e.keyCode == 13) {
      var product_id = $(this).val();
      var product_id = $(this).val();
      var exist = $("#SchoolHiddenId_" + product_id).val();
      var qty = $("#total_qntt_" + product_id).val();
      var add_qty = parseInt(qty) + 1;
      var csrf_test_name = $('[name="csrf_test_name"]').val();
      var base_url = $("#base_url").val();
      if (product_id == exist) {
        $("#total_qntt_" + product_id).val(add_qty);
        quantity_calculate(product_id);
        calculateSum();
        invoice_paidamount();
        document.getElementById("add_item_m_p").value = "";
        document.getElementById("add_item_m_p").focus();
      } else {
        $.ajax({
          type: "post",
          async: false,
          url: base_url + "invoice/invoice/insert_pos_invoice",
          data: { product_id: product_id, csrf_test_name: csrf_test_name },
          success: function (data) {
            if (data == false) {
              alert("This Product Not Found !");
              document.getElementById("add_item_m_p").value = "";
              document.getElementById("add_item_m_p").focus();
              quantity_calculate(product_id);
              calculateSum();
              invoice_paidamount();
            } else {
              $("#hidden_tr").css("display", "none");
              document.getElementById("add_item_m_p").value = "";
              document.getElementById("add_item_m_p").focus();
              $("#addinvoice tbody").append(data);
              quantity_calculate(product_id);
              calculateSum();
              invoice_paidamount();
            }
          },
          error: function () {
            alert("Request Failed, Please check your code and try again!");
          },
        });
      }
    }
  });

  // capture barcode scanner input
  $("#add_item_p").on("keypress", function (e) {
    barcodeStringPos = barcodeStringPos + String.fromCharCode(e.charCode);
    clearTimeout(barcodeScannerTimerP);
    barcodeScannerTimerP = setTimeout(function () {
      processBarcodePosinvoice();
    }, 300);
  });

  ("use strict");
  function processBarcodePosinvoice() {
    if (barcodeStringPos != "") {
      var product_id = barcodeStringPos;
      var exist = $("#SchoolHiddenId_" + product_id).val();
      var qty = $("#total_qntt_" + product_id).val();
      var add_qty = parseInt(qty) + 1;
      var csrf_test_name = $('[name="csrf_test_name"]').val();
      var base_url = $("#base_url").val();
      if (product_id == exist) {
        $("#total_qntt_" + product_id).val(add_qty);
        quantity_calculate(product_id);
        calculateSum();
        invoice_paidamount();
        document.getElementById("add_item_p").value = "";
        document.getElementById("add_item_p").focus();
      } else {
        $.ajax({
          type: "post",
          async: false,
          url: base_url + "invoice/invoice/insert_pos_invoice",
          data: { product_id: product_id, csrf_test_name: csrf_test_name },
          success: function (data) {
            if (data == false) {
              alert("This Product Not Found !");
              document.getElementById("add_item_p").value = "";
              document.getElementById("add_item_p").focus();
              quantity_calculate(product_id);
              calculateSum();
              invoice_paidamount();
            } else {
              $("#hidden_tr").css("display", "none");
              document.getElementById("add_item_p").value = "";
              document.getElementById("add_item_p").focus();
              $("#addinvoice tbody").append(data);
              quantity_calculate(product_id);
              calculateSum();
              invoice_paidamount();
            }
          },
          error: function () {
            alert("Request Failed, Please check your code and try again!");
          },
        });
      }
    } else {
      alert("barcode is invalid: " + barcodeStringPos);
    }

    barcodeStringPos = ""; // reset
  }
});

$(document).ready(function () {
  "use strict";
  var frm = $("#pos_sale_insert");

  var output = $("#output");
  frm.on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: $(this).attr("action"),
      method: $(this).attr("method"),
      dataType: "json",
      data: frm.serialize(),
      success: function (data) {
        if (data.status == true) {
          toastr["success"](data.message);
          $("#image-active_count_" + 54258082).removeClass("quantity");
          $("#inv_id").val(data.invoice_id);
          $("#printconfirmodal").modal("show");
          $("#pos_sale_insert").trigger("reset");
          if (data.status == true && event.keyCode == 13) {
          }
        } else {
          toastr["error"](data.exception);
        }
      },
      error: function (xhr) {
        alert("failed!");
      },
    });
  });
});

$("#printconfirmodal").on("keydown", function (e) {
  var key = e.which || e.keyCode;
  if (key == 13) {
    $("#yes").trigger("click");
  }
});

function cancelprint() {
  render_pos_inv(0);
  location.reload();
}

("use strict");
function invoice_productList(sl) {
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var priceClass = "price_item" + sl;
  var available_quantity = "available_quantity_" + sl;
  var unit = "unit_" + sl;
  var tax = "total_tax_" + sl;
  var serial_no = "serial_no_" + sl;
  var discount_type = "discount_type_" + sl;

  // Auto complete
  var options = {
    minLength: 0,
    source: function (request, response) {
      var product_name = $("#product_name_" + sl).val();
      $.ajax({
        url: base_url + "invoice/invoice/bdtask_autocomplete_product",
        method: "post",
        dataType: "json",
        data: {
          term: request.term,
          product_name: product_name,
          csrf_test_name: csrf_test_name,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    focus: function (event, ui) {
      $(this).val(ui.item.label);
      return false;
    },
    select: function (event, ui) {
      $(this)
        .parent()
        .parent()
        .find(".autocomplete_hidden_value")
        .val(ui.item.value);
      $(this).val(ui.item.label);
      var sl = $(this).parent().parent().find(".sl").val();
      var id = ui.item.value;
      var dataString = "product_id=" + id;
      var base_url = $(".baseUrl").val();

      $.ajax({
        type: "POST",
        url: base_url + "invoice/invoice/retrieve_product_data_inv",
        data: { product_id: id, csrf_test_name: csrf_test_name },
        cache: false,
        success: function (data) {
          var obj = jQuery.parseJSON(data);
          for (var i = 0; i < obj.txnmber; i++) {
            var txam = obj.taxdta[i];
            var txclass = "total_tax" + i + "_" + sl;
            $("." + txclass).val(obj.taxdta[i]);
          }
          $("." + priceClass).val(obj.price);
          $("." + available_quantity).val(obj.total_product.toFixed(2, 2));
          $("." + unit).val(obj.unit);
          $("." + tax).val(obj.tax);
          $("#txfieldnum").val(obj.txnmber);
          $("#" + serial_no).html(obj.serial);
          $("#" + discount_type).val(obj.discount_type);

          //This Function Stay on others.js page
          quantity_calculate(sl);
        },
      });

      $(this).unbind("change");
      return false;
    },
  };

  $("body").on("keypress.autocomplete", ".productSelection", function () {
    $(this).autocomplete(options);
  });
}

window.onload = function () {
  $("body").addClass("sidebar-mini sidebar-collapse");
};

$(".product-grid").each(function () {
  const ps = new PerfectScrollbar($(this)[0]);
});

$(document).ready(function () {
  Mousetrap.bind("ctrl+s", function (e) {
    if (e.preventDefault) {
      e.preventDefault();
    } else {
      e.returnValue = false;
    }
    $("#add_invoice").trigger("click");
  });

  Mousetrap.bind("shift+c", function () {
    $("#cust_info").modal();
    setTimeout(function () {
      $("#m_customer_name").focus();
    }, 500);
  });

  Mousetrap.bind("shift+f", function () {
    $("#full_paid_tab").trigger("click");
  });

  Mousetrap.bind("shift+l", function () {
    $("#todays_salelist").trigger("click");
  });
  Mousetrap.bind("shift+n", function () {
    $("#new_sale").trigger("click");
  });

  Mousetrap.bind("alt+c", function () {
    $("#calculator_modal").trigger("click");
  });
  Mousetrap.bind("alt+n", function () {
    $("#customer_name").focus();
  });

  Mousetrap.bind("ctrl+d", function (e) {
    if (e.preventDefault) {
      e.preventDefault();
    } else {
      e.returnValue = false;
    }
    $("#invoice_discount").focus();
  });

  Mousetrap.bind("alt+p", function () {
    $("#paidAmount").focus();
  });

  Mousetrap.bind("alt+s", function () {
    $("#shipping_cost").focus();
  });
});
function printRawHtml(view) {
  printJS({
    printable: view,
    type: "raw-html",
    onPrintDialogClose: printJobComplete(),
  });
}

function printJobComplete() {
  $("#list_categories").val("");
  $(".product-grid").html("");
  $(".product-grid").attr("style", "height: 0px;");
  elems = [];
  var invoice_no = $("#gui_invoice_no").text();
  var nextinvoice = parseInt(invoice_no) + +1;
  $("#addinvoice tbody tr").remove();
  $("#gui_sale_insert").trigger("reset");
  $("#n_total").val("");
  $("#net_total_text").text("0.00");
  $("#dueAmmount").val("");
  $("#due_text").text("0.00");
  invoice_no_check();
  render_pos_inv(0);
}

function invoice_no_check() {
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "invoice/invoice/number_generator_ajax",
    type: "json",
    success: function (msg) {
      $("#gui_invoice_no").text(msg);
      $("#invoice_no").val(msg);
      console.log(msg);
    },
    error: function (xhr, desc, err) {
      alert("failed");
    },
  });
}

$(document).on("click", "#delim", function () {
  if ($("#delim").prop("checked")) {
    render_pos_inv(1);
  } else {
    render_pos_inv(0);
  }
});

$(document).on("click", "#instore", function () {
  if ($("#instore").prop("checked")) {
    $(".instore").attr("style", "display:none;");
    $("#fh_entrega_tienda").attr("style", "display:block;");
  } else {
    $(".instore").attr("style", "display:block;");
    $("#fh_entrega_tienda").attr("style", "display:none;");
  }
});

$(document).on("click", "#cancel", function () {
  if ($("#cancel").prop("checked")) {
    $("#motivo_cancelacion").attr("style", "display:block;");
  } else {
    $("#motivo_cancelacion").attr("style", "display:none;");
  }
});

function render_pos_inv(x) {
  var html_render_entrega = "";
  var html_render_reparto = "";
  var html_render_caja = "";
  var html_render_taller = "";

  var customer_name = document.getElementById("customer_name").value;
  var opts = document.getElementById("opts").value;
  var optfl = document.getElementById("optfl").value;
  var optz = document.getElementById("optz").value;

  var base_url = $("#base_url").val();

  if (x == 1) {
    elems.forEach(function (word) {
      var _details_ = "";

      $.ajax({
        type: "post",
        async: false,
        url: base_url + "insumos_invoice",
        data: { product_id: word.id, cantidad: word.qty },
        success: function (data) {
          _details_ += data;
        },
        error: function () {
          alert("Request Failed, Please check your code and try again!");
        },
      });

      html_render_entrega +=
        '<div class="row" style="border-bottom:1px solid #666;">' +
        '<div class="col-md-7">' +
        '<div class="row">' +
        "<p>Complete los siguientes campos:</p>" +
        '<div class="col-md-2"  style="margin-bottom:5px;">' +
        '<input id="id_arreglo_' +
        word.id +
        '" type="text" class="form-control" name="id_arreglo[]" placeholder="ID Arreglo" value="' +
        word.id +
        '"  readonly>' +
        "</div>	" +
        '<div class="col-md-3"  style="margin-bottom:5px;">' +
        '<input id="arreglo_' +
        word.id +
        '" type="text" class="form-control" name="arreglo[]" placeholder="Nombre del arreglo" value="' +
        word.nombre +
        '" readonly>' +
        "</div>" +
        '<div class="col-md-3" style="margin-bottom:5px;">' +
        '<input type="text" class="form-control fecha_entrega" id="fecha_entrega_' +
        word.id +
        '" name="fecha_entrega[]" placeholder="Fecha de entrega" ida="' +
        word.id +
        '">' +
        '<script>$(function() {$("#fecha_entrega_' +
        word.id +
        '").daterangepicker({timePicker: true,singleDatePicker: true,locale: {format: "YYYY-MM-DD HH:mm", applyLabel: "Guardar",cancelLabel: "Cancelar",daysOfWeek: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre"]}});});</script>' +
        "</div>" +
        '<div class="col-md-4" style="margin-bottom:5px;">' +
        '<select id="hora_entrega_' +
        word.id +
        '" class="hora_entrega" name="hora_entrega[]" style="padding-top:5px;padding-bottom:5px;" ida="' +
        word.id +
        '">' +
        '<option value="6:00 am - 8:00 am">6:00 am - 8:00 am</option>' +
        '<option value="8:00 am - 10:00 am">8:00 am - 10:00 am</option>' +
        '<option value="10:00 am - 12:00 pm">10:00 am - 12:00 pm</option>' +
        '<option value="12:00 pm - 3:00 pm">12:00 pm - 3:00 pm</option>' +
        '<option value="3:00 pm - 6:00 pm">3:00 pm - 6:00 pm</option>' +
        '<option value="6:00 pm - 9:00 pm">6:00 pm - 9:00 pm</option>' +
        "</select>" +
        "</div>" +
        '<div class="col-md-12"  style="margin-bottom:5px;">' +
        '<input id="destinatario_' +
        word.id +
        '" type="text" class="form-control destinatario" name="destinatario[]" placeholder="Nombre del destinatario" ida="' +
        word.id +
        '">' +
        "</div>" +
        '<div class="col-md-12"  style="margin-bottom:5px;">' +
        '<input type="text" class="form-control autocomplete direccion" id="direccion_' +
        word.id +
        '" name="direccion[]" placeholder="Dirección" ida="' +
        word.id +
        '">' +
        '<input type="hidden" id="lat_' +
        word.id +
        '" name="lat[]">' +
        '<input type="hidden" id="lon_' +
        word.id +
        '" name="lon[]">' +
        '<input type="hidden" id="radius_' +
        word.id +
        '" name="radius[]">' +
        "</div>" +
        '<div class="col-md-8" style="margin-bottom:5px;">' +
        '<input id="direccion2_' +
        word.id +
        '" type="text" class="form-control direccion2" name="direccion2[]" placeholder="Dirección 2" ida="' +
        word.id +
        '">' +
        "</div>" +
        '<div class="col-md-4"  style="margin-bottom:5px;">' +
        '<select id="zona_' +
        word.id +
        '" name="zona[]" style="padding-top:5px;padding-bottom:5px;">' +
        optz +
        "</select>" +
        "</div>" +
        '<div class="col-md-12" style="margin-bottom:5px;">' +
        '<input id="descripcion_entrega_' +
        word.id +
        '" type="text" class="form-control descripcion_entrega" name="descripcion_entrega[]" placeholder="Anotaciones de entrega/Referencias" ida="' +
        word.id +
        '">' +
        "</div>" +
        '<div class="col-md-12" style="margin-bottom:5px;">' +
        '<input id="telefono_' +
        word.id +
        '" type="text" class="form-control phone" name="telefono[]" placeholder="Teléfono">' +
        "</div>" +
        "<p>Datos del cliente:</p>" +
        '<div class="col-md-6"  style="margin-bottom:5px;">' +
        '<input id="nombre_cliente_' +
        word.id +
        '" type="text" class="form-control" name="nombre_cliente[]" placeholder="Nombre del cliente">' +
        "</div>" +
        '<div class="col-md-6" style="margin-bottom:5px;">' +
        '<input id="telefono_cliente_' +
        word.id +
        '" type="text" class="form-control phone" name="telefono_cliente[]" placeholder="Teléfono de cliente">' +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="col-md-5">' +
        '<div id="us2_' +
        word.id +
        '" style="width:100%; height:220px;"></div>' +
        "</div>" +
        '<script>$("#us2_' +
        word.id +
        '").locationpicker({location: {latitude: 17.067100,longitude: -96.720410},radius: 300,inputBinding: {latitudeInput: $("#lat_' +
        word.id +
        '"),longitudeInput: $("#lon_' +
        word.id +
        '"),radiusInput: $("#radius_' +
        word.id +
        '"),locationNameInput: $("#direccion_' +
        word.id +
        '")},enableAutocomplete: true});$(".phone").mask("(000) 000-0000");</script>' +
        "</div>";

      html_render_reparto +=
        '<div class="row" style="border-bottom:1px solid #666;">' +
        '<div class="col-md-3"  style="margin-bottom:5px;">' +
        '<label class="form-label">ID Arreglo</label>' +
        '<input type="text" class="form-control" name="id_arreglo_reparto[]" placeholder="ID Arreglo" value="' +
        word.id +
        '"  readonly>' +
        "</div>	" +
        '<div class="col-md-3"  style="margin-bottom:5px;">' +
        '<label class="form-label">Nombre Arreglo</label>' +
        '<input type="text" class="form-control" name="arreglo_reparto[]" placeholder="Nombre del arreglo" value="' +
        word.nombre +
        '" readonly>' +
        "</div>" +
        '<div class="col-md-6"  style="margin-bottom:5px;">' +
        '<label for="destinatario_reparto_' +
        word.id +
        '" class="form-label">Nombre del destinatario</label>' +
        '<input type="text" class="form-control" id="destinatario_reparto_' +
        word.id +
        '" name="destinatario_reparto[]" placeholder="Nombre del destinatario" readonly>' +
        "</div>" +
        '<div class="col-md-6"  style="margin-bottom:5px;">' +
        '<label for="direccion_reparto_' +
        word.id +
        '" class="form-label">Dirección de entrega</label>' +
        '<input type="text" class="form-control autocomplete" id="direccion_reparto_' +
        word.id +
        '" name="direccion_reparto[]" placeholder="Dirección" readonly>' +
        "</div>" +
        '<div class="col-md-3" style="margin-bottom:5px;">' +
        '<label for="fecha_entrega_reparto_' +
        word.id +
        '" class="form-label">Fecha de entrega</label>' +
        '<input type="text" class="form-control" id="fecha_entrega_reparto_' +
        word.id +
        '" name="fecha_entrega_reparto[]" placeholder="Fecha de entrega" readonly>' +
        "</div>" +
        '<div class="col-md-3" style="margin-bottom:5px;">' +
        '<label for="hora_entrega_reparto_' +
        word.id +
        '" class="form-label">Hora de entrega</label>' +
        '<input type="text" class="form-control" id="hora_entrega_reparto_' +
        word.id +
        '" name="hora_entrega_reparto[]" readonly  value="6:00 am - 8:00 am">' +
        "</div>" +
        '<div class="col-md-12" style="margin-bottom:5px;">' +
        '<label for="descripcion_entrega_reparto_' +
        word.id +
        '" class="form-label">Anotaciones de entrega/Referencias</label>' +
        '<input type="text" class="form-control" id="descripcion_entrega_reparto_' +
        word.id +
        '" name="descripcion_entrega_reparto[]" placeholder="Anotaciones de entrega/Referencias" readonly>' +
        "</div>" +
        "</div>";

      html_render_caja +=
        '<div class="row" style="border-bottom:1px solid #666;">' +
        '<div class="col-md-3"  style="margin-bottom:5px;">' +
        '<label class="form-label">ID Arreglo</label>' +
        '<input type="text" class="form-control" name="id_arreglo_caja[]" placeholder="ID Arreglo" value="' +
        word.id +
        '"  readonly>' +
        "</div>	" +
        '<div class="col-md-3"  style="margin-bottom:5px;">' +
        '<label class="form-label">Nombre Arreglo</label>' +
        '<input type="text" class="form-control" name="arreglo_caja[]" placeholder="Nombre del arreglo" value="' +
        word.nombre +
        '" readonly>' +
        "</div>" +
        '<div class="col-md-6"  style="margin-bottom:5px;">' +
        '<label for="cliente_caja_' +
        word.id +
        '" class="form-label">Nombre del cliente</label>' +
        '<input type="text" class="form-control customer_name" id="cliente_caja_' +
        word.id +
        '" name="cliente_caja[]" placeholder="Nombre del cliente" readonly value="' +
        customer_name +
        '">' +
        "</div>" +
        '<div class="col-md-3" style="margin-bottom:5px;">' +
        '<label for="fecha_entrega_caja_' +
        word.id +
        '" class="form-label">Fecha de entrega</label>' +
        '<input type="text" class="form-control" id="fecha_entrega_caja_' +
        word.id +
        '" name="fecha_entrega_caja[]" placeholder="Fecha de entrega" readonly>' +
        "</div>" +
        '<div class="col-md-3" style="margin-bottom:5px;">' +
        '<label for="hora_entrega_caja_' +
        word.id +
        '" class="form-label">Hora de entrega</label>' +
        '<input type="text" class="form-control" id="hora_entrega_caja_' +
        word.id +
        '" name="hora_entrega_caja[]"  readonly  value="6:00 am - 8:00 am">' +
        "</div>" +
        '<div class="col-md-6"  style="margin-bottom:5px;">' +
        '<label for="destinatario_caja_' +
        word.id +
        '" class="form-label">Nombre del destinatario</label>' +
        '<input type="text" class="form-control" id="destinatario_caja_' +
        word.id +
        '" name="destinatario_caja[]" placeholder="Nombre del destinatario" readonly>' +
        "</div>" +
        '<div class="col-md-6"  style="margin-bottom:5px;">' +
        '<label for="direccion_caja_' +
        word.id +
        '" class="form-label">Dirección de entrega</label>' +
        '<input type="text" class="form-control autocomplete" id="direccion_caja_' +
        word.id +
        '" name="direccion_caja[]" placeholder="Dirección" readonly>' +
        "</div>" +
        '<div class="col-md-6" style="margin-bottom:5px;">' +
        '<label for="repartidor_caja_' +
        word.id +
        '" class="form-label">Repartidor:</label><br>' +
        '<select style="padding-top:5px;padding-bottom:5px;" id="repartidor_caja_' +
        word.id +
        '" name="repartidor_caja[]" >' +
        opts +
        "</select>" +
        "</div>" +
        '<div class="col-md-12" style="margin-bottom:5px;">' +
        '<label for="descripcion_entrega_caja_' +
        word.id +
        '" class="form-label">Anotaciones de entrega/Referencias</label>' +
        '<input type="text" class="form-control" id="descripcion_entrega_caja_' +
        word.id +
        '" name="descripcion_entrega_caja[]" placeholder="Anotaciones de entrega/Referencias" readonly>' +
        "</div>" +
        "</div>";

      html_render_taller +=
        '<div class="row" style="border-bottom:1px solid #666;">' +
        '<div class="col-md-3"  style="margin-bottom:5px;">' +
        '<label class="form-label">ID Arreglo</label>' +
        '<input type="text" class="form-control" name="id_arreglo_taller[]" placeholder="ID Arreglo" value="' +
        word.id +
        '"  readonly>' +
        "</div>	" +
        '<div class="col-md-3"  style="margin-bottom:5px;">' +
        '<label class="form-label">Nombre Arreglo</label>' +
        '<input type="text" class="form-control" name="arreglo_taller[]" placeholder="Nombre del arreglo" value="' +
        word.nombre +
        '" readonly>' +
        "</div>" +
        '<div class="col-md-3" style="margin-bottom:5px;">' +
        '<label for="fecha_entrega_taller_' +
        word.id +
        '" class="form-label">Fecha de entrega</label>' +
        '<input type="text" class="form-control" id="fecha_entrega_taller_' +
        word.id +
        '" name="fecha_entrega_taller[]" placeholder="Fecha de entrega"  readonly>' +
        "</div>" +
        '<div class="col-md-3" style="margin-bottom:5px;">' +
        '<label for="hora_entrega_taller_' +
        word.id +
        '" class="form-label">Hora de entrega</label>' +
        '<input type="text" class="form-control" id="hora_entrega_taller_' +
        word.id +
        '" name="hora_entrega_taller[]"  readonly  value="6:00 am - 8:00 am">' +
        "</div>" +
        '<div class="col-md-12"  style="margin-bottom:5px;">' +
        '<label for="florista_taller_' +
        word.id +
        '" class="form-label">Florísta</label><br>' +
        '<select style="padding-top:5px;padding-bottom:5px;" id="florista_taller_' +
        word.id +
        '" name="florista_taller[]" placeholder="Florísta">' +
        optfl +
        "</select>" +
        "</div>" +
        "<p>Detalles de materiales</p>" +
        '<div class="col-md-12"  style="margin-bottom:5px;">' +
        '<table id="insumos_arreglo_' +
        word.id +
        '"class="table"><thead><th>Producto</th><th>Cantidad</th><!--<th>Precio</th><th>Total</th>--></thead><tbody>' +
        _details_ +
        "</tbody></table>" +
        "</div>" +
        '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
        '<label for="cantidad_modelo_' +
        word.id +
        '" class="form-label">Cantidad</label>' +
        '<input type="text" class="form-control" id="cantidad_modelo_' +
        word.id +
        '" name="cantidad_modelo[]" placeholder="Cantidad">' +
        "</div>" +
        '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
        '<label for="modelo_' +
        word.id +
        '" class="form-label">Modelo</label>' +
        '<input type="text" class="form-control" id="modelo_' +
        word.id +
        '" name="modelo[]" placeholder="Modelo">' +
        "</div>" +
        '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
        '<label for="cantidad_bases_' +
        word.id +
        '" class="form-label">Cantidad</label>' +
        '<input type="text" class="form-control" id="cantidad_bases_' +
        word.id +
        '" name="cantidad_bases[]" placeholder="Cantidad">' +
        "</div>" +
        '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
        '<label for="bases_' +
        word.id +
        '" class="form-label">Bases</label>' +
        '<input type="text" class="form-control" id="bases_' +
        word.id +
        '" name="bases[]" placeholder="Bases">' +
        "</div>" +
        '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
        '<label for="cantidad_flores_' +
        word.id +
        '" class="form-label">Cantidad</label>' +
        '<input type="text" class="form-control" id="cantidad_flores_' +
        word.id +
        '" name="cantidad_flores[]" placeholder="Cantidad">' +
        "</div>" +
        '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
        '<label for="flores_' +
        word.id +
        '" class="form-label">Flores</label>' +
        '<input type="text" class="form-control" id="flores_' +
        word.id +
        '" name="flores[]" placeholder="Flores">' +
        "</div>" +
        '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
        '<label for="cantidad_otros_' +
        word.id +
        '" class="form-label">Cantidad</label>' +
        '<input type="text" class="form-control" id="cantidad_otros_' +
        word.id +
        '" name="cantidad_otros[]" placeholder="Cantidad">' +
        "</div>" +
        '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
        '<label for="otros_' +
        word.id +
        '" class="form-label">Otros</label>' +
        '<input type="text" class="form-control" id="otros_' +
        word.id +
        '" name="otros[]" placeholder="Otros">' +
        "</div>" +
        "<p>Indicaciones de mensaje personalizado:</p>" +
        '<div class="col-md-12"  style="margin-bottom:5px;">' +
        '<textarea class="form-control" id="mensaje_' +
        word.id +
        '" name="mensaje[]" rows="3" cols="10"></textarea>' +
        "</div>" +
        "</div>";
    });

    $("#render_entrega").empty();
    $("#render_entrega").append(html_render_entrega);

    $("#render_reparto").empty();
    $("#render_reparto").append(html_render_reparto);

    $("#render_caja").empty();
    $("#render_caja").append(html_render_caja);

    $("#render_taller").empty();
    $("#render_taller").append(html_render_taller);
  } else if (x == 0) {
    var _details_ = "";
    elems.forEach(function (word) {
      $.ajax({
        type: "post",
        async: false,
        url: base_url + "insumos_invoice",
        data: { product_id: word.id, cantidad: word.qty },
        success: function (data) {
          _details_ += data;
        },
        error: function () {
          alert("Request Failed, Please check your code and try again!");
        },
      });
    });

    var html_render_entrega =
      '<div class="row">' +
      '<div class="col-md-7">' +
      '<div class="row" id="test">' +
      '<div class="instore">' +
      "<p>Complete los siguientes campos:</p>" +
      '<div class="col-md-5"  style="margin-bottom:5px;">' +
      '<input type="text" class="form-control" id="destinatario" name="destinatario" placeholder="Nombre del destinatario">' +
      "</div>" +
      '<div class="col-md-3" style="margin-bottom:5px;">' +
      '<input type="text" class="form-control" id="fecha_entrega" name="fecha_entrega" placeholder="Fecha de entrega">' +
      '<script>$(function() {$("#fecha_entrega").daterangepicker({timePicker: false,singleDatePicker: true,locale: {format: "YYYY-MM-DD", applyLabel: "Guardar",cancelLabel: "Cancelar",daysOfWeek: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre"]}});});</script>' +
      "</div>" +
      '<div class="col-md-4" style="margin-bottom:5px;">' +
      '<select id="hora_entrega" name="hora_entrega" style="padding-top:5px;padding-bottom:5px;">' +
      '<option value="6:00 am - 8:00 am">6:00 am - 8:00 am</option>' +
      '<option value="8:00 am - 10:00 am">8:00 am - 10:00 am</option>' +
      '<option value="10:00 am - 12:00 pm">10:00 am - 12:00 pm</option>' +
      '<option value="12:00 pm - 3:00 pm">12:00 pm - 3:00 pm</option>' +
      '<option value="3:00 pm - 6:00 pm">3:00 pm - 6:00 pm</option>' +
      '<option value="6:00 pm - 9:00 pm">6:00 pm - 9:00 pm</option>' +
      "</select>" +
      "</div>" +
      '<div class="col-md-12"  style="margin-bottom:5px;">' +
      '<input type="text" class="form-control autocomplete" id="direccion" name="direccion" placeholder="Dirección">' +
      '<input type="hidden" id="lat" name="lat">' +
      '<input type="hidden" id="lon" name="lon">' +
      '<input type="hidden" id="radius" name="radius">' +
      "</div>" +
      '<div class="col-md-12" style="margin-bottom:5px;">' +
      '<input type="text" class="form-control" name="direccion2" id="direccion2" placeholder="Dirección 2">' +
      "</div>" +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<select id="zona" name="zona" style="width: 100%;padding-top:5px;padding-bottom:5px;">' +
      optz +
      "</select>" +
      "</div>" +
      '<div class="col-md-6" style="margin-bottom:5px;">' +
      '<select id="subzone" name="subzone" disabled="" style="width: 100%;padding-top:5px;padding-bottom:5px;">' +
      '<option value="" selected="" disabled="">Seleccione una subzona</option>' +
      "</select>" +
      "</div>" +
      '<div class="col-md-12" style="margin-bottom:5px;">' +
      '<input type="text" class="form-control" id="descripcion_entrega" name="descripcion_entrega" placeholder="Anotaciones de entrega/Referencias">' +
      "</div>" +
      '<div class="col-md-12" style="margin-bottom:5px;">' +
      '<input type="text" class="form-control phone" id="telefono" name="telefono" placeholder="Teléfono">' +
      "</div>" +
      "</div>" +
      "<p>Datos del cliente:</p>" +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" placeholder="Nombre del cliente">' +
      "</div>" +
      '<div class="col-md-6" style="margin-bottom:5px;">' +
      '<input type="text" class="form-control phone" id="telefono_cliente" name="telefono_cliente" placeholder="Teléfono de cliente">' +
      "</div>" +
      '<div id="fh_entrega_tienda" style="display:none;">' +
      "<p>Fecha y hora de entrega en tienda:</p>" +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<input type="text" class="form-control" id="dh_instore" name="dh_instore">' +
      '<script>$(function() {$("#dh_instore").daterangepicker({timePicker: true,singleDatePicker: true,locale: {format: "YYYY-MM-DD hh:mm A", applyLabel: "Guardar", cancelLabel: "Cancelar", daysOfWeek: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"], monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre"]}});});</script>' +
      "</div>" +
      "</div>" +
      "</div>" +
      "</div>" +
      '<div class="col-md-5 instore">' +
      '<div id="us2" style="width:100%; height:220px;"></div>' +
      "</div>" +
      '<script>$("#us2").locationpicker({location: {latitude: 17.067100,longitude: -96.720410},radius: 300,inputBinding: {latitudeInput: $("#lat"),longitudeInput: $("#lon"),radiusInput: $("#radius"),locationNameInput: $("#direccion")},enableAutocomplete: true});$(".phone").mask("(000) 000-0000");</script>' +
      "</div>";

    $("#render_entrega").empty();
    $("#render_entrega").append(html_render_entrega);

    html_render_reparto =
      '<div class="row" style="border-bottom:1px solid #666;">' +
      '<div class="col-md-5"  style="margin-bottom:5px;">' +
      '<label for="destinatario_reparto" class="form-label">Nombre del destinatario</label>' +
      '<input type="text" class="form-control" id="destinatario_reparto" name="destinatario_reparto" placeholder="Nombre del destinatario" readonly>' +
      "</div>" +
      '<div class="col-md-3" style="margin-bottom:5px;">' +
      '<label for="fecha_entrega_reparto" class="form-label">Fecha de entrega</label>' +
      '<input type="text" class="form-control" id="fecha_entrega_reparto" name="fecha_entrega_reparto" placeholder="Fecha de entrega" readonly>' +
      "</div>" +
      '<div class="col-md-4" style="margin-bottom:5px;">' +
      '<label for="hora_entrega_reparto" class="form-label">Hora de entrega</label>' +
      '<input type="text" class="form-control" id="hora_entrega_reparto" name="hora_entrega_reparto" readonly  value="6:00 am - 8:00 am">' +
      "</div>" +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<label for="direccion_reparto" class="form-label">Dirección de entrega</label>' +
      '<input type="text" class="form-control autocomplete" id="direccion_reparto" name="direccion_reparto" placeholder="Dirección" readonly>' +
      "</div>" +
      '<div class="col-md-6" style="margin-bottom:5px;">' +
      '<label for="descripcion_entrega_reparto" class="form-label">Anotaciones de entrega/Referencias</label>' +
      '<input type="text" class="form-control" id="descripcion_entrega_reparto" name="descripcion_entrega_reparto" placeholder="Anotaciones de entrega/Referencias" readonly>' +
      "</div>" +
      "</div>";
    $("#render_reparto").empty();
    $("#render_reparto").append(html_render_reparto);

    html_render_caja =
      '<div class="row" style="border-bottom:1px solid #666;">' +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<label for="cliente_caja" class="form-label">Nombre del cliente</label>' +
      '<input type="text" class="form-control customer_name" id="cliente_caja" name="cliente_caja" placeholder="Nombre del cliente" readonly value="' +
      customer_name +
      '">' +
      "</div>" +
      '<div class="instore">' +
      '<div class="col-md-3" style="margin-bottom:5px;">' +
      '<label for="fecha_entrega_caja" class="form-label">Fecha de entrega</label>' +
      '<input type="text" class="form-control" id="fecha_entrega_caja" name="fecha_entrega_caja" placeholder="Fecha de entrega" readonly>' +
      "</div>" +
      '<div class="col-md-3" style="margin-bottom:5px;">' +
      '<label for="hora_entrega_caja" class="form-label">Hora de entrega</label>' +
      '<input type="text" class="form-control" id="hora_entrega_caja" name="hora_entrega_caja"  readonly  value="6:00 am - 8:00 am">' +
      "</div>" +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<label for="destinatario_caja" class="form-label">Nombre del destinatario</label>' +
      '<input type="text" class="form-control" id="destinatario_caja" name="destinatario_caja" placeholder="Nombre del destinatario" readonly>' +
      "</div>" +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<label for="direccion_caja" class="form-label">Dirección de entrega</label>' +
      '<input type="text" class="form-control autocomplete" id="direccion_caja" name="direccion_caja" placeholder="Dirección" readonly>' +
      "</div>" +
      '<div class="col-md-6" style="margin-bottom:5px;">' +
      '<label for="descripcion_entrega_caja" class="form-label">Anotaciones de entrega/Referencias</label>' +
      '<input type="text" class="form-control" id="descripcion_entrega_caja" name="descripcion_entrega_caja" placeholder="Anotaciones de entrega/Referencias" readonly>' +
      "</div>" +
      '<div class="col-md-6" style="margin-bottom:5px;">' +
      '<label for="repartidor_caja" class="form-label">Repartidor:</label><br>' +
      '<select style="padding-top:5px;padding-bottom:5px;" id="repartidor_caja" name="repartidor_caja">' +
      opts +
      "</select>" +
      "</div>" +
      "</div>" +
      "</div>";

    $("#render_caja").empty();
    $("#render_caja").append(html_render_caja);

    html_render_taller =
      '<div class="row" style="border-bottom:1px solid #666;">' +
      '<div class="col-md-3 instore" style="margin-bottom:5px;">' +
      '<label for="fecha_entrega_taller" class="form-label">Fecha de entrega</label>' +
      '<input type="text" class="form-control" id="fecha_entrega_taller" name="fecha_entrega_taller" placeholder="Fecha de entrega"  readonly>' +
      "</div>" +
      '<div class="col-md-3 instore" style="margin-bottom:5px;">' +
      '<label for="hora_entrega_taller" class="form-label">Hora de entrega</label>' +
      '<input type="text" class="form-control" id="hora_entrega_taller" name="hora_entrega_taller"  readonly  value="6:00 am - 8:00 am">' +
      "</div>" +
      '<div class="col-md-6"  style="margin-bottom:5px;">' +
      '<label for="florista_taller" class="form-label">Florísta</label><br>' +
      '<select style="padding-top:5px;padding-bottom:5px;" id="florista_taller" name="florista_taller" placeholder="Florísta">' +
      optfl +
      "</select>" +
      "</div>" +
      "<p>Indicaciones de mensaje personalizado:</p>" +
      '<div class="col-md-12"  style="margin-bottom:5px;">' +
      '<textarea class="form-control" id="mensaje" name="mensaje" rows="3" cols="10"></textarea>' +
      "</div>" +
      '<div class="col-md-12"  style="margin-bottom:5px;">' +
      "<p>Detalles de materiales</p>" +
      '<table class="table"><thead><th>Producto</th><th>Cantidad</th><!--<th>Precio</th><th>Total</th>--></thead><tbody>' +
      _details_;
    "</tbody></table>" +
      "</div>" +
      '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
      '<label for="cantidad_modelo" class="form-label">Cantidad</label>' +
      '<input type="text" class="form-control" id="cantidad_modelo" name="cantidad_modelo" placeholder="Cantidad">' +
      "</div>" +
      '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
      '<label for="modelo" class="form-label">Modelo</label>' +
      '<input type="text" class="form-control" id="modelo" name="modelo" placeholder="Modelo">' +
      "</div>" +
      '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
      '<label for="cantidad_bases" class="form-label">Cantidad</label>' +
      '<input type="text" class="form-control" id="cantidad_bases" name="cantidad_bases" placeholder="Cantidad">' +
      "</div>" +
      '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
      '<label for="bases" class="form-label">Bases</label>' +
      '<input type="text" class="form-control" id="bases" name="bases" placeholder="Bases">' +
      "</div>" +
      '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
      '<label for="cantidad_flores" class="form-label">Cantidad</label>' +
      '<input type="text" class="form-control" id="cantidad_flores" name="cantidad_flores" placeholder="Cantidad">' +
      "</div>" +
      '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
      '<label for="flores" class="form-label">Flores</label>' +
      '<input type="text" class="form-control" id="flores" name="flores" placeholder="Flores">' +
      "</div>" +
      '<div class="col-md-3"  style="margin-bottom:5px; display:none;">' +
      '<label for="cantidad_otros" class="form-label">Cantidad</label>' +
      '<input type="text" class="form-control" id="cantidad_otros" name="cantidad_otros" placeholder="Cantidad">' +
      "</div>" +
      '<div class="col-md-9"  style="margin-bottom:5px; display:none;">' +
      '<label for="otros" class="form-label">Otros</label>' +
      '<input type="text" class="form-control" id="otros" name="otros" placeholder="Otros">' +
      "</div>" +
      "</div>";

    $("#render_taller").empty();
    $("#render_taller").append(html_render_taller);
  }
}

$(document).on("change", "#customer_name", function () {
  var customer_name = $(this).val();
  $(".customer_name").val(customer_name);
});

$(document).ready(function () {
  var customer_name = $("#customer_name").val();
  $(".customer_name").val(customer_name);
});

$(document).on("keyup", "#destinatario", function () {
  var destinatario = $(this).val();
  $("#destinatario_reparto").val(destinatario);
  $("#destinatario_caja").val(destinatario);
});
$(document).on("change", "#destinatario", function () {
  var destinatario = $(this).val();
  $("#destinatario_reparto").val(destinatario);
  $("#destinatario_caja").val(destinatario);
});

$(document).on("change", "#fecha_entrega", function () {
  var fecha_entrega = $(this).val();
  $("#fecha_entrega_reparto").val(fecha_entrega);
  $("#fecha_entrega_caja").val(fecha_entrega);
  $("#fecha_entrega_taller").val(fecha_entrega);
});

$(document).on("change", "#hora_entrega", function () {
  var fecha_entrega = $(this).val();
  $("#hora_entrega_reparto").val(fecha_entrega);
  $("#hora_entrega_caja").val(fecha_entrega);
  $("#hora_entrega_taller").val(fecha_entrega);
});

$(document).on("keyup", "#direccion", function () {
  var direccion = $(this).val();
  $("#direccion_reparto").val(direccion);
  $("#direccion_caja").val(direccion);
});
$(document).on("change", "#direccion", function () {
  var direccion = $(this).val();
  $("#direccion_reparto").val(direccion);
  $("#direccion_caja").val(direccion);
});

$(document).on("keyup", "#descripcion_entrega", function () {
  var descripcion_entrega = $(this).val();
  $("#descripcion_entrega_reparto").val(descripcion_entrega);
  $("#descripcion_entrega_caja").val(descripcion_entrega);
});

$(document).on("change", "#descripcion_entrega", function () {
  var descripcion_entrega = $(this).val();
  $("#descripcion_entrega_reparto").val(descripcion_entrega);
  $("#descripcion_entrega_caja").val(descripcion_entrega);
});

/**/

$(document).on("change", ".fecha_entrega", function () {
  var fecha_entrega = $(this).val();
  var ida = $(this).attr("ida");
  $("#fecha_entrega_reparto_" + ida).val(fecha_entrega);
  $("#fecha_entrega_caja_" + ida).val(fecha_entrega);
  $("#fecha_entrega_taller_" + ida).val(fecha_entrega);
});

$(document).on("change", ".hora_entrega", function () {
  var hora_entrega = $(this).val();
  var ida = $(this).attr("ida");
  $("#hora_entrega_reparto_" + ida).val(hora_entrega);
  $("#hora_entrega_caja_" + ida).val(hora_entrega);
  $("#hora_entrega_taller_" + ida).val(hora_entrega);
});

$(document).on("keyup", ".destinatario", function () {
  var destinatario = $(this).val();
  var ida = $(this).attr("ida");
  $("#destinatario_reparto_" + ida).val(destinatario);
  $("#destinatario_caja_" + ida).val(destinatario);
});

$(document).on("keyup", ".direccion", function () {
  var direccion = $(this).val();
  var ida = $(this).attr("ida");
  $("#direccion_reparto_" + ida).val(direccion);
  $("#direccion_caja_" + ida).val(direccion);
});

$(document).on("keyup", ".descripcion_entrega", function () {
  var descripcion_entrega = $(this).val();
  var ida = $(this).attr("ida");
  $("#descripcion_entrega_reparto_" + ida).val(descripcion_entrega);
  $("#descripcion_entrega_caja_" + ida).val(descripcion_entrega);
});

$(document).on("change", ".destinatario", function () {
  var destinatario = $(this).val();
  var ida = $(this).attr("ida");
  $("#destinatario_reparto_" + ida).val(destinatario);
  $("#destinatario_caja_" + ida).val(destinatario);
});

$(document).on("change", ".direccion", function () {
  var direccion = $(this).val();
  var ida = $(this).attr("ida");
  $("#direccion_reparto_" + ida).val(direccion);
  $("#direccion_caja_" + ida).val(direccion);
});

$(document).on("change", ".descripcion_entrega", function () {
  var descripcion_entrega = $(this).val();
  var ida = $(this).attr("ida");
  $("#descripcion_entrega_reparto_" + ida).val(descripcion_entrega);
  $("#descripcion_entrega_caja_" + ida).val(descripcion_entrega);
});

//$(document).on('click','.getprodins',function(){
function check_insumo(id_insumo) {
  //var id_insumo = $(this).attr('insumo');
  var base_url = $("#base_url").val();
  $.ajax({
    type: "post",
    url: base_url + "invoice/invoice/bdtask_get_prodins",
    data: { id_insumo: id_insumo },
    success: function (data) {
      $("#product_search").html(data);
    },
    error: function () {
      alert("Request Failed, Please check your code and try again!");
    },
  });
  $("#list_bases").val("");
  $("#list_flores").val("");
  $("#list_categories").val("");
}

function check_customer(customer_id) {
  // Validar que se haya seleccionado un cliente válido
  if (!customer_id || customer_id === "Seleccionar opción") {
    $("#nombre_cliente").val("");
    $("#telefono_cliente").val("");
    $("#invoice_discount").val("");
    return;
  }

  var base_url = $("#base_url").val();

  // Mostrar carga mientras se obtienen los datos
  $("#nombre_cliente").val("Cargando...");
  $("#telefono_cliente").val("Cargando...");
  $("#invoice_discount").val("Cargando...");

  $.ajax({
    type: "POST",
    url: base_url + "invoice/invoice/bdtask_get_customer_data",
    dataType: "json",
    data: { customer_id: customer_id },
    success: function (response) {
      // Llenar los campos con los datos del cliente
      $("#nombre_cliente").val(response.customer_name);
      $("#invoice_discount").val(response.custom_discount);

      // Formatear el teléfono si existe
      if (response.customer_mobile) {
        var phone = response.customer_mobile.toString();
        // Eliminar cualquier formato previo
        phone = phone.replace(/\D/g, "");
        // Aplicar formato (XXX) XXX-XXXX
        if (phone.length === 10) {
          phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
        }
        $("#telefono_cliente").val(phone);
      } else {
        $("#telefono_cliente").val("");
      }

      // Configurar fecha y hora actual por defecto
      var now = new Date();
      var formattedDate =
        now.getFullYear() +
        "-" +
        String(now.getMonth() + 1).padStart(2, "0") +
        "-" +
        String(now.getDate()).padStart(2, "0") +
        " " +
        String(now.getHours()).padStart(2, "0") +
        ":" +
        String(now.getMinutes()).padStart(2, "0");

      $("#dh_instore").val(formattedDate);
    },
    error: function () {
      alert("Error al cargar los datos del cliente");
      $("#nombre_cliente").val("");
      $("#telefono_cliente").val("");
      $("#invoice_discount").val("");
    },
  });
}

function getProductById(product_id) {
  var base_url = $("#base_url").val();
  $.ajax({
    type: "POST",
    method: "POST",
    dataType: "JSON",
    url: base_url + "invoice/invoice/getProductDetailsById",
    data: { product_id: product_id },
    success: function (response) {
      if (!response.error) {
        $("#product_image").attr("src", response.image);
        $("#product_name_card").text(response.name);
      }
    },
    error: function () {
      alert("Request Failed, Please check your code and try again!");
    },
  });
}

//});
