@extends('supervisor.layouts.app')

@section('content')
<div class="dashboard-content">
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-link"></i> Blockchain de Auditoría</h1>
            <p>Registro inmutable de eventos de seguridad.</p>
        </div>
        <div class="header-actions">
            @if($integrity['status'])
                <span class="status-badge success" style="font-size: 1rem; padding: 10px;">
                    <i class="fas fa-shield-alt"></i> CADENA SEGURA
                </span>
            @else
                <span class="status-badge error" style="font-size: 1rem; padding: 10px; background: #e53e3e; color: white;">
                    <i class="fas fa-exclamation-triangle"></i> CADENA ROTA (Bloque #{{ $integrity['broken_block_id'] }})
                </span>
                <form action="{{ route('supervisor.blockchain.repair') }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn-primary">Reparar Cadena</button>
                </form>
            @endif
        </div>
    </div>

    @if(session('error'))
        <div style="background: #fed7d7; color: #c53030; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif
    
    @if(session('success'))
        <div style="background: #c6f6d5; color: #2f855a; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="blockchain-container">
        @foreach($blocks as $block)
            <div class="block-card">
                <div class="block-header">
                    <span class="block-id">Bloque #{{ $block->id }}</span>
                    <span class="block-time">{{ $block->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="block-body">
                    <div class="hash-row">
                        <small>Hash Previo:</small>
                        <code class="hash-text">{{ substr($block->previous_hash, 0, 20) }}...</code>
                    </div>
                    
                    <div class="data-box">
                        <strong>{{ $block->tipo_evento }}</strong>
                        <pre>{{ Str::limit($block->data, 50) }}</pre>
                    </div>

                    <div class="hash-row highlight">
                        <small>Hash Actual:</small>
                        <code class="hash-text">{{ substr($block->hash, 0, 20) }}...</code>
                    </div>
                </div>

                <div class="block-footer">
                     <form action="{{ route('supervisor.blockchain.hack', $block->id) }}" method="POST">
                        @csrf
                        <button class="btn-hack" title="Simular alteración de datos en DB">
                            <i class="fas fa-bug"></i> Alterar
                        </button>
                    </form>
                </div>
            </div>
            
            @if(!$loop->last)
                <div class="chain-link"><i class="fas fa-link"></i></div>
            @endif
        @endforeach
    </div>
</div>

<style>
    .blockchain-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .block-card {
        background: white;
        width: 100%;
        max-width: 600px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-left: 5px solid #004F6E;
        overflow: hidden;
    }
    .block-header {
        background: #f7fafc;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #eee;
        font-weight: bold;
        color: #2d3748;
    }
    .block-body { padding: 15px; }
    .hash-row { display: flex; justify-content: space-between; font-size: 0.8rem; color: #718096; margin-bottom: 5px; }
    .hash-text { font-family: monospace; background: #edf2f7; padding: 2px 5px; border-radius: 4px; color: #4a5568; }
    .data-box { background: #2d3748; color: #00ff00; padding: 10px; border-radius: 6px; font-family: monospace; font-size: 0.8rem; margin: 10px 0; }
    .highlight .hash-text { color: #38a169; font-weight: bold; }
    .chain-link { font-size: 1.5rem; color: #cbd5e0; }
    
    .block-footer {
        padding: 10px;
        background: #fff5f5;
        text-align: right;
        border-top: 1px solid #eee;
    }
    .btn-hack {
        background: #e53e3e;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.7rem;
    }
</style>
@endsection