<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeCategoriaRequest;
use App\Models\Caracteristicas;
use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Mostrar listado de categorías
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Guardar nueva categoría
     */
    public function store(storeCategoriaRequest $request)
    {
        try {
            DB::beginTransaction();
            Categoria::create($request->validated());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categorias.index')
                ->with('error', 'Error al crear categoría: ' . $e->getMessage());
        }

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada correctamente');
    }

    /**
     * Mostrar una categoría (opcional)
     */
    public function show(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Formulario de edición
     */
    public function edit(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Actualizar categoría
     */
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

    /**
     * Eliminar categoría
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente');
    }
}
