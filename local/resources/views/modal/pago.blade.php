<!--Inicia modal -->
                        <!-- Modal -->
                        <div class="modal fade" id="pagoModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Pago</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    {{Form::open(array('url' => 'pagos/create','method' => 'POST','id'=>'frm-pago','class'=>'form'))}}
                                    <div class="row">
                                          <input type="hidden" id="folio_p" name="folio_p" value="0" />
                                            <div class="form-group col-md-6">
                                                <input type="text" autocomplete="off" class="form-control" id="importe" name="importe">
                                                <label for="importe">Importe</label>
                                            </div>
                                            
                        <div class="form-group col-md-6">
                            <select class="form-control" id="forma_pago" name="forma_pago">
                                <option>EFECTIVO</option>
                                <option>DEPOSITO</option>
                                <option>TARJETA</option>
                                <option>TRANSFERENCIA</option>
                            </select>
                            <label for="forma_pago">Forma de Pago</label>
                        </div>
                        <div class="form-group col-md-6">
                            <select class="form-control" id="institucion_bancaria" name="institucion_bancaria">
                                <option>BANCOPPEL</option>
                                <option>BANAMEX</option>
                                <option>BANJERCITO</option>
                                <option>BANCO AZTECA</option>
                                <option>BANCOMER</option>
                                <option>HSBC</option>
                                <option>INBURSA</option>
                                <option>SANTANDER</option>
                                <option>SCOTIABANK</option>
                                <option>TELECOM</option>
                                <option>OXXO</option>
                                <option>OTRO</option>

                            </select>
                            <label for="institucion_bancaria">Institucion Bancaria</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input class="form-control" id="fecha_pago" name="fecha_pago" autocomplete="off"
                                value="{{ date('Y-m-d') }}" />
                            <label for="fecha_pago">Fecha de pago</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input class="form-control" id="ticket" name="ticket" autocomplete="off"/>
                            <label for="ticket">Ticket</label>
                        </div>
                        </div>

                                        {{ Form::close()}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary btn-guardar">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Termina modal -->