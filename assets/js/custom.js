var count_insumo = $("#count_ip").val();
var count_insumo = parseInt(count_insumo);
var count = 2;

function calcular_precio() {
  var total_ip = 0;
  var p_util = $("#utilidad").val();
  p_util = parseFloat(p_util);
  var utilidad = p_util / 100 + 1;

  var p_iva = $("#iva").val();
  p_iva = parseFloat(p_iva);

  if (isNaN(p_iva)) {
    p_iva = 0;
  }
  var iva = p_iva / 100 + 1;

  console.log(p_iva);
  console.log(iva);

  $(".insumo_total").each(function (index) {
    var tip = parseFloat($(this).val());
    total_ip += tip;
  });

  var precio_final = total_ip * utilidad;

  precio_final = precio_final * iva;

  precio_final = Math.round(precio_final);

  if (!isNaN(precio_final)) {
    $("#sell_price_p").val(precio_final);
  } else {
    $("#sell_price_p").val(0);
  }
}

function addInsumoItem(e) {
  count_insumo++;
  var insumos = $("#insumo_list").val();
  var t =
    '<td width="300"><select name="insumo_id[]" class="form-control insumo_id" serial="' +
    count_insumo +
    '" id="insumo_id_' +
    count_insumo +
    '" required><option value="">Seleccionar insumo</option>' +
    insumos +
    '</select></td><td class=""><input type="text" tabindex="6" class="form-control text-right" name="insumo_price[]" placeholder="0.00"  min="0" readonly  serial="' +
    count_insumo +
    '" id="insumo_price_' +
    count_insumo +
    '" required/></td><td class=""><input type="text" tabindex="6" class="form-control text-right insumo_cantidad" name="insumo_cantidad[]" placeholder="0.00"  min="0"  serial="' +
    count_insumo +
    '" id="insumo_cantidad_' +
    count_insumo +
    '" required/></td><td class=""><input type="text" tabindex="6" class="form-control text-right insumo_total" name="insumo_total[]" placeholder="0.00"  min="0" readonly  serial="' +
    count_insumo +
    '" id="insumo_total_' +
    count_insumo +
    '" required/></td><td><a  id="add_insumo_item" class="btn btn-info btn-sm" name="add_insumo_item" onClick="addInsumoItem(' +
    "'proudt_item'" +
    ')"  tabindex="9"><i class="fa fa-plus-square" aria-hidden="true"></i></a><a class="btn btn-danger btn-sm"  value="" onclick="deleteInsumoRow(this)" tabindex="10"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
  count_insumo == limits
    ? alert("You have reached the limit of adding " + count_insumo + " inputs")
    : $("tbody#insumo_proudt_item").append("<tr>" + t + "</tr>");

  $("select.form-control:not(.dont-select-me)").select2({
    placeholder: "Select option",
    allowClear: true,
  });

  calcular_precio();
}

function deleteInsumoRow(e) {
  var t = $("#insumo_table > tbody > tr").length;
  if (1 == t) alert("There only one row you can't delete.");
  else {
    var a = e.parentNode.parentNode;
    a.parentNode.removeChild(a);
  }

  calcular_precio();
}

$(document).on("keyup", ".insumo_cantidad", function () {
  var serial = $(this).attr("serial");
  var cantidad = $(this).val();
  var precio = $("#insumo_price_" + serial).val();
  var total = parseFloat(cantidad) * parseFloat(precio);
  if (!isNaN(total)) {
    $("#insumo_total_" + serial).val(total);
  } else {
    $("#insumo_total_" + serial).val(0);
  }
  calcular_precio();
});

$(document).on("click", ".delete_inv", function () {
  var invoice = $(this).attr("invoice");
  var base_url = $("#base_url").val();
  swal(
    {
      title: "Eliminar venta",
      showCancelButton: true,
      cancelButtonText: "NO",
      cancelButtonColor: "red",
      confirmButtonText: "Yes",
      confirmButtonColor: "#008000",
      text: "¿Desea eliminar la venta?",
      type: "success",
    },
    function (inputValue) {
      if (inputValue === true) {
        $.ajax({
          type: "POST",
          url: base_url + "invoice/invoice/delete_invoice",
          data: { invoice: invoice },
          cache: false,
          success: function (data) {
            location.reload();
          },
        });
      } else {
      }
    }
  );
});

$(document).on("click", ".get_purchases_insumo", function () {
  var insumo = $(this).attr("insumo");
  var base_url = $("#baseUrl").val();

  $.ajax({
    url: base_url + "report/report/get_purchases_insumo",
    type: "POST",
    data: { insumo: insumo },
  })
    .done(function (response) {
      $("#render_purchases_insumo").html(response);
    })
    .fail(function () {
      console.log("error");
    });
});

$(document).on("keyup", "#utilidad", function () {
  calcular_precio();
});

$(document).on("keyup", "#iva", function () {
  calcular_precio();
});

$(document).on("change", ".insumo_id", function () {
  var serial = $(this).attr("serial");
  var value = $(this).val();
  var base_url = $("#base_url").val();
  if (value != "") {
    $.ajax({
      type: "POST",
      url: base_url + "product/product/bdtask_get_insumo_data",
      data: { insumo: value },
      cache: false,
      success: function (data) {
        obj = JSON.parse(data);
        $("#insumo_price_" + serial).val(obj.price);
      },
    });
  } else {
    $("#insumo_price_" + serial).val(0);
    $("#insumo_cantidad_" + serial).val("");
    $("#insumo_total_" + serial).val(0);
  }

  calcular_precio();
});

/* final codigo producto personalizado*/

$(function ($) {
  "use strict";

  $(document).on("click", ".mover_pagado", function () {
    var invoice = $(this).attr("inv");
    $("#invoice_id_mover").val(invoice);
    $("#modal_pagado").modal("show");
  });

  $(document).on("click", "#mover_a_pagado", function () {
    var base_url = $("#baseUrl").val();
    var invoice = $("#invoice_id_mover").val();
    var tipo = $("#tipo_pago_mover").val();

    $.ajax({
      url: base_url + "invoice/invoice/bdtask_pagado_invoice",
      type: "POST",
      data: { invoice: invoice, tipo: tipo },
    })
      .done(function (response) {
        location.reload();
      })
      .fail(function () {
        console.log("error");
      });
  });

  $(document).on("click", ".entregar", function () {
    var base_url = $("#baseUrl").val();
    var invoice = $(this).attr("invoice");
    var product = $(this).attr("product");

    $.ajax({
      url: base_url + "invoice/invoice/bdtask_entregar_invoice",
      type: "POST",
      data: { invoice: invoice, product: product },
    })
      .done(function (response) {
        location.reload();
      })
      .fail(function () {
        console.log("error");
      });
  });

  $(document).on("click", ".florista", function () {
    var base_url = $("#baseUrl").val();
    var invoice = $(this).attr("invoice");
    var product = $(this).attr("product");

    $.ajax({
      url: base_url + "invoice/invoice/bdtask_florista_invoice",
      type: "POST",
      data: { invoice: invoice, product: product },
    })
      .done(function (response) {
        location.reload();
      })
      .fail(function () {
        console.log("error");
      });
  });

  $(document).on("click", ".edit_customer", function () {
    var nombre_cliente = $(this).attr("nombre_cliente");
    var telefono_cliente = $(this).attr("telefono_cliente");

    $("#nombre_cliente").val(nombre_cliente);
    $("#n_nombre_cliente").val(nombre_cliente);
    $("#telefono_cliente").val(telefono_cliente);
  });

  $(document).ready(function () {
    "use strict";
    var frm_uc = $("#update_customer_data");
    frm_uc.on("submit", function (e) {
      e.preventDefault();
      $.ajax({
        url: $(this).attr("action"),
        method: $(this).attr("method"),
        data: frm_uc.serialize(),
        success: function () {
          location.reload();
        },
        error: function (xhr) {
          alert("failed!");
        },
      });
    });
  });

  //tooltips
  $('[data-toggle="tooltip"]').tooltip();
  //datatable
  $(".datatable").DataTable({
    responsive: true,
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    buttons: [
      { extend: "copy", className: "btn-sm prints" },
      { extend: "csv", title: "ExampleFile", className: "btn-sm prints" },
      {
        extend: "excel",
        title: "ExampleFile",
        className: "btn-sm prints",
        title: "exportTitle",
      },
      { extend: "pdf", title: "ExampleFile", className: "btn-sm prints" },
      { extend: "print", className: "btn-sm prints" },
    ],
  });

  //datatable
  $(".datatable2").DataTable({
    responsive: true,
    paging: false,
    dom: "<'row'<'col-sm-4'B><'col-sm-4'l><'col-sm-4'f>>tp",
    buttons: [
      { extend: "copy", className: "btn-sm prints" },
      { extend: "csv", title: "ExampleFile", className: "btn-sm prints" },
      {
        extend: "excel",
        title: "ExampleFile",
        className: "btn-sm prints",
        title: "exportTitle",
      },
      { extend: "pdf", title: "ExampleFile", className: "btn-sm prints" },
      { extend: "print", className: "btn-sm prints" },
    ],
  });

  //timepicker
  $(".timepicker").timepicker({
    datepicker: false,
    format: "h:i A",
    step: 60,
  });

  //timepicker
  $(".timepicker-hour-min-only").timepicker({
    timeFormat: "HH:mm:00",
    stepHour: 1,
    stepMinute: 5,
  });

  // semantic button
  $(".ui.selection.dropdown").dropdown();
  $(".ui.menu .ui.dropdown").dropdown({
    on: "hover",
  });

  // select 2 dropdown
  $("select.form-control:not(.dont-select-me)").select2({
    placeholder: "Select option",
    allowClear: true,
  });

  var twelveHour = $(".timepicker-12-hr").wickedpicker();
  $(".time").text("//JS Console: " + twelveHour.wickedpicker("time"));
  $(".timepicker-24-hr").wickedpicker({ twentyFour: true });
  $(".timepicker-12-hr-clearable").wickedpicker({ clearable: true });

  //preloader
  $(window).on("load", function () {
    $(".se-pre-con").fadeOut("slow");
  });

  // fixed table head
  $("#fixTable").tableHeadFixer();

  //print a div
  ("use strict");
  function printContent(el) {
    var restorepage = $("body").html();
    var printcontent = $("#" + el).clone();
    $("body").empty().html(printcontent);
    window.print();
    $("body").html(restorepage);
    location.reload();
  }

  //Copy text
  ("use strict");
  function myFunction() {
    var copyText = document.getElementById("copyed");
    copyText.select();
    document.execCommand("Copy");
  }

  ("use strict");
  function myFunction1() {
    var copyText = document.getElementById("copyed1");
    copyText.select();
    document.execCommand("Copy");
  }

  function myFunction2() {
    var copyText = document.getElementById("copyed2");
    copyText.select();
    document.execCommand("Copy");
  }
});

("use strict");
function printDiv(divName) {
  var printContents = document.getElementById(divName).innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  document.body.style.marginTop = "0px";
  window.print();
  document.body.innerHTML = originalContents;
}

/*product part*/

$(document).ready(function () {
  "use strict";
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var total_invoice = $("#total_invoice").val();
  var currency = $("#currency").val();

  var invoicedatatable = $("#InvList").DataTable({
    responsive: true,

    aaSorting: [[0, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5 /*,6*/] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_invoice],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "InvoiceList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "InvoiceList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "Invoice List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "<center> Invoice List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "invoice/invoice/CheckInvoiceList",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      /*{ data: 'sl' },
             { data: 'invoice' },
             { data: 'salesman' },
             { data: 'customer_name'},
             { data: 'final_date' },
             { data: 'total_amount',class:"total_sale text-right",render: $.fn.dataTable.render.number( ',', '.', 2, currency )},
             { data: 'button'},*/

      { data: "invoice" },
      { data: "customer_name" },
      { data: "final_date" },
      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "tipo_pago" },
      { data: "status" },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  var invoicedatatable_pc = $("#InvList_pc").DataTable({
    responsive: true,

    aaSorting: [[0, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5 /*,6*/] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_invoice],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "InvoiceList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "InvoiceList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "Invoice List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "<center> Invoice List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "invoice/invoice/CheckInvoiceListPc",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      /*{ data: 'sl' },
             { data: 'invoice' },
             { data: 'salesman' },
             { data: 'customer_name'},
             { data: 'final_date' },
             { data: 'total_amount',class:"total_sale text-right",render: $.fn.dataTable.render.number( ',', '.', 2, currency )},
             { data: 'button'},*/

      { data: "invoice" },
      { data: "customer_name" },
      { data: "final_date" },
      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "tipo_pago" },
      { data: "status" },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  var invoicedatatable = $("#ArrangementList").DataTable({
    responsive: true,
    aaSorting: [[1, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6] }],
    processing: true,
    serverSide: true,
    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_invoice],
      [10, 25, 50, 100, 250, 500, "All"],
    ],
    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [],
    serverMethod: "post",
    ajax: {
      url: base_url + "invoice/invoice/CheckArrangementList",
      data: function (data) {
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "sl" },
      { data: "factura" },
      { data: "vendedor" },
      { data: "fecha" },
      { data: "recibe" },
      { data: "entrega" },
      { data: "direccion" },
      { data: "repartidor" },
      { data: "tipo_pago" },
      { data: "status" },
      { data: "button" },
    ],
    footerCallback: function (row, data, start, end, display) {},
  });

  var invoicedatatable1 = $("#ArrangementList1").DataTable({
    responsive: true,
    aaSorting: [[1, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6] }],
    processing: true,
    serverSide: true,
    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_invoice],
      [10, 25, 50, 100, 250, 500, "All"],
    ],
    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [],
    serverMethod: "post",
    ajax: {
      url: base_url + "invoice/invoice/CheckArrangementList1",
      data: function (data) {
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "sl" },
      { data: "factura" },
      { data: "vendedor" },
      { data: "fecha" },
      { data: "recibe" },
      { data: "entrega" },
      { data: "direccion" },
      { data: "florista" },
      { data: "tipo_pago" },
      { data: "status" },
      { data: "button" },
    ],
    footerCallback: function (row, data, start, end, display) {},
  });

  /****** acumulado *********/

  var invoicedatatable_efectivo = $("#InvList_efectivo").DataTable({
    responsive: true,
    aaSorting: [[0, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2 /*,3,4,5*/] }],
    processing: true,
    serverSide: true,
    //'lengthMenu':[[10, 25, 50,100,250,500, total_invoice], [10, 25, 50,100,250,500, "All"]],
    serverMethod: "post",
    iDisplayLength: 500,
    ajax: {
      url: base_url + "invoice/invoice/CheckInvoiceList_efectivo",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.seller = $("#seller").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "invoice" },
      //{ data: 'customer_name'},
      { data: "final_date" },
      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      //{ data: 'tipo_pago'},
      //{ data: 'status'},
      //{ data: 'button'},
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  var invoicedatatable_tarjeta = $("#InvList_tarjeta").DataTable({
    responsive: true,
    aaSorting: [[0, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2 /*,3,4,5*/] }],
    processing: true,
    serverSide: true,
    //'lengthMenu':[[10, 25, 50,100,250,500, total_invoice], [10, 25, 50,100,250,500, "All"]],
    serverMethod: "post",
    iDisplayLength: 500,
    ajax: {
      url: base_url + "invoice/invoice/CheckInvoiceList_tarjeta",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.seller = $("#seller").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "invoice" },
      //{ data: 'customer_name'},
      { data: "final_date" },
      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      //{ data: 'tipo_pago'},
      //{ data: 'status'},
      //{ data: 'button'},
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  var invoicedatatable_transferencia = $("#InvList_transferencia").DataTable({
    responsive: true,
    aaSorting: [[0, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2 /*,3,4,5*/] }],
    processing: true,
    serverSide: true,
    //'lengthMenu':[[10, 25, 50,100,250,500, total_invoice], [10, 25, 50,100,250,500, "All"]],
    serverMethod: "post",
    iDisplayLength: 500,
    ajax: {
      url: base_url + "invoice/invoice/CheckInvoiceList_transferencia",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.seller = $("#seller").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "invoice" },
      //{ data: 'customer_name'},
      { data: "final_date" },
      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      //{ data: 'tipo_pago'},
      //{ data: 'status'},
      //{ data: 'button'},
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  var invoicedatatable_anticipo = $("#InvList_anticipo").DataTable({
    responsive: true,
    aaSorting: [[0, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2 /*,3,4,5*/] }],
    processing: true,
    serverSide: true,
    //'lengthMenu':[[10, 25, 50,100,250,500, total_invoice], [10, 25, 50,100,250,500, "All"]],
    serverMethod: "post",
    iDisplayLength: 500,
    ajax: {
      url: base_url + "invoice/invoice/CheckInvoiceList_anticipo",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.seller = $("#seller").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "invoice" },
      //{ data: 'customer_name'},
      { data: "final_date" },
      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      //{ data: 'tipo_pago'},
      //{ data: 'status'},
      //{ data: 'button'},
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  var invoicedatatable_porcobrar = $("#InvList_porcobrar").DataTable({
    responsive: true,
    aaSorting: [[0, "desc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2 /*,3,4,5*/] }],
    processing: true,
    serverSide: true,
    //'lengthMenu':[[10, 25, 50,100,250,500, total_invoice], [10, 25, 50,100,250,500, "All"]],
    serverMethod: "post",
    iDisplayLength: 500,
    ajax: {
      url: base_url + "invoice/invoice/CheckInvoiceList_porcobrar",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.seller = $("#seller").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "invoice" },
      //{ data: 'customer_name'},
      { data: "final_date" },
      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      //{ data: 'tipo_pago'},
      //{ data: 'status'},
      //{ data: 'button'},
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  /****** acumulado *********/

  $("#btn-filter").click(function () {
    invoicedatatable.ajax.reload();
  });

  $("#btn-filter-acumulated").click(function () {
    invoicedatatable_efectivo.ajax.reload();
    invoicedatatable_tarjeta.ajax.reload();
    invoicedatatable_transferencia.ajax.reload();
    invoicedatatable_anticipo.ajax.reload();
    invoicedatatable_porcobrar.ajax.reload();
  });
});

/*CALCULATOR PART*/
var number = "",
  total = 0,
  regexp = /[0-9]/,
  mainScreen = document.getElementById("mainScreen");
("use strict");
function InputSymbol(num) {
  mainScreen = document.getElementById("mainScreen");
  var total = 0;
  var regexp = /[0-9]/;
  var cur = document.getElementById(num).value;
  var prev = number.slice(-1);
  // Do not allow 2 math operators in row
  if (!regexp.test(prev) && !regexp.test(cur)) {
    console.log("Two math operators not allowed after each other ;)");
    return;
  }
  number = number.concat(cur);
  mainScreen.innerHTML = number;
}

("use strict");
function CalculateTotal() {
  // Time for some EVAL magic
  total = Math.round(eval(number) * 100) / 100;
  mainScreen.innerHTML = total;
}

("use strict");
function DeleteLastSymbol() {
  if (number) {
    number = number.slice(0, -1);
    mainScreen.innerHTML = number;
  }
  if (number.length === 0) {
    mainScreen.innerHTML = "0";
  }
}

("use strict");
function ClearScreen() {
  number = "";
  mainScreen.innerHTML = 0;
}

//security page js end
/*stock list js*/
$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var currency = $("#currency").val();
  var total_stock = $("#total_stock").val();

  $("#checkListStockList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6, 7, 8, 9] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_stock],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "report/report/bdtask_checkStocklist",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "product_model", class: "text-center" },
      {
        data: "sales_price",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "purchase_p",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "totalPurchaseQnty", class: "text-right" },
      { data: "totalSalesQnty", class: "text-right" },
      { data: "stok_quantity", class: "stock text-right" },
      {
        data: "total_sale_price",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "purchase_total",
        class: "total_purchase text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".stock", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });
      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
      api
        .columns(".total_purchase", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });

  $("#checkListStockList_insumo").DataTable({
    responsive: true,
    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6] }],
    processing: true,
    serverSide: true,
    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_stock],
      [10, 25, 50, 100, 250, 500, "All"],
    ],
    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
      },
    ],
    serverMethod: "post",
    ajax: {
      url: base_url + "report/report/bdtask_checkStocklist_insumo",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "date" },
      { data: "product_name" },
      { data: "product_model" },
      { data: "sales_price", class: "text-right" },
      { data: "used", class: "text-right" },
      { data: "stok_quantity", class: "text-right" },
      { data: "options", class: "text-center" },
    ],
    footerCallback: function (row, data, start, end, display) {},
  });
});

/*Cash Calculator*/
("use strict");
function cashCalculator() {
  var mul0 = $(".text_0").val();
  var text_0_bal = mul0 * 2000;
  $(".text_0_bal").val(text_0_bal);

  var mul1 = $(".text_1").val();
  var text_1_bal = mul1 * 1000;
  $(".text_1_bal").val(text_1_bal);

  var mul2 = $(".text_2").val();
  var text_2_bal = mul2 * 500;
  $(".text_2_bal").val(text_2_bal);

  var mul3 = $(".text_3").val();
  var text_3_bal = mul3 * 100;
  $(".text_3_bal").val(text_3_bal);

  var mul4 = $(".text_4").val();
  var text_4_bal = mul4 * 50;
  $(".text_4_bal").val(text_4_bal);

  var mul5 = $(".text_5").val();
  var text_5_bal = mul5 * 20;
  $(".text_5_bal").val(text_5_bal);

  var mul6 = $(".text_6").val();
  var text_6_bal = mul6 * 10;
  $(".text_6_bal").val(text_6_bal);

  var mul7 = $(".text_7").val();
  var text_7_bal = mul7 * 5;
  $(".text_7_bal").val(text_7_bal);

  var mul8 = $(".text_8").val();
  var text_8_bal = mul8 * 2;
  $(".text_8_bal").val(text_8_bal);

  var mul9 = $(".text_9").val();
  var text_9_bal = mul9 * 1;
  $(".text_9_bal").val(text_9_bal);

  var total_money =
    text_0_bal +
    text_1_bal +
    text_2_bal +
    text_3_bal +
    text_4_bal +
    text_5_bal +
    text_6_bal +
    text_7_bal +
    text_8_bal +
    text_9_bal;

  $(".total_money").val(total_money);
}

function checkTime(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}
$(document).ready(function () {
  "use strict";
  function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById("time").innerHTML = h + ":" + m + ":" + s;
    t = setTimeout(function () {
      startTime();
    }, 500);
  }
});
/*Account part start*/

$(document).ready(function () {
  $("#jstree1").jstree({
    core: {
      themes: { icons: false },
    },
    plugins: ["types", "dnd"],

    types: {
      default: {
        icon: "fa fa-folder",
      },
      html: {
        icon: "fa fa-file-code-o",
      },
      svg: {
        icon: "fa fa-file-picture-o",
      },
      css: {
        icon: "fa fa-file-code-o",
      },
      img: {
        icon: "fa fa-file-image-o",
      },
      js: {
        icon: "fa fa-file-text-o",
      },
      attr: {
        class: "panel-heading",
      },
    },
  });
});

("use strict");
function loadCoaData(id) {
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "account/account/selectedform/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#newform").html(data);
      $("#treeviewmodal").modal("show");
      $("#btnSave").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error get data from ajax");
    },
  });
}

("use strict");
function newHeaddata(id) {
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "account/account/newform/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      console.log(data.rowdata);
      var headlabel = data.headlabel;
      $("#txtHeadCode").val(data.headcode);
      document.getElementById("txtHeadName").value = "";
      $("#txtPHead").val(data.rowdata.HeadName);
      $("#txtHeadLevel").val(headlabel);
      $("#btnSave").prop("disabled", false);
      $("#btnSave").show();
      $("#btnUpdate").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error get data from ajax");
    },
  });
}

function treeSubmit() {
  var frm = $("#treeview_form");
  var fdata = frm.serialize();
  var base_url = $("#base_url").val();
  var headcode = $("input[name=txtHeadCode]").val();
  var headname = $("input[name=txtHeadName]").val();
  var pheadname = $("input[name=txtPHead]").val();
  var level = $("input[name=txtHeadLevel]").val();
  var type = $("input[name=txtHeadType]").val();
  var is_active = $("input[name=IsActive]").val();
  var is_trans = $("input[name=IsTransaction]").val();
  var is_gl = $("input[name=IsGL]").val();
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  $.ajax({
    url: base_url + "account/account/insert_coa",
    method: "POST",
    dataType: "json",
    data: {
      txtHeadCode: headcode,
      txtHeadName: headname,
      txtPHead: pheadname,
      txtHeadLevel: level,
      txtHeadType: type,
      IsActive: is_active,
      IsTransaction: is_trans,
      IsGL: is_gl,
      csrf_test_name: csrf_test_name,
    },
    success: function (data) {
      if (data.status == true) {
        toastr["success"](data.message);
      } else {
        toastr["error"](data.exception);
      }
      $("#treeviewmodal").modal("hide");
    },
    error: function (xhr) {
      alert("failed!");
    },
  });
}

/*TAX SETTING*/
("use strict");
function add_columnTaxsettings(sl) {
  var text = "";
  var i;
  for (i = 0; i < sl; i++) {
    var f = i + 1;
    text +=
      '<div class="form-group row"><label for="fieldname" class="col-sm-1 col-form-label">Tax Name' +
      f +
      '*</label><div class="col-sm-2"><input type="text" placeholder="Tax Name" class="form-control" required autocomplete="off" name="taxfield[]"></div><label for="default_value" class="col-sm-1 col-form-label">Default Value<i class="text-danger">(%)</i></label><div class="col-sm-2"><input type="text" class="form-control" name="default_value[]" id="default_value"  placeholder="Default Value" /></div><label for="reg_no" class="col-sm-1 col-form-label">Reg No</label><div class="col-sm-2"><input type="text" class="form-control" name="reg_no[]" id="reg_no"  placeholder="Reg No" /></div><div class="col-sm-1"><input type="checkbox" name="is_show" class="form-control" value="1"></div><label for="isshow" class="col-sm-1 col-form-label">Is Show</label></div>';
  }
  document.getElementById("taxfield").innerHTML = text;
}

("use strict");
function deleteTaxRow(row) {
  var i = row.parentNode.parentNode.rowIndex;
  document.getElementById("POITable").deleteRow(i);
}

("use strict");
function TaxinsRow() {
  console.log("hi");
  var x = document.getElementById("POITable");
  var new_row = x.rows[1].cloneNode(true);
  var len = x.rows.length;
  new_row.cells[0].innerHTML = len;

  var inp1 = new_row.cells[1].getElementsByTagName("input")[0];
  inp1.id += len;
  inp1.value = "";
  var inp2 = new_row.cells[2].getElementsByTagName("input")[0];
  inp2.id += len;
  inp2.value = "";
  x.appendChild(new_row);
}

$(document).ready(function () {
  var taxn = $("#taxnumber").val();
  for (var i = 0; i < taxn; i++) {
    var sum = 0;
    $(".rpttax" + i).each(function () {
      sum += parseFloat($(this).text());
    });

    $("#rpttax" + i).html(
      sum.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })
    );
  }
});

("use strict");
function bank_paymetExpense(val) {
  if (val == 2) {
    var style = "block";
  } else {
    var style = "none";
  }

  document.getElementById("bank_div").style.display = style;
}

$("body").on("change", "#nameofficeloanperson", function (event) {
  event.preventDefault();
  var person_id = $("#nameofficeloanperson").val();
  var csrf_test_name = $("[name=csrf_test_name]").val();
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "hrm/loan/phone_search_by_name",
    type: "post",
    data: { person_id: person_id, csrf_test_name: csrf_test_name },
    success: function (msg) {
      $(".phone").val(msg);
    },
    error: function (xhr, desc, err) {
      alert("failed");
    },
  });
});

$("body").on("change", "#namepersonloan", function (event) {
  event.preventDefault();
  var person_id = $("#namepersonloan").val();
  var base_url = $("#base_url").val();
  var csrf_test_name = $("[name=csrf_test_name]").val();
  $.ajax({
    url: base_url + "hrm/loan/loan_phone_search_by_name",
    type: "post",
    data: { person_id: person_id, csrf_test_name: csrf_test_name },
    success: function (msg) {
      $(".phone").val(msg);
    },
    error: function (xhr, desc, err) {
      alert("failed");
    },
  });
});

$(document).ready(function () {
  "use strict";
  $("#customer_nameCommission").change(function (e) {
    var customer_id = $(this).val();
    var csrf_test_name = $("[name=csrf_test_name]").val();
    var base_url = $("#base_url").val();
    $.ajax({
      type: "post",
      async: false,
      url: base_url + "dashboard/setting/retrive_product_info",
      data: { customer_id: customer_id, csrf_test_name: csrf_test_name },
      success: function (data) {
        if (data) {
          $("#product_model").html(data);
        } else {
          $("#product_model").html("Product not found!");
        }
      },
      error: function () {
        alert("Request Failed, Please check your code and try again!");
      },
    });
  });
});

("use strict");
function checkallcreate(sl) {
  $("#checkAllcreate" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".create" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".create" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}
("use strict");
function checkallread(sl) {
  $("#checkAllread" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".read" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".read" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}

("use strict");
function checkalledit(sl) {
  $("#checkAlledit" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".edit" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".edit" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}

("use strict");
function checkalldelete(sl) {
  $("#checkAlldelete" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".delete" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".delete" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}

("use strict");
function userRole(id) {
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "dashboard/permission/select_to_rol/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#existrole").html(data);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#existrole").html("<p style='color:red'>No Role Assigned Yet</p>");
    },
  });
}

//            ========= its for toastr error message =============
$(document).ready(function () {
  $("#passrecoveryform").submit(function (e) {
    e.preventDefault();
    var form = $("#passrecoveryform");
    var email = $("#rec_email").val();
    var base_url = $("#base_url").val();
    var csrf_test_name = $("[name=csrf_test_name]").val();

    $.ajax({
      url: $(this).attr("action"),
      method: $(this).attr("method"),
      dataType: "json",
      data: $(this).serialize(),
      success: function (r) {
        if (r.status == 1) {
          toastr["success"](r.success);
        }
        if (r.status == 0) {
          toastr["error"](r.exception);
        }
      },
      error: function (xhr) {
        alert("failed!");
      },
    });
  });
});
/*dashboarjs*/

$(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });

// select 2 dropdown
$("select.form-control:not(.dont-select-me)").select2({
  placeholder: "Select option",
  allowClear: true,
});

$(window).on("load", function () {
  setTimeout(function () {
    $(".page-loader-wrapper").fadeOut();
  }, 50);
});

$(document).ready(function () {
  "use strict";
  $("#newcustomer").submit(function (e) {
    e.preventDefault();
    var customeMessage = $("#customeMessage");
    var customer_id = $("#autocomplete_customer_id");
    var customer_name = $("#customer_name");
    $.ajax({
      url: $(this).attr("action"),
      method: $(this).attr("method"),
      dataType: "json",
      data: $(this).serialize(),
      beforeSend: function () {
        customeMessage.removeClass("hide");
      },
      success: function (data) {
        if (data.status == true) {
          toastr["success"](data.message);
          customer_id.val(data.customer_id);
          customer_name.val(data.customer_name);
          $("#cust_info").modal("hide");
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

function customer_form() {
  var form = $("#customer_form");
  var custome_id = $("#customer_id").val();
  var customer_name = $("#customer_name").val();
  var base_url = $("#base_url").val();
  if (custome_id !== "") {
    var form_url = base_url + "edit_customer/" + custome_id;
  } else {
    var form_url = base_url + "add_customer";
  }

  if (customer_name == "") {
    $("#customer_name").focus();
    toastr["error"]("Customer name must be required");
    setTimeout(function () {}, 500);
    return false;
  }

  $.ajax({
    url: form_url,
    method: "POST",
    dataType: "json",
    data: form.serialize(),
    success: function (r) {
      if (r.status == 1) {
        if (custome_id == "") {
          $("#customer_form").trigger("reset");
        } else {
          setTimeout(function () {}, 1000);
          location.reload();
        }
        toastr["success"](r.msg);
      } else {
        toastr["error"](r.msg);
      }
    },
    error: function (xhr) {
      alert("failed!");
    },
  });
}

$(document).ready(function () {
  // customer list
  var CSRF_TOKEN = $("#CSRF_TOKEN").val();
  var customer_id = $("#customer_id").val();
  var base_url = $("#base_url").val();
  var mydatatable = $("#CustomerList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6, 7, 8, 9] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, -1],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "customer/customer/bdtask_CheckCustomerList",
      data: function (data) {
        data.csrf_test_name = CSRF_TOKEN;
        data.customer_id = $("#customer_id").val();
        data.customfiled = $("select[name='customsearch[]']").val();
      },
    },
    columns: [
      { data: "sl" },
      { data: "customer_name" },
      { data: "address" },
      { data: "mobile" },
      { data: "email" },
      { data: "city" },
      { data: "state" },
      { data: "zip" },
      { data: "country" },
      { data: "balance", class: "balance" },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toFixed(2, 2));
        });
    },
  });

  var mydatatable2 = $("#CustomerList2").DataTable({
    responsive: true,
    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3] }], //define el numero de columnas  aaaaaaaaaa
    processing: true,
    serverSide: true,
    lengthMenu: [
      [10, 25, 50, 100, 250, 500, -1],
      [10, 25, 50, 100, 250, 500, "All"],
    ],
    serverMethod: "post",
    ajax: {
      url: base_url + "customer/customer/bdtask_CheckCustomerList2",
      data: function (data) {
        data.csrf_test_name = CSRF_TOKEN;
        data.customer_id = $("#customer_id").val();
        data.customfiled = $("select[name='customsearch[]']").val();
      },
    },
    columns: [
      { data: "nombre_cliente" },
      { data: "telefono_cliente" },
      { data: "n_purchases" },
      { data: "total_purchases" },
    ],
  });

  $("#customer_id").on("change", function () {
    mydatatable.ajax.reload();
  });

  $("#customsearch").on("change", function () {
    mydatatable.ajax.reload();
  });
});

("use strict");
function customerdelete(id) {
  var base_url = $("#base_url").val();
  var r = confirm("Are you sure");
  if (r == true) {
    $.ajax({
      url: base_url + "customer/customer/bdtask_delete/" + id,
      type: "GET",
      success: function (r) {
        toastr["success"](r);
        setTimeout(function () {}, 5000);
        location.reload();
      },
    });
  }
}

$(document).ready(function () {
  // customer list
  var CSRF_TOKEN = $("#CSRF_TOKEN").val();
  var customer_id = $("#customer_id").val();
  var base_url = $("#base_url").val();
  var mydatatable = $("#Credit_customerList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6, 7, 8, 9] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, -1],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Credit CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Credit CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Credit CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        title: "<center> Credit CustomerList</center>",
        className: "btn-sm prints",
      },
    ],
    serverMethod: "post",
    ajax: {
      url: base_url + "customer/customer/bdtask_CheckCreditCustomerList",
      data: function (data) {
        data.csrf_test_name = CSRF_TOKEN;
        data.customer_id = $("#customer_id").val();
        data.customfiled = $("select[name='customsearch[]']").val();
      },
    },
    columns: [
      { data: "sl" },
      { data: "customer_name" },
      { data: "address" },
      { data: "mobile" },
      { data: "email" },
      { data: "city" },
      { data: "state" },
      { data: "zip" },
      { data: "country" },
      { data: "balance", class: "balance" },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toFixed(2, 2));
        });
    },
  });
  $("#customer_id").on("change", function () {
    mydatatable.ajax.reload();
  });

  $("#customsearch").on("change", function () {
    mydatatable.ajax.reload();
  });
});

$(document).ready(function () {
  // customer list
  var CSRF_TOKEN = $("#CSRF_TOKEN").val();
  var customer_id = $("#customer_id").val();
  var base_url = $("#base_url").val();
  var mydatatable = $("#paid_CustomerList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6, 7, 8, 9] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, -1],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "customer/customer/bdtask_CheckPaidCustomerList",
      data: function (data) {
        data.csrf_test_name = CSRF_TOKEN;
        data.customer_id = $("#customer_id").val();
        data.customfiled = $("select[name='customsearch[]']").val();
      },
    },
    columns: [
      {
        data: "sl",
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      { data: "customer_name" },
      { data: "address" }, // Asegúrate que coincide con el nombre en $data[]
      { data: "mobile" }, // Cambiado de "customer_mobile" a "mobile"
      { data: "email" },
      {
        data: "custom_discount",
        render: function (data, type, row) {
          return data ? data + "%" : "0%";
        },
      },
      { data: "city" },
      // { data: "state" },
      { data: "zip" },
      { data: "country" },
      // {
      //     data: "balance",
      //     class: "balance",
      //     render: function(data, type, row) {
      //         return parseFloat(data).toFixed(2);
      //     }
      // },
      {
        data: "button",
        orderable: false,
        searchable: false,
      },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toFixed(2, 2));
        });
    },
  });
  $("#customer_id").on("change", function () {
    mydatatable.ajax.reload();
  });

  $("#customsearch").on("change", function () {
    mydatatable.ajax.reload();
  });
});

function supplier_form() {
  var form = $("#supplier_form");
  var supplier_id = $("#supplier_id").val();
  var supplier_name = $("#supplier_name").val();
  var base_url = $("#base_url").val();
  if (supplier_id !== "") {
    var form_url = base_url + "edit_supplier/" + supplier_id;
  } else {
    var form_url = base_url + "add_supplier";
  }

  if (supplier_name == "") {
    $("#supplier_name").focus();
    toastr["error"]("supplier name must be required");
    setTimeout(function () {}, 500);
    return false;
  }

  $.ajax({
    url: form_url,
    method: "POST",
    dataType: "json",
    data: form.serialize(),
    success: function (r) {
      if (r.status == 1) {
        if (supplier_id == "") {
          $("#supplier_form").trigger("reset");
        } else {
          setTimeout(function () {}, 1000);
          location.reload();
        }
        toastr["success"](r.msg);
      } else {
        toastr["error"](r.msg);
      }
    },
    error: function (xhr) {
      alert("failed!");
    },
  });
}

$(document).ready(function () {
  // supplier list
  var CSRF_TOKEN = $("#CSRF_TOKEN").val();
  var supplier_id = $("#supplier_id").val();
  var base_url = $("#base_url").val();

  var mydatatable = $("#supplierList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [
      { bSortable: false, aTargets: [0, 2, 3, 4, 5, 6, 7 /*,8,9*/] },
    ],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, -1],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "supplier/supplier/bdtask_ChecksupplierList",
      data: function (data) {
        data.csrf_test_name = CSRF_TOKEN;
        data.supplier_id = $("#supplier_id").val();
        data.customfiled = $("select[name='customsearch[]']").val();
      },
    },
    columns: [
      { data: "sl" },
      { data: "supplier_name" },
      { data: "address" },
      { data: "mobile" },
      { data: "email" },
      { data: "city" },
      //{ data: 'state'},
      { data: "zip" },
      { data: "country" },
      //{ data: 'balance',class:"balance" },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toFixed(2, 2));
        });
    },
  });
  $("#supplier_id").on("change", function () {
    mydatatable.ajax.reload();
  });

  $("#customsearch").on("change", function () {
    mydatatable.ajax.reload();
  });
});

("use strict");
function supplierdelete(id) {
  var base_url = $("#base_url").val();
  var r = confirm("Are you sure");
  if (r == true) {
    $.ajax({
      url: base_url + "supplier/supplier/bdtask_delete/" + id,
      type: "GET",
      success: function (r) {
        toastr["success"](r);
        setTimeout(function () {}, 5000);
        location.reload();
      },
    });
  }
}

//Insert supplier
$("#insert_supplier").validate();
$("#validate").validate();

//Update supplier
$("#supplier_update").validate();

//Update customer
$("#customer_update").validate();
//Insert customer
$("#insert_customer").validate();

//Update product
$("#product_update").validate();
//Insert product
$("#insert_product").validate();
//Insert pos invoice
$("#insert_pos_invoice").validate();
//Insert invoice
$("#insert_invoice").validate();
//Update invoice
$("#invoice_update").validate();
//Insert purchase
$("#insert_purchase").validate();
//Update purchase
$("#purchase_update").validate();
//Add category
$("#insert_category").validate();
//Update category
$("#category_update").validate();
//Stock report
$("#stock_report").validate();
//Stock report
$("#stock_report_supplier_wise").validate();
//Stock report
$("#stock_report_product_wise").validate();
//Create account
$("#create_account_data").validate();
//Update account
$("#update_account_data").validate();
