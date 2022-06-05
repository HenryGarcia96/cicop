@extends ('layouts.dashboard')
@section('page_heading', 'Pagos')

@section('section')
    <div class="row">
        {{ Form::open(['url' => 'pagos/buscar', 'method' => 'POST', 'id' => 'frm-pagos', 'class' => 'form']) }}
        <div class="col-md-6">
            <div class="panel-group" id="data1">
                <div class="card panel">
                    <div class="card-body">
                        <input type="hidden" id="id_intencion" name="id_intencion" value="0" />
                        <input type="hidden" id="id_pago" name="id_pago" value="0" />
                        <input type="hidden" id="indice" name="indice" value="0" />

                        <div class="form-group col-md-4">
                            <input class="form-control" autocomplete="off" name="folio" id="folio" />
                            <label for="folio">Folio</label>
                        </div>
                        <div class="form-group col-md-8 ">
                            <input class="form-control" id="cliente" name="cliente" />
                            <label for="cliente">Cliente</label>
                        </div>

                        <div class="form-group col-md-6">
                            <select class="form-control" name="tipo_pago" id="tipo_pago">
                                
                            </select>
                            <label for="lote">Tipo de Pago</label>
                        </div>
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
                        <div class="form-group col-md-6">
                            <input class="form-control" id="fecha_pago" name="fecha_pago" autocomplete="off"
                                value="{{ date('Y-m-d') }}" />
                            <label for="fecha_pago">Fecha de pago</label>
                        </div>

                        <div class="form-group col-md-6">
                            <input class="form-control" readonly="readonly" autocomplete="off" id="corresponde"
                                name="corresponde" />
                            <input type="hidden" autocomplete="off" id="fecha" name="fecha" />
                            <div class="hidden" id="fechas_pago" style="max-height:200px; overflow-y:auto"></div>
                            <label for="corresponde">Pago Correspondiente</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input class="form-control" type="number" autocomplete="off" id="importe" name="importe" />
                            <label for="importe">Importe a pagar</label>
                        </div>

                        <div class="form-group col-md-6">
                            <input class="form-control" autocomplete="off" id="ticket" name="ticket" />
                            <label for="ticket">Folio del ticket</label>
                        </div>
                        <div class="form-group col-md-12">

                            <textarea class="form-control" rows="4" id="comments" name="comments"></textarea>
                            <label for="comments">Observaciones</label>
                        </div>

                        <div class="form-group col-md-12">
                            <a href="{{url('/pagos')}}" class="btn btn-primary btn-limpiar">Limpiar</a>
                            <a href="#!" class="btn btn-success btn-guardar">Guardar</a>
                            <a href="#!" class="hidden btn btn-primary btn-imprimir">Imprimir Recibo</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!--Iniicia col-6 -->
            <div class="panel-group" id="data1">
                <div class="card panel">
                    <div class="card-body">
                        <div class="row">
                            <label class="radio-inline radio-styled">
                                <input type="radio" name="chkPago" checked="checked"
                                    value="1"><span>Mensual/Quincenal</span>
                            </label>
                            <label class="radio-inline radio-styled">
                                <input type="radio" name="chkPago" value="2"><span>Pagos Esp</span>
                            </label>
                            <label class="radio-inline radio-styled">
                                <input type="radio" name="chkPago" value="3"><span>Abonos a Capital</span>
                            </label>
                            <label class="radio-inline radio-styled">
                                <input type="radio" name="chkPago" value="4"><span>Otros</span>
                            </label>
                        </div>
                        <table id="tblPagos" class="table">
                            <thead>
                                <tr>
                                    <td>Tipo Pago</td>
                                    <td>Fecha Pago</td>
                                    <td>Importe</td>
                                    <td>Correspondiente</td>
                                    <td>Forma Pago</td>
                                    <td>Ticket</td>
                                    <td></td>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Termina div-col-6 -->

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

        table tbody tr td{font-size:8pt}

    </style>
    {{ Form::open(['url' => ['clientes/destroy', 'USER_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) }}
    {{ Form::close() }}
@section('scripts')
    <script type="text/javascript">
    $('.btn-limpiar').click(function(){
        $('#frm-pagos')[0].reset();
        $('input[name="chkPago"').eq(0).prop('checked', true);
        $('#tblPagos tbody tr').remove();
    });
        $('.btn-imprimir').addClass('disabled');
        $('#folio').keyup(function(e) {
            if (e.which == 13) {

                buscar();
            }
        });
        
        let no_pagos_esp = 0;
        let no_pagos_norm = 0;
        let fechas_normales = null;
        let fechas_esp = null;
        let pagos_esp = 0;
        let pagos = 0;
        let pagos_generales = null;
        let tipo_pago = 2;
        let pagos_gral_esp = null;
        let tipo_pagos = null;
        
        let pagos_globales=0;
        let importe_globales=0;
        let liquidacion_financiamiento=0;

        $(document).ready(function() {
            var form = $('#frm-pagos');
            var url = form.attr('action').replace('pagos/buscar', 'config/getTipoPagos');
            console.log(url);
            var data = null;
            $.get(url, function(result) {
                console.log(result);
                tipo_pagos=result;
                $.each(result,function(index,item){
                    $('#tipo_pago').append(`<option value="${item.id}">${item.descripcion}</option>`)
                });
            }).fail(function(err) {
                console.log(err);
            });
        });
        var buscar = function() {
            $('.btn-guardar').removeClass('disabled');
            var form = $('#frm-pagos');
            var url = form.attr('action');
            console.log(url);
            var folio=$('#folio').val();
            $('input[name="chkPago"').eq(0).prop('checked', true);


            var data = form.serialize();
            $('#frm-pagos')[0].reset();
            $('#folio').val(folio);
            $.post(url, data, function(result) {
                no_pagos_esp = 0;
                no_pagos_norm = 0;
                fechas_normales = null;
                fechas_esp = null;
                pagos_esp = 0;
                pagos = 0;
                importe_globales=0;
                pagos_globales=0;
                liquidacion_financiamiento=0;
                pagos_generales = null;
                var fila = result.intencion[0];
                tipo_pago = fila.tipo_pago;

                if (Number(tipo_pago) == 2) {
                    $("#tipo_pago option[value=1]").html('Mensualidad');
                } else {
                    $("#tipo_pago option[value=1]").html('Quincena');
                }

                console.log(result);
                pagos_generales = result.pagos_generales;
                pagos_gral_esp = result.pagos_esp;
                if (pagos_generales.length <= 0) {
                    mensajes('Debe de generar la tabla de financiamiento', 'error');
                    return false;
                }

                $('.btn-imprimir').removeClass('disabled');

                $('#cliente').val(fila.nombre);
                fechas_normales = result.pagos_generales.filter(x => x.tipo == 1);
                fechas_esp = result.pagos_generales.filter(x => x.tipo == 2);
                $('#id_intencion').val(fila.id);

                if (fila.estatus != 1) {
                    mensaje(fila.estatus);
                    $('.btn-guardar').remove();
                }

                var total = 0;
                var enganche = 0;
                var lote = '';
                var manzana = '';
                //var pago_esp = 0;
                var no_pago_esp = 0;
                //var pagos = 0;
                var no_pago = 0;
                $.each(result.intencion, function(index, item) {
                    total += Number(item.total);
                    enganche += Number(item.enganche);
                    manzana += item.manzana + ',';
                    lote += item.lote + ',';
                    pagos_esp += Number(item.pago_esp);
                    no_pago_esp = item.no_pagos_esp;

                    if (item.tipo_pago == 1) {
                        pagos += Number(item.pago_quincenal);
                        no_pagos = item.quincenas;
                    } else {
                        pagos += Number(item.pago_mensual);
                        no_pagos = item.mensualidades;
                    }
                });
                //$('#importe').val(pagos);
                importe_globales=total;
                pagos_globales +=enganche;

                var pagos_normales = 0;
                var pagos_especiales = 0;
                var total_pagos_especiales = 0;
                var abonos_capital = 0;
                var liquido=0;
                
                if (result.pagos.length > 0) {
                    $.each(result.pagos, function(index, item) {
                        console.log(item);
                        switch (Number(item.tipo)) {
                            case 1:
                                pagos_normales += Number(item.pagos);
                                no_pagos_norm = Number((item.total == null) ? 0 : item.total);
                                console.log('entramos 1');
                                break;
                            case 2:
                                pagos_especiales += Number(item.pagos);
                                no_pagos_esp = Number((item.total == null) ? 0 : item.total);
                                console.log('entramos 2');
                                break;
                            case 3:
                                abonos_capital += Number(item.pagos);
                                console.log('entramos 3');
                                break;
                            case 14:
                                liquido = Number(item.pagos);
                                console.log('entramos 14');
                                break;
                        }
                    });
                } else {
                    console.log('No existen pagos');
                }
                $.each(result.pagos_esp, function(index, item) {
                        pagos_especiales += Number(item.importe);
                });

                pagos_globales +=pagos_normales;
                pagos_globales +=abonos_capital;
                pagos_globales +=pagos_especiales;
                console.log(importe_globales,pagos_globales);
                console.log(no_pagos_norm, no_pagos_esp);

                liquidacion_financiamiento=importe_globales-pagos_globales-liquido;

                if(liquidacion_financiamiento<=0)
                {
                    mensaje(3);
                    $('.btn-guardar').remove();
                }

                setTimeout(function() {
                    $('#tipo_pago').change();
                }, 500);

                //Listamos los pagos
                //====================================================
                listarPagos(1);
            }).fail(function(err) {
                console.log(err);
                alert('No se completo la busqueda');
            });
        }
        $('input[name="chkPago"]').change(function() {
            var valor = $(this).val();
            listarPagos(valor);
        });
        
        
        html='';
        var listarPagos = function(tipo) {
            if (Number(tipo_pago) == 2) {
            forma_pago = 'Mensualidad';
        } else {
            forma_pago = 'Quincena';
        }
            $('#tblPagos tbody tr').remove();
            if (tipo == 2) {
                
                $.each(pagos_gral_esp, function(index, item) {
                    html = '<tr><td>Pagos Esp</td>';
                    html += '<td>' + item.fecha_pago + '</td>';
                    html += '<td>' + item.importe + '</td>';
                    html += '<td>' + item.fecha + '</td>';
                    html += '<td>' + item.forma_pago + '</td>';
                    html += '<td>' + item.ticket + '</td>';
                    html += '<td><a href="#!" class="esp-print" data-id="' + item.id +
                                '" class="text-danger"><i class="fa fa-print"></i></a></td>';
                    html += '<td><a href="#!" class="esp" data-id="' + item.id +
                        '" class="text-danger"><i class="fa fa-trash"></i></a></td></tr>';

                    $('#tblPagos').append(html);

                });
            } else {
                $.each(pagos_generales, function(index, item) {
                    console.log(tipo);
                    if (Number(item.importe) > 0) {
                        if ((Number(item.tipo) == 1 || Number(item.tipo) == 3) && Number(item.tipo) == tipo) {
                            if(tipo==3)
                            {
                                forma_pago=tipo_pagos.find(x=>x.id==item.tipo).descripcion;
                            }
                            html = '<tr><td>' + forma_pago + '</td>';
                            html += '<td>' + item.fecha_pago + '</td>';
                            html += '<td>' + item.importe + '</td>';
                            html += '<td>' + item.fecha + '</td>';
                            html += '<td>' + item.forma_pago + '</td>';
                            html += '<td>' + item.ticket + '</td>';
                            if (Number(tipo) == 3) {
                                html += '<td><a href="#!" class="print" data-id="' + item.id +
                                '" class="text-danger"><i class="fa fa-print"></i></a></td>';
                                html += '<td><a href="#!" class="otros" data-id="' + item.id +
                                    '" class="text-danger"><i class="fa fa-trash"></i></a></td></tr>';
                            } else {
                                html += '<td><a href="#!" class="print" data-id="' + item.id +
                                '" class="text-danger"><i class="fa fa-print"></i></a></td>';
                                html += '<td><a href="#!" class="pagos" data-id="' + item.id +
                                    '" class="text-danger"><i class="fa fa-trash"></i></a></td></tr>';
                            }
                            $('#tblPagos').append(html);
                        } else if (Number(item.tipo) > 3 && Number(tipo) > 3) {
                            html = '<tr><td>' + tipo_pagos.find(x=>x.id==item.tipo).descripcion + '</td>';
                            html += '<td>' + item.fecha_pago + '</td>';
                            html += '<td>' + item.importe + '</td>';
                            html += '<td>' + item.fecha + '</td>';
                            html += '<td>' + item.forma_pago + '</td>';
                            html += '<td>' + item.ticket + '</td>';
                            html += '<td><a href="#!" class="print" data-id="' + item.id +
                                '" class="text-danger"><i class="fa fa-print"></i></a></td>';
                            html += '<td><a href="#!" class="otros" data-id="' + item.id +
                                '" class="text-danger"><i class="fa fa-trash"></i></a></td></tr>';
                                $('#tblPagos').append(html);
                        }
                        
                        html = '';
                    }
                });
            }
        }
        $('#tblPagos tbody').on('click', 'tr td a.pagos', function() {
            if (!confirm('Esta seguro de realizar esta accion?')) {
                return false;
            }
            console.log('Mandamos a actualizar');
            var id = $(this).data('id');
            var form = $('#frm-pagos');
            var url = form.attr('action').replace('/buscar', '/actualizar/' + id);
            console.log(url);

            var data = null;
            $.get(url, function(result) {
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
                buscar();

            }).fail(function(err) {
                console.log(err);
                alert('No se completo la busqueda');
            });
        });
        $('#tblPagos tbody').on('click', 'tr td a.esp', function() {
            if (!confirm('Esta seguro de realizar esta accion?')) {
                return false;
            }
            console.log('Mandamos a actualizar');
            var id = $(this).data('id');
            var form = $('#frm-pagos');
            var url = form.attr('action').replace('/buscar', '/borrar/' + id);
            console.log(url);

            var data = null;
            $.get(url, function(result) {
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
                buscar();

            }).fail(function(err) {
                console.log(err);
                alert('No se completo la busqueda');
            });
        });
        $('#tblPagos tbody').on('click', 'tr td a.otros', function() {
            console.log('Mandamos a borrar');
            if (!confirm('Esta seguro de realizar esta accion?')) {
                return false;
            }
            console.log('Mandamos a actualizar');
            var id = $(this).data('id');
            var form = $('#frm-pagos');
            var url = form.attr('action').replace('/buscar', '/borrar/' + id);
            console.log(url);

            var data = null;
            $.get(url, function(result) {
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
                buscar();

            }).fail(function(err) {
                console.log(err);
                alert('No se completo la busqueda');
            });
        });
        var mensaje = function(estatus) {
            mensaje = ['', 'Activo', 'Cancelado', 'Saldado', 'Pago Anticipado'];
            color = ['', 'success', 'error', 'success', 'success'];
            $.toast({
                text: mensaje[estatus],
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
        $(document).on('click','a.print',function(){
            var id=$(this).data('id');
            
            var form = $('#frm-pagos');
            var url = form.attr('action').replace('/buscar', '/recibo2');
            console.log(url);

            var data = form.serialize()+'&id_pago='+id;
            $.post(url, data, function(result) {
                console.log(result);
                var printWindow = window.open('', '', 'height=400,width=800');
                printWindow.document.write(result.html);
                printWindow.document.close();
                printWindow.onload = function() {

                    printWindow.print();
                }

            }).fail(function(err) {
                console.log(err);
                alert('No se completo la busqueda');
            });
        });
        $(document).on('click','a.esp-print',function(){
            var id=$(this).data('id');
            
            var form = $('#frm-pagos');
            var url = form.attr('action').replace('/buscar', '/recibo_esp');
            console.log(url);

            var data = form.serialize()+'&id_pago='+id;
            $.post(url, data, function(result) {
                console.log(result);
                var printWindow = window.open('', '', 'height=400,width=800');
                printWindow.document.write(result.html);
                printWindow.document.close();
                printWindow.onload = function() {

                    printWindow.print();
                }

            }).fail(function(err) {
                console.log(err);
                alert('No se completo la busqueda');
            });
        });
        $('.btn-imprimir').click(function() {
            var form = $('#frm-pagos');
            var url = form.attr('action').replace('/buscar', '/recibo');
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

            }).fail(function(err) {
                console.log(err);
                alert('No se completo la busqueda');
            });
        });
        var meses = ['', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE',
            'NOVIEMBRE', 'DICIEMBRE'
        ];
        var fechaaletras = function(fe) {

            var campos = fe.split('-');
            var mes = Number(campos[1]);

            return campos[2] + ' DE ' + meses[mes] + ' DE ' + campos[0];
        }
        var fechaaletras2 = function(fe) {

            var campos = fe.split('-');
            var mes = Number(campos[1]);

            return campos[2] + ' DE ' + meses[mes] + ' DE ' + campos[0];
        }
        $('#tipo_pago').change(function() {
            var fecha = '';
            $('.btn-guardar').removeClass('disabled');
            $('#fecha').val('');
            $('#importe').val(0);
            $('#corresponde').val('');
            $('#fechas_pago').html('');
            $('#id_pago').val(0);
            $('#importe').removeAttr('readonly');

            if($(this).val()==14)
            {
                $('#importe').val(liquidacion_financiamiento);
                $('#importe').attr('readonly','readonly');
            }
            
            switch (Number($(this).val())) {
                case 1:
                    fecha = fechas_normales[no_pagos_norm]['fecha'];
                    $('#fecha').val(fecha);
                    $('#corresponde').val(fechaaletras(fecha));
                    $('#importe').val(pagos);
                    $('#id_pago').val(fechas_normales[no_pagos_norm]['id']);
                    $('#indice').val(fechas_normales[no_pagos_norm]['indice'])
                    break;
                case 2:
                    var pagosa=pagos_generales.filter(x=>x.tipo==2);
                    var cont =0;
                    var total=0;
                    $.each(pagosa,function(index,item){
                        var res=pagos_gral_esp.filter(y=>y.id_pago==item.id);
                        if(res.length>0){
                            total=0;
                            console.log(res);
                            $.each(res,function(i,obj){
                                total +=Number(obj.importe);
                            });
                            if(total==pagos_esp)
                            {
                                cont++;
                            }
                        }
                    });
                    console.log(total);

                    if (fechas_esp[cont]['fecha']) {
                        fecha = fechas_esp[cont]['fecha'];
                        $('#fecha').val(fecha);
                        $('#corresponde').val(fechaaletras(fecha));
                        $('#id_pago').val(fechas_esp[cont]['id']);
                        $('#indice').val(fechas_esp[cont]['indice'])
                    }
                    var importe=Number(pagos_esp)-Number(total);
                    if(importe==0){
                        importe=pagos_esp;
                    }
                    $('#importe').val(importe);
                    break;

            }

            $.each(pagos_generales, function(index, item) {
                if (Number(item.importe) <= 0 && Number(item.tipo) == Number($('#tipo_pago').val())) {
                    var html = '<label><input type="checkbox" name="pagos[]" value="' + item.fecha + '">' +
                        fechaaletras2(item.fecha) + '</label><br>';
                    $('#fechas_pago').append(html);
                }

            });

        });

        $('#fechas_pago').on('change', 'input[name="pagos[]"]', function() {
            console.log($(this).val());


            var total = $('input[name="pagos[]"]:checked').length;
            if (Number($('#tipo_pago').val()) == 1) {
                $('#importe').val(pagos * total);
            } else if (Number($('#tipo_pago').val()) == 2) {
                $('#importe').val(pagos_esp * total);
            }

        });
        var mensajes = function(mensaje, color) {
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
        };
        $('.btn-guardar').click(function() {
            $('.btn-imprimir').addClass('disabled');
            if ($.trim($('#fecha').val()) == '' && Number($('#tipo_pago').val()) < 3) {
                return false;
            }

            var form = $('#frm-pagos');
            var url = form.attr('action').replace('/buscar', '/create');
            console.log(url);

            var data = form.serialize();
            $.post(url, data, function(result) {
                console.log(result);
                $('.btn-imprimir').removeClass('disabled');
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
                alert('No se completo la busqueda');
            });
        });
    </script>
@endsection
@stop
