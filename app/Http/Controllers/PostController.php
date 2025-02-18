<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author:id,name,last_name')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'posts' => $posts
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author_id' => 'required|exists:authors,id'
        ]);

        if ($request->image) {
            $strpos = strpos($request->image, ';');
            $sub = substr($request->image, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time().".".$ex;
            $img = Image::read($request->image)->resize(200, 200);
            $upload_path = public_path()."/upload/";
            $img->save($upload_path.$name);
            $validatedData['image'] = $name;
        }

        $post = Post::create($validatedData);

        return response()->json([
            'message' => 'Post creado exitosamente',
            'post' => $post
        ], 201);
    }

    public function show(string $id)
    {
        $post = Post::with('author')->find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post no encontrado'
            ], 404);
        }
    
        return response()->json([
            'post' => $post
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author_id' => 'required|exists:authors,id'
        ]);

        if ($request->image != $post->image) {
            $upload_path = public_path()."/upload/";
            $image = $upload_path.$post->image;
            if (file_exists($image)) {
                @unlink($image);
            }

            $strpos = strpos($request->image, ';');
            $sub = substr($request->image, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time().".".$ex;
            $img = Image::read($request->image)->resize(200, 200);
            $img->save($upload_path.$name);
            $validatedData['image'] = $name;
        }else {
            $validatedData['image'] = $post->image;
        }

        $updated = $post->update($validatedData);

        return response()->json([
        'message' => $updated ? 'Post actualizado exitosamente' : 'No se pudo actualizar el post',
        'post' => $post->fresh()
    ], $updated ? 200 : 400);
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        // Eliminar la imagen si existe
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post eliminado exitosamente'
        ], 200);
    }
}
