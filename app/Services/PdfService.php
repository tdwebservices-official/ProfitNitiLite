<?php

namespace App\Services;
use App\Models\Options;
use Mpdf\Mpdf;

class PdfService
{
    public function generateFinancialReport(string $insights, string $filePath, string $report_title = '' )
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 40,
            'margin_bottom' => 30,
            
        ]);

        $branding_option_list = Options::where('option_name', 'branding_options')->first();
        $branding_option_arr = [];
        if( isset($branding_option_list->option_value) && !empty($branding_option_list->option_value) ){
            $branding_option_arr = unserialize($branding_option_list->option_value);
        }

        $html = view('reports.deepseek', [          
            'insights' => $insights,
            'report_title' => $report_title,
            'branding_option_arr' => $branding_option_arr,
        ])->render();

        $mpdf->WriteHTML($html);
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        return $filePath;
    }
}