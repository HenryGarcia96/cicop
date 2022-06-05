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

class ContratosController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$lab_id=Auth::user()->__get('tbl_tienda_id');

		
		$result=DB::table('tbl_intenciones')
		->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
		'tbl_clientes.nombre','tbl_clientes.celular','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
		'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
		'mensualidades','quincenas','total',DB::raw('case when tbl_intenciones.estatus!=5 then case tbl_intenciones.tipo_pago when 1 then \'Quincenal\' when 2 then \'Mensual\' end else \'Contado\' end tipo_pago'),
		DB::raw('case tbl_intenciones.estatus when 1 then \'Activo\' when 0 then \'Cancelado\'
		 when 3 then \'Saldado\' when 4 then \'Pago Anticipado\' when 5 then \'Contado\' end estatus'),
		 'tbl_intenciones.pago_anticipado')
		->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
		->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
		->get();
		return View::make('lsintenciones')->with('intenciones',$result);
        
	}

	public function nuevo()
	{
		return View::make('contrato');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	public function create(Request $request)
	{
		if($request->ajax())
		{
			$error=0;
			$input = Input::all();
			$user_id=Auth::user()->__get('id');
			$message='La Intencion de compra ha sido guardada correctamente';
			$color='success';
			$id_contrato=0;
			try{
				$folio=$input['folio'];
				if(!empty($folio))
				{
					$res=DB::table('tbl_intenciones')->select('id')
					->where('folio',$folio)
					->get();
					
					if(isset($res[0]['id']))
					{
						$error=1;
						$message="El folio ya esta en uso";
						$color='error';
					}
				}
				if($error==0)
				{
					$tipo_venta=$input['tipo_venta'];
					if($tipo_venta==0)
					{
						$id_contrato=DB::table('tbl_intenciones')
						->insertGetId(
							[
								'id_cliente'=>$input['id_cliente'],
								'folio'=>$folio,
								'fecha'=>$input['fecha'],
								'id_usuario'=>$user_id,
								'fecha_registro'=>date('Y-m-d H:i:s'),
								'ref1'=>$input['nombre_a'],
								'ref2'=>$input['nombre_b'],
								'ref3'=>$input['nombre_c'],
								'tel1'=>$input['telefono_a'],
								'tel2'=>$input['telefono_b'],
								'tel3'=>$input['telefono_c'],
								'tipo_pago'=>$input['tipo_pago'],
								'fecha_primer_pago'=>$input['fecha_primer_pago'],
								'fecha_primer_pago_esp'=>$input['fecha_primer_pago_esp'],
								'fecha_ult_pago_esp'=>$input['fecha_ultimo_pago_esp'],
								'tipo_venta'=>$tipo_venta
							]
						);
					}else
					{
						$id_contrato=DB::table('tbl_intenciones')
						->insertGetId(
							[
								'id_cliente'=>$input['id_cliente'],
								'folio'=>$folio,
								'fecha'=>$input['fecha'],
								'id_usuario'=>$user_id,
								'fecha_registro'=>date('Y-m-d H:i:s'),
								'ref1'=>$input['nombre_a'],
								'ref2'=>$input['nombre_b'],
								'ref3'=>$input['nombre_c'],
								'tel1'=>$input['telefono_a'],
								'tel2'=>$input['telefono_b'],
								'tel3'=>$input['telefono_c'],
								'tipo_pago'=>$input['tipo_pago'],
								'fecha_primer_pago'=>$input['fecha_primer_pago'],
								'fecha_primer_pago_esp'=>$input['fecha_primer_pago_esp'],
								'fecha_ult_pago_esp'=>$input['fecha_ultimo_pago_esp'],
								'tipo_venta'=>$tipo_venta,
								'observaciones'=>$input['descripcion'],
								'fecha_pago_anticipado'=>$input['fecha_pago'],
								'fecha_registro_anticipado'=>date('Y-m-d H:i:s'),
								'forma_pago'=>$input['forma_pago'],
								'id_usuario_pago'=>$user_id,
								'institucion_bancaria'=>$input['institucion_bancaria'],
								'pago_anticipado'=>$input['importe'],
								'ticket'=>$input['ticket'],
								'estatus'=>5,
								'lote_contado'=>$input['lote'],
								'manzana_contado'=>$input['manzana']
							]
						);
					}
	
					if($id_contrato && $tipo_venta==0)
					{
						$planes=$input['plan_seleccionado'];
						foreach($planes as $plan)
						{
							$item=explode('|',$plan);
							DB::table('tbl_intenciones_detalle')
							->insert(
								[
									'id_intencion'=>$id_contrato,
									'id_plan'=>$item[0],
									'pago_mensual'=>$item[2],
									'pago_quincenal'=>$item[3],
									'no_pagos_esp'=>$item[4],
									'pago_esp'=>$item[5],
									'tipo'=>$item[1],
									'mensualidades'=>$item[6],
									'quincenas'=>$item[7],
									'total'=>$item[8],
									'enganche'=>$item[9]
								]
								);
						}
					}
				}
				
			}catch(\Excecption $ex)
			{
				$error=1;
			}
			return response()->json(['mensaje'=>$message,'error'=>$error,'color'=>$color,'id'=>$id_contrato]);
		}
		
        
	}
	public function editar(Request $request)
	{
		if($request->ajax())
		{
			$error=0;
			$input = Input::all();

			$res=DB::table('tbl_intenciones')->select('id')
			->where('folio',$input['folio'])
			->where('id','!=',$input['id_contrato'])
			->get();

			$existe=0;
			$mensaje='';
			if(isset($res[0]['id']))
			{
				$existe=1;
				$mensaje='El folio existe';
				$color='error';
				$error=1;
			}
			if($existe==0)
			{
				try{
					$affect=DB::table('tbl_intenciones')
					->where('id',$input['id_contrato'])
					->update(
						[
							'folio'=>$input['folio'],
							'N'=>$input['norte'],
							'S'=>$input['sur'],
							'E'=>$input['este'],
							'O'=>$input['oeste'],
							'metros'=>$input['metros']
						]
						);
					
					if($input['id_plan']>0)
					{
						$affect=DB::table('tbl_intenciones_detalle')
						->where('id',$input['id_plan'])
						->where('id_intencion',$input['id_contrato'])
						->update(
							[
								'manzana'=>$input['manzana'],
								'lote'=>$input['lote']
							]
							);
					}
				}catch(\Excecption $ex)
				{
					$error=1;
				}
				$mensaje='Intencion actualizada';
				$color='success';
			}
			
			return response()->json(['mensaje'=>$mensaje,'color'=>$color,'error'=>$error]);
		}
		
        
	}
	public function enganche(Request $request)
	{
		if($request->ajax())
		{
			$error=0;
			$input = Input::all();
			
			try{
				
					$affect=DB::table('tbl_intenciones_detalle')
					->where('id_intencion',$input['id_contrato_enganche'])
					->update(
						[
							'fecha_pago'=>$input['fecha_pago'],
							'forma_pago'=>$input['forma_pago'],
							'institucion_bancaria'=>$input['institucion_bancaria'],
							'observaciones'=>$input['observaciones']
						]
						);
						if($input['id_plan_enganche']>0)
						{
							$affect=DB::table('tbl_intenciones_detalle')
							->where('id',$input['id_plan_enganche'])
							->where('id_intencion',$input['id_contrato_enganche'])
							->update(
								[
									'enganche'=>$input['enganche']
								]
								);
						}
				
			}catch(\Excecption $ex)
			{
				$error=1;
			}
			return response()->json(['mensaje'=>'Intencion actualizada','error'=>$error]);
		}
		
        
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id,Request $request)
	{
		if($request->ajax())
		{
			$cliente=DB::table('tbl_clientes')->select('tbl_clientes.id',
			'tbl_clientes.nombre as cliente',
			'tbl_clientes.calle',
			'tbl_clientes.ciudad',
			'tbl_clientes.num_ext',
			'tbl_clientes.estado',
			'tbl_clientes.colonia',
			'tbl_clientes.cp',
			'tbl_clientes.celular',
			'tbl_clientes.email',
			'tbl_clientes.rfc',
			'tbl_clientes.facebook',
			'tbl_clientes.twitter',
			'tbl_clientes.fecha_nac'
			,'tbl_clientes.direccion2'
			,'tbl_clientes.direccion3'
			,'credito')
        ->where('tbl_clientes.estatus',1)
        ->where('tbl_clientes.id',$id)
        ->get();


        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json($cliente);
		}
	}

	public function getPlanesById($id,Request $request)
	{
		if($request->ajax())
		{
			$cliente=DB::table('tbl_intenciones_detalle')->select('tbl_intenciones_detalle.id',
			'tbl_intenciones_detalle.id_intencion',
			'tbl_planes.descripcion',
			'tbl_intenciones_detalle.pago_mensual',
			'tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp',
			'tbl_intenciones_detalle.pago_esp',
			'tbl_intenciones_detalle.enganche',
			'tbl_intenciones_detalle.fecha_pago',
			'tbl_intenciones_detalle.forma_pago',
			'tbl_intenciones_detalle.institucion_bancaria',
			'tbl_intenciones_detalle.observaciones',
			'tbl_intenciones.estatus',
			DB::Raw('ifnull(tbl_intenciones.N,\'\')N'),
			DB::Raw('ifnull(tbl_intenciones.S,\'\')S'),
			DB::Raw('ifnull(tbl_intenciones.E,\'\')E'),
			DB::Raw('ifnull(tbl_intenciones.O,\'\')O'),
			DB::Raw('ifnull(tbl_intenciones.metros,\'\')metros'),
			DB::Raw('ifnull(tbl_intenciones_detalle.manzana,\'\')manzana'),
			DB::Raw('ifnull(tbl_intenciones_detalle.lote,\'\')lote'))
			->leftJoin('tbl_planes','tbl_intenciones_detalle.id_plan','=','tbl_planes.id')
			->leftJoin('tbl_intenciones','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
        ->where('tbl_intenciones_detalle.id_intencion',$id)
        ->get();


        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json($cliente);
		}
	}
	public function imprimir(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_contrato'];
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'tbl_intenciones_detalle.mensualidades','tbl_intenciones_detalle.quincenas'
			,'tbl_intenciones_detalle.total','tbl_intenciones_detalle.enganche'
			,'tipo_pago','calle','ref1','ref2','ref3','tel1','tel2','tel3','direccion2','fecha_primer_pago',
			'fecha_primer_pago_esp','celular','email','rfc','tbl_planes.medidas'
			,DB::raw('tbl_planes.enganche as enganche_real'))
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
			->leftJoin('tbl_planes','tbl_intenciones_detalle.id_plan','=','tbl_planes.id')
			->where('tbl_intenciones.id',$id)
			->get();

			$int=$result[0];

			$style='<style>
			body{ font-family: Arial, Helvetica, sans-serif; font-size:11pt; margin:10mm}
			div{ width:100%; vertical-align:bottom; margin-top:15px}
			table tr td{ height:30px; }
			div.fecha{ text-align:right}
			table{ width:70%}
			label{ width:29%; display:inline-block}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{ width:100%; text-align:center; display:block;position:fixed; bottom:20}
			.titulo{ width:100%;text-align:center; position:relative;margin-top:-70px}
			.rosa_vientos{width:100%; text-align:center}
			.salto{display:block;page-break-after: always;}
			.col-3{ width:32%; display:inline-block}
			.linea{border-bottom:1px solid}
			.firma{width:40%; display:inline-block; text-align:center}
			</style>';
			$js='';

			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Carta</title>
			' . $js . $style . '</head><body>';

			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"></div><br><br>';
			$html .='<div style="text-align:center"><h3>CARTA DE INTENCION DE COMPRA</h3></div>';
			$html .='<div class="fecha">FECHA: '.$int['fecha'].'</div>';
			$html .='<div><b>DATOS DEL COMPRADOR</b></div>';
			$html .='<div><label>NOMBRE:</label><span> '.$int['nombre'].'</span></div>';
			$html .='<div><label>IDENTIFICACION:</label><span>'.$int['rfc'].'</span> </div>';
			$html .='<div><label>DIRECCION:</label><span> '.$int['calle'].'</span></div>';
			$html .='<div><label>REFERENCIAS: </label><span>'.$int['direccion2'].'</span></div>';
			$html .='<div><label>TELEFONO(S): </label><span>'.$int['celular'].'</span></div>';
			$html .='<div><label>CORREO ELECTRONICO:</label><span> '.$int['email'].'</span></div>';
			$html .='<div><b>3 REFERENCIAS PERSONALES:</b></div>';
			$html .='<div><table><tr><th><b>NOMBRE</b></th><th><b>TELEFONO</b></th></tr>
			<tr><td>'.$int['ref1'].'</td><td>'.$int['tel1'].'</td></tr>
			<tr><td>'.$int['ref2'].'</td><td>'.$int['tel2'].'</td></tr>
			<tr><td>'.$int['ref3'].'</td><td>'.$int['tel3'].'</td></tr>
			</table></div>';

			$html .='<div style="margin-top:40px"><b>UBICACI&Oacute;N DEL(OS) TERRENO(S):</b></div>';
			$anios=0;
			$enganche_total=0;
			$precio=0;
			$no_letras=0;
			$pagos=0;
			$pagos_esp=0;
			$no_pagos_esp=0;
			$medidas='';
			$enganche_real=0;
			foreach ($result as $item) {
				$html .='<div>MANZANA '.$item['manzana'].'&nbsp;&nbsp;&nbsp;LOTE '.$item['lote'].'</div>';
				$enganche_total +=$item['enganche'];
				$anios=(float)$item['mensualidades']/12;
				$precio +=$item['total'];
				$pagos_esp +=$item['pago_esp'];
				$no_pagos_esp=$item['no_pagos_esp'];
				$enganche_real +=$item['enganche_real'];
				if($item['tipo_pago']==1)
				{
					$no_letras=$item['quincenas'];
					$pagos +=$item['pago_quincenal'];
					
				}else
				{
					$no_letras=$item['mensualidades'];
					$pagos +=$item['pago_mensual'];
					
				}
				$medidas .='<div class="col-3">[x] '.$item['medidas'].'</div>';



			}
			$promocion='';
			$dif=$enganche_real-$enganche_total;
			if($dif>0)
			{
				//Calculamos la promocion
				$promocion=round((($dif/$enganche_real)*100),0).'% menos';
			}

			$fecha=strtoupper(self::formatoFecha(substr($int['fecha_primer_pago'],0,10)));
			$fecha_esp=strtoupper(self::formatoFecha(substr($int['fecha_primer_pago_esp'],0,10)));
			$formatter = new NumeroALetras();
			$letra_total=$formatter->toWords($enganche_total).' PESOS 00/100 M.N.';
			$html .='
			<div class="rosa_vientos"><img src="./local/logos/rosa_vientos.png" style="width:200px" /></div>';
			$html .='<div class="salto"></div>';
			$html .='<div style="margin-top:10mm;display:inline-block"></div>';			
			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"></div>';
			$html .='<div><b>FINANCIAMIENTO</b></div>';
			$html .='<div><div class="col-3"><b>Años: </b>'.$anios.'</div>
			<div class="col-3"><b>Enganche total: </b>'.number_format($enganche_total,2).'</div>
			<div class="col-3"><b>Promocion: </b>'.$promocion.'</div></div>';
			$html .='<div><div class="col-3"><b>Precio: </b>'.number_format($precio,2).'</div>
			<div class="col-3"><b>Letras: </b>'.$no_letras.'</div>
			<div class="col-3"><b>de: </b>'.number_format($pagos,2).'</div></div>';
			$html .='<div>'.$medidas.'</div>';
			$html .='<div><b>Fecha de inicio del primer pago :</b>'.$fecha.'</div>';
			$html .='<div><div class="col-3"><b>Pagos especiales :</b>'.$no_pagos_esp.' pagos</div><div class="col-3"><b>Cantidades: '.number_format($pagos_esp,2).'</b></div></div>';
			$html .='<div><b>Fecha de inicio del primer pago :</b>'.(($int['tipo']==1)?$fecha_esp:'').'</div>';
			$html .='<div><b>NOTA :</b></div>';
			$html .='<div class="linea">SE HACE MENCION DE QUE SE RECIBE UN ENGANCHE DE $'.number_format($enganche_total,2).' ('.$letra_total.')</div>';
			$html .='<br><div><b>OBSERVACIONES :</b></div>';
			$html .='<div class="linea"></div>';
			$html .='<div class="linea"></div>';
			$html .='<br><div><div class="linea firma">NOMBRE DEL COMPRADOR<br>'.$item['nombre'].'</div>
			<div style="width:10%;display:inline-block"></div><div class="linea firma">FIRMA<br><br></div>
			</div>';
			$html .='<div style="text-align:justify"><b>NOTA:</b><br>
			EN CASO DE RESICION DE CARTA DE INTENCION DE COMPRA-VENTA Y/O CONTRATO PRIVADO
			DE COMPRA VENTA, POR PARTE DEL COMPRADOR NO SE HARÁ DEVOLUCIÓN ALGUNA.<br><br>
			EL COMPRADOR CONSTA DE 72 HRS. PARA LIQUIDAR EL MONTO DEL ENGANCHE EN CASO DE
			UN PAGO PARCIAL DEL MISMO, ASI COMO TAMBIEN TENDRA QUE PASAR EN EL TIEMPO
			INDICADO A LAS OFICINAS DE LA COLONIA SAN LAZARO A RECOGER SU RECIBO DE ENGANCHE.
			A PARTIR DEL TERMINO DE ESTE PROCEDIMIENTO Y LA ELABORACION DEL CONTRATO-COMPRA
			VENTA, TENDRA 7 DIAS HABILES PARA PASAR A LAS OFICINAS DE LA COLONIA SAN LAZARO A
			FIRMAR SU CONTRATO DE COMPRA-VENTA, EN CASO DE NO HACERLO SERA MOTIVO DE
			CANCELACION DE LA CARTA DE INTENCION DE COMPRA SIN DEVOLUCION ALGUNA, ASI COMO
			POR PAGOS PARCIALES O INCOMPLETOS, COMO MOROSIDAD A LA FECHA PACTADA DE DICHOS
			PAGOS, LLAMESE ENGANCHE, PAGOS QUINCENALES O MENSUALES Y PARCIALES; DE NO
			CUMPLIR, SERÁ MOTIVO DE RESICION DE CONTRATO O CARTA DE INTENCION DE COMPRA
			VENTA HACIA COLONIA SAN LAZARO AC.</div>';

			$html .='<div class="pie">Calle Josefa Ortiz de Domínguez # 211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.<br>Correo electrónico coloniasanlazaro@outlook.com</div>';

			$html .='</body></html>';




        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
	public function recibo(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_contrato_enganche'];
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'fecha_pago','tbl_intenciones_detalle.forma_pago','tbl_intenciones_detalle.institucion_bancaria','enganche','tbl_intenciones_detalle.observaciones',
			'mensualidades','quincenas','total','calle','ref1','ref2','ref3','tel1','tel2','tel3','direccion2','celular','tbl_clientes.email'
			,'rfc',DB::Raw('tbl_usuario.nombre as nombre_u'),DB::Raw('tbl_usuario.apellidos as apellidos_u'))
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
			->leftJoin('tbl_usuario','tbl_intenciones.id_usuario','=','tbl_usuario.id')
			->where('tbl_intenciones.id',$id)
			->get();

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
			$lotes='';
			$importe=0;
			$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
			foreach ($result as $item) {
				$lotes .=$item['lote'].',';
				$importe +=$item['enganche'];
			}
			$formatter = new NumeroALetras();
			$letra_total=$formatter->toWords($importe).' PESOS 00/100 M.N.';
			$html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			$html .='<div class="titulo"></div>';
			$html .='<div style="text-align:center"><h3>CONTRA RECIBO DE PAGO</h3></div>';
			$html .='<div class="fecha">FECHA DE CONTRATO: '.$int['fecha'].'</div>';
			$html .='<div><b>DATOS DEL COMPRADOR</b></div>';
			$html .='<div><label>RECIBIMOS DE:</label><span> '.strtoupper($int['nombre']).'</span></div>';
			$html .='<div><label>LA CANTIDAD DE:</label><span>'.$importe.' ('.$letra_total.')</span> </div>';
			$html .='<div><label>CORRESPONDIENTE AL MES DE :</label><span> ENGANCHE DEL AÑO '.date('Y',strtotime($int['fecha'])).'</span></div>';
			
			
			$html .='<div><div class="tres"><b>MANZANA:</b> '.$int['manzana'].'</div><div class="tres"><b>LOTE:</b> '.substr($lotes,0,-1).'</div><div class="tres"> <b>EXP. NO.</b> '.$int['folio'].'</div></div>';
			$fecha_pago=strtotime($int['fecha_pago']);
			$html .='<div style="margin-top:20px; "><label >FECHA DE REALIZACIÓN DE PAGO: </label><span>'.date('d',$fecha_pago).' DE '.$meses[date('n',$fecha_pago)-1].' DEL '.date('Y',$fecha_pago).'</span></div>';
			$html .='<div style="margin-top:20px"><div class="tres"><b>FORMAS DE PAGO</b></div><div class="tres"><b>INSTITUCION BANCARIA</b></div><div class="tres"><b>OBSERVACIONES</b></div></div>';
			$html .='<div><div class="tres">'.$int['forma_pago'].'</div><div class="tres">'.$int['institucion_bancaria'].'</div><div class="tres">'.$int['observaciones'].'</div></div>';
			$html .='<div><div class="tres">FECHA DE ELABORACION '.date('Y-m-d H:i').'</div><div class="tres"></div><div class="tres"></div></div>';
            $html .='<div><div class="tres"></div><div class="tres"></div><div class="tres"><b>FOLIO DE CONTRA RECIBO: </b> RG/'.date('Y',$fecha_pago).'/'.sprintf("%08d", $int['id']).'</div></div>';
			$html .='<div style="width:40%; text-align:center; margin-top:20px"><span>'.strtoupper($int['nombre_u'].' '.$int['apellidos_u']).'</span></div>';
			$html .='<br><div style="width:40%; text-align:center">(NOMBRE Y FIRMA)</div>';
			$html .='<br><br>';
			
			//$html .='<div class="rosa_vientos"><img src="./local/logos/rosa_vientos.png" style="width:200px" /></div>';
			//$html .='<div class="pie">Calle Josefa Ortiz de Domínguez # 211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.<br>Correo electrónico coloniasanlazaro@outlook.com</div>';

			$html .='</body></html>';




        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
	
	public function contrato(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_contrato'];

			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'fecha_pago','tbl_intenciones_detalle.forma_pago','tbl_intenciones_detalle.institucion_bancaria','tbl_intenciones.observaciones','enganche','tipo_pago',
			'tbl_intenciones.N','tbl_intenciones.S','tbl_intenciones.E','tbl_intenciones.O','tbl_intenciones.metros',
			'mensualidades','quincenas','total','calle','ref1','ref2','ref3','tel1','tel2','tel3','direccion2','celular','tbl_clientes.email'
			,'rfc',DB::Raw('tbl_usuario.nombre as nombre_u'),DB::Raw('tbl_usuario.apellidos as apellidos_u')
			,'fecha_primer_pago','fecha_primer_pago_esp','fecha_ult_pago_esp'
			)
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
			->leftJoin('tbl_usuario','tbl_intenciones.id_usuario','=','tbl_usuario.id')
			->where('tbl_intenciones.id',$id)
			->get();

			$int=$result[0];

			$style='<style>
			@media print
			{
				body,html{margin:0}
				@page{margin:0mm 30mm 10mm 30mm;size:Letter}
				div.separador{display:block; page-break-after: always;}
			}
			
			
			body{ font-family: Arial, Helvetica, sans-serif; margin:0mm 26mm 10mm 26mm;
				text-align:justify; font-size:11.5pt;line-height:150%}
			div{ min-height:20px; width:100%; display:inline-block}
			div.fecha{ text-align:right;position:relative; margin-top:-3mm}
			span{ width:70%; display:inline-block; border-bottom:1px solid}
			.pie{     width: 100%;
				text-align: center;
				position: fixed;
				bottom: 10;
				font-size: 9pt;
				display: block;
				margin-left: -30mm;}
			.titulo{ width:100%;text-align:center; position:relative; margin-top:-30mm }
			.tres{ width:32%; display:inline-block}
			.lineas{ border:1px 0 1px 0 solid; text-align:center; display:inline-block}
			.header{width:100%; display:inline-block; margin-top:10mm}
			.col-61{display:inline-block; width:55%; text-align:center; font-size:11pt}
			.col-62{display:inline-block; width:44%; text-align:center; font-size:11pt}
			.vendedor{width:100%; border-top:1px solid; text-align:center}
			.comprador{width:100%; text-align:center}
			.cuadrado{width:26mm; height:26mm; border:1px solid; margin-left:5mm; display:inline-block}
			</style>';
			$js='';

			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Contrato</title>
			' . $js . $style . '</head><body>';

			$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');

			$encabezado ='<div class="header"><img src="./local/logos/superior_sicop.png" style="width:100%; " />';
			$encabezado .='<div class="titulo"></div>';
			$encabezado .='<div class="fecha">EXP No.: '.$int['folio'].'</div></div>';

			$pie ='<div class="pie">Calle Josefa Ortiz de Domínguez #211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.
			<br>Correo electrónico coloniasanlazaro@outlook.com</div>';
			

			$html .=$encabezado;
			$html .='<div style="text-align:center"><h3>CONTRATO PRIVADO DE COMPRA-VENTA</h3></div>';
			

			$html .=str_replace('@encabezado',$encabezado,file_get_contents(asset('/local/reportes/contrato.html')));
			$meses=array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');

			$manzana='';
			$lotes='lote ';
			$enganche_total=0;
			$precio=0;
			$no_letras=0;
			$pagos=0;
			$pagos_esp=0;
			$no_pagos_esp=0;
			$medidas='';
			$enganche_real=0;
			$dif=0;
			if(count($result)>1)
			{
				$lotes='lotes ';
			}
			$fecha=strtoupper(self::formatoFecha(substr($int['fecha_primer_pago'],0,10)));
			$dias=explode(' ',$fecha);
			$dias_texto=$dias[0]." DE CADA MES ";
			foreach ($result as $item) {
				$manzana=$item['manzana'];
				$lotes .=$item['lote'].', ';

				
				$enganche_total +=$item['enganche'];
				$anios=(float)$item['mensualidades']/12;
				$precio +=$item['total'];
				$pagos_esp +=$item['pago_esp'];
				$no_pagos_esp=$item['no_pagos_esp'];
				$enganche_real +=$item['enganche'];
				if($item['tipo_pago']==1)
				{
					$no_letras=$item['quincenas'];
					$pagos +=$item['pago_quincenal'];
					$dias_texto="15 Y 30 DE CADA MES ";
					
				}else
				{
					$no_letras=$item['mensualidades'];
					$pagos +=$item['pago_mensual'];
					
				}
				
			}
			$lotes = '<b>'.substr($lotes,0,-1).'</b>';
			
			
			$dif=$precio-$enganche_total;
			$formatter = new NumeroALetras();
			

			$letra_total=$formatter->toWords($precio).' PESOS';
			$letra_enganche=$formatter->toWords($enganche_real).' PESOS';
			$letra_diferencia=$formatter->toWords($dif).' PESOS';
			$letra_pago_normal=$formatter->toWords($pagos).' PESOS';
			$letra_pago_esp=$formatter->toWords($pagos_esp).' PESOS';
			$letra_metros=$formatter->toWords($int['metros']);
			$hoy=date('Y-m-d');
			$items_hoy=explode('-',$hoy);

			$fecha_contrato='<b>'.$formatter->toWords($items_hoy[2]).'</b> DIAS DEL MES DE <b>'.$meses[(int)$items_hoy[1]].'</b> DEL AÑO <b>'.$formatter->toWords($items_hoy[0]).'</b>';

			$pagos_especiales=array('','PRIMER PAGO','SEGUNDO PAGO','TERCER PAGO','CUARTO PAGO','QUINTO PAGO','SEXTO PAGO','SEPTIMO PAGO','OCTAVO PAGO','NOVENO PAGO','DECIMO PAGO');
			$descripcion_pagos='';
			

			if($int['tipo']==2)
			{
				$descripcion_pagos='';
				$no_pagos_esp=0;
				$pagos_esp=0;
			}else
			{
				$fechas_esp=self::getFechasEsp(1,$int['fecha_primer_pago_esp'],$int['fecha_ult_pago_esp'],$no_pagos_esp);
				$i=1;
				foreach ($fechas_esp as $date) {
					$date1_s=strtoupper(self::formatoFecha($date['fecha']));
					if($i==1){
						$descripcion_pagos .=' EL <b>'.$pagos_especiales[$i].'</b> ESPECIAL SER&Aacute; EL <b>'.$date1_s.'</b>,';
					}else
					{
						$descripcion_pagos .=' EL <b>'.$pagos_especiales[$i].'</b> EL <b>'.$date1_s.'</b>,';	
					}
					$i++;
				}
				$descripcion_pagos=substr($descripcion_pagos,0,-1);
			}

			$array_valores=array($int['nombre'],$int['metros'],$int['N'],$int['S'],
			$int['E'],$int['O'],$lotes,$manzana
			,$int['calle'],$int['celular'],number_format($precio,2),number_format($enganche_total,2)
			,number_format($dif,2),$no_letras,number_format($pagos,2),
			$fecha
			,$no_pagos_esp,number_format($pagos_esp,2),$dias_texto,$letra_total,$letra_enganche,
			$letra_diferencia,$letra_pago_normal,$letra_pago_esp,$letra_metros,$descripcion_pagos,$fecha_contrato);

			$array=array('@cliente','@metros','@norte','@sur',
			'@este','@oeste','@lotes','@manzana',
			'@direccion','@celular','@precio','@enganche','@diferencia','@letras','@pagos_normales'
			,'@primer_pago','@no_pagos_esp','@pagos_esp','@dias','@letra_total','@letra_enganche',
		'@letra_diferencia','@letra_pago_normal','@letra_pago_esp','@letra_metros','@descripcion_pagos','@fecha_contrato');
			
			$html = str_replace($array,$array_valores,$html);
			
			$html .=$pie.'</body></html>';




        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html]);
		}
	}
	public function numeroLetras($numero)
	{
		$formatterES = new NumberFormatter("es-ES", NumberFormatter::SPELLOUT);
		$n = $numero;
		$izquierda = intval(floor($n));
		$derecha = intval(($n - floor($n)) * 100);
		return $formatterES->format($izquierda) . " " . $formatterES->format($derecha);
	}
	public function tabla(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$id=$input['id_contrato'];
			$result=DB::table('tbl_intenciones')
			->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
			'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
			'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
			'tbl_intenciones_detalle.mensualidades','tbl_intenciones_detalle.quincenas'
			,'tbl_intenciones_detalle.total','tbl_intenciones_detalle.enganche'
			,'tipo_pago','calle','ref1','ref2','ref3','tel1','tel2','tel3','direccion2','fecha_primer_pago',
			'fecha_primer_pago_esp','fecha_ult_pago_esp','celular','tbl_clientes.email','rfc','tbl_planes.medidas',
			DB::Raw('concat(tbl_usuario.nombre,\' \',ifnull(tbl_usuario.apellidos,\'\')) as usuario')
			,DB::raw('tbl_planes.enganche as enganche_real'))
			->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
			->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
			->leftJoin('tbl_planes','tbl_intenciones_detalle.id_plan','=','tbl_planes.id')
			->leftJoin('tbl_usuario','tbl_intenciones.id_usuario','=','tbl_usuario.id')
			->where('tbl_intenciones.id',$id)
			->get();

			$int=$result[0];

			$style='<style>
			@page {size: landscape}
			body{ font-family: Arial, Helvetica, sans-serif; font-size:8pt; margin:10mm}
			div{ min-height:20px; width:100%; display:inline-block}
			table tr th,td{font-size:@font;border:1px solid; margin:0;padding:0}
			div.fecha{ text-align:right}
			table{ width:100%}
			label{display:inline-block; font-weight:bold}
			span{ display:inline-block; }
			.pie{ width:100%; text-align:center; position:fixed; bottom:10mm;display:block}
			.titulo{ wodth:100%;text-align:center; position:absolute; top:30mm}
			.rosa_vientos{width:100%; text-align:center}
			.tres{ width:32%; display:inline-block}
			div.col-12{width:100%, display:inline-block}
			div.col-11{width:92%, display:inline-block}
			.col-10{width:82.5%; display:inline-block}
			.col-9{width:74.5%; display:inline-block}
			.col-8{width:66.5%; display:inline-block}
			.col-7{width:57.5%; display:inline-block}
			.col-6{width:49.5%; display:inline-block}
			.col-5{width:41.5%; display:inline-block}
			.col-4{width:32.5%; display:inline-block}
			.col-3{width:24.5%; display:inline-block}
			.col-2{width:16.5%; display:inline-block}
			.col-1{width:7.5%; display:inline-block}

			</style>';

			if($int['tipo_pago']==1)
			{
				$style=str_replace('@font','7pt',$style);
			}else
			{
				$style=str_replace('@font','8pt',$style);
			}
			$js='';
			$pie ='<div class="pie">Calle Josefa Ortiz de Domínguez #211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.
			<br>Correo electrónico coloniasanlazaro@outlook.com</div>';
			$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Tabla de cotizacion</title>
			' . $js . $style . '</head><body>';

			

			// $html .='<img src="./local/logos/superior_sicop.png" style="width:100%" />';
			// $html .='<div class="titulo"><h2>COLONIA SAN LAZARO A.C.</h2></div>';
			$html .='<div style="text-align:center"><h3>TABLA DE COTIZACION DE FINANCIAMIENTO</h3></div>';
			
			$anios=0;
			$enganche_total=0;
			$precio=0;
			$no_letras=0;
			$pagos=0;
			$pagos_esp=0;
			$no_pagos_esp=0;
			$medidas='';
			$enganche_real=0;
			$manzanas='';
			$lotes='';
			foreach ($result as $item) {
				$manzanas .=$item['manzana'].',';
				$lotes .=$item['lote'].',';

				$enganche_total +=$item['enganche'];
				$anios=(float)$item['mensualidades']/12;
				$precio +=$item['total'];
				$pagos_esp +=$item['pago_esp'];
				$no_pagos_esp=$item['no_pagos_esp'];
				$enganche_real +=$item['enganche_real'];
				if($item['tipo_pago']==1)
				{
					$no_letras=$item['quincenas'];
					$pagos +=$item['pago_quincenal'];
					
				}else
				{
					$no_letras=$item['mensualidades'];
					$pagos +=$item['pago_mensual'];
					
				}
				$medidas .='<div class="col-3">[x] '.$item['medidas'].'</div>';



			}
			
			$html .='<div>
			<div class="col-6"><label>NOMBRE DEL CLIENTE: </label> <span> '.$int['nombre'].'</span></div>
			<div class="col-3"><label>PRECIO DEL LOTE: </label> <span>'.$precio.'</span></div>
			<div class="col-3"><label>P. NORMAL: </label> <span>'.$pagos.'</span></div></div>';
			$html .='<div>
			<div class="col-3"><label>MANZANA: </label> <span>'.$manzanas.'</span></div>
			<div class="col-2"><label>LOTES: </label> <span>'.$lotes.'</span></div>
			<div class="col-2"><label>FOLIO: </label> <span>'.$int['folio'].'</span></div>
			<div class="col-3"><label>TIPO FINANCIAMIENTO: </label> <span>'.$int['tipo'].'</span></div>
			<div class="col-2"><label>PAGOS: </label> <span>'.$no_letras.'</span></div>
			</div>';
			$html .='<div>
			<div class="col-3"><label>ENGANCHE: </label> <span>'.$enganche_total.'</span></div>
			<div class="col-3"><label>FECHA 1ER PAGO: </label> <span>'.self::formatoFecha(substr($int['fecha_primer_pago'],0,10)).'</span></div>
			<div class="col-2"><label>PAGO ESPECIAL: </label> <span>'.$pagos_esp.'</span></div>
			<div class="col-3"><label>NO. PAGOS: </label> <span>'.$no_pagos_esp.'</span></div>
			
			</div>';
			$html .='<div>
			<div class="col-6"><label>ASESOR: </label> <span>'.$int['usuario'].'</span></div>
			<div class="col-6"><label>FECHA DE ELABORACION: </label> <span>'.self::formatoFecha(date('Y-m-d')).'</span></div>
			</div>';

			$tabla='<table cellspacing="0" cellpadding="0"><thead>
			<tr><th>No.</th>
			<th>IMPORTE CREDITO</th>
			<th>PAGO MENSUAL</th>
			<th>DIFERENCIA RESTANTE</th>
			<th>FECHA DE PAGO</th>
			</tr></thead><tbody>';

			$tabla_aux='';
			$tabla2='';
			$tabla3='';
			$html .='<div style="vertical-align:top">';
			$pago_aux=$pagos;
			$importe=$precio-$enganche_total;
			$dif_enganche=$enganche_real-$enganche_total;
			$ultimo_pago=$pagos;

			if($dif_enganche>0)
			{
				$ultimo_pago=$pagos+$dif_enganche;
			}
			$cont_pago_esp=0;
			$fecha_pago_normal=date('d/m/Y',strtotime($int['fecha_primer_pago']));
			$fecha_pago_esp=date('d/m/Y',strtotime($int['fecha_primer_pago_esp']));
			$fecha_primer=$int['fecha_primer_pago'];
			
			$dia=date('j',strtotime($int['fecha_primer_pago']));
			$total=106;
			$ancho_div='col-4';
			$salto_esp=false;
			if($int['tipo_pago']==1)
			{
				$total=140;	
				$ancho_div='col-3';
			}
			
			$fechas_esp=null;
			if($int['tipo']==1)
			{
				$fechas_esp=self::getFechasEsp($int['id'],$int['fecha_primer_pago_esp'],$int['fecha_ult_pago_esp'],$no_pagos_esp);
			}
			$fechas_norm=self::getFechas($int['id'],$int['fecha_primer_pago'],$no_letras,$int['tipo_pago']);

			
			$i=1;
			$cont_esp=1;
			$cont=0;
			$contb=0;
			// if($fecha_pago_normal>$fecha_pago_esp)
			// {
				//Recorremos primero pagos_especiales
				if(count($fechas_esp)>1){

					for($j=0;$j<=count($fechas_esp);$j++)
					{
						if(isset($fechas_esp[$j])){
							$fecha_esp=$fechas_esp[$j]['fecha'];
							for($i=$cont;$i<=count($fechas_norm);$i++)
							{
								if(isset($fechas_norm[$i])){
									$fecha_norm=$fechas_norm[$i]['fecha'];
									if($fecha_norm<$fecha_esp)
									{
										$dif=$importe-$pagos;
										$tabla_aux .='<tr><td style="text-align:center">'.$i.'</td><td style="text-align:right">'.number_format($importe,2).'</td><td style="text-align:right">'.number_format($pago_aux,2).'</td><td style="text-align:right">'.number_format($dif,2).'</td><td style="text-align:right">'.$fecha_norm.'</td></tr>';
										$importe = $importe-$pagos;
										$contb++;
										if($contb==40 || $contb==80 || $contb==120)
										{
											$html .='<div class="'.$ancho_div.'">'.$tabla.$tabla_aux.'</tbody></table></div>';
											$tabla_aux='';
										}

									}else
									{
										$dif=$importe-$pagos_esp;
										$tabla_aux .='<tr><td style="text-align:center">E '.$cont_esp.'</td><td style="text-align:right">'.number_format($importe,2).'</td><td style="text-align:right">'.number_format($pagos_esp,2).'</td><td style="text-align:right">'.number_format($dif,2).'</td><td style="text-align:right">'.$fecha_esp.'</td></tr>';
										$cont_esp++;
										$importe = $importe-$pagos_esp;	
										$contb++;
										if($contb==40 || $contb==80 || $contb==120)
										{
											$html .='<div class="'.$ancho_div.'" style="vertical-align:top">'.$tabla.$tabla_aux.'</tbody></table></div>';
											$tabla_aux='';
										}
										break;		
									}
								}
							}
							$cont=$i;
							
							
						}
					}
				}
				for($i=$cont;$i<=count($fechas_norm);$i++)
						{
							if(isset($fechas_norm[$i])){
									$fecha_norm=$fechas_norm[$i]['fecha'];
									$dif=$importe-$pagos;
									$tabla_aux .='<tr><td style="text-align:center">'.$i.'</td><td style="text-align:right">'.number_format($importe,2).'</td><td style="text-align:right">'.number_format($pago_aux,2).'</td><td style="text-align:right">'.number_format($dif,2).'</td><td style="text-align:right">'.$fecha_norm.'</td></tr>';
									$importe = $importe-$pagos;
									$contb++;
										if($contb==40 || $contb==80 || $contb==120)
										{
											$html .='<div class="'.$ancho_div.'" style="vertical-align:top">'.$tabla.$tabla_aux.'</tbody></table></div>';
											$tabla_aux='';
										}
							}
						}

			
			$html .='<div class="'.$ancho_div.'" style="vertical-align:top">'.$tabla.$tabla_aux.'</tbody></table></div>';

			
			// $html .='<div class="col-4">'.$tabla.'</div>';
			// $html .='<div class="col-4">'.$tabla.'</div>';
			// $html .='<div class="col-4">'.$tabla.'</div>';
			$html .='</div>'.$pie;
			$html .='</body></html>';

			$check=DB::table('tbl_pagos')->select('id_intencion')
			->where('id_intencion',$int['id'])
			->get();
			$res=null;
			if(!isset($check[0]['id_intencion'])){
				DB::table('tbl_pagos')->insert($fechas_norm);
				DB::table('tbl_pagos')->insert($fechas_esp); 
			}
			

        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json(['html'=>$html,'fechas'=>$fechas_norm,'fechas_esp'=>$fechas_esp]);
		}
	}

	public function getFechasEsp($id,$fecha_primer,$ultimo_pago,$no_pagos_esp)
	{
		$fechas=array();
		$date1=date('Y-m-d',strtotime($fecha_primer));
        $dia=date('j',strtotime($fecha_primer));
		//$date2=date('Y-m-d',strtotime($fecha_pago_esp));
		$cont_pago_esp=0;
		$cont=1;

		$fechas[$cont]=array('id_intencion'=>$id,'fecha'=>$date1,'tipo'=>2,'indice'=>1);
		for($cont_pago_esp=1;$cont_pago_esp<($no_pagos_esp-1);$cont_pago_esp++)
		{
			$date2 = date('Y-m-d', strtotime($date1. ' + '.$cont_pago_esp.' year'));
			$fechas[]=array('id_intencion'=>$id,'fecha'=>$date2,'tipo'=>2,'indice'=>($cont_pago_esp+1));
			
		}
		$fechas[]=array('id_intencion'=>$id,'fecha'=>date('Y-m-d',strtotime($ultimo_pago)),'tipo'=>2,'indice'=>($cont_pago_esp+1));
		return $fechas;
	}

	public function getFechas($id,$fecha_pago,$no_pagos,$tipo_pago){
		$fechas=array();
		$date1=date('Y-m-d',strtotime($fecha_pago));
		
        $dia=date('j',strtotime($fecha_pago));
		$cont_pago_esp=0;
		$cont=1;
		for($i=1;$i<=$no_pagos;$i++)
		{
			$fechas[$cont]=array('id_intencion'=>$id,'fecha'=>$date1,'tipo'=>1,'indice'=>$cont);
			
			if($tipo_pago==1) //Quincenal
			{
				$date_aux=date('d/m/Y',strtotime($date1));
				
				$items=explode('-',$date1);
				
				
				if($items[2]==30 || $items[2]==28 || $items[2]==29)
				{
					$next_day=15;
				}else
				{
					$next_day=30;
					if($items[1]==2 || $items[1]=='02')
					{
						$last_day=date('Y-m',strtotime($items[0].'-'.$items[1].'-'.$items[1].' +1 month')).'-01';
						
						$next_day=date('d',strtotime($last_day.' -1 day'));
					}
				}
				

				if($next_day==15)
				{
					
					$sig=date('Y-m-d',strtotime($items[0].'-'.$items[1].'-'.$next_day.' +1 month'));
				}else
				{
					
					$sig=date('Y-m-d',strtotime($items[0].'-'.$items[1].'-'.$next_day));
				}
				
				$date1=$sig;
				
				
			}else{ //Mensual
					$campos=explode('-',$date1);
                    $str_date=$campos[0].'-'.$campos[1].'-'.$campos[2];
                    
					if(date('n',strtotime($str_date))==1 && $dia==30)
					{
						$last_day=date('Y-m',strtotime($str_date.' + 2 month')).'-01';
						$date1=date('Y-m-d',strtotime($last_day.' - 1 day'));
					}else{
						$date1 = date('Y-m-d', strtotime($fecha_pago. ' + '.$i.' month'));				
					}
			}
			$cont++;
		}
		return $fechas;
	}

	
	public function formatoFecha($fecha)
	{
		$meses=array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');

		$items=explode('-',$fecha);

		return $items[2].' de '.ucfirst(strtolower(($meses[(int)$items[1]]))).' de '.$items[0];
	}

	public function cancelar(Request $request,$id)
	{
		if($request->ajax())
		{
			$user_id=Auth::user()->__get('id');
			$color='success';
			$error=0;
			$input=Input::all();
			$status=['Cancelado','Activado'];
			$estatus=($input['estatus']==1)?0:1;
			try{

				$affected=DB::table('tbl_intenciones')
				->where('id', $id)
				->update(array('estatus'=>$estatus));

				DB::table('tbl_log_cancelacion')
				->insert([
					'id_intencion'=>$id,
					'estatus'=>$estatus,
					'fecha_registro'=>date('Y-m-d H:i:s'),
					'id_usuario'=>$user_id
				]);

				$mensaje='El contrato ha sido '.$status[$estatus];
			}catch(\Exception $ex)
            {
                $error=1;
                $mensaje=$ex->getMessage();
                $color='error';
            }

			return response()->json(['mensaje'=>$mensaje,'error'=>$error,'color'=>$color]) ;
		}
	}
	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id,Request $request )
	{
		if($request->ajax())
		{
			$mensaje='La venta ha sido eliminada';
			$color='success';
			$error=0;
			try{
				$affected=DB::table('tbl_intenciones')
				->where('id', $id)
				->delete();
			}catch(\Excepcion $ex)
			{
				$mensaje=$ex->getMessage();
				$error=1;
				$color='danger';
			}
			return response()->json(['mensaje'=>$mensaje,'error'=>$error,'color'=>$color]) ;
		}

	}

	/**
	 * Buscar.
	 *
	 * @param  int  $id
	 * @return Response
	 */


	

	public function buscar(Request $request)
	{
		if($request->ajax())
		{
			$lab_id=Auth::user()->__get('tbl_tienda_id');
			$input = Input::all();

			$query=DB::table('tbl_intenciones')
		->select('tbl_intenciones.id','tbl_intenciones.folio','tbl_intenciones.id_cliente',DB::Raw('DATE(tbl_intenciones.fecha) fecha'),
		'tbl_clientes.nombre','tbl_intenciones_detalle.pago_mensual','tbl_intenciones_detalle.pago_quincenal',
		'tbl_intenciones_detalle.no_pagos_esp','tbl_intenciones_detalle.pago_esp','tbl_intenciones_detalle.tipo','manzana','lote',
		'mensualidades','quincenas','total',DB::raw('case when tbl_intenciones.estatus!=5 then case tbl_intenciones.tipo_pago when 1 then \'Quincenal\' when 2 then \'Mensual\' end else \'Contado\' end tipo_pago'),
		DB::raw('case tbl_intenciones.estatus when 1 then \'Activo\' when 0 then \'Cancelado\'
		 when 3 then \'Saldado\' when 4 then \'Pago Anticipado\' when 5 then \'Contado\' end estatus'),
		 'tbl_intenciones.pago_anticipado')
		->leftJoin('tbl_intenciones_detalle','tbl_intenciones.id','=','tbl_intenciones_detalle.id_intencion')
		->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
		->where(function($query) use ($input)
            {
                $query->orWhere('tbl_clientes.nombre','like','%'.$input['buscar'].'%')
								->orWhere('tbl_intenciones.folio','like','%'.$input['buscar'].'%');
            });
		
		if(isset($input['incluirFechas']))
		{
			$query=$query->whereBetween('tbl_intenciones.fecha', [$input['start'].' 00:00:00', $input['end'].' 23:59:59']);
		}
			
		$results=$query->get();


		return response()->json($results) ;
		}
	}

	public function historial($id)
	{
		$cliente=DB::table('tbl_clientes')->select('id','nombre')->where('id',$id)->get();

		//Traemos la compras que realizo el cliente  y el monto que debe
		$query=DB::table('tbl_ventas')->where('tbl_cliente_id',$id)->where('tipo_venta',4);

		$query->leftJoin(DB::raw('(select tbl_venta_id,sum(tbl_abonos.abono) as abonos from tbl_abonos
		where tbl_venta_id in(select id from tbl_ventas where tipo_venta=4 and tbl_cliente_id='.$id.') group by tbl_venta_id) as abonos'),
		'tbl_ventas.id','=','abonos.tbl_venta_id');

		$query->leftJoin(DB::raw('(select tbl_venta_id,sum(tbl_ventas_detalle.precio*tbl_ventas_detalle.cantidad) as total,
		sum(tbl_ventas_detalle.descuento) as descuentos
		from tbl_ventas_detalle
		where tbl_venta_id in(select id from tbl_ventas where tipo_venta=4 and tbl_cliente_id='.$id.') group by tbl_venta_id) as detalle'),
		'tbl_ventas.id','=','detalle.tbl_venta_id');


		$compras=$query->select('tbl_ventas.id','tbl_ventas.fecha','abonos.abonos','detalle.total','detalle.descuentos')->get();


		return View::make('historial')->with('clientes',$cliente[0])->with('compras',$compras);
	}


}