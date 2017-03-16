<?php

namespace myocuhub\Helpers;

use Maatwebsite\Excel\Facades\Excel;

trait ExcelHelper
{
    public static function exportExcel($data, $fileName, $widthArray = array(), $fileType = 'xlsx')
    {
        Excel::create($fileName, function ($excel) use ($data, $widthArray) {
            $excel->sheet('Audits', function ($sheet) use ($data, $widthArray) {
                $sheet->setWidth($widthArray);
                $sheet->setPageMargin(0.25);
                $sheet->fromArray($data);
                $sheet->cell('A1:Z1', function ($cells) {
                    $cells->setFont(array(
                        'family'     => 'Calibri',
                        'size'       => '11',
                        'bold'       =>  true
                    ));
                });
            });
        })->export($fileType);

        return true;
    }
}
