<?php

namespace App\Filament\Imports;

use App\Models\Nilai;
use App\Models\Santri;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Ustadz;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Collection;

class NilaiImporter extends Importer
{
    protected static ?string $model = Nilai::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('santri_id')
                ->label('Santri')
                ->relationship(resolveUsing: function (string $state) {
                    // Try to find by NIS first, then by name
                    $santri = Santri::where('nis', $state)->first();

                    if (!$santri) {
                        $santri = Santri::where('nama_lengkap', $state)->first();
                    }

                    return $santri;
                })
                ->requiredMapping()
                ->rules(['required'])
                ->example('S001 atau Ahmad Santoso')
                ->helperText('Masukkan NIS atau nama lengkap santri'),

            ImportColumn::make('mata_pelajaran_id')
                ->label('Mata Pelajaran')
                ->relationship(resolveUsing: 'nama')
                ->requiredMapping()
                ->rules(['required'])
                ->example('Matematika'),

            ImportColumn::make('kelas_id')
                ->label('Kelas')
                ->relationship(resolveUsing: 'nama_kelas')
                ->requiredMapping()
                ->rules(['required'])
                ->example('Kelas 1A'),

            ImportColumn::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship(resolveUsing: 'nama_tahun_ajaran')
                ->requiredMapping()
                ->rules(['required'])
                ->example('2023/2024 Semester 1'),

            ImportColumn::make('ustadz_id')
                ->label('Ustadz Pengampu')
                ->relationship(resolveUsing: 'nama_lengkap')
                ->rules(['nullable'])
                ->example('Ustadz Budi'),

            ImportColumn::make('jenis_nilai')
                ->label('Jenis Nilai')
                ->rules(['required', 'in:TUGAS,HARIAN,PROYEK,UTS,UAS,AKHIR,SIKAP'])
                ->requiredMapping()
                ->example('TUGAS')
                ->helperText('Nilai harus salah satu dari: TUGAS, HARIAN, PROYEK, UTS, UAS, AKHIR, SIKAP'),

            ImportColumn::make('nilai_angka')
                ->label('Nilai')
                ->numeric()
                ->rules(['required', 'numeric', 'min:0', 'max:100'])
                ->requiredMapping()
                ->example('85')
                ->helperText('Nilai harus berupa angka antara 0-100'),

            ImportColumn::make('nilai_predikat')
                ->label('Predikat')
                ->rules(['nullable', 'string', 'max:5'])
                ->example('A'),

            ImportColumn::make('catatan')
                ->label('Catatan')
                ->rules(['nullable', 'string'])
                ->example('Tugas dikerjakan dengan baik'),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label('Update nilai yang sudah ada')
                ->helperText('Jika dicentang, nilai yang sudah ada akan diperbarui berdasarkan data yang diimpor.'),
        ];
    }

    public function resolveRecord(): ?Nilai
    {
        // If updateExisting is checked, try to find existing record
        if ($this->options['updateExisting'] ?? false) {
            return Nilai::query()
                ->where('santri_id', $this->data['santri_id'])
                ->where('mata_pelajaran_id', $this->data['mata_pelajaran_id'])
                ->where('tahun_ajaran_id', $this->data['tahun_ajaran_id'])
                ->where('jenis_nilai', $this->data['jenis_nilai'])
                ->first() ?? new Nilai();
        }

        return new Nilai();
    }
}
