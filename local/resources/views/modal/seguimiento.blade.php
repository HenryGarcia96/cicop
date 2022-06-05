<!--Inicia modal -->
                        <!-- Modal -->
                        <div class="modal fade" id="seguimientoModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Seguimiento</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                        {{Form::open(array('url' => 'cobranza/create','method' => 'POST','id'=>'frm-seguimiento','class'=>'form'))}}
                                          <input type="hidden" id="id_contrato_cobranza" name="id_contrato_cobranza" value="0" />
                                          
                                                    <ul class="nav nav-tabs" data-toggle="tabs">
                                                        <li class="active"><a href="#first1">Nuevo</a></li>
                                                        <li><a href="#second1">Historico</a></li>
                                                        
                                                    </ul>
                                                
                                                <div class="tab-content">
                                                    <!-- Inicia nuevo -->
                                                    <div class="tab-pane active" id="first1">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" autocomplete="off" class="form-control" readonly="readonly" id="folio_cobranza" name="folio_cobranza">
                                                            <label for="folio_cobranza">Folio</label>
                                                        </div>  
                                                        <div class="form-group col-md-6">
                                                            <input type="text" autocomplete="off" class="form-control" readonly="readonly" id="nombre_cobranza" name="nombre_cobranza">
                                                            <label for="nombre_cobranza">Cliente</label>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="text" autocomplete="off" class="form-control" readonly="readonly" id="telefono_cobranza" name="telefono_cobranza">
                                                            <label for="telefono_cobranza">Telefono</label>
                                                        </div>  
                                                        <div class="form-group col-md-12">
                                                            <textarea class="form-control" id="observaciones" name="observaciones" rows="5"></textarea>
                                                            <label for="forma_pago">Comentario</label>
                                                        </div>
                                                    </div>
                                                    <!-- Termina nuevo -->
                                                    <!-- Inicio historico -->
                                                    <div class="tab-pane" id="second1">
                                                        <table id="historico" class="table">
                                                            <thead>
                                                                <th>Fecha</th>
                                                                <th>Usuario</th>
                                                                <th>Comentarios</th>
                                                            </thead>
                                                            
                                                        </table>
                                                    </div>
                                                    <!-- Termina historico -->
                                                    
                                                </div><!--end .card-body -->
                                            
                                        
                                    {{ Form::close()}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary btn-guardar-seguimiento">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Termina modal -->
<script>
    let fila_seguimiento=null;
    let id_seguimiento=0;
    function cargarHistorico(){
        var fila=fila_seguimiento;
        var id=id_seguimiento;
        $('#frm-seguimiento')[0].reset();
		$('#nombre_cobranza').val(fila.data('nombre'));
		$('#folio_cobranza').val(fila.data('folio'));
        $('#telefono_cobranza').val(fila.data('telefono'));
		$('#id_contrato_cobranza').val(id);

		//Cargamos el historico de seguimiento
		var form = $('#frm-seguimiento');
			var url = form.attr('action').replace('create','historico');
			var data = form.serialize();
            console.log(url);
            console.log(data);
            $('#historico tbody tr').remove();
			$.post(url, data, function(result) {
				$.each(result,function(index,item){
					var html=`<tr>
                    <td>${item.fecha_registro}</td>
                    <td>${item.nombre+' '+item.apellidos}</td>
                    <td>${item.observaciones}</td>
                    </tr>`;
                    $('#historico').append(html);
				});

			}).fail(function(res) {
				console.log(res);
				console.log('Error al cargar el historico del seguimiento');
			});

		$('#seguimientoModal').modal('show');
    }
    $('.btn-seguimiento').click(function(){
        fila_seguimiento=$(this).parents('tr');
        id_seguimiento=$(this).data('id');
		cargarHistorico();
	});
	$('.btn-guardar-seguimiento').click(function(){
		
			var form = $('#frm-seguimiento');
			var url = form.attr('action');
			var data = form.serialize();

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

				if(result.color=='success')
				{
					cargarHistorico();
				}

			}).fail(function() {
				alert('Error al registrar el seguimiento');
			});
		
	});
</script>