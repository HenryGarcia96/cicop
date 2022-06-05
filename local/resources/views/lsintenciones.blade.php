@extends ('layouts.dashboard')
@section('page_heading', 'Intenciones de compra')



@section('section')


<div class="col-sm-12">
    <div class="row">

        <div class="col-md-12">
            <div class="row">
                {{ Form::open(['url' => 'contratos/buscar', 'method' => 'POST', 'id' => 'form-buscar']) }}
                <div class="col-md-2">
                    <a href="{{ url('contrato/nuevo') }}" class="btn btn-success">Nuevo <i
                            class="fa fa-diamond"></i></a>
                </div>
                <div class="form-group col-md-2"><label><input type="checkbox" id="incluirFechas"
                            name="incluirFechas">Incluir Fecha</label></div>
                <div class="form-group col-md-4">
                    <div class="input-daterange input-group" id="demo-date-range">
                        <div class="input-group-content">
                            <input type="text" class="form-control" id="start" name="start" placeholder="Fecha inicio"
                                value="{{date('Y-m').'-01'}}" />
                            <div class="form-control-line"></div>
                        </div>
                        <span class="input-group-addon">to</span>
                        <div class="input-group-content">
                            <input type="text" class="form-control" id="end" name="end" placeholder="Fecha fin"
                                value="{{date('Y-m-d',strtotime(date('Y-m', strtotime(date('Y-m-d'). ' + 1 months')).'-01 - 1 days'))}}" />
                            <div class="form-control-line"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">

                    <div class="input-group">

                        <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre,folio"
                            class="form-control">
                        <span class="input-group-btn"><button class="btn btn-buscar btn-default" type="button"><i
                                    class="fa fa-search"></i></button></span>
                    </div>

                </div>
                {{ Form::close() }}

            </div>
            <input type="hidden" id="acciones" name="acciones"
                value="{{ Session::get('menu')['CLIENTES-ED'] . '-' . Session::get('menu')['CLIENTES-EL'] }}" />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblPlanes" class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Tipo Pago</th>
                                    <th>#</th>
                                    <th>$</th>
                                    <th>No.PE</th>
                                    <th>PE</th>
                                    <th>Tipo</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($intenciones as $intencion)
                                <tr data-id="{{ $intencion['id'] }}" data-folio="{{ $intencion['folio'] }}" data-nombre="{{ $intencion['nombre'] }}" data-telefono="{{$intencion['celular']}}" data-status="{{$intencion['estatus']}}">
                                    <td>{{ $intencion['folio'] }}</td>
                                    <td>{{ $intencion['nombre'] }}</td>
                                    <td>{{ $intencion['fecha'] }}</td>
                                    <td>{{ $intencion['tipo_pago'] }}</td>
                                    @if($intencion['tipo_pago']==1)
                                        <td>{{ $intencion['quincenas'] }}</td>
                                    @else
                                        <td>{{ $intencion['mensualidades'] }}</td>
                                    @endif
                                    @if($intencion['tipo_pago']==1)
                                        <td>{{ $intencion['pago_quincenal'] }}</td>
                                    @else
                                        <td>{{ $intencion['pago_mensual'] }}</td>
                                    @endif
                                    <td>{{ $intencion['no_pagos_esp'] }}</td>
                                    <td>{{ $intencion['pago_esp'] }}</td>
                                    @if($intencion['estatus']!="Contado")
                                        <td>{{ $intencion['tipo'] == 1 ? 'Tipo 1' : 'Tipo 2' }}</td>
                                        <td>{{ $intencion['total'] }}</td>
                                    @else
                                        <td></td>
                                        <td>{{ $intencion['pago_anticipado'] }}</td>
                                    @endif
                                    
                                    <td>{{ $intencion['estatus'] }}</td>
                                    <td>
                                        @if($intencion['estatus']=='Contado')
                                        <a href="#!" class="btn-print-intencion btn btn-success"><i class="fa fa-print"></i></a>
                                        <a href="#!" class="btn-delete-intencion btn btn-danger"><i class="fa fa-trash"></i></a>
                                        @else
                                        <a href="#!" class="btn-editar btn btn-primary"><i class="fa fa-edit"></i></a>
                                        <a href="#!" class="btn btn-success btn-seguimiento" data-id="{{$intencion['id']}}" data-toggle="tooltip" data-placement="top" title="Seguimiento"><i class="fa fa-file-text"></i></a>
                                        @endif

                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--</form>-->
    </div>
    <!--Inicia modal -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar intencion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ Form::open(['url' => 'contratos/editar', 'method' => 'POST', 'id' => 'frm-contrato', 'class' => 'form']) }}
                    <input type="hidden" id="id_contrato" name="id_contrato" value="0" />
                    <input type="hidden" id="id_plan" name="id_plan" value="0" />
                    <input type="hidden" id="estatus" name="estatus" />
                    <div class="form-group col-md-3">
                        <input type="text" autocomplete="off" class="form-control" id="folio" name="folio">
                        <label for="nombre">Folio</label>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" autocomplete="off" class="form-control" id="manzana" name="manzana">
                        <label for="nombre">Manzana</label>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" autocomplete="off" class="form-control" id="lote" name="lote">
                        <label for="nombre">Lote</label>
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" autocomplete="off" class="form-control" id="metros" name="metros">
                        <label for="nombre">Mts<sup>2</sup> totales</label>
                    </div>
                    <div class="row">Colindancia para contrato</div>
                    <div class="form-group col-md-6">
                        <input type="text" autocomplete="off" class="form-control" id="norte" name="norte">
                        <label for="nombre">Norte</label>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" autocomplete="off" class="form-control" id="sur" name="sur">
                        <label for="nombre">Sur</label>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" autocomplete="off" class="form-control" id="este" name="este">
                        <label for="nombre">Este</label>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" autocomplete="off" class="form-control" id="oeste" name="oeste">
                        <label for="nombre">Oeste</label>
                    </div>

                    <table id="tblPlanesSeleccionados" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Descripcion</th>
                                <th>$M</th>
                                <th>$Q</th>
                                <th>#Esp</th>
                                <th>$Esp</th>
                                <th>Manzana</th>
                                <th>Lote</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>


                    {{ Form::close() }}
                </div>
                <div class="modal-footer">
                <a href="#!" id="cancelar" class="btn btn-danger btn-cancelar"><i class="fa fa-save"></i> Cancelar</a>
                    <a href="#!" class="btn btn-success btn-guardar"><i class="fa fa-save"></i> Guardar</a>
                    <a href="#!" class="btn btn-success btn-enganche"><i class="fa fa-money"></i> Enganche</a>
                    <a href="#!" class="btn-imprimir btn btn-success"><i class="fa fa-print"></i> Carta</a>

                    <a href="#!" class="btn-contrato btn btn-warning"><i class="fa fa-file"></i> Contrato</a>
                    <a href="#!" class="btn-tabla-cotizacion btn btn-warning"><i class="fa fa-table"></i> Tabla</a>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Termina modal -->
    <!-- Modal -->
    <div class="modal fade" id="pagoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pagoModalLabel">Pago de Enganche</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ Form::open(['url' => 'contratos/enganche', 'method' => 'POST', 'id' => 'frm-enganche', 'class' => 'form']) }}
                    <input type="hidden" id="id_contrato_enganche" name="id_contrato_enganche" value="0" />
                    <input type="hidden" id="id_plan_enganche" name="id_plan_enganche" value="0" />
                    <div class="form-group col-md-3">
                        <input type="text" autocomplete="off" class="form-control" id="folioa" name="folioa">
                        <label for="folioa">Folio</label>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" autocomplete="off" class="form-control" id="fecha_pago" name="fecha_pago"
                            value="{{date('Y-m-d')}}">
                        <label for="fecha_pago">Fecha de Pago</label>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" autocomplete="off" class="form-control" id="enganche" name="enganche">
                        <label for="enganche">Enganche</label>
                    </div>
                    <div class="row"></div>
                    <div class="form-group col-md-6">
                        <select class="form-control" id="forma_pago" name="forma_pago">
                            <option>EFECTIVO</option>
                            <option>DEPOSITO</option>
                            <option>TARJETA</option>
                            <option>TRANSFERENCIA</option>
                        </select>
                        <label for="forma_pago">Forma de Pago</label>
                    </div>
                    <div class="form-group col-md-6">
                    <select class="form-control" id="institucion_bancaria" name="institucion_bancaria">
                                <option>BANCOPPEL</option>
                                <option>BANAMEX</option>
                                <option>BANJERCITO</option>
                                <option>BANCO AZTECA</option>
                                <option>BANCOMER</option>
                                <option>HSBC</option>
                                <option>INBURSA</option>
                                <option>SANTANDER</option>
                                <option>SCOTIABANK</option>
                                <option>TELECOM</option>
                                <option>OXXO</option>
                                <option>OTRO</option>

                            </select>
                        <label for="institucion_bancaria">Institucion Bancaria</label>
                    </div>
                    <div class="form-group col-md-12">
                        <textarea type="text" autocomplete="off" class="form-control" id="observaciones"
                            name="observaciones"></textarea>
                        <label for="observaciones">Observaciones</label>
                    </div>



                    {{ Form::close() }}
                    <div class="row"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-guardar-enganche">Guardar</button>
                    <button type="button" class="btn btn-primary btn-imprimir-enganche">Imprimir</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Termina modal -->

    {{ Form::open(['url' => ['contratos/destroy', 'USER_ID'], 'method' => 'DELETE', 'id' => 'frm-delete']) }}
    {{ Form::close() }}
    @include('modal.seguimiento')

</div>
@section('scripts')
<script type="text/javascript">
//    $('#demo-date-format').datepicker({autoclose: true, todayHighlight: true, format: "yyyy/mm/dd"});
$('#demo-date-range').datepicker({
    todayHighlight: true,
    format: "yyyy-mm-dd"
});
var row = new Object();
var tblPlanes = new Object();
$(document).ready(function() {
    $('.btn-nuevo').click(function() {
        $('#frm-planes')[0].reset();
        $('#id_plan').val(0);
        $('#exampleModal').modal('show');
    });

    $('.btn-enganche').click(function() {
        $('#pagoModal').modal('show');
    });
    $('#tblPlanesSeleccionados tbody').on('click', 'tr', function() {
        $('#tblPlanesSeleccionados tbody tr').removeClass('info');
        var id = $(this).data('id');
        $(this).addClass('info');
        $('#id_plan_enganche').val(id);
        $('#id_plan').val(id);
        var folio = $(this).data('folio');



        var item = tblPlanes.find(item => item.id == id);
        if (item) {
            console.log(item);

            $('#manzana').val(item.manzana);
            $('#lote').val(item.lote);
            // $('#norte').val(item.N);
            // $('#sur').val(item.S);
            // $('#este').val(item.E);
            // $('#oeste').val(item.O);

        }
    });
    
    $('#tblPlanes tbody').on('click', 'tr td .btn-print-intencion', function() {
        row = $(this).parents('tr');
        $('#tblPlanes tbody tr').removeClass('info');
        $('#frm-contrato')[0].reset();
        $('#tblPlanesSeleccionados tbody tr').remove();
        $('#id_plan').val(0);
        var fila = $(this).parents('tr');
        $(fila).addClass('info');
        var id = fila.data('id');
        var folio = fila.data('folio');
        var enganche = fila.data('enganche');
        var estatus=fila.data('status');
        var form = $('#frm-contrato');
        var url = form.attr('action').replace('contratos/editar', 'pagos/recibo_contado');
        $('#id_contrato').val(id);
        $('#id_contrato_enganche').val(id);
        $('#folio').val(folio);
        $('#folioa').val(folio);
        

        console.log(url);
        var data = form.serialize();
console.log(data);
        $.post(url, data, function(result) {
            console.log(result);
            var printWindow = window.open('', '', 'height=400,width=800');

            printWindow.document.write(result.html);
            printWindow.document.close();
            printWindow.onload = function() {

                printWindow.print();
            }



        }).fail(function() {
            alert('Error al imprimir el recibo');
        });
        
        
    });
    $('#tblPlanes tbody').on('click', 'tr td .btn-delete-intencion', function() {
        
        var fila = $(this).parents('tr');
        $(fila).addClass('info');
        var id = fila.data('id');
        
        var form = $('#frm-delete');
        var url = form.attr('action').replace('USER_ID', id);
        
        var data =form.serialize();
        if(confirm('Esta seguro de realizar esta accion?\nLe recordamos que no podra recuperar el registro')){
            $.post(url, data, function(result) {
                mensaje(result.mensaje,result.color);
                if(result.error==0){
                    $(fila).remove();
                }
            }).fail(function(result) {
                console.log(result);
                alert('Error al intentar borrar el registro');
            });
        }
        
        
    });
    $('#tblPlanes tbody').on('click', 'tr td .btn-editar', function() {
        row = $(this).parents('tr');
        $('#tblPlanes tbody tr').removeClass('info');
        $('#frm-contrato')[0].reset();
        $('#tblPlanesSeleccionados tbody tr').remove();
        $('#id_plan').val(0);
        var fila = $(this).parents('tr');
        $(fila).addClass('info');
        var id = fila.data('id');
        var folio = fila.data('folio');
        var enganche = fila.data('enganche');
        var estatus=fila.data('status');
            $('#cancelar').text('Cancelar');
            $('#cancelar').removeClass('btn-primary');
            $('#cancelar').addClass('btn-danger');


        if(estatus=="Cancelado"){
            $('#cancelar').text('Activar');
            $('#cancelar').removeClass('btn-danger');
            $('#cancelar').addClass('btn-primary');
        }

        if ($.trim(folio) != '') {
            $('.btn-recibo').removeClass('disabled');
        } else {
            $('.btn-recibo').addClass('disabled');
        }

        var form = $('#frm-delete');
        var url = form.attr('action').replace('/destroy/USER_ID', '/getPlanesById/' + fila.data('id'));
        $('#id_contrato').val(id);
        $('#id_contrato_enganche').val(id);
        $('#folio').val(folio);
        $('#folioa').val(folio);
        $('#enganche').val(enganche);
        console.log(url);
        var enganche_total = 0;
        var data = form.serialize();
        $.post(url, data, function(result) {
            $('#estatus').val(result[0].estatus);
            console.log(result);
            $('#tblPlanesSeleccionados tbody tr').remove();
            var html = '';
            var arr = result;
            tblPlanes = result;
            var fila = '';
            var aux = 0;

            for (i = 0; i < arr.length; i++) {

                html += '<tr data-id="' + arr[i].id + '" >';
                html += '<td>' + arr[i].descripcion + '</td>';
                html += '<td>' + arr[i].pago_mensual + '</td>';
                html += '<td>' + arr[i].pago_quincenal + '</td>';
                html += '<td>' + arr[i].no_pagos_esp + '</td>';
                html += '<td>' + arr[i].pago_esp + '</td>';
                html += '<td>' + arr[i].manzana + '</td>';
                html += '<td>' + arr[i].lote + '</td>';
                html += '</tr>';
                enganche_total += Number(arr[i].enganche);
                $('#tblPlanesSeleccionados').append(html);
                html = '';

            }
            if (arr[0]) {
                $('#forma_pago').val(arr[0].forma_pago);
                $('#fecha_pago').val(arr[0].fecha_pago);
                $('#institucion_bancaria').val(arr[0].institucion_bancaria);
                $('#observaciones').val(arr[0].observaciones);
                $('#norte').val(arr[0].N);
                $('#sur').val(arr[0].S);
                $('#este').val(arr[0].E);
                $('#oeste').val(arr[0].O);
                $('#metros').val(arr[0].metros);
            }

            $('#enganche').val(enganche_total);
        }).fail(function(err) {
            console.log(err);
            alert('No se completo la busqueda');
        });
        $('#exampleModal').modal('show');
    });
    function mensaje(mensaje,color){
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
    $('.btn-cancelar').click(function(){
		console.log(url);
		var fila=$(this).parents('tr');
		var id=$('#id_contrato').val();
		var button=$(this);
        var texto=$(this).text();
		if(confirm('¿Desea '+texto+' el contrato con el folio '+$('#folio').val()+'?'))
		{
			var form = $('#frm-delete');
			var url = form.attr('action').replace('destroy/USER_ID','cancelar/'+id);
			var data = form.serialize()+'&estatus='+$('#estatus').val();
console.log(data);
			$.post(url, data, function(result) {
				console.log(result);
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

				if(result.error==0)
				{
					button.text((texto=='Activar')?'Cancelar':'Activar');
                    if(texto=="Cancelar"){
                        $('#cancelar').text('Activar');
                        $('#cancelar').removeClass('btn-danger');
                        $('#cancelar').addClass('btn-primary');
                    }else
                    {
                        $('#cancelar').text('Cancelar');
                        $('#cancelar').removeClass('btn-primary');
                        $('#cancelar').addClass('btn-danger');
                    }
                    buscar();
				}

			}).fail(function() {
				alert('Error al cancelar el contrato');
			});
		}
	});
    $('.btn-guardar').click(function() {
        var form = $('#frm-contrato');
        var url = form.attr('action');
        console.log(url);
        var data = form.serialize();

        $.post(url, data, function(result) {
            console.log(row);
            var folio = $('#folio').val();
            if (result.error == 0) {
                $(row).data("folio", folio);
                $(row).find('td:eq(0)').text(folio);
            }

            console.log($('#folio').val());
            console.log(result);
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

            //$('#exampleModal').modal('hide');
            $('.btn-enganche').removeClass('disabled');

        }).fail(function() {
            alert('Error al guardar el plan');
        });
    });
    $('.btn-guardar-enganche').click(function() {
        var form = $('#frm-enganche');
        var url = form.attr('action');
        console.log(url);
        var data = form.serialize();

        $.post(url, data, function(result) {
            console.log(result);
            $.toast({
                text: "El enganche ha sido guardado correctamente",
                heading: 'ATENCION',
                icon: 'success',
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


        }).fail(function() {
            alert('Error al guardar el enganche');
        });
    });

    $('.btn-imprimir').click(function() {
        // var fila = $(this).parents('tr');
        // var id = fila.data('id');
        // $('#id_contrato').val(id);
        var form = $('#frm-contrato');
        var url = form.attr('action').replace('editar', 'imprimir');
        console.log(url);
        var data = form.serialize();

        $.post(url, data, function(result) {
            console.log(result);
            var printWindow = window.open('', '', 'height=400,width=800');

            printWindow.document.write(result.html);
            printWindow.document.close();
            printWindow.onload = function() {

                printWindow.print();
            }



        }).fail(function() {
            alert('Error al imprimir la carta de intencion');
        });
    });
    $('.btn-contrato').click(function() {
        // var fila = $(this).parents('tr');
        // var id = fila.data('id');
        // $('#id_contrato').val(id);
        var form = $('#frm-contrato');
        var url = form.attr('action').replace('editar', 'contrato');
        console.log(url);
        var data = form.serialize();

        $.post(url, data, function(result) {
            console.log(result);
            var printWindow = window.open('', '', 'height=400,width=800');

            printWindow.document.write(result.html);
            printWindow.document.close();
            printWindow.onload = function() {

                printWindow.print();
            }



        }).fail(function() {
            alert('Error al imprimir el contrato');
        });
    });
    $('.btn-tabla-cotizacion').click(function() {
        // var fila = $(this).parents('tr');
        // var id = fila.data('id');
        // $('#id_contrato').val(id);
        var form = $('#frm-contrato');
        var url = form.attr('action').replace('editar', 'tabla');
        console.log(url);
        var data = form.serialize();

        $.post(url, data, function(result) {
            console.log(result);
            var printWindow = window.open('', '', 'height=400,width=800');

            printWindow.document.write(result.html);
            printWindow.document.close();
            printWindow.onload = function() {

                printWindow.print();
            }



        }).fail(function() {
            alert('Error al imprimir la tabla de financiamiento');
        });
    });
    $('.btn-imprimir-enganche').click(function() {

        var form = $('#frm-enganche');
        var url = form.attr('action').replace('enganche', 'recibo');
        console.log(url);
        var data = form.serialize();

        $.post(url, data, function(result) {
            console.log(result);
            var printWindow = window.open('', '', 'height=400,width=800');
            //printWindow.document.write('<html><head><style>div,span{font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;font-size:10pt}table{font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace; font-size:10pt}div.ticket{width:50mm;font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;}.ticket-header{text-align: center;}table{width:100%}.ticket-totales{ text-align:right}.ticket-totales span,.ticket-totales-2 span{width: 50%;display: inline-block; text-align:right}.ticket-footer{margin-top:10px;text-align: center;}.ticket-footer span {font-weight: bold; width:40mm}.ticket-folio span{font-weight: bold;width: 100px;display: inline-block;}</style><title>Ticket</title>');
            //printWindow.document.write('</head><body style="margin-left:0mm;font-size:10pt;">');
            //printWindow.document.write('<div class="ticket">'+divContents+'</div>');
            //printWindow.document.write('</body></html>');
            printWindow.document.write(result.html);
            printWindow.document.close();
            printWindow.onload = function() {

                printWindow.print();
            }


        }).fail(function() {
            alert('Error al imprimir la carta de intencion');
        });
    });

    $('#buscar').keyup(function(e) {
        if (e.which == 13) {
            buscar();
        }
    });

    $('.btn-buscar').click(function() {
        buscar();
    });


});

function buscar() {
    var form = $('#form-buscar');
    var url = form.attr('action');


    console.log(url);

    var data = form.serialize();
    var acc = $('#acciones').val().split('-');
    $.post(url, data, function(result) {

        $('#tblPlanes tbody tr').remove();
        var html = '';
        var arr = result;
        var fila = '';
        var aux = 0;

        for (i = 0; i < arr.length; i++) {

            html += '<tr data-id="' + arr[i].id + '" data-folio="' + ((arr[i].folio == null) ? '' : arr[i]
                .folio) + '" data-status="'+arr[i].estatus+'" >';
            html += '<td>' + ((arr[i].folio == null) ? '' : arr[i].folio) + '</td>';
            html += '<td>' + arr[i].nombre + '</td>';
            html += '<td>' + arr[i].fecha + '</td>';
            html += '<td>' + arr[i].tipo_pago + '</td>';
            if(arr[i].tipo_pago==1)
            {
                html += '<td>' + ((arr[i].quincenas == null) ? '' : arr[i].quincenas) + '</td>';
            }else
            {
                html += '<td>' + ((arr[i].mensualidades == null) ? '' : arr[i].mensualidades) + '</td>';
            }
            if(arr[i].tipo_pago==1)
            {
                html += '<td>' + ((arr[i].pago_quincenal == null) ? '' : arr[i].pago_quincenal) + '</td>';
            }else
            {
                html += '<td>' + ((arr[i].pago_mensual == null) ? '' : arr[i].pago_mensual) + '</td>';
            }
            html += '<td>' + ((arr[i].no_pagos_esp == null) ? '' : arr[i].no_pagos_esp) + '</td>';
            html += '<td>' + ((arr[i].pago_esp == null) ? '' : arr[i].pago_esp) + '</td>';
            if(arr[i].estatus!='Contado')
            {
                html += '<td>' + ((arr[i].tipo == 1) ? 'Tipo 1' : 'Tipo 2') + '</td>';
                html += '<td>' + ((arr[i].total == null) ? '' : arr[i].total) + '</td>';
            }else
            {
                html +='<td></td>';
                html += '<td>' + arr[i].pago_anticipado + '</td>';
            }
            
            html += '<td>' + arr[i].estatus + '</td>';
            if(arr[i].estatus=='Contado')
            {
                html +='<td><a href="#!" class="btn-print-intencion btn btn-success"><i class="fa fa-print"></i></a>';
                html +='<a href="#!" class="btn-delete-intencion btn btn-danger"><i class="fa fa-trash"></i></a></td>';
            }else{
                html +='<td><a href="#!" class="btn-editar btn btn-primary"><i class="fa fa-edit"></i></a>';
                html +='<a href="#!" class="btn btn-success btn-seguimiento" data-id="{{$intencion['id']}}" data-toggle="tooltip" data-placement="top" title="Seguimiento"><i class="fa fa-file-text"></i></a></td>';
            }
            html += '</tr>';

            $('#tblPlanes').append(html);
            html = '';
        }

    }).fail(function(err) {
        console.log(err);
        alert('No se completo la busqueda');
    });
}

function stopRKey(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13) && (node.type == "text")) {
        return false;
    }
}
document.onkeypress = stopRKey;
</script>
@endsection
@stop