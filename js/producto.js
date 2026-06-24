// obtener token guardado despues del login
const token = localStorage.getItem("token");


// verificar si existe token
if(!token){

    Swal.fire({

        icon:'warning',

        title:'sesión expirada',

        text:'debe iniciar sesión'

    }).then(()=>{

        window.location.href="../views/login.html";

    });

}

// ejecutar accion segun el boton presionado
function ejecutar(accion){

    switch(accion){

        case "guardar":
            guardarProducto();
        break;

        case "buscar":
            buscarProducto();
        break;

        case "modificar":
            modificarProducto();
        break;

        case "eliminar":
            eliminarProducto();
        break;

        default:

            Swal.fire({

                icon:'warning',

                title:'Aviso',

                text:'Acción no válida'

            });

    }

}


// guardar producto
async function guardarProducto(){

    const datos = {
        codigo: document.getElementById("codigo").value,
        producto: document.getElementById("producto").value,
        precio: document.getElementById("precio").value,
        cantidad: document.getElementById("cantidad").value
    };


    try{

        if(datos.precio < 0 || datos.cantidad < 0){
            Swal.fire({
                icon:'warning',
                title:'Dato inválido',
                text:'El precio y la cantidad no pueden ser negativos'
            });
            return;
        }

        const respuesta = await fetch(
            "http://localhost/JWT_API_MAIN/api/products.php",
            {
                method:"POST",
                headers:{
                    "Authorization":"Bearer "+token,
                    "Content-Type":"application/json"
                },
                body:JSON.stringify(datos)
            }
        );

        

        const resultado = await respuesta.json();
        if(respuesta.ok){
            Swal.fire({
                icon:'success',
                title:'Correcto',
                text:resultado.message
            });
            limpiarFormulario();
        }

        else{
            Swal.fire({
                icon:'error',
                title:'Error',
                text:resultado.message
            });
        }
    }

    catch(error){
        Swal.fire({
            icon:'error',
            title:'Error',
            text:'No se pudo conectar con la API'
        });
    }
}



// buscar producto por codigo
async function buscarProducto(){
    const codigo = document.getElementById("codigo").value;
    if(codigo==""){
        Swal.fire({
            icon:'warning',
            title:'Campo vacío',
            text:'Ingrese el código del producto'
        });
        return;

    }


    try{
        const respuesta = await fetch(
            "http://localhost/JWT_API_MAIN/api/products.php?codigo="+codigo,
            {
                method:"GET",
                headers:{
                    "Authorization":"Bearer "+token
                }
            }
        );


        const resultado = await respuesta.json();


        if(resultado.success){
            document.getElementById("producto").value = resultado.data.producto;
            document.getElementById("precio").value = resultado.data.precio;
            document.getElementById("cantidad").value = resultado.data.cantidad;
            Swal.fire({
                icon:'success',
                title:'Producto encontrado'
            });
        }

        else{
            Swal.fire({
                icon:'error',
                title:'No encontrado',
                text:resultado.message

            });

        }

    }

    catch(error){
        Swal.fire({
            icon:'error',
            title:'Error',
            text:'No se pudo conectar con la API'
        });
    }
}



// modificar producto
async function modificarProducto(){
    const datos = {
        codigo: document.getElementById("codigo").value,
        producto: document.getElementById("producto").value,
        precio: document.getElementById("precio").value,
        cantidad: document.getElementById("cantidad").value
    };


    try{

        if(datos.precio < 0 || datos.cantidad < 0){
            Swal.fire({
                icon:'warning',
                title:'Dato inválido',
                text:'El precio y la cantidad no pueden ser negativos'
            });
            return;
        }

        const respuesta = await fetch(
            "http://localhost/JWT_API_MAIN/api/products.php",
            {
                method:"PUT",
                headers:{
                    "Authorization":"Bearer "+token,
                    "Content-Type":"application/json"
                },
                body:JSON.stringify(datos)
            }
        );


        const resultado = await respuesta.json();


        if(respuesta.ok){
            Swal.fire({
                icon:'success',
                title:'Correcto',
                text:resultado.message
            });
            limpiarFormulario();
        }
        else{
            Swal.fire({
                icon:'error',
                title:'Error',
                text:resultado.message
            });
        }
    }

    catch(error){
        Swal.fire({
            icon:'error',
            title:'Error',
            text:'No se pudo conectar con la API'
        });
    }
}



// eliminar producto
async function eliminarProducto(){
    const codigo = document.getElementById("codigo").value;


    if(codigo==""){
        Swal.fire({
            icon:'warning',
            title:'Campo vacío',
            text:'Ingrese el código del producto'
        });
        return;

    }


    const confirmar = await Swal.fire({
        title:'¿Desea eliminar este producto?',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Sí, eliminar',
        cancelButtonText:'Cancelar'
    });


    if(!confirmar.isConfirmed){
        return;
    }


    try{
        const respuesta = await fetch(
            "http://localhost/JWT_API_MAIN/api/products.php",
            {
                method:"DELETE",
                headers:{
                    "Authorization":"Bearer "+token,
                    "Content-Type":"application/json"
                },
                
                body:JSON.stringify({
                    codigo:codigo
                })
            }
        );


        const resultado = await respuesta.json();


        if(respuesta.ok){
            Swal.fire({
                icon:'success',
                title:'Eliminado',
                text:resultado.message
            });

            limpiarFormulario();

        }

        else{
            Swal.fire({
                icon:'error',
                title:'Error',
                text:resultado.message
            });

        }

    }

    catch(error){

        Swal.fire({
            icon:'error',
            title:'Error',
            text:'No se pudo conectar con la API'
        });

    }

}



// limpiar formulario
function limpiarFormulario(){

    document.getElementById("codigo").value = "";
    document.getElementById("producto").value = "";
    document.getElementById("precio").value = "";
    document.getElementById("cantidad").value = "";

}

// cerrar sesion y eliminar token
function cerrarSesion(){
    localStorage.removeItem("token");
    window.location.href="../views/login.html";

}