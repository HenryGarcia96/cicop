@extends ('layouts.dashboard')
@section('page_heading','Planes de Financiamiento')



@section('section')


<div class="col-sm-12">
  <div class="row">

    <div class="col-md-12">



      <div class="row">

        {{Form::open(array('url' => 'planes/buscar','method' => 'POST','id'=>'form-buscar'))}}
        <div class="col-md-6">
            <a class="btn btn-success btn-nuevo" >Nuevo Plan <i
                                    class="md md-person"></i></a>
        </div>
        <div class="col-xs-6">
          <div class="input-group">
            <input type="text" id="buscar" name="buscar" placeholder="Buscar por descripcion" class="form-control">
            <span class="input-group-btn"><button class="btn btn-buscar btn-default" type="button"><i class="fa fa-search"></i></button></span>
          </div>

        </div>
        {{ Form::close() }}
        
      </div>
      <input type="hidden" id="acciones" name="acciones" value="{{Session::get('menu')['CLIENTES-ED'].'-'.Session::get('menu')['CLIENTES-EL']}}" />
      @section ('htable_panel_title','Lista de planes')
      @section ('htable_panel_body')

      <table id="tblPlanes" class="table table-hover">
        <thead>
          <tr>
            <th>Descripcion</th>
            <th>Medidas</th>
            <th>Enganche</th>
            <th>$ M</th>
            <th>$ Q</th>
            <th>No.PE</th>
            <th>PE</th>
            <th>Dif M</th>
            <th>Dif Q</th>
            <th>Mensualidad</th>
            <th>Quincena</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($planes as $plan)
          <tr data-id="{{$plan['id']}}">
            <td>{{$plan['descripcion']}}</td>
            <td>{{$plan['medidas']}}</td>
            <td>{{$plan['enganche']}}</td>
            <td>{{$plan['pago_mensual']}}</td>
            <td>{{$plan['pago_quincenal']}}</td>
            <td>{{$plan['no_pagos_esp']}}</td>
            <td>{{$plan['pago_esp']}}</td>
            <td>{{$plan['pago_diferido_m']}}</td>
            <td>{{$plan['pago_diferido_q']}}</td>
            <td>{{$plan['no_mensualidades']}}</td>
            <td>{{$plan['no_quincenas']}}</td>
            <td>{{$plan['monto_total']}}</td>
            <td>
            @if(Session::get('menu')['CLIENTES-ED']==1)
            <a href="#!" class="btn-editar btn btn-primary"><i class="fa fa-edit"></i></a>
            @endif
            </td>
            <td>
            @if(Session::get('menu')['CLIENTES-EL']==1)
                    <a href="#!" class="btn-delete btn btn-danger"><i class="fa fa-trash-o"></i></a>
            @endif
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>

      @endsection

      @include('widgets.panel', array('header'=>true, 'as'=>'htable'))
    </div>

    <!--</form>-->
  </div>
  <!--Inicia modal -->
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Plan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    {{Form::open(array('url' => 'planes/create','method' => 'POST','id'=>'frm-planes','class'=>'form'))}}
                                          <input type="hidden" id="id_plan" name="id_plan" value="0" />

                                            <div class="form-group col-md-12">
                                                <input type="text" autocomplete="off" class="form-control" id="descripcion" name="descripcion">
                                                <label for="descripcion">Descripcion</label>
                                            </div>


                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" autocomplete="off" id="medidas" name="medidas">
                                                <label for="medidas">Medidas</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" autocomplete="off" id="enganche" name="enganche">
                                                <label for="enganche">Enganche</label>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="pago_mensual" autocomplete="off" name="pago_mensual">
                                                <label for="pago_mensual">Pago Mensual</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="pago_quincenal" autocomplete="off" name="pago_quincenal">
                                                <label for="pago_quincenal">Pago Quincenal</label>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="no_pagos_esp" autocomplete="off" name="no_pagos_esp">
                                                <label for="no_pagos_esp">No Pagos Especiales</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="pago_esp" autocomplete="off" name="pago_esp">
                                                <label for="pago_esp">Pago Especial</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="pago_diferido_m" autocomplete="off" name="pago_diferido_m">
                                                <label for="pago_diferido_m">Pago Diferido Mensual</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="pago_diferido_q" autocomplete="off" name="pago_diferido_q">
                                                <label for="pago_diferido_q">Pago Diferido Quincenal</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="no_mensualidades" autocomplete="off" name="no_mensualidades">
                                                <label for="no_mensualidades">No Mensualidades</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="no_quincenas" autocomplete="off" name="no_quincenas">
                                                <label for="no_quincenas">No Quincenas</label>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input class="form-control" id="monto_total" autocomplete="off" name="monto_total">
                                                <label for="monto_total">Total a pagar</label>
                                            </div>
                                            

                                        {{ Form::close()}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary btn-guardar">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Termina modal -->
                        
                        
                        
  {{Form::open(array('url' => ['planes/destroy','USER_ID'],'method' => 'DELETE','id'=>'form-delete'))}}
  {{ Form::close() }}
</div>
@section('scripts')
<script type="text/javascript">
var row=new Object();
$(document).ready(function()
{
  $('.btn-nuevo').click(function(){
    $('#frm-planes')[0].reset();
    $('#id_plan').val(0);
    $('#exampleModal').modal('show');
  });
  $('.btn-guardar').click(function(){
      var form=$('#frm-planes');
      var url=form.attr('action');
      var data=form.serialize();

      $.post(url,data,function(result)
      {
        $.toast({
            text: "El plan ha sido almacenado correctamente", 
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
          buscar(); 
      }).fail(function()
      {
          alert('Error al guardar el plan');
      });
  });
  $('#tblPlanes tbody ').on('click','tr td .btn-delete',function(){
      var row=$(this).parents('tr');
      var id=row.data('id');
      var form=$('#form-delete');
      var url=form.attr('action').replace('USER_ID',id);
      var data=form.serialize();
      if(!confirm('¿Desea eliminar el plan?'))
      {
        return false;
      }
      $.post(url,data,function(result)
      {
          alert(result);
          row.fadeOut();
      }).fail(function()
      {
          alert('Plan no eliminado');
      });

    });
    $('#tblPlanes tbody ').on('click','tr td .btn-editar',function(){
      var row=$(this).parents('tr');
      var id=row.data('id');
      var form=$('#form-delete');
      var url=form.attr('action').replace('destroy','show').replace('USER_ID',id);
      var data=form.serialize();
      $('#frm-planes')[0].reset();
      $('#id_plan').val(0);
      $.post(url,data,function(result)
      {
          console.log(result);
          var pac=result[0];
          $('#id_plan').val(pac.id);
          $('#descripcion').val(pac.descripcion);
          $('#medidas').val(pac.medidas);
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
          $('#exampleModal').modal('show');
      }).fail(function(xhr, status, error) {
        console.log(xhr,status,error);
      });

    });

  $('#buscar').keyup(function(e)
  {
    if(e.which ==13)
    {
      buscar();
    }
  });

    $('.btn-buscar').click(function()
    {
      buscar();
    });


  });

  function buscar()
  {
    var form=$('#form-buscar');
    var url=form.attr('action');
    

    console.log(url);

    var data=form.serialize();
    var acc=$('#acciones').val().split('-');
    $.post(url,data,function(result)
    {

      $('#tblPlanes tbody tr').remove();
      var html='';
      var arr=result;
      var fila='';
      var aux=0;

      for(i=0;i<arr.length;i++)
      {

        html +='<tr data-id="'+arr[i].id+'" >';
        html +='<td>'+arr[i].descripcion+'</td>';
        html +='<td>'+arr[i].medidas +'</td>';
        html +='<td>'+arr[i].enganche +'</td>';
        html +='<td>'+arr[i].pago_mensual+'</td>';
        html +='<td>'+arr[i].pago_quincenal+'</td>';
        html +='<td>'+arr[i].no_pagos_esp+'</td>';
        html +='<td>'+arr[i].pago_esp+'</td>';
        html +='<td>'+arr[i].pago_diferido_m+'</td>';
        html +='<td>'+arr[i].pago_diferido_q+'</td>';
        html +='<td>'+arr[i].no_mensualidades+'</td>';
        html +='<td>'+arr[i].no_quincenas+'</td>';
        html +='<td>'+arr[i].monto_total+'</td>';
        if(acc[0]==1)
        html +='<td><a href="#!" class="btn-editar btn btn-primary" ><i class="fa fa-edit"></i></a></td>';
        if(acc[1]==1)
        html +='<td><a href="#!" class="btn-delete btn btn-danger" ><i class="fa fa-trash-o"></i></a></td>';
        html +='</tr>';

        $('#tblPlanes').append(html);
        html='';
      }

    }).fail(function(err)
    {
      console.log(err);
      alert('No se completo la busqueda');
    });
  }

  function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
  }
  document.onkeypress = stopRKey;
  </script>
  @endsection
  @stop
