<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAjaranResource\Pages;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;;
use Filament\Forms\Components\Select;      // <-- Benarkan ini


class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;

    // Ensure this property is declared only once
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Manajemen Akademik';
    protected static ?int $navigationSort = 4; // Atur urutan jika perlu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input untuk Tahun Ajaran (contoh: 2024/2025)
                TextInput::make('tahun')
                    ->required() // <-- Wajib diisi
                    ->maxLength(9) // Sesuaikan panjang maks jika perlu (misal "YYYY/YYYY")
                    ->placeholder('Contoh: 2024/2025')
                    ->label('Tahun Ajaran'),

                // Input untuk Nama Tahun Ajaran (opsional)
                TextInput::make('nama_tahun_ajaran')
                    ->required() // <-- Wajib diisi
                    ->maxLength(50) // Sesuaikan panjang maks jika perlu
                    ->placeholder('Contoh: Tahun Ajaran 2024/2025')
                    ->label('Nama Tahun Ajaran'),

                // Select untuk Semester
                Select::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil', // Sesuaikan valuenya jika di DB berbeda
                        'Genap' => 'Genap',
                    ])
                    ->required() // <-- Wajib diisi
                    ->native(false) // Gunakan style Filament
                    ->label('Semester'),

                // Toggle untuk Status Aktif
                Toggle::make('aktif')
                    ->required() // <-- Wajib diisi (karena default DB mungkin tidak ada)
                    ->default(false) // Defaultnya non-aktif saat membuat baru
                    ->label('Aktifkan Semester Ini?'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('semester')->sortable(),
                tables\Columns\TextColumn::make('nama_tahun_ajaran')->searchable()->sortable(),
                // Menampilkan nama tahun ajaran dengan accessor
                Tables\Columns\TextColumn::make('nama_tahun_ajaran')
                    ->label('Nama Tahun Ajaran')
                    ->getStateUsing(function (TahunAjaran $record) {
                        return $record->nama_tahun_ajaran;
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('aktif')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                 Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
             ->defaultSort('tahun', 'desc'); // Urutkan berdasarkan tahun terbaru
    }

    public static function getRelations(): array
    {
        return [
            // Mungkin relasi ke Kelas atau Nilai yang ada di tahun ajaran ini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTahunAjarans::route('/'),
            'create' => Pages\CreateTahunAjaran::route('/create'),
            'view' => Pages\ViewTahunAjaran::route('/{record}'),
            'edit' => Pages\EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}