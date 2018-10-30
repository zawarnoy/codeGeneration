<?php

namespace App\Service;



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class XLSGenerator
{
    public function generate($codes) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        for($i=0; $i<count($codes); $i++) {
            $sheet->setCellValue('A'.($i+1), $codes[$i]);
        }

        $writer = new Xls($spreadsheet);
        $writer->save('blablabla.xls');
    }
}