<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


class StrukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Struk::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(nama_toko) like ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(nomor_struk) like ?', ["%{$search}%"]);
        }

        // Use pagination for better performance on large datasets
        $struks = $query->latest()->paginate(10);

        return view('struks.index', compact('struks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangList = Barang::all(); // Fetch all Barang for dropdowns
        return view('struks.create', compact('barangList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Consider StrukStoreRequest here
    {
        // Validate incoming data from the form
        $validatedData = $request->validate([
            'nama_toko' => 'required|string|max:255', // Added string|max:255
            'nomor_struk' => 'required|string|max:255', // Added string|max:255
            'tanggal_struk' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0', // Added min:0
            'foto_struk' => 'nullable|image|max:2048' // Foto tidak wajib, maksimal 2MB
        ]);

        $fotoFilename = null;
        // Check if foto_struk file was uploaded
        if ($request->hasFile('foto_struk')) {
            // Store the photo in 'struk_foto' directory within 'storage/app/public'
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
            // Get only the filename from the stored path
            $fotoFilename = basename($fotoPath);
        }

        // Create a new entry in the Struk table
        Struk::create([
            'nama_toko' => $validatedData['nama_toko'],
            'nomor_struk' => $validatedData['nomor_struk'],
            'tanggal_struk' => $validatedData['tanggal_struk'],
            'items' => json_encode($validatedData['items']), // Store items array as JSON string
            'total_harga' => $validatedData['total_harga'],
            'foto_struk' => $fotoFilename // Store photo filename
        ]);

        // Redirect back to the struk index page with a success message
        return redirect()->route('struks.index')->with('success', 'Struk berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Struk $struk)
    {
        // Decode the items column from JSON string to PHP array/object
        $struk->items = json_decode($struk->items);
        // Fetch all Barang for the dropdown in the edit form
        $barangList = Barang::all();
        return view('struks.edit', compact('struk', 'barangList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Struk $struk) // Consider StrukUpdateRequest here
    {
        // Validate incoming data for update
        $validatedData = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'nomor_struk' => 'required|string|max:255',
            'tanggal_struk' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'foto_struk' => 'nullable|image|max:2048'
        ]);

        // Check if a new photo was uploaded
        if ($request->hasFile('foto_struk')) {
            // If there's an old photo, delete it from storage
            if ($struk->foto_struk) {
                Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
            }
            // Store the new photo and update the filename in the model
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
            $validatedData['foto_struk'] = basename($fotoPath); // Assign to validatedData
        } else {
            // If no new file, retain the existing one
            $validatedData['foto_struk'] = $struk->foto_struk;
        }

        // Update struk data
        $struk->update([
            'nama_toko' => $validatedData['nama_toko'],
            'nomor_struk' => $validatedData['nomor_struk'],
            'tanggal_struk' => $validatedData['tanggal_struk'],
            'items' => json_encode($validatedData['items']), // Store items array as JSON string
            'total_harga' => $validatedData['total_harga'],
            'foto_struk' => $validatedData['foto_struk'], // Ensure photo filename is updated
        ]);

        // Redirect back to the struk index page with a success message
        return redirect()->route('struks.index')->with('success', 'Struk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Struk $struk)
    {
        // If there's a photo associated with the struk, delete it from storage
        if ($struk->foto_struk) {
            Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
        }
        // Delete the struk entry from the database
        $struk->delete();
        // Redirect back to the struk index page with a success message
        return redirect()->route('struks.index')->with('success', 'Struk berhasil dihapus!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Struk $struk)
    {
        // Decode the items column from JSON string to PHP array/object for display
        $struk->items = json_decode($struk->items);
        return view('struks.show', compact('struk'));
    }

    /**
     * Export all Struk data to an Excel file.
     */
    public function exportExcel()
    {
        $struks = Struk::all(); // Fetch all struk data
        $spreadsheet = new Spreadsheet(); // Create a new Spreadsheet object
        $sheet = $spreadsheet->getActiveSheet(); // Get the active sheet

        // Add column headers
        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Items', 'Total Harga'], NULL, 'A1');

        $row = 2; // Start from the second row for data
        foreach ($struks as $struk) {
            // Convert JSON items to a readable string
            $items = collect(json_decode($struk->items))->map(fn($item) => "{$item->nama} ({$item->jumlah} x {$item->harga})")->implode(', ');
            // Add struk data to the sheet
            $sheet->fromArray([$struk->id, $struk->nama_toko, $struk->nomor_struk, $struk->tanggal_struk, $items, $struk->total_harga], NULL, "A$row");
            $row++; // Move to the next row
        }

        // Prepare writer for XLSX format
        $writer = new Xlsx($spreadsheet);
        // Set HTTP headers for Excel file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data_struks.xlsx"');
        // Save the file to output (will be downloaded by the browser)
        $writer->save('php://output');
        exit; // Stop script execution
    }

    /**
     * Export all Struk data to a CSV file.
     */
    public function exportCSV()
    {
        $struks = Struk::all(); // Fetch all struk data
        $spreadsheet = new Spreadsheet(); // Create a new Spreadsheet object
        $sheet = $spreadsheet->getActiveSheet(); // Get the active sheet

        // Add column headers
        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Items', 'Total Harga'], NULL, 'A1');

        $row = 2; // Start from the second row for data
        foreach ($struks as $struk) {
            // Convert JSON items to a readable string
            $items = collect(json_decode($struk->items))->map(fn($item) => "{$item->nama} ({$item->jumlah} x {$item->harga})")->implode(', ');
            // Add struk data to the sheet
            $sheet->fromArray([$struk->id, $struk->nama_toko, $struk->nomor_struk, $struk->tanggal_struk, $items, $struk->total_harga], NULL, "A$row");
            $row++; // Move to the next row
        }

        // Prepare writer for CSV format
        $writer = new Csv($spreadsheet);
        // Set HTTP headers for CSV file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="data_struks.csv"');
        // Save the file to output (will be downloaded by the browser)
        $writer->save('php://output');
        exit; // Stop script execution
    }

    /**
     * Update items within a specific Struk.
     * This method seems intended to handle updates of existing items and adding new ones dynamically.
     */
    public function updateItems(Request $request, $id)
    {
        // Find struk by ID or throw 404 if not found
        $struk = Struk::findOrFail($id);

        // Validasi untuk item yang ada
        $request->validate([
            'nama.*' => 'required|string|max:255',
            'jumlah.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0',
            'item_index.*' => 'nullable|integer',
            // Validasi untuk item baru (opsional jika diisi)
            'nama_new.*' => 'nullable|string|max:255',
            'jumlah_new.*' => 'nullable|integer|min:1',
            'harga_new.*' => 'nullable|numeric|min:0',
        ]);

        // Decode existing items to array
        $existingItems = json_decode($struk->items, true) ?? [];

        // === Update atau simpan ulang item yang sudah ada ===
        $namaArr = $request->input('nama', []);
        $jumlahArr = $request->input('jumlah', []);
        $hargaArr = $request->input('harga', []);
        $indexArr = $request->input('item_index', []);

        foreach ($namaArr as $i => $nama) {
            $item = [
                'nama' => $nama,
                'jumlah' => (int) $jumlahArr[$i],
                'harga' => (float) $hargaArr[$i],
            ];

            // Cek apakah index disediakan dan valid
            if (isset($indexArr[$i]) && isset($existingItems[$indexArr[$i]])) {
                $existingItems[$indexArr[$i]] = $item;
            } else {
                $existingItems[] = $item;
            }
        }

        // === Tambahkan item baru jika ada ===
        $namaBaru = $request->input('nama_new', []);
        $jumlahBaru = $request->input('jumlah_new', []);
        $hargaBaru = $request->input('harga_new', []);

        foreach ($namaBaru as $i => $nama) {
            // Hanya tambahkan jika nama, jumlah, dan harga tidak kosong/null
            if ($nama && isset($jumlahBaru[$i], $hargaBaru[$i])) {
                $existingItems[] = [
                    'nama' => $nama,
                    'jumlah' => (int) $jumlahBaru[$i],
                    'harga' => (float) $hargaBaru[$i],
                ];
            }
        }

        // Simpan perubahan ke database
        $struk->items = json_encode($existingItems);
        $struk->save();

        return redirect()->route('struks.index', ['search' => request('search')])
            ->with('success', 'Item struk berhasil diperbarui.');



        // Merge updated existing items with newly added items
        $mergedItems = array_values(array_merge($existingItems, $processedItems));

        // Recalculate total price based on the merged and validated items
        $total = collect($mergedItems)->sum(function ($item) {
            return $item['jumlah'] * $item['harga'];
        });

        // Update the 'items' and 'total_harga' columns in the Struk model
        $struk->items = json_encode($mergedItems); // Save back as JSON string
        $struk->total_harga = $total;
        $struk->save(); // Save changes to the database

        // Redirect back to the struk index page with a success message
        return redirect()->route('struks.index')->with('success', 'Item berhasil diperbarui!');
    }


    /**
     * Add a single new item to a specific Struk.
     * This method might be redundant if `updateItems` is designed to handle both updates and additions.
     * Consider if this separate endpoint is truly necessary.
     */
    public function addItem(Request $request, $id)
    {
        $struk = Struk::findOrFail($id);

        // Validate the new item data
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        // Decode existing items, default to an empty array if null
        $items = json_decode($struk->items, true) ?? [];

        // Add the new item to the array
        $items[] = $request->only(['nama', 'jumlah', 'harga']);
        // Encode the items array back to a JSON string
        $struk->items = json_encode($items);
        // Recalculate total price
        $struk->total_harga = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $struk->save(); // Save changes
        return redirect()->route('struks.index')->with('success', 'Item baru berhasil ditambahkan.');
    }

    /**
     * Delete an item from a specific Struk by its index.
     */
    public function deleteItem($id, $index)
    {
        $struk = Struk::findOrFail($id);
        // Decode existing items
        $items = json_decode($struk->items, true);

        // Delete the item at the specified index if it exists
        if (isset($items[$index])) {
            unset($items[$index]); // Remove the element from the array
            $items = array_values($items); // Reset array keys to be sequential
            $struk->items = json_encode($items); // Encode back to JSON
            // Recalculate total price after item deletion
            $struk->total_harga = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);
            $struk->save(); // Save changes
        }

        // Redirect back to the struk index page (or the struk edit page if preferred)
        // If you want to redirect to the edit page, make sure your route accepts 'edit' as a parameter.
        return redirect()->route('struks.index')->with('success', 'Item berhasil dihapus.');
        // Alternative to redirect back to edit page:
        // return redirect()->route('struks.edit', $struk->id)->with('success', 'Item berhasil dihapus.');
    }
}
