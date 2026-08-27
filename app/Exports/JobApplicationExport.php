<?php

namespace App\Exports;

use App\Models\Job;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JobApplicationExport extends DefaultValueBinder implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithCustomValueBinder,
    WithEvents,
    WithTitle
{
    protected Job $job;
    protected array $filters;

    // Kolom yang HARUS dipaksa jadi teks (biar tidak jadi notasi ilmiah)
    protected array $forceTextColumns = ['D', 'G']; // NIK, No HP/WA

    protected array $statusLabel = [
        'pending'   => 'Menunggu',
        'review'    => 'Review',
        'interview' => 'Interview',
        'accepted'  => 'Diterima',
        'rejected'  => 'Ditolak',
    ];

    public function __construct(Job $job, array $filters = [])
    {
        $this->job = $job;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = $this->job->applications()->with(['applicantProfile.user']);

        if (!empty($this->filters['status'])) {
            $query->whereIn('status', $this->filters['status']);
        }

        if (!empty($this->filters['tanggal_dari'])) {
            $query->whereDate('tanggal_melamar', '>=', Carbon::parse($this->filters['tanggal_dari']));
        }

        if (!empty($this->filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_melamar', '<=', Carbon::parse($this->filters['tanggal_sampai']));
        }

        return $query->latest('tanggal_melamar')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pelamar',
            'Email',
            'NIK',
            'Pendidikan',
            'Jurusan',
            'No. HP/WA',
            'Tanggal Melamar',
            'Status',
            'Catatan HRD',
            'Diterima',
            'Ditolak',
        ];
    }

    public function map($app): array
    {
        static $no = 0;
        $no++;

        $profile = $app->applicantProfile;

        return [
            $no,
            $profile->nama,
            $profile->user->email ?? '-',
            $profile->nik ?? '-',
            $profile->pendidikan,
            $profile->jurusan ?? '-',
            $profile->no_hp_wa ?? '-',
            $app->tanggal_melamar->translatedFormat('d M Y'),
            $this->statusLabel[$app->status] ?? $app->status,
            $app->catatan_hrd ?? '-',
            $app->status === 'accepted' ? '✓' : '',
            $app->status === 'rejected' ? '✓' : '',
        ];
    }

    /**
     * Dipanggil setiap kali sebuah cell mau ditulis.
     * Paksa kolom NIK & No HP/WA selalu jadi teks murni,
     * supaya Excel tidak otomatis mengubahnya jadi angka/notasi ilmiah.
     */
    public function bindValue(Cell $cell, $value)
    {
        if (in_array($cell->getColumn(), $this->forceTextColumns) && $value !== null) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function title(): string
    {
        return 'Data Pelamar';
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'L';
                $lastRow = $sheet->getHighestRow();

                // 1. Sisipkan baris judul + baris keterangan filter di paling atas
                $sheet->insertNewRowBefore(1, 2); // sisipkan 2 baris: judul & sub-judul filter

                // Baris 1: Judul utama
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', 'Data Pelamar - ' . $this->job->judul);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Baris 2: Keterangan filter (status & rentang tanggal), tampil hanya jika ada filter
                $filterText = $this->buildFilterText();
                $sheet->mergeCells("A2:{$lastColumn}2");
                $sheet->setCellValue('A2', $filterText);
                $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)
                    ->getColor()->setRGB('666666');
                $sheet->getStyle('A2')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(2)->setRowHeight($filterText ? 18 : 5);

                // Setelah insert 2 baris, header pindah ke baris 3, data mulai baris 4
                $headerRow = 3;
                $lastRow += 2;

                // 2. Style header (rata tengah, bold, background abu-abu)
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E9ECEF'],
                    ],
                ]);
                $sheet->getRowDimension($headerRow)->setRowHeight(22);

                // 3. Rata tengah untuk kolom No, Tanggal, Status, Diterima, Ditolak
                foreach (['A', 'H', 'I', 'K', 'L'] as $col) {
                    $sheet->getStyle("{$col}{$headerRow}:{$col}{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // 4. Border tipis untuk seluruh tabel (header + data)
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '999999'],
                        ],
                    ],
                ]);

                // 5. Freeze pane supaya header tetap terlihat saat scroll
                $sheet->freezePane("A" . ($headerRow + 1));

                // 6. Setup halaman untuk PRINT
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($headerRow, $headerRow);

                $sheet->getPageMargins()
                    ->setTop(0.6)->setBottom(0.6)
                    ->setLeft(0.5)->setRight(0.5);

                $sheet->getPageSetup()->setHorizontalCentered(true);
            },
        ];
    }

    protected function buildFilterText(): string
    {
        $parts = [];

        if (!empty($this->filters['status'])) {
            $labels = array_map(
                fn($key) => $this->statusLabel[$key] ?? $key,
                $this->filters['status']
            );
            $parts[] = 'Status: ' . implode(', ', $labels);
        }

        $dari = $this->filters['tanggal_dari'] ?? null;
        $sampai = $this->filters['tanggal_sampai'] ?? null;

        if ($dari && $sampai) {
            $parts[] = 'Tanggal: ' . Carbon::parse($dari)->translatedFormat('d M Y')
                . ' - ' . Carbon::parse($sampai)->translatedFormat('d M Y');
        } elseif ($dari) {
            $parts[] = 'Tanggal: mulai ' . Carbon::parse($dari)->translatedFormat('d M Y');
        } elseif ($sampai) {
            $parts[] = 'Tanggal: sampai ' . Carbon::parse($sampai)->translatedFormat('d M Y');
        }

        return implode('  |  ', $parts);
    }
}
