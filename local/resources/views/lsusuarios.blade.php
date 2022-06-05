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

        {{Form::open(array('url' => 'usuarios/buscar','method' => 'POST','id'=>'form-buscar'))}}
        <div class="col-md-6">
            <a class="btn btn-success btn-nuevo" >Nuevo Usuario <i
                                    class="md md-person"></i></a>
        </div>
        <div class="col-xs-6">
          <div class="input-group">
            <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre" class="form-control">
            <span class="input-group-btn"><button class="btn btn-buscar btn-default" type="button"><i class="fa fa-search"></i></button></span>
          </div>

        </div>
        {{ Form::close() }}
        
      </div>
      <input type="hidden" id="acciones" name="acciones" value="{{Session::get('menu')['CLIENTES-ED'].'-'.Session::get('menu')['CLIENTES-EL']}}" />
      @section ('htable_panel_title','Lista de usuarios')
      @section ('htable_panel_body')

      <table id="tblClientes" class="table table-hover">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Telefono</th>
            <th>Rol</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($usuarios as $usuario)
          <tr data-id="{{$usuario['id']}}" data-rolid="{{$usuario['tbl_rol_id']}}">
            <td>{{$usuario['usuario']}}</td>
            <td>{{$usuario['nombre']}}</td>
            <td>{{$usuario['email']}}</td>
            <td>{{$usuario['telefono']}}</td>
            <td>{{$usuario['descripcion']}}</td><!--Referencias-->
            <td>
            <a href="#!" class="btn-editar btn btn-primary"><i class="fa fa-edit"></i></a>
            </td>
            <td>
            <a href="#!" class="btn-delete btn btn-danger"><i class="fa fa-trash-o"></i></a>
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
  @include('modal.usuario')
                        
                        
                        
  {{Form::open(array('url' => ['usuarios/destroy','USER_ID'],'method' => 'DELETE','id'=>'form-delete'))}}
  {{ Form::close() }}
</div>
  
@section('scripts')
<script type="text/javascript">
var row=new Object();
$(document).ready(function()
{
  $('.btn-nuevo').click(function(){
    $('#frm-usuario')[0].reset();
    $('#id_usuario').val(0);
    $('#exampleModal').modal('show');
  });

  //Cargamos los roles
    var form = $('#form-delete');
    var url = form.attr('action').replace('usuarios/destroy', 'config/getRoles');
    console.log(url);
    var data = null;
    $.get(url, function(result) {
        console.log(result);
        $.each(result,function(index,item){
            $('#tbl_rol_id').append(`<option value="${item.id}">${item.descripcion}</option>`)
        });
    }).fail(function(err) {
        console.log(err);
    });
  

  $('.btn-guardar-usuario').click(function(){
        var isValid=$('#frm-usuario')[0].checkValidity();
        console.log(isValid);
        if(!isValid)
        {
            $("#frm-usuario")[0].reportValidity();
            return false;
        }
    
      var form=$('#frm-usuario');
      var url=form.attr('action');
      var data=form.serialize();

      $.post(url,data,function(result)
      {
         mensaje(result.mensaje,result.color);
          $('#exampleModal').modal('hide'); 
          if(result.error==0)
          {
              buscar(); 
          } 
      }).fail(function()
      {
          alert('Error al guardar el usuario');
      });
  });
  function mensaje(mensaje,color)
  {
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
            beforeShow: function () {}, 
            afterShown: function () {}, 
            beforeHide: function () {}, 
            afterHidden: function () {} 
          }); 
  }
  $('#tblClientes tbody ').on('click','tr td .btn-delete',function(){
      var row=$(this).parents('tr');
      var id=row.data('id');
      var form=$('#form-delete');
      var url=form.attr('action').replace('USER_ID',id);
      var data=form.serialize();
      if(!confirm('¿Desea eliminar el usuario?'))
      {
        return false;
      }
      $.post(url,data,function(result)
      {
          mensaje(result,'success');
          row.fadeOut();
      }).fail(function()
      {
          alert('Ocurrio un error al intentar eliminar el usuario');
      });

    });
    $('#tblClientes tbody ').on('click','tr td .btn-editar',function(){
      var row=$(this).parents('tr');
      var id=row.data('id');
      var form=$('#form-delete');
      var url=form.attr('action').replace('destroy','show').replace('USER_ID',id);
      var data=form.serialize();
      $('#frm-usuario')[0].reset();
      $('#id_usuario').val(0);
      $.post(url,data,function(result)
      {
          console.log(result);
          var pac=result[0];
          $('#id_usuario').val(pac.id);
          $('#usuario').val(pac.usuario);
          $('#nombre').val(pac.nombre);
          $('#email').val(pac.email);
          $('#telefono').val(pac.telefono);
          $('#tbl_rol_id').val(pac.tbl_rol_id);
          
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
        html +='<td>'+arr[i].usuario+'</td>';
        html +='<td>'+arr[i].nombre +'</td>';
        html +='<td>'+arr[i].email+'</td>';
        html +='<td>'+arr[i].telefono+'</td>';
        html +='<td>'+arr[i].descripcion+'</td>'; //Referencias
        html +='<td><a href="#!" class="btn-editar btn btn-primary" ><i class="fa fa-edit"></i></a></td>';
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
