<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    /**
     * Entry point untuk Executive Dashboard (BRAIN).
     */
    public function index(Request $request)
    {
        // -------------------------------------------------------------------------
        // 1. Konfigurasi Filter & Inisialisasi Baseline
        // -------------------------------------------------------------------------
        $filterYear = $request->input('year', 'CURRENT'); 
        $filterMonth = $request->input('month', 'ALL');     
        $filterProject = $request->input('project', 'ALL'); 

        $currentDate = Carbon::create(2026, 2, 19); 
        $currentYear = 2026;
        $currentMonth = 2; 

        // -------------------------------------------------------------------------
        // 2. Mapping Master Data Project
        // -------------------------------------------------------------------------
        $projectsData = [
            ['name' => 'RDMP RU V Balikpapan Phase I', 'color' => '#FF2E2E'], 
            ['name' => 'New Polypropylene Plant Balongan', 'color' => '#FF6B00'], 
            ['name' => 'New DHT Dumai', 'color' => '#FF9F1C'], 
            ['name' => 'Restorasi Tanki Balongan', 'color' => '#FFC107'], 
            ['name' => 'SPL SPM Balongan', 'color' => '#007BFF'], 
            ['name' => 'New DHT Cilacap', 'color' => '#00C6FF'], 
            ['name' => 'RFCC Cilacap', 'color' => '#00BCD4'], 
            ['name' => 'Biorefinery Cilacap', 'color' => '#2196F3'], 
            ['name' => 'PLBC Cilacap', 'color' => '#4CAF50'], 
            ['name' => 'Olefin TPPI', 'color' => '#8BC34A'], 
            ['name' => 'RDMP RU IV Cilacap', 'color' => '#CDDC39'], 
            ['name' => 'Relokasi SPM Balongan', 'color' => '#009688'], 
            ['name' => 'New DHT Plaju', 'color' => '#9C27B0'], 
            ['name' => 'Revitalisasi RCC RU VI Balongan', 'color' => '#E91E63'], 
            ['name' => 'Green Refinery Plaju', 'color' => '#673AB7'], 
            ['name' => 'New EWTP Balongan', 'color' => '#FF4081'], 
            ['name' => 'RDMP RU VI Balongan Phase I', 'color' => '#FF5722'], 
            ['name' => 'RDMP RU V Early Works 1', 'color' => '#2ECC71'], 
            ['name' => 'GRR Tuban', 'color' => '#F1C40F'], 
            ['name' => 'Petrochemical Jawa Barat', 'color' => '#1ABC9C'], 
            ['name' => 'RDMP RU V ISBL - OSBL', 'color' => '#3498DB'], 
            ['name' => 'RDMP RU V Lawe - Lawe', 'color' => '#E74C3C'], 
        ];

        // -------------------------------------------------------------------------
        // 3. Konfigurasi Sumbu X (X-Axis) & Judul Dinamis
        // -------------------------------------------------------------------------
        $chartCategories = [];   
        $chartTooltipDates = []; 
        $dynamicChartTitle = "";

        $effectiveYear = ($filterYear === 'CURRENT' || $filterYear === 'ALL') ? $currentYear : (int)$filterYear;

        if ($filterMonth !== 'ALL') {
            $startDate = Carbon::createFromDate($effectiveYear, $filterMonth, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($effectiveYear, $filterMonth, 1)->endOfMonth();
            
            $period = CarbonPeriod::create($startDate, '1 day', $endDate);
            $monthNameShort = Carbon::createFromDate(null, $filterMonth, 1)->format('M');
            $monthNameFull = Carbon::createFromDate(null, $filterMonth, 1)->format('F');
            
            foreach ($period as $date) {
                $dayStr = $date->format('d');
                $chartCategories[] = $dayStr; 
                if ($filterYear === 'ALL') {
                    $chartTooltipDates[] = $dayStr . " " . $monthNameShort . " (Total 2025-2026)";
                } else {
                    $chartTooltipDates[] = $date->format('d M Y');
                }
            }
            $dynamicChartTitle = ($filterYear === 'ALL') ? $monthNameFull . " (Total 2025-2026)" : $monthNameFull . " " . $effectiveYear;
        } else {
            $chartCategories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            if ($filterYear === 'ALL') {
                $dynamicChartTitle = "Aggregate Monthly (2025 - 2026)";
                foreach ($chartCategories as $cat) { $chartTooltipDates[] = $cat . " (Total 2025-2026)"; }
            } else {
                $yearLabel = ($filterYear === 'CURRENT') ? $currentYear : $filterYear;
                $dynamicChartTitle = ($filterYear === 'CURRENT') ? "Current Year (YTD)" : "Year " . $filterYear;
                foreach ($chartCategories as $cat) { $chartTooltipDates[] = $cat . " " . $yearLabel; }
            }
        }

        // -------------------------------------------------------------------------
        // 4. Kalkulasi Data Volume Dokumen
        // -------------------------------------------------------------------------
        $barChartData = []; 
        $waveChartData = []; 
        $grandTotalDocuments = 0; 

        foreach ($projectsData as $proj) {
            $isProjectActive = ($filterProject === 'ALL' || $filterProject === $proj['name']);
            $isGiantProject = ($proj['name'] === 'RDMP RU VI Balongan Phase I');
            $waveDataPoints = [];
            $projectTotal = 0; 

            for ($i = 0; $i < count($chartCategories); $i++) {
                $val = 0;
                $shouldGenerate = true;
                if ($filterYear === 'ALL') {
                    foreach ([2025, 2026] as $simYear) {
                        if ($filterMonth !== 'ALL') {
                             $base = $isGiantProject ? rand(300, 500) : rand(10, 50);
                        } else {
                             $base = $isGiantProject ? rand(12000, 16000) : rand(800, 2000);
                        }
                        $variance = rand(90, 110) / 100;
                        $val += floor($base * $variance);
                    }
                } else {
                    if ($filterMonth !== 'ALL') {
                        $day = $i + 1;
                        if ($effectiveYear == $currentYear && $filterMonth == $currentMonth && $day > 19) $shouldGenerate = false;
                        if ($effectiveYear == $currentYear && $filterMonth > $currentMonth) $shouldGenerate = false;
                        if ($shouldGenerate) $val = $isGiantProject ? rand(300, 500) : rand(10, 50);
                    } else {
                        $month = $i + 1;
                        if ($effectiveYear == $currentYear && $month > $currentMonth) $shouldGenerate = false;
                        if ($shouldGenerate) $val = $isGiantProject ? rand(15000, 20000) : rand(800, 2500);
                    }
                }
                $waveDataPoints[] = (int)$val;
                $projectTotal += (int)$val;
            }

            if ($isProjectActive) {
                $barChartData[] = ['name' => $proj['name'], 'color' => $proj['color'], 'total' => $projectTotal];
                $waveChartData[] = ['name' => $proj['name'], 'color' => $proj['color'], 'data' => $waveDataPoints, 'total_trend' => $projectTotal];
                $grandTotalDocuments += $projectTotal;
            }
        }

        usort($barChartData, function($a, $b) { return $b['total'] <=> $a['total']; });
        $barNamesOrder = array_column($barChartData, 'name');
        usort($waveChartData, function($a, $b) use ($barNamesOrder) {
            $posA = array_search($a['name'], $barNamesOrder);
            $posB = array_search($b['name'], $barNamesOrder);
            return $posA <=> $posB;
        });

        // -------------------------------------------------------------------------
        // 5. Formatting Payload View (Data Chart)
        // -------------------------------------------------------------------------
        $fullBarNames = array_column($barChartData, 'name');
        $fullBarValues = array_column($barChartData, 'total');
        $fullBarColors = array_column($barChartData, 'color');

        $top10Bar = array_slice($barChartData, 0, 10);
        $others10Bar = array_slice($barChartData, 10);
        $barNames = array_column($top10Bar, 'name');
        $barValues = array_column($top10Bar, 'total');
        $barColors = array_column($top10Bar, 'color');

        if (!empty($others10Bar)) {
            $sumOthers = 0;
            foreach ($others10Bar as $item) $sumOthers += $item['total'];
            $barNames[] = 'Others (' . count($others10Bar) . ' Projects)';
            $barValues[] = $sumOthers;
            $barColors[] = '#CFD8DC';
        }

        $fullWaveSeries = [];
        foreach($waveChartData as $item) $fullWaveSeries[] = ['name' => $item['name'], 'data' => $item['data']];
        $fullWaveColors = array_column($waveChartData, 'color');

        $top5Wave = array_slice($waveChartData, 0, 5);
        $others5Wave = array_slice($waveChartData, 5);
        $waveColors = array_column($top5Wave, 'color');
        $waveSeries = [];
        foreach($top5Wave as $item) $waveSeries[] = ['name' => $item['name'], 'data' => $item['data']];

        if (!empty($others5Wave)) {
            $othersWaveData = array_fill(0, count($chartCategories), 0);
            foreach ($others5Wave as $item) {
                for($k=0; $k<count($chartCategories); $k++) $othersWaveData[$k] += $item['data'][$k];
            }
            $waveSeries[] = ['name' => 'Others (' . count($others5Wave) . ' Projects)', 'data' => $othersWaveData];
            $waveColors[] = '#CFD8DC';
        }

        $kpiData = [
            'total_documents' => $grandTotalDocuments,
            'document_project' => floor($grandTotalDocuments * 0.76),
            'document_fungsi' => floor($grandTotalDocuments * 0.24),
            'total_users' => 1240,
            'active_users_30d' => 202
        ];

        $lifecycleData = [
            ['phase' => '01. Initiation', 'count' => 119, 'active' => true, 'icon' => 'M4 6h16M4 12h16M4 18h7'],
            ['phase' => '02. Pre-FS', 'count' => 22, 'active' => true, 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['phase' => '03. Pre-FID/Early Work', 'count' => 16, 'active' => true, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['phase' => '04. BED', 'count' => floor($grandTotalDocuments * 0.30), 'active' => false, 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ['phase' => '05. FEED', 'count' => floor($grandTotalDocuments * 0.20), 'active' => false, 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
            ['phase' => '06. FS & FID', 'count' => 1, 'active' => false, 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
            ['phase' => '07. EPC Bidding', 'count' => 27, 'active' => false, 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['phase' => '08. EPC Work', 'count' => floor($grandTotalDocuments * 0.48), 'active' => false, 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['phase' => '09. Operation', 'count' => 6, 'active' => false, 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['phase' => '10. Closing', 'count' => 4, 'active' => false, 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
        ];

        $phaseDocuments = [
            '01. Initiation' => [
                ['doc_name' => 'Project_Charter_RDMP_Balikpapan_Final.pdf', 'project' => 'RDMP RU V Balikpapan', 'uploader' => 'Andi Wijaya', 'date' => '18-Feb-2026', 'size' => '2.4 MB', 'type' => 'PDF'],
                ['doc_name' => 'MoM_Kickoff_Meeting_Tuban.pdf', 'project' => 'GRR Tuban', 'uploader' => 'Budi Santoso', 'date' => '15-Feb-2026', 'size' => '1.1 MB', 'type' => 'PDF'],
                ['doc_name' => 'Initial_Risk_Assessment_Cilacap.xlsx', 'project' => 'Biorefinery Cilacap', 'uploader' => 'Siti Nurhaliza', 'date' => '10-Feb-2026', 'size' => '845 KB', 'type' => 'XLSX'],
                ['doc_name' => 'Stakeholder_Mapping_Dumai.docx', 'project' => 'New DHT Dumai', 'uploader' => 'Rina Melati', 'date' => '08-Feb-2026', 'size' => '520 KB', 'type' => 'DOCX'],
                ['doc_name' => 'Business_Case_Approval_Form.pdf', 'project' => 'Olefin TPPI', 'uploader' => 'Hanif Naufal', 'date' => '05-Feb-2026', 'size' => '3.2 MB', 'type' => 'PDF'],
            ],
            '02. Pre-FS' => [
                ['doc_name' => 'Pre_Feasibility_Study_Report_Dumai_v2.pdf', 'project' => 'New DHT Dumai', 'uploader' => 'Dimas Pratama', 'date' => '17-Feb-2026', 'size' => '8.6 MB', 'type' => 'PDF'],
                ['doc_name' => 'Market_Analysis_Data_Tuban.xlsx', 'project' => 'GRR Tuban', 'uploader' => 'I Putu Borneo', 'date' => '12-Feb-2026', 'size' => '4.1 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Site_Selection_Criteria.pptx', 'project' => 'Petrochemical Jawa Barat', 'uploader' => 'Citra Dewi', 'date' => '09-Feb-2026', 'size' => '12.5 MB', 'type' => 'PPTX'],
                ['doc_name' => 'Preliminary_Cost_Estimate.xlsx', 'project' => 'New EWTP Balongan', 'uploader' => 'Muhammad Syafri', 'date' => '04-Feb-2026', 'size' => '1.8 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Environmental_Impact_Screening.pdf', 'project' => 'Green Refinery Plaju', 'uploader' => 'Andi Wijaya', 'date' => '01-Feb-2026', 'size' => '4.2 MB', 'type' => 'PDF'],
            ],
            '03. Pre-FID/Early Work' => [
                ['doc_name' => 'Site_Preparation_Survey_Balongan.pdf', 'project' => 'RDMP RU VI Balongan Phase I', 'uploader' => 'Citra Dewi', 'date' => '05-Feb-2026', 'size' => '5.2 MB', 'type' => 'PDF'],
                ['doc_name' => 'Early_Works_Approval_Memo.docx', 'project' => 'RDMP RU V Early Works 1', 'uploader' => 'Hanif Naufal', 'date' => '02-Feb-2026', 'size' => '890 KB', 'type' => 'DOCX'],
                ['doc_name' => 'Land_Acquisition_Status_Report.pdf', 'project' => 'GRR Tuban', 'uploader' => 'Budi Santoso', 'date' => '28-Jan-2026', 'size' => '3.1 MB', 'type' => 'PDF'],
                ['doc_name' => 'Geotechnical_Investigation_Data.xlsx', 'project' => 'New DHT Cilacap', 'uploader' => 'I Putu Borneo', 'date' => '25-Jan-2026', 'size' => '6.4 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Budget_Allocation_Early_Works.pdf', 'project' => 'RDMP RU V Early Works 1', 'uploader' => 'Siti Nurhaliza', 'date' => '20-Jan-2026', 'size' => '1.5 MB', 'type' => 'PDF'],
            ],
            '04. BED' => [
                ['doc_name' => 'Basic_Engineering_Design_Data.xlsx', 'project' => 'New Polypropylene Plant Balongan', 'uploader' => 'Budi Santoso', 'date' => '20-Jan-2026', 'size' => '12.5 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Process_Flow_Diagram_Draft.pdf', 'project' => 'Green Refinery Plaju', 'uploader' => 'Rina Melati', 'date' => '18-Jan-2026', 'size' => '3.8 MB', 'type' => 'PDF'],
                ['doc_name' => 'Equipment_Sizing_Calculation.xlsx', 'project' => 'New DHT Dumai', 'uploader' => 'Dimas Pratama', 'date' => '15-Jan-2026', 'size' => '2.2 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Material_Selection_Diagram.pdf', 'project' => 'RDMP RU V ISBL - OSBL', 'uploader' => 'Muhammad Syafri', 'date' => '10-Jan-2026', 'size' => '5.6 MB', 'type' => 'PDF'],
                ['doc_name' => 'Utility_Consumption_Summary.docx', 'project' => 'Biorefinery Cilacap', 'uploader' => 'Andi Wijaya', 'date' => '08-Jan-2026', 'size' => '1.1 MB', 'type' => 'DOCX'],
            ],
            '05. FEED' => [
                ['doc_name' => 'FEED_Final_Report_Cilacap.pdf', 'project' => 'RFCC Cilacap', 'uploader' => 'Andi Wijaya', 'date' => '10-Jan-2026', 'size' => '45.2 MB', 'type' => 'PDF'],
                ['doc_name' => 'P&ID_Master_Drawing.pdf', 'project' => 'New DHT Plaju', 'uploader' => 'Muhammad Syafri', 'date' => '05-Jan-2026', 'size' => '18.1 MB', 'type' => 'PDF'],
                ['doc_name' => 'HAZOP_Study_Report_Final.pdf', 'project' => 'RDMP RU VI Balongan Phase I', 'uploader' => 'Siti Nurhaliza', 'date' => '03-Jan-2026', 'size' => '9.4 MB', 'type' => 'PDF'],
                ['doc_name' => 'Instrument_Data_Sheets.xlsx', 'project' => 'Olefin TPPI', 'uploader' => 'Citra Dewi', 'date' => '28-Dec-2025', 'size' => '8.7 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Project_Execution_Plan_FEED.docx', 'project' => 'Petrochemical Jawa Barat', 'uploader' => 'Hanif Naufal', 'date' => '20-Dec-2025', 'size' => '2.9 MB', 'type' => 'DOCX'],
            ]
        ];

        // -------------------------------------------------------------------------
        // 7. Audit Trail Data Log (Initial Data - The rest handled by JS for Dynamic Live effect)
        // -------------------------------------------------------------------------
        $activityLogs = [
            [
                'user' => 'Andi Wijaya', 'initial' => 'AW', 'avatar_color' => 'bg-green-100 text-green-700',
                'action_label' => 'Uploaded', 'action_color' => 'text-green-700 bg-green-50 border-green-200',
                'document' => 'P&ID_RDMP_Balikpapan_v2.pdf', 'location' => 'Project: RDMP RU V Balikpapan',
                'time' => '1 mins ago',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />'
            ],
            [
                'user' => 'Siti Nurhaliza', 'initial' => 'SN', 'avatar_color' => 'bg-purple-100 text-purple-700',
                'action_label' => 'Asked AI', 'action_color' => 'text-purple-700 bg-purple-50 border-purple-200',
                'document' => 'Prompt: "Summarize HAZOP Report..."', 'location' => 'Module: AI Chatbot',
                'time' => '5 mins ago',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />'
            ],
            [
                'user' => 'Budi Santoso', 'initial' => 'BS', 'avatar_color' => 'bg-blue-100 text-blue-700',
                'action_label' => 'Updated Metadata', 'action_color' => 'text-blue-700 bg-blue-50 border-blue-200',
                'document' => 'LAPORAN AKHIR Studi Fault Risk...', 'location' => 'Project: NGRR GRR Tuban',
                'time' => '12 mins ago',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />'
            ],
            [
                'user' => 'Rina Melati', 'initial' => 'RM', 'avatar_color' => 'bg-teal-100 text-teal-700',
                'action_label' => 'Searched', 'action_color' => 'text-teal-700 bg-teal-50 border-teal-200',
                'document' => 'Keyword: "Piping Work ISO"', 'location' => 'Module: Smart Search',
                'time' => '25 mins ago',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />'
            ],
            [
                'user' => 'Dimas Pratama', 'initial' => 'DP', 'avatar_color' => 'bg-red-100 text-red-700',
                'action_label' => 'Deleted', 'action_color' => 'text-red-700 bg-red-50 border-red-200',
                'document' => 'Draft_Kontrak_Lama_v1.pdf', 'location' => 'Module: Documents Inventory',
                'time' => '45 mins ago',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />'
            ]
        ];

        $qaRecentDocs = [
            ['name' => 'Tes Kirim File Fungsi.pdf', 'type' => 'pdf', 'category' => 'Fungsi', 'security' => 'Public', 'date' => '04-Feb-2026 09:20:23'],
            ['name' => 'Mockup New Brain Dashboard.jpg', 'type' => 'image', 'category' => 'Fungsi', 'security' => 'Public', 'date' => '05-Feb-2026 10:38:10'],
            ['name' => 'Minutes_of_Meeting_VP.docx', 'type' => 'word', 'category' => 'Project', 'security' => 'Internal', 'date' => '17-Feb-2026 09:12:00']
        ];

        $qaRecentOpen = [
            ['name' => 'P&ID_RDMP_Balikpapan_v2.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Internal', 'date' => '19-Feb-2026 08:15:00'],
            ['name' => 'Draft_Kontrak_EPC_Tuban.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Restricted', 'date' => '18-Feb-2026 14:20:00']
        ];

        $qaConfidential = [
            ['name' => 'Board_Resolution_Q1_2026.pdf', 'type' => 'pdf', 'category' => 'Fungsi', 'security' => 'Confidential', 'date' => '10-Feb-2026 11:00:00'],
            ['name' => 'Financial_Audit_Report.xlsx', 'type' => 'excel', 'category' => 'Fungsi', 'security' => 'Confidential', 'date' => '01-Feb-2026 09:30:00']
        ];

        $qaHandover = [
            ['name' => 'As-Built_Drawing_Area_5.pdf', 'type' => 'pdf', 'category' => 'Project', 'status' => 'Synced to BRAIN', 'status_color' => 'bg-blue-50 text-blue-600 border-blue-200', 'date' => '19-Feb-2026 16:45:00'],
            ['name' => 'Final_HAZOP_Report.docx', 'type' => 'word', 'category' => 'Project', 'status' => 'Indexing', 'status_color' => 'bg-gray-100 text-gray-600 border-gray-300', 'date' => '19-Feb-2026 15:30:00']
        ];

        $aiImpact = [
            'total_queries' => '1,250',
            'documents_summarized' => '430',
            'growth' => '+18%'
        ];

        $trendingKeywords = [
            '#HAZOP_Balongan',
            '#Kontrak_EPC_Tuban',
            '#P&ID_Cilacap'
        ];

        $dummyFiles = [];

        return view('brain', compact(
            'kpiData', 'projectsData', 
            'fullBarNames', 'fullBarValues', 'fullBarColors',
            'barNames', 'barValues', 'barColors',
            'fullWaveSeries', 'fullWaveColors', 
            'waveSeries', 'waveColors',
            'lifecycleData', 'chartCategories', 'chartTooltipDates', 
            'filterYear', 'filterMonth', 'filterProject', 'dummyFiles', 
            'dynamicChartTitle', 'activityLogs', 'phaseDocuments',
            'qaRecentDocs', 'qaRecentOpen', 'qaConfidential', 'qaHandover',
            'aiImpact', 'trendingKeywords'
        ));
    }

    public function chatAi() { return view('chat-ai'); }

    public function smartSearch(Request $request)
    {
        $searchQuery = $request->query('q', '');
        
        // Dummy Database Dokumen (Kaya Variasi)
        $allDocs = [
            ['title' => 'Pipa Instalasi Area 5', 'project' => 'RDMP RU V Balikpapan', 'category' => 'Engineering', 'type' => 'pdf', 'date' => '10-Feb-2026'],
            ['title' => 'Piping Isometric Drawing', 'project' => 'RDMP RU V Balikpapan', 'category' => 'Engineering', 'type' => 'pdf', 'date' => '12-Feb-2026'],
            ['title' => 'Inspeksi Pipa dan Valve Pipeline', 'project' => 'GRR Tuban', 'category' => 'Quality Control', 'type' => 'docx', 'date' => '15-Feb-2026'],
            ['title' => 'Kontrak Piping Konstruksi', 'project' => 'New DHT Dumai', 'category' => 'Legal', 'type' => 'pdf', 'date' => '18-Feb-2026'],
            ['title' => 'Pengadaan Material Pipa (PO)', 'project' => 'Olefin TPPI', 'category' => 'Procurement', 'type' => 'pdf', 'date' => '25-Feb-2026'],
            ['title' => 'Spesifikasi Material Piping', 'project' => 'New DHT Plaju', 'category' => 'Engineering', 'type' => 'xlsx', 'date' => '01-Mar-2026'],
            ['title' => 'Desain Pipa Bawah Laut', 'project' => 'SPL SPM Balongan', 'category' => 'Engineering', 'type' => 'pdf', 'date' => '03-Mar-2026'],
            ['title' => 'Review Piping Layout', 'project' => 'Biorefinery Cilacap', 'category' => 'Engineering', 'type' => 'pdf', 'date' => '04-Mar-2026'],
            
            ['title' => 'Laporan HAZOP Balongan Tahap 1', 'project' => 'RDMP RU VI Balongan Phase I', 'category' => 'HSE', 'type' => 'pdf', 'date' => '20-Feb-2026'],
            ['title' => 'MoM HAZOP Review Tuban', 'project' => 'GRR Tuban', 'category' => 'HSE', 'type' => 'docx', 'date' => '22-Feb-2026'],
            ['title' => 'Panduan Keselamatan HAZOP', 'project' => 'RDMP RU V Balikpapan', 'category' => 'HSE', 'type' => 'pdf', 'date' => '02-Mar-2026'],
            
            ['title' => 'Draft Kontrak EPC Tuban', 'project' => 'GRR Tuban', 'category' => 'Legal', 'type' => 'docx', 'date' => '26-Feb-2026'],
            ['title' => 'Agreement EPC Balongan', 'project' => 'RDMP RU VI Balongan Phase I', 'category' => 'Legal', 'type' => 'pdf', 'date' => '27-Feb-2026'],
            ['title' => 'P&ID Cilacap Master Diagram', 'project' => 'RFCC Cilacap', 'category' => 'Engineering', 'type' => 'xlsx', 'date' => '21-Feb-2026'],
        ];

        $results = [];
        $isBooleanUsed = false;
        $detectedOperators = [];

        if (!empty($searchQuery)) {
            // Trim ruang (spasi) ekstra
            $queryStr = trim($searchQuery);

            // Cek apakah mengandung operator Boolean kapital (Mendeteksi unlimited operator)
            if (preg_match('/\b(AND|OR|NOT)\b/', $queryStr)) {
                $isBooleanUsed = true;
                
                // Mengambil daftar semua operator yang dipakai user untuk di-display ke UI
                preg_match_all('/\b(AND|OR|NOT)\b/', $queryStr, $matches);
                $detectedOperators = array_values(array_unique($matches[0]));
                
                // Pisahkan string berdasarkan operator. Hasilnya berupa array kalimat dan operator secara selang-seling.
                // Contoh: ['HAZOP', 'AND', 'Balongan', 'NOT', 'Tuban']
                $tokens = preg_split('/(\bAND\b|\bOR\b|\bNOT\b)/', $queryStr, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                $tokens = array_map('trim', $tokens);

                // ALGORITMA EVALUASI FLEKSIBEL (Left-to-Right Sequential Evaluator)
                foreach ($allDocs as $doc) {
                    $content = strtolower($doc['title'] . ' ' . $doc['project'] . ' ' . $doc['category']);
                    
                    // Fungsi internal cerdas untuk mengecek sebuah kata/frasa
                    $checkToken = function($token, $text) {
                        // Hapus tanda baca (, ), dan " agar lebih mudah ketemu (Flexibility)
                        $cleanToken = strtolower(trim(str_replace(['(', ')', '"'], '', $token)));
                        if (empty($cleanToken)) return true;
                        
                        // Jika 1 token ternyata terdiri dari beberapa kata (misal: Laporan Keuangan)
                        $subWords = explode(' ', $cleanToken);
                        foreach($subWords as $sw) {
                            if (trim($sw) !== '' && strpos($text, $sw) === false) {
                                return false; // Salah satu kata tidak ada, langsung gagalkan token ini
                            }
                        }
                        return true;
                    };

                    // Set evaluasi berdasarkan kata / frasa pertama
                    $isMatch = $checkToken($tokens[0], $content);

                    // Looping tanpa batas: Evaluasi sisa token berapapun panjangnya
                    for ($i = 1; $i < count($tokens); $i += 2) {
                        if (!isset($tokens[$i+1])) break; // Mencegah error index out of bounds jika pengetikan user gantung

                        $operator = $tokens[$i];
                        $nextWordExists = $checkToken($tokens[$i+1], $content);

                        if ($operator === 'AND') {
                            $isMatch = $isMatch && $nextWordExists;
                        } elseif ($operator === 'OR') {
                            $isMatch = $isMatch || $nextWordExists;
                        } elseif ($operator === 'NOT') {
                            $isMatch = $isMatch && !$nextWordExists;
                        }
                    }

                    if ($isMatch) {
                        $results[] = $doc;
                    }
                }
            } 
            // PENCARIAN REGULAR (Tanpa Boolean)
            else {
                // Untuk handle hashtag dari dashboard (misal: HAZOP_Balongan menjadi HAZOP Balongan)
                $term = strtolower(str_replace(['_', '(', ')', '"'], ' ', $queryStr)); 
                $words = explode(' ', $term);
                
                foreach ($allDocs as $doc) {
                    $content = strtolower($doc['title'] . ' ' . $doc['project'] . ' ' . $doc['category']);
                    $matchAll = true;
                    foreach($words as $word) {
                        if (trim($word) !== '' && strpos($content, trim($word)) === false) {
                            $matchAll = false;
                            break;
                        }
                    }
                    if ($matchAll) {
                        $results[] = $doc;
                    }
                }
            }
        } else {
            // Jika kosong, tampilkan semua
            $results = $allDocs;
        }

        return view('smart-search', compact('searchQuery', 'results', 'isBooleanUsed', 'detectedOperators'));
    }
}