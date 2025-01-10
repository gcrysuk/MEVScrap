<?php
class Filters
{
    public static function prepareFilters($data)
    {
        $filters = [];
        if (!empty($data['caratula'])) {
            $filters['caratula'] = $data['caratula'];
        }
        if (!empty($data['expediente'])) {
            $filters['expediente'] = $data['expediente'];
        }
        if (!empty($data['receptoria'])) {
            $filters['receptoria'] = $data['receptoria'];
        }
        if (!empty($data['fecha_desde']) && !empty($data['fecha_hasta'])) {
            $filters['fecha_desde'] = $data['fecha_desde'];
            $filters['fecha_hasta'] = $data['fecha_hasta'];
        }
        return $filters;
    }
}
