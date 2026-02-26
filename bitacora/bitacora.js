document.addEventListener("DOMContentLoaded", () => {

    cargarTelefonos();

    document.getElementById("btnNuevo").addEventListener("click", abrirModal);

    document.getElementById("btnCerrar").addEventListener("click", cerrarModal);

    document.getElementById("formTelefono").addEventListener("submit", guardarTelefono);

});



function abrirModal(){

document.getElementById("modalTelefono").style.display="flex";

}



function cerrarModal(){

document.getElementById("modalTelefono").style.display="none";

document.getElementById("formTelefono").reset();

document.getElementById("empleado").innerHTML=
"<option value=''>Seleccione empleado</option>";

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

<td>${t.tel_id}</td>

<td>${t.marca}</td>

<td>${t.modelo}</td>

<td>${t.num_serie}</td>

<td>${t.num_telefono}</td>

<td>${t.puesto ?? ''}</td>

<td>${t.area ?? ''}</td>

<td>${t.nombre ?? ''} ${t.apellidop ?? ''} ${t.apellidom ?? ''}</td>

<td>${t.cedis ?? ''}</td>
<td>
<img src="/Dismar/public/telefonos/${t.front}" width="80">
</td>

<td>
<img src="/Dismar/public/telefonos/${t.back}" width="80">
</td>

<td>${t.folio}</td>

<td>${t.comentarios}</td>

<td>

<span class="
${
t.estatus === 'ACTIVO'
? 'badge-activo'
: t.estatus === 'BAJA'
? 'badge-baja'
: 'badge-reparacion'
}
">

${t.estatus}



</span>

</td>

<td>

${
t.estatus === 'ACTIVO'

? `<button class="btn btn-danger btn-sm" onclick="baja(${t.tel_id})">
Dar Baja
</button>`

: `<button class="btn btn-success btn-sm" onclick="alta(${t.tel_id})">
Dar Alta
</button>`

}

<button class="btn btn-warning btn-sm" onclick="reparacion(${t.tel_id})">
Reparación
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

document.getElementById("empleado").innerHTML=

"<option value=''>Seleccione empleado</option>"+data;

});

}

});


function guardarTelefono(e){

e.preventDefault();

let formData = new FormData(document.getElementById("formTelefono"));

fetch("controller.php?op=guardar",{

method:"POST",

body:formData

})
.then(res=>res.text())
.then(res=>{

if(res=="OK"){

// cerrar modal
cerrarModal();

// recargar tabla
cargarTelefonos();


// mostrar alerta
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

if(res=="OK"){

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

if(res=="OK"){

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

if(res=="OK"){

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