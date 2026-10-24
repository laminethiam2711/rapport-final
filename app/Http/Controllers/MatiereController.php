<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatiereController extends Controller
{
    public function index(Request $request)
    {
        return Matiere::with(['classe', 'enseignant.user'])
            ->when($request->filled('classe_id'), fn ($q) => $q->where('classe_id', $request->classe_id))
            ->when($request->filled('semestre'), fn ($q) => $q->where('semestre', $request->semestre))
            ->orderBy('classe_id')->orderBy('semestre')->orderBy('libelle')
            ->get();
    }

    public function store(Request $request)
    {
        $matiere = Matiere::create($this->valider($request));

        return response()->json($matiere->load(['classe', 'enseignant.user']), 201);
    }

    public function show(Matiere $matiere)
    {
        return $matiere->load(['classe', 'enseignant.user']);
    }

    public function update(Request $request, Matiere $matiere)
    {
        $matiere->update($this->valider($request, $matiere));

        return $matiere->load(['classe', 'enseignant.user']);
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();

        return response()->json(['message' => 'Matière supprimée.']);
    }

    private function valider(Request $request, ?Matiere $matiere = null): array
    {
        return $request->validate([
            'code' => [
                'required', 'string', 'max:20',
                Rule::unique('matieres')
                    ->where('classe_id', $request->input('classe_id'))
                    ->ignore($matiere?->id),
            ],
            'libelle' => 'required|string|max:150',
            'coefficient' => 'required|integer|min:1|max:10',
            'semestre' => 'required|integer|in:1,2',
            'classe_id' => 'required|exists:classes,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
        ], [
            'code.unique' => 'Ce code de matière existe déjà dans cette classe.',
        ]);
    }
}
