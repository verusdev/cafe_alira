<?php

namespace App\Http\Controllers;

use App\Models\Event;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExportController extends Controller
{
    public function events()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Мероприятия');

        $headers = ['Клиент', 'Тип', 'Дата', 'Время', 'Гостей', 'Статус', 'Телефон', 'Email', 'Стоимость услуги', 'Стоимость меню', 'Итого', 'Прибыль'];
        $cols = 'A1:L1';

        $sheet->getStyle($cols)->getFont()->setBold(true);
        $sheet->getStyle($cols)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF97316');
        $sheet->getStyle($cols)->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle($cols)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $colsMap = range('A', 'L');
        foreach ($headers as $i => $h) {
            $sheet->setCellValue($colsMap[$i] . '1', $h);
        }

        $events = Event::with('dishes')->orderBy('event_date', 'desc')->get();
        $row = 2;
        foreach ($events as $event) {
            $sheet->setCellValue('A' . $row, $event->client_name);
            $sheet->setCellValue('B' . $row, $event->type_label);
            $sheet->setCellValue('C' . $row, $event->event_date->format('d.m.Y'));
            $sheet->setCellValue('D' . $row, $event->event_time ? date('H:i', strtotime($event->event_time)) : '');
            $sheet->setCellValue('E' . $row, $event->people_count);
            $sheet->setCellValue('F' . $row, $event->status_label);
            $sheet->setCellValue('G' . $row, $event->client_phone ?? '');
            $sheet->setCellValue('H' . $row, $event->client_email ?? '');
            $sheet->setCellValue('I' . $row, $event->service_price);
            $sheet->setCellValue('J' . $row, $event->menu_price);
            $sheet->setCellValue('K' . $row, $event->total_price);
            $sheet->setCellValue('L' . $row, $event->expected_profit);
            $row++;
        }

        $sheet->getStyle('A1:L' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        return $this->download($spreadsheet, 'meropriyatiya.xlsx');
    }

    public function finance()
    {
        abort_unless(auth()->user()->isManager(), 403);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Финансы');

        $headers = ['Клиент', 'Тип', 'Дата', 'Гостей', 'Статус', 'Цена/чел', 'Услуга', 'Меню', 'Итого', 'Затраты', 'Прибыль', 'Рентаб.'];
        $cols = 'A1:L1';

        $sheet->getStyle($cols)->getFont()->setBold(true);
        $sheet->getStyle($cols)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF16A34A');
        $sheet->getStyle($cols)->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle($cols)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $colsMap = range('A', 'L');
        foreach ($headers as $i => $h) {
            $sheet->setCellValue($colsMap[$i] . '1', $h);
        }

        $events = Event::with('dishes.ingredients')->orderBy('event_date', 'desc')->get();
        $row = 2;
        $totals = [0, 0, 0, 0, 0];
        foreach ($events as $event) {
            $margin = $event->total_price > 0 ? ($event->expected_profit / $event->total_price) * 100 : 0;
            $sheet->setCellValue('A' . $row, $event->client_name);
            $sheet->setCellValue('B' . $row, $event->type_label);
            $sheet->setCellValue('C' . $row, $event->event_date->format('d.m.Y'));
            $sheet->setCellValue('D' . $row, $event->people_count);
            $sheet->setCellValue('E' . $row, $event->status_label);
            $sheet->setCellValue('F' . $row, $event->type_price);
            $sheet->setCellValue('G' . $row, $event->service_price);
            $sheet->setCellValue('H' . $row, $event->menu_price);
            $sheet->setCellValue('I' . $row, $event->total_price);
            $sheet->setCellValue('J' . $row, $event->ingredient_cost);
            $sheet->setCellValue('K' . $row, $event->expected_profit);
            $sheet->setCellValue('L' . $row, round($margin, 1) . '%');
            $totals[0] += $event->service_price;
            $totals[1] += $event->menu_price;
            $totals[2] += $event->total_price;
            $totals[3] += $event->ingredient_cost;
            $totals[4] += $event->expected_profit;
            $row++;
        }

        $sheet->setCellValue('A' . $row, 'ИТОГО');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('G' . $row, $totals[0]);
        $sheet->setCellValue('H' . $row, $totals[1]);
        $sheet->setCellValue('I' . $row, $totals[2]);
        $sheet->setCellValue('J' . $row, $totals[3]);
        $sheet->setCellValue('K' . $row, $totals[4]);
        $totalMargin = $totals[2] > 0 ? ($totals[4] / $totals[2]) * 100 : 0;
        $sheet->setCellValue('L' . $row, round($totalMargin, 1) . '%');

        $sheet->getStyle('A1:L' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        return $this->download($spreadsheet, 'finansoviy_otchet.xlsx');
    }

    private function download(Spreadsheet $spreadsheet, string $filename)
    {
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
