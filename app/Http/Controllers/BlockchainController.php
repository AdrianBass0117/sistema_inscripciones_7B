<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockchainBlock;

class BlockchainController extends Controller
{
    public function index()
    {
        // Obtener la cadena completa (los últimos 20 bloques para no saturar)
        $blocks = BlockchainBlock::orderBy('id', 'desc')->take(20)->get();
        
        // Verificar integridad en tiempo real
        $integrity = BlockchainBlock::checkIntegrity();
        
        return view('supervisor.blockchain', compact('blocks', 'integrity'));
    }

    // Función para simular un ataque (Hackear la BD)
    public function hackBlock($id)
    {
        $block = BlockchainBlock::find($id);
        
        // ¡HACKEO! Modificamos los datos sin actualizar el hash
        $datosFalsos = json_decode($block->data, true);
        $datosFalsos['status'] = 'HACKED_BY_STUDENT'; // Alteramos la info
        
        $block->data = json_encode($datosFalsos);
        $block->save(); // Guardamos directamente, rompiendo la cadena criptográfica
        
        return back()->with('error', '¡Bloque ' . $id . ' manipulado manualmente! La cadena debería estar rota ahora.');
    }

    // Función para reparar (Recalcular hashes - Minería)
    public function repairChain()
    {
        $blocks = BlockchainBlock::orderBy('id', 'asc')->get();
        $prevHash = '0000000000000000000000000000000000000000000000000000000000000000';

        foreach($blocks as $block) {
            // Actualizar el puntero al anterior
            $block->previous_hash = $prevHash;
            
            // Recalcular hash válido
            $stringToHash = $prevHash . $block->created_at . $block->data . $block->tipo_evento;
            $block->hash = hash('sha256', $stringToHash);
            $block->save();
            
            $prevHash = $block->hash;
        }

        return back()->with('success', 'Blockchain recalculada y reparada.');
    }
}