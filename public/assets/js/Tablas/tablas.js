function configureDataTable(selector, permissions) {
    var buttonsConfig = [];

   

    if (permissions.copy) {
        buttonsConfig.push({ extend: 'copy', text: 'Copiar' });
    }
    if (permissions.excel) {
        buttonsConfig.push({ extend: 'excel', text: 'Excel' });
    }
    if (permissions.csv) {
        buttonsConfig.push({ extend: 'csv', text: 'CSV' });
    }
    if (permissions.pdf) {
        buttonsConfig.push({ extend: 'pdf', text: 'PDF' });
    }
    if (permissions.print) {
        buttonsConfig.push({ extend: 'print', text: 'Imprimir' });
    }

    

    $(selector).DataTable({
        responsive: true,
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": '<i class="fas fa-angle-double-left"></i>',
                "sLast": '<i class="fas fa-angle-double-right"></i>',
                "sNext": '<i class="fas fa-angle-right"></i>',
                "sPrevious": '<i class="fas fa-angle-left"></i>'
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        },
        dom: '<"top"Bf>rt<"bottom"lip><"clear">',
        buttons: buttonsConfig
    });
}

$(document).ready(function() {
    // Obtener los permisos desde la variable global
    var permissions = window.permissions;

    // Configurar DataTables para las diferentes tablas
    configureDataTable('#Principal', permissions);
    configureDataTable('#restaurar', permissions);
});

$(".formulario-eliminar").submit(function (e) {
    e.preventDefault();

    Swal.fire({
        title: "¿Estas seguro?",
        text: "Este Usuario se eliminara definitivamente",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});

$(".formulario-restaurar").submit(function (e) {
    e.preventDefault();

    Swal.fire({
        title: "¿Estas seguro?",
        text: "Este usuario se reutaurara",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, restaurar!",
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
