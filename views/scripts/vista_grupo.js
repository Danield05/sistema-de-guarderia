var tabla;

//funcion que se ejecuta al inicio
function init(){
   listar();
}

//funcion listar
function listar(){
    // Definir las columnas para la vista de grupo
    const columns = [
        { 
            title: 'Opciones',
            render: function(data, row, index) {
                return '<div class="action-buttons">' +
                    '<a href="asistencia.php?idgrupo=' + getUrlParameter('idgrupo') + '&idnino=' + row[0] + 
                    '" class="btn-action btn-edit">📋 Asistencia</a>' +
                    '</div>';
            }
        },
        { 
            title: 'Imagen',
            render: function(data, row, index) {
                return '<img src="../files/articulos/anonymous.png" class="img-circle" width="50px" height="50px">';
            }
        },
        { title: 'Nombre' },
        { title: 'Apellidos' },
        { title: 'Teléfono' },
        { title: 'Estado' }
    ];
    
    tabla = initCustomTable('tbllistado', {
        url: '../ajax/ninos.php?op=listar&idgrupo=' + getUrlParameter('idgrupo'),
        columns: columns,
        itemsPerPage: 15
    });
}

//función auxiliar para obtener parámetros URL
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

init();