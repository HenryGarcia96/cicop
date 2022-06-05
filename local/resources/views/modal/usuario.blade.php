<!--Inicia modal -->
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Usuario</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    {{Form::open(array('url' => 'usuarios/create','method' => 'POST','id'=>'frm-usuario','class'=>'form'))}}
                                          <input type="hidden" id="id_usuario" name="id_usuario" value="0" />
                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" autocomplete="off" id="nombre" name="nombre" required>
                                                <label for="nombre">Nombre</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="email" autocomplete="off" name="email">
                                                <label for="email">Email</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="telefono" autocomplete="off" name="telefono">
                                                <label for="telefono">Tel&eacute;fono</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <select class="form-control" name="tbl_rol_id" id="tbl_rol_id">
                                                </select>
                                                <label for="tbl_rol_id">Rol</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" autocomplete="off" class="form-control" id="usuario" name="usuario" required pattern="[A-Za-z0-9]+" >
                                                <label for="usuario">Usuario</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input class="form-control" id="password" autocomplete="off" name="password">
                                                <label for="password">Password</label>
                                            </div>
                                            <input type="hidden" id="old_password" autocomplete="off" name="old_password">
                                            
                                            </div>
                                        {{ Form::close()}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary btn-guardar-usuario">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Termina modal -->