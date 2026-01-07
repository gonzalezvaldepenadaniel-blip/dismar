<!-- MODAL USUARIO -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<form id="formUsuario">

<div class="modal-header">
  <h5 class="modal-title">Usuario</h5>
  <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<input type="hidden" name="usu_id" id="usu_id">

<input class="form-control mb-2" name="usu_nombre" id="usu_nombre"
placeholder="Nombre" required>

<input class="form-control mb-2" name="usu_apellido" id="usu_apellido"
placeholder="Apellido" required>

<input type="email" class="form-control mb-2" name="usu_correo"
id="usu_correo" placeholder="Correo" required>

<!-- CEDIS -->
<select class="form-control mb-2" name="cedis" id="cedis" required>
    <option value="">Cedis</option>
    <option value="Iztapalapa">Iztapalapa</option>
    <option value="Ecatepec">Ecatepec</option>
    <option value="Tultitlán">Tultitlán</option>
    <option value="Corporativo">Corporativo</option>
    <option value="Querétaro">Querétaro</option>
</select>


<!-- CONTRASEÑA -->

<!-- CONTRASEÑA -->
<!-- CONTRASEÑA -->
<div class="form-group password-wrapper">
    <input type="password" class="form-control"
           name="usu_pass" id="usu_pass"
           placeholder="Contraseña">

    <span class="toggle-pass">👁</span>
</div>

<div class="form-group password-wrapper">
    <input type="password" class="form-control"
           id="usu_pass_confirm"
           placeholder="Confirmar contraseña">

    <span class="toggle-pass">👁</span>
</div>

<small class="text-muted">
    Deja la contraseña en blanco si no deseas cambiarla.
</small>


<select class="form-control mt-2" name="rol" id="rol">
  <option value="user">Usuario</option>
  <option value="admin">Administrador</option>
</select>

</div>

<div class="modal-footer">
  <button class="btn btn-primary">Guardar</button>
</div>

</form>

</div>
</div>
</div>
