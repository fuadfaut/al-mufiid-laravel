<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MataPelajaranResource\Pages;
use App\Models\MataPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select; // Kita akan gunakan ini untuk 'kategori'
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn; // <-- Ganti jadi BadgeColumn atau gunakan color pada TextColumn untuk kategori
use Filament\Tables\Filters\SelectFilter; // Untuk filter kategori

class MataPelajaranResource extends Resource
{
    protected static ?string $model = MataPelajaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Manajemen Akademik';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Gunakan 'nama' sesuai Model
                TextInput::make('nama')
                    ->label('Nama Mata Pelajaran')
                    ->required()
                    ->unique(ignoreRecord: true) // Pastikan unik, abaikan record saat edit
                    ->maxLength(150),

                // Gunakan 'kategori' sesuai Model dengan Select
                Select::make('kategori')
                    ->label('Kategori Mata Pelajaran')
                    ->options([
                        // Sesuaikan OPSI ini dengan Kategori TPA Anda
                        'Qiraah' => 'Qira\'ah (Contoh: Iqro\', Al-Quran)',
                        'Hafalan' => 'Hafalan (Contoh: Surat Pendek, Doa)',
                        'Tajwid' => 'Tajwid',
                        'Fiqih' => 'Fiqih Ibadah',
                        'Adab' => 'Adab & Akhlak',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->searchable() // Jika opsi banyak, aktifkan search
                    ->native(false), // Gunakan styling Filament

                // 'deskripsi' sudah sesuai
                Textarea::make('deskripsi')
                      ->label('Deskripsi (Opsional)')
                      ->nullable()
                      ->columnSpanFull(), // Agar mengisi lebar penuh
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom 'nama' sudah benar
                TextColumn::make('nama')
                    ->label('Nama Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                // Kolom untuk 'kategori', gunakan Badge untuk tampilan lebih baik
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge() // Tampilkan sebagai badge
                    ->color(fn (string $state): string => match ($state) { // Warna berbeda per kategori
                        'Qiraah' => 'primary',
                        'Hafalan' => 'success',
                        'Tajwid' => 'info',
                        'Fiqih' => 'warning',
                        'Adab' => 'gray',
                        'Lainnya' => 'danger',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),

                // Kolom 'deskripsi'
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50) // Batasi teks yang tampil
                    ->tooltip(fn($record) => $record->deskripsi) // Tampilkan full teks saat hover (jika panjang)
                    ->toggleable(isToggledHiddenByDefault: false), // Biarkan tampil default

                // Kolom timestamp
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
                // Filter berdasarkan 'kategori'
                SelectFilter::make('kategori')
                    ->options([
                        'Qiraah' => 'Qira\'ah',
                        'Hafalan' => 'Hafalan',
                        'Tajwid' => 'Tajwid',
                        'Fiqih' => 'Fiqih Ibadah',
                        'Adab' => 'Adab & Akhlak',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->label('Filter Kategori'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                 Tables\Actions\DeleteAction::make(), // Tambahkan delete action jika belum ada
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
            // Relasi mungkin tidak diperlukan langsung di sini jika Nilai dikelola terpisah
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMataPelajarans::route('/'),
            'create' => Pages\CreateMataPelajaran::route('/create'),
            'view' => Pages\ViewMataPelajaran::route('/{record}'),
            'edit' => Pages\EditMataPelajaran::route('/{record}/edit'),
        ];
    }
}