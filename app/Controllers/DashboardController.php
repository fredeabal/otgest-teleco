<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        $db = \Config\Database::connect();
        
        $filter = $this->request->getGet('filter') ?? 'month';
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        $dbStartDate = $startDate;
        $dbEndDate = $endDate;
        
        if ($startDate) {
            $d = \DateTime::createFromFormat('d/m/Y', $startDate);
            if ($d) $dbStartDate = $d->format('Y-m-d');
        }
        if ($endDate) {
            $d = \DateTime::createFromFormat('d/m/Y', $endDate);
            if ($d) $dbEndDate = $d->format('Y-m-d');
        }
        
        $canViewAll = $user->can('orders.view_all') || $user->inGroup('superadmin', 'admin');
        
        // --- 1. Get metrics counts ---
        $builder = $db->table('ordenes');
        
        if (!$canViewAll) {
            $builder->where('ot_usr', $user->id);
        }
        
        // Apply date filters to metrics
        if ($filter === 'day') {
            $builder->where('ot_fecha', date('Y-m-d'));
        } elseif ($filter === 'month') {
            $builder->where("strftime('%Y-%m', ot_fecha)", date('Y-m'));
        } elseif ($filter === 'year') {
            $builder->where("strftime('%Y', ot_fecha)", date('Y'));
        } elseif ($filter === '12months') {
            $builder->where('ot_fecha >=', date('Y-m-d', strtotime('-12 months')));
        } elseif ($filter === 'custom' && $dbStartDate && $dbEndDate) {
            $builder->where('ot_fecha >=', $dbStartDate);
            $builder->where('ot_fecha <=', $dbEndDate);
        }
        
        $builder->select('ot_tipo, COUNT(*) as total');
        $builder->groupBy('ot_tipo');
        $results = $builder->get()->getResultArray();
        
        $metrics = [
            'INSTALACION' => 0, 'AVERIA' => 0, 'MODIFICACION' => 0,
            'TRASLADO' => 0, 'PORTABILIDAD' => 0, 'BAJA' => 0,
            'AUDITORIA' => 0, 'TOTAL' => 0
        ];
        
        foreach ($results as $row) {
            $tipo = strtoupper(trim($row['ot_tipo']));
            if (isset($metrics[$tipo])) {
                $metrics[$tipo] = $row['total'];
            }
            $metrics['TOTAL'] += $row['total'];
        }
        
        $data['metrics'] = $metrics;
        $data['currentFilter'] = $filter;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        // --- 2. Get chart data ---
        $chartBuilder = $db->table('ordenes');
        
        if (!$canViewAll) {
            $chartBuilder->where('ot_usr', $user->id);
        }

        // Group by day if filter is month, custom, or day. Group by month if year or total.
        $groupBy = 'month';
        
        if ($filter === 'month') {
            $chartBuilder->where("strftime('%Y-%m', ot_fecha)", date('Y-m'));
            $chartBuilder->select("strftime('%Y-%m-%d', ot_fecha) as label, COUNT(*) as total");
            $groupBy = 'day';
        } elseif ($filter === 'custom' && $dbStartDate && $dbEndDate) {
            $chartBuilder->where('ot_fecha >=', $dbStartDate);
            $chartBuilder->where('ot_fecha <=', $dbEndDate);
            // Si el rango es mayor a 60 días, quizás agrupar por mes, pero por defecto día
            $date1 = new \DateTime($dbStartDate);
            $date2 = new \DateTime($dbEndDate);
            if ($date1->diff($date2)->days > 90) {
                $chartBuilder->select("strftime('%Y-%m', ot_fecha) as label, COUNT(*) as total");
                $groupBy = 'month';
            } else {
                $chartBuilder->select("strftime('%Y-%m-%d', ot_fecha) as label, COUNT(*) as total");
                $groupBy = 'day';
            }
        } elseif ($filter === 'year') {
            $chartBuilder->where("strftime('%Y', ot_fecha)", date('Y'));
            $chartBuilder->select("strftime('%Y-%m', ot_fecha) as label, COUNT(*) as total");
        } elseif ($filter === '12months') {
            $chartBuilder->where('ot_fecha >=', date('Y-m-d', strtotime('-12 months')));
            $chartBuilder->select("strftime('%Y-%m', ot_fecha) as label, COUNT(*) as total");
        } elseif ($filter === 'day') {
            $chartBuilder->where('ot_fecha', date('Y-m-d'));
            $chartBuilder->select("strftime('%Y-%m-%d', ot_fecha) as label, COUNT(*) as total");
            $groupBy = 'day';
        } else {
            // Default: Total Histórico
            $chartBuilder->select("strftime('%Y', ot_fecha) as label, COUNT(*) as total");
            $groupBy = 'year';
        }
        
        $chartBuilder->groupBy('label');
        $chartBuilder->orderBy('label', 'ASC');
        $chartResults = $chartBuilder->get()->getResultArray();
        
        $chartLabels = [];
        $chartData = [];
        
        $mesesEs = [
            '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
            '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
            '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
        ];
        
        foreach ($chartResults as $row) {
            if ($groupBy === 'year') {
                $chartLabels[] = $row['label'];
            } elseif ($groupBy === 'month') {
                $parts = explode('-', $row['label']);
                if (count($parts) >= 2) {
                    $chartLabels[] = $mesesEs[$parts[1]] . ($filter === '12months' || $filter === 'total' ? ' ' . substr($parts[0], 2) : '');
                } else {
                    $chartLabels[] = $row['label'];
                }
            } else {
                // Day format: DD/MM
                $parts = explode('-', $row['label']);
                if (count($parts) == 3) {
                    $chartLabels[] = $parts[2] . '/' . $parts[1];
                } else {
                    $chartLabels[] = $row['label'];
                }
            }
            $chartData[] = (int)$row['total'];
        }
        
        $data['chartLabels'] = json_encode($chartLabels);
        $data['chartData'] = json_encode($chartData);

        echo view('template/header', $data);
        echo view('dashboards/index', $data);
        echo view('template/footer');
    }
}
