@extends('layouts.user_type.auth')

@section('content')

<div class="container" style=" min-height: 75vh;display: flex; flex-direction: column;">
    <div class="row d-flex justify-content-center">
        <div class="col-sm-6 mb-3">
            <input type="text" id="myFilter" class="form-control" onkeyup="myFunction()" placeholder="Búsqueda por categoría">
        </div>
    </div>
    <div class="row" id="myItems">
        <div class="wrap d-flex flex-wrap justify-content-start">
            <div class="box one m-2" onclick="window.location.href='{{ route('hardwares.index') }}';">
                <h1>Todo</h1>
                <div class="poster">
                    <img src="https://img.freepik.com/vector-premium/componentes-hardware-pc-simbolos-items-computadora-procesador-servidor-ssd-o-hdd-memoria-ram-iconos-linea_80590-5893.jpg" alt="">
                </div>
            </div>
            @foreach($categories as $categoria)
            <div class="box one m-2" onclick="window.location.href='{{ route('hardwares.index', ['category_id' => $categoria->id]) }}';">
                <h1>{{$categoria->name}}</h1>
                <div class="poster">
                    <img src="{{$categoria->image}}" alt="">
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Contenedor para la paginación y el mensaje -->
    <div class="mt-auto">
        <div class="row justify-content-between align-items-center mt-3">
            <div>
                Mostrando registros del {{ $categories->firstItem() }} al {{ $categories->lastItem() }} de un total de {{ $categories->total() }} registros
            </div>
            @if($categories->total() > $categories->perPage())
            <div>
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<script>
    function myFunction() {
        var input, filter, boxes, boxContainer, h1, title, i;
        input = document.getElementById("myFilter");
        filter = input.value.toUpperCase();
        boxContainer = document.getElementById("myItems");
        boxes = boxContainer.getElementsByClassName("box");
        for (i = 0; i < boxes.length; i++) {
            title = boxes[i].querySelector("h1");
            // 
            if (title.innerText.toUpperCase().indexOf(filter) > -1) {
                
                boxes[i].style.display = "";
            } else {
                boxes[i].style.display = "none";
            }
        }
    }
</script>


@endsection