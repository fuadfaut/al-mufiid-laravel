<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash; // Import Hash
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker; // Untuk email_verified_at
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Select; // <-- Import Select
use Filament\Tables\Filters\SelectFilter; // <-- Import SelectFilter
use Illuminate\Database\Eloquent\Builder; // Untuk query scope

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true) // Abaikan record saat ini ketika edit
                    ->maxLength(255),

                // === Tambahkan Select untuk Role ===
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'ustadz' => 'Ustadz',
                        'santri' => 'Santri',
                    ])
                    ->required()
                    ->native(false) // Gunakan style Filament
                    ->label('Role Pengguna'),
                // ===================================

                TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create') // Hanya wajib saat membuat user baru
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Hash password sebelum disimpan
                    ->dehydrated(fn ($state) => filled($state)) // Hanya proses jika password diisi (agar tidak menimpa password saat edit jika kosong)
                    ->maxLength(255)
                    ->helperText('Kosongkan jika tidak ingin mengubah password saat edit.'),
                // Forms\Components\DateTimePicker::make('email_verified_at'), // Mungkin tidak perlu diedit manual
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),

                // === Tampilkan Kolom Role ===
                TextColumn::make('role')
                    ->label('Role')
                    ->badge() // Tampilkan sebagai badge
                    ->color(fn (string $state): string => match ($state) { // Warna badge berdasarkan role
                        'admin' => 'danger', // Merah
                        'ustadz' => 'warning', // Kuning/Oranye
                        'santri' => 'success', // Hijau
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                // ==============================

                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan default
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
                // === Tambahkan Filter Role ===
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'ustadz' => 'Ustadz',
                        'santri' => 'Santri',
                    ])
                    ->label('Filter by Role'),
                // ===========================
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                     ->before(function (User $record, Tables\Actions\DeleteAction $action) {
                         // Pencegahan: Jangan biarkan admin menghapus dirinya sendiri
                         if ($record->id === auth()->id()) {
                             // Kirim notifikasi error
                             \Filament\Notifications\Notification::make()
                                ->title('Aksi Ditolak')
                                ->body('Anda tidak dapat menghapus akun Anda sendiri.')
                                ->danger()
                                ->send();
                             // Batalkan aksi delete
                             $action->cancel();
                         }
                         // Pencegahan: Jangan biarkan admin terakhir dihapus (opsional tapi bagus)
                          if ($record->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
                              \Filament\Notifications\Notification::make()
                                 ->title('Aksi Ditolak')
                                 ->body('Tidak dapat menghapus admin terakhir.')
                                 ->danger()
                                 ->send();
                              $action->cancel();
                          }
                     }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        // Tambahkan pencegahan untuk bulk delete jika perlu
                         ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $loggedInAdminId = auth()->id();
                            $containsSelf = $records->contains(fn($record) => $record->id === $loggedInAdminId);
                            // $containsLastAdmin = $records->contains(fn($record) => $record->role === 'admin') && User::where('role', 'admin')->count() <= $records->where('role', 'admin')->count();

                            if ($containsSelf) {
                                \Filament\Notifications\Notification::make()
                                   ->title('Aksi Ditolak')
                                   ->body('Anda tidak dapat menghapus akun Anda sendiri dalam aksi massal.')
                                   ->danger()
                                   ->send();
                                return; // Hentikan aksi
                            }
                            // if ($containsLastAdmin) {
                            //     \Filament\Notifications\Notification::make()
                            //        ->title('Aksi Ditolak')
                            //        ->body('Aksi massal ini akan menghapus admin terakhir.')
                            //        ->danger()
                            //        ->send();
                            //     return; // Hentikan aksi
                            // }

                            // Jika lolos validasi, lanjutkan penghapusan
                            $records->each->delete();

                             \Filament\Notifications\Notification::make()
                                   ->title('Pengguna Dihapus')
                                   ->body('Pengguna yang dipilih berhasil dihapus (kecuali yang dilindungi).')
                                   ->success()
                                   ->send();

                        }),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Opsional: Jika Anda ingin membatasi user mana yang bisa dilihat/diedit oleh Admin
    // Misalnya, Admin tidak bisa mengedit super admin lain (jika ada konsep itu)
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->where('role', '!=', 'superadmin'); // Contoh
    // }
}