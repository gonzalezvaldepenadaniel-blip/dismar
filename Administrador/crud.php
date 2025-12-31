<!-- MODAL USUARIO -->
<div class="modal fade" id="modalUsuario">
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

<!-- CONTRASEÑA -->
<input type="password" class="form-control mb-2"
name="usu_pass" id="usu_pass"
placeholder="Contraseña">

<input type="password" class="form-control mb-2"
id="usu_pass_confirm"
placeholder="Confirmar contraseña">

<div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" id="verPass">
    <label class="form-check-label" for="verPass">
        Mostrar contraseña
    </label>
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
