<?php
function convertirNumerosEnArray($data)
{
    $fields = [
        'acreedor_id',
        'objeto_deuda',
        'moneda_id',
        'total_capital',
        'total_interes',
        'total_comisiones',
        'total_sum',
        'capital',
        'interes',
        'comisiones',
        'total',
        'saldo',
    ];
    // Convierte los campos del array principal
    foreach ($fields as $field) {
        if (isset($data[$field])) {
            $data[$field] = str_replace('.', '', $data[$field]); // Elimina los puntos de miles
            $data[$field] = str_replace(',', '.', $data[$field]); // Cambia la coma por punto para decimales
        }
    }

    // Verifica si hay un array 'cuadro_pagos' y lo convierte
    if (isset($data['cuadro_pagos']) && is_array($data['cuadro_pagos'])) {
        foreach ($data['cuadro_pagos'] as &$item) {
            foreach ($fields as $field) {
                if (isset($item[$field])) {
                    $item[$field] = str_replace('.', '', $item[$field]); // Elimina los puntos de miles
                    $item[$field] = str_replace(',', '.', $item[$field]); // Cambia la coma por punto para decimales
                }
            }
        }
    }

    return $data;
}
