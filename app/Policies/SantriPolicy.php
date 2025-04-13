<?php

namespace App\Policies;

use App\Models\Santri;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization; // Atau Response class

class SantriPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Ustadz & Admin bisa melihat daftar santri.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'ustadz';
        // Atau return true; saja jika Gate::before sudah menangani admin
    }

    /**
     * Determine whether the user can view the model.
     * Ustadz & Admin bisa melihat detail santri.
     * (Logic bisa diperketat: Ustadz hanya bisa lihat santri di kelasnya)
     */
    public function view(User $user, Santri $santri): bool
    {
        // Jika Gate::before sudah ada, cukup return true, karena admin sudah diizinkan
        return true; // Asumsi Ustadz bisa lihat semua detail santri
                     // Jika tidak: periksa apakah santri ini ada di kelas Ustadz tsb.
                     // return $user->role === 'ustadz' && $user->ustadz->kelas_id === $santri->kelas_id; (Contoh jika relasi ada)

    }

    /**
     * Determine whether the user can create models.
     * Hanya Admin yang bisa menambah data santri baru.
     */
    public function create(User $user): bool
    {
        // Tidak perlu cek role jika Gate::before sudah menangani admin
        // Jika tidak pakai Gate::before: return $user->role === 'admin';
        return false; // Ustadz tidak bisa create, hanya admin (ditangani Gate::before)
    }

    /**
     * Determine whether the user can update the model.
     * Hanya Admin yang bisa mengubah data santri.
     */
    public function update(User $user, Santri $santri): bool
    {
        // Hanya Admin yang boleh (ditangani Gate::before)
         return false; // Ustadz tidak bisa update
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya Admin yang bisa menghapus data santri.
     */
    public function delete(User $user, Santri $santri): bool
    {
         // Hanya Admin yang boleh (ditangani Gate::before)
         return false; // Ustadz tidak bisa delete
    }

    /**
     * Determine whether the user can restore the model.
     * (Jika menggunakan Soft Deletes) - Hanya Admin
     */
    public function restore(User $user, Santri $santri): bool
    {
         return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * (Jika menggunakan Soft Deletes) - Hanya Admin
     */
    public function forceDelete(User $user, Santri $santri): bool
    {
        return false;
    }
}