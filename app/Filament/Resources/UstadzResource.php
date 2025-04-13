<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UstadzResource\Pages;

use App\Models\Ustadz;
use App\Models\User; // Import User model jika ada relasi
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker; // Jika ada tanggal lahir dll

use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;


use Filament\Forms\Components\Hidden; // Untuk menyimpan state

use Filament\Forms\Get;
use Filament\Forms\Set;


class UstadzResource extends Resource
{
    protected static ?string $model = Ustadz::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; // Atau ikon lain yang sesuai
    protected static ?string $navigationGroup = 'Manajemen SDM'; // Atau 'Manajemen Akademik'
    protected static ?int $navigationSort = 2; // Setelah User atau Santri

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')->required(),
                            TextInput::make('email')->email()->required()->unique(User::class, 'email'),
                            TextInput::make('password')
                                ->password()
                                ->suffixAction(
                                    Action::make('toggle_password')
                                        ->icon('heroicon-m-eye')
                                        ->iconButton()
                                        ->tooltip('Toggle visibility')
                                        ->extraAttributes(['class' => 'ml-2'])
                                        ->action(function (callable $set, $state) {
                                            $set('password_visible', ! $state);
                                        })
                                )
                                ->type(function (Get $get) {
                                    return $get('password_visible') ? 'text' : 'password';
                                })
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(),
                        ])
                        ->label('Akun User Login')
                        ->nullable(),
                    
                TextInput::make('nip')
                    ->label('NIP/ID Ustadz')
                    ->unique(ignoreRecord: true)
                    ->nullable()
                    ->maxLength(50),

                TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),
    
                            
                 Select::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->nullable(), // Atau required
                TextInput::make('tempat_lahir')->maxLength(100)->nullable(),
                DatePicker::make('tanggal_lahir')->nullable(),
                TextInput::make('bidang_studi') // Bidang keahlian utama
                    ->label('Bidang Studi Utama')
                    ->nullable()
                    ->maxLength(150),
                 // Bisa juga relasi ManyToMany ke MataPelajaran jika mengajar banyak mapel
                 // Select::make('mataPelajarans')
                 //    ->relationship('mataPelajarans', 'nama_mapel')
                 //    ->multiple()
                 //    ->preload()
                 //    ->searchable()
                 //    ->label('Mata Pelajaran yang Diampu'),
                Textarea::make('alamat')
                    ->columnSpanFull()
                    ->nullable(),
                TextInput::make('no_hp')
                    ->label('No. Handphone')
                    ->tel()
                    ->nullable(),
                DatePicker::make('tanggal_bergabung')
                     ->default(now())
                     ->nullable(),
                Toggle::make('aktif') // Status keaktifan ustadz
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')->label('NIP/ID')->searchable()->sortable(),
                TextColumn::make('nama_lengkap')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Akun Login')->sortable()->searchable(), // Tampilkan nama user terkait
                TextColumn::make('jenis_kelamin')
                     ->formatStateUsing(fn (?string $state): string => $state === 'L' ? 'Laki-laki' : ($state === 'P' ? 'Perempuan' : '-'))
                     ->sortable(),
                TextColumn::make('bidang_studi')->label('Bidang Studi')->searchable(),
                TextColumn::make('no_hp')->label('No. HP'),
                IconColumn::make('aktif')->boolean()->sortable(),
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
                Tables\Filters\TernaryFilter::make('aktif')
                     ->label('Status Aktif')
                     ->boolean()
                     ->trueLabel('Aktif')
                     ->falseLabel('Tidak Aktif')
                     ->native(false),
                // Filter berdasarkan User yang terhubung
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Filter User'),
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
            // Jika Ustadz menjadi Wali Kelas, bisa tampilkan relasi ke Kelas di sini
            // Contoh: KelasRelationManager::class (jika ada KelasRelationManager)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUstadzs::route('/'),
            'create' => Pages\CreateUstadz::route('/create'),
            'view' => Pages\ViewUstadz::route('/{record}'),
            'edit' => Pages\EditUstadz::route('/{record}/edit'),
        ];
    }
}