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
    public function index(Request $request)
    {
        $query = Struk::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(nama_toko) like ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(nomor_struk) like ?', ["%{$search}%"]);
        }
        $struks = $query->latest()->paginate(10);
        $barangList = Barang::all();

        return view('struks.index', compact('struks', 'barangList'));
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
        $struk = Struk::findOrFail($id);

        $request->validate([
            'nama.*' => 'required|string|max:255',
            'jumlah.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0',
        ]);

        $namaArr = $request->input('nama', []);
        $jumlahArr = $request->input('jumlah', []);
        $hargaArr = $request->input('harga', []);

        $items = [];
        foreach ($namaArr as $i => $nama) {
            $items[] = [
                'nama' => $nama,
                'jumlah' => (int) ($jumlahArr[$i] ?? 0),
                'harga' => (float) ($hargaArr[$i] ?? 0),
            ];
        }

        $total = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $struk->items = json_encode($items);
        $struk->total_harga = $total;
        $struk->save();

        return redirect()->route('struks.index')->with('success', 'Item berhasil diperbarui!');
    }


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

        return redirect()->route('struks.index')->with('success', 'Item berhasil dihapus.');
    }

    public function items($id)
    {
        $struk = Struk::findOrFail($id);
        return response()->json(json_decode($struk->items, true));
    }
}
