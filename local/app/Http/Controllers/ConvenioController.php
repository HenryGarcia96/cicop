<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use View;


class ConvenioController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id=0)
	{
        $folio="";
        if($id>0)
        {
            $res=DB::table('tbl_intenciones')->select('folio')
            ->where('id',$id)
            ->get();

            if(isset($res[0]['folio']))
            {
                $folio=$res[0]['folio'];
            }
        }
        return View::make('convenio')->with('folio',$folio);
	}
 	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
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

                //Fechas de pagos mensuales o quincenales
                $num_pagos=0;
                $tipo_pago=$results[0]['tipo_pago'];
                if($tipo_pago==1)
                {
                    $num_pagos=$results[0]['quincenas'];
                }else{
                    $num_pagos=$results[0]['mensualidades'];
                }

                
                // $fechas=self::getFechas($results[0]['fecha_primer_pago'],$num_pagos,$tipo_pago,false);

                // //Fechas de pagos especiales
                // $num_pagos=$results[0]['no_pagos_esp'];
                // if($results[0]['tipo']==1)
                // {
                //     $fechas_esp=self::getFechas($results[0]['fecha_primer_pago_esp'],$num_pagos,$tipo_pago,true);
                // }

                $pagos_generales=DB::table('tbl_pagos')
                ->select('tbl_pagos.id','tipo','importe',DB::raw('DATE(fecha) fecha'),'id_usuario','forma_pago',
                'institucion_bancaria','ticket',DB::raw('DATE(fecha_pago) fecha_pago'),'tbl_usuario.nombre')
                ->leftJoin('tbl_usuario','tbl_pagos.id_usuario','=','tbl_usuario.id')
                ->where('id_intencion',$results[0]['id'])
                //->where('importe','>',0)
                ->orderBy('id')
                ->get();

                $adeudos=DB::table('tbl_pagos')
                ->select('tbl_pagos.id_intencion'
                ,'folio'
                ,'nombre'
                ,DB::raw('case tbl_intenciones.tipo_pago when 1 then \'Quincenal\' when 2 then \'Mensual\' end tipo_pago')
                ,DB::raw('date(tbl_pagos.fecha) fecha')
                ,'pagos.mensual'
                ,'pagos.quincenal'
                ,'pagos.especiales'
                ,'tbl_pagos.tipo')
                ->leftJoin('tbl_intenciones','tbl_pagos.id_intencion','=','tbl_intenciones.id')
                ->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
                ->leftJoin(DB::raw('(select id_intencion,sum(pago_mensual) mensual,sum(pago_quincenal) quincenal,sum(pago_esp) especiales from tbl_intenciones_detalle group by id_intencion) pagos'),'pagos.id_intencion','=','tbl_pagos.id_intencion')
                ->whereRaw("date(tbl_pagos.fecha)<'".date('Y-m-d')."' and date(tbl_pagos.fecha)>'2021-01-01'  and tbl_pagos.importe is null")
                ->where('tbl_intenciones.estatus',1)
                ->where('tbl_intenciones.id',$results[0]['id'])
                ->orderBy('tbl_intenciones.id','asc')
                ->orderBy('tbl_pagos.fecha','asc')
                ->get();
            }


            $tbl_anticipado=DB::table('tbl_anticipado')->select('mes_inicial','mes_final','precio')
            ->get();


            

            

		return response()->json(['intencion'=>$results,'pagos'=>$pagos,'anticipado'=>$tbl_anticipado,'pagos_generales'=>$pagos_generales,'adeudos'=>$adeudos]) ;
		}
	}
	public function create(Request $request)
	{
		if($request->ajax())
		{
        $user_id=Auth::user()->__get('id');
		$error=0;
        $color='success';
        $mensaje='';
		$input = Input::all();
		$id_cliente=$input['id_cliente'];
        $id_contrato=$input['id_contrato'];
        $id_convenio=$input['id_convenio'];
		try{
		if($id_convenio>0){
			$affected=DB::table('tbl_convenios')
		->where('id',$id_convenio)
		->update(
			    [
                    'fecha'=>$input['fecha'],
                    'manzana'=>$input['manzana'],
                    'lote'=>$input['lote'],
                    'cantidad_contratada'=>$input['precio'],
                    'cantidad_pagada'=>$input['pagado'],
                    'cantidad_adeuda'=>$input['deuda'],
                    'meses_rezago'=>$input['meses_rezago'],
                    'meses_rezago_text'=>$input['meses_rezago_text'],
                    'esp_rezago'=>$input['pagos_especiales'],
                    'esp_rezago_text'=>$input['pagos_especiales_text'],
                    'monto_rezago'=>$input['rezago_mensualidades'],
                    'monto_rezago_esp'=>$input['rezago_especiales'],
                    'recargos'=>$input['recargos'],
                    'total_rezagos'=>$input['total_rezago'],
                    'documento'=>$input['documento']
			    ]
			);
            $error=0;
            $mensaje='Registro actualizado';
		}else{
        	$id_convenio=DB::table('tbl_convenios')->insertGetId(
			    [
                    'id_intencion'=>$id_contrato,
                    'fecha'=>$input['fecha'],
                    'manzana'=>$input['manzana'],
                    'lote'=>$input['lote'],
                    'cantidad_contratada'=>$input['precio'],
                    'cantidad_pagada'=>$input['pagado'],
                    'cantidad_adeuda'=>$input['deuda'],
                    'meses_rezago'=>$input['meses_rezago'],
                    'meses_rezago_text'=>$input['meses_rezago_text'],
                    'esp_rezago'=>$input['pagos_especiales'],
                    'esp_rezago_text'=>$input['pagos_especiales_text'],
                    'monto_rezago'=>$input['rezago_mensualidades'],
                    'monto_rezago_esp'=>$input['rezago_especiales'],
                    'recargos'=>$input['recargos'],
                    'total_rezagos'=>$input['total_rezago'],
                    'documento'=>$input['documento'],
                    'fecha_registro'=>date('Y-m-d'),
                    'id_usuario'=>$user_id,
                    'id_cliente'=>$id_cliente

			    ]
			);
            $error=0;
            $mensaje='Registro realizado';
		}
        
	}catch(\Excecption $ex)
	{
		$error=1;
        $mensaje=$ex->getMessage();
        $color='danger';
	}
        
		return response()->json(['mensaje'=>$mensaje,'color'=>$color,'error'=>$error,'id_convenio'=>$id_convenio]);
	}
		
        
	}

    public function getConvenios($id,Request $request)
    {
        if($request->ajax())
		{
			$result=DB::table('tbl_convenios')
            ->select('tbl_convenios.id',
            'tbl_convenios.id_intencion',
            'tbl_convenios.id_usuario',
            DB::raw('date(tbl_convenios.fecha) fecha'),
            'tbl_usuario.nombre',
            'tbl_usuario.apellidos')
            ->leftJoin('tbl_usuario','tbl_convenios.id_usuario','=','tbl_usuario.id')
            ->where('tbl_convenios.id_intencion',$id)
            ->orderBy('tbl_convenios.fecha_registro')
            ->get();
			return response()->json(['convenios'=>$result]);
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
            $result=DB::table('tbl_convenios')
            ->select(
                    'id',
			        'id_intencion',
                    DB::raw('date(fecha) fecha'),
                    'manzana',
                    'lote',
                    'cantidad_contratada',
                    'cantidad_pagada',
                    'cantidad_adeuda',
                    'meses_rezago',
                    'meses_rezago_text',
                    'esp_rezago',
                    'esp_rezago_text',
                    'monto_rezago',
                    'monto_rezago_esp',
                    'recargos',
                    'total_rezagos',
                    'documento',
                    'fecha_registro',
                    'id_usuario',
                    'id_cliente')
                    ->where('id',$id)
                    ->get();
			return response()->json(['convenio'=>$result]);
		}
	}

	public function imprimir($id,Request $request)
	{
		if($request->ajax())
		{
            $result=DB::table('tbl_convenios')
            ->select(
                'tbl_convenios.id','id_intencion','folio',DB::raw('date(tbl_convenios.fecha) fecha'),
                'manzana','lote','cantidad_contratada',
                'cantidad_pagada','cantidad_adeuda','meses_rezago',
                'meses_rezago_text','esp_rezago','esp_rezago_text',
                'monto_rezago','monto_rezago_esp','recargos',
                'total_rezagos','documento',
                'tbl_convenios.id_cliente','tbl_clientes.nombre')
                ->leftJoin('tbl_clientes','tbl_convenios.id_cliente','=','tbl_clientes.id')
                ->leftJoin('tbl_intenciones','tbl_convenios.id_intencion','=','tbl_intenciones.id')
                ->where('tbl_convenios.id',$id)
                ->get();
            
            $style='<style>
            
            body{ font-family: Arial, Helvetica, sans-serif; font-size:11pt; margin:10mm;border:3px double; padding:2mm }
            div{ min-height:20px; width:100%; display:inline-block}
            
            table tr td{ height:40px; }
            div.fecha{ text-align:right}
            div.fila{margin-top:3mm}
            table{ width:70%}
            label{ width:29%; display:inline-block}
            span{ width:70%; display:inline-block; border-bottom:1px solid}
            .pie{ width:90%; text-align:center; position:fixed; bottom:14mm;display:block}
            .titulo{ width:100%;text-align:center; margin-top:-25mm}
            .rosa_vientos{width:100%; text-align:center}
            .tres{ width:32%; display:inline-block}
            .dos{width:64%; display:inline-block}
            </style>';
            $js='';
            $data=$result[0];
            $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Convenio '.$data['folio'].'</title>
            ' . $js . $style . '</head><body>';
            $lotes='';
            $importe=0;
            $meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
            
            
            $fecha=strtotime($data['fecha']);
            // $formatter = new NumeroALetras();
            // $letra_total=$formatter->toWords($importe).' PESOS 00/100 M.N.';
            $html .='<img src="./../local/logos/superior_sicop.png" style="width:100%" />';
            $html .='<div class="titulo"><h2>COLONIA SAN LAZARO A.C.</h2></div>';
            $html .='<div style="text-align:center"><h3>CONVENIO DE PROMESA DE PAGO</h3></div>';
            $html .='<div class="fecha">FOLIO: C'.$data['id'].'/'.$data['folio'].'</div>';
            $html .='<div class="fila">H. C&Aacute;RDENAS, TABASCO A <b>'.date('d',$fecha).'</b> DE <b>'.$meses[date('n',$fecha)-1].'</b> DEL <b>'.date('Y',$fecha).'</b></div>';
            $html .='<div class="fila">CLIENTE: <b>'.$data['nombre'].'</b></div>';
            $html .='<div class="fila"><div class="tres">MANZANA: <b>'.$data['manzana'].'</b></div><div class="tres">LOTE(S): <b>'.$data['lote'].'</b></div><div class="tres">FOLIO CONTRATO: <b>'.$data['folio'].'</b></div></div>';
            $html .='<div class="fila">CANTIDAD CONTRATADA: <b>'.$data['cantidad_contratada'].'</b></div>';
            $html .='<div class="fila">CANTIDAD PAGADA: <b>'.$data['cantidad_pagada'].'</b></div>';
            $html .='<div class="fila">CANTIDAD QUE ADEUDA: <b>'.$data['cantidad_adeuda'].'</b></div>';
            
            $html .='<div class="fila"><div class="tres">MESES DE REZAGO: <b>'.$data['meses_rezago'].'</b></div><div class="dos">'.$data['meses_rezago_text'].'</div></div>';
            $html .='<div class="fila"><div class="tres">PAGOS ESPECIALES: <b>'.$data['esp_rezago'].'</b></div><div class="dos">'.$data['esp_rezago_text'].'</div></div>';
            $html .='<div class="fila">REZAGO MENSUALIDADES: <b>'.$data['monto_rezago'].'</b></div>';
            $html .='<div class="fila">REZAGO PAGOS ESPECIALES: <b>'.$data['monto_rezago_esp'].'</b></div>';
            $html .='<div class="fila">RECARGOS: <b>'.$data['recargos'].'</b></div>';
            $html .='<div class="fila">TOTAL DE REZAGO: <b>'.$data['total_rezagos'].'</b></div>';
            $html .='<div class="fila" style="text-align:justify">POR MEDIO DE LA PRESENTE ME COMPROMETO A PAGAR:<br>'.$data['documento'].'</div>';
            $html .='<div class="fila" style="text-align:center;font-weight:bold">DE NO CUMPLIR CON ESTE COMPROMISO DE PAGO; CON LA CANTIDAD Y FECHA ESTABLECIDA, ACEPTO LA RECISIÓN DE MI CONTRATO, COMO LO MENCIONA LA CLAUSULA SEPTIMA DEL MISMO.
            </div>';
            //$html .='<div class="rosa_vientos"><img src="./local/logos/rosa_vientos.png" style="width:200px" /></div>';
            $html .='<div class="pie">Calle Josefa Ortiz de Domínguez # 211, Cárdenas, Tabasco.  Teléfonos Cel. 937 283 5004, 937 283 5064.<br>Correo electrónico coloniasanlazaro@outlook.com</div>';

            $html .='</body></html>';
			return response()->json(['html'=>$html]);
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
			$affected=DB::table('tbl_clientes')
            ->where('id', $id)
            ->update(array('estatus'=>'0'));
			return 'El cliente ha sido eliminado '.$id;
		}

	}


}
