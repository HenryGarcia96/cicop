<?php namespace App\Http\Controllers;
use View;
use DB;
use Auth;
use Session;
use App\RolesModulos;
class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user=Auth::user();

		$valor= $user->__get('nombre');
		$tbl_laboratorio_id= $user->__get('tbl_tienda_id');
		$tbl_rol_id= $user->__get('tbl_rol_id');
		
		$user_id=$user->__get('id');
		
		$rol=DB::table('tbl_roles')->select('descripcion')->where('id',$tbl_rol_id)->get();
		$rol_d='';
		if(isset($rol[0]['descripcion']))
		{
			$rol_d=$rol[0]['descripcion'];
		}
		$data = [ 'nombre'=>$valor,'rol'=>$rol_d];
		Session::put('rol_id',$tbl_rol_id);
		Session::put('data',$data);
		

		#Obtenemos los permisos por modulos
 		$rolesmodulos=new RolesModulos();
        $rolesmodulos->setRolId($tbl_rol_id);
        $permisos=$rolesmodulos->getPermisos();
        $array=array();
        foreach ($permisos as $permiso) {
        	$array[$permiso['descripcion'].'-G']=$permiso['guardar'];
					$array[$permiso['descripcion'].'-ED']=$permiso['editar'];
					$array[$permiso['descripcion'].'-EL']=$permiso['eliminar'];
					$array[$permiso['descripcion'].'-C']=$permiso['consultar'];
        }

        Session::put('menu',$array);


		#Obtenemos las sucursales por usuario
	

	
	#Obtenemos nombre, direccion y si usa requisicion la tienda
	// $tienda=DB::table('tbl_tiendas')->select('nombre','direccion','requisicion','imprimir_etiquetas','logo','contacto','porcentaje_monedero','porcentaje_anticipo','porcentaje_descuento')
	// ->where('id',$tbl_laboratorio_id)
	// ->get();

	
	// Session::put('tienda',$tienda[0]);

	#Verificamos si existe la caja, en caso contrario, lo enviamos a aperturar caja
	if(isset($caja[0]))
	{
		Session::put('corte_id',$caja[0]['id']);
		Session::put('sucursal_id',$caja[0]['tbl_sucursal_id']);
	}else {
		Session::forget('corte_id');
		Session::put('sucursal_id','-1');
		//return redirect('caja/apertura');
	}


	$results=DB::table('tbl_pagos')
	->select('tbl_pagos.id_intencion','folio','nombre','tbl_clientes.celular','tbl_pagos.tipo',DB::raw('count(*) atrasos'),
	'tipo_pago')
	->leftJoin('tbl_intenciones','tbl_pagos.id_intencion','=','tbl_intenciones.id')
	->leftJoin('tbl_clientes','tbl_clientes.id','=','tbl_intenciones.id_cliente')
	->whereRaw("date(tbl_pagos.fecha)<'".date('Y-m-d')."' and date(tbl_pagos.fecha)>'2021-01-01' and (tbl_pagos.importe is null or tbl_pagos.importe<=0)")
	->where('tbl_pagos.tipo',1)
	->where('tbl_intenciones.estatus',1)
	->groupBy('id_intencion','tipo')
	->get();

	$especiales=DB::select(DB::raw("select tbl_pagos.id_intencion,tbl_intenciones.folio,tbl_clientes.nombre,tbl_clientes.celular, 2 as tipo,count(*) atrasos
	from tbl_pagos 
	left join (select id_pago,sum(tbl_pagos_esp.importe) total from tbl_pagos_esp 
	group by id_pago) pagos on tbl_pagos.id=pagos.id_pago
	left join tbl_intenciones on tbl_pagos.id_intencion = tbl_intenciones.id 
	left join tbl_clientes on tbl_clientes.id = tbl_intenciones.id_cliente 
	where tbl_pagos.tipo=2 and date(tbl_pagos.fecha)<='".date('Y-m-d')."'
	and tbl_intenciones.folio is not null
	and tbl_intenciones.estatus=1
	and (pagos.total is null or pagos.total<=0)
	group by tbl_pagos.id_intencion
	order by tbl_pagos.id_intencion"));

	//Obtenemos total a cobrar en el mes
	$fecha=date('Y-m-').'01';
	$start=$fecha.' 00:00:00';
	//$fecha_aux=date('Y-m-d',strtotime($fecha.' +1 Month'));
	//$end=date('Y-m-d',strtotime($fecha_aux.' -1 day')).' 23:59:59';
	$end=date('Y-m-d').' 23:59:59';
	
	$total_cobro=DB::select(DB::raw("select tp.id_intencion,ti.tipo_pago,tp.tipo,tp.fecha,(select sum(tid.pago_mensual) from tbl_intenciones_detalle tid where tid.id_intencion=tp.id_intencion) pago_mensual,
	(select sum(tid.pago_quincenal) from tbl_intenciones_detalle tid where tid.id_intencion=tp.id_intencion) pago_quincenal,
	(select sum(tid.pago_esp) from tbl_intenciones_detalle tid where tid.id_intencion=tp.id_intencion) pago_esp
	from tbl_pagos tp
	left join tbl_intenciones ti on tp.id_intencion=ti.id
	where tp.fecha between '$start' and '$end'
	and tp.tipo in (1,2)"));

	$total_a_cobrar=0;
	$tota_cobrado=0;
	$morosos=0;
	foreach($total_cobro as $fila)
	{
		if($fila['tipo_pago']==1 && $fila['tipo']==1)
		{
			$total_a_cobrar +=$fila['pago_quincenal'];
		}elseif($fila['tipo_pago']==2 && $fila['tipo']==1)
		{
			$total_a_cobrar +=$fila['pago_mensual'];
		}elseif($fila['tipo']==2)
		{
			$total_a_cobrar +=$fila['pago_esp'];
		}
	}

	//PAgos en lo que va del mes
	$pagos=DB::select(DB::raw("select tp.id_intencion,tbl_intenciones.folio,tp.fecha,tp.importe,(select sum(importe) from tbl_pagos_esp where tbl_pagos_esp.id_pago=tp.id) importe_esp
	from tbl_pagos tp
    left join tbl_intenciones on tp.id_intencion=tbl_intenciones.id
	where tp.fecha between '$start' and '$end'
	and tp.tipo in (1,2) and folio is not null"));
	foreach($pagos as $fila)
	{
		$tota_cobrado +=$fila['importe']+$fila['importe_esp'];
		if($fila['importe']<=0)
		{
			$morosos++;
		}
		
	}


	return View::make('home')
	->with('data',$data)
	->with('atrasos',$results)
	->with('especiales',$especiales)
	->with('totalacobrar',$total_a_cobrar)
	->with('totalcobrado',$tota_cobrado)
	->with('morosos',$morosos);



	}

}
