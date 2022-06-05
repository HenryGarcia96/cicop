<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use View;
use Luecano\NumeroALetras\NumeroALetras;

/**
 * Estatus
 * 1: Activo
 * 0: Cancelado
 * 3: Pagado
 * 4: Liquidado con anticipacion
 */

class PagosController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('edos');
	}
    public function pagos()
    {
        return View::make('pagos');
    }
    public function create(Request $request)
	{
		if($request->ajax())
		{
            $error=0;
            $input=Input::all();
            $user_id=Auth::user()->__get('id');
            $color='success';
            
            try{
                $id=0;
                if(!empty($input['ticket']))
                {
                    $res=DB::table('tbl_pagos')->select('id')
                    ->where('ticket',$input['ticket'])
                    ->get();

                    $res2=DB::table('tbl_pagos_esp')->select('id')
                    ->where('ticket',$input['ticket'])
                    ->get();

                    if(isset($res[0]['id']))
                    {
                        $id=$res[0]['id'];
                    }

                    if(isset($res2[0]['id']) && $id<=0)
                    {
                        $id=$res2[0]['id'];
                    }
                }
                if($id==0)
                {
                    if($input['tipo_pago']==1){
                    
                        $date = str_replace('/', '-', $input['fecha']);
                        $res=DB::table('tbl_pagos')
                        ->where('id_intencion',$input['id_intencion'])
                        ->where('fecha',date('Y-m-d',strtotime($date)))
                        ->where('tipo',$input['tipo_pago'])
                        ->update([
                            'forma_pago'=>$input['forma_pago']
                            ,'institucion_bancaria'=>$input['institucion_bancaria']
                            ,'importe'=>$input['importe']
                            ,'id_usuario'=>$user_id
                            ,'fecha_registro'=>date('Y-m-d H:i:s')
                            ,'ticket'=>$input['ticket']
                            ,'fecha_pago'=>$input['fecha_pago']
                            ,'observaciones'=>$input['comments']
                        
                        ]);
                    }elseif($input['tipo_pago']==2){
                        //Agregamos el abono al pago especial
                        DB::table('tbl_pagos_esp')->insert(
                            [
                                'id_pago'=>$input['id_pago']
                                ,'importe'=>$input['importe']
                                ,'fecha_registro'=>date('Y-m-d H:i:s')
                                ,'id_usuario'=>$user_id
                                ,'forma_pago'=>$input['forma_pago']
                                ,'institucion_bancaria'=>$input['institucion_bancaria']
                                ,'ticket'=>$input['ticket']
                                ,'fecha_pago_esp'=>$input['fecha_pago']
                                ,'observaciones'=>$input['comments']
                                ,'fecha'=>$input['fecha']
                                ,'indice'=>$input['indice']
                            ]
                            );
                    }
                    else{
                        DB::table('tbl_pagos')->insert(
                        [
                            'id_intencion'=>$input['id_intencion']
                            ,'importe'=>$input['importe']
                            ,'fecha'=>date('Y-m-d H:i:s')
                            ,'fecha_registro'=>date('Y-m-d H:i:s')
                            ,'id_usuario'=>$user_id
                            ,'tipo'=>$input['tipo_pago']
                            ,'forma_pago'=>$input['forma_pago']
                            ,'institucion_bancaria'=>$input['institucion_bancaria']
                            ,'ticket'=>$input['ticket']
                            ,'fecha_pago'=>$input['fecha_pago']
                            ,'observaciones'=>$input['comments']
                            
                        ]
                        );
                    }
                    $mensaje='Pago registrado';
                }else
                {
                    $mensaje='El No de ticket ya existe';
                    $error=1;
                    $color='error';
                }
                
                
            }catch(\Exception $ex)
            {
                $error=1;
                $mensaje=$ex->getMessage();
                $color='error';
            }
			

		return response()->json(['mensaje'=>$mensaje,'error'=>$error,'color'=>$color]) ;
		}
	}
    public function pagoAnticipada(Request $request)
    {
        if($request->ajax())
		{
            $user_id=Auth::user()->__get('id');
            $mensaje='Pago realizado';
            $error=0;
            $color='success';
            $input=Input::all();
            $arr_delete=['$',','];
            $total=0;
            try
            {
                if(!empty($input['ticket']))
                {
                    $res=DB::table('tbl_pagos')->select('id')->where('ticket',$input['ticket'])->get();
                    $res2=DB::table('tbl_pagos_esp')->select('id')->where('ticket',$input['ticket'])->get();

                    $total=count($res)+count($res2);
                }
                if($total>0)
                {
                    $error=1;
                    $mensaje="El ticket ".$input['ticket'].", ya fue usado en el sistema";
                    $color='error';
                }else
                {
                    $res=DB::table('tbl_intenciones')->where('id',$input['id_contrato'])
                    ->update(
                        [
                            'estatus'=>4,
                            'pago_anticipado'=>str_replace($arr_delete,'',$input['importe']),
                            'fecha_pago_anticipado'=>$input['fecha_pago'],
                            'forma_pago'=>$input['forma_pago'],
                            'institucion_bancaria'=>$input['institucion_bancaria'],
                            'id_usuario_pago'=>$user_id,
                            'importe_anticipado'=>str_replace($arr_delete,'',$input['importe_anticipado']),
                            'fecha_registro_anticipado'=>date('Y-m-d H:i:s'),
                            'ticket'=>$input['ticket']
                        ]);
                }
            }catch(\Exception $ex)
            {
                $error=1;
                $mensaje=$ex->getMessage();
                $color='error';
            }

            return response()->json(['mensaje'=>$mensaje,'error'=>$error,'color'=>$color]) ;
		}
    }
	public function buscar(Request $request)
	{
		if($request->ajax())
		{
            $input=Input::all();
			$results=DB::table('tbl_intenciones')
            ->select(
                'tbl_intenciones.id'
                ,'tbl_intenciones.id_cliente'
                ,'tbl_clientes.nombre'
                ,DB::raw('coalesce(tbl_intenciones_detalle.manzana,\'\') manzana')
                ,DB::raw('coalesce(tbl_intenciones_detalle.lote,\'\') lote')
                ,'tbl_intenciones_detalle.pago_mensual'
                ,'tbl_intenciones_detalle.pago_quincenal'
                ,'tbl_intenciones_detalle.mensualidades'
                ,'tbl_intenciones_detalle.quincenas'
                ,'tbl_intenciones_detalle.no_pagos_esp'
                ,'tbl_intenciones_detalle.pago_esp'
                ,'tbl_intenciones_detalle.total'
                ,'tbl_intenciones_detalle.tipo'
                ,'tbl_intenciones.tipo_pago'
                ,DB::raw('tbl_intenciones_detalle.enganche as enganche_total')
                ,'tbl_planes.enganche'
                ,'tbl_intenciones.fecha_primer_pago'
                ,'tbl_intenciones.fecha_primer_pago_esp'
                ,'tbl_intenciones.estatus'
                ,'tbl_intenciones.pago_anticipado'
                ,'tbl_intenciones.importe_anticipado'
            )
            ->leftJoin('tbl_clientes','tbl_intenciones.id_cliente','=','tbl_clientes.id')
            ->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
            ->leftJoin('tbl_planes','tbl_intenciones_detalle.id_plan','=','tbl_planes.id')
            ->where('folio',$input['folio'])
            ->get();

            $pagos=null;
            $fechas=null;
            $fechas_esp=null;
            $pagos_generales=null;
            $pagos_esp=null;
            //===========================================
            // <option value="1">Mensualidad/Quincena</option>
            // <option value="2">Pago Especial</option>
            // <option value="3">Abono a capital</option>
            // <option value="4">Pago por Reubicacion</option>
            // <option value="5">Pago por cesion de derecho</option>
            // <option value="6">Restructura</option>
            // <option value="7">Recargo</option>
            // <option value="8">Venta de plano</option>
            //===========================================
            if(isset($results[0]['id']))
            {
                $pagos=DB::table('tbl_pagos')->select('tipo',DB::raw('count(*) as total'),DB::raw('sum(importe) as pagos'))
                ->where('id_intencion',$results[0]['id'])
                ->where('importe','>',0)
                ->groupBy('tipo')
                ->get();

                $pagos_generales=DB::table('tbl_pagos')
                ->select('tbl_pagos.id','tipo','importe',DB::raw('DATE(fecha) fecha'),'id_usuario','forma_pago',
                'institucion_bancaria','ticket',DB::raw('DATE(fecha_pago) fecha_pago'),'tbl_usuario.nombre','indice')
                ->leftJoin('tbl_usuario','tbl_pagos.id_usuario','=','tbl_usuario.id')
                ->where('id_intencion',$results[0]['id'])
                //->where('importe','>',0)
                ->orderBy('id')
                ->get();

                $pagos_esp=DB::table('tbl_pagos_esp')
                ->select('tbl_pagos_esp.id','id_pago',DB::raw('2 as tipo'),'importe',DB::raw('DATE(fecha) fecha'),'id_usuario','forma_pago',
                'institucion_bancaria','ticket',DB::raw('DATE(fecha_pago_esp) fecha_pago'),'tbl_usuario.nombre')
                ->leftJoin('tbl_usuario','tbl_pagos_esp.id_usuario','=','tbl_usuario.id')
                ->whereRaw('id_pago in(select id from tbl_pagos where id_intencion='.$results[0]['id'].')')
                ->orderBy('tbl_pagos_esp.id')
                ->get();
            }

            $tbl_anticipado=DB::table('tbl_anticipado')->select('mes_inicial','mes_final','precio')
            ->get();

		return response()->json(['intencion'=>$results,'pagos'=>$pagos,'pagos_esp'=>$pagos_esp,'anticipado'=>$tbl_anticipado,'pagos_generales'=>$pagos_generales]) ;
		}
	}
    public function recibo(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_intencion'];
            $forma_pago=['','Mensualidad/Quincena','Especiales','Abono a Capital','Reubicacion','Cesion de derecho','Restructura','Recargo','Venta de plano'];
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'fecha_pago','tbl_intenciones_detalle.forma_pago','tbl_intenciones_detalle.institucion_bancaria','tbl_intenciones_detalle.observaciones','enganche',
			'mensualidades','quincenas','total','tipo_pago')
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
			->where('tbl_intenciones.id',$id)
			->get();

            $pago=DB::table('tbl_pagos')->select('tbl_pagos.id','fecha','importe','fecha_registro'
            ,'forma_pago'
            ,'institucion_bancaria','fecha_pago','ticket','observaciones'
            ,'tipo'
            ,DB::Raw('tbl_usuario.nombre as nombre_u')
            ,DB::Raw('tbl_usuario.apellidos as apellidos_u'))
            ->leftJoin('tbl_usuario','tbl_pagos.id_usuario','=','tbl_usuario.id')
            ->where('id_intencion',$id)
            //->where('importe','>',0)
            ->orderBy('tbl_pagos.fecha_registro','desc')
            ->get();

            $total_pagos=0;
            $total_pagos_realizados=0;
            $total_pagos_esp=0;
            $total_pagos_esp_realizados=0;
            foreach($pago as $row)
            {
                switch($row['tipo'])
                {
                    case 1:
                        $total_pagos++;
                        if($row['importe']>0)
                        {
                            $total_pagos_realizados++;
                        }   
                        break;
                    case 2:
                        $total_pagos_esp++;
                        if($row['importe']>0)
                        {
                            $total_pagos_esp_realizados++;
                        }   
                        break;
                }
                
            }

			$int=$result[0];

			$style='<style>
			body{ font-family: Arial, Helvetica, sans-serif; font-size:14pt; margin:10mm}
			div{ min-height:20px; width:100%; display:inline-block}
			table tr td{ height:40px; }
			div.fecha{ text-align:right}
			table{ width:70%}
			label{ width:29%; display:inline-block}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{ width:100%; text-align:center; position:absolute; bottom:0}
			.titulo{ wodth:100%;text-align:center; position:absolute; top:30mm}
			.rosa_vientos{width:100%; text-align:center}
			.tres{ width:32%; display:inline-block}
			</style>';
			$js='';

			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Carta</title>
			' . $js . $style . '</head><body>';

			$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
			$formatter = new NumeroALetras();
			$letra_total=$formatter->toWords($pago[0]['importe']).' PESOS 00/100 M.N.';
			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"><h2>COLONIA SAN LAZARO A.C.</h2></div>';
			$html .='<div style="text-align:center"><h3>CONTRA RECIBO DE PAGO</h3></div>';
			$html .='<div class="fecha"></div>';
            if($pago[0]['tipo']==1)
            {
                $html .='<div class="fecha"><b>No: '.$total_pagos_realizados.'/'.$total_pagos.'</b></div>';
            }else if($pago[0]['tipo']==2)
            {
                $html .='<div class="fecha"><b>No: '.$total_pagos_esp_realizados.'/'.$total_pagos_esp.'</b></div>';
            }
			$html .='<div><b>DATOS DEL COMPRADOR</b></div>';
			$html .='<div><label>RECIBIMOS DE:</label><span> '.strtoupper($int['nombre']).'</span></div>';
			$html .='<div><label>LA CANTIDAD DE:</label><span>'.format_number($pago[0]['importe']).' ('.$letra_total.')</span> </div>';
            $fecha=strtotime($pago[0]['fecha']);
            $quincena_mes=' MENSUALIDAD DE ';
            $tipo_recibo='Mensualidad';
            if($int['tipo_pago']==1)
            {
                $quincena_mes=' QUINCENA DEL ';
                $tipo_recibo='Quincena';
            }

            if($pago[0]['tipo']<2){
                $forma_pago[1]=$tipo_recibo;
                if($int['tipo_pago']==1) //Quincenal
                {
                    $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.$quincena_mes.date('d',$fecha).' DE '.$meses[date('n',$fecha)-1].' DEL '.date('Y',$fecha).'</span></div>';    
                }else
                {
                    $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span>'.$quincena_mes.$meses[date('n',$fecha)-1].' DEL '.date('Y',$fecha).'</span></div>';    
                }
            }else{
                $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.strtoupper($forma_pago[$pago[0]['tipo']]).'</span></div>';
            }
			
			$lotes='';
			foreach ($result as $item) {
				$lotes .=$item['lote'].',';
			}
			$html .='<div><div class="tres"><b>MANZANA:</b> '.$int['manzana'].'</div><div class="tres"><b>LOTE:</b> '.substr($lotes,0,-1).'</div><div class="tres"> <b>EXP. NO.</b> '.$int['folio'].'</div></div>';
			$fecha_pago=strtotime($pago[0]['fecha_pago']);
			$html .='<div style="margin-top:20px; "><label >FECHA DE REALIZACIÓN DE PAGO: </label><span>'.date('d',$fecha_pago).' DE '.$meses[date('n',$fecha_pago)-1].' DEL '.date('Y',$fecha_pago).'</span></div>';
			$html .='<div style="margin-top:20px"><div class="tres"><b>FORMAS DE PAGO</b></div><div class="tres"><b>INSTITUCION BANCARIA</b></div><div class="tres"><b>TIPO PAGO</b></div></div>';
			$html .='<div><div class="tres">'.$pago[0]['forma_pago'].'</div><div class="tres">'.$pago[0]['institucion_bancaria'].'</div><div class="tres">'.strtoupper($forma_pago[$pago[0]['tipo']]).'</div></div>';
			$html .='<div style="width:40%; text-align:center; margin-top:20px"><span>'.strtoupper($pago[0]['nombre_u'].' '.$pago[0]['apellidos_u']).'</span></div>';
			$html .='<br><div style="width:40%; text-align:center">(NOMBRE Y FIRMA)</div>';
			$html .='<br><br>';
			$html .='<div><div class="tres" style="width:60%">FECHA DE ELABORACION '.date('Y-m-d H:i').'</div><div class="tres"><b>Ticket: </b>'.$pago[0]['ticket'].'</div></div>';
            $html .='<div><div class="tres" style="width:60%"><b>OBSERVACIONES:</b><br>'.$pago[0]['observaciones'].'</div><div class="tres"><b>FOLIO DE CONTRA RECIBO: </b> RG/'.date('Y',$fecha_pago).'/'.sprintf("%08d", $pago[0]['id']).'</div></div>';
			//$html .='<div class="rosa_vientos"><img src="./local/logos/rosa_vientos.png" style="width:200px" /></div>';
			//$html .='<div class="pie">Calle Josefa Ortiz de Domínguez # 211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.<br>Correo electrónico coloniasanlazaro@outlook.com</div>';

			$html .='</body></html>';
        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
    public function recibo2(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_intencion'];
            $id_pago=$input['id_pago'];
            
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'fecha_pago','tbl_intenciones_detalle.forma_pago','tbl_intenciones_detalle.institucion_bancaria','tbl_intenciones_detalle.observaciones','enganche',
			'mensualidades','quincenas','total','tipo_pago')
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
			->where('tbl_intenciones.id',$id)
			->get();

            $pago=DB::table('tbl_pagos')->select('tbl_pagos.id','fecha','importe','fecha_registro'
            ,'forma_pago','indice'
            ,'institucion_bancaria','fecha_pago','ticket','observaciones'
            ,'tipo','tbl_tipo_pago.descripcion'
            ,DB::Raw('tbl_usuario.nombre as nombre_u')
            ,DB::Raw('tbl_usuario.apellidos as apellidos_u'))
            ->leftJoin('tbl_usuario','tbl_pagos.id_usuario','=','tbl_usuario.id')
            ->leftJoin('tbl_tipo_pago','tbl_pagos.tipo','=','tbl_tipo_pago.id')
            ->where('tbl_pagos.id',$id_pago)
            ->get();

            $total_pagos=0;
            $total_pagos_realizados=0;
            $total_pagos_esp=0;
            $total_pagos_esp_realizados=0;
                      

			$int=$result[0];
            $no_pagos=0;
            if($int['tipo_pago']==1)
            {
                $total_pagos=$int['quincenas'];
            }else
            {
                $total_pagos=$int['mensualidades'];
            }
            $no_pagos_realizados=$pago[0]['indice'];

			$style='<style>
			body{ font-family: Arial, Helvetica, sans-serif; font-size:14pt; margin:10mm}
			div{ min-height:20px; width:100%; display:inline-block}
			table tr td{ height:40px; }
			div.fecha{ text-align:right}
			table{ width:70%}
			label{ width:29%; display:inline-block}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{ width:100%; text-align:center; position:absolute; bottom:0}
			.titulo{ wodth:100%;text-align:center; position:absolute; top:30mm}
			.rosa_vientos{width:100%; text-align:center}
			.tres{ width:32%; display:inline-block}
			</style>';
			$js='';

			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Carta</title>
			' . $js . $style . '</head><body>';

			$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
			$formatter = new NumeroALetras();
			$letra_total=$formatter->toWords($pago[0]['importe']).' PESOS 00/100 M.N.';
			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"><h2>COLONIA SAN LAZARO A.C.</h2></div>';
			$html .='<div style="text-align:center"><h3>CONTRA RECIBO DE PAGO</h3></div>';
			$html .='<div class="fecha"></div>';
            if($pago[0]['tipo']==1)
            {
                $html .='<div class="fecha"><b>No: '.$no_pagos_realizados.'/'.$total_pagos.'</b></div>';

            }else if($pago[0]['tipo']==2)
            {
                $html .='<div class="fecha"><b>No: '.$no_pagos_realizados.'/'.$total_pagos.'</b></div>';

            }
			$html .='<div><b>DATOS DEL COMPRADOR</b></div>';
			$html .='<div><label>RECIBIMOS DE:</label><span> '.strtoupper($int['nombre']).'</span></div>';
			$html .='<div><label>LA CANTIDAD DE:</label><span>'.number_format($pago[0]['importe']).' ('.$letra_total.')</span> </div>';
            $fecha=strtotime($pago[0]['fecha']);
            $quincena_mes=' MENSUALIDAD DE ';
            $tipo_recibo='Mensualidad';
            if($int['tipo_pago']==1)
            {
                $quincena_mes=' QUINCENA DEL ';
                $tipo_recibo='Quincena';
            }

            $forma_pago=$pago[0]['descripcion'];

            if($pago[0]['tipo']<2){
                $forma_pago=$tipo_recibo;
                if($int['tipo_pago']==1) //Quincenal
                {
                    $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.$quincena_mes.date('d',$fecha).' DE '.$meses[date('n',$fecha)-1].' DEL '.date('Y',$fecha).'</span></div>';    
                }else
                {
                    $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span>'.$quincena_mes.$meses[date('n',$fecha)-1].' DEL '.date('Y',$fecha).'</span></div>';    
                }
            }else{
                $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.strtoupper($forma_pago).'</span></div>';
            }
			
			$lotes='';
			foreach ($result as $item) {
				$lotes .=$item['lote'].',';
			}
			$html .='<div><div class="tres"><b>MANZANA:</b> '.$int['manzana'].'</div><div class="tres"><b>LOTE:</b> '.substr($lotes,0,-1).'</div><div class="tres"> <b>EXP. NO.</b> '.$int['folio'].'</div></div>';
			$fecha_pago=strtotime($pago[0]['fecha_pago']);
			$html .='<div style="margin-top:20px; "><label >FECHA DE REALIZACIÓN DE PAGO: </label><span>'.date('d',$fecha_pago).' DE '.$meses[date('n',$fecha_pago)-1].' DEL '.date('Y',$fecha_pago).'</span></div>';
			$html .='<div style="margin-top:20px"><div class="tres"><b>FORMAS DE PAGO</b></div><div class="tres"><b>INSTITUCION BANCARIA</b></div><div class="tres"><b>TIPO PAGO</b></div></div>';
			$html .='<div><div class="tres">'.$pago[0]['forma_pago'].'</div><div class="tres">'.$pago[0]['institucion_bancaria'].'</div><div class="tres">'.strtoupper($forma_pago).'</div></div>';
			$html .='<div style="width:40%; text-align:center; margin-top:20px"><span>'.strtoupper($pago[0]['nombre_u'].' '.$pago[0]['apellidos_u']).'</span></div>';
			$html .='<br><div style="width:40%; text-align:center">(NOMBRE Y FIRMA)</div>';
			$html .='<br><br>';
			$html .='<div><div class="tres" style="width:60%">FECHA DE ELABORACION '.date('Y-m-d H:i').'</div><div class="tres"><b>Ticket: </b>'.$pago[0]['ticket'].'</div></div>';
            $html .='<div><div class="tres" style="width:60%"><b>OBSERVACIONES:</b><br>'.$pago[0]['observaciones'].'</div><div class="tres"><b>FOLIO DE CONTRA RECIBO: </b> RG/'.date('Y',$fecha_pago).'/'.sprintf("%08d", $pago[0]['id']).'</div></div>';
			//$html .='<div class="rosa_vientos"><img src="./local/logos/rosa_vientos.png" style="width:200px" /></div>';
			//$html .='<div class="pie">Calle Josefa Ortiz de Domínguez # 211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.<br>Correo electrónico coloniasanlazaro@outlook.com</div>';

			$html .='</body></html>';
        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
    public function recibo_esp(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_intencion'];
            $id_pago=$input['id_pago'];
            $forma_pago=['','Mensualidad/Quincena','Especiales','Abono a Capital','Reubicacion','Cesion de derecho','Restructura','Recargo','Venta de plano'];
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'fecha_pago','tbl_intenciones_detalle.forma_pago','tbl_intenciones_detalle.institucion_bancaria','tbl_intenciones_detalle.observaciones','enganche',
			'mensualidades','quincenas','total','tipo_pago')
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
			->where('tbl_intenciones.id',$id)
			->get();

            $pago=DB::table('tbl_pagos_esp')->select('tbl_pagos_esp.id','fecha','importe','fecha_registro'
            ,'forma_pago','indice','id_pago'
            ,'institucion_bancaria',DB::raw('fecha_pago_esp as fecha_pago'),'ticket','observaciones'
            ,DB::raw('2 as tipo')
            ,DB::Raw('tbl_usuario.nombre as nombre_u')
            ,DB::Raw('tbl_usuario.apellidos as apellidos_u'))
            ->leftJoin('tbl_usuario','tbl_pagos_esp.id_usuario','=','tbl_usuario.id')
            ->where('tbl_pagos_esp.id',$id_pago)
            ->get();

			$int=$result[0];
            
            $no_pagos_realizados=$pago[0]['indice'];
            $total_pagos_realizados=0;
            $total_pagos_esp=0;
            $total_pagos_esp_realizados=0;
            $total_pagos=$int['no_pagos_esp'];

            

			$style='<style>
			body{ font-family: Arial, Helvetica, sans-serif; font-size:14pt; margin:10mm}
			div{ min-height:20px; width:100%; display:inline-block}
			table tr td{ height:40px; }
			div.fecha{ text-align:right}
			table{ width:70%}
			label{ width:29%; display:inline-block}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{ width:100%; text-align:center; position:absolute; bottom:0}
			.titulo{ wodth:100%;text-align:center; position:absolute; top:30mm}
			.rosa_vientos{width:100%; text-align:center}
			.tres{ width:32%; display:inline-block}
			</style>';
			$js='';

			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Carta</title>
			' . $js . $style . '</head><body>';

			$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
			$formatter = new NumeroALetras();
			$letra_total=$formatter->toWords($pago[0]['importe']).' PESOS 00/100 M.N.';
			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"><h2>COLONIA SAN LAZARO A.C.</h2></div>';
			$html .='<div style="text-align:center"><h3>CONTRA RECIBO DE PAGO</h3></div>';
			$html .='<div class="fecha"></div>';
            if($pago[0]['tipo']==1)
            {
                $html .='<div class="fecha"><b>No: '.$no_pagos_realizados.'/'.$total_pagos.'</b></div>';
            }else if($pago[0]['tipo']==2)
            {
                $html .='<div class="fecha"><b>No: '.$no_pagos_realizados.'/'.$total_pagos.'</b></div>';
            }
			$html .='<div><b>DATOS DEL COMPRADOR</b></div>';
			$html .='<div><label>RECIBIMOS DE:</label><span> '.strtoupper($int['nombre']).'</span></div>';
			$html .='<div><label>LA CANTIDAD DE:</label><span>'.number_format($pago[0]['importe']).' ('.$letra_total.')</span> </div>';
            $fecha=strtotime($pago[0]['fecha']);
            $quincena_mes=' MENSUALIDAD DE ';
            $tipo_recibo='Mensualidad';
            if($int['tipo_pago']==1)
            {
                $quincena_mes=' QUINCENA DEL ';
                $tipo_recibo='Quincena';
            }
            if($pago[0]['importe']<$int['pago_esp'])
            {
                $concepto='ABONO A ESPECIAL '.date('Y',$fecha);
                $pagos_fecha=DB::table('tbl_pagos_esp')->select('id',DB::raw('sum(importe) importes'),DB::raw('count(*) total'))
                ->where('id_pago',$pago[0]['id_pago'])
                ->where('id','<',$pago[0]['id'])
                ->groupBy('id_pago')
                ->get();
                $total_importe=0;
                if(isset($pagos_fecha[0]['importes']))
                {
                    $total_importe=$pagos_fecha[0]['importes']+$pago[0]['importe'];

                }
                if(($int['pago_esp']-$total_importe)==0)
                {
                    $concepto='LIQUIDACION A ESPECIAL '.date('Y',$fecha);
                }

            }else {
                $concepto='ESPECIAL DE '.date('Y',$fecha);
            }
            if($pago[0]['tipo']<2){
                $forma_pago[1]=$tipo_recibo;
                if($int['tipo_pago']==1) //Quincenal
                {
                    $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.$quincena_mes.date('d',$fecha).' DE '.$meses[date('n',$fecha)-1].' DEL '.date('Y',$fecha).'</span></div>';    
                }else
                {
                    $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span>'.$quincena_mes.$meses[date('n',$fecha)-1].' DEL '.date('Y',$fecha).'</span></div>';    
                }
            }else{
                $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.$concepto.'</span></div>';
            }

            
			
			$lotes='';
			foreach ($result as $item) {
				$lotes .=$item['lote'].',';
			}
			$html .='<div><div class="tres"><b>MANZANA:</b> '.$int['manzana'].'</div><div class="tres"><b>LOTE:</b> '.substr($lotes,0,-1).'</div><div class="tres"> <b>EXP. NO.</b> '.$int['folio'].'</div></div>';
			$fecha_pago=strtotime($pago[0]['fecha_pago']);
			$html .='<div style="margin-top:20px; "><label >FECHA DE REALIZACIÓN DE PAGO: </label><span>'.date('d',$fecha_pago).' DE '.$meses[date('n',$fecha_pago)-1].' DEL '.date('Y',$fecha_pago).'</span></div>';
			$html .='<div style="margin-top:20px"><div class="tres"><b>FORMAS DE PAGO</b></div><div class="tres"><b>INSTITUCION BANCARIA</b></div><div class="tres"><b>TIPO PAGO</b></div></div>';
			$html .='<div><div class="tres">'.$pago[0]['forma_pago'].'</div><div class="tres">'.$pago[0]['institucion_bancaria'].'</div><div class="tres">'.$concepto.'</div></div>';
			$html .='<div style="width:40%; text-align:center; margin-top:20px"><span>'.strtoupper($pago[0]['nombre_u'].' '.$pago[0]['apellidos_u']).'</span></div>';
			$html .='<br><div style="width:40%; text-align:center">(NOMBRE Y FIRMA)</div>';
			$html .='<br><br>';
			$html .='<div><div class="tres" style="width:60%">FECHA DE ELABORACION '.date('Y-m-d H:i').'</div><div class="tres"><b>Ticket: </b>'.$pago[0]['ticket'].'</div></div>';
            $html .='<div><div class="tres" style="width:60%"><b>OBSERVACIONES:</b><br>'.$pago[0]['observaciones'].'</div><div class="tres"><b>FOLIO DE CONTRA RECIBO: </b> RG/'.date('Y',$fecha_pago).'/'.sprintf("%08d", $pago[0]['id']).'</div></div>';
			//$html .='<div class="rosa_vientos"><img src="./local/logos/rosa_vientos.png" style="width:200px" /></div>';
			//$html .='<div class="pie">Calle Josefa Ortiz de Domínguez # 211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.<br>Correo electrónico coloniasanlazaro@outlook.com</div>';

			$html .='</body></html>';
        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
    public function recibo_anticipado(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_contrato'];
            
            $forma_pago=['','Mensualidad/Quincena','Especiales','Abono a Capital','Reubicacion','Cesion de derecho','Restructura','Recargo','Venta de plano'];
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'tbl_intenciones.fecha_pago_anticipado','tbl_intenciones.forma_pago','tbl_intenciones.institucion_bancaria','tbl_intenciones_detalle.observaciones','enganche',
			'mensualidades','quincenas','total','tipo_pago','fecha_pago_anticipado','pago_anticipado','importe_anticipado','tbl_intenciones.ticket'
            ,DB::Raw('tbl_usuario.nombre as nombre_u')
            ,DB::Raw('tbl_usuario.apellidos as apellidos_u'))
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
            ->leftJoin('tbl_usuario','tbl_intenciones.id_usuario_pago','=','tbl_usuario.id')
			->where('tbl_intenciones.id',$id)
			->get();

            

			$int=$result[0];
            
            $no_pagos_realizados=0;
            $total_pagos_realizados=0;
            $total_pagos_esp=0;
            $total_pagos_esp_realizados=0;
            $total_pagos=$int['no_pagos_esp'];

            

			$style='<style>
			body{ font-family: Arial, Helvetica, sans-serif; font-size:14pt; margin:10mm}
			div{ min-height:20px; width:100%; display:inline-block}
			table tr td{ height:40px; }
			div.fecha{ text-align:right}
			table{ width:70%}
			label{ width:29%; display:inline-block}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{ width:100%; text-align:center; position:absolute; bottom:0}
			.titulo{ wodth:100%;text-align:center; position:absolute; top:30mm}
			.rosa_vientos{width:100%; text-align:center}
			.tres{ width:32%; display:inline-block}
			</style>';
			$js='';

			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Carta</title>
			' . $js . $style . '</head><body>';

			$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
			$formatter = new NumeroALetras();
			$letra_total=$formatter->toWords($int['pago_anticipado']).' PESOS 00/100 M.N.';
			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"><h2>COLONIA SAN LAZARO A.C.</h2></div>';
			$html .='<div style="text-align:center"><h3>CONTRA RECIBO DE PAGO</h3></div>';
			$html .='<div class="fecha"></div>';
            
            $html .='<div class="fecha"><b>No: 1/1</b></div>';
            
			$html .='<div><b>DATOS DEL COMPRADOR</b></div>';
			$html .='<div><label>RECIBIMOS DE:</label><span> '.strtoupper($int['nombre']).'</span></div>';
			$html .='<div><label>LA CANTIDAD DE:</label><span>'.number_format($int['pago_anticipado']).' ('.$letra_total.')</span> </div>';
            $fecha=strtotime($int['fecha_pago_anticipado']);
            $quincena_mes=' MENSUALIDAD DE ';
            $tipo_recibo='Mensualidad';
            
            $concepto="LIQUIDACION ANTICIPADA";
            
            $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.$concepto.'</span></div>';
            
			$lotes='';
			foreach ($result as $item) {
				$lotes .=$item['lote'].',';
			}
			$html .='<div><div class="tres"><b>MANZANA:</b> '.$int['manzana'].'</div><div class="tres"><b>LOTE:</b> '.substr($lotes,0,-1).'</div><div class="tres"> <b>EXP. NO.</b> '.$int['folio'].'</div></div>';
			$fecha_pago=strtotime($int['fecha_pago_anticipado']);
			$html .='<div style="margin-top:20px; "><label >FECHA DE REALIZACIÓN DE PAGO: </label><span>'.date('d',$fecha_pago).' DE '.$meses[date('n',$fecha_pago)-1].' DEL '.date('Y',$fecha_pago).'</span></div>';
			$html .='<div style="margin-top:20px"><div class="tres"><b>FORMAS DE PAGO</b></div><div class="tres"><b>INSTITUCION BANCARIA</b></div><div class="tres"><b>TIPO PAGO</b></div></div>';
			$html .='<div><div class="tres">'.$int['forma_pago'].'</div><div class="tres">'.$int['institucion_bancaria'].'</div><div class="tres">'.$concepto.'</div></div>';
			$html .='<div style="width:40%; text-align:center; margin-top:20px"><span>'.strtoupper($int['nombre_u'].' '.$int['apellidos_u']).'</span></div>';
			$html .='<br><div style="width:40%; text-align:center">(NOMBRE Y FIRMA)</div>';
			$html .='<br><br>';
			$html .='<div><div class="tres" style="width:60%">FECHA DE ELABORACION '.date('Y-m-d H:i').'</div><div class="tres"><b>Ticket: </b>'.$int['ticket'].'</div></div>';
            

			$html .='</body></html>';
        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
    public function recibo_contado(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_contrato'];
            
            
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha')
			,'tbl_clientes.nombre'
			,'tbl_intenciones.fecha_pago_anticipado','tbl_intenciones.forma_pago','tbl_intenciones.institucion_bancaria'
            ,'tbl_intenciones.observaciones'
			,'pago_anticipado','tbl_intenciones.ticket','tbl_intenciones.lote_contado','tbl_intenciones.manzana_contado'
            ,DB::Raw('tbl_usuario.nombre as nombre_u')
            ,DB::Raw('tbl_usuario.apellidos as apellidos_u'))
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
            ->leftJoin('tbl_usuario','tbl_intenciones.id_usuario_pago','=','tbl_usuario.id')
			->where('tbl_intenciones.id',$id)
			->get();

			$int=$result[0];
            
            $no_pagos_realizados=0;
            $total_pagos_realizados=0;
            $total_pagos_esp=0;
            $total_pagos_esp_realizados=0;


			$style='<style>
			body{ font-family: Arial, Helvetica, sans-serif; font-size:14pt; margin:10mm}
			div{ min-height:20px; width:100%; display:inline-block}
			table tr td{ height:40px; }
			div.fecha{ text-align:right}
			table{ width:70%}
			label{ width:29%; display:inline-block}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{ width:100%; text-align:center; position:absolute; bottom:0}
			.titulo{ wodth:100%;text-align:center; position:absolute; top:30mm}
			.rosa_vientos{width:100%; text-align:center}
			.tres{ width:32%; display:inline-block}
			</style>';
			$js='';

			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Carta</title>
			' . $js . $style . '</head><body>';

			$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
			$formatter = new NumeroALetras();
			$letra_total=$formatter->toWords($int['pago_anticipado']).' PESOS 00/100 M.N.';
			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"><h2>COLONIA SAN LAZARO A.C.</h2></div>';
			$html .='<div style="text-align:center"><h3>CONTRA RECIBO DE PAGO</h3></div>';
			$html .='<div class="fecha"></div>';
            
            $html .='<div class="fecha"><b>No: 1/1</b></div>';
            
			$html .='<div><b>DATOS DEL COMPRADOR</b></div>';
			$html .='<div><label>RECIBIMOS DE:</label><span> '.strtoupper($int['nombre']).'</span></div>';
			$html .='<div><label>LA CANTIDAD DE:</label><span>'.number_format($int['pago_anticipado']).' ('.$letra_total.')</span> </div>';
            $fecha=strtotime($int['fecha_pago_anticipado']);
            $quincena_mes=' MENSUALIDAD DE ';
            $tipo_recibo='Mensualidad';
            
            $concepto="PAGO DE CONTADO";
            
            $html .='<div><label>CORRESPONDIENTE AL PAGO DE:</label><span> '.$concepto.'</span></div>';
            
			
			$html .='<div><div class="tres">'.$int['observaciones'].'</div><div class="tres"></div><div class="tres"> <b>EXP. NO.</b> '.$int['folio'].'</div></div>';
			$fecha_pago=strtotime($int['fecha_pago_anticipado']);
            $html .='<div><div class="tres"><b>MANZANA:</b> '.$int['manzana_contado'].'</div><div class="tres"><b>LOTE:</b> '.$int['lote_contado'].'</div><div class="tres"></div></div>';
			$html .='<div style="margin-top:20px; "><label >FECHA DE REALIZACIÓN DE PAGO: </label><span>'.date('d',$fecha_pago).' DE '.$meses[date('n',$fecha_pago)-1].' DEL '.date('Y',$fecha_pago).'</span></div>';
			$html .='<div style="margin-top:20px"><div class="tres"><b>FORMAS DE PAGO</b></div><div class="tres"><b>INSTITUCION BANCARIA</b></div><div class="tres"><b>TIPO PAGO</b></div></div>';
			$html .='<div><div class="tres">'.$int['forma_pago'].'</div><div class="tres">'.$int['institucion_bancaria'].'</div><div class="tres">'.$concepto.'</div></div>';
			$html .='<div style="width:40%; text-align:center; margin-top:20px"><span>'.strtoupper($int['nombre_u'].' '.$int['apellidos_u']).'</span></div>';
			$html .='<br><div style="width:40%; text-align:center">(NOMBRE Y FIRMA)</div>';
			$html .='<br><br>';
			$html .='<div><div class="tres" style="width:60%">FECHA DE ELABORACION '.date('Y-m-d H:i').'</div><div class="tres"><b>Ticket: </b>'.$int['ticket'].'</div></div>';
            

			$html .='</body></html>';
        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
    public function getFechas($fecha,$num_pagos,$tipo_pago,$pagos_esp){
        $res=array();
        $date1=date('d/m/Y',strtotime($fecha));
        $dia=date('j',strtotime($fecha));
        $res[1]=array($date1);
        for($i=2;$i<=$num_pagos;$i++)
        {
            if($pagos_esp)
            {
                $date1 = date('d/m/Y', strtotime($fecha. ' + '.($i-1).' year'));
            }else{
            if($tipo_pago==1) //Quincenal
				{
					$items=explode('/',$date1);
					
					if($items[0]==30 || $items[0]==28 || $items[0]==29)
					{
						$next_day=15;
					}else
					{
						$next_day=30;
						if($items[1]==2)
						{
							$last_day=date('Y-m',strtotime($items[2].'-'.$items[1].'-'.$items[1].' +1 month')).'-01';
							$next_day=date('d',strtotime($last_day.' -1 day'));
						}
					}
					

					if($next_day==15)
					{
						$sig=$next_day.'/'.date('m/Y',strtotime($items[2].'-'.$items[1].'-'.$items[1].' +1 month'));
					}else
					{
						$sig=$next_day.'/'.date('m/Y',strtotime($items[2].'-'.$items[1].'-'.$items[1]));
					}
					
					$date1=$sig;
					
					
				}else{ //Mensual
                    $campos=explode('/',$date1);
                    $str_date=$campos[2].'-'.$campos[1].'-'.$campos[0];
                    
					if(date('n',strtotime($str_date))==1 && $dia==30)
					{
						$last_day=date('Y-m',strtotime($str_date.' + 2 month')).'-01';
						$date1=date('d/m/Y',strtotime($last_day.' - 1 day'));
					}else{
						$date1 = date('d/m/Y', strtotime($fecha. ' + '.($i-1).' month'));				
					}
				}
            }
            
            $res[$i]=array($date1);
        }
        return $res;
    }

    public function actualizar($id,Request $request)
	{
		if($request->ajax())
		{
            $input=Input::all();
			DB::table('tbl_pagos')->where('id',$id)
            ->update(['importe'=>0,'ticket'=>'']);

		    return response()->json(['mensaje'=>'Pago eliminado correctamente','color'=>'success']) ;
		}
	}
    public function borrar($id,Request $request)
	{
		if($request->ajax())
		{
            $input=Input::all();
			DB::table('tbl_pagos_esp')->where('id',$id)
            ->delete();

		    return response()->json(['mensaje'=>'Pago eliminado correctamente','color'=>'success']) ;
		}
	}

    public function crear_seguimiento(Request $request)
    {
        if($request->ajax())
            {
                $mensaje='Seguimiento registrado';
                $color ='success';
                try{
                    $input=Input::all();
                    $user_id=Auth::user()->__get('id');
                    DB::table('tbl_seguimiento')
                    ->insert(
                        [
                            'id_intencion'=>$input['id_contrato_cobranza'],
                            'id_usuario'=>$user_id,
                            'fecha_registro'=>date('Y-m-d H:i'),
                            'observaciones'=>$input['observaciones']
                        ]
                        );
                }catch(\Excepcion $ex)
                {
                    $mensaje='Error '.$ex->getMessage();
                    $color='error';
                }

                return response()->json(['mensaje'=>$mensaje,'color'=>$color]) ;
            }
    }
    //Historico seguimiento 
    public function historico(Request $request)
    {
        if($request->ajax())
            {
                $input=Input::all();
                
                $result=DB::table('tbl_seguimiento')
                ->select('fecha_registro','id_usuario','observaciones','nombre','apellidos')
                ->leftJoin('tbl_usuario','tbl_seguimiento.id_usuario','=','tbl_usuario.id')
                ->where('id_intencion',$input['id_contrato_cobranza'])
                ->orderBy('tbl_seguimiento.id','desc')
                ->get();

                return response()->json($result) ;
            }
    }



}