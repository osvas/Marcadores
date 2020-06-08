<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TiempoExtension extends AbstractExtension {

    const CONFIGURACION = ['formato' => 'Y/m/d H:m:s'];

    public function getFilters() {

        return [
            new TwigFilter('tiempo', [$this, 'formatearTiempo'])
        ];
    }

    public function formatearTiempo($fecha, $configuracion = []) {
        $configuracion = array_merge(self::CONFIGURACION, $configuracion);
        $fechaActual = new \DateTime();
        $fechaFormateada = $fecha->format($configuracion['formato']);
        $diferenciaEnSegundos = $fechaActual->getTimestamp() - $fecha->getTimestamp();

        if ($diferenciaEnSegundos < 60) {
            $fechaFormateada = 'Creado ahora mismo';
        } elseif ($diferenciaEnSegundos < 3600) {
            $fechaFormateada = 'Creado recientemente';
        } elseif ($diferenciaEnSegundos < 144000) {
            $fechaFormateada = 'Creado hace unas horas';
        }

        return $fechaFormateada;
    }

}
