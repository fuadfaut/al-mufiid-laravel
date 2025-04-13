<?php

namespace App\Observers;

use App\Models\Santri;
use Carbon\Carbon;

class SantriObserver
{
    /**
     * Handle the Santri "creating" event.
     * This will be triggered before a new Santri is saved to the database.
     */
    public function creating(Santri $santri): void
    {
        // Only generate NIS if it's not already set
        if (empty($santri->nis)) {
            $santri->nis = $this->generateNIS();
        }
    }

    /**
     * Generate a unique NIS (Nomor Induk Santri)
     * Format: [Year of Entry (2 digits)][Month of Entry (2 digits)][Sequential Number (4 digits)]
     * Example: 2408 (Year 2024, Month 08) + 0001 = 24080001
     */
    protected function generateNIS(): string
    {
        // Get current year and month
        $now = Carbon::now();
        $yearPrefix = $now->format('y'); // 2-digit year (e.g., 24 for 2024)
        $monthPrefix = $now->format('m'); // 2-digit month (e.g., 08 for August)
        $prefix = $yearPrefix . $monthPrefix;
        
        // Find the highest sequential number for the current year-month prefix
        $latestSantri = Santri::where('nis', 'like', $prefix . '%')
            ->orderBy('nis', 'desc')
            ->first();
        
        if ($latestSantri) {
            // Extract the sequential number from the latest NIS
            $sequentialNumber = (int) substr($latestSantri->nis, 4);
            $sequentialNumber++;
        } else {
            // Start with 1 if no existing NIS with this prefix
            $sequentialNumber = 1;
        }
        
        // Format the sequential number to 4 digits with leading zeros
        $formattedNumber = str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
        
        // Combine prefix and sequential number
        return $prefix . $formattedNumber;
    }
}
