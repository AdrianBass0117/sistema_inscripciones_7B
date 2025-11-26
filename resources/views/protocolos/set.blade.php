@extends('layouts.app') 

@section('content')
<div style="max-width: 800px; margin: 40px auto; padding: 20px; font-family: sans-serif;">
    <h2 style="color: #004F6E; text-align: center;">Simulación de Protocolo SET</h2>
    <p style="text-align: center;">Secure Electronic Transaction - Integridad de Pagos</p>

    <div style="display: flex; gap: 20px; margin-top: 30px;">
        <div style="flex: 1; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
            <h3>1. Datos de la Transacción</h3>
            <form id="setForm">
                <div style="margin-bottom: 15px;">
                    <label>Titular de la Tarjeta:</label><br>
                    <input type="text" name="titular" value="Juan Perez" style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Monto a Pagar:</label><br>
                    <input type="number" name="monto" value="1500.00" style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Número de Tarjeta:</label><br>
                    <input type="text" name="tarjeta" placeholder="Ingresa tarjeta ficticia" value="4500 1234 5678 9012" style="width: 100%; padding: 8px;">
                </div>
                <button type="submit" style="width: 100%; padding: 10px; background: #00AA8B; color: white; border: none; cursor: pointer;">
                    Iniciar Transacción SET
                </button>
            </form>
        </div>

        <div style="flex: 1; padding: 20px; background: #f0f4f8; border-radius: 8px;">
            <h3>2. Generación de Firma Digital</h3>
            <p style="font-size: 0.9rem; color: #666;">SET genera un hash único (Dual Signature) para garantizar que nadie alteró los datos, incluida la tarjeta.</p>
            
            <div id="resultado" style="display: none;">
                <p><strong>Datos Originales (JSON):</strong></p>
                <code id="jsonOriginal" style="display: block; background: #333; color: #0f0; padding: 10px; font-size: 0.8rem; white-space: pre-wrap;"></code>
                
                <p><strong>Firma Digital (HMAC-SHA256):</strong></p>
                <div id="firma" style="word-break: break-all; background: #e3f2fd; padding: 10px; border-left: 4px solid #2196F3; font-family: monospace;"></div>
                
                <div style="margin-top: 20px; text-align: center; color: green;">
                    <i class="fas fa-check-circle"></i> Integridad Verificada
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('setForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    // CAMBIO: Ahora tomamos la tarjeta del formulario
    const datos = {
        titular: formData.get('titular'),
        monto: formData.get('monto'),
        tarjeta: formData.get('tarjeta'), // <-- Valor dinámico
        timestamp: new Date().toISOString()
    };

    // Enviar a Laravel para que lo firme
    fetch('/proto/set-generar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('resultado').style.display = 'block';
        document.getElementById('jsonOriginal').innerText = JSON.stringify(datos, null, 2);
        document.getElementById('firma').innerText = data.firma;
    });
});
</script>
@endsection