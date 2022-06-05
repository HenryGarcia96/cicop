@extends('layouts.dashboard')
@section('page_heading','Dashboard')
@section('section')

<div class="row">

						<!-- BEGIN ALERT - REVENUE -->
						<div class="col-md-3 col-sm-6">
							<div class="card">
								<div class="card-body no-padding">
									<div class="alert alert-callout alert-info no-margin">
										<!-- <strong class="pull-right text-success text-lg">0,38% <i
												class="md md-trending-up"></i></strong> -->
										<strong class="text-xl">$ {{number_format($totalacobrar)}}</strong><br />
										<span class="opacity-50">Total a Cobrar del Mes</span>
										<div class="stick-bottom-left-right">

										</div>
									</div>
								</div>
								<!--end .card-body -->
							</div>
							<!--end .card -->
						</div>
						<!--end .col -->
						<!-- END ALERT - REVENUE -->

						<!-- BEGIN ALERT - VISITS -->
						<div class="col-md-3 col-sm-6">
							<div class="card">
								<div class="card-body no-padding">
									<div class="alert alert-callout alert-warning no-margin">
										<!-- <strong class="pull-right text-warning text-lg">0,01% <i
												class="md md-swap-vert"></i></strong> -->
										<strong class="text-xl">$ {{number_format($totalcobrado)}}</strong><br />
										<span class="opacity-50">Total Cobrado del Mes</span>
										<div class="stick-bottom-right">

										</div>
									</div>
								</div>
								<!--end .card-body -->
							</div>
							<!--end .card -->
						</div>
						<!--end .col -->
						<!-- END ALERT - VISITS -->

						<!-- BEGIN ALERT - BOUNCE RATES -->
						<div class="col-md-3 col-sm-6">
							<div class="card">
								<div class="card-body no-padding">
									<div class="alert alert-callout alert-danger no-margin">
										<!-- <strong class="pull-right text-danger text-lg">0,18% <i
												class="md md-trending-down"></i></strong> -->
										<strong class="text-xl">$ {{number_format($totalacobrar-$totalcobrado)}}</strong><br />
										<span class="opacity-50">Adeudos del Mes</span>
										<div class="stick-bottom-left-right">
											<div class="progress progress-hairline no-margin">

											</div>
										</div>
									</div>
								</div>
								<!--end .card-body -->
							</div>
							<!--end .card -->
						</div>
						<!--end .col -->
						<!-- END ALERT - BOUNCE RATES -->

						<!-- BEGIN ALERT - TIME ON SITE -->
						<div class="col-md-3 col-sm-6">
							<div class="card">
								<div class="card-body no-padding">
									<div class="alert alert-callout alert-success no-margin">
										<h1 class="pull-right text-success"><i class="md md-timer"></i></h1>
										<strong class="text-xl">{{$morosos}}</strong><br />
										<span class="opacity-50">Pagos Vencidos del Mes</span>
									</div>
								</div>
								<!--end .card-body -->
							</div>
							<!--end .card -->
						</div>
						<!--end .col -->
						<!-- END ALERT - TIME ON SITE -->

</div>
					<!--end .row -->
					<div class="row">

						<!-- BEGIN SITE ACTIVITY -->
						<div class="col-md-8">
							<div class="card ">
								<div class="row">
									<div class="col-md-12">
										<div class="card-head">
											<header>Pagos vencidos</header>
										</div>
										<!--end .card-head -->
										<div class="card-body">
											<div class="table-responsive">
												<table class="table no-margin">
													<thead>
														<tr>
															<th>#</th>
															<th>Nombre</th>
															<th>No Pagos Vencidos</th>
															<th></th>
															<th></th>
														</tr>
													</thead>
													<tbody>
													
													@foreach ($especiales as $esp)
														<tr data-id="{{ $esp['id_intencion'] }}" data-folio="{{$esp['folio']}}" data-nombre="{{$esp['nombre']}}" data-telefono="{{$esp['celular']}}" >
														<td>{{ $esp['folio'] }}</td>
														<td>{{ $esp['nombre'] }}</td>
														<td>{{ $esp['atrasos'] }}</td>
														<td>{{ 'Pago Esp'}}</td>
														<td><a href="#!" class="btn btn-danger btn-cancelar" data-id="{{$esp['id_intencion']}}" data-toggle="tooltip" data-placement="top" title="Cancelar"><i class="fa fa-ban"></i></a></td>
														<td><a href="#!" class="btn btn-warning btn-convenio" data-id="{{$esp['id_intencion']}}"data-toggle="tooltip" data-placement="top" title="Convenio"><i class="fa fa-thumbs-up"></i></a></td>
														<td><a href="#!" class="btn btn-success btn-seguimiento" data-id="{{$esp['id_intencion']}}"data-toggle="tooltip" data-placement="top" title="Seguimiento"><i class="fa fa-file-text"></i></a></td>
													
													@endforeach
													@foreach ($atrasos as $intencion)
														<tr data-id="{{ $intencion['id_intencion'] }}" data-folio="{{ $intencion['folio'] }}" data-nombre="{{$intencion['nombre']}}" data-telefono="{{$intencion['celular']}}">
														<td>{{ $intencion['folio'] }}</td>
														<td>{{ $intencion['nombre'] }}</td>
														<td>{{ $intencion['atrasos'] }}</td>
														<td>{{ (($intencion['tipo_pago']==1)?'Quincena':'Mensualidad') }}</td>
														@if($intencion['atrasos']>=3 || $intencion['tipo']==2)
														<td><a href="#!" class="btn btn-danger btn-cancelar" data-id="{{$intencion['id_intencion']}}" data-toggle="tooltip" data-placement="top" title="Cancelar"><i class="fa fa-ban"></i></a></td>
														<td><a href="#!" class="btn btn-warning btn-convenio" data-id="{{$intencion['id_intencion']}}"data-toggle="tooltip" data-placement="top" title="Convenio"><i class="fa fa-thumbs-up"></i></a></td>
														<td><a href="#!" class="btn btn-success btn-seguimiento" data-id="{{$intencion['id_intencion']}}"data-toggle="tooltip" data-placement="top" title="Seguimiento"><i class="fa fa-file-text"></i></a></td>
														@else
														<td></td>
														<td></td>
														<td><a href="#!" class="btn btn-success btn-seguimiento" data-id="{{$intencion['id_intencion']}}"data-toggle="tooltip" data-placement="top" title="Seguimiento"><i class="fa fa-file-text"></i></a></td>
														@endif
													@endforeach
													</tbody>
												</table>
											</div>
											<!--end .table-responsive -->
										</div>
										<!--end .card-body -->
									</div>
									<!--end .col -->

									<!--end .col -->
								</div>
								<!--end .row -->
							</div>
							<!--end .card -->
						</div>
						<!--end .col -->
						<!-- END SITE ACTIVITY -->
						<div class="col-md-4">
						<div class="card ">
								<div class="row">
									<div class="col-md-12">
										<div class="card-head">
											<header>Accesos Rapidos</header>
										</div>
										<!--end .card-head -->
										<div class="card-body">
											<a href="{{ url ('pagos') }}" class="btn"><i class="md md-attach-money"></i>&nbsp;Pagos</a><br>
											<a href="{{ url ('edos') }}" class="btn"><i class="md md-folder"></i>&nbsp;Estados de Cuenta</a><br>
											<a href="{{ url ('contrato') }}" class="btn"><i class="fa fa-diamond"></i>&nbsp;Nuevo contrato</a><br>
											<a href="{{ url ('convenio') }}" class="btn"><i class="fa fa-file"></i>&nbsp;Convenio</a>
											
										</div>
										<!--end .card-body -->
									</div>
									<!--end .col -->

									<!--end .col -->
								</div>
								<!--end .row -->
							</div>
						</div>


					</div>
					<!--end .row -->
					{{ Form::open(['url' => ['contratos/cancelar', 'USER_ID'], 'method' => 'DELETE', 'id' => 'frm-delete']) }}
    				{{ Form::close() }}
					@include('modal.seguimiento')
@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
	  
	
    $('.btn-cancelar').click(function(){
		console.log(url);
		var fila=$(this).parents('tr');
		var id=$(this).data('id');
		var button=$(this);
		if(confirm('¿Desea cancelar el contrato con el folio '+fila.data('folio')+'?'))
		{
			var form = $('#frm-delete');
			var url = form.attr('action').replace('USER_ID',id);
			var data = form.serialize()+'&estatus=1';

			$.post(url, data, function(result) {
				console.log(result);
				$.toast({
					text: result.mensaje,
					heading: 'ATENCION',
					icon: result.color,
					showHideTransition: 'fade',
					allowToastClose: true,
					hideAfter: 3000,
					stack: 5,
					position: 'top-right',
					textAlign: 'left',
					loader: false,
					loaderBg: '#9EC600',
					beforeShow: function() {},
					afterShown: function() {},
					beforeHide: function() {},
					afterHidden: function() {}
				});

				if(result.error==0)
				{
					button.remove();
				}

			}).fail(function() {
				alert('Error al cancelar el contrato');
			});
		}
	});
	$('.btn-convenio').click(function(){
		console.log(url);
		var fila=$(this).parents('tr');
		var id=$(this).data('id');
		var url="{{url('convenio')}}/"+id;
		console.log(url);
		
		window.location.href=url;
	});
  });
</script>
@endsection


@stop
