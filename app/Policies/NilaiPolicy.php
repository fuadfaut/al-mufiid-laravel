<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization; // Atau Response

class NilaiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Admin dan Ustadz bisa melihat daftar nilai (mungkin difilter nantinya).
     */
    public function viewAny(User $user): bool
    {
        return true; // Keduanya bisa lihat list (Admin via Gate::before)
    }

    /**
     * Determine whether the user can view the model.
     * Admin bisa lihat semua detail nilai.
     * Ustadz bisa lihat detail nilai (mungkin dibatasi untuk santri/inputannya saja).
     */
    public function view(User $user, Nilai $nilai): bool
    {
         // Logic bisa diperketat: Ustadz hanya bisa lihat nilai santri di kelasnya
         // atau nilai yang dia input.
        //  if ($user->role === 'ustadz') {
             // Contoh: hanya jika ustadz yg input
             // return $nilai->user_id === $user->id;
             // Atau: Cek kelas santri
             // return $user->ustadz->kelas_id === $nilai->santri->kelas_id;
        //  }
         return true; // Asumsi sementara ustadz bisa lihat semua detail
    }

    /**
     * Determine whether the user can create models.
     * Admin dan Ustadz bisa input nilai.
     */
    public function create(User $user): bool
    {
        // Jika tidak pakai Gate::before:
        // return $user->role === 'admin' || $user->role === 'ustadz';
        return true; // Keduanya bisa (Admin via Gate::before)
    }

    /**
     * Determine whether the user can update the model.
     * Admin bisa update semua nilai.
     * Ustadz mungkin hanya bisa update nilai yang dia input sendiri.
     */
    public function update(User $user, Nilai $nilai): bool
    {
        if ($user->role === 'ustadz') {
            // Izinkan Ustadz update hanya jika dia yang menginput nilai tersebut
            return $nilai->user_id === $user->id; // Asumsi ada kolom user_id di tabel nilai
        }
        // Admin sudah diizinkan oleh Gate::before, Ustadz diatur di atas.
        return false; // Jika user bukan admin dan bukan ustadz yg input
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya Admin yang bisa menghapus nilai.
     */
    public function delete(User $user, Nilai $nilai): bool
    {
        return false; // Ustadz tidak bisa delete (Admin via Gate::before)
    }

    // Implementasikan restore / forceDelete jika perlu (biasanya hanya Admin)
}