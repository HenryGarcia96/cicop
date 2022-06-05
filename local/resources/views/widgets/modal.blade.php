<!-- Modal -->
<div id="{{$id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$title_modal}}</h4>
      </div>
      <div class="modal-body">

        @if(isset($content_modal['tabla']) && $modulo=='permisos')
        <table class="table table-hover">
          <thead>
            <tr>
              @foreach ($content_modal['columnas'] as $col)
              <th>{{ucfirst($col)}}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($content_modal['tabla'] as $tbl)
            <tr >
              @foreach ($content_modal['columnas'] as $col)
              @if($tbl[$col]=='1' && strpos('|guardar|editar|eliminar|consultar',$col))
              <td><input  type="checkbox" checked="checked" name="chkPermiso{{$tbl['rol_modulo_id'].$col}}" ></td>
              @elseif((empty($tbl[$col]) || $tbl[$col]=='0') && strpos('|guardar|editar|eliminar|consultar',$col))
              <td><input type="checkbox" name="chkPermiso{{$tbl['rol_modulo_id'].$col}}" ></td>
              @else
              <td>{{$tbl[$col]}}</td>
              @endif

              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
        @elseif (isset($content_modal['tabla']) && $modulo=='requisicion')

        <div class="form-group">
          <label>Clave</label>
          <input class="form-control" id='txtFiltro' name='txtFiltro'   />
        </div>
        <table id="tblProductos" class="table table-hover">
          <thead>
            <tr>

              @foreach ($content_modal['columnas'] as $col)
              <th>{{ucfirst($col)}}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($content_modal['tabla'] as $tbl)
            <tr >
              @foreach ($content_modal['columnas'] as $col)
              <td>{{$tbl[$col]}}</td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
        @elseif ($modulo=='nuevocliente')
        <div class="row">
          <div class="form-group col-xs-12">
            <label>Nombre Completo</label>
            <input class="form-control" id='nombre' name='nombre'   />
          </div>
          <div class="form-group col-xs-12">
            <label>Email</label>
            <input class="form-control" id='email' name='email'   />
          </div>
          <div class="form-group col-xs-6" >
            <label>Tel&eacute;fono</label>
            <input class="form-control" id='celular' name='celular'   />
          </div>
          <div class="form-group col-xs-6" style="display:none">
            <label>RFC</label>
            <input class="form-control" id='rfc' name='rfc'   />
          </div>
          @if(Session::get('rol_id')==1 || Session::get('rol_id')==2)
          <div class="form-group col-xs-12">
            <label class="check-inline"><input type="checkbox" id='tieneCredito' name='tieneCredito'>Tiene Credito</label>
          </div>
          @endif
        </div>
        @elseif ($modulo=='cobrar')

        <!--

 ######   #######  ########  ########     ###    ########
##    ## ##     ## ##     ## ##     ##   ## ##   ##     ##
##       ##     ## ##     ## ##     ##  ##   ##  ##     ##
##       ##     ## ########  ########  ##     ## ########
##       ##     ## ##     ## ##   ##   ######### ##   ##
##    ## ##     ## ##     ## ##    ##  ##     ## ##    ##
 ######   #######  ########  ##     ## ##     ## ##     ##

        -->

        <div class="row">
          <!--<div class="form-group col-xs-6">
            <label>Tipo de Pago</label>
            <select class="form-control" id="forma_pago" name="forma_pago">
              <option value="1" selected="selected">Efectivo</option>
              <option value="2" >Tarjeta Electronica</option>
              <option value="3" >Vales</option>
            </select>
          </div>-->
          <div class="form-group col-xs-6 divPago">
            <label>Fecha vencimiento</label>
            <input class="form-control" id='fecha_vence' name='fecha_vence' value={{date('Y-m-d')}}   />
          </div>
          <div class="col-md-12 col-xs-12 cedula hidden">
          <div class="row">
          <div class="form-group col-md-4 col-xs-12">
            <label>Cedula</label><input class="form-control" name="cedula" id="cedula" />
          </div>
          <div class="form-group col-md-8 col-xs-12">
            <label>Nombre</label><input class="form-control" name="nombre_med" id="nombre_med" />
          </div>
          </div></div>
          <div class="form-group col-xs-12 text-center">
            <label>Total</label>
            <input class="form-control text-center" style="font-size:14pt" readonly="readonly" id='totalL' name='totalL'   />
          </div>
          <div class="form-group col-xs-4 hidden" >
            <label id="lblPago">A pagar</label>
            <input class="form-control" id='pago' name='pago' />
          </div>
          <div class="form-group col-xs-4 anticipo" >
          <label>Anticipo</label>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
            <input class="form-control" id='anticipo1' name='anticipo1' />
            </div>
          </div>
          <div class="form-group col-xs-4 text-center" >
          <label>Efectivo</label><br>
          <div class="btn btn-success btn-lg"><i class="fa fa-money fa-2x"></i></div>
          <div class="input-group">
            <input class="form-control text-center " style="font-size:14pt" id='importe' name='importe' autofocus  />
            </div>
          </div>
          <div class="form-group col-xs-4 text-center" >
            <label>T. Electr&oacute;nica</label><br>
            <div class="btn btn-primary btn-lg"><i class="fa fa-credit-card fa-2x"></i></div>
            <div class="input-group">
            <input class="form-control text-center" style="font-size:14pt" id='telectronica' name='telectronica' value="0" />
            </div>
          </div>
          <div class="form-group col-xs-6 hidden" >
            <label>Vales</label><br>
            <div class="btn btn-primary btn-lg"><i class="fa fa-ticket"></i></div>
            <div class="input-group">
            <input class="form-control" id='vales' name='vales' value="0" />
            </div>
          </div>

          <div id="cobro_monedero" class="form-group col-xs-4 text-center" >
            <label id="lblMonederoC">Monedero</label><br>
            <div class="btn btn-warning btn-lg"><i class="fa fa-laptop fa-2x"></i></div>
            <div >
                <div class="form-group input-group col-xs-12"  >
                    <input class="form-control text-center"
                        style="font-size:14pt"
                        id='monedero1'
                        name='monedero1'
                        value="0" />
                    <span class="input-group-addon">
                        <i id ="deleteMonedero"
                            title="Borrar montos"
                            style="color:red"
                            onclick="borraMonederoTable()"
                            class="fa fa-times"></i>
                    </span>
                    <span class="input-group-addon">
                        <i id="viewMonedero"
                            data-toggle="popover"

                            data-placement="bottom"
                            data-html ="true"
                            data-content=""
                            style="color:blue"
                            class="fa fa-eye"
                            ></i>
                    </span>
                </div>
                <div class="form-group input-group col-xs-12"  id='divInputMonedero'>
                    <input type="text" id="FiltroinputMonedero" class="typeahead form-control" placeholder="Ticket">
                    <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                </div>
                <label id="lblMonederoMonto"></label>
                <div id="" class="form-group input-group col-xs-12"
                    style="display:none;position:absolute;z-index:99;background-color: ghostwhite;margin-left:-100px;height: 50px;overflow:auto">
                    <table  style="height:100%" id="tblMonedero">
                         <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
          </div>
          <div class="form-group col-xs-12 text-center">
            <label>Cambio</label>
            <input class="form-control text-center" style="font-size:14pt" readonly="readonly" id='cambio' name='cambio'   />
          </div>
        </div>
        @elseif ($modulo=='cancelar')
        <div class="row">
          <input type="hidden" id="id" name="id" />
          <div class="form-group col-xs-4">
            <label>Fecha</label>
            <input class="form-control" id='fecha_venta' name='fecha_venta' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4">
            <label>Fecha Limite</label>
            <input class="form-control" id='fecha_vence' name='fecha_vence' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4">
            <label>Estatus</label>
            <input class="form-control" id='estatusv' name='estatusv' readonly="readonly" />
          </div>
          <div class="form-group col-xs-12">
            <label>Cliente</label>
            <input class="form-control" id='cliente' name='cliente' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4">
            <label>Total</label>
            <input class="form-control" id='total' name='total' readonly="readonly"  />
          </div>
          <div class="form-group col-xs-4" >
            <label >Abonos</label>
            <input class="form-control" id='abono' name='abono' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4" >
            <label>Adeudo</label>
            <input class="form-control" id='adeudo' name='adeudo' readonly="readonly" />
          </div>
        </div>
        <div class="row">
          @section ('btable_panel_title','Productos seleccionados')
          @section ('btable_panel_body')
          <table id="tblProductosSeleccionados" class="table table-hover">
            <thead>
              <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Importe</th>
                <th>Descuento</th>
                <th>Retorna a</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
          @endsection
          @include('widgets.panel', array('header'=>true, 'as'=>'btable'))
          
          <div class="form-group col-xs-12" >
            <label>Observaciones por cancelaci&oacute;n</label>
            <textarea class="form-control" id='observaciones' name='observaciones'></textarea>
          </div>
        </div>
        @elseif ($modulo=='movimientos')
        <div class="row">
          <div class="form-group col-xs-4">
            <label>Fecha</label>
            <input class="form-control" id='fecha' name='fecha' readonly="readonly" />
          </div>
          <div class="form-group col-xs-12">
            <label>Usuario</label>
            <input class="form-control" id='usuario' name='usuario' readonly="readonly" />
          </div>
          <div class="form-group col-xs-12">
            <label>Total</label>
            <input class="form-control" id='total' name='total' readonly="readonly" />
          </div>
          <div class="form-group col-xs-12" >
            <label>Observaciones por cancelaci&oacute;n</label>
            <textarea class="form-control" id='observaciones' name='observaciones'></textarea>
          </div>
        </div>
        <div class="row">
          @section ('btable_panel_title','Productos seleccionados')
          @section ('btable_panel_body')
          <table id="tblProductosSeleccionados" class="table table-hover">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Origen</th>
                <th>Destino</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
          @endsection
          @include('widgets.panel', array('header'=>true, 'as'=>'btable'))
        </div>
        @elseif ($modulo=='abonos')
        <div class="row">
          <input type="hidden" id="id1" name="id1" />
          <div class="form-group col-xs-4">
            <label>Fecha</label>
            <input class="form-control" id='fecha_venta1' name='fecha_venta1' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4">
            <label>Fecha Limite</label>
            <input class="form-control" id='fecha_vence1' name='fecha_vence1' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4">
            <label>Estatus</label>
            <input class="form-control" id='estatusv1' name='estatusv1' readonly="readonly" />
          </div>
          <div class="form-group col-xs-12">
            <label>Cliente</label>
            <input class="form-control" id='cliente1' name='cliente1' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4">
            <label>Total</label>
            <input class="form-control" id='total1' name='total1' readonly="readonly"  />
          </div>
          <div class="form-group col-xs-4" >
            <label >Abonos</label>
            <input class="form-control" id='abono1' name='abono1' readonly="readonly" />
          </div>
          <div class="form-group col-xs-4" >
            <label>Adeudo</label>
            <input class="form-control" id='adeudo1' name='adeudo1' readonly="readonly" />
          </div>
        </div>
        <div class="row">
          @section ('table_panel_title','Historial de Abonos')
      		@section ('table_panel_body')
          <table id="tblAbonos" class="table table-hover">
            <thead>
              <tr>
                <th>Fecha Abono</th>
                <th>Importe</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
      		@endsection
      		@include('widgets.panel', array('header'=>true, 'as'=>'table'))
          <div class="row">
          <div class="form-group col-xs-4 col-md-4 text-center" >
          <label>Efectivo</label><br>
          <div class="btn btn-success btn-lg"><i class="fa fa-money fa-2x"></i></div>
          <div class="input-group">
            <input class="form-control text-center " style="font-size:14pt" id='importe' name='importe' autofocus value="0" />
            </div>
          </div>
          <div class="form-group col-xs-4 col-md-4 text-center" >
            <label>T. Electr&oacute;nica</label><br>
            <div class="btn btn-primary btn-lg"><i class="fa fa-credit-card fa-2x"></i></div>
            <div class="input-group">
            <input class="form-control text-center" style="font-size:14pt" id='telectronica' name='telectronica' value="0" />
            </div>
          
          </div>
          <div class="form-group col-xs-4 col-md-4 text-center" >
            <label>Deposito</label><br>
            <div class="btn btn-primary btn-lg"><i class="fa fa-ticket fa-2x"></i></div>
            <div class="input-group">
            <input class="form-control text-center" style="font-size:14pt" id='deposito' name='deposito' value="0" />
            </div>
          </div>
          </div>
        </div>
        @elseif ($modulo=='cambio')
        <div class="row">
          <div class="form-group input-group col-xs-12">
            <input type="text" id="txtFiltro" class="form-control" placeholder="Clave" autofocus="">
            <span class="input-group-addon"><i class="fa fa-filter"></i></span>

          </div>
          @section ('htable_panel_title','Productos')
          @section ('htable_panel_body')

          <table id="tblProductos" class="table table-hover">
            <thead>
              <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Existencia</th>
                <th>Precio</th>
                <th>Desc %</th>
              </tr>
            </thead>
            <tbody>


            </tbody>
          </table>

          @endsection

          @include('widgets.panel', array('header'=>true, 'as'=>'htable'))
        </div>
        @elseif ($modulo=='existencias')
        <div class="row">
          <div class="form-group col-xs-12">
            <label>Clave del producto </label><input type="text" id="txtClave" class="form-control" disabled="disabled">
          </div>
          <div class="form-group col-xs-12">
          <label>Descripcion corta </label><input type="text" id="txtDescripcionCorta" class="form-control" disabled="disabled">
</div>
          <div class="form-group col-xs-12">
            <label>Descripcion larga </label><input type="text" id="txtDescripcionLarga" class="form-control" disabled="disabled">
          </div>
        </div>
        <div class="row">
          @section ('stable_panel_title','Existencias por sucursal y almacen')
          @section ('stable_panel_body')

          <table id="tblExistencas" class="table table-hover">
            <thead>
              <tr>
                <th>Sucursal</th>
                <th>Almacen</th>
                <th>Existencia</th>
              </tr>
            </thead>
            <tbody>


            </tbody>
          </table>

          @endsection

          @include('widgets.panel', array('header'=>true, 'as'=>'stable'))
        </div>
        @elseif ($modulo=='nueva_cita')
        <div class="row">
          <div class="col-md-12">
          <input type="hidden" id="clienteid" name="clienteid" />
          <input type="hidden" id="telefono_r" name="telefono_r" />
          <input type="hidden" id="citaid" name="citaid" />
          <input type="hidden" id="hoy" name="hoy" value="{{date('Y-m-d')}}" />
          <div class="form-group col-md-12 col-xs-12">
            <label>Cliente</label>
            <input class="form-control" readonly="readonly" id='nombre_c' name='nombre_c' />
          </div>
          <div class="form-group col-xs-12">
            <label>Telefono(s)</label>
            <input class="form-control" id='telefono' name='telefono'  />
          </div>
          <div class="form-group col-xs-4">
            <label>Anticipo</label>
            <input class="form-control" id='anticipo' name='anticipo' value="0"/>
          </div>
          <div class="form-group col-xs-4">
            <label>Fecha</label>
            <input class="form-control" title="Fecha cita" id='fecha_inicio' name='fecha_inicio' value={{date('Y-m-d')}}   />
          </div>


          </div>
          <div class="col-md-12">
          <div class="form-group col-md-4 col-xs-12">
            <label>De:</label>
            <input class="form-control reloj" id='hora_ini' name='hora_ini' />
          </div>
          <div class="form-group col-md-4 col-xs-12" >
            <label id="lblPago">A:</label>
            <input class="form-control reloj" id='hora_fin' name='hora_fin' />
          </div>
          <div class="form-group col-md-12 col-xs-12" >
          <label>Comentarios:</label>
          <textarea class="form-control" id="comentarios" name="comentarios"></textarea>
          </div>
          </div>
        </div>
        @else

        {{$content_modal['cadena']}}
        @endif
      </div>
      <div class="modal-footer">
        @if($modulo=='cobrar')
          <label><input type="checkbox" id="chkregalo" name="chkregalo" />Regalo   </label>
        @endif
        @if(isset($buttons_modal))
        @foreach($buttons_modal as $boton)
        <button type="button" class="btn btn-default {{ $boton }}" >{{str_replace('btn','',$boton)}}</button>
        @endforeach
        @endif
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
