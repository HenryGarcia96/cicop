<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class RolesModulos extends Model {

	protected $table = 'tbl_roles_modulos';
	private $rolid=0;
	public function setRolId($rol)
	{
		$this->rolid=$rol;
	}
	public function getPermisos()
	{
		if($this->rolid==1)
		{
			$results=DB::table('tbl_modulos')->select('tbl_modulos.id as modulo_id', 'tbl_modulos.descripcion',DB::raw('1 as guardar,1 as editar,1 as eliminar,1 as consultar'))
	        ->get();
		}else
		{
			$results=DB::table('tbl_modulos')->select('tbl_roles_modulos.id as rol_modulo_id','tbl_modulos.id as modulo_id', 'tbl_modulos.descripcion','tbl_roles_modulos.guardar','tbl_roles_modulos.editar','tbl_roles_modulos.eliminar','tbl_roles_modulos.consultar')
	        ->leftJoin('tbl_roles_modulos', function($join)
	        {
	            $join->on('tbl_roles_modulos.tbl_modulo_id', '=', 'tbl_modulos.id')->where('tbl_roles_modulos.tbl_rol_id','=',$this->rolid);
	        }) 
	        ->get();
		}
		

        return $results;
	}

}
