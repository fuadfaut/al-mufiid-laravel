<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Layout Sederhana</title>
    <!-- Sementara hilangkan semua CSS/JS/Fonts -->
</head>
<body>
    <h1>Awal Layout</h1>

    <main>
        Ini sebelum slot.
        <hr>
         {{-- Slot seharusnya ada di sini --}}
        {{ $slot }}
         <hr>
         Ini sesudah slot.
    </main>

    <h1>Akhir Layout</h1>
    <!-- Hilangkan script Vite untuk sementara -->
</body>
</html>