<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockchainBlock extends Model
{
    use HasFactory;
    
    protected $table = 'blockchain_ledger';
    
    protected $fillable = ['data', 'previous_hash', 'hash', 'tipo_evento', 'created_at'];

    /**
     * Agrega un nuevo bloque a la cadena.
     */
    public static function addBlock($data, $tipo = 'General')
    {
        // 1. Obtener el último bloque
        $lastBlock = self::latest('id')->first();
        
        // 2. Definir el hash anterior (Si es el primero, usamos ceros - Bloque Génesis)
        $previousHash = $lastBlock ? $lastBlock->hash : '0000000000000000000000000000000000000000000000000000000000000000';
        
        // 3. Preparar datos
        $timestamp = now();
        $dataJson = json_encode($data);
        
        // 4. Calcular Hash: SHA256(HashAnterior + Timestamp + Datos)
        // Esto vincula criptográficamente este bloque con el anterior.
        $stringToHash = $previousHash . $timestamp . $dataJson . $tipo;
        $newHash = hash('sha256', $stringToHash);
        
        // 5. Guardar
        return self::create([
            'tipo_evento' => $tipo,
            'data' => $dataJson,
            'previous_hash' => $previousHash,
            'hash' => $newHash,
            'created_at' => $timestamp
        ]);
    }

    /**
     * Verifica si la cadena es válida o ha sido manipulada.
     */
    public static function checkIntegrity()
    {
        $blocks = self::orderBy('id', 'asc')->get();
        
        foreach ($blocks as $key => $block) {
            // Saltar bloque génesis
            if ($key === 0) continue; 
            
            $prevBlock = $blocks[$key - 1];
            
            // Regla 1: El "previous_hash" de este bloque debe ser igual al "hash" real del anterior
            if ($block->previous_hash !== $prevBlock->hash) {
                return ['status' => false, 'broken_block_id' => $block->id, 'error' => 'Cadena Rota: Hash previo no coincide'];
            }
            
            // Regla 2: Recalcular el hash de este bloque con sus datos actuales
            $recalculatedHash = hash('sha256', $block->previous_hash . $block->created_at . $block->data . $block->tipo_evento);
            
            if ($recalculatedHash !== $block->hash) {
                return ['status' => false, 'broken_block_id' => $block->id, 'error' => 'Datos Manipulados: El contenido del bloque ha sido alterado'];
            }
        }
        
        return ['status' => true];
    }
}