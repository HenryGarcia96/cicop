<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use View;
use Hash;

class UsuariosController extends Controller {

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$lab_id=Auth::user()->__get('tbl_tienda_id');
		$results=DB::table('tbl_usuario')->select('tbl_usuario.id','tbl_usuario.usuario', 'tbl_usuario.nombre','tbl_usuario.apellidos','tbl_usuario.email','tbl_usuario.telefono','tbl_usuario.tbl_rol_id','tbl_roles.descripcion')
		->leftJoin('tbl_roles', 'tbl_usuario.tbl_rol_id', '=', 'tbl_roles.id')
		->where('tbl_usuario.estatus',1)
		->get();

		return View::make('lsusuarios')->with('usuarios',$results);
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
		$mensaje='Usuario creado correctamente';
		$color='success';
		$input = Input::all();
		$usuario_id=$input['id_usuario'];

		
		try{
			
		if($usuario_id>0){
			$password=(empty($input['password'])?$input['old_password']:$input['password']);
			$affected=DB::table('tbl_usuario')
		->where('tbl_usuario.id',$usuario_id)
		->update(
			    [
					'usuario' => $input['usuario'], 
					'nombre' => $input['nombre'], 
					'email'=>$input['email'],
					'telefono'=>$input['telefono'],
					'tbl_rol_id'=>$input['tbl_rol_id'],
					'password'=>Hash::make($password)
			    ]
			);
			$mensaje='Usuario actualizado correctamente';
		}else{
			$find=DB::table('tbl_usuario')
			->where('tbl_usuario.usuario',$input['usuario'])
			->get();

			if(isset($find[0]))
			{
				$error=0;
				$mensaje='El usuario ya existe, cambie de usuario';
				$color='danger';
			}else{
				$usuario_id=DB::table('tbl_usuario')->insertGetId(
					['usuario' => $input['usuario'], 
					'nombre' => $input['nombre'], 
					'email'=>$input['email'],
					'telefono'=>$input['telefono'],
					'tbl_rol_id'=>$input['tbl_rol_id'],
					'password'=>Hash::make($input['password'])]
				);
			}

		}
	}catch(Excecption $ex)
	{
		$error=1;
		$mensaje=$ex->getMessage();
		$color= 'danger';
	}
        
		return response()->json(['mensaje'=>$mensaje,'error'=>$error,'color'=>$color,'usuario_id'=>$usuario_id]);
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
	
	$user=DB::table('tbl_usuario')
	->select('tbl_usuario.id', 
	'tbl_usuario.usuario',
	'tbl_usuario.nombre',
	'tbl_usuario.email',
	'tbl_usuario.telefono',
	'tbl_usuario.tbl_rol_id',
	'tbl_roles.descripcion',
	'tbl_usuario.password')
	->leftJoin('tbl_roles', 'tbl_usuario.tbl_rol_id', '=', 'tbl_roles.id')
	->where('tbl_usuario.estatus',1)
	->where('tbl_usuario.id',$id)
	->get();

	

	return response()->json($user);
	}
}

/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return Response
*/
public function edit($id)
{
	$lab_id=Auth::user()->__get('tbl_tienda_id');
	$input = Input::all();


	$find=DB::table('tbl_usuario')
	->where('tbl_usuario.usuario',$input['usuario'])
	->where('tbl_usuario.id','!=',$id)
	->where('tbl_usuario.tbl_tienda_id',$lab_id)
	->get();
	$roles = DB::table('tbl_roles')->where('tbl_tienda_id',$lab_id)->where('estatus','1')->get();

	$sucursales=DB::table('tbl_sucursales')->where('tbl_tienda_id',$lab_id)->where('estatus','1')->orderBy('tbl_sucursales.nombre','ASC')->get();


	if(isset($find[0]))
	{

		Session::flash('alert-warning', 'El correo ya existe, por favor utilice uno nuevo');
		$user=DB::table('tbl_usuario')
		->select('tbl_usuario.id', 
		'tbl_usuario.usuario',
		'tbl_usuario.nombre',
		'tbl_usuario.apellidos',
		'tbl_usuario.email',
		'tbl_usuario.telefono',
		'tbl_usuario.tbl_rol_id',
		'tbl_roles.descripcion','tbl_usuario.password')
		->leftJoin('tbl_roles', 'tbl_usuario.tbl_rol_id', '=', 'tbl_roles.id')
		->where('tbl_usuario.tbl_tienda_id',$lab_id)
		->where('tbl_usuario.estatus',1)
		->where('tbl_usuario.id',$id)
		->get();

		$results=DB::table('tbl_usuario')
		->select('tbl_usuario.id', 
		'tbl_usuario.nombre',
		'tbl_usuario.apellidos',
		'tbl_usuario.email',
		'tbl_usuario.telefono',
		'tbl_usuario.tbl_rol_id',
		'tbl_roles.descripcion')
		->leftJoin('tbl_roles', 'tbl_usuario.tbl_rol_id', '=', 'tbl_roles.id')
		->where('tbl_usuario.tbl_tienda_id',$lab_id)
		->where('tbl_usuario.estatus',1)
		->get();

		return View::make('usuarios')->with('usuarios',$results)->with('roles',$roles)->with('user',$user[0]);

	}else
	{

		if(empty($input['password']))
		{
			$affected=DB::table('tbl_usuario')
			->where('tbl_usuario.id',$id)
			->update(
			['nombre' => $input['nombre'], 
			'usuario'=>$input['usuario'],
			'apellidos'=>$input['apellidos'],
			'email'=>$input['email'],
			'telefono'=>$input['telefono'],
			'tbl_rol_id'=>$input['rol']]
		);
	}else {
		$affected=DB::table('tbl_usuario')
		->where('tbl_usuario.id',$id)
		->update(
		['usuario'=>$input['usuario'],'nombre' => $input['nombre'], 'apellidos'=>$input['apellidos'],'email'=>$input['email'],'telefono'=>$input['telefono'],'tbl_rol_id'=>$input['rol'],'password'=>Hash::make($input['password'])]
	);
}


DB::table('tbl_usuarios_sucursales')->where('tbl_tienda_id', $lab_id)->where('tbl_usuario_id', $id)->delete();

foreach($input['sucursal'] as $suc)
{
	DB::table('tbl_usuarios_sucursales')->insert(
	['tbl_usuario_id'=>$id,'tbl_sucursal_id'=>$suc,'tbl_tienda_id'=>$lab_id]
);
}


$results=DB::table('tbl_usuario')->select('tbl_usuario.id', 'tbl_usuario.nombre','tbl_usuario.apellidos','tbl_usuario.email','tbl_usuario.telefono','tbl_usuario.tbl_rol_id','tbl_roles.descripcion')
->leftJoin('tbl_roles', 'tbl_usuario.tbl_rol_id', '=', 'tbl_roles.id')
->where('tbl_usuario.tbl_tienda_id',$lab_id)
->where('tbl_usuario.estatus',1)
->get();

Session::flash('alert-success', 'Registro actualizado');

return View::make('usuarios')->with('usuarios',$results)->with('roles',$roles)->with('sucursales',$sucursales);
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
			$affected=DB::table('tbl_usuario')
            ->where('id', $id)
            ->update(array('estatus'=>'0'));
			return 'El usuario ha sido eliminado '.$id;
		}

	}

public function buscar(Request $request)
	{
		if($request->ajax())
		{
			$lab_id=Auth::user()->__get('tbl_tienda_id');
			$input = Input::all();

			$results=DB::table('tbl_usuario')->select('tbl_usuario.id','tbl_usuario.usuario', 'tbl_usuario.nombre','tbl_usuario.apellidos','tbl_usuario.email','tbl_usuario.telefono','tbl_usuario.tbl_rol_id','tbl_roles.descripcion')
			->leftJoin('tbl_roles', 'tbl_usuario.tbl_rol_id', '=', 'tbl_roles.id')
			->where('tbl_usuario.estatus',1)
			->where(function($query) use ($input)
            {
                $query->orWhere('tbl_usuario.nombre','like','%'.$input['buscar'].'%')
								->orWhere('tbl_usuario.telefono','like','%'.$input['buscar'].'%')
								->orWhere('tbl_usuario.email','like','%'.$input['buscar'].'%');
            })

		->orderBy('tbl_usuario.id','DESC')
		->take(1000)
	    ->get();

		return response()->json($results) ;
		}
	}

}
