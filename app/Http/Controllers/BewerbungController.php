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
            'Anschreiben'          => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'Lebenslauf'           => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'Zeugnisse'            => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'Zertifikate'          => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
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
        ]);

        return redirect()->route('dashboard');
    }
}
