<?php

namespace App\Http\Controllers;

use App\Models\Bewerber;
use App\Models\Bewerbung;
use App\Models\Stelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BewerbungController extends Controller
{
    public function create()
    {
        $user     = auth()->user();
        $bewerber = Bewerber::where('UserID', $user->UserID)->firstOrFail();

        return Inertia::render('BewerbungForm', [
            'stellen'  => Stelle::online()->select(['StellenID', 'Name'])->get(),
            'bewerber' => [
                'Name'     => $bewerber->Name,
                'Vorname'  => $bewerber->Vorname,
                'Gebdatum' => $bewerber->Gebdatum?->format('Y-m-d'),
                'Strasse'  => $bewerber->Strasse,
                'Hausnr'   => $bewerber->Hausnr,
                'Plz'      => $bewerber->Plz,
                'Ort'      => $bewerber->Ort,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'StellenID'            => ['required', 'integer', 'exists:stellen,StellenID'],
            'Anschreiben'          => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'Lebenslauf'           => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'Zeugnisse'            => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'Zertifikate'          => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'Datenschutzerklaerung' => ['required', 'accepted'],
        ]);

        $user = auth()->user();

        $toBytea = fn($file) => $file
            ? ('\\x' . bin2hex(file_get_contents($file->getRealPath())))
            : null;

        $bewerbungID = DB::table('bewerbungen')->insertGetId([
            'UserID'               => $user->UserID,
            'StellenID'            => $request->input('StellenID'),
            'Anschreiben'          => $request->hasFile('Anschreiben')
                                        ? DB::raw("decode('" . bin2hex(file_get_contents($request->file('Anschreiben')->getRealPath())) . "', 'hex')")
                                        : null,
            'Lebenslauf'           => $request->hasFile('Lebenslauf')
                                        ? DB::raw("decode('" . bin2hex(file_get_contents($request->file('Lebenslauf')->getRealPath())) . "', 'hex')")
                                        : null,
            'Zeugnisse'            => $request->hasFile('Zeugnisse')
                                        ? DB::raw("decode('" . bin2hex(file_get_contents($request->file('Zeugnisse')->getRealPath())) . "', 'hex')")
                                        : null,
            'Zertifikate'          => $request->hasFile('Zertifikate')
                                        ? DB::raw("decode('" . bin2hex(file_get_contents($request->file('Zertifikate')->getRealPath())) . "', 'hex')")
                                        : null,
            'Datenschutzerklaerung' => true,
            'Status'               => Bewerbung::STATUS_EINGEGANGEN,
            'created_at'           => now(),
            'updated_at'           => now(),
        ], 'BewerbungID');

        return redirect()->route('dashboard');
    }

    public function downloadUnterlagen(Bewerbung $bewerbung)
    {
        $record = DB::table('bewerbungen')
            ->where('BewerbungID', $bewerbung->BewerbungID)
            ->select(['Anschreiben', 'Lebenslauf', 'Zeugnisse', 'Zertifikate'])
            ->first();

        $slots = [
            'Anschreiben' => $record->Anschreiben,
            'Lebenslauf'  => $record->Lebenslauf,
            'Zeugnisse'   => $record->Zeugnisse,
            'Zertifikate' => $record->Zertifikate,
        ];

        // Bytea comes back as a PHP stream resource from PDO/pgsql
        $docs = array_filter($slots, fn($v) => $v !== null);

        if (empty($docs)) {
            abort(404, 'Keine Unterlagen vorhanden.');
        }

        $read = fn($v) => is_resource($v) ? stream_get_contents($v) : $v;

        // Single file — return directly as PDF
        if (count($docs) === 1) {
            $name    = array_key_first($docs);
            $content = $read($docs[$name]);
            return response($content, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$name}.pdf\"",
            ]);
        }

        // Multiple files — bundle into a ZIP
        $tmp = tempnam(sys_get_temp_dir(), 'unterlagen_');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);

        foreach ($docs as $name => $data) {
            $zip->addFromString("{$name}.pdf", $read($data));
        }

        $zip->close();

        return response()
            ->download($tmp, "Bewerbung_{$bewerbung->BewerbungID}_Unterlagen.zip", [
                'Content-Type' => 'application/zip',
            ])
            ->deleteFileAfterSend(true);
    }

    public function updateStatus(Request $request, Bewerbung $bewerbung)
    {
        $request->validate([
            'Status' => ['required', 'string', 'in:' . implode(',', Bewerbung::STATUSES)],
        ]);

        $bewerbung->update(['Status' => $request->input('Status')]);

        return redirect()->route('dashboard');
    }
}
