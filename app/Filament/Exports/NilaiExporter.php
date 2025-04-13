<?php

namespace App\Filament\Exports;

use App\Models\Nilai;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Illuminate\Database\Eloquent\Builder;

class NilaiExporter extends Exporter
{
    protected static ?string $model = Nilai::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('santri.nis')
                ->label('NIS'),
            ExportColumn::make('santri.nama_lengkap')
                ->label('Nama Santri'),
            ExportColumn::make('mataPelajaran.nama')
                ->label('Mata Pelajaran'),
            ExportColumn::make('kelas.nama_kelas')
                ->label('Kelas'),
            ExportColumn::make('tahunAjaran.nama_tahun_ajaran')
                ->label('Tahun Ajaran'),
            ExportColumn::make('jenis_nilai')
                ->label('Jenis Nilai'),
            ExportColumn::make('nilai_angka')
                ->label('Nilai'),
            ExportColumn::make('nilai_predikat')
                ->label('Predikat'),
            ExportColumn::make('catatan')
                ->label('Catatan'),
            ExportColumn::make('ustadz.nama_lengkap')
                ->label('Ustadz Pengampu'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
            ExportColumn::make('updated_at')
                ->label('Tanggal Diperbarui'),
        ];
    }

    public static function getFormats(): array
    {
        return [
            'xlsx',
            'csv',
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        // Add any query modifications here if needed
        return $query;
    }
}
