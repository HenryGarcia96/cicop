@extends ('layouts.dashboard')
@section('page_heading', 'Estados de Cuenta')

@section('section')
<div class="row">
    {{ Form::open(['url' => 'pagos/buscar', 'method' => 'POST', 'id' => 'frm-pagos', 'class' => 'form']) }}
    <div class="col-md-4">
        <div class="panel-group" id="data1">
            <div class="card panel">
                <div class="card-body">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="0" />
                    <input type="hidden" id="id_contrato" name="id_contrato" value="0" />
                    <div class="form-group col-md-4">
                        <input class="form-control" autocomplete="off" name="folio" id="folio" />
                        <label for="folio">Folio</label>
                    </div>
                    <div class="form-group col-md-8 ">
                        <input class="form-control" id="cliente" name="cliente" />
                        <label for="cliente">Cliente</label>
                    </div>

                    <div class="form-group col-md-4">
                        <input type="text" autocomplete="off" class="form-control" id="manzana" name="manzana">
                        <label for="manzana">Manzana</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" class="form-control" autocomplete="off" id="lote" name="lote">
                        <label for="lote">Lote</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" autocomplete="off" id="precio" name="precio">
                        <label for="precio">Precio</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="pagado" autocomplete="off" name="pagado">
                        <label for="pagado">Pagado</label>
                    </div>

                    <div class="form-group col-md-4">
                        <input class="form-control bg-warning moneda" id="deuda" autocomplete="off" name="deuda">
                        <label for="deuda">Deuda</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="enganche" autocomplete="off" name="enganche">
                        <label for="enganche">Enganche</label>
                    </div>
                    <div class="form-group col-md-12 bg-success">
                        CON ESPECIALES
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="no_pagos_esp" autocomplete="off" name="no_pagos_esp">
                        <label for="no_pagos_esp">No Pagos</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="pago_esp" autocomplete="off" name="pago_esp">
                        <label for="pago_esp">Especiales de</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="total_esp" autocomplete="off" name="total_esp">
                        <label for="total_esp">Total</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="pagado_esp" autocomplete="off" name="pagado_esp">
                        <label for="pagado_esp">Pagados</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="monto_pagado_esp" autocomplete="off"
                            name="monto_pagado_esp">
                        <label for="monto_pagado_esp">Por</label>
                    </div>
                    <div class="form-group col-md-4">
                        <label>&nbsp;</label><br><br>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="adeuda_esp" autocomplete="off" name="adeuda_esp">
                        <label for="adeuda_esp">Adeuda</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="monto_adeuda_esp" autocomplete="off"
                            name="monto_adeuda_esp">
                        <label for="monto_adeuda_esp">Por</label>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-group" id="data1">
            <div class="card panel">
                <div class="card-body">
                    <div class="form-group col-md-12 bg-success">
                        MENSUALIDADES O QUINCENAS
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="no_pagos" autocomplete="off" name="no_pagos">
                        <label for="no_pagos">No Pagos</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="pagos" autocomplete="off" name="pagos">
                        <label for="pagos">Pagos de</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="total" autocomplete="off" name="total">
                        <label for="total">Total</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="no_pagado" autocomplete="off" name="no_pagado">
                        <label for="no_pagado">Pagados</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="monto_pagado" autocomplete="off" name="monto_pagado">
                        <label for="monto_pagado">Por</label>
                    </div>
                    <div class="form-group col-md-4">
                        <label>&nbsp;</label><br><br>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="adeuda" autocomplete="off" name="adeuda">
                        <label for="adeuda">Adeuda</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="monto_adeuda" autocomplete="off" name="monto_adeuda">
                        <label for="monto_adeuda">Por</label>
                    </div>
                    <div class="form-group col-md-12 bg-success">
                        ABONOS A CAPITAL
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="abonos" autocomplete="off" name="abonos">
                        <label for="abonos">Por</label>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-group" id="data1">
            <div class="card panel">
                <div class="card-body">
                    
                    <div class="form-group col-md-12 bg-success">
                        LIQUIDACION ANTICIPADA
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="deuda_liquidacion" autocomplete="off" name="deuda_liquidacion">
                        <label for="deuda_liquidacion">Deuda Real</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="costo_liquidacion" autocomplete="off" name="costo_liquidacion">
                        <label for="costo_liquidacion">Costo anticipado</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="pago_liquidacion" autocomplete="off" name="pago_liquidacion">
                        <label for="pago_liquidacion">Pago Final</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="ahorro" autocomplete="off" name="ahorro">
                        <label for="ahorro">Usted Ahorra</label>
                    </div>
                    <div class="form-group col-md-12 acciones">
                        
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Form::close() }}
    @include('modal.pago')
</div>
<style>
#tblPlanesSeleccionados tbody tr th {
    font-size: 8pt;
    padding: 0
}

#tblPlanesSeleccionados tbody tr td {
    font-size: 8pt
}
</style>
{{ Form::open(['url' => ['clientes/destroy', 'USER_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) }}
{{ Form::close() }}
@section('scripts')
<script type="text/javascript">

$('#folio').keyup(function(e) {
    if (e.which == 13) {
        buscar();
    }
});

var buscar = function() {
    var form = $('#frm-pagos');
    var url = form.attr('action');
    console.log(url);
    let liquidacion_financiamiento=0;
    var data = form.serialize();
    $.post(url, data, function(result) {

        console.log(result);
        var fila = result.intencion[0];
        $('div.acciones').html('');
        if(fila.estatus!=1)
        {
            $('div.acciones').append(`
                <a href="#!" class="btn btn-success btn-recibo">Recibo</a>
                <a href="#!" class="btn btn-primary btn-imprimir">Imprimir</a>`);
            mensaje(fila.estatus);
        }else
        {
            $('div.acciones').append(`<a href="#!" class="btn btn-success btn-iniciar">Liquidar</a>
                <a href="#!" class="btn btn-success btn-recibo">Recibo</a>
                <a href="#!" class="btn btn-primary btn-imprimir">Imprimir</a>`);
        }

        $('#cliente').val(fila.nombre);
        $('#id_contrato').val(fila.id);
        var total = 0;
        var enganche = 0;
        var lote = '';
        var manzana = '';
        var pago_esp = 0;
        var no_pago_esp = 0;
        var pagos = 0;
        var no_pago = 0;
        var meses=0;
        $.each(result.intencion, function(index, item) {
            total += Number(item.total);
            enganche += Number(item.enganche);
            manzana += item.manzana + ',';
            lote += item.lote + ',';
            pago_esp += Number(item.pago_esp);
            no_pago_esp = item.no_pagos_esp;

            if (item.tipo_pago == 1) {
                pagos += Number(item.pago_quincenal);
                no_pagos = item.quincenas;
            } else {
                pagos += Number(item.pago_mensual);
                no_pagos = item.mensualidades;
                
            }


        });
        
        
        var pagos_normales = 0;
        var pagos_especiales = 0;
        var total_pagos_especiales = 0;
        var abonos_capital = 0;
        var no_pagos_esp = 0;
        var no_pagos_norm = 0;
        var liquido=0;
        if (result.pagos != null) {

            $.each(result.pagos, function(index, item) {
                console.log(item);
                switch (Number(item.tipo)) {
                    case 1:
                        pagos_normales += Number(item.pagos);
                        no_pagos_norm = item.total;
                        break;
                    case 3:
                        abonos_capital = item.pagos;
                        break;
                    case 14:
                                liquido = Number(item.pagos);
                                console.log('entramos 14');
                                break;
                }
            });
        }
        pagos_especiales =0;
        no_pagos_esp = 0;
        var items_pagos_esp=result.pagos_generales.filter(x=>x.tipo==2);
        $.each(items_pagos_esp, function(index, item) {
                
                var items=result.pagos_esp.filter(p=>p.id_pago==item.id);
                var total=0;
                $.each(items,function(i,obj){
                    total +=Number(obj.importe);
                    pagos_especiales +=Number(obj.importe);
                })
                
                if(total==pago_esp)
                {
                    no_pagos_esp++;
                    total=0;
                }
            });
        meses=no_pagos_norm;
        if(fila.tipo_pago==1 && meses>0)
        {
            meses=no_pagos_norm/2;
        }
        $('#abonos').val(abonos_capital);
        $('#pagado').val(pagos_normales);
        $('#pagado_esp').val(no_pagos_esp);
        $('#monto_pagado_esp').val(pagos_especiales);

        $('#precio').val(total);
        $('#enganche').val(enganche);
        $('#lote').val(lote.slice(0, -1));
        $('#manzana').val(manzana.slice(0, -1));
        $('#no_pagos_esp').val(no_pago_esp);
        $('#pago_esp').val(pago_esp);
        var total_pago_esp = pago_esp * no_pago_esp;
        $('#total_esp').val(total_pago_esp);

        $('#adeuda_esp').val(no_pago_esp - no_pagos_esp);
        $('#monto_adeuda_esp').val(total_pago_esp - pagos_especiales);

        var deuda = total - Number($('#pagado').val());
        $('#deuda').val(deuda);

        $('#no_pagos').val(no_pagos);
        $('#pagos').val(pagos);
        $('#total').val(no_pagos * pagos);

        $('#no_pagado').val(no_pagos_norm);
        $('#monto_pagado').val(pagos_normales);

        $('#adeuda').val(no_pagos - no_pagos_norm);
        $('#monto_adeuda').val((no_pagos * pagos) - pagos_normales);

        

        //Liquidacion
        var total_abonos=Number(pagos_especiales)+Number(pagos_normales)+Number(abonos_capital)+Number(enganche)+Number(liquido);
        console.log(total_abonos,total);
        var deuda_liquidacion=Number(total)-total_abonos;
        $('#deuda_liquidacion').val(deuda_liquidacion);
        var precio_anticipado=fila.importe_anticipado;

        if(deuda_liquidacion<=0)
        {
            mensaje(3);
            $('.btn-iniciar').remove();
        }
        // if(meses>0)
        // {
        //     $.each(result.anticipado,function(index,item){
        //         if(meses>=item.mes_inicial && meses<=item.mes_final)
        //         {
        //             precio_anticipado=item.precio;
        //         }
        //     });
        // }
        $('#costo_liquidacion').val(precio_anticipado);
        var pago_liquidacion=precio_anticipado-total_abonos;
        $('#pago_liquidacion').val(pago_liquidacion);
        $('#ahorro').val(deuda_liquidacion-pago_liquidacion);

        $('.moneda').formatCurrency();


    }).fail(function(err) {
        console.log(err);
        alert('No se completo la busqueda');
    });
}
var mensaje=function(estatus){
    msj=['','Activo','Cancelado','Saldado','Pago Anticipado'];
    color=['','success','error','success','success'];
    $.toast({
        text: msj[estatus],
        heading: 'ATENCION',
        icon: color[color],
        showHideTransition: 'fade',
        allowToastClose: true,
        hideAfter: 3000,
        stack: 5,
        position: 'top-right',
        textAlign: 'left',
        loader: false,
        loaderBg: '#9EC600',
        beforeShow: function() {},
        afterShown: function() {},
        beforeHide: function() {},
        afterHidden: function() {}
    });
}
$('#costo_liquidacion').blur(function(){
        var total_abonos=Number($('#monto_pagado_esp').val().replace('$','').replace(',',''))+Number($('#pagado').val().replace('$','').replace(',',''))+Number($('#abonos').val().replace('$','').replace(',',''))+Number($('#enganche').val().replace('$','').replace(',',''));
        console.log(total_abonos);
        var precio_anticipado=$(this).val().replace('$','').replace(',','');
        var pago_liquidacion=precio_anticipado-total_abonos;
        var deuda_liquidacion=Number($('#deuda_liquidacion').val().replace('$','').replace(',',''));
        $('#pago_liquidacion').val(pago_liquidacion);
        $('#ahorro').val(deuda_liquidacion-pago_liquidacion);

        $('.moneda').formatCurrency();
});
$(document).on('click','a.btn-iniciar',function(){
    var importe=$('#pago_liquidacion').val();
    $('#importe').val(importe);
    
    $('#pagoModal').modal('show');
});
$(document).on('click','.btn-guardar',function(){
    var form = $('#frm-pago');
    var url = form.attr('action').replace('create','pagoAnticipada');
    console.log(url);
    
    var data = form.serialize()+'&id_contrato='+$('#id_contrato').val()+'&importe_anticipado='+$('#costo_liquidacion').val();
    console.log(data);
    
    $.post(url, data, function(result) {

        console.log(result);
        if(result.error<=0)
        {
            $('#pagoModal').modal('hide');
            $('.btn-iniciar').remove();
        }
            $.toast({
            text: result.mensaje,
            heading: 'ATENCION',
            icon: result.color,
            showHideTransition: 'fade',
            allowToastClose: true,
            hideAfter: 3000,
            stack: 5,
            position: 'top-right',
            textAlign: 'left',
            loader: false,
            loaderBg: '#9EC600',
            beforeShow: function() {},
            afterShown: function() {},
            beforeHide: function() {},
            afterHidden: function() {}
        });
        buscar();

    }).fail(function(err) {
        console.log(err);
        alert('No se completo la accion');
    });
});
$(document).on('click','.btn-recibo',function(){
    var form = $('#frm-pago');
    var url = form.attr('action').replace('create','recibo_anticipado');
    console.log(url);
    
    var data = form.serialize()+'&id_contrato='+$('#id_contrato').val()+'&importe_anticipado='+$('#costo_liquidacion').val();
    console.log(data);
    
    $.post(url, data, function(result) {
        var printWindow = window.open('', '', 'height=400,width=800');
                printWindow.document.write(result.html);
                printWindow.document.close();
                printWindow.onload = function() {

                    printWindow.print();
                }

    }).fail(function(err) {
        console.log(err);
        alert('No se completo la accion');
    });
});
$(document).on('click','a.btn-imprimir',function(){
    let date = moment().format("YYYY-MM-DD HH:mm");
    let logo = "{{asset('local/logos/superior_sicop.png')}}";
    var html=`<html>

<head>
    <title>Estado de cuenta</title>
    <style>
    @media print{
        html,body{margin:0}
        @page{size:A4; margin:10mm}
    }
    div {
            height: 40px;
        }
        .fecha{
            width:100%;
            text-align:right;
        }
        .titulo {
            width: 100%;
            text-align: center;
            font-weight: bold;
            height: 20px;
        }

        .fondo {
            background: #000;
            color: #FFF;
        }

        .fondo2 {
            background: rgb(233, 154, 8);
            color: rgb(0, 0, 0);
        }

        label {
            font-weight: bold;
            width: 12%;
            display: inline-block;
        }

        .col-a {
            display: inline-block;
            width: 18%;
        }

        .col-b {
            display: inline-block;
            width: 50%;
        }
    </style>

    <head>
    <img src="${logo}" style="width:100%" />
    <div class="fecha">Fecha impresion: ${date} </div>
        <div class="titulo">ESTADO DE CUENTA</div>
        <div>
            <label>FOLIO </label>
            <div class="col-a">${$('#folio').val()}</div>
            <label>NOMBRE </label>
            <div class="col-b">${$('#cliente').val()}</div>
        </div>
        <div>
            <label>MANZANA </label>
            <div class="col-a">${$('#manzana').val()}</div>
            <label>LOTE </label>
            <div class="col-a">${$('#lote').val()}</div>
        </div>
        <div>
            <label>PRECIO </label>
            <div class="col-a">${$('#precio').val()}</div>
            <label>PAGADO </label>
            <div class="col-a">${$('#pagado').val()}</div>
            <label>ADEUDA </label>
            <div class="col-a">${$('#deuda').val()}</div>
        </div>
        <div>
            <label>ENGANCHE </label>
            <div class="col-a">${$('#enganche').val()}</div>
        </div>
        <div class="titulo fondo">CON ESPECIALES</div>
        <div>
            <label>ESPECIALES </label>
            <div class="col-a">${$('#no_pagos_esp').val()}</div>
            <label>DE </label>
            <div class="col-a">${$('#pago_esp').val()}</div>
            <label>TOTAL </label>
            <div class="col-a">${$('#total_esp').val()}</div>
        </div>
        <div>
            <label>PAGADOS </label>
            <div class="col-a">${$('#pagado_esp').val()}</div>
            <label>POR </label>
            <div class="col-a">${$('#monto_pagado_esp').val()}</div>
        </div>
        <div>
            <label>ADEUDA </label>
            <div class="col-a">${$('#adeuda_esp').val()}</div>
            <label>POR </label>
            <div class="col-a">${$('#monto_adeuda_esp').val()}</div>
        </div>
        <div class="titulo fondo">MENSUALIDADES O QUINCENAS</div>
        <div>
            <label>&nbsp; </label>
            <div class="col-a">${$('#no_pagos').val()}</div>
            <label>PAGOS DE </label>
            <div class="col-a">${$('#pagos').val()}</div>
            <label>TOTAL </label>
            <div class="col-a">${$('#total').val()}</div>
        </div>
        <div>
            <label>PAGADOS </label>
            <div class="col-a">${$('#no_pagado').val()}</div>
            <label>POR </label>
            <div class="col-a">${$('#monto_pagado').val()}</div>
        </div>
        <div>
            <label>ADEUDA </label>
            <div class="col-a">${$('#adeuda').val()}</div>
            <label>POR </label>
            <div class="col-a">${$('#monto_adeuda').val()}</div>
        </div>
        <div class="titulo fondo">ABONOS A CAPITAL</div>
        <div>
            <label>POR </label>
            <div class="col-a">${$('#abonos').val()}</div>
        </div>
        <div class="titulo fondo">LIQUIDACION ANTICIPADA</div>
        <div>
            <label>DEUDA REAL </label>
            <div class="col-a">${$('#deuda_liquidacion').val()}</div>
            <label>COSTO ANTICIPADO </label>
            <div class="col-a">${$('#costo_liquidacion').val()}</div>
        </div>
        <div class="titulo fondo2">C. ${$('#cliente').val()}, USTED LIQUIDA CON ${$('#pago_liquidacion').val()}</div>
        <div class="titulo">
            USTED AHORRA ${$('#ahorro').val()}
        </div>`;
    var printWindow = window.open('', '', 'height=400,width=800');
                    printWindow.title=name;
                    printWindow.document.write(html);
                    printWindow.document.close();
                    printWindow.onload = function() {
                        printWindow.print();
                    };

});
</script>
@endsection
@stop