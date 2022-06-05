<div class="ticket"
@if(isset($id))
	id="{{$id}}"
@endif
>
	<div class="ticket-header">
		@if(!empty(Session::get('tienda')['logo']))
		<div style="text-align:center"><img src="{{url('/local/logos/').'/'.Session::get('tienda')['logo']}}" width="90%" /></div>
		@endif
    <div id="lblDireccionSucursal" class="lblDireccionSucursal"></div>
	</div>
  <br/>
	<div class="ticket-folio"><span>Folio</span></div>
  <div class="ticket-content">
    <table id="tblProductosTicket" class="tblProductos table table-hover">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <br/>
  <div class="ticket-totales">
  </div>
  <div class="ticket-totales-2">
  <!-- @if(isset($esAbono))
		@if($esAbono=='true')
		<table id="tblAbonosTicket" class="table table-hover">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Importe</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
		@endif
    @endif-->
    </div>
  <div class="ticket-footer">
    <span id="vendedor"></span><br/>
    <span>"Gracias por su compra"</span>

    <br/>
    <br/>
		<span class="fecha_hora"></span><br/>
    ESTIMADO CLIENTE DESPUES DE SU COMPRA NO HAY CAMBIOS NI DEVOLUCIONES
    PARA CUALQUIER ACLARACION CONSERVE SU TICKET.
  </div>

</div>
