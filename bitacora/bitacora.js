document.addEventListener("DOMContentLoaded", () => {

// 👇 AGREGA ESTO AQUÍ
    let btnCerrarHistorial = document.getElementById("cerrarHistorial");
    let modalHistorial = document.getElementById("modalHistorial");

    if(btnCerrarHistorial){
        btnCerrarHistorial.onclick = () => {
            modalHistorial.style.display = "none";
        };
    }

    if(modalHistorial){
        modalHistorial.addEventListener("click", function(e){
            if(e.target === this){
                this.style.display = "none";
            }
        });
    }

cargarTelefonos();

let btnNuevo = document.getElementById("btnNuevo");
if(btnNuevo){
btnNuevo.addEventListener("click", abrirModal);
}

let btnCerrar = document.getElementById("btnCerrar");
if(btnCerrar){
btnCerrar.addEventListener("click", cerrarModal);
}

let form = document.getElementById("formTelefono");
form.addEventListener("submit", guardarTelefono);
/* PREVIEW AUTOMATICA DE IMAGEN */

let front = document.querySelector("input[name='front']");
let back = document.querySelector("input[name='back']");

if(front){
front.addEventListener("change", function(){
let file = this.files[0];
if(file){
document.getElementById("previewFront").src = URL.createObjectURL(file);
}
});
}

if(back){
back.addEventListener("change", function(){
let file = this.files[0];
if(file){
document.getElementById("previewBack").src = URL.createObjectURL(file);
}
});
}

});

function abrirModal(){

document.getElementById("modalTelefono").style.display="flex";

/* LIMPIAR PREVIEWS */

let pf = document.getElementById("previewFront");
let pb = document.getElementById("previewBack");

if(pf) pf.src="";
if(pb) pb.src="";

}

function cerrarModal(){

document.getElementById("modalTelefono").style.display="none";

let form = document.getElementById("formTelefono");
if(form) form.reset();

let empleado = document.getElementById("empleado");
if(empleado){
empleado.innerHTML="<option value=''>Seleccione empleado</option>";
}

}

function cargarTelefonos(){

fetch("controller.php?op=listar")

.then(res=>res.json())

.then(data=>{

const tbody=document.querySelector("#tablaTelefonos tbody");

tbody.innerHTML="";



let grupos = {};

// AGRUPAR POR CEDIS
data.forEach(t => {
    let cedis = t.cedis || "SIN CEDIS";

    if(!grupos[cedis]){
        grupos[cedis] = [];
    }

    grupos[cedis].push(t);
});


// LIMPIAR TABLA
tbody.innerHTML = "";

// RECORRER GRUPOS
for(let cedis in grupos){

    let total = grupos[cedis].length;

    // HEADER DEL GRUPO
    tbody.innerHTML += `
    <tr class="grupo-cedis">
        <td colspan="15">
            <i class="bi bi-building"></i> ${cedis} 
            <span class="contador">(${total} equipos)</span>
        </td>
    </tr>
    `;

    // 👇 SOLO LOS DE ESTE CEDIS
   grupos[cedis].forEach(t => {

    tbody.innerHTML += `
    <tr class="fila-cedis oculta">




            <td>${t.marca}</td>
            <td>${t.imei}</td>
            <td>${t.modelo}</td>
            <td>${t.num_serie}</td>
            <td>${t.num_telefono}</td>
            <td>${t.puesto ?? ''}</td>
            <td>${t.area ?? ''}</td>
            <td>${t.nombre ?? ''} ${t.apellidop ?? ''} ${t.apellidom ?? ''}</td>
            <td>${t.cedis ?? ''}</td>

          <td>
${t.front ? `<img src="/Dismar/public/telefonos/${t.front}" class="img-preview">` : ''}
</td>

<td>
${t.back ? `<img src="/Dismar/public/telefonos/${t.back}" class="img-preview">` : ''}
</td>

<td>${t.folio}</td>

<td>${t.comentarios}</td>

<td>
${t.responsiva 
? `<a href="/Dismar/public/responsivas/${t.responsiva}" target="_blank" class="btn btn-sm btn-danger">
<i class="bi bi-file-earmark-pdf"></i>
</a>` 
: ''}
</td>

            <td>
            <span class="${
            t.estatus === 'ACTIVO'
            ? 'badge-activo'
            : t.estatus === 'BAJA'
            ? 'badge-baja'
            : 'badge-reparacion'
            }">
            ${t.estatus}
            </span>
            </td>

            <td class="text-center acciones-col">
            <button class="btn btn-acciones"
            onclick="abrirAcciones(${t.tel_id})">
            <i class="bi bi-three-dots-vertical"></i>
            </button>
            </td>
        </tr>
        `;
    });
}

// ✅ AQUÍ VA (UNA SOLA VEZ)
ocultarColumnasExtra();

});

}
/* FILTRAR EMPLEADOS POR CEDIS */

document.addEventListener("change",function(e){

if(e.target.id==="cedis"){

let cedis=e.target.value;

fetch("empleados_cedis.php?cedis="+cedis)

.then(res=>res.text())

.then(data=>{

let empleado = document.getElementById("empleado");

if(empleado){
empleado.innerHTML=
"<option value=''>Seleccione empleado</option>"+data;
}

});

}

});

function guardarTelefono(e){

e.preventDefault();

let formData = new FormData(document.getElementById("formTelefono"));

let tel_id = document.getElementById("tel_id").value;

let url = "controller.php?op=guardar";

if(tel_id){
url = "controller.php?op=editar";
}

fetch(url,{
method:"POST",
body:formData
})

.then(res=>res.text())

.then(res=>{

console.log("RESPUESTA DEL SERVIDOR:", res);

if(res.trim() === "OK"){

cerrarModal();

cargarTelefonos();

let cedis = document.getElementById("cedis");
let cedisSeleccionado = "";

if(cedis){
cedisSeleccionado = cedis.value;
}

if(cedisSeleccionado){

fetch("empleados_cedis.php?cedis="+cedisSeleccionado)

.then(res=>res.text())

.then(data=>{

let empleado = document.getElementById("empleado");

if(empleado){
empleado.innerHTML=
"<option value=''>Seleccione empleado</option>"+data;
}

});

}

Swal.fire({
icon:"success",
title:"Teléfono guardado correctamente",
showConfirmButton:false,
timer:1500
});

}

else{

Swal.fire({
icon:"error",
title:"Error al guardar"
});

}

});

}

/* DAR DE BAJA */

function baja(id){

Swal.fire({

title: '¿Dar de baja el teléfono?',
icon: 'warning',
showCancelButton: true,
confirmButtonText: 'Sí, dar baja',
cancelButtonText: 'Cancelar'

}).then((result)=>{

if(result.isConfirmed){

fetch("controller.php?op=baja",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"tel_id="+id

})

.then(res=>res.text())

.then(res=>{

if(res.trim() === "OK"){

Swal.fire({
icon:"success",
title:"Teléfono dado de baja",
timer:1500,
showConfirmButton:false
});

cargarTelefonos();

}

});

}

});

}

/* DAR DE ALTA */

function alta(id){

Swal.fire({

title: '¿Dar de alta el teléfono?',
icon: 'question',
showCancelButton: true,
confirmButtonText: 'Sí, dar alta',
cancelButtonText: 'Cancelar'

}).then((result)=>{

if(result.isConfirmed){

fetch("controller.php?op=alta",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"tel_id="+id

})

.then(res=>res.text())

.then(res=>{

if(res.trim() === "OK"){

Swal.fire({
icon:"success",
title:"Teléfono activado",
timer:1500,
showConfirmButton:false
});

cargarTelefonos();

}

});

}

});

}



/* EDITAR */

function editar(id){

fetch("controller.php?op=obtener&tel_id="+id)

.then(res=>res.json())

.then(data=>{

abrirModal();

document.getElementById("tel_id").value = data.tel_id;

document.querySelector("[name='marca']").value = data.marca;
document.querySelector("[name='modelo']").value = data.modelo;
document.querySelector("[name='num_serie']").value = data.num_serie;
document.querySelector("[name='num_telefono']").value = data.num_telefono;
document.querySelector("[name='imei']").value = data.imei;
document.querySelector("[name='folio']").value = data.folio;
document.querySelector("[name='comentarios']").value = data.comentarios;
document.getElementById("front_actual").value = data.front ?? "";
document.getElementById("back_actual").value  = data.back ?? "";

let cedis = document.getElementById("cedis");
cedis.value = data.cedis;

fetch("empleados_cedis.php?cedis="+data.cedis+"&tel_id="+data.tel_id)

.then(res=>res.text())

.then(html=>{

let empleado = document.getElementById("empleado");

empleado.innerHTML =
"<option value=''>Seleccione empleado</option>"+html;

empleado.value = data.usu_id;

});

/* MOSTRAR IMAGENES */

if(data.front){
document.getElementById("previewFront").src =
"/Dismar/public/telefonos/"+data.front;
}

if(data.back){
document.getElementById("previewBack").src =
"/Dismar/public/telefonos/"+data.back;
}
document.getElementById("front_actual").value = data.front ?? "";
document.getElementById("back_actual").value  = data.back ?? "";

});

}

let telefonoSeleccionado = null;

function abrirAcciones(id){

telefonoSeleccionado = id;

document.getElementById("modalAcciones").style.display = "flex";

}

document.getElementById("cerrarAcciones").onclick = () => {

document.getElementById("modalAcciones").style.display = "none";

};

document.querySelector(".accion-btn.editar").onclick = () => {
editar(telefonoSeleccionado);
cerrarAcciones();
};

document.querySelector(".accion-btn.alta").onclick = () => {
alta(telefonoSeleccionado);
cerrarAcciones();
};

document.querySelector(".accion-btn.baja").onclick = () => {
baja(telefonoSeleccionado);
cerrarAcciones();
};

document.querySelector(".accion-btn.reparacion").onclick = () => {
reparacion(telefonoSeleccionado);
cerrarAcciones();
};

document.querySelector(".accion-btn.historial").onclick = () => {
verHistorial(telefonoSeleccionado);
cerrarAcciones();
};

function cerrarAcciones(){
document.getElementById("modalAcciones").style.display="none";
}

document.getElementById("modalAcciones").addEventListener("click",function(e){
if(e.target === this){
cerrarAcciones();
}
});

/* VISOR DE IMAGEN */

document.addEventListener("click", function(e){

if(e.target.classList.contains("img-preview")){

let visor = document.getElementById("visorImagen");
let imgGrande = document.getElementById("imgGrande");

imgGrande.src = e.target.src;
visor.style.display = "flex";

}

});

document.querySelector(".cerrar-img").onclick = () => {
document.getElementById("visorImagen").style.display="none";
};

document.getElementById("visorImagen").onclick = function(e){
if(e.target === this){
this.style.display="none";
}
};


// ENVIAR A REPARACION 
function reparacion(id){ Swal.fire({ title: '¿Enviar a reparación?',
     icon: 'warning', showCancelButton: true, confirmButtonText: 
     'Sí, enviar', cancelButtonText: 'Cancelar' }).then((result)=>{ if(result.isConfirmed)
        { fetch("controller.php?op=reparacion",{ method:"POST", headers:
            { "Content-Type":"application/x-www-form-urlencoded" }, body:"tel_id="+id }) 
            .then(res=>res.text()) .then(res=>{ if(res.trim() === "OK"){ Swal.fire(
                { icon:"success", title:"Enviado a reparación", timer:1500, showConfirmButton:false
                    
                 }); cargarTelefonos(); } }); } }); }



                 function verDetalles(id){

fetch("controller.php?op=obtener&tel_id="+id)

.then(res=>res.json())

.then(t=>{

let html = `

<p><b>Folio:</b> ${t.folio}</p>
<p><b>Comentarios:</b> ${t.comentarios}</p>

<p><b>Estatus:</b> ${t.estatus}</p>

<div style="display:flex;gap:10px;margin-top:10px;">

${t.front ? `<img src="/Dismar/public/telefonos/${t.front}" width="120">` : ''}

${t.back ? `<img src="/Dismar/public/telefonos/${t.back}" width="120">` : ''}

</div>

`;

document.getElementById("contenidoDetalles").innerHTML = html;

document.getElementById("modalDetalles").style.display = "flex";

});

}

function cerrarDetalles(){
document.getElementById("modalDetalles").style.display="none";
}

// CONTROL DE COLUMNAS
let mostrando = false;

function ocultarColumnasExtra(){

    if(mostrando) return;

    // ocultar headers
    document.querySelectorAll("#tablaTelefonos thead th").forEach((th, i) => {
        if(i >= 9 && i <= 14){
            th.classList.add("oculta");
        }
    });

    // ocultar celdas
    document.querySelectorAll("#tablaTelefonos tbody tr").forEach(row => {
        for(let i = 9; i <= 14; i++){
            if(row.children[i]){
                row.children[i].classList.add("oculta");
            }
        }
    });

}

// BOTON MOSTRAR MÁS
document.addEventListener("click", function(e){

if(e.target.closest("#btnMostrarMas")){

    mostrando = !mostrando;

    // headers
    document.querySelectorAll("#tablaTelefonos thead th").forEach((th, i) => {
        if(i >= 9 && i <= 14){
            th.classList.toggle("oculta");
        }
    });

    // filas
    document.querySelectorAll("#tablaTelefonos tbody tr").forEach(row => {
        for(let i = 9; i <= 14; i++){
            if(row.children[i]){
                row.children[i].classList.toggle("oculta");
            }
        }
    });

    document.getElementById("btnMostrarMas").innerHTML =
        mostrando
        ? '<i class="bi bi-eye-slash"></i> Mostrar menos'
        : '<i class="bi bi-eye"></i> Mostrar más';

}

});


document.addEventListener("click", function(e){

if(e.target.closest(".grupo-cedis")){

    let fila = e.target.closest("tr");
    let siguiente = fila.nextElementSibling;

    while(siguiente && !siguiente.classList.contains("grupo-cedis")){

        siguiente.classList.toggle("oculta");
        siguiente = siguiente.nextElementSibling;
    }

}

});

function hayFilasVisibles(){
    return document.querySelectorAll(".fila-cedis:not(.oculta)").length > 0;
}

/* VISOR DE PDF */

document.addEventListener("click", function(e){

if(e.target.closest(".ver-pdf")){

let pdf = e.target.closest(".ver-pdf").dataset.pdf;

let visor = document.getElementById("visorPDF");
let frame = document.getElementById("pdfGrande");

frame.src = "/Dismar/public/responsivas/" + pdf;

visor.style.display = "flex";

}

});

document.querySelector(".cerrar-pdf").onclick = () => {
document.getElementById("visorPDF").style.display="none";
};

document.getElementById("visorPDF").onclick = function(e){
if(e.target === this){
this.style.display="none";
}
};



function verHistorial(tel_id){

fetch("controller.php?op=historial&tel_id="+tel_id)
.then(res=>res.json())
.then(data=>{

let html = `
<div class="d-flex justify-content-between mb-2">
    <button class="btn btn-primary btn-sm" onclick="abrirAgregarComentario(${tel_id})">
        <i class="bi bi-plus"></i> Agregar comentario
    </button>
</div>

<div class="timeline">
`;

data.forEach(item => {

let usuario = item.nombre+" "+item.apellidop+" "+item.apellidom;

html += `
<div class="timeline-item">

<div class="timeline-dot"></div>

<div class="timeline-content">

<div class="timeline-fecha">
Asignado: ${item.fecha_asignacion}
</div>

<div class="timeline-text">
Usuario: ${usuario}
</div>

<div class="timeline-text">
📝 ${item.comentario ? item.comentario : 'Sin comentario'}
</div>
`;

if(item.fecha_cambio){
html += `
<div class="timeline-fecha">
Cambio: ${item.fecha_cambio}
</div>
`;
}

html += `
</div>
</div>
`;
});

html += `</div>`;

document.getElementById("contenidoHistorial").innerHTML = html;
document.getElementById("modalHistorial").style.display="flex";

});

}





// CERRAR MODAL HISTORIAL (BOTÓN)
document.getElementById("cerrarHistorial").onclick = () => {
    document.getElementById("modalHistorial").style.display = "none";
};

// CERRAR AL HACER CLICK FUERA
document.getElementById("modalHistorial").addEventListener("click", function(e){
    if(e.target === this){
        this.style.display = "none";
    }
});

// COMENTARIO HISTORIAL
function abrirAgregarComentario(tel_id){

    telefonoPendiente = tel_id;

    document.getElementById("modalComentario").style.display = "flex";
    document.getElementById("txtComentario").value = "";

    fetch("controller.php?op=empleados_historial&tel_id=" + tel_id)
    .then(res => res.json())
    .then(data => {

        let select = document.getElementById("empleadoComentario");
        select.innerHTML = "<option value=''>Seleccione empleado</option>";

        data.forEach(emp => {
            select.innerHTML += `
                <option value="${emp.usu_id}">
                    ${emp.nombre} ${emp.apellidop} ${emp.apellidom}
                </option>
            `;
        });
    });

    document.getElementById("guardarComentario").onclick = () => {

        let comentario = document.getElementById("txtComentario").value.trim();
        let usu_id = document.getElementById("empleadoComentario").value;

        if(!usu_id){
            alert("Selecciona un empleado");
            return;
        }

        if(!comentario){
            alert("Escribe un comentario");
            return;
        }

        fetch("controller.php?op=comentario", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `tel_id=${telefonoPendiente}&comentario=${encodeURIComponent(comentario)}&usu_id=${encodeURIComponent(usu_id)}`
        })
        .then(res => res.text())
        .then(res => {

            console.log("RESPUESTA:", res);

            if(res.trim() === "OK"){
                document.getElementById("modalComentario").style.display = "none";
                document.getElementById("txtComentario").value = "";
                document.getElementById("empleadoComentario").value = "";

                verHistorial(telefonoPendiente);
            } else {
                alert("Error: " + res);
            }
        });
    };
}