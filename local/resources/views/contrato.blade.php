@extends ('layouts.dashboard')
@section('page_heading', 'Contratos')

@section('section')
    <div class="row">
        {{ Form::open(['url' => 'contratos/create', 'method' => 'POST', 'id' => 'frm-contrato', 'class' => 'form']) }}
        <div class="col-md-6">
        <div class="panel-group" id="data1"><div class="card panel"><div class="card-body">
            <input type="hidden" id="id_cliente" name="id_cliente" value="0" />
            <input type="hidden" id="id_plan" name="id_plan" value="0" />
            <div class="form-group col-md-6">
                <label class="control-label">Tipo Venta</label>
                <div class="col-sm-9">
                    <label class="radio-inline radio-styled">
                        <input type="radio" name="tipo_venta" checked="checked" value="0"><span>Credito</span>
                    </label>
                    <label class="radio-inline radio-styled">
                        <input type="radio" name="tipo_venta" value="1"><span>Contado</span>
                    </label>
                </div>
            </div>
            <div class="form-group col-md-6">
            <input class="form-control" name="folio" id="folio" autocomplete="off" />
            <label for="folio">Folio</label>
            </div>
            <div class="form-group col-md-4">
            <input class="form-control" name="fecha" id="fecha" value="{{date('Y-m-d')}}" />
            <label for="fecha">Fecha registro</label>
            </div>
            <div class="form-group input-group">
                <input class="form-control typeahead" id="cliente" name="cliente" />
                <a href="#!" class="btn-nuevo input-group-addon btn btn-primary" title="Nuevo cliente">
                    <i class="fa fa-users"></i>
                </a>
                <label for="cliente">Cliente</label>
            </div>
            <div class="form-group credito">
                <input class="form-control typeahead" id="plan" name="plan" />
                <label for="plan">Plan financiero</label>
            </div>

            <div class="row">
            </div>
            <div class="form-group col-md-12">
                <input type="text" autocomplete="off" class="form-control" id="descripcion" name="descripcion">
                <label for="descripcion">Descripcion</label>
            </div>
            <div class="form-group col-md-4 credito">
                <input type="text" class="form-control" autocomplete="off" id="enganche" name="enganche">
                <label for="enganche">Enganche</label>
            </div>

            <div class="form-group col-md-4 credito">
                <input class="form-control" id="pago_mensual" autocomplete="off" name="pago_mensual">
                <label for="pago_mensual">Pago Mensual</label>
            </div>
            <div class="form-group col-md-4 credito">
                <input class="form-control" id="pago_quincenal" autocomplete="off" name="pago_quincenal">
                <label for="pago_quincenal">Pago Quincenal</label>
            </div>

            <div class="form-group col-md-4 credito">
                <input class="form-control" id="no_pagos_esp" autocomplete="off" name="no_pagos_esp">
                <label for="no_pagos_esp">No Pagos Especiales</label>
            </div>
            <div class="form-group col-md-4 credito ">
                <input class="form-control" id="pago_esp" autocomplete="off" name="pago_esp">
                <label for="pago_esp">Pago Especial</label>
            </div>
            <div class="form-group col-md-4 credito">
                <a href="#!" class="btn btn-success btn-opcion-a">Opcion 1</a>
            </div>
            <div class="row"></div>
            <div class="form-group col-md-4 credito">
                <input class="form-control" id="pago_diferido_m" autocomplete="off" name="pago_diferido_m">
                <label for="pago_diferido_m">Pago Diferido Mensual</label>
            </div>
            <div class="form-group col-md-4 credito">
                <input class="form-control" id="pago_diferido_q" autocomplete="off" name="pago_diferido_q">
                <label for="pago_diferido_q">Pago Diferido Quincenal</label>
            </div>
            <div class="form-group col-md-4 credito">
                <a href="#!" class="btn btn-success btn-opcion-b">Opcion 2</a>

            </div>
            <div class="row"></div>
            <div class="form-group col-md-4 credito">
                <input class="form-control" id="no_mensualidades" autocomplete="off" name="no_mensualidades">
                <label for="no_mensualidades">No Mensualidades</label>
            </div>
            <div class="form-group col-md-4 credito">
                <input class="form-control" id="no_quincenas" autocomplete="off" name="no_quincenas">
                <label for="no_quincenas">No Quincenas</label>
            </div>
            <div class="form-group col-md-4 contado hidden">
                <input class="form-control" id="lote" autocomplete="off" name="lote">
                <label for="lote">Lote</label>
            </div>
            <div class="form-group col-md-4 contado hidden">
                <input class="form-control" id="manzana" autocomplete="off" name="manzana">
                <label for="manzana">Manzana</label>
            </div>
            <div class="form-group col-md-4">
                <input class="form-control" id="monto_total" autocomplete="off" name="monto_total">
                <label for="monto_total">Total a pagar</label>
            </div>
            <div class="form-group col-md-6 credito">
                <label class="control-label">Tipo de Pago</label>
                    <div class="col-sm-12">
                        <label class="radio-inline radio-styled">
                            <input type="radio" name="tipo_pago"  checked="checked" value="1"><span>Quincenal</span>
                        </label>
                        <label class="radio-inline radio-styled">
                            <input type="radio" name="tipo_pago" value="2"><span>Mensual</span>
                        </label>
                    </div>
                </div>
                <div class="form-group col-md-6 credito">
                <input class="form-control" id="fecha_primer_pago" autocomplete="off" name="fecha_primer_pago" placeholder="{{date('Y-m-d')}}">
                <label for="fecha_primer_pago">Fecha 1er Pago</label>
                                
                </div>

                <div class="form-group col-md-6 credito">
                <input class="form-control" id="fecha_primer_pago_esp" autocomplete="off" name="fecha_primer_pago_esp" placeholder="{{date('Y-m-d')}}">
                <label for="fecha_primer_pago_esp">Fecha 1er Pago Esp</label>

                </div>
                <div class="form-group col-md-6 credito">
                <input class="form-control" id="fecha_ultimo_pago_esp" autocomplete="off" name="fecha_ultimo_pago_esp" placeholder="{{date('Y-m-d')}}">
                <label for="fecha_ultimo_pago_esp">Fecha Ult Pago Esp</label>
                </div>
</div>


</div>
</div>
        </div>
        <div class="col-md-6">
        <div class="panel-group" id="accordion1">
									<div class="card panel">
										<div class="card-head collapsed" data-toggle="collapse" data-parent="#accordion1" data-target="#accordion1-1">
											<header>Referencias</header>
											<div class="tools">
												<a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
											</div>
										</div>
										<div id="accordion1-1" class="collapse">
											<div class="card-body">
                                                <div class="form-group col-md-8">
                                                    <input type="text" class="form-control" autocomplete="off" id="nombre_a" name="nombre_a" >
                                                    <label for="referencia_a">Referencia 1</label>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <input class="form-control" id="telefono_a" autocomplete="off" name="telefono_a">
                                                    <label for="telefono_a">Telefono 1</label>
                                                </div>
                                                <div class="form-group col-md-8">
                                                    <input type="text" class="form-control" autocomplete="off" id="nombre_b" name="nombre_b">
                                                    <label for="referencia_2">Referencia 2</label>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <input class="form-control" id="telefono_b" autocomplete="off" name="telefono_b">
                                                    <label for="telefono_b">Telefono 2</label>
                                                </div>
                                                <div class="form-group col-md-8">
                                                    <input type="text" class="form-control" autocomplete="off" id="nombre_c" name="nombre_c">
                                                    <label for="referencia_c">Referencia 3</label>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <input class="form-control" id="telefono_c" autocomplete="off" name="telefono_c">
                                                    <label for="telefono_c">Telefono 3</label>
                                                </div>
											</div>
										</div>
									</div><!--end .panel -->
								</div><!--end .panel-group -->
            

            
            <div class="panel-group" id="tabla"><div class="card panel"><div class="card-body">
                <table id="tblPlanesSeleccionados" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>$M</th>
                            <th>$Q</th>
                            <th>#Esp</th>
                            <th>$Esp</th>
                            <th>Tipo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                </div></div></div>
            
        
        <a href="#!" class="btn btn-primary btn-guardar-solicitud" >Guardar</a>
        <a href="{{url('/contrato')}}" class="btn btn-warning btn-limpiar-solicitud" >Limpiar</a>
    </div>
    {{ Form::close() }}
    @include('modal.cliente')

</div>
@include('modal.pago')
<style>
#tblPlanesSeleccionados tbody tr th{ font-size:8pt; padding:0}
#tblPlanesSeleccionados tbody tr td{ font-size:8pt}
</style>
{{ Form::open(['url' => ['clientes/destroy', 'USER_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) }}
{{ Form::close() }}
@section('scripts')
    <script type="text/javascript">
        $('.btn-nuevo').click(function(){
        $('#frm-paciente')[0].reset();
        $('#id_cliente').val(0);
        $('#exampleModal').modal('show');
    });

    $('input:radio[name=tipo_venta]').change(function(){
        $('.credito').removeClass('hidden');
        $('.contado').addClass('hidden');
        if($(this).val()==1)
        {
            $('.credito').addClass('hidden');
            $('.contado').removeClass('hidden');
        }

    });
    function isValidDate(dateString) {
        var regEx = /^\d{4}-\d{2}-\d{2}$/;
        if(!dateString.match(regEx)) return false;  // Invalid format
        var d = new Date(dateString);
        if(Number.isNaN(d.getTime())) return false; // Invalid date
        return d.toISOString().slice(0,10) === dateString;
    }
    $('#fecha_primer_pago').blur(function(){
        if($.trim($(this).val())!=""){
            var fecha=$(this).val().split('-');

            $('#fecha_primer_pago_esp').val(fecha[0]+'-12-'+fecha[2]);
            $('#fecha_ultimo_pago_esp').val(fecha[0]+'-12-'+fecha[2]);
        }

    });

    
    $('.btn-guardar-cliente').click(function(){
      var form=$('#frm-paciente');
      var url=form.attr('action');
      var data=form.serialize();

      $.post(url,data,function(result)
      {
        if(result.cliente_id>0)
        {
            $('#id_cliente').val(result.cliente_id);
            $('#cliente').val($('#nombre').val());
        }
        
        $.toast({
            text: "El cliente ha sido almacenado correctamente", 
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
            beforeShow: function () {}, 
            afterShown: function () {}, 
            beforeHide: function () {}, 
            afterHidden: function () {} 
          });  
          $('#exampleModal').modal('hide');  
          
      }).fail(function()
      {
          alert('Error al guardar el cliente');
      });
  });
        var tblClientes = null;
        var tblPlanes = null;
        var url = "{{ url('clientes/getComboClientes') }}";
        console.log(url);
        $.get(url, function(data) {
            tblClientes = data;
            $('#cliente.typeahead').typeahead({
                onSelect: function(item) {
                    $('#id_cliente').val(item.value);
                },
                source: tblClientes,
                displayField: 'nombre',
                valueField: 'id'
            });
        });
        var url = "{{ url('planes/getComboPlanes') }}";
        console.log(url);
        $.get(url, function(data) {
            tblPlanes = data;
            $('#plan.typeahead').typeahead({
                onSelect: function(item) {
                    cargarPlan(item.value);
                    setTimeout(function() {
                        $('#plan').val('');
                    }, 200);
                },
                source: tblPlanes,
                displayField: 'nombre',
                valueField: 'id'
            });
        });

        function cargarPlan(id) {
            var item = tblPlanes.find(elem => elem.id == id);
            if (item) {
                var pac = item;
                $('#id_plan').val(pac.id);
                $('#descripcion').val(pac.descripcion + ' ' + pac.medidas);
                $('#enganche').val(pac.enganche);
                $('#pago_mensual').val(pac.pago_mensual);
                $('#pago_quincenal').val(pac.pago_quincenal);
                $('#no_pagos_esp').val(pac.no_pagos_esp);
                $('#pago_esp').val(pac.pago_esp);
                $('#pago_diferido_m').val(pac.pago_diferido_m);
                $('#pago_diferido_q').val(pac.pago_diferido_q);
                $('#no_mensualidades').val(pac.no_mensualidades);
                $('#no_quincenas').val(pac.no_quincenas);
                $('#monto_total').val(pac.monto_total);
            }
        }

        function checkPlanes()
        {
            band=false;
            $('input[name="plan_seleccionado[]"]').each(function(){
                var id=$(this).val().split('|')[0];
                if(id==$('#id_plan').val())
                {
                    band=true;
                }
            });
            return band;
        }

        $('.btn-opcion-a').click(function(){
            // if(checkPlanes())
            // {
            //     return false;
            // }
            var html='<tr>';
            html +='<td style="font-size:8pt"><input type="hidden" name="plan_seleccionado[]" value="'+$('#id_plan').val()+'|1|';
            html +=$('#pago_mensual').val()+'|'+$('#pago_quincenal').val()+'|';
            html +=$('#no_pagos_esp').val()+'|'+$('#pago_esp').val()+'|'+$('#no_mensualidades').val()+'|'+$('#no_quincenas').val()+'|'+$('#monto_total').val()+'|'+$('#enganche').val()+'" />'+$('#descripcion').val()+'</td>';
            html +='<td>'+$('#pago_mensual').val()+'</td>';
            html +='<td>'+$('#pago_quincenal').val()+'</td>';
            html +='<td>'+$('#no_pagos_esp').val()+'</td>';
            html +='<td>'+$('#pago_esp').val()+'</td>';
            html +='<td>Opcion 1</td>';
            html +='<td><a href="#!" class="btn-eliminar btn btn-danger" ><i class="fa fa-trash-o"></i></a></td>';
            html +='</tr>';

            $('#tblPlanesSeleccionados').append(html);
        });
        $('.btn-opcion-b').click(function(){
            // if(checkPlanes())
            // {
            //     return false;
            // }
            var html='<tr>';
            html +='<td style="font-size:8pt"><input type="hidden" name="plan_seleccionado[]" value="'+$('#id_plan').val()+'|2|';
            html +=$('#pago_diferido_m').val()+'|'+$('#pago_diferido_q').val()+'|0|0'+'|'+$('#no_mensualidades').val()+'|'+$('#no_quincenas').val()+'|'+$('#monto_total').val()+'|'+$('#enganche').val()+'" />'+$('#descripcion').val()+'</td>';
            html +='<td>'+$('#pago_diferido_m').val()+'</td>';
            html +='<td>'+$('#pago_diferido_q').val()+'</td>';
            html +='<td>0</td>';
            html +='<td>0</td>';
            html +='<td>Opcion 2</td>';
            html +='<td><a href="#!" class="btn-eliminar btn btn-danger" ><i class="fa fa-trash-o"></i></a></td>';
            html +='</tr>';

            $('#tblPlanesSeleccionados').append(html);
        });

        $('#tblPlanesSeleccionados tbody').on('click','tr td .btn-eliminar',function(){
            var row=$(this).parents('tr');
            $(row).remove();
        });
        $('.btn-guardar').click(function(){//Guardar solicitud de contado
            var mensaje='';
            var total_planes=$('input[name="plan_seleccionado[]"]').length;
            if(Number($('#id_cliente').val())==0)
            {
                mensaje +='Debe seleccionar un cliente<br/>';
            }
           

    var form=$('#frm-contrato');
    var url=form.attr('action');
    var data=form.serialize()+'&importe='+$('#importe').val()+'&forma_pago='+$('#forma_pago').val();
    data +='&institucion_bancaria='+$('#institucion_bancaria').val()+'&fecha_pago='+$('#fecha_pago').val();
    data +='&ticket='+$('#ticket').val();


      $.post(url,data,function(result)
      {
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
            beforeShow: function () {}, 
            afterShown: function () {}, 
            beforeHide: function () {}, 
            afterHidden: function () {} 
          });  
          if(result.error<=0)
          {
            $('#pagoModal').modal('hide');
              if(confirm('Desea imprimir el recibo de la venta?'))
              {
                var form = $('#frm-contrato');
                var url = form.attr('action').replace('contratos/create', 'pagos/recibo_contado');
                
                console.log(url);
                var data = form.serialize()+'&id_contrato='+result.id;
                console.log(data);
                $.post(url, data, function(result) {
                    console.log(result);
                    var url="{{url('/contrato')}}";
                    
                    var printWindow = window.open('', '', 'height=400,width=800');

                    printWindow.document.write(result.html);
                    printWindow.document.close();
                    
                    printWindow.onload = function() {
                        printWindow.print();
                        
                    }
                    window.location.href = url;



                }).fail(function() {
                    alert('Error al imprimir el recibo');
                });
              }
          }
          
      }).fail(function()
      {
          alert('Error al guardar la intencion de compra');
      });
        });
        $('.btn-guardar-solicitud').click(function(){
            var mensaje='';
            var total_planes=$('input[name="plan_seleccionado[]"]').length;
            if(Number($('#id_cliente').val())==0)
            {
                mensaje +='Debe seleccionar un cliente<br/>';
            }

            var tipo_venta=$('input:radio[name=tipo_venta]:checked').val();
            if(tipo_venta==1)
            {
                $('#importe').val($('#monto_total').val());
                $('#pagoModal').modal('show');
                return false;
            }
            if($.trim($('#nombre_a').val())=='')
            {
                mensaje +='Debe agregar al menos una referencia<br/>';
            }
            if(total_planes<=0)
            {
                mensaje +='Debe seleccionar al menos un plan de financiamiento<br/>';
            }
            if(!isValidDate($('#fecha_primer_pago').val()))
            {
                mensaje +='Fecha de primer pago invalido<br>';
            }
            if(!isValidDate($('#fecha_primer_pago_esp').val()))
            {
                mensaje +='Fecha de primer pago especial invalido<br>';
            }
            if(!isValidDate($('#fecha_ultimo_pago_esp').val()))
            {
                mensaje +='Fecha de ultimo pago especial invalido<br>';
            }
            if(mensaje!='')
            {
                $.toast({
                    text: mensaje, 
                    heading: 'ERROR', 
                    icon: 'danger', 
                    showHideTransition: 'fade', 
                    allowToastClose: true, 
                    hideAfter: 3000, 
                    stack: 5, 
                    position: 'top-right', 
                    textAlign: 'left',  
                    loader: false,  
                    loaderBg: '#9EC600',  
                    beforeShow: function () {}, 
                    afterShown: function () {}, 
                    beforeHide: function () {}, 
                    afterHidden: function () {} 
                });  
                return false;
            }

        var form=$('#frm-contrato');
      var url=form.attr('action');
      var data=form.serialize();

      $.post(url,data,function(result)
      {
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
            beforeShow: function () {}, 
            afterShown: function () {}, 
            beforeHide: function () {}, 
            afterHidden: function () {} 
          });  
          
      }).fail(function()
      {
          alert('Error al guardar la intencion de compra');
      });
        });
mensaje=(message,color)=>{
    $.toast({
            text: message, 
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
            beforeShow: function () {}, 
            afterShown: function () {}, 
            beforeHide: function () {}, 
            afterHidden: function () {} 
          });  
}
    </script>
@endsection
@stop
