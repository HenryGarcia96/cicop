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
use Excel;
use File;

class ReportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('reporte');
	}

	public function chart()
	{
		return View::make('chart');
	}

	public function corte()
	{
		return View::make('corte');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	public function create(Request $request)
	{
		
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
			$results=[];
		if($input['tipo']<3){
            // 1 Quincenal 2 Mensual
            $query=DB::table('tbl_pagos')
            ->select('tbl_pagos.id_intencion'
            ,'folio'
            ,'nombre'
            ,DB::raw('case tbl_intenciones.tipo_pago when 1 then \'Quincenal\' when 2 then \'Mensual\' end tipo_pago')
            ,DB::raw('date(tbl_pagos.fecha) fecha')
            ,'pagos.mensual'
            ,'pagos.quincenal'
            ,'pagos.especiales'
			,DB::Raw('0 as importe')
            ,'tbl_pagos.tipo')
            ->leftJoin('tbl_intenciones','tbl_pagos.id_intencion','=','tbl_intenciones.id')
            ->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
            ->leftJoin(DB::raw('(select id_intencion,sum(pago_mensual) mensual,sum(pago_quincenal) quincenal,sum(pago_esp) especiales from tbl_intenciones_detalle group by id_intencion) pagos'),'pagos.id_intencion','=','tbl_pagos.id_intencion')
			->where('tbl_intenciones.estatus',1)
			->whereRaw('tbl_pagos.importe is null')
			->where('tbl_pagos.tipo',1)
            ->where(function($query) use ($input)
            {
                $query->orWhere('tbl_clientes.nombre','like','%'.$input['buscar'].'%')
				->orWhere('tbl_intenciones.folio','like','%'.$input['buscar'].'%');
            });
		
			if(isset($input['incluirFechas']))
			{
				$query=$query->whereBetween('tbl_pagos.fecha', [$input['start'].' 00:00:00', $input['end'].' 23:59:59']);
			}
			if($input['tipo']>0)
			{
				$query=$query->where('tbl_intenciones.tipo_pago', $input['tipo']);
			}
			
			$results=$query->orderBy('tbl_intenciones.id','asc')
            ->orderBy('tbl_pagos.fecha','asc')
            ->get();
		}
		$especiales=[];
		if($input['tipo']<=0 || $input['tipo']==3){
			$query=DB::table('tbl_pagos')
            ->select('tbl_pagos.id_intencion'
            ,'folio'
            ,'nombre'
            ,DB::raw('case tbl_intenciones.tipo_pago when 1 then \'Quincenal\' when 2 then \'Mensual\' end tipo_pago')
            ,DB::raw('date(tbl_pagos.fecha) fecha')
            ,DB::raw('0 mensual')
            ,DB::raw('0 quincenal')
			,DB::raw('pagos.especiales')
            ,DB::raw('tbl_pagos_esp.importe')
            ,'tbl_pagos.tipo')
            ->leftJoin('tbl_intenciones','tbl_pagos.id_intencion','=','tbl_intenciones.id')
            ->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
            ->leftJoin('tbl_pagos_esp','tbl_pagos.id','=','tbl_pagos_esp.id_pago')
			->leftJoin(DB::raw('(select id_intencion,sum(pago_mensual) mensual,sum(pago_quincenal) quincenal,sum(pago_esp) especiales from tbl_intenciones_detalle group by id_intencion) pagos'),'pagos.id_intencion','=','tbl_pagos.id_intencion')
			->where('tbl_intenciones.estatus',1)
			->whereRaw('tbl_pagos.importe is null')
			->where('tbl_pagos.tipo',2)
            ->where(function($query) use ($input)
            {
                $query->orWhere('tbl_clientes.nombre','like','%'.$input['buscar'].'%')
				->orWhere('tbl_intenciones.folio','like','%'.$input['buscar'].'%');
            });
		
			if(isset($input['incluirFechas']))
			{
				$query=$query->whereBetween('tbl_pagos.fecha', [$input['start'].' 00:00:00', $input['end'].' 23:59:59']);
			}
			
			
			$especiales=$query->orderBy('tbl_intenciones.id','asc')
            ->orderBy('tbl_pagos.fecha','asc')
            ->get();
		}


		    return response()->json(['normales'=>$results,'especiales'=>$especiales]) ;
		}
	}

	public function buscar_corte(Request $request)
	{
		if($request->ajax())
		{
			$input = Input::all();
			
			$start=$input['start'].' 00:00:00';
			$end=$input['end'].' 23:59:59';
		
			$results=DB::select(DB::raw("(select ti.folio,ti.id_cliente,tc.nombre,tid.manzana,tid.lote, 
			tp.forma_pago,tp.fecha,tp.fecha_pago,tp.fecha_registro,tp.tipo,
			case when tp.tipo=1 then case when ti.tipo_pago=1 then 'Quincena' else 'Mensualidad' end else ttp.descripcion end descripcion,tp.ticket,tp.importe,tp.institucion_bancaria
			from tbl_pagos tp
			left join tbl_intenciones ti on tp.id_intencion=ti.id
			left join tbl_clientes tc on ti.id_cliente=tc.id
			left join tbl_intenciones_detalle tid on ti.id=tid.id_intencion
			left join tbl_tipo_pago ttp on tp.tipo=ttp.id
			where tp.fecha_registro between '$start' and '$end'
			and ti.folio is not null group by ti.id,tp.fecha_registro)
			union all 
			(select ti.folio,ti.id_cliente,tc.nombre,tid.manzana,tid.lote, 
			tp.forma_pago,tp.fecha,tp.fecha_pago_esp,tp.fecha_registro,2,'Pago Esp',tp.ticket,tp.importe,tp.institucion_bancaria
			from tbl_pagos_esp tp
			left join tbl_pagos on tbl_pagos.id=tp.id_pago
			left join tbl_intenciones ti on tbl_pagos.id_intencion=ti.id
			left join tbl_clientes tc on ti.id_cliente=tc.id
			left join tbl_intenciones_detalle tid on ti.id=tid.id_intencion
			where tp.fecha_registro between '$start' and '$end')
			union all
			(select ifnull(ti.folio,'') folio,ti.id_cliente,tc.nombre,ifnull(ti.manzana_contado,'')manzana,ifnull(ti.lote_contado,'')lote, 
			ti.forma_pago,ti.fecha_pago_anticipado,ti.fecha_pago_anticipado,ti.fecha_registro_anticipado,100,case ti.estatus when 5 then 'Contado' else 'Pago Anticipado' end,ti.ticket,ti.pago_anticipado,ti.institucion_bancaria
			from tbl_intenciones ti
			left join tbl_clientes tc on ti.id_cliente=tc.id
			left join tbl_intenciones_detalle tid on ti.id=tid.id_intencion
			where ti.fecha_registro_anticipado between '$start' and '$end')
			"));

			if(file_exists(base_path() . "/reportes/corte.xls"))
			{
				unlink(base_path() . "/reportes/corte.xls");
			}
            
			Excel::create('corte', function($excel) use($results) {
				$excel->sheet('Corte', function($sheet) use ($results) {
					$sheet->loadView('exceldiario')->with('result',$results);
				});
			})->store('xls', base_path() . "/reportes/", false);

		    return response()->json($results);
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

	public function buscar_chart(Request $request)
	{
		if($request->ajax())
		{
			$input=Input::all();
			$start=$input['start'].' 00:00:00';
			$end=$input['end'].' 23:59:59';

			$atrasos=DB::select(DB::raw("select id_intencion,ti.tipo_pago,count(*) atrasos from tbl_pagos 
			left join tbl_intenciones ti on tbl_pagos.id_intencion=ti.id
			where tbl_pagos.fecha between '$start' and '$end'
			and tbl_pagos.tipo=1 and (tbl_pagos.importe is null or tbl_pagos.importe<=0)
			group by id_intencion,ti.tipo_pago"));
			
			$aldia=DB::select(DB::raw("select ti.tipo_pago,count(*) atrasos from tbl_pagos 
			left join tbl_intenciones ti on tbl_pagos.id_intencion=ti.id
			where tbl_pagos.fecha between '$start' and '$end'
			and tbl_pagos.tipo=1 and tbl_pagos.importe>0
			group by ti.tipo_pago"));

			$cancelaciones=DB::select(DB::raw("select ifnull((select count(*) total from tbl_log_cancelacion where id_intencion in (select id from tbl_intenciones where estatus=0) 
			and fecha_registro between '$start' and '$end'),0)total"));

			$convenios=DB::select(DB::raw("select count(distinct(id_intencion)) total from tbl_convenios where fecha_registro between '$start' and '$end'"));

			$esp_atrasos=DB::select(DB::raw("select year(tbl_pagos.fecha)anio,2 as tipo,count(*) atrasos
			from tbl_pagos 
			left join (select id_pago,sum(tbl_pagos_esp.importe) total from tbl_pagos_esp 
			group by id_pago) pagos on tbl_pagos.id=pagos.id_pago
			left join tbl_intenciones on tbl_pagos.id_intencion = tbl_intenciones.id 
			left join tbl_clientes on tbl_clientes.id = tbl_intenciones.id_cliente 
			where tbl_pagos.tipo=2 and tbl_pagos.fecha <'$end'
			and tbl_intenciones.folio is not null
			and tbl_intenciones.estatus=1
			and (pagos.total is null or pagos.total=0)
			group by year(tbl_pagos.fecha)
			order by year(tbl_pagos.fecha)"));

			$esp_aldia=DB::select(DB::raw("select 2 as tipo,count(*) atrasos
			from tbl_pagos 
			left join (select id_pago,sum(tbl_pagos_esp.importe) total from tbl_pagos_esp 
			group by id_pago) pagos on tbl_pagos.id=pagos.id_pago
			left join tbl_intenciones on tbl_pagos.id_intencion = tbl_intenciones.id 
			left join tbl_clientes on tbl_clientes.id = tbl_intenciones.id_cliente 
			where tbl_pagos.tipo=2 and tbl_pagos.fecha <'$end'
			and tbl_intenciones.folio is not null
			and tbl_intenciones.estatus=1
			and pagos.total>0"));

			return response()->json(['atrasos'=>$atrasos,'aldia'=>$aldia,'cancelaciones'=>$cancelaciones,'convenios'=>$convenios,'esp_aldia'=>$esp_aldia,'esp_atrasos'=>$esp_atrasos]);
		}
	}


}