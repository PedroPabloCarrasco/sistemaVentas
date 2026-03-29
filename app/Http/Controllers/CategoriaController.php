<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeCategoriaRequest;
use App\Models\Caracteristicas;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(storeCategoriaRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {

                // ✅ Crear características
                $caracteristicas = Caracteristicas::create([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                ]);

                // ✅ Crear categoría vinculada
                Categoria::create([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'caracteristicas_id' => $caracteristicas->id
                ]);
            });

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría creada correctamente');
        } catch (\Exception $e) {

            return redirect()->route('categorias.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.show', compact('categoria'));
    }

    public function edit(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $categoria = Categoria::findOrFail($id);

        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente');
    }
}
