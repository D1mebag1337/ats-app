<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Stelle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class StelleController extends Controller
{
    public function index()
    {
        $stellen = Stelle::online()
            ->select(['StellenID', 'Name', 'Kurzbeschreibung', 'Arbeitsorte', 'ImageID'])
            ->with('image:ImageID,Alternativtext')
            ->get();

        return Inertia::render('ApplicationOverview', ['stellen' => $stellen]);
    }

    private function syncAndGetImages(): \Illuminate\Database\Eloquent\Collection
    {
        $dir   = public_path('stelle-images');
        $files = File::exists($dir)
            ? collect(File::files($dir))->map(fn($f) => $f->getFilename())->sort()->values()
            : collect();

        foreach ($files as $filename) {
            Image::firstOrCreate(['Alternativtext' => $filename], ['ImageBinary' => null]);
        }

        return Image::select(['ImageID', 'Alternativtext'])
            ->whereIn('Alternativtext', $files)
            ->get();
    }

    public function create()
    {
        return Inertia::render('StelleForm', [
            'stelle' => null,
            'images' => $this->syncAndGetImages(),
        ]);
    }

    private function decodeJsonFields(Request $request): void
    {
        foreach (['Aufgaben', 'Voraussetzungen'] as $field) {
            $value = $request->input($field);
            if (is_string($value)) {
                $request->merge([$field => json_decode($value, true) ?? []]);
            }
        }
    }

    public function store(Request $request)
    {
        $this->decodeJsonFields($request);

        $request->validate([
            'Name'             => ['required', 'string', 'max:255'],
            'Kurzbeschreibung' => ['required', 'string'],
            'Arbeitsorte'      => ['required', 'string', 'max:255'],
            'Aufgaben'         => ['required', 'array', 'min:1'],
            'Aufgaben.*'       => ['required', 'string'],
            'Voraussetzungen'  => ['required', 'array', 'min:1'],
            'Voraussetzungen.*' => ['required', 'string'],
            'Online'           => ['nullable'],
            'ImageID'          => ['nullable', 'integer'],
        ]);

        $imageID = $request->input('ImageID');

Stelle::create([
            'Name'             => $request->input('Name'),
            'Kurzbeschreibung' => $request->input('Kurzbeschreibung', ''),
            'Beschreibung'     => $request->input('Kurzbeschreibung', ''),
            'Arbeitsorte'      => $request->input('Arbeitsorte', ''),
            'Aufgaben'         => $request->input('Aufgaben'),
            'Voraussetzungen'  => $request->input('Voraussetzungen'),
            'Online'           => filter_var($request->input('Online'), FILTER_VALIDATE_BOOLEAN),
            'ImageID'          => $imageID,
        ]);

        return redirect()->route('dashboard');
    }

    public function edit(Stelle $stelle)
    {
        return Inertia::render('StelleForm', [
            'stelle' => $stelle,
            'images' => $this->syncAndGetImages(),
        ]);
    }

    public function update(Request $request, Stelle $stelle)
    {
        $this->decodeJsonFields($request);

        $request->validate([
            'Name'             => ['required', 'string', 'max:255'],
            'Kurzbeschreibung' => ['required', 'string'],
            'Arbeitsorte'      => ['required', 'string', 'max:255'],
            'Aufgaben'         => ['required', 'array', 'min:1'],
            'Aufgaben.*'       => ['required', 'string'],
            'Voraussetzungen'  => ['required', 'array', 'min:1'],
            'Voraussetzungen.*' => ['required', 'string'],
            'Online'           => ['nullable'],
            'ImageID'          => ['nullable', 'integer'],
        ]);

        $imageID = $request->input('ImageID', $stelle->ImageID);

$stelle->update([
            'Name'             => $request->input('Name'),
            'Kurzbeschreibung' => $request->input('Kurzbeschreibung', $stelle->Kurzbeschreibung),
            'Arbeitsorte'      => $request->input('Arbeitsorte', $stelle->Arbeitsorte),
            'Aufgaben'         => $request->input('Aufgaben'),
            'Voraussetzungen'  => $request->input('Voraussetzungen'),
            'Online'           => filter_var($request->input('Online'), FILTER_VALIDATE_BOOLEAN),
            'ImageID'          => $imageID,
        ]);

        return redirect()->route('dashboard');
    }

    public function serveImage(Image $image)
    {
        abort_if(! $image->Alternativtext, 404);

        return redirect('/stelle-images/' . $image->Alternativtext);
    }
}
