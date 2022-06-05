@extends ('layouts.dashboard')
@section('page_heading', 'Corte diario')

@section('section')
<div class="col-sm-12">
    <div class="row">

        <div class="col-md-12">
            <div class="row">
                {{ Form::open(['url' => 'reporte/buscar_corte', 'method' => 'POST', 'id' => 'form-buscar']) }}
                <div class="form-group col-md-3">
                    <div class="input-daterange input-group" id="demo-date-range">
                        <div class="input-group-content">
                            <input type="text" class="form-control" id="start" name="start" placeholder="Fecha inicio"
                                value="{{date('Y-m-d')}}" />
                            <div class="form-control-line"></div>
                        </div>
                        <span class="input-group-addon">to</span>
                        <div class="input-group-content">
                            <input type="text" class="form-control" id="end" name="end" placeholder="Fecha fin"
                                value="{{date('Y-m-d')}}" />
                            <div class="form-control-line"></div>
                        </div>
                    </div>
                </div>
                <span class="input-group-btn"><button class="btn btn-buscar btn-default" type="button"><i
                                    class="fa fa-search"></i></button>
                                    <button class="btn btn-download btn-default" type="button"><i
                                    class="fa fa-download"></i></button>
                                    </span>          
                {{ Form::close() }}
                <div class="col-md-3"><label id="totales"></label></div>
            </div>
            <div class="form-group">
                <a href="#!" id="btn-descargar" class="btn btn-success hidden" ><i class="fa fa-download"></i>&nbsp;Descargar</a>
                <a href="#!" id="btn-imprimir" class="btn btn-primary hidden" ><i class="fa fa-print"></i>&nbsp;Imprimir</a>
            </div>
            <input type="hidden" id="acciones" name="acciones"
                value="{{ Session::get('menu')['CLIENTES-ED'] . '-' . Session::get('menu')['CLIENTES-EL'] }}" />
            <div class="card">
                <div class="card-body">
                    <div id="result" class="table-responsive">
                        <table id="tblPlanes" class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Cliente</th>
                                    <th>Mza</th>
                                    <th>Lote</th>
                                    <th>Fecha Pago</th>
                                    <th>Fecha Registro</th>
                                    <th>Correspondiente</th>
                                    <th>Tipo Pago</th>
                                    <th>Institucion Bancaria</th>
                                    <th>Forma pago</th>
                                    <th>Ticket</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--</form>-->
    </div>
    

    {{ Form::open(['url' => ['contratos/destroy', 'USER_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) }}
    {{ Form::close() }}
</div>
<style>
    table tbody tr td{font-size:8pt}
</style>
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
    
    $('#btn-descargar').click(function(e){
        url="{{ asset('local/reportes/vencidos.xls')}}";
        e.preventDefault();  //stop the browser from following
        window.location.href = url;
    });
    
    $('#buscar').keyup(function(e) {
        if (e.which == 13) {
            buscar();
        }
    });

    $('.btn-buscar').click(function() {
        buscar();
    });

    $('.btn-download').click(function(e){
        url="{{ asset('local/reportes/corte.xls')}}";
        e.preventDefault();  //stop the browser from following
        window.location.href = url;
    });

});

function buscar() {
    var form = $('#form-buscar');
    var url = form.attr('action');


    console.log(url);

    var data = form.serialize();
    
    $.post(url, data, function(result) {
        $('#tblPlanes tbody tr').remove();
        var html = '';
        var arr = result;
        var fila = '';
        var aux = 0;

        console.log(result);
        let total=0;
        $.each(result,function(index,item){
            var html=`<tr>
                                    <td>${item.folio}</td>
                                    <td>${item.nombre}</td>
                                    <td>${item.manzana}</td>
                                    <td>${item.lote}</td>
                                    <td>${item.fecha_pago.substr(0,10)}</td>
                                    <td>${item.fecha_registro}</td>
                                    <td>${item.fecha.substr(0,10)}</td>
                                    <td>${item.descripcion}</td>
                                    <td>${item.institucion_bancaria}</td>
                                    <td>${item.forma_pago}</td>
                                    <td>${item.ticket}</td>
                                    <td class="moneda">${item.importe}</td>
                                </tr>`;
            total +=Number(item.importe);
            $('#tblPlanes').append(html);
            html='';
        });
        $('#totales').html('Totales: <span class="moneda">'+total+'</span>');

        $('.moneda').formatCurrency();
    }).fail(function(err) {
        console.log(err);
        alert('No se completo la busqueda');
    });
}


buscar();
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