<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Usuarios;
use DB;
use Excel;
use File;
use Illuminate\Http\Request;
use Input;
use Session;
use View;

class ReportController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $turno = DB::table('cat_turno')->get();
        return View::make('report')->with('turno', $turno);
    }

    public function buscar(Request $request)
    {
        if ($request->ajax()) {
            $input = Input::all();

            $periodo = 'Periodo de ' . $input['fecha_inicio'] . ' a ' . $input['fecha_fin'];
            $logo = '/local/logos/' . Session::get('config')['logo'];
            $membrete = '<div style="width:100%;display:inline-block">
			<span style="position:absolute;left:70%">Fecha impresi&oacute;n: ' . date('Y-m-d H:i:s') . '</span>
			<div style="width:80px;display:table-cell"><img src="' . asset($logo) . '" width="80px" /></div>
			<div style="width:90%;display:table-cell;vertical-align:middle; text-align:center"><span style="font-size:14pt;font-weight:bold">' . Session::get('config')['nombre_lab'] . '</span><br/><span style="font-size:8pt">' . Session::get('config')['direccion'] . ' <span>Tel.</span> ' . Session::get('config')['telefono1'] . ' Ext.' . Session::get('config')['telefono2'] . '</span><br/>
			<br>' . $periodo . '</div></div>';

            $style = '
			<style type="text/css" media="print">

        thead
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }

		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
		div{ display:inline-block;width:100%}
		div.folio,div.campo2{ width:8%;;font-size:8pt}
		div.nombre{ width:20%;font-size:8pt}

    </style>
			<style type="text/css" media="screen">

			@page {size: 216mm 279mm;
				margin-top:10mm;
				margin-bottom:0mm;
			}
			.pagina {
				width:100%;
				height:279mm;
				page-break-after:always;
			}
			.upagina {
				width:100%;
				height:279mm;
				page-break-after:avoid;
			}
			thead
        {
            display: block;
        }
        tfoot
        {
            display: block;
        }
		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
		div{ display:inline-block;width:100%}
		div.folio,div.campo2{ width:8%;;font-size:8pt}
		div.nombre{ width:20%;font-size:8pt}

			</style>';

            $js = '';

            //ini_set('display_errors',1);

            //$final = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Resultado</title>' . $js . $style . '</head><body>';
            $html = '';

            $html .= '<table style="width:100%;table-header-group"><thead><tr><th colspan="9">' . $membrete . '</th></tr><tbody>';

            $res = DB::table('solicitudes_pacientes')
                ->select(DB::raw('solicitudes_pacientes.id as folio'),
                    DB::raw('cat_pacientes.nombre||\' \'||cat_pacientes.a_paterno||\' \'||cat_pacientes.a_materno as paciente'),
                    DB::raw('to_char(fecha,\'YYYY-MM-DD\') as fecha'),
                    DB::raw('cat_estudios.codigo as codigo'),
                    DB::raw('cat_estudios.descripcion as estudio'),
                    DB::raw('round(solicitudes_estudios.costo,2) costo'),
                    DB::raw('cat_areas.descripcion as area'),
                    DB::raw('cat_medicos.nombre||\' \'||cat_medicos.a_paterno||\' \'||cat_medicos.a_materno as medico'),
                    DB::raw('cat_empresas.empresa')
                )
                ->leftJoin('solicitudes_estudios', 'solicitudes_estudios.folio', '=', 'solicitudes_pacientes.id')
                ->leftJoin('cat_estudios', 'cat_estudios.id', '=', 'solicitudes_estudios.id_estudio')
                ->leftJoin('cat_areas', 'cat_estudios.id_area', '=', 'cat_areas.id')
                ->leftJoin('cat_pacientes', 'cat_pacientes.id', '=', 'solicitudes_pacientes.id_paciente')
                ->leftJoin('cat_medicos', 'cat_medicos.id', '=', 'solicitudes_pacientes.id_medico')
                ->leftJoin('cat_empresas', 'cat_empresas.id', '=', 'solicitudes_pacientes.id_empresa')
                ->whereBetween('solicitudes_pacientes.fecha', [$input['fecha_inicio'] . ' 00:00:00', $input['fecha_fin'] . ' 23:59:59'])
                ->where('cat_estudios.serie', $input['serie'])
                ->get();

                $folioinicial=substr(date('Ymd',strtotime($input['fecha_inicio'].' -1 day')).'000',2);
                $foliofinal=substr(date('Ymd',strtotime($input['fecha_fin'].' +1 day')).'000',2);
            
            // $res_dlab=DB::connection('dlab')->table('solicitudes_estudios')
            // ->select('folio','cve_estudio','USUARIOID','cat_usuarios.nombre')
            // ->leftJoin('cat_usuarios','cat_usuarios.id','=','solicitudes_estudios.USUARIOID')
            // ->whereBetween('solicitudes_estudios.folio', [ $folioinicial, $foliofinal])
            // ->get();

            // $res_dmedical=DB::connection('dlab')->table('solicitudes_estudios')
            // ->select('folio','cve_estudio','USUARIOID','cat_usuarios.nombre')
            // ->leftJoin('cat_usuarios','cat_usuarios.id','=','solicitudes_estudios.USUARIOID')
            // ->whereBetween('solicitudes_estudios.folio', [ $folioinicial, $foliofinal])
            // ->get();

            

            // $estudios=array();
            // foreach($res_dlab as $fila)
            // {
            //     $estudios[$fila['folio'].'-'.$fila['cve_estudio']]=$fila['nombre'];
            // }



            $costototal = 0;
            
            foreach ($res as $row) {
                // $valido='';
                // if(isset($estudios[$row['folio'].'-'.$row['codigo']]))
                // {
                //     $valido=$estudios[$row['folio'].'-'.$row['codigo']];
                // }
                $html .= '<tr>';
                $html .='<td>'.$row['folio'].'</td>';
                $html .='<td>'.$row['paciente'].'</td><td>'.$row['fecha'].'</td>';
                $html .='<td>'.$row['empresa'].'</td><td>'.$row['estudio'].'</td>';
                $html .='<td>'.$row['costo'].'</td><td>'.$row['area'].'</td>';
                $html .='<td>'.$row['medico'].'</td>';
                $html .='</tr>';
                $costototal = $costototal + $row['costo'];
            }

            $html .= '<tr>';
            $html .='<td>Total</td>';
            $html .='<td></td><td></td>';
            $html .='<td></td><td></td>';
            $html .='<td>'.$costototal.'</td><td></td>';
            $html .='<td></td></tr>';


            $html .= '</tbody></table>';

            $final = $html . '</body></html>';

            $bytes_written = File::put(base_path() . "/resultados/reporte.html", $final);
            $path_reporte = "/resultados/reporte.html";

            $logo = base_path() . '/logos/' . Session::get('config')['logo'];
            Excel::create('reporte', function ($excel) use ($res, $logo, $periodo) {

                $excel->sheet('Reporte', function ($sheet) use ($res, $logo, $periodo) {

                    $sheet->loadView('reportexcel')->with('res', $res)->with('logo', $logo)->with('periodo', $periodo);
                    $sheet->getStyle('A1')->getAlignment()->setWrapText(true);

                });

            })->store('xls', base_path() . "/resultados/", false);

            //print_r($array_nuevo);

            return response()->json(["reporte" => $path_reporte,"style"=>$style])->header('Content-Type', 'application/json');

        }

    }

    public function estadisticas(Request $request)
    {
        if ($request->ajax()) {
            $input = Input::all();

            $periodo = 'Periodo de ' . $input['fecha_inicio'] . ' a ' . $input['fecha_fin'];
            $logo = '/local/logos/' . Session::get('config')['logo'];
            $membrete = '<div style="width:100%;display:inline-block">
			<span style="position:absolute;left:70%">Fecha impresi&oacute;n: ' . date('Y-m-d H:i:s') . '</span>
			<div style="width:80px;display:table-cell"><img src="' . asset($logo) . '" width="80px" /></div>
			<div style="width:90%;display:table-cell;vertical-align:middle; text-align:center"><span style="font-size:14pt;font-weight:bold">' . Session::get('config')['nombre_lab'] . '</span><br/><span style="font-size:8pt">' . Session::get('config')['direccion'] . ' <span>Tel.</span> ' . Session::get('config')['telefono1'] . ' Ext.' . Session::get('config')['telefono2'] . '</span><br/>
			<br>' . $periodo . '</div></div>';

            $style = '
			<style type="text/css" media="print">

        thead
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }

		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
		div{ display:inline-block;width:100%}
		div.folio,div.campo2{ width:8%;;font-size:8pt}
		div.nombre{ width:20%;font-size:8pt}

    </style>
			<style type="text/css" media="screen">

			@page {size: 216mm 279mm;
				margin-top:10mm;
				margin-bottom:0mm;
			}
			.pagina {
				width:100%;
				height:279mm;
				page-break-after:always;
			}
			.upagina {
				width:100%;
				height:279mm;
				page-break-after:avoid;
			}
			thead
        {
            display: block;
        }
        tfoot
        {
            display: block;
        }
		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
		div{ display:inline-block;width:100%}
		div.folio,div.campo2{ width:8%;;font-size:8pt}
		div.nombre{ width:20%;font-size:8pt}

			</style>';

            $js = '';

            //ini_set('display_errors',1);

            //$final = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Resultado</title>' . $js . $style . '</head><body>';
            $html = '';

            $html .= '<table style="width:100%;table-header-group"><thead><tr><th colspan="9">' . $membrete . '</th></tr><tbody>';

            $res = DB::table('solicitudes_pacientes')
                ->select(DB::raw('solicitudes_pacientes.id as folio'),
                    'no_seguropopular',
                    DB::raw('cat_pacientes.nombre||\' \'||cat_pacientes.a_paterno||\' \'||cat_pacientes.a_materno as paciente'),
                    DB::raw('to_char(fecha,\'YYYY-MM-DD\') as fecha'),
                    DB::raw('cat_centrosalud.descripcion as centrosalud'),
                    DB::raw('cat_estudios.descripcion as estudio'),
                    DB::raw('round(solicitudes_estudios.costo,2) costo'),
                    DB::raw('cat_areas.descripcion as area'),
                    DB::raw('to_char(cat_pacientes.vigencia_inicio,\'YYYY-MM-DD\') as vigencia_ini'),
                    DB::raw('to_char(cat_pacientes.vigencia_fin,\'YYYY-MM-DD\') as vigencia_fin'),
                    DB::raw('cat_medicos.nombre||\' \'||cat_medicos.a_paterno||\' \'||cat_medicos.a_materno as medico')
                )
                ->leftJoin('solicitudes_estudios', 'solicitudes_estudios.folio', '=', 'solicitudes_pacientes.id')
                ->leftJoin('cat_estudios', 'cat_estudios.id', '=', 'solicitudes_estudios.id_estudio')
                ->leftJoin('cat_areas', 'cat_estudios.id_area', '=', 'cat_areas.id')
                ->leftJoin('cat_pacientes', 'cat_pacientes.id', '=', 'solicitudes_pacientes.id_paciente')
                ->leftJoin('cat_medicos', 'cat_medicos.id', '=', 'solicitudes_pacientes.id_medico')
                ->leftJoin('cat_centrosalud', 'cat_centrosalud.id', '=', 'solicitudes_pacientes.id_centrosalud')
                ->whereBetween('solicitudes_pacientes.fecha', [$input['fecha_inicio'] . ' 00:00:00', $input['fecha_fin'] . ' 23:59:59'])
                ->where('cat_estudios.serie', $input['serie'])
                ->get();

            $costototal = 0;
            foreach ($res as $row) {
                $html .= '<tr>';
                $html .='<td>'.$row['folio'].'</td><td>'.$row['no_seguropopular'].'</td>';
                $html .='<td>'.$row['paciente'].'</td><td>'.$row['fecha'].'</td>';
                $html .='<td>'.$row['centrosalud'].'</td><td>'.$row['estudio'].'</td>';
                $html .='<td>'.$row['costo'].'</td><td>'.$row['area'].'</td>';
                $html .='<td>'.$row['medico'].'</td></tr>';
                $costototal = $costototal + $row['costo'];
            }

            $html .= '<tr>';
            $html .='<td>Total</td><td></td>';
            $html .='<td></td><td></td>';
            $html .='<td></td><td></td>';
            $html .='<td>'.$costototal.'</td><td></td>';
            $html .='<td></td></tr>';


            $html .= '</tbody></table>';

            $final = $html . '</body></html>';

            $bytes_written = File::put(base_path() . "/resultados/reporte.html", $final);
            $path_reporte = "/resultados/reporte.html";

            $logo = base_path() . '/logos/' . Session::get('config')['logo'];
            Excel::create('reporte', function ($excel) use ($res, $logo, $periodo) {

                $excel->sheet('Reporte', function ($sheet) use ($res, $logo, $periodo) {

                    $sheet->loadView('reportexcel')->with('res', $res)->with('logo', $logo)->with('periodo', $periodo);
                    $sheet->getStyle('A1')->getAlignment()->setWrapText(true);

                });

            })->store('xls', base_path() . "/resultados/", false);

            //print_r($array_nuevo);

            return response()->json(["reporte" => $path_reporte,"style"=>$style])->header('Content-Type', 'application/json');

        }

    }

    public function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                array_push($new_array, $array[$k]);
            }
        }

        return $new_array;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function folios()
    {

        $areas = DB::select('CALL paAREAS(?,?,?,?)', array(0, '', '', 'listar'));
        $equipos = DB::table('cat_equipos')->get();
        $jurisdiccion = DB::select('CALL paJURISDICCION(?,?,?,?)', array(0, '', '', 'listar'));
        $centro_salud = DB::select('CALL paCENTROSALUD(?,?,?,?,?)', array(0, 0, '', '', 'listar'));
        $empresas = DB::select('CALL paEMPRESAS(?,?,?,?,?,?,?,?,?,?,?,?,?)', array('', '', '', '', '', '', 0, '', '', 0, '', '', 'listar'));
        $tipo_servicio = DB::table('dev_tipo_servicio')->orderBy('descripcion')->get();

        return View::make('reportFolio')->with('jurisdiccion', $jurisdiccion)->with('centro_salud', $centro_salud)
            ->with('empresas', $empresas)->with('areas', $areas)->with('equipos', $equipos)->with('tipo_servicio', $tipo_servicio);
    }
    public function buscarAcciones(Request $request)
    {
        if ($request->ajax()) {
            $input = Input::all();

            $folio = empty($input['folio']) ? 0 : $input['folio'];
            $registro = empty($input['no_solicitud']) ? 0 : $input['no_solicitud'];
            $fechai = $input['fecha_inicio'] . ' 00:00:00';
            $fechaf = $input['fecha_fin'] . ' 23:59:59';

            $acciones = DB::select('CALL paACCIONES(?,?,?,?)', array($folio, $registro, $fechai, $fechaf));

            $nombrea = '';
            $html = '';
            foreach ($acciones as $fila) {
                $folio = $fila['folio'];
                $paciente = $fila['A_PATERNO'] . ' ' . $fila['A_MATERNO'] . ' ' . $fila['NOMBRE'];
                $usuario = $fila['usuario'];
                $registro = 'N. Registro:' . $fila['NO_SEGUROPOPULAR'];
                $accion = $fila['accion'];
                $tipo = ($fila['tipo'] == 'E') ? 'Estudio' : 'Todo';
                $estudio = $fila['comentario'];
                $fecha = $fila['fecha'];

                if (!empty($paciente)) {
                    if (empty($nombrea)) {
                        $html .= '<tr><td><div><div class="nombrep">' . $paciente . ' ' . $registro . '</div></div>';
                    }
                    if ($paciente != $nombrea) {
                        $html .= '</td></tr><tr><td><div><div class="nombrep">' . $paciente . ' ' . $registro . '</div></div>';
                    }

                    $html .= '<div><div class="nombre">' . $usuario . '</div>';
                    $html .= '<div class="columna">' . $accion . '</div>';
                    $html .= '<div class="columna">' . $tipo . '</div>';
                    $html .= '<div class="columna">' . $estudio . '</div>';
                    $html .= '<div class="columna">' . $fecha . '</div></div>';

                    $nombrea = $paciente;
                }

            }
            $html .= '</td></tr>';

            $periodo = '';
            $logo = '/local/logos/' . Session::get('config')['logo'];
            $membrete = '<div style="width:100%;display:inline-block">
			<span style="position:absolute;left:70%">Fecha impresi&oacute;n: ' . date('Y-m-d H:i:s') . '</span>
			<div style="width:80px;display:table-cell"><img src="' . asset($logo) . '" width="80px" /></div>
			<div style="width:90%;display:table-cell;vertical-align:middle; text-align:center"><span style="font-size:14pt;font-weight:bold">' . Session::get('config')['nombre_lab'] . '</span><br/><span style="font-size:8pt">' . Session::get('config')['direccion'] . ' <span>Tel.</span> ' . Session::get('config')['telefono1'] . ' Ext.' . Session::get('config')['telefono2'] . '</span><br/>
			<b>Laboratorio de Análisis Clínicos y Banco de Sangre</b><br/>Lic. Sanitaria 04 AM 0913012 - 20 14 400365<br>' . $periodo . '</div></div>';

            $style = '
			<style type="text/css" media="print">

        thead
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }

		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
			div{ display:inline-block;width:100%;font-size:9pt; font-family:Arial}
			div.folio{ width:10%;font-weight:bold}
			div.nombre{ width:30%;text-align:left;display:inline-block}
			div.nombrep{ width:100%;text-align:left;display:inline-block;font-weight:bold}
			div.columna{ width:17%;display:inline-block;text-align:center}
			div.columna2{ width:20%;display:inline-block;text-align:center}
    </style>
			<style type="text/css" media="screen">

			@page {size: 216mm 279mm;
				margin-top:10mm;
				margin-bottom:0mm;
			}
			.pagina {
				width:100%;
				height:279mm;
				page-break-after:always;
			}
			.upagina {
				width:100%;
				height:279mm;
				page-break-after:avoid;
			}
			thead
        {
            display: block;
        }
        tfoot
        {
            display: block;
        }
		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
			div{ display:inline-block;width:100%;font-size:9pt; font-family:Arial}
			div.folio{ width:10%;font-weight:bold}
			div.nombre{ width:30%;text-align:left;display:inline-block}
			div.nombrep{ width:100%;text-align:left;display:inline-block;font-weight:bold}
			div.columna{ width:17%;display:inline-block;text-align:center}
			div.columna2{ width:20%;display:inline-block;text-align:center}
			</style>';

            $js = '<script type="text/javascript">window.onload = function () {
			window.print();
			setTimeout(function(){window.close();}, 1);
			}</script>';

            $final = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Resultado</title>' . $js . $style . '</head><body>';

            $final .= '<table style="table-header-group"><thead><tr><th>' . $membrete . '</th></tr>';
            $final .= '<tr><th><div><div class="nombre">Paciente/usuario</div>';
            $final .= '<div class="columna">Accion</div>';
            $final .= '<div class="columna">Tipo</div>';
            $final .= '<div class="columna">Estudio</div>';
            $final .= '<div class="columna">Fecha</div></div>';
            $final .= '</th></tr></thead><tbody>';

            $final .= $html;
            $html .= '</tbody></table></body>';

            $bytes_written = File::put(base_path() . "/resultados/acciones.html", $final);
            $path_reporte = "/resultados/acciones.html";

            return response()->json(["reporte" => $path_reporte])->header('Content-Type', 'application/json');

        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function ingresosegresos()
    {

        $usuarios = new Usuarios();
        $usuarios->setOpcion('listar');
        $users = $usuarios->accion();
        return View::make('ingresosegresos')->with('usuarios', $users);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function buscaringresosegresos(Request $request)
    {
        if ($request->ajax()) {
            $input = Input::all();

            $sucursal = ($input['sucursal1'] == "0") ? '' : $input['sucursal1'];
            $usuario = $input['usuario'];
            $fechai = $input['fecha_inicio'] . ' 00:00:00';
            $fechaf = $input['fecha_fin'] . ' 23:59:59';

            $params = array(0, $usuario, $fechai, $fechaf, 0, 0, $sucursal, 'ingresosegresos');

            $acciones = DB::select('CALL paCAJA(?,?,?,?,?,?,?,?)', $params);

            $nombrea = '';
            $html = '';
            $empresaa = '';
            foreach ($acciones as $fila) {
                $empresa = $fila['NOMBRE_EMPRESA'];
                $folio = $fila['FOLIO'];
                $fecha = $fila['FECHA'];
                $paciente = $fila['NOMBRE_PACIENTE'];
                $monto = $fila['MONTO_TOTAL'];
                $anticipo = $fila['ANTICIPO'];
                $saldo = $fila['SALDO'];
                $estado = $fila['ESTADO'];

                if ($fila['CVE_EMPRESA'] != $empresaa) {
                    $html .= '<tr><td><div>' . $empresa . '</div></td></tr>';
                }

                $html .= '<tr><td><div><div class="columna" style="text-align:left">' . $folio . '</div>';
                $html .= '<div class="nombre">' . $paciente . '</div>';
                $html .= '<div class="columna">' . $fecha . '</div>';
                $html .= '<div class="columna">' . $monto . '</div>';
                $html .= '<div class="columna">' . $anticipo . '</div>';
                $html .= '<div class="columna">' . $saldo . '</div>';
                $html .= '<div class="columna">' . $estado . '</div></div></td></tr>';

                $empresaa = $fila['CVE_EMPRESA'];
            }

            $periodo = '';
            $logo = '/local/logos/' . Session::get('config')['logo'];
            $membrete = '<div style="width:100%;display:inline-block">
			<span style="position:absolute;left:70%">Fecha impresi&oacute;n: ' . date('Y-m-d H:i:s') . '</span>
			<div style="width:80px;display:table-cell"><img src="' . asset($logo) . '" width="80px" /></div>
			<div style="width:90%;display:table-cell;vertical-align:middle; text-align:center"><span style="font-size:14pt;font-weight:bold">' . Session::get('config')['nombre_lab'] . '</span><br/><span style="font-size:8pt">' . Session::get('config')['direccion'] . ' <span>Tel.</span> ' . Session::get('config')['telefono1'] . ' Ext.' . Session::get('config')['telefono2'] . '</span><br/>
			<b>Laboratorio de Análisis Clínicos y Banco de Sangre</b><br/>Lic. Sanitaria 04 AM 0913012 - 20 14 400365<br>' . $periodo . '</div></div>';

            $style = '
			<style type="text/css" media="print">

        thead
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }

		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial;text-align:left}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
			div{ display:inline-block;width:100%;font-size:9pt; font-family:Arial}
			div.folio{ width:10%;font-weight:bold}
			div.nombre{ width:30%;text-align:left;display:inline-block}
			div.nombrep{ width:100%;text-align:left;display:inline-block;font-weight:bold}
			div.columna{ width:10%;display:inline-block;text-align:center}
			div.columna2{ width:20%;display:inline-block;text-align:center}
    </style>
			<style type="text/css" media="screen">

			@page {size: 216mm 279mm;
				margin-top:10mm;
				margin-bottom:0mm;
			}
			.pagina {
				width:100%;
				height:279mm;
				page-break-after:always;
			}
			.upagina {
				width:100%;
				height:279mm;
				page-break-after:avoid;
			}

		table {width:100%;PAGE-BREAK-AFTER: always;font-size:9pt; font-family:Arial}
		table th td {width:100%,font-size:9pt; font-family:Arial;text-align:left}
		table tbody th td div {width:100%; font-size:9pt; font-family:Arial}
		body{font-size:9pt; font-family:Arial}
			div{ display:inline-block;width:100%;font-size:9pt; font-family:Arial}
			div.folio{ width:10%;font-weight:bold}
			div.nombre{ width:30%;text-align:left;display:inline-block}
			div.nombrep{ width:100%;text-align:left;display:inline-block;font-weight:bold}
			div.columna{ width:10%;display:inline-block;text-align:center}
			div.columna2{ width:20%;display:inline-block;text-align:center}
			</style>';

            $js = '<script type="text/javascript">window.onload = function () {
			window.print();
			setTimeout(function(){window.close();}, 1);
			}</script>';

            $final = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Resultado</title>' . $js . $style . '</head><body>';

            $final .= '<table style="table-header-group"><thead><tr><th>' . $membrete . '</th></tr>';
            $final .= '<tr><th style="text-align:left"><div><div class="columna" style="text-align:left">No muestra</div>';
            $final .= '<div class="nombre">Paciente</div>';
            $final .= '<div class="columna">Fecha</div>';
            $final .= '<div class="columna">Monto</div>';
            $final .= '<div class="columna">Anticipo</div>';
            $final .= '<div class="columna">Saldo</div>';
            $final .= '<div class="columna">Estado</div></div>';
            $final .= '</th></tr></thead><tbody>';

            $final .= $html;
            $final .= '</tbody></table></body></html>';

            $bytes_written = File::put(base_path() . "/resultados/ingresosegresos.html", $final);
            $path_reporte = "/resultados/ingresosegresos.html";

            return response()->json(["reporte" => $path_reporte])->header('Content-Type', 'application/json');

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
        //
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
    public function destroy($id)
    {
        //
    }

}