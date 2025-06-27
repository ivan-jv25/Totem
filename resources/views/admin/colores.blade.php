@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Colores</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('colores.update') }}" method="POST">
                        @csrf

                        @foreach($colores as $nombre => $valor)
                        <div class="mb-4 row align-items-center">
                            <label for="{{ $nombre }}" class="form-label col-md-2 text-capitalize">{{ ucfirst($nombre) }}</label>
                            <div class="col-md-2">
                                <input type="color" id="{{ $nombre }}" name="{{ $nombre }}" value="{{ $valor }}" class="form-control form-control-color" style="width: 60px; height: 40px;">
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="{{ $nombre }}-hex" value="{{ $valor }}" maxlength="7" class="form-control" style="width: 100px;" pattern="^#([A-Fa-f0-9]{6})$" title="Código hexadecimal válido (#RRGGBB)">
                            </div>
                            <div class="col-md-2">
                                <span id="{{ $nombre }}-preview" style="display:inline-block;width:100px;height:40px;vertical-align:middle;background:{{ $valor }};border-radius:6px;border:1px solid #ccc;margin-left:10px;"></span>
                            </div>
                        </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary mt-3">Actualizar colores</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input[type=color]').forEach(function(input) {
        let name = input.id;
        let hexInput = document.getElementById(name + '-hex');
        let preview = document.getElementById(name + '-preview');

        // Cambia el color al escribir en el input color
        input.addEventListener('input', function() {
            hexInput.value = input.value;
            preview.style.background = input.value;
        });

        // Cambia el color al escribir en el input texto
        hexInput.addEventListener('input', function() {
            if(/^#([A-Fa-f0-9]{6})$/.test(hexInput.value)) {
                input.value = hexInput.value;
                preview.style.background = hexInput.value;
            }
        });
    });
</script>
@endsection