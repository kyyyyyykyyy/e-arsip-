<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function dashboard()
    {
        $documents = Document::latest()->get();
        return Inertia::render('Dashboard', [
            'documents' => $documents
        ]);
    }

    public function index(Request $request)
    {
        $query = Document::latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('document_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'Semua Arsip') {
            $query->where('category', $request->category);
        }

        return Inertia::render('Arsip/Index', [
            'documents' => $query->paginate(10)->withQueryString(),
            'filters' => $request->only(['search', 'category']) 
        ]);
    }

    public function store(Request $request)
    {
        // PENGGUNAAN VALIDASI LANGSUNG (Menggantikan StoreDocumentRequest yang lama)
        $validated = $request->validate([
            'document_number' => 'required|string|unique:documents,document_number',
            'title' => 'required|string|max:255',
            'category' => 'required|in:DPA,RKA,Renja,Laporan Bulanan,Laporan Triwulanan',
            'document_date' => 'required|date',
            'bidang' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('private/documents');

        Document::create([
            'user_id' => Auth::id(),
            'document_number' => $validated['document_number'],
            'title' => $validated['title'],
            'category' => $validated['category'],
            'document_date' => $validated['document_date'],
            'bidang' => $validated['bidang'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
        ]);

        ActivityLog::catat('Upload Arsip', 'Mengunggah dokumen perencanaan/pelaporan baru bernomor: ' . $validated['document_number']);

        return redirect()->back()->with('message', 'Dokumen SIDEPPA berhasil diarsipkan secara aman!');
    }

    public function destroy(Document $document)
    {
        $nomorSurat = $document->document_number;

        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        ActivityLog::catat('Hapus Arsip', 'Memusnahkan dokumen bernomor: ' . $nomorSurat);

        return redirect()->back()->with('message', 'Arsip dan file berhasil dimusnahkan!');
    }

    public function download(Document $document)
    {
        if (Storage::exists($document->file_path)) {
            ActivityLog::catat('Download File', 'Mendownload file fisik dari arsip bernomor: ' . $document->document_number);

            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            return Storage::download($document->file_path, $document->document_number . ' - ' . $document->title . '.' . $extension);
        }
        return back()->with('message', 'Waduh, file fisik tidak ditemukan di server!');
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|unique:documents,document_number,' . $document->id,
            'title' => 'required|string|max:255',
            'category' => 'required|in:DPA,RKA,Renja,Laporan Bulanan,Laporan Triwulanan',
            'document_date' => 'required|date',
            'bidang' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        $dataToUpdate = [
            'document_number' => $validated['document_number'],
            'title' => $validated['title'],
            'category' => $validated['category'],
            'document_date' => $validated['document_date'],
            'bidang' => $validated['bidang'],
        ];

        if ($request->hasFile('file')) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
            $dataToUpdate['file_path'] = $request->file('file')->store('private/documents');
        }

        $document->update($dataToUpdate);

        ActivityLog::catat('Edit Arsip', 'Memperbarui data dokumen bernomor: ' . $dataToUpdate['document_number']);

        return back()->with('message', 'Data arsip berhasil diperbarui!');
    }

   public function laporan(Request $request)
{
    $documents = collect([]); // Inisialisasi sebagai collection kosong
    $stats = [
        'total' => 0,
        'dpa_rka' => 0,
        'renja' => 0,
        'laporan' => 0
    ];

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $documents = Document::with('user')
            ->whereBetween('document_date', [$request->start_date, $request->end_date])
            ->orderBy('document_date', 'asc')
            ->get();

        $stats['total'] = $documents->count();
        $stats['dpa_rka'] = $documents->whereIn('category', ['DPA', 'RKA'])->count();
        $stats['renja'] = $documents->where('category', 'Renja')->count();
        $stats['laporan'] = $documents->whereIn('category', ['Laporan Bulanan', 'Laporan Triwulanan'])->count();
    }

    return Inertia::render('Laporan/Index', [
        'documents' => $documents,
        'filters' => $request->only(['start_date', 'end_date']),
        'stats' => $stats
    ]);
}

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        ActivityLog::catat('Ekspor Excel', 'Mengunduh laporan rekapitulasi SIDEPPA tanggal ' . $request->start_date . ' s/d ' . $request->end_date);

        $documents = Document::with('user')
            ->whereBetween('document_date', [$request->start_date, $request->end_date])
            ->orderBy('document_date', 'asc')
            ->get();

        $fileName = 'Laporan_SIDEPPA_' . $request->start_date . '_sd_' . $request->end_date . '.csv';

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Nomor Dokumen', 'Nama Kegiatan / Judul', 'Kategori', 'Tanggal', 'Bidang', 'Diupload Oleh');

        $callback = function() use($documents, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns, ';');

            $row = 1;
            foreach ($documents as $doc) {
                $nomor_dokumen_text = " " . $doc->document_number;
                $tanggal_text = " " . date('d-m-Y', strtotime($doc->document_date));

                fputcsv($file, array(
                    $row++,
                    $nomor_dokumen_text,
                    $doc->title,
                    $doc->category,
                    $tanggal_text,
                    $doc->bidang,
                    $doc->user->name
                ), ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}