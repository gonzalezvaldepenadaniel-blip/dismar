<?php
require_once("../config/conexion.php");
$con = Conectar::conexion();
?>

<link rel="stylesheet" href="bitacora.css">

<div class="layout">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div class="logo">SIE</div>
       <nav>
            <a href="empleados.php">Empleados</a>
            <a href="computadoras.php">Computadoras</a>
            <a href="index.php">Teléfonos</a>
   
            <a href="/Dismar/Administrador/administrador.php" class="logout">
                Volver
            </a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <div id="btnMenu" class="hamburger">☰</div>
        <div id="overlay" class="overlay"></div>

        <div class="topbar">
            <h5>Inventario de Computadoras</h5>

            <button class="btn btn-primary btn-sm" id="btnNuevaPC">
                <i class="bi bi-plus-lg"></i> Nueva
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="tablaComputadoras">
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Procesador</th>
                            <th>RAM</th>
                            <th>SO</th>
                            <th>Tipo</th>
                            <th>CEDIS</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </main>
</div>
    <div class="modal-box">
        <h5 class="mb-3">Agregar Computadora</h5>

        <form id="formComputadora">

            <input type="hidden" name="pc_id" id="pc_id">

            <!-- FILA 1 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Marca</label>
                    <input type="text" name="marca" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Procesador</label>
                    <input type="text" name="procesador" class="form-control">
                </div>
            </div>

            <!-- FILA 2 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>RAM</label>
                    <input type="text" name="ram" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Sistema Operativo</label>
                    <input type="text" name="so" class="form-control">
                </div>
            </div>

            <!-- FILA 3 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Disco Duro</label>
                    <input type="text" name="disco_duro" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="ESCRITORIO">Escritorio</option>
                        <option value="LAPTOP">Laptop</option>
                    </select>
                </div>
            </div>

            <!-- CEDIS -->
            <div class="mt-2">
                <label>CEDIS</label>
                <select name="cedis" id="cedis" class="form-control">
                    <option value="">Seleccione CEDIS</option>
                    <option value="ECATEPEC">ECATEPEC</option>
                    <option value="IZTAPALAPA">IZTAPALAPA</option>
                    <option value="CHICOLOAPAN">CHICOLOAPAN</option>
                    <option value="TULTITLAN">TULTITLAN</option>
                    <option value="QUERETARO">QUERETARO</option>
                    <option value="NEXTLALPAN">NEXTLALPAN</option>
                </select>
            </div>

            <!-- EMPLEADO -->
            <div class="mt-2">
                <label>Asignar a Empleado</label>
                <select name="usu_id" id="empleado" class="form-control">
                    <option value="">Seleccione empleado</option>
                </select>
            </div>

            <!-- PERIFERICOS -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Monitor</label>
                    <input type="text" name="monitor" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Teclado</label>
                    <input type="text" name="teclado" class="form-control">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Mouse</label>
                    <input type="text" name="mouse" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Cámara</label>
                    <input type="text" name="camara" class="form-control">
                </div>
            </div>

            <!-- SERIES -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Serie CPU</label>
                    <input type="text" name="num_serie_cpu" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Serie Monitor</label>
                    <input type="text" name="num_serie_monitor" class="form-control">
                </div>
            </div>

            <!-- EXTRA -->
            <div class="mt-2">
                <label>Folio</label>
                <input type="text" name="folio" class="form-control">
            </div>

            <div class="mt-2">
                <label>Comentarios</label>
                <textarea name="comentarios" class="form-control"></textarea>
            </div>

            <!-- BOTONES (MISMO DISEÑO QUE TELÉFONOS) -->
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


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="bitacora.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


</body>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const btnMenu = document.getElementById("btnMenu");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    btnMenu.onclick = () => {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    };

    overlay.onclick = () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    };

});
</script>

</html>
