<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Session;
class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $lab_id=Auth::user()->__get('tbl_tienda_id');

        $results = DB::table('tbl_tiendas')->where('id',$lab_id)->get();

        return View::make('config')->with('lab',$results[0]);

    }
    public function getTipoPagos(Request $request)
    {        
        if($request->ajax())
		{
			$tipo_pagos=DB::table('tbl_tipo_pago')->select('tbl_tipo_pago.id',
			'tbl_tipo_pago.descripcion')
            ->get();

			return response()->json($tipo_pagos);
		}
    }
    public function getRoles(Request $request)
    {        
        if($request->ajax())
		{
			$roles=DB::table('tbl_roles')->select('tbl_roles.id',
			'tbl_roles.descripcion')
            ->get();
			return response()->json($roles);
		}
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $lab_id=Auth::user()->__get('tbl_tienda_id');
        $input = Input::all();

        $req=0;
        $etiquetas=0;

        if(isset($input['requisicion']))
        {
          $req=1;
        }
        if(isset($input['imprimir_etiquetas']))
        {
          $etiquetas=1;
        }

        if($request->Hasfile('logo'))
        {
          $imageName = time() . '.' .
          $request->file('logo')->getClientOriginalExtension();

        $request->file('logo')->move(
            base_path() . '/logos/', $imageName
        );
    }else
    {
      $imageName=$input['logo_edit'];
    }

        //$affected = DB::update('update tbl_laboratorios set nombre ="'.$input['nombre'].'" where id = '.$lab_id);
        $affected=DB::table('tbl_tiendas')
            ->where('id', $lab_id)
            ->update(array('nombre' => $input['nombre'],'direccion' => $input['direccion'],'contacto' => $input['contacto'],'email' => $input['email'],'requisicion'=>$req,'imprimir_etiquetas'=>$etiquetas,'logo'=>$imageName,'porcentaje_monedero'=>$input['porcentaje_monedero'],'porcentaje_anticipo'=>$input['porcentaje_anticipo'],'porcentaje_descuento'=>$input['porcentaje_descuento']));
        Session::flash('alert-success', 'Registro actualizado');

        //$results = DB::table('tbl_tiendas')->where('id',$lab_id)->get();
        $tienda=DB::table('tbl_tiendas')->select('nombre','direccion','requisicion','imprimir_etiquetas','logo','contacto','porcentaje_monedero','porcentaje_anticipo','porcentaje_descuento')
        ->where('id',$lab_id)
        ->get();

        Session::put('tienda',$tienda[0]);
        #return View::make('config')->with('lab',$results[0]);
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
