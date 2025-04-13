<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CreateSantri extends CreateRecord
{
    protected static string $resource = SantriResource::class;

    protected function afterCreate(): void
    {
        // Get the created santri record
        $santri = $this->record;

        // Show notification with the generated NIS
        if ($santri && $santri->nis) {
            Notification::make()
                ->title('NIS Dibuat Otomatis')
                ->body("NIS {$santri->nis} telah dibuat otomatis untuk santri {$santri->nama_lengkap}")
                ->success()
                ->send();
        }

        // Create a user account for the santri
        try {
            DB::transaction(function () use ($santri) {
                // Generate a random password
                $password = Str::random(10);

                // Create email from nama_lengkap if not provided
                $email = strtolower(str_replace(' ', '.', $santri->nama_lengkap)) . '@santri.bum.test';

                // Create user with santri role
                $user = User::create([
                    'name' => $santri->nama_lengkap,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'santri',
                    'santri_id' => $santri->id,
                ]);

                // Set santri as confirmed since it's created by admin
                $santri->is_confirmed = true;

                // Update the santri record with the user_id
                $santri->user_id = $user->id;
                $santri->save();

                // Show notification with the user credentials
                Notification::make()
                    ->title('Akun Santri Dibuat')
                    ->body("Akun untuk santri {$santri->nama_lengkap} telah dibuat dengan email: {$email} dan password: {$password}")
                    ->success()
                    ->persistent()
                    ->send();
            });
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Membuat Akun Santri')
                ->body("Terjadi kesalahan saat membuat akun untuk santri {$santri->nama_lengkap}: {$e->getMessage()}")
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
