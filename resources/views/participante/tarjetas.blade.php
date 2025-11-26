@extends('participante.layouts.app')

@section('content')
<div class="dashboard-content">
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-shield-alt"></i> Billetera Segura (SET)</h1>
            <p>Gestión de métodos de pago bajo el protocolo Secure Electronic Transaction</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem;">
        
        <div style="background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="color: #004F6E; margin-bottom: 1.5rem; border-bottom: 2px solid #00AA8B; padding-bottom: 0.5rem;">
                <i class="fas fa-plus-circle"></i> Nueva Tarjeta
            </h3>
            
            <form action="{{ route('personal.tarjetas.store') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Titular de la Tarjeta</label>
                    <input type="text" name="titular" class="form-control" required placeholder="Como aparece en la tarjeta" style="width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Número de Tarjeta</label>
                    <input type="text" name="numero" class="form-control" required maxlength="16" placeholder="16 dígitos sin espacios" style="width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label>Expiración</label>
                        <input type="text" name="expiracion" placeholder="MM/AA" required style="width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>
                    <div>
                        <label>CVV</label>
                        <input type="password" name="cvv" maxlength="4" required style="width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: #004F6E; color: white; padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                    <i class="fas fa-lock"></i> Registrar y Firmar Digitalmente
                </button>
                <p style="font-size: 0.8rem; color: #666; margin-top: 10px; text-align: center;">
                    <i class="fas fa-info-circle"></i> Tus datos serán encriptados y se generará un certificado X.509 único.
                </p>
            </form>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @foreach($tarjetas as $tarjeta)
            <div style="background: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 5px solid #00AA8B;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h4 style="margin: 0; color: #2D3748;">{{ $tarjeta->nombre_titular }}</h4>
                    <span style="background: #e6fffa; color: #00AA8B; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                        SET VERIFIED
                    </span>
                </div>
                
                <p style="font-size: 1.2rem; letter-spacing: 2px; font-family: monospace; margin-bottom: 1rem;">
                    {{ $tarjeta->numero_enmascarado }}
                </p>

                <div style="background: #f7fafc; padding: 1rem; border-radius: 8px; font-size: 0.8rem;">
                    <p><strong><i class="fas fa-file-signature"></i> Firma Digital (HMAC):</strong></p>
                    <code style="display: block; word-break: break-all; color: #004F6E;">{{ $tarjeta->firma_digital_set }}</code>
                </div>
                
                <details style="margin-top: 10px; cursor: pointer;">
                    <summary style="color: #00AA8B; font-weight: 600;">Ver Certificado Digital X.509</summary>
                    <pre style="background: #2d3748; color: #48bb78; padding: 10px; border-radius: 8px; margin-top: 10px; font-size: 0.7rem; overflow-x: auto;">{{ $tarjeta->certificado_seguridad }}</pre>
                </details>
            </div>
            @endforeach
            
            @if($tarjetas->isEmpty())
            <div style="text-align: center; padding: 3rem; color: #a0aec0;">
                <i class="fas fa-wallet" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>No tienes tarjetas registradas bajo el protocolo SET.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif

@endsection