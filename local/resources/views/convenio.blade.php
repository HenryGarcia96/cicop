@extends ('layouts.dashboard')
@section('page_heading', 'Convenios')

@section('section')
<div class="row">
    {{ Form::open(['url' => 'convenio/create', 'method' => 'POST', 'id' => 'frm-convenio', 'class' => 'form']) }}
    <div class="col-md-4">
        <div class="panel-group" id="data1">
            <div class="card panel">
                <div class="card-body">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="0" />
                    <input type="hidden" id="id_contrato" name="id_contrato" value="0" />
                    <input type="hidden" id="id_convenio" name="id_convenio" value="0" />
                    <div class="form-group col-md-4">
                        <input class="form-control" autocomplete="off" name="folio" id="folio" value="{{$folio}}" />
                        <label for="folio">Folio</label>
                    </div>
                    <div class="form-group col-md-8 ">
                        <input class="form-control" id="cliente" name="cliente" />
                        <label for="cliente">Cliente</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" autocomplete="off" class="form-control" id="fecha" name="fecha">
                        <label for="fecha">Fecha de Convenio</label>
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
                        <label for="precio">Cantidad Contratada</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control moneda" id="pagado" autocomplete="off" name="pagado">
                        <label for="pagado">Cantidad Pagada</label>
                    </div>

                    <div class="form-group col-md-4">
                        <input class="form-control bg-warning moneda" id="deuda" autocomplete="off" name="deuda">
                        <label for="deuda">Cantidad que Adeuda</label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="meses_rezago" autocomplete="off" name="meses_rezago">
                        <label for="meses_rezago">Meses de rezago</label>
                    </div>
                    <div class="form-group col-md-8">
                        <input class="form-control" id="meses_rezago_text" autocomplete="off" name="meses_rezago_text">
                        <label for="meses_rezago_text"></label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="pagos_especiales" autocomplete="off" name="pagos_especiales">
                        <label for="pagos_especiales">Pagos Especiales</label>
                    </div>
                    <div class="form-group col-md-8">
                        <input class="form-control" id="pagos_especiales_text" autocomplete="off" name="pagos_especiales_text">
                        <label for="pagos_especiales_text"></label>
                    </div>
                    <div class="form-group col-md-6">
                        <input class="form-control moneda" id="rezago_mensualidades" autocomplete="off" name="rezago_mensualidades">
                        <label for="rezago_mensualidades">Rezago mensualidades</label>
                    </div>
                    <div class="form-group col-md-6">
                        <input class="form-control moneda" id="rezago_especiales" autocomplete="off" name="rezago_especiales">
                        <label for="rezago_especiales">Rezago pagos especiales</label>
                    </div>
                    <div class="form-group col-md-6">
                        <input class="form-control moneda" id="recargos" autocomplete="off" name="recargos">
                        <label for="recargos">Recargos</label>
                    </div>
                    <div class="form-group col-md-6 bg-danger">
                        <input class="form-control  moneda" id="total_rezago" autocomplete="off" name="total_rezago">
                        <label for="total_rezago">Total de Rezago</label>
                    </div>
                    
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-group" id="data1">
            <div class="card panel">
                <div class="card-body">
                    <div class="form-group">
                    <textarea id="documento" name="documento" class="form-control" style="font-size:10pt" rows="19">
6 PAGOS QUINCENALES PUNTUALES POR LA CANTIDAD DE $1,185 PESOS COMO A CONTINUACION SE DESCRIBE. EL PRIMER PAGO PUNTUAL EL DÍA  15 DE MAYO DE 2021, EL SEGUNDO EL 30 DE MAYO DE 2021, EL TERCERO EL 15 DE JUNIO DE 2021, EL CUARTO PAGO EL 30 DE JUNIO DE 2021, EL QUINTO PAGO EL DÍA 15 DE JULIO DE 2021, EL SEXTO PAGO EL 30 DE JULIO DE 2021.QUEDANDO ESTIPULADO EN ESTE DOCUMENTO QUE NO PODRÉ VOLVER A PAGAR ATRASADO NINGUN PAGO, POR CONCEPTO DE QUINCENAS,  MENSUALIDADES Y/O PAGOS ESPECIALES.  POR LO QUE EN CASO DE NO PAGAR EN TIEMPO Y FORMA LOS DIAS  15 Y 30 DE CADA MES POR EL RESTO DEL CONTRATO, ACEPTARÉ LA RESCICIÓN DE ESTE,  EN PLENO USO DE CONOCIMIENTO QUE ESTE ES UN CONVENIO DE NO ATRASO, DERIVADO DE LA OPORTUNIDAD DE CONTINUAR PAGANDO MI CONTRATO, Y DANDOME POR ENTERADA QUE NO SE HARÁ DEVOLUCIÓN ALGUNA POR NINGUN CONCEPTO PAGADO, Y SE PODRÁ DISPONER DEL TERRENO EN EL MOMENTO EN QUE SE INCUMPLA DICHO ACUERDO.
</textarea>
                    <label for="documento">POR MEDIO DE LA PRESENTE ME COMPROMETO A PAGAR</label>
</div>
<div class="form-control">
    <a href="#!" class="btn btn-success btn-guardar">Guardar</a>
    <a href="{{ url ('convenio') }}" class="btn btn-warning ">Nuevo</a>
</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-group" id="data1">
            <div class="card panel">
                <div class="card-body">

                
                        <div class="table-responsive">
                            <table id="tblConvenios" class="table no-margin">
                                <thead>
                                    <tr>

                                        <th>Fecha</th>
                                        <th>Usuario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    
                    
                </div>
            </div>
        </div>
    </div>

    {{ Form::close() }}

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
$(document).ready(function(){
    if($.trim($('#folio').val())!=""){
        buscar();
    }
});
$('#folio').keyup(function(e) {
    if (e.which == 13) {
        buscar();
    }
});

var buscar = function() {
    var form = $('#frm-convenio');
    var url = form.attr('action').replace('create','buscar');
    console.log(url);

    var data = form.serialize();
    $.post(url, data, function(result) {

        console.log(result);
        var fila = result.intencion[0];

        if(fila.estatus!=1)
        {
            $('.btn-guardar').remove();
            mensaje(fila.estatus);
        }

        $('#cliente').val(fila.nombre);
        $('#id_cliente').val(fila.id_cliente);
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
        if (result.pagos != null) {

            $.each(result.pagos, function(index, item) {
                console.log(item);
                switch (Number(item.tipo)) {
                    case 1:
                        pagos_normales += Number(item.pagos);
                        no_pagos_norm = item.total;
                        break;
                    case 2:
                        pagos_especiales += Number(item.pagos);
                        no_pagos_esp = item.total;
                        break;
                    case 3:
                        abonos_capital = item.pagos;
                        break;
                }
            });
        }
        meses=no_pagos_norm;
        if(fila.tipo_pago==1 && meses>0)
        {
            meses=no_pagos_norm/2;
        }
        $('#abonos').val(abonos_capital);
        $('#pagado').val(pagos_normales+enganche+pagos_especiales+abonos_capital);
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

        

        //Adeudos
        var adeudos=result.adeudos;
        
        var meses_rezago=0;
        var esp_rezago=0;

        var resultado=adeudos.filter(x=>x.tipo==1);
        if(resultado.length>1)
        {
            console.log(resultado[0].fecha);
            console.log(resultado[resultado.length-1].fecha);
            var text='DEL MES DE '+soloMesLetras(resultado[0].fecha)+' AL MES DE '+soloMesLetras(resultado[resultado.length-1].fecha);
            $('#meses_rezago_text').val(text);
        }
        var resultado=adeudos.filter(x=>x.tipo==2);
        if(resultado.length>=1)
        {
            console.log(resultado[0].fecha);
            console.log(resultado[resultado.length-1].fecha);
            var text='DEL MES DE '+soloMesLetras(resultado[0].fecha);
            $('#pagos_especiales_text').val(text);
        }

        var monto_rezago=0;
        var monto_rezago_esp=0;
        $.each(adeudos,function(index,item){
            if(item.tipo==1)
            {
                if(item.tipo_pago=='Mensual')
                {
                    meses_rezago++;
                    monto_rezago=monto_rezago+Number(item.mensual);
                }else
                {
                    meses_rezago=meses_rezago+0.5;
                    monto_rezago=monto_rezago+Number(item.quincenal);
                }
            }else if(item.tipo==2)
            {
                esp_rezago++;
                monto_rezago_esp=monto_rezago_esp+Number(item.especiales);
            }
        });
        $('#meses_rezago').val(meses_rezago);
        $('#pagos_especiales').val(esp_rezago);
        $('#rezago_mensualidades').val(monto_rezago);
        $('#rezago_especiales').val(monto_rezago_esp);

        calcularTotal();

        $('.moneda').formatCurrency();

        getConvenios();


    }).fail(function(err) {
        console.log(err);
        alert('No se completo la busqueda');
    });
}
$('#total_rezago').blur(function(){
    calcularTotal();
});
$('#rezago_mensualidades').blur(function(){
    calcularTotal();
});
$('#rezago_especiales').blur(function(){
    calcularTotal();
});
var calcularTotal=function(){
    var monto_rezago=Number($('#rezago_mensualidades').val().replace('$','').replace(',',''));
    var monto_rezago_esp=Number($('#rezago_especiales').val().replace('$','').replace(',',''));

    var recargos=Number($('#recargos').val().replace('$','').replace(',',''));

    $('#total_rezago').val(monto_rezago+monto_rezago_esp+recargos);
    $('.moneda').formatCurrency();
}
var mensaje=function(mensaje,color){
    
    $.toast({
        text: mensaje,
        heading: 'ATENCION',
        icon: color,
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
var meses=['','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
var soloMesLetras=function(fe){
    var campos=fe.split('-');
    var mes=Number(campos[1]);
    
    return meses[mes]+' DE ' + campos[0];
}
var fechaaletras=function(fe){
    
    var campos=fe.split('/');
    var mes=Number(campos[1]);
    
    return campos[0]+' DE '+ meses[mes]+' DE ' + campos[2];
}
var fechaaletras2=function(fe){
    
    var campos=fe.split('-');
    var mes=Number(campos[1]);
    
    return campos[2]+' DE '+ meses[mes]+' DE ' + campos[0];
}
$('.btn-guardar').click(function(){
    var form = $('#frm-convenio');
    var url = form.attr('action');
    console.log(url);

    var data = form.serialize();
    $.post(url, data, function(result) {
        $('#id_convenio').val(result.id_convenio);
        mensaje(result.mensaje,result.color);

    }).fail(function(err) {
        console.log(err);
        alert('No se completo la accion');
    });
});
var getConvenios=function(){
    var id=$('#id_contrato').val();
    var form = $('#frm-convenio');
    var url = form.attr('action').replace('create','getConvenios/'+id);
    console.log(url);

    var data = null;
    $('#tblConvenios tbody tr').remove();
    $.get(url, data, function(result) {
        console.log(result);
        var convenios=result.convenios;
        $.each(convenios,function(index,item){
            var html='<tr>';
            html +='<td>'+item.fecha+'</td>';
            html +='<td>'+item.nombre+'</td>';
            html +='<td><a data-id="'+item.id+'" href="#!" class="btn btn-success btn-show"><i class="fa fa-eye"></i></a>&nbsp;<a data-id="'+item.id+'" href="#!" class="btn btn-warning btn-imprimir"><i class="fa fa-print "></i></a></td>';
            html +='</tr>';

            $('#tblConvenios').append(html);
            
        });
    }).fail(function(err) {
        console.log(err);
        alert('No se completo la accion');
    });
}

$('#tblConvenios').on('click','tr td a.btn-imprimir',function(){
    var id=$(this).data('id');
    var form = $('#frm-convenio');
        var url = form.attr('action').replace('create', 'imprimir/'+id);
        console.log(url);
        var data = null;

        $.get(url, data, function(result) {
            console.log(result);
            var printWindow = window.open('', '', 'height=400,width=800');

            printWindow.document.write(result.html);
            printWindow.document.close();
            printWindow.onload = function() {

                printWindow.print();
            }

        }).fail(function() {
            alert('Error al imprimir el convenio');
        });
});
$('#tblConvenios').on('click','tr td a.btn-show',function(){
    
    var id=$(this).data('id');
    var form = $('#frm-convenio');
    var url = form.attr('action').replace('create','show/'+id);
    console.log(url);

    var data = null;
    
    $.get(url, data, function(result) {
        console.log(result);
        var convenio=result.convenio[0];

        $('#id_convenio').val(convenio.id);
        $('#fecha').val(convenio.fecha);
        $('#manzana').val(convenio.manzana);
        $('#lote').val(convenio.lote);
        $('#precio').val(convenio.cantidad_contratada);
        $('#pagado').val(convenio.cantidad_pagada);
        $('#deuda').val(convenio.cantidad_adeuda);
        $('#meses_rezago').val(convenio.meses_rezago);
        $('#meses_rezago_text').val(convenio.meses_rezago_text);
        $('#pagos_especiales').val(convenio.esp_rezago);
        $('#pagos_especiales_text').val(convenio.esp_rezago_text);
        $('#rezago_mensualidades').val(convenio.monto_rezago);
        $('#rezago_especiales').val(convenio.monto_rezago_esp);
        $('#recargos').val(convenio.recargos);
        $('#total_rezago').val(convenio.total_rezagos);
        $('#documento').val(convenio.documento);

        
    }).fail(function(err) {
        console.log(err);
        alert('No se completo la accion');
    });
});

</script>
@endsection
@stop