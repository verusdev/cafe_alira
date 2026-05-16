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

        foreach ($headers as $i => $h) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $h);
        }

        $events = Event::with('dishes')->orderBy('event_date', 'desc')->get();
        $row = 2;
        foreach ($events as $event) {
            $sheet->setCellValueByColumnAndRow(1, $row, $event->client_name);
            $sheet->setCellValueByColumnAndRow(2, $row, $event->type_label);
            $sheet->setCellValueByColumnAndRow(3, $row, $event->event_date->format('d.m.Y'));
            $sheet->setCellValueByColumnAndRow(4, $row, $event->event_time ? date('H:i', strtotime($event->event_time)) : '');
            $sheet->setCellValueByColumnAndRow(5, $row, $event->people_count);
            $sheet->setCellValueByColumnAndRow(6, $row, $event->status_label);
            $sheet->setCellValueByColumnAndRow(7, $row, $event->client_phone ?? '');
            $sheet->setCellValueByColumnAndRow(8, $row, $event->client_email ?? '');
            $sheet->setCellValueByColumnAndRow(9, $row, $event->service_price);
            $sheet->setCellValueByColumnAndRow(10, $row, $event->menu_price);
            $sheet->setCellValueByColumnAndRow(11, $row, $event->total_price);
            $sheet->setCellValueByColumnAndRow(12, $row, $event->expected_profit);
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

        foreach ($headers as $i => $h) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $h);
        }

        $events = Event::with('dishes.ingredients')->orderBy('event_date', 'desc')->get();
        $row = 2;
        $totals = [0, 0, 0, 0, 0];
        foreach ($events as $event) {
            $margin = $event->total_price > 0 ? ($event->expected_profit / $event->total_price) * 100 : 0;
            $sheet->setCellValueByColumnAndRow(1, $row, $event->client_name);
            $sheet->setCellValueByColumnAndRow(2, $row, $event->type_label);
            $sheet->setCellValueByColumnAndRow(3, $row, $event->event_date->format('d.m.Y'));
            $sheet->setCellValueByColumnAndRow(4, $row, $event->people_count);
            $sheet->setCellValueByColumnAndRow(5, $row, $event->status_label);
            $sheet->setCellValueByColumnAndRow(6, $row, $event->type_price);
            $sheet->setCellValueByColumnAndRow(7, $row, $event->service_price);
            $sheet->setCellValueByColumnAndRow(8, $row, $event->menu_price);
            $sheet->setCellValueByColumnAndRow(9, $row, $event->total_price);
            $sheet->setCellValueByColumnAndRow(10, $row, $event->ingredient_cost);
            $sheet->setCellValueByColumnAndRow(11, $row, $event->expected_profit);
            $sheet->setCellValueByColumnAndRow(12, $row, round($margin, 1) . '%');
            $totals[0] += $event->service_price;
            $totals[1] += $event->menu_price;
            $totals[2] += $event->total_price;
            $totals[3] += $event->ingredient_cost;
            $totals[4] += $event->expected_profit;
            $row++;
        }

        $sheet->setCellValueByColumnAndRow(1, $row, 'ИТОГО');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValueByColumnAndRow(7, $row, $totals[0]);
        $sheet->setCellValueByColumnAndRow(8, $row, $totals[1]);
        $sheet->setCellValueByColumnAndRow(9, $row, $totals[2]);
        $sheet->setCellValueByColumnAndRow(10, $row, $totals[3]);
        $sheet->setCellValueByColumnAndRow(11, $row, $totals[4]);
        $totalMargin = $totals[2] > 0 ? ($totals[4] / $totals[2]) * 100 : 0;
        $sheet->setCellValueByColumnAndRow(12, $row, round($totalMargin, 1) . '%');

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
