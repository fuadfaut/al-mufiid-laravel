<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers; // Untuk relation manager
use App\Filament\Resources\KelasResource\RelationManagers\SantriRelationManager; // Import SantriRelationManager
use App\Models\Kelas;
use App\Models\Ustadz; // Import Ustadz
use App\Models\TahunAjaran; // Import TahunAjaran
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

// Tambahkan Relation Manager jika belum dibuat (opsional tapi berguna)
// php artisan make:filament-relation-manager KelasResource santris nis --generate

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library'; // Atau 'heroicon-o-user-group'
    protected static ?string $navigationGroup = 'Manajemen Akademik';
    protected static ?int $navigationSort = 2; // Setelah Santri

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_kelas')
                    ->required()
                    ->maxLength(100),
                TextInput::make('tingkat') // Misal: 1, 2, 3 atau A, B, C
                    ->required() // Atau nullable tergantung kebutuhan
                    ->maxLength(10),
                // Relasi ke Ustadz (sebagai Wali Kelas)
                Select::make('ustadz_id')
                    ->relationship('ustadz', 'nama_lengkap') // Asumsi nama relasi di model Kelas adalah 'ustadz'
                    ->searchable()
                    ->preload()
                    ->label('Wali Kelas')
                    ->nullable(), // Bisa jadi belum ditentukan
                // Relasi ke Tahun Ajaran
                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'semester') // Asumsi relasi 'tahunAjaran'
                    ->required()
                    ->searchable()
                    ->preload()
                    // Mungkin default ke tahun ajaran aktif?
                    ->default(fn() => TahunAjaran::where('aktif', true)->first()?->id)
                    ->label('Tahun Ajaran'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')->searchable()->sortable(),
                TextColumn::make('tingkat')->searchable()->sortable(),
                TextColumn::make('ustadz.nama_lengkap')->label('Wali Kelas')->sortable()->searchable(), // Sesuaikan dengan nama relasi
                TextColumn::make('tahunAjaran.semester')->label('Tahun Ajaran')->sortable()->searchable(),
                // Hitung jumlah santri di kelas (perlu 'withCount' atau relasi hasMany 'santris')
                TextColumn::make('santris_count')->counts('santris')->label('Jumlah Santri')->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan Tahun Ajaran
                SelectFilter::make('tahun_ajaran')
                    ->relationship('tahunAjaran', 'semester')
                    ->label('Filter Tahun Ajaran'),
                // Filter berdasarkan Wali Kelas
                SelectFilter::make('ustadz') // Sesuaikan dengan nama relasi
                    ->relationship('ustadz', 'nama_lengkap')
                    ->searchable()
                    ->label('Filter Wali Kelas'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tampilkan daftar santri yang ada di kelas ini
            //RelationManagers\SantrisRelationManager::class,
            SantriRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'), // View akan menampilkan relation manager
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}