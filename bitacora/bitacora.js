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