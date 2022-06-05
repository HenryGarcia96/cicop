@extends ('layouts.dashboard')
@section('page_heading','Clientes')



@section('section')


<div class="col-sm-12">
  <div class="row">
    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))
      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
      @endif
      @endforeach
    </div>
    <div class="col-md-12">



      <div class="row">

        {{Form::open(array('url' => 'clientes/buscar','method' => 'POST','id'=>'form-buscar'))}}
        <div class="col-md-6">
            <a class="btn btn-success btn-nuevo" >Nuevo Cliente <i
                                    class="md md-person"></i></a>
        </div>
        <div class="col-xs-6">
          <div class="input-group">
            <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre, apellidos" class="form-control">
            <span class="input-group-btn"><button class="btn btn-buscar btn-default" type="button"><i class="fa fa-search"></i></button></span>
          </div>

        </div>
        {{ Form::close() }}
        
      </div>
      <input type="hidden" id="acciones" name="acciones" value="{{Session::get('menu')['CLIENTES-ED'].'-'.Session::get('menu')['CLIENTES-EL']}}" />
      @section ('htable_panel_title','Lista de clientes')
      @section ('htable_panel_body')

      <table id="tblClientes" class="table table-hover">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Telefonos</th>
            <th>Email</th>
            <th>Direccion</th>
            <th>Referencias</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($clientes as $cliente)
          <tr data-id="{{$cliente['id']}}">
            <td>{{$cliente['cliente']}}</td>
            <td>{{$cliente['celular']}}</td>
            <td>{{$cliente['email']}}</td>
            <td>{{$cliente['calle']}}</td>
            <td>{{$cliente['direccion2']}}</td><!--Referencias-->
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
  @include('modal.cliente')
                        
                        
                        
  {{Form::open(array('url' => ['clientes/destroy','USER_ID'],'method' => 'DELETE','id'=>'form-delete'))}}
  {{ Form::close() }}
</div>
  
@section('scripts')
<script type="text/javascript">
var row=new Object();
$(document).ready(function()
{
  $('.btn-nuevo').click(function(){
    $('#frm-paciente')[0].reset();
    $('#id_cliente').val(0);
    $('#exampleModal').modal('show');
  });
  $('.btn-guardar-cliente').click(function(){
      var form=$('#frm-paciente');
      var url=form.attr('action');
      var data=form.serialize();

      $.post(url,data,function(result)
      {
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
          buscar(); 
      }).fail(function()
      {
          alert('Error al guardar el cliente');
      });
  });
  $('#tblClientes tbody ').on('click','tr td .btn-delete',function(){
      var row=$(this).parents('tr');
      var id=row.data('id');
      var form=$('#form-delete');
      var url=form.attr('action').replace('USER_ID',id);
      var data=form.serialize();
      if(!confirm('¿Desea eliminar al cliente?'))
      {
        return false;
      }
      $.post(url,data,function(result)
      {
          alert(result);
          row.fadeOut();
      }).fail(function()
      {
          alert('Cliente no eliminado');
      });

    });
    $('#tblClientes tbody ').on('click','tr td .btn-editar',function(){
      var row=$(this).parents('tr');
      var id=row.data('id');
      var form=$('#form-delete');
      var url=form.attr('action').replace('destroy','show').replace('USER_ID',id);
      var data=form.serialize();
      $('#frm-paciente')[0].reset();
      $('#id_cliente').val(0);
      $.post(url,data,function(result)
      {
          console.log(result);
          var pac=result[0];
          $('#id_paciente').val(pac.id);
          $('#nombre').val(pac.cliente);
          $('#direccion').val(pac.calle);
          $('#referencia').val(pac.direccion2);
          $('#telefono').val(pac.celular);
          $('#email').val(pac.email);
          if(pac.rfc){
            $(":checkbox[value="+pac.rfc+"]").prop("checked","true");
          }
          $(":radio[value="+pac.tipo+"]").prop("checked","true");
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

      $('#tblClientes tbody tr').remove();
      var html='';
      var arr=result;
      var fila='';
      var aux=0;

      for(i=0;i<arr.length;i++)
      {

        html +='<tr data-id="'+arr[i].id+'" >';
        html +='<td>'+arr[i].cliente+'</td>';
        html +='<td>'+arr[i].celular +'</td>';
        html +='<td>'+arr[i].email+'</td>';
        html +='<td>'+arr[i].calle+'</td>';
        html +='<td>'+arr[i].direccion2+'</td>'; //Referencias
        
        if(acc[0]==1)
        html +='<td><a href="#!" class="btn-editar btn btn-primary" ><i class="fa fa-edit"></i></a></td>';
        if(acc[1]==1)
        html +='<td><a href="#!" class="btn-delete btn btn-danger" ><i class="fa fa-trash-o"></i></a></td>';
        html +='</tr>';

        $('#tblClientes').append(html);
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
