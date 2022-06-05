<!--Inicia modal -->
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Cliente</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    {{Form::open(array('url' => 'clientes/create','method' => 'POST','id'=>'frm-paciente','class'=>'form'))}}
                                          <input type="hidden" id="id_paciente" name="id_paciente" value="0" />
                                            <div class="form-group">
                                                <input type="text" autocomplete="off" class="form-control" id="nombre" name="nombre">
                                                <label for="nombre">Nombre</label>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="control-label">Tipo Cliente</label>
                                                <div class="col-sm-9">
                                                    <label class="radio-inline radio-styled">
                                                        <input type="radio" name="tipo_cliente" checked="checked" value="0"><span>Credito</span>
                                                    </label>
                                                    <label class="radio-inline radio-styled">
                                                        <input type="radio" name="tipo_cliente" value="1"><span>Contado</span>
                                                    </label>
                                                </div>
                                            </div><br>
                                            <div class="form-group">
                                              <label class="control-label">Identificacion</label>
                                              <div class="col-sm-9">
                                                  <label class="checkbox-inline checkbox-styled">
                                                    <input type="checkbox" name="identificacion" value="INE"><span>INE</span>
                                                  </label>
                                                  <label class="checkbox-inline checkbox-styled">
                                                    <input type="checkbox" name="identificacion" value="TARJETA DE CIRCULACION"><span>TARJETA DE CIRCULACION</span>
                                                  </label>
                                                  <label class="checkbox-inline checkbox-styled">
                                                    <input type="checkbox" name="identificacion" value="OTRO"><span>OTRO</span>
                                                  </label>
                                              </div>
                                            </div><br>
                                            <div class="form-group">
                                                <input type="text" class="form-control" autocomplete="off" id="direccion" name="direccion">
                                                <label for="direccion">Direccion:</label>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="referencia" autocomplete="off" name="referencia">
                                                <label for="referencia">Referencias</label>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="telefono" autocomplete="off" name="telefono">
                                                <label for="telefono">Tel&eacute;fonos</label>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="email" autocomplete="off" name="email">
                                                <label for="email">Correo Electronico</label>
                                            </div>

                                        {{ Form::close()}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary btn-guardar-cliente">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Termina modal -->