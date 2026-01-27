document.addEventListener("DOMContentLoaded", () => {
    cargarTelefonos();

    document.getElementById("btnNuevo").addEventListener("click", abrirModal);
    document.getElementById("btnCerrar").addEventListener("click", cerrarModal);
    document.getElementById("formTelefono").addEventListener("submit", guardarTelefono);
});

function abrirModal() {
    document.getElementById("modalTelefono").style.display = "flex";
}

function cerrarModal() {
    document.getElementById("modalTelefono").style.display = "none";
    document.getElementById("formTelefono").reset();
}

function cargarTelefonos() {
    fetch("controller.php?op=listar")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#tablaTelefonos tbody");
            tbody.innerHTML = "";

            data.forEach(t => {
                tbody.innerHTML += `
                    <tr>
                        <td>${t.tel_id}</td>
                        <td>${t.marca}</td>
                        <td>${t.modelo}</td>
                        <td>${t.num_serie}</td>
                        <td>${t.num_telefono}</td>
                        <td>${t.puesto}</td>
                        <td>${t.area}</td>
                        <td>${t.nombre_usuario}</td>
                        <td>${t.cedis ?? ''}</td>
                        <td>${t.front}</td>
                        <td>${t.back}</td>
                        <td>${t.folio}</td>
                        <td>${t.comentarios}</td>
                        <td>
                            <span class="${t.estatus === 'ACTIVO' ? 'badge-activo' : 'badge-baja'}">
                                ${t.estatus}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="baja(${t.tel_id})">
                                Baja
                            </button>
                        </td>
                    </tr>
                `;
            });
        });
}
document.addEventListener("click", function(e){
    if(e.target.classList.contains("btnVer")){
        let id = e.target.getAttribute("data-id");

        fetch("detalle.php?id=" + id)
        .then(res => res.text())
        .then(data => {
            document.getElementById("detalleTelefono").innerHTML = data;
            let modal = new bootstrap.Modal(document.getElementById("modalDetalle"));
            modal.show();
        });
    }
});
