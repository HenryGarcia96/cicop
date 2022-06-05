<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sucursales extends Model {
	protected $table = 'tbl_sucursales';
	//

	public function getSucursales($lab_id)
	{
		$results=DB::table('tbl_sucursales')->select('tbl_sucursales.id','tbl_sucursales.nombre as sucursal','tbl_sucursales.telefono','tbl_sucursales.tbl_usuario_id','tbl_usuario.nombre','tbl_usuario.apellidos')
		->leftjoin('tbl_usuario', 'tbl_usuario.id', '=', 'tbl_sucursales.tbl_usuario_id')
        ->where('tbl_sucursales.tbl_tienda_id',$lab_id)
        ->where('tbl_sucursales.estatus',1)
        ->get();

	 return $results;
	}

}
