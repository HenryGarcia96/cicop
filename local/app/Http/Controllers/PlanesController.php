<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use View;


class PlanesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$lab_id=Auth::user()->__get('tbl_tienda_id');

		$results=DB::table('tbl_planes')->select('tbl_planes.id'
		,'tbl_planes.descripcion'
		,'tbl_planes.medidas'
		,'tbl_planes.pago_mensual'
		,'tbl_planes.pago_quincenal'
		,'tbl_planes.no_pagos_esp'
        ,'tbl_planes.pago_esp'
		,'tbl_planes.pago_diferido_m'
		,'tbl_planes.pago_diferido_q'
		,'tbl_planes.no_mensualidades'
        ,'tbl_planes.no_quincenas'
        ,'tbl_planes.monto_total'
        ,'tbl_planes.enganche'
		)
        ->where('tbl_planes.estatus',1)
				->orderBy('tbl_planes.id','DESC')
				->take(100)
        ->get();

        return View::make('lsplanes')->with('planes',$results);
	}
 public function nuevo()
 {
			 return View::make('clientes');
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
		$cliente_id=$input['id_plan'];
		try{
		if($cliente_id>0){
			$affected=DB::table('tbl_planes')
		->where('tbl_planes.id',$cliente_id)
		->update(
			    [
			    'descripcion'=>$input['descripcion'],
			    'medidas'=>$input['medidas'],
			    'pago_mensual'=>$input['pago_mensual'],
			    'pago_quincenal'=>$input['pago_quincenal'],
			    'no_pagos_esp'=>$input['no_pagos_esp'],
                'pago_esp'=>$input['pago_esp'],
                'pago_diferido_m'=>$input['pago_diferido_m'],
                'pago_diferido_q'=>$input['pago_diferido_q'],
                'no_mensualidades'=>$input['no_mensualidades'],
                'no_quincenas'=>$input['no_quincenas'],
                'monto_total'=>$input['monto_total'],
                'enganche'=>$input['enganche']
				
			    ]
			);
		}else{
        	$cliente_id=DB::table('tbl_planes')->insertGetId(
			    [
                'descripcion'=>$input['descripcion'],
			    'medidas'=>$input['medidas'],
			    'pago_mensual'=>$input['pago_mensual'],
			    'pago_quincenal'=>$input['pago_quincenal'],
			    'no_pagos_esp'=>$input['no_pagos_esp'],
                'pago_esp'=>$input['pago_esp'],
                'pago_diferido_m'=>$input['pago_diferido_m'],
                'pago_diferido_q'=>$input['pago_diferido_q'],
                'no_mensualidades'=>$input['no_mensualidades'],
                'no_quincenas'=>$input['no_quincenas'],
                'monto_total'=>$input['monto_total'],
                'enganche'=>$input['enganche']
			    ]
			);
		}
	}catch(Excecption $ex)
	{
		$error=1;
	}
        
		return response()->json(['mensaje'=>'Cliente guardado','error'=>$error,'cliente_id'=>$cliente_id]);
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
			$cliente=DB::table('tbl_planes')->select('tbl_planes.id'
			,'tbl_planes.descripcion'
		,'tbl_planes.medidas'
		,'tbl_planes.pago_mensual'
		,'tbl_planes.pago_quincenal'
		,'tbl_planes.no_pagos_esp'
        ,'tbl_planes.pago_esp'
		,'tbl_planes.pago_diferido_m'
		,'tbl_planes.pago_diferido_q'
		,'tbl_planes.no_mensualidades'
        ,'tbl_planes.no_quincenas'
        ,'tbl_planes.monto_total'
        ,'tbl_planes.enganche')
        ->where('tbl_planes.estatus',1)
        ->where('tbl_planes.id',$id)
        ->get();


        //return View::make('clientes')->with('cliente',$cliente[0]);
			return response()->json($cliente);
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
			$affected=DB::table('tbl_planes')
            ->where('id', $id)
            ->update(array('estatus'=>'0'));
			return 'El cliente ha sido eliminado '.$id;
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

			$results=DB::table('tbl_planes')->select('tbl_planes.id'
            ,'tbl_planes.descripcion'
            ,'tbl_planes.medidas'
            ,'tbl_planes.pago_mensual'
            ,'tbl_planes.pago_quincenal'
            ,'tbl_planes.no_pagos_esp'
            ,'tbl_planes.pago_esp'
            ,'tbl_planes.pago_diferido_m'
            ,'tbl_planes.pago_diferido_q'
            ,'tbl_planes.no_mensualidades'
            ,'tbl_planes.no_quincenas'
            ,'tbl_planes.monto_total'
            ,'tbl_planes.enganche')
        ->where('tbl_planes.estatus',1)
		->where('tbl_planes.descripcion','like','%'.$input['buscar'].'%')
		->orderBy('tbl_planes.id','DESC')
		->take(1000)
	    ->get();

		return response()->json($results) ;
		}
	}
	public function getComboPlanes(Request $request)
	{
		if($request->ajax())
		{
			$lab_id=Auth::user()->__get('tbl_tienda_id');
			$input = Input::all();

			$results=DB::table('tbl_planes')->select('tbl_planes.id'
			,DB::raw('CONCAT(tbl_planes.descripcion,\' \',tbl_planes.medidas) nombre')
            ,'tbl_planes.descripcion'
            ,'tbl_planes.medidas'
            ,'tbl_planes.pago_mensual'
            ,'tbl_planes.pago_quincenal'
            ,'tbl_planes.no_pagos_esp'
            ,'tbl_planes.pago_esp'
            ,'tbl_planes.pago_diferido_m'
            ,'tbl_planes.pago_diferido_q'
            ,'tbl_planes.no_mensualidades'
            ,'tbl_planes.no_quincenas'
            ,'tbl_planes.monto_total'
            ,'tbl_planes.enganche')
        ->where('tbl_planes.estatus',1)
		->orderBy('tbl_planes.id','DESC')
	    ->get();

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
