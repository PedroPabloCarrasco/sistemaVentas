<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    /**
     * Listar productos
     */
    public function index()
    {
        $productos = Producto::with('categoria')->get();

        return view('productos.index', compact('productos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::all();

        return view('productos.create', compact('categorias'));
    }

    /**
     * Guardar producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente');
    }

    /**
     * Mostrar producto
     */
    public function show(string $id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);

        return view('productos.show', compact('producto'));
    }

    /**
     * Formulario de edición
     */
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $producto = Producto::findOrFail($id);

        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Eliminar producto
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}
