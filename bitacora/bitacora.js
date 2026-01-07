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
                        <td>
                            <span class="${t.estatus === 'ACTIVO' ? 'badge-activo' : 'badge-baja'}">
                                ${t.estatus}
                            </span>
                        </td>
                        <td>
                            ${t.estatus === 'ACTIVO'
                                ? `<button class="btn btn-danger btn-sm" onclick="baja(${t.tel_id})">Baja</button>`
                                : ''
                            }
                        </td>
                    </tr>
                `;
            });
        });
}

function guardarTelefono(e) {
    e.preventDefault();

    const datos = new FormData(e.target);

    fetch("controller.php?op=guardar", {
        method: "POST",
        body: datos
    }).then(() => {
        cerrarModal();
        cargarTelefonos();
    });
}

function baja(id) {
    if (!confirm("¿Dar de baja este equipo?")) return;

    const datos = new FormData();
    datos.append("tel_id", id);

    fetch("controller.php?op=baja", {
        method: "POST",
        body: datos
    }).then(() => cargarTelefonos());
}
