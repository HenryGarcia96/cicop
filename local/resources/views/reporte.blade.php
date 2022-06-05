@extends ('layouts.dashboard')
@section('page_heading', 'Reporte de pagos vencidos')



@section('section')


<div class="col-sm-12">
    <div class="row">

        <div class="col-md-12">
            <div class="row">
                {{ Form::open(['url' => 'reporte/buscar', 'method' => 'POST', 'id' => 'form-buscar']) }}
                
                <div class="form-group col-md-1"><label><input type="checkbox" id="incluirFechas"
                            name="incluirFechas">Incluir Fecha</label></div>
                <div class="form-group col-md-3">
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
                        <select class="form-control" id="tipo" name="tipo">
                        <option value="0">Todos</option>    
                        <option value="1">Quincenas</option>
                        <option value="2">Mensualidades</option>
                        <option value="3">Especiales</option>
                        </select>
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
                                    <th>Fecha de Pago</th>
                                    <th>Tipo Pago</th>
                                    <th>$</th>
                                    <th></th>
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
    
$('#btn-descargar').click(function(e){
    url="{{ asset('local/reportes/vencidos.xls')}}";
    e.preventDefault();  //stop the browser from following
    window.location.href = url;
});
    $('#btn-imprimir').click(function() {
        
        

        html=`<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>Carta</title>
			<style>
			body{ font-family: Arial, Helvetica, sans-serif; font-size:10pt; margin:10mm}
			div{ height:20px; width:100%; display:inline-block}
			table tr td{ height:40px; }
			div.fecha{ text-align:right}
			table{ width:70%}
			label{ width:29%; display:inline-block}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{ width:100%; text-align:center; position:absolute; bottom:0}
			.titulo{ wodth:100%;text-align:center; position:absolute; top:30mm}
			.rosa_vientos{width:100%; text-align:center}
			.tres{ width:32%; display:inline-block}
            table{width:100%}
            table tbody tr td {font-size:10pt}
            table thead tr th {font-size:10pt}
			</style></head>
            <body><img src="./local/logos/superior_sicop.png" style="width:100%">
            <div class="titulo"><b>REPORTE DE PAGOS VENCIDOS</b></div>`;


            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write(html+$('#result').html()+'</body></html>');
            printWindow.document.close();
            printWindow.onload = function() {

                printWindow.print();
            }

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

        console.log(result);
        addItems(result.especiales);
        addItems(result.normales);
    }).fail(function(err) {
        console.log(err);
        alert('No se completo la busqueda');
    });
}

function addItems(data)
{
    $.each(data,function(index,item){
            if(item.tipo==1 || ((Number(item.importe)<=0) && item.tipo==2))
            {
                var html='<tr>';
                html +='<td>'+item.folio+'</td>';
                html +='<td>'+item.nombre+'</td>';
                html +='<td>'+item.fecha+'</td>';
                if(item.tipo==1)
                {
                    html +='<td>'+item.tipo_pago+'</td>';
                    html +='<td>'+((item.tipo_pago=='Mensual')?item.mensual:item.quincenal)+'</td>';
                    html +='<td></td>';
                }else if(item.tipo==2)
                {
                    html +='<td>Pago Especial</td>';
                    html +='<td>'+item.especiales+'</td>';
                    html +='<td></td>';
                }
                html +='</tr>';
                $('#tblPlanes').append(html);
                html = '';    
            }
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