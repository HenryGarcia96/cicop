<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use View;


class ClientesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$lab_id=Auth::user()->__get('tbl_tienda_id');

		$results=DB::table('tbl_clientes')->select('tbl_clientes.id'
		,'tbl_clientes.nombre as cliente'
		,'tbl_clientes.celular'
		,'tbl_clientes.email'
		,'tbl_clientes.rfc'
		,'tbl_clientes.calle'
		,'tbl_clientes.direccion2'
		,'tbl_clientes.estado'
		,'tbl_clientes.fecha_nac'
		,'credito'
		,'tipo')
		// ->where('tbl_clientes.tbl_tienda_id',$lab_id)
        ->where('tbl_clientes.estatus',1)
		->orderBy('tbl_clientes.id','DESC')
		->take(1000)
        ->get();

        return View::make('lsclientes')->with('clientes',$results);
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
		$cliente_id=$input['id_paciente'];
		try{
		if($cliente_id>0){
			$affected=DB::table('tbl_clientes')
		->where('tbl_clientes.id',$cliente_id)
		->update(
			    [
			    'nombre'=>$input['nombre'],
			    'calle'=>$input['direccion'],
			    'direccion2'=>$input['referencia'],
			    'celular'=>$input['telefono'],
			    'email'=>$input['email'],
				'rfc'=>(isset($input['identificacion'])?$input['identificacion']:''),
				'tipo'=>$input['tipo_cliente']
			    ]
			);
		}else{
        	$cliente_id=DB::table('tbl_clientes')->insertGetId(
			    ['nombre'=>$input['nombre'],
			    'calle'=>$input['direccion'],
			    'direccion2'=>$input['referencia'],
			    'celular'=>$input['telefono'],
			    'email'=>$input['email'],
				'rfc'=>(isset($input['identificacion'])?$input['identificacion']:''),
				'tipo'=>$input['tipo_cliente']
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
			,'credito'
			,'tipo')
        ->where('tbl_clientes.estatus',1)
        ->where('tbl_clientes.id',$id)
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
			$affected=DB::table('tbl_clientes')
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

			$results=DB::table('tbl_clientes')->select('tbl_clientes.id'
		,'tbl_clientes.nombre as cliente'
		,'tbl_clientes.celular'
		,'tbl_clientes.email'		
		,'tbl_clientes.calle'
		,'tbl_clientes.direccion2')
		// 	->where('tbl_clientes.tbl_tienda_id',$lab_id)
        ->where('tbl_clientes.estatus',1)
		->where(function($query) use ($input)
            {
                $query->orWhere('tbl_clientes.nombre','like','%'.$input['buscar'].'%')
								->orWhere('tbl_clientes.celular','like','%'.$input['buscar'].'%')
								->orWhere('tbl_clientes.email','like','%'.$input['buscar'].'%');
            })

		->orderBy('tbl_clientes.id','DESC')
		->take(1000)
	    ->get();

		return response()->json($results) ;
		}
	}

	public function getComboClientes(Request $request)
	{
		if($request->ajax())
		{
			$input = Input::all();

			$results=DB::table('tbl_clientes')->select('tbl_clientes.id'
		,'tbl_clientes.nombre'
		,'tbl_clientes.tipo')
        ->where('tbl_clientes.estatus',1)
		->orderBy('tbl_clientes.id','DESC')
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
