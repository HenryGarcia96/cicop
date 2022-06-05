@extends ('layouts.dashboard')
@section('page_heading','Lineas de Productos')

@section('section')


<div class="col-sm-12">

<div class="row">
    <div class="col-lg-6">
        <!-- <form role="form">-->
        <div class="flash-message">
  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
    @endif
  @endforeach
</div>
        @if(isset($rol1))
        {{Form::open(array('id'=>'frmlineas','url' => ['lineas/edit',$rol1['id']],'method' => 'DELETE','class'=>'has-validation-callback'))}}
        @else
        {{Form::open(array('id'=>'frmlineas','url' => 'lineas/create','method' => 'POST','class'=>'has-validation-callback'))}}
        @endif
        <input class="form-control" id='idrol' name='idrol' type="hidden" @if(isset($rol1))
                    value="{{ $rol1['id'] }}"
                @endif
                >
            <div class="form-group">
                <label>Descripci&oacute;n</label>
                <input class="form-control" id='descripcion' name='descripcion' data-validation="required"
                @if(isset($rol1))
                    value="{{ $rol1['descripcion'] }}"
                @endif
                >
            </div>
            <a href="{{url('/lineas')}}" class="btn btn-success">Nuevo</a>
            @if(Session::get('menu')['LINEAS-G']==1)
            <button type="submit" class="btn btn-primary">Guardar</button>
            @endif
            <a href="{{url('/')}}" class="btn btn-warning">Cancelar</a>


           {{Form::close() }}
           </div>
        <div class="col-sm-6">

        @section ('htable_panel_title','Lista de lineas de productos')
        @section ('htable_panel_body')

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Descripcion</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($roles as $rol)
                <tr data-id="{{$rol['id']}}">
                    <td>{{$rol['descripcion']}}</td>
                    <td>
                      @if(Session::get('menu')['LINEAS-ED']==1)
                    <a href="{{url('/lineas/show/'.$rol['id'])}}" class="btn-editar btn btn-primary"><i class="fa fa-edit"></i></a>
                    @endif
                    </td>
                    <td>
                    @if(isset($rol1) && Session::get('menu')['LINEAS-EL']==1)
                        @if ($rol['id']!=$rol1['id'] )
                            <a href="#!" class="btn-delete btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        @endif
                    @elseif (Session::get('menu')['LINEAS-EL']==1)
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
    {{Form::open(array('url' => ['lineas/destroy','LINEA_ID'],'method' => 'DELETE','id'=>'form-delete'))}}
    {{ Form::close() }}
</div>
@section('scripts')
<script type="text/javascript">
$(function() {


  });
    $(document).ready(function()
    {

        $('.btn-delete').click(function()
        {
            var row=$(this).parents('tr');
            var id=row.data('id');
            var form=$('#form-delete');
            var url=form.attr('action').replace('LINEA_ID',id);
            var data=form.serialize();

            $.post(url,data,function(result)
            {
                alert(result.mensaje);
                if(result.error==0)
                {
                row.fadeOut();
                }
            }).fail(function()
            {
                alert('Linea no eliminada');
            });

        });

        $('.btnGuardar').click(function()
        {
            var form=$('#form-permisos');
            var data=form.serialize();
            var url=form.attr('action');
            $.post(url,data,function(result)
            {
                alert(result.mensaje);
            }).fail(function(respuesta)
            {
                console.log(respuesta);
                alert('Permisos no actualizados');
            });
            $('#myModal').modal("hide");
        });
    });

    (function($, window) {

        var dev = '.dev'; //window.location.hash.indexOf('dev') > -1 ? '.dev' : '';
        window.applyValidation = function(validateOnBlur, forms, messagePosition, xtraModule) {
            if( !forms )
                forms = 'form';
            if( !messagePosition )
                messagePosition = 'top';

            $.validate({
                form : forms,
                language : {
                    requiredFields: '*Campo requerido'
                },
                validateOnBlur : validateOnBlur,
                errorMessagePosition : messagePosition,
                scrollToTopOnError : true,
                lang : 'es',
                sanitizeAll : 'trim', // only used on form C
               // borderColorOnError : 'purple',
                modules : 'security'+dev+', location'+dev+', sweden'+dev+', file'+dev+', uk'+dev+' , brazil'+dev +( xtraModule ? ','+xtraModule:''),
                /*onModulesLoaded: function() {
                    $('#country-suggestions').suggestCountry();
                    $('#swedish-county-suggestions').suggestSwedishCounty();
                    $('#password').displayPasswordStrength();
                },*/
                /*onValidate : function($f) {

                    console.log('about to validate form '+$f.attr('id'));

                    var $callbackInput = $('#callback');
                    if( $callbackInput.val() == 1 ) {
                        return {
                            element : $callbackInput,
                            message : 'This validation was made in a callback'
                        };
                    }
                },*/
                onError : function($form) {
                    //alert('Invalid '+$form.attr('id'));
                    return false;
                }/*,
                onSuccess : function($form) {
                    alert('Valid '+$form.attr('id'));
                    //return false;
                }*/
            });
        };

        window.applyValidation(true, '#frmlineas', 'element');

        // Load one module outside $.validate() even though you do not have to
        $.formUtils.loadModules('date'+dev+'.js', false, false);


    })(jQuery, window);

    </script>
@endsection
@stop
