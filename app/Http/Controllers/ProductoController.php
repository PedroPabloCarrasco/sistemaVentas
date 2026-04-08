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
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'codigo_barra' => 'required|string|max:255|unique:productos,codigo_barra',
        ]);

        // ✅ Estado automático (INT)
        $estado = $request->stock > 0 ? 1 : 0;

        Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'estado' => $estado,
            'codigo_barra' => $request->codigo_barra
        ]);

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
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'codigo_barra' => 'required|string|max:255|unique:productos,codigo_barra,' . $producto->id,
        ]);

        // ✅ Estado automático (INT) — CORREGIDO
        $estado = $request->stock > 0 ? 1 : 0;

        $producto->update([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'estado' => $estado,
            'codigo_barra' => $request->codigo_barra
        ]);

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
