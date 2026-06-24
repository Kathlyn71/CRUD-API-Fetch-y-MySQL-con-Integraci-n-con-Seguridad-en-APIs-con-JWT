// funcion para iniciar sesion
async function iniciarSesion(){

    // crear objeto formdata para enviar los datos
    const formData = new FormData();

    // agregar el usuario ingresado
    formData.append(
        "usuario",
        document.getElementById("usuario").value

    );

    // agregar la clave ingresada
    formData.append(

        "clave",
        document.getElementById("clave").value

    );

    try{

        // enviar los datos al archivo login.php mediante post
        const respuesta = await fetch(

            "http://localhost/JWT_API_MAIN/login.php",

            {
                method:"POST",
                body:formData

            }

        );

        // convertir la respuesta a formato json
        const resultado = await respuesta.json();

        // verificar si el inicio de sesion fue exitoso
        if(resultado.success){

            // guardar el token en localstorage
            localStorage.setItem(
                "token",
                resultado.token

            );

            // mostrar mensaje de bienvenida
            Swal.fire({
                icon:"success",
                title:"Bienvenido",
                text:resultado.message

            }).then(()=>{

                // redirigir a la pagina de productos
                window.location.href="productos.html";

            });

        }

        else{

            // mostrar mensaje de error si las credenciales son incorrectas
            Swal.fire({
                icon:"error",
                title:"Error",
                text:resultado.message
            });

        }

    }

    catch(error){

        // mostrar mensaje si ocurre un error de conexion
        Swal.fire({
            icon:"error",
            title:"Error",
            text:"No se pudo conectar"
        });

    }

}