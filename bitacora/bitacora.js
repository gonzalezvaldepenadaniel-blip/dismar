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

<td class="text-center">
<div class="dropdown">
  <button class="btn btn-light btn-sm border-0 shadow-none" 
          type="button" 
          data-bs-toggle="dropdown">
    <i class="bi bi-three-dots-vertical"></i>
  </button>

  <ul class="dropdown-menu dropdown-menu-end shadow-sm">

    <li>
      <a class="dropdown-item" href="#" onclick="editar(${t.tel_id})">
        ✏ Editar
      </a>
    </li>

    <li><hr class="dropdown-divider"></li>

    ${
      t.estatus === 'ACTIVO'
      ? `
      <li>
        <a class="dropdown-item text-danger" href="#" onclick="baja(${t.tel_id})">
          ⛔ Dar de Baja
        </a>
      </li>
      `
      : `
      <li>
        <a class="dropdown-item text-success" href="#" onclick="alta(${t.tel_id})">
          ✔ Dar de Alta
        </a>
      </li>
      `
    }

    <li>
      <a class="dropdown-item text-warning" href="#" onclick="reparacion(${t.tel_id})">
        🔧 Reparación
      </a>
    </li>

  </ul>
</div>
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

console.log("RESPUESTA DEL SERVIDOR:", res);

if(res.trim() === "OK"){

    cerrarModal();
cargarTelefonos();

let cedisSeleccionado = document.getElementById("cedis").value;

if(cedisSeleccionado){
    fetch("empleados_cedis.php?cedis="+cedisSeleccionado)
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("empleado").innerHTML =
        "<option value=''>Seleccione empleado</option>"+data;
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

// volver a cargar empleados del mismo cedis
let cedisSeleccionado = document.getElementById("cedis").value;

if(cedisSeleccionado){
    fetch("empleados_cedis.php?cedis="+cedisSeleccionado)
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("empleado").innerHTML =
        "<option value=''>Seleccione empleado</option>"+data;
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