<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Filament\Resources\SantriResource\RelationManagers;
// use App\Filament\Resources\SantriResource\RelationManagers\NilaiRelationManager; // Jika Anda meng-comment out, tidak perlu di-use
use App\Models\Santri;
use App\Models\Kelas; // Import Kelas model
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn; // Untuk boolean
use Filament\Tables\Filters\SelectFilter; // Untuk filter
use App\Models\TahunAjaran; // Pastikan sudah di-import
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf; // Untuk cetak PDF
use App\Http\Controllers\Santri\DashboardController; // Untuk cetak PDF

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Manajemen Akademik';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        // --- FORM DEFINITION (Tetap sama) ---
        return $form
            ->schema([
                // ... Isi form Anda ...
                 TextInput::make('nis')
                     ->label('NIS')
                     ->unique(ignoreRecord: true)
                     ->disabled(fn ($operation) => $operation === 'create')
                     ->dehydrated(fn ($operation) => $operation !== 'create')
                     ->helperText('NIS akan dibuat otomatis saat data disimpan'),
                 TextInput::make('nama_lengkap')->required()->maxLength(255),
                 TextInput::make('nama_panggilan')->maxLength(100),
                 Select::make('jenis_kelamin')
                     ->options(['L' => 'Laki-laki','P' => 'Perempuan',])->required(),
                 TextInput::make('tempat_lahir')->maxLength(100),
                 DatePicker::make('tanggal_lahir'),
                 Select::make('kelas_id')
                     ->relationship('kelas', 'nama_kelas') // Pastikan relasi 'kelas' ada
                     ->searchable()->preload()->label('Kelas/Kelompok'),
                 TextInput::make('nama_ayah')->maxLength(255),
                 TextInput::make('nama_ibu')->maxLength(255),
                 Textarea::make('alamat')->columnSpanFull(),
                 TextInput::make('no_hp_ortu')->label('No. HP Orang Tua')->tel(),
                 DatePicker::make('tanggal_masuk')->default(now()),
                 Toggle::make('aktif')->default(true),
                 Toggle::make('is_confirmed')
                     ->label('Konfirmasi Pendaftaran')
                     ->helperText('Santri hanya dapat mengakses dashboard jika sudah dikonfirmasi')
                     ->default(false),
            ]);
        // --- END OF FORM DEFINITION ---
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 // --- KOLOM TABEL (Tetap sama) ---
                 TextColumn::make('nis')->label('NIS')->searchable()->sortable(),
                 TextColumn::make('nama_lengkap')->searchable()->sortable(),
                 TextColumn::make('kelas.nama_kelas')->label('Kelas')->sortable(), // Membutuhkan relasi 'kelas'
                 TextColumn::make('jenis_kelamin')
                      // Gunakan match atau null coalescing jika bisa null
                      ->formatStateUsing(fn (?string $state): string => match($state) {
                          'L' => 'Laki-laki',
                          'P' => 'Perempuan',
                          default => '-',
                      })->sortable(),
                 TextColumn::make('no_hp_ortu')->label('No HP Ortu'),
                 IconColumn::make('aktif')->boolean()->sortable(),
                 IconColumn::make('is_confirmed')
                     ->boolean()
                     ->label('Terkonfirmasi')
                     ->sortable(),
                 TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                 TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                 // --- END OF KOLOM TABEL ---
            ])
            ->filters([
                 // --- FILTER TABEL (Tetap sama) ---
                 SelectFilter::make('kelas')
                     ->relationship('kelas', 'nama_kelas') // Pastikan relasi 'kelas' ada
                     ->label('Filter Kelas'),
                 Tables\Filters\TernaryFilter::make('aktif')
                     ->label('Status Aktif')->boolean()->trueLabel('Aktif')->falseLabel('Tidak Aktif')->native(false),
                 Tables\Filters\TernaryFilter::make('is_confirmed')
                     ->label('Status Konfirmasi')
                     ->boolean()
                     ->trueLabel('Terkonfirmasi')
                     ->falseLabel('Belum Dikonfirmasi')
                     ->native(false),
                 // --- END OF FILTER TABEL ---
            ])
            ->actions([
                 // === Tetap gunakan visible() untuk konsistensi dan kejelasan ===
                 Tables\Actions\ViewAction::make()
                     ->visible(fn (Santri $record): bool => Auth::user()->can('view', $record)),
                 Tables\Actions\EditAction::make()
                     ->visible(fn (Santri $record): bool => Auth::user()->can('update', $record)),

                // ======= UPDATE URL DI DALAM ACTION GROUP INI =======
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\Action::make('previewRapor')
                        ->label('Lihat Rapor')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                         // === PERBAIKI BAGIAN INI ===
                        ->url(function (Santri $record): string {
                            $activeTA = TahunAjaran::where('aktif', true)->latest('id')->first();
                            if (!$activeTA) { return '#'; }
                            // Gunakan nama route 'admin.rapor.preview' dan parameter yg benar
                            return route('admin.rapor.preview', [
                                'santri' => $record->id,
                                'tahun_ajaran' => $activeTA->id // Passing ID untuk Route Model Binding
                            ]);
                        }, shouldOpenInNewTab: true)
                        ->disabled(fn (): bool => !TahunAjaran::where('aktif', true)->exists())
                        ->tooltip(function(Santri $record): string { // Tambahkan parameter $record
                            $activeTA = TahunAjaran::where('aktif', true)->latest('id')->first();
                            // Cek jika TahunAjaran memiliki 'nama_tahun_ajaran' atau format manual
                             $ta_label = $activeTA ? ($activeTA->nama_tahun_ajaran ?? ($activeTA->tahun . ' - ' . $activeTA->semester)) : null;
                            return $activeTA
                                ? 'Lihat preview rapor ' . ($record->nama_panggilan ?? $record->nama_lengkap) . ' (' . $ta_label . ')'
                                : 'Tidak ada Tahun Ajaran Aktif';
                        }),
                        // ->visible(...) // Tambahkan jika perlu check policy spesifik


                    Tables\Actions\Action::make('cetakRapor')
                        ->label('Cetak PDF')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        // === PERBAIKI BAGIAN INI ===
                        ->url(function (Santri $record): string {
                            $activeTA = TahunAjaran::where('aktif', true)->latest('id')->first();
                            if (!$activeTA) { return '#'; }
                            // Gunakan nama route 'admin.rapor.cetak' dan parameter yg benar
                            return route('admin.rapor.cetak', [
                                'santri' => $record->id,
                                'tahun_ajaran' => $activeTA->id // Passing ID untuk Route Model Binding
                            ]);
                        })
                        ->openUrlInNewTab()
                        ->disabled(fn(): bool => !TahunAjaran::where('aktif', true)->exists())
                        ->tooltip(function(Santri $record): string { // Tambahkan parameter $record
                            $activeTA = TahunAjaran::where('aktif', true)->latest('id')->first();
                            $ta_label = $activeTA ? ($activeTA->nama_tahun_ajaran ?? ($activeTA->tahun . ' - ' . $activeTA->semester)) : null;
                            return $activeTA
                                ? 'Cetak rapor PDF ' . ($record->nama_panggilan ?? $record->nama_lengkap) . ' (' . $ta_label . ')'
                                : 'Tidak ada Tahun Ajaran Aktif';
                        }),
                         // ->visible(...) // Tambahkan jika perlu check policy spesifik

                    // Delete Action bisa diletakkan di sini atau di luar grup
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (Santri $record): bool => Auth::user()->can('delete', $record)),

                ]) // End Action Group
                // ======= SELESAI MODIFIKASI URL =======
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                     Tables\Actions\DeleteBulkAction::make()
                         ->visible(fn (): bool => Auth::user()->can('deleteAny', Santri::class)),
                ]),
            ]);
    }

    /**
     * Modifikasi query dasar untuk tabel Santri. (Tetap Sama)
     */
    public static function getEloquentQuery(): Builder
    {
        // ... Logika filtering Anda sudah benar ...
        $user = Auth::user();
        $query = parent::getEloquentQuery();

        if ($user && $user->role === 'ustadz') {
             // Cek jika user punya relasi 'ustadz' sebelum akses properti
            $ustadzModel = $user->ustadz; // Ambil model ustadz (jika ada)

            if ($ustadzModel) {
                 $ustadzId = $ustadzModel->id; // Ambil ID ustadz
                // Cari ID kelas yang dipegang ustadz ini sebagai wali (asumsi Kelas punya ustadz_id)
                $kelasIds = Kelas::where('ustadz_id', $ustadzId)->pluck('id')->toArray();

                if (!empty($kelasIds)) {
                    return $query->whereIn('kelas_id', $kelasIds);
                } else {
                     return $query->whereRaw('1 = 0'); // Tidak jadi wali kelas manapun
                }
            } else {
                 // User Ustadz tapi tidak terhubung ke record Ustadz
                 return $query->whereRaw('1 = 0');
             }
        }
        return $query; // Admin lihat semua
    }

    public static function getRelations(): array
    {
        // --- Tetap sama ---
        // Pastikan class NilaiRelationManager benar-benar ada jika tidak di-comment
        return [
             // RelationManagers\NilaiRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
         // --- Tetap sama ---
        return [
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'view' => Pages\ViewSantri::route('/{record}'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
        ];
    }
}