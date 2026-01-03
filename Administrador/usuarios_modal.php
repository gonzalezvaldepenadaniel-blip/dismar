<?php
require_once("../config/conexion.php");
?>

<!-- ================= MODAL USUARIOS ================= -->
<div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Usuarios registrados</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <button class="btn btn-primary mb-3" onclick="nuevoUsuario()">
          Nuevo usuario
        </button>

        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Cedis</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <!-- ESTE TBODY ES EL QUE SE RECARGA POR AJAX -->
          <tbody id="tablaUsuarios">
            <?php include("usuarios_tabla.php"); ?>
          </tbody>

        </table>

      </div>
    </div>
  </div>
</div>
