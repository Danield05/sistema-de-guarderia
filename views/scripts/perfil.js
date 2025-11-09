// Función para inicializar el perfil
function init() {
    mostrarPerfil();
    $("#formularioPerfil").on("submit", function(e) {
        guardaryeditarPerfil(e);
    });

    // Configurar el selector de imagen
    $("#imagen").change(function() {
        readURL(this);
    });

    // Configurar el label personalizado
    $(".custom-file-label").on("click", function() {
        $("#imagen").click();
    });
}

// Función para mostrar los datos del perfil del usuario logueado
function mostrarPerfil() {
    $.post("../ajax/usuario.php?op=mostrar_perfil", function(data, status) {
        data = JSON.parse(data);

        $("#idusuario").val(data.id_usuario);
        $("#nombre").val(data.nombre_completo);
        $("#dui").val(data.dui);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#rol").val(data.rol);
        $("#imagenactual").val(data.fotografia);

        // Mostrar información en la tarjeta lateral
        $("#nombrePerfil").text(data.nombre_completo);
        $("#rolPerfil").text(data.rol);

        // Mostrar imagen de perfil
        if (data.fotografia) {
            $("#imagenmuestra").html('<img src="../files/usuarios/' + data.fotografia + '" style="width: 100%; height: 100%; object-fit: cover;">');
            $("#imagenPerfil").html('<img src="../files/usuarios/' + data.fotografia + '" style="width: 100%; height: 100%; object-fit: cover;">');
            $("#navbarUserImage").html('<img src="../files/usuarios/' + data.fotografia + '" style="width: 100%; height: 100%; object-fit: cover;">');
        } else {
            $("#imagenmuestra").html('<i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>');
            $("#imagenPerfil").html('<i class="fas fa-user" style="font-size: 2rem; color: #28a745;"></i>');
            $("#navbarUserImage").html('<i class="fas fa-user" style="font-size: 1.2rem; color: #666;"></i>');
        }
    });
}

// Función para guardar o editar el perfil
function guardaryeditarPerfil(e) {
    e.preventDefault(); // No se activará la acción predeterminada del evento

    var formData = new FormData($("#formularioPerfil")[0]);

    // Validar contraseña actual si se proporciona una nueva
    if ($("#clave_nueva").val() !== "") {
        if ($("#clave_actual").val() === "") {
            bootbox.alert("Debes ingresar tu contraseña actual para cambiarla.");
            return;
        }
    }

    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar_perfil",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            bootbox.alert(datos);
            mostrarPerfil(); // Recargar los datos del perfil
            // Limpiar campos de contraseña
            $("#clave_actual").val("");
            $("#clave_nueva").val("");
        },
        error: function() {
            bootbox.alert("Error al actualizar el perfil");
        }
    });
}

// Función para leer la URL de la imagen seleccionada
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imagenmuestra').html('<img src="'+e.target.result+'" style="width: 100%; height: 100%; object-fit: cover;">');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar cuando el documento esté listo
$(document).ready(function() {
    init();

    // Actualizar imagen del navbar cuando se carga la página
    actualizarImagenNavbar();
});

// Función para actualizar la imagen del navbar desde cualquier página
function actualizarImagenNavbar() {
    $.post("../ajax/usuario.php?op=mostrar_perfil", function(data, status) {
        data = JSON.parse(data);

        // Mostrar imagen en el navbar
        if (data.fotografia) {
            $("#navbarUserImage").html('<img src="../files/usuarios/' + data.fotografia + '" style="width: 100%; height: 100%; object-fit: cover;">');
        } else {
            $("#navbarUserImage").html('<i class="fas fa-user" style="font-size: 1.2rem; color: #666;"></i>');
        }
    }).fail(function(xhr, status, error) {
        console.error("Error actualizando imagen del navbar:", error);
    });
}