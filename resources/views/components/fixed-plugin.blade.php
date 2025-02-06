 <script src="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js"></script>
 <script>
     var botmanWidget = {
         frameEndpoint: '/botman/chat', // EndPoint del botman para procesar el chat
         introMessage: '¡Hola! ¿Cómo puedo ayudarte? Puedes decir: "crear usuario" o "mostrar inventarios"',
         placeholderText: 'Escribe un mensaje...',
         title: 'Mi Chatbot'
     };
 </script>

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Error de Validación',
                html: `
                    <ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
@endif
