<!-- MODAL USUARIO -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form id="formUsuario">

        <!-- HEADER -->
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Usuario</h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            &times;
          </button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <input type="hidden" name="usu_id" id="usu_id">

          <!-- DATOS -->
          <div class="mb-3">
            <h6 class="text-muted">Datos del usuario</h6>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input class="form-control" name="usu_nombre" id="usu_nombre" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Apellidos</label>
              <input class="form-control" name="usu_apellido" id="usu_apellido" required>
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label">Correo</label>
            <input type="email" class="form-control" name="usu_correo" id="usu_correo" required>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label class="form-label">Cedis</label>
              <select class="form-control" name="cedis" id="cedis" required>
                <option value="">Seleccionar</option>
                <option>Iztapalapa</option>
                <option>Ecatepec</option>
                <option>Tultitlán</option>
                <option>Corporativo</option>
                <option>Querétaro</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Rol</label>
              <select class="form-control" name="rol" id="rol">
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
              </select>
            </div>
          </div>

          <hr>

          <!-- SEGURIDAD -->
          <div class="mb-3">
            <h6 class="text-muted">Seguridad</h6>
          </div>

          <div class="row">
           <div class="col-md-6">
  <label class="form-label">Contraseña</label>

  <div class="password-wrapper">
    <input type="password"
           class="form-control"
           name="usu_pass"
           id="usu_pass">

    <span class="toggle-password"
          onclick="verPassword('usu_pass', this)">
      <i class="fa fa-eye"></i>
    </span>
  </div>
</div>

<div class="col-md-6">
  <label class="form-label">Confirmar contraseña</label>

  <div class="password-wrapper">
    <input type="password"
           class="form-control"
           id="usu_pass_confirm">

    <span class="toggle-password"
          onclick="verPassword('usu_pass_confirm', this)">
      <i class="fa fa-eye"></i>
    </span>
  </div>
</div>
          </div>

          <div class="col-12">
    <small class="text-muted fst-italic">
      Déjala en blanco si no deseas cambiar la contraseña.
    </small>
  </div>

</div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary px-4">
            Guardar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
