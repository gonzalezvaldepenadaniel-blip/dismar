document.addEventListener("DOMContentLoaded", () => {

    cargarComputadoras();

    let btnNuevo = document.getElementById("btnNuevo");
    if(btnNuevo){
        btnNuevo.addEventListener("click", abrirModal);
    }

    let btnCerrar = document.getElementById("btnCerrar");
    if(btnCerrar){
        btnCerrar.addEventListener("click", cerrarModal);
    }

    let form = document.getElementById("formComputadora");
    if(form){
        form.addEventListener("submit", guardarComputadora);
    }

});

/* =========================
   MODAL
========================= */

function abrirModal(){
    document.getElementById("modalComputadora").style.display="flex";
}

function cerrarModal(){

    document.getElementById("modalComputadora").style.display="none";

    let form = document.getElementById("formComputadora");
    if(form) form.reset();

    document.getElementById("pc_id").value = "";

    let empleado = document.getElementById("empleado");
    if(empleado){
        empleado.innerHTML="<option value=''>Seleccione empleado</option>";
    }
}

/* =========================
   CARGAR DATOS
========================= */

function cargarComputadoras(){

    fetch("controller_pc.php?op=listar")
    .then(res=>res.json())
    .then(data=>{

        const tbody = document.querySelector("#tablaComputadoras tbody");
        tbody.innerHTML = "";

        data.forEach(pc => {

            tbody.innerHTML += `
            <tr>
                <td>${pc.marca}</td>
                <td>${pc.procesador}</td>
                <td>${pc.ram}</td>
                <td>${pc.so}</td>
                <td>${pc.disco_duro}</td>
                <td>${pc.tipo}</td>
                <td>${pc.monitor}</td>
                <td>${pc.teclado}</td>
                <td>${pc.mouse}</td>
                <td>${pc.camara}</td>
                <td>${pc.num_serie_cpu}</td>
                <td>${pc.num_serie_monitor}</td>
                <td>${pc.puesto ?? ''}</td>
                <td>${pc.nombre ?? ''} ${pc.apellidop ?? ''}</td>
                <td>${pc.folio}</td>
                <td>${pc.comentarios}</td>

                <td class="text-center">
                    <button class="btn btn-acciones"
                    onclick="abrirAcciones(${pc.pc_id})">
                    <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </td>
            </tr>
            `;
        });

    });

}

/* =========================
   GUARDAR / EDITAR
========================= */

function guardarComputadora(e){

    e.preventDefault();

    let formData = new FormData(document.getElementById("formComputadora"));
    let pc_id = document.getElementById("pc_id").value;

    let url = "controller_pc.php?op=guardar";

    if(pc_id){
        url = "controller_pc.php?op=editar";
    }

    fetch(url,{
        method:"POST",
        body:formData
    })
    .then(res=>res.text())
    .then(res=>{

        if(res.trim() === "OK"){

            cerrarModal();
            cargarComputadoras();

            Swal.fire({
                icon:"success",
                title:"Guardado correctamente",
                timer:1500,
                showConfirmButton:false
            });

        }else{
            Swal.fire({
                icon:"error",
                title:"Error al guardar"
            });
        }

    });

}

/* =========================
   EDITAR
========================= */

function editar(id){

    fetch("controller_pc.php?op=obtener&pc_id="+id)
    .then(res=>res.json())
    .then(data=>{

        abrirModal();

        document.getElementById("pc_id").value = data.pc_id;

        document.querySelector("[name='marca']").value = data.marca;
        document.querySelector("[name='procesador']").value = data.procesador;
        document.querySelector("[name='ram']").value = data.ram;
        document.querySelector("[name='so']").value = data.so;
        document.querySelector("[name='disco_duro']").value = data.disco_duro;
        document.querySelector("[name='tipo']").value = data.tipo;

        document.querySelector("[name='monitor']").value = data.monitor;
        document.querySelector("[name='teclado']").value = data.teclado;
        document.querySelector("[name='mouse']").value = data.mouse;
        document.querySelector("[name='camara']").value = data.camara;

        document.querySelector("[name='num_serie_cpu']").value = data.num_serie_cpu;
        document.querySelector("[name='num_serie_monitor']").value = data.num_serie_monitor;

        document.querySelector("[name='puesto']").value = data.puesto;
        document.querySelector("[name='folio']").value = data.folio;
        document.querySelector("[name='comentarios']").value = data.comentarios;

    });

}

/* =========================
   ACCIONES
========================= */

let computadoraSeleccionada = null;

function abrirAcciones(id){
    computadoraSeleccionada = id;
    document.getElementById("modalAcciones").style.display = "flex";
}

function cerrarAcciones(){
    document.getElementById("modalAcciones").style.display="none";
}

document.getElementById("cerrarAcciones").onclick = cerrarAcciones;

document.querySelector(".accion-btn.editar").onclick = () => {
    editar(computadoraSeleccionada);
    cerrarAcciones();
};

document.querySelector(".accion-btn.baja").onclick = () => {
    baja(computadoraSeleccionada);
    cerrarAcciones();
};

document.querySelector(".accion-btn.alta").onclick = () => {
    alta(computadoraSeleccionada);
    cerrarAcciones();
};

/* =========================
   BAJA
========================= */

function baja(id){

    Swal.fire({
        title: '¿Dar de baja la computadora?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result)=>{

        if(result.isConfirmed){

            fetch("controller_pc.php?op=baja",{
                method:"POST",
                headers:{
                    "Content-Type":"application/x-www-form-urlencoded"
                },
                body:"pc_id="+id
            })
            .then(res=>res.text())
            .then(res=>{
                if(res.trim() === "OK"){
                    cargarComputadoras();
                }
            });

        }

    });

}

/* =========================
   ALTA
========================= */

function alta(id){

    fetch("controller_pc.php?op=alta",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:"pc_id="+id
    })
    .then(res=>res.text())
    .then(res=>{
        if(res.trim() === "OK"){
            cargarComputadoras();
        }
    });
}

document.addEventListener("change", function(e){

    if(e.target.id === "cedis"){

        let cedis = e.target.value;

        fetch("empleados_pc.php?cedis=" + cedis)
        .then(res => res.text())
        .then(data => {

            let empleado = document.getElementById("empleado");

            if(empleado){
                empleado.innerHTML =
                "<option value=''>Seleccione empleado</option>" + data;
            }

        });

    }

});

// SETEAR CEDIS
let cedis = document.getElementById("cedis");
cedis.value = data.cedis;

// CARGAR EMPLEADOS DEL CEDIS
fetch("empleados_pc.php?cedis="+cedis)
.then(res => res.text())
.then(html => {

    let empleado = document.getElementById("empleado");

    empleado.innerHTML =
    "<option value=''>Seleccione empleado</option>" + html;

    empleado.value = data.usu_id;

});