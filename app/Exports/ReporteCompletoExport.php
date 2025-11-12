<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class ReporteCompletoExport implements WithMultipleSheets
{
    use Exportable;

    protected $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Hoja 1: Distribución por Género
        $sheets[] = new EstadisticasGeneroExport($this->datos['genero']);

        // Hoja 2: Distribución por Categoría
        $sheets[] = new EstadisticasCategoriaExport($this->datos['categoria']);

        // Hoja 3: Ranking de Disciplinas
        $sheets[] = new RankingDisciplinasExport($this->datos['ranking']);

        // Hoja 4: Métricas de Desempeño
        $sheets[] = new MetricasDesempenoExport($this->datos['metricas']);

        return $sheets;
    }
}
