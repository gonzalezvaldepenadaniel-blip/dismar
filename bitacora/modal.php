<?php
require_once("../config/conexion.php");
$con = Conectar::conexion();
?>

<div class="modal-custom" id="modalTelefono">
    <div class="modal-box">
        <h5 class="mb-3">Agregar Teléfono</h5>

       <form id="formTelefono" enctype="multipart/form-data">
        

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

            <div class="mb-3">
<label class="form-label">IMEI</label>
<input type="text" name="imei" class="form-control" required>
</div>
<label>CEDIS</label>

<select name="cedis" id="cedis" class="form-control" required>

<option value="">Seleccione CEDIS</option>

<option value="ECATEPEC">ECATEPEC</option>
<option value="IZTAPALAPA">IZTAPALAPA</option>
<option value="CHICOLOAPAN">CHICOLOAPAN</option>
<option value="TULTITLAN">TULTITLAN</option>
<option value="QUERETARO">QUERETARO</option>
<option value="NEXTLALPAN">NEXTLALPAN</option>

</select>



<label>Asignar a Empleado</label>

<select name="usu_id" id="empleado" class="form-control" required>

<option value="">Seleccione empleado</option>

</select>

            <!-- FILA 3 -->
            <div class="row mt-2">

                <div class="col-md-6">
                    <label>Folio</label>
                    <input type="text" name="folio" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Front</label>
                    <input type="file" name="front" class="form-control" accept="image/*" required>
                </div>

            </div>


            <!-- FILA 4 -->

            <div class="row mt-2">

                <div class="col-md-6">
                    <label>Back</label>
                    <input type="file" name="back" class="form-control" accept="image/*" required>
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
