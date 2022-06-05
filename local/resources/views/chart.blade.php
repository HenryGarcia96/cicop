@extends ('layouts.dashboard')
@section('page_heading', 'Corte diario')

@section('section')
<div class="col-sm-12">
    <div class="row">

        <div class="col-md-12">
            <div class="row">
                {{ Form::open(['url' => 'reporte/buscar_chart', 'method' => 'POST', 'id' => 'form-buscar']) }}
                <div class="form-group col-md-3">
                    <div class="input-daterange input-group" id="demo-date-range">
                        <div class="input-group-content">
                            <input type="text" class="form-control" id="start" name="start" placeholder="Fecha inicio"
                                value="{{date('Y-m-').'01'}}" />
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
                                    <button class="btn btn-download btn-default hidden" type="button"><i
                                    class="fa fa-download"></i></button>
                                    </span>          
                {{ Form::close() }}
                <div class="col-md-3"><label id="totales"></label></div>
            </div>
            <div class="row">
                <!-- Inicia grafica mensual -->
                <div class="col-md-6"> 
                    <div class="card">
                    <div class="card-header text-center">
                            <h4>Atrasos mensuales (Periodo de fecha)</h4>
                        </div><!--end .card-body -->
                        <div class="card-body">
                            <div id="morris-bar-graph" class="height-7" data-colors="#F79C36"></div>
                        </div><!--end .card-body -->
                    </div><!--end .card -->
                </div> 
                <!-- Termina grafica -->
                <!-- Inicia grafica quincenal -->
                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header text-center">
                                <h4>Atrasos quincenales (Periodo de Fecha)</h4>
                            </div><!--end .card-body -->
                            <div class="card-body">
                                <div id="morris-bar-graph-2" class="height-7" data-colors="#14CC3D"></div>
                            </div><!--end .card-body -->
                    </div><!--end .card -->
                </div> 
                <!-- Termina grafica -->
                <!-- Inicia grafica pagos especiales -->
                <div class="col-md-12"> 
                    <div class="card">
                        <div class="card-header text-center">
                                <h4>Pagos Especiales Atrasados (Historico)</h4>
                            </div><!--end .card-body -->
                            <div class="card-body">
                                <div id="morris-bar-graph-3" class="height-7" data-colors="#0598BB"></div>
                            </div><!--end .card-body -->
                    </div><!--end .card -->
                </div> 
                <!-- Termina grafica -->
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
let graficaA=null;
let graficaB= null;
let graficaC=null;
$(document).ready(function() {
    
    $('#btn-descargar').click(function(e){
        url="{{ asset('local/reportes/vencidos.xls')}}";
        e.preventDefault();  //stop the browser from following
        window.location.href = url;
    });
    
    $('#buscar').keyup(function(e) {
        if (e.which == 13) {
            buscar(false);
        }
    });

    $('.btn-buscar').click(function() {
        buscar(false);
    });

    $('.btn-download').click(function(e){
        url="{{ asset('local/reportes/corte.xls')}}";
        e.preventDefault();  //stop the browser from following
        window.location.href = url;
    });

});

function buscar(primerCarga) {
    var form = $('#form-buscar');
    var url = form.attr('action');


    console.log(url);

    var data = form.serialize();
    
    $.post(url, data, function(result) {
        console.log(result);

        var aldia = result.aldia;
        var mes=[];
        mes.push({x: 'AL DIA', y: aldia.find(x=>x.tipo_pago==2).atrasos});
        mes.push({x: '1 mes', y: result.atrasos.filter(x => x.tipo_pago == 2 && x.atrasos==1).length});
        mes.push({x: '2 meses', y: result.atrasos.filter(x => x.tipo_pago == 2 && x.atrasos==2).length});
        mes.push({x: '3 meses', y: result.atrasos.filter(x => x.tipo_pago == 2 && x.atrasos==3).length});
        mes.push({x: 'CANCELACION', y: result.cancelaciones[0].total});
        mes.push({x: 'CONVENIO', y: result.convenios[0].total});


        var quin=[];
        quin.push({x: 'AL DIA', y: aldia.find(x=>x.tipo_pago==1).atrasos});
        quin.push({x: '1 quincena', y: result.atrasos.filter(x => x.tipo_pago == 1 && x.atrasos==1).length});
        quin.push({x: '2 quincenas', y: result.atrasos.filter(x => x.tipo_pago == 1 && x.atrasos==2).length});
        quin.push({x: '3 quincenas', y: result.atrasos.filter(x => x.tipo_pago == 1 && x.atrasos==3).length});
        quin.push({x: '4 quincenas', y: result.atrasos.filter(x => x.tipo_pago == 1 && x.atrasos==4).length});
        quin.push({x: '5 quincenas', y: result.atrasos.filter(x => x.tipo_pago == 1 && x.atrasos==5).length});
        quin.push({x: '6 quincenas', y: result.atrasos.filter(x => x.tipo_pago == 1 && x.atrasos==6).length});
        

        var esp=[];

        esp.push({x:'AL DIA',y:result.esp_aldia[0].atrasos});
        $.each(result.esp_atrasos,function(index,item){
            esp.push({x:item.anio,y:item.atrasos});
        })

        if(primerCarga)
        {
            graficar(mes);
            graficarespeciales(esp);
            graficarquincenales(quin);
        }else
        {
            graficaA.setData(mes);
            graficaB.setData(esp);
            graficaC.setData(quin);
        }
        
        
    }).fail(function(err) {
        console.log(err);
        alert('No se completo la busqueda');
    });
}

graficar=(mes)=>{
    graficaA=Morris.Bar({
				element: 'morris-bar-graph',
				data: mes,
                xkey: 'x',
                ykeys: ['y'],
                labels: ['Atrasos mensuales'],
                barRatio: 0.4,
                xLabelAngle: 35,
                hideHover: 'auto',
                barColors: ['#FB8500']
			});

    
}
graficarespeciales=(esp)=>{
    graficaB=Morris.Bar({
				element: 'morris-bar-graph-3',
				data: esp,
                xkey: 'x',
                ykeys: ['y'],
                labels: ['Atrasos especiales'],
                barRatio: 0.4,
                xLabelAngle: 35,
                hideHover: 'auto',
                barColors: ['#0598BB']
			});
            
}
graficarquincenales=(quin)=>{
    graficaC=Morris.Bar({
				element: 'morris-bar-graph-2',
				data: quin,
                xkey: 'x',
                ykeys: ['y'],
                labels: ['Atrasos quincenales'],
                barRatio: 0.4,
                xLabelAngle: 35,
                hideHover: 'auto',
                barColors: ['#3AFB00']
                
                
			});
            
    
}

buscar(true);
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