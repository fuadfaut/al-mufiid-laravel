<?php

namespace App\Filament\Resources;

use App\Filament\Exports\NilaiExporter;
use App\Filament\Imports\NilaiImporter;
use App\Filament\Resources\NilaiResource\Pages;
use App\Filament\Resources\NilaiResource\RelationManagers;
use App\Filament\Resources\SantriResource\RelationManagers\NilaiRelationManager;
use App\Filament\Resources\NilaiResource\RelationManagers\SantriRelationManager;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Ustadz; // Jika ingin mencatat ustadz penginput/pengampu
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get; // Untuk form dependency

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar'; // Atau clipboard-document-list
    protected static ?string $navigationGroup = 'Manajemen Akademik';
    protected static ?int $navigationSort = 5; // Di bagian akhir manajemen akademik

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Filter Tahun Ajaran dulu untuk memudahkan
                // Pastikan field 'ustadz_id' diisi dengan benar saat create/edit
                // Bisa di-default ke ustadz yang login jika logis
                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama_tahun_ajaran') // Sesuaikan 'nama_tahun_ajaran'
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->default(fn() => \App\Models\TahunAjaran::where('aktif', true)->first()?->id)
                    ->label('Tahun Ajaran'),

                Select::make('kelas_id') // Asumsi kelas_id ini untuk memfilter santri saja
                    ->relationship('kelas', 'nama_kelas') // Pastikan relasi 'kelas' ada di model Nilai atau via Santri
                    ->searchable()
                    ->preload()
                    ->live()
                    ->nullable() // Atau required jika filter santri wajib via kelas
                    ->label('Filter Kelas Santri (Opsional)'), // Beri label jelas jika ini HANYA filter

                Select::make('santri_id')
                     // Ganti 'santri' dengan nama relasi yg benar di model Nilai
                    ->relationship('santri', 'nama_lengkap', modifyQueryUsing: function (Builder $query, Get $get) {
                        $kelasId = $get('kelas_id');
                        if ($kelasId) {
                            // Asumsi Santri punya kelas_id
                            return $query->where('kelas_id', $kelasId)->where('aktif', true);
                        }
                         // Tampilkan semua santri aktif jika kelas tidak dipilih
                        return $query->where('aktif', true);
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Nama Santri'),

                Select::make('mata_pelajaran_id')
                    ->relationship('mataPelajaran', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Mata Pelajaran'),

                Select::make('ustadz_id')
                     // Pastikan relasi 'ustadz' ada di model Nilai
                     ->relationship('ustadz', 'nama_lengkap') // Asumsi 'nama_lengkap' ada di model Ustadz
                     ->searchable()
                     ->preload()
                     // *** Default ke Ustadz yang sedang login (jika login sbg Ustadz) ***
                     ->default(function () {
                         $user = Auth::user();
                         if ($user && $user->role === 'ustadz') {
                             // Asumsi relasi 'ustadz' ada di model User
                             return $user->ustadz?->id;
                         }
                         return null; // Admin atau jika user tidak terkait ustadz
                     })
                      // *** Sembunyikan & Disable jika Ustadz login (dia hanya bisa input untuk dirinya) ***
                     ->disabled(fn() => Auth::user()?->role === 'ustadz')
                     ->hidden(fn() => Auth::user()?->role === 'ustadz')
                     ->required() // Harus ada ustadz pengampu
                     ->label('Ustadz Pengampu/Penginput'),

                 Select::make('jenis_nilai')
                    ->options([
                        'TUGAS' => 'Tugas', 'HARIAN' => 'Penilaian Harian', 'PROYEK' => 'Proyek',
                        'UTS' => 'PTS', 'UAS' => 'PAS', 'AKHIR' => 'Nilai Akhir Rapor', 'SIKAP' => 'Penilaian Sikap',
                    ])
                    ->required()->searchable()->label('Jenis Penilaian'),

                 TextInput::make('nilai_angka')
                     ->required()->maxLength(10)->label('Nilai / Predikat'), // Hapus numeric() jika bisa non-angka

                Textarea::make('catatan')
                    ->label('Catatan Tambahan (Opsional)')->nullable()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
         // ... Definisi table Anda (tidak perlu diubah untuk filtering ini) ...
          return $table
                ->columns([
                    TextColumn::make('santri.nis')->label('NIS')->searchable()->sortable()->toggleable(),
                    TextColumn::make('santri.nama_lengkap')->label('Santri')->searchable()->sortable(),
                    TextColumn::make('mataPelajaran.nama')->label('Mapel')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('kelas.nama_kelas')->label('Kelas Santri') // Asumsi Nilai bisa akses Kelas via Santri
                        ->getStateUsing(fn (Nilai $record) => $record->santri?->kelas?->nama_kelas ?? '-') // Ambil via relasi santri
                        ->sortable()->searchable()->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('tahunAjaran.nama_tahun_ajaran')->label('Thn Ajaran')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('jenis_nilai')->label('Jenis')->searchable()->sortable(),
                    TextColumn::make('nilai_angka')->label('Nilai')->searchable()->sortable(),
                    TextColumn::make('ustadz.nama_lengkap')->label('Pengampu/Input')->sortable()->searchable()->toggleable(), // Relasi ustadz
                    TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                ])
                ->defaultSort('updated_at', 'desc')
                ->filters([
                     SelectFilter::make('tahun_ajaran')
                        ->relationship('tahunAjaran', 'nama_tahun_ajaran') // Sesuaikan field
                        ->searchable()->preload()
                        ->default(fn() => \App\Models\TahunAjaran::where('aktif', true)->first()?->id)
                        ->label('Tahun Ajaran'),

                    // Filter berdasarkan kelas SANTRI, bukan nilai itu sendiri
                    SelectFilter::make('kelas')
                        ->relationship('santri.kelas', 'nama_kelas') // Filter via relasi santri
                        ->label('Filter Kelas Santri')
                        ->searchable(),

                    SelectFilter::make('mata_pelajaran')
                        ->relationship('mataPelajaran', 'nama') ->label('Mapel')->searchable(),
                    SelectFilter::make('santri')
                        ->relationship('santri', 'nama_lengkap') ->label('Nama Santri')->searchable(),
                    SelectFilter::make('jenis_nilai') ->options([
                            'TUGAS' => 'Tugas', 'HARIAN' => 'PH', 'PROYEK' => 'Proyek', 'UTS' => 'PTS',
                            'UAS' => 'PAS', 'AKHIR' => 'Akhir', 'SIKAP' => 'Sikap',
                         ])->label('Jenis Nilai'),
                      // Filter berdasarkan ustadz pengampu (berguna untuk admin)
                     SelectFilter::make('ustadz')
                         ->relationship('ustadz', 'nama_lengkap') // Relasi 'ustadz' di model Nilai
                         ->searchable()
                         ->preload()
                         ->label('Ustadz Pengampu'),
                ])
                ->actions([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                         // Hanya tampilkan tombol Edit jika Policy mengizinkan
                         ->visible(fn (Nilai $record): bool => Auth::user()->can('update', $record)),
                    Tables\Actions\DeleteAction::make()
                         // Hanya tampilkan tombol Delete jika Policy mengizinkan
                         ->visible(fn (Nilai $record): bool => Auth::user()->can('delete', $record)),
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make()
                            // Pastikan bulk delete juga dicek policy-nya jika perlu
                            // Namun seringkali bulk delete hanya untuk admin.
                            ->visible(fn (): bool => Auth::user()->role === 'admin'),
                    ]),
                ]);
    }

    /**
     * Modifikasi query dasar untuk tabel Nilai.
     * Jika user adalah Ustadz, filter berdasarkan ustadz_id yang terkait.
     */
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $query = parent::getEloquentQuery(); // Ambil query dasar

        // Periksa apakah user valid dan memiliki peran 'ustadz'
        if ($user && $user->role === 'ustadz') {
            // Ambil ID Ustadz yang terkait dengan User yang login
            // Ganti 'ustadz' dengan nama relasi yang benar di model User Anda
            // Gunakan optional chaining (?) untuk menghindari error jika relasi tidak ada
            $ustadzId = $user->ustadz?->id;

            // Jika ID Ustadz ditemukan, filter query Nilai
            if ($ustadzId) {
                // Hanya tampilkan nilai yang kolom 'ustadz_id'-nya sama dengan $ustadzId
                return $query->where('ustadz_id', $ustadzId);
            } else {
                // Jika user 'ustadz' tapi tidak terhubung ke record Ustadz,
                // jangan tampilkan data nilai apapun untuk mencegah akses tidak sah.
                return $query->whereRaw('1 = 0'); // Query yang selalu mengembalikan hasil kosong
            }
        }

        // Jika user adalah 'admin' atau peran lain (yang seharusnya tidak ada akses),
        // atau jika user tidak terautentikasi (seharusnya sudah ditangani middleware),
        // kembalikan query tanpa filter tambahan (admin lihat semua).
        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNilais::route('/'),
            'create' => Pages\CreateNilai::route('/create'),
            'view' => Pages\ViewNilai::route('/{record}'),
            'edit' => Pages\EditNilai::route('/{record}/edit'),
        ];
    }
}