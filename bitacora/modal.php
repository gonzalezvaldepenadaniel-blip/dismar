<div class="modal-custom" id="modalTelefono">
    <div class="modal-box">
        <h5 class="mb-3">Agregar Teléfono</h5>

        <form id="formTelefono">

            <!-- FILA 1 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Marca</label>
                    <input type="text" name="marca" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Modelo</label>
                    <input type="text" name="modelo" class="form-control">
                </div>
            </div>

            <!-- FILA 2 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Número de Serie</label>
                    <input type="text" name="num_serie" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Número de Teléfono</label>
                    <input type="text" name="num_telefono" class="form-control">
                </div>
            </div>

            <!-- FILA 3 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Puesto</label>
                    <input type="text" name="puesto" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Área</label>
                    <input type="text" name="area" class="form-control">
                </div>
            </div>

            <!-- FILA 4 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>CEDIS</label>
                    <select name="cedis" class="form-control">
                        <option value="">Seleccione CEDIS</option>
                        <option value="CEDIS Norte">IZTAPALAPA</option>
                        <option value="CEDIS Sur">CHICOLOAPAN</option>
                        <option value="CEDIS Centro">ECATEPEC</option>
                        <option value="CEDIS Centro">QUERETARO</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="nombre_usuario" class="form-control">
                </div>
            </div>

            <!-- FILA 5 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Folio</label>
                    <input type="text" name="folio" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Front</label>
                    <input type="text" name="front" class="form-control">
                </div>
            </div>

            <!-- FILA 6 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Back</label>
                    <input type="text" name="back" class="form-control">
                </div>
            </div>

            <!-- COMENTARIOS -->
            <div class="mt-2">
                <label>Comentarios</label>
                <textarea name="comentarios" class="form-control"></textarea>
            </div>

            <!-- BOTONES -->
            <div class="modal-actions mt-3">
                <button type="button" class="btn btn-secondary" id="btnCerrar">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="modalDetalle" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Detalles del Teléfono</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="detalleTelefono">
        Cargando...
      </div>

    </div>
  </div>
</div>
