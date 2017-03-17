<?php

namespace myocuhub\Helpers;

use Maatwebsite\Excel\Facades\Excel;
use Event;
use myocuhub\Events\MakeAuditEntry;

trait ExcelHelper
{
    public static function exportExcel($data, $fileName, $requestIP, $widthArray = array(), $fileType = 'xlsx')
    {

        $action = 'Exported File - ' . $fileName;
        $description = '';
        $filename = basename(__FILE__);
        $ip = $requestIP;
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

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
