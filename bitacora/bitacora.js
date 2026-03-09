document.addEventListener("DOMContentLoaded", () => {

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
    if(form){
        form.addEventListener("submit", guardarTelefono);
    }

});



function abrirModal(){
    document.getElementById("modalTelefono").style.display="flex";
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

data.forEach(t=>{

tbody.innerHTML+=`

<tr>

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
<img src="/Dismar/public/telefonos/${t.front}" class="img-preview">
</td>

<td>
<img src="/Dismar/public/telefonos/${t.back}" class="img-preview">
</td>

<td>${t.folio}</td>
<td>${t.comentarios}</td>

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




});

}


// FILTRAR EMPLEADOS POR CEDIS
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




// DAR DE BAJA
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




// DAR DE ALTA
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




// ENVIAR A REPARACION
function reparacion(id){

Swal.fire({

title: '¿Enviar a reparación?',
icon: 'warning',
showCancelButton: true,
confirmButtonText: 'Sí, enviar',
cancelButtonText: 'Cancelar'

}).then((result)=>{

if(result.isConfirmed){

fetch("controller.php?op=reparacion",{

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
title:"Enviado a reparación",
timer:1500,
showConfirmButton:false
});

cargarTelefonos();

}

});

}

});

}


function editar(id){

fetch("controller.php?op=obtener&tel_id="+id)

.then(res=>res.json())

.then(data=>{

abrirModal();

/* llenar formulario */

document.getElementById("tel_id").value = data.tel_id;

document.querySelector("[name='marca']").value = data.marca;
document.querySelector("[name='modelo']").value = data.modelo;
document.querySelector("[name='num_serie']").value = data.num_serie;
document.querySelector("[name='num_telefono']").value = data.num_telefono;
document.querySelector("[name='imei']").value = data.imei;
document.querySelector("[name='folio']").value = data.folio;
document.querySelector("[name='comentarios']").value = data.comentarios;

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

// BOTONES DEL MODAL EMPRESARIAL

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

function cerrarAcciones(){
    document.getElementById("modalAcciones").style.display="none";
}

document.getElementById("modalAcciones").addEventListener("click",function(e){
    if(e.target === this){
        cerrarAcciones();
    }
});

// ===== VISOR DE IMAGEN =====

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