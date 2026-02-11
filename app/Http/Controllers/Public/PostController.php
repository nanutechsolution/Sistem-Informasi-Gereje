<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Menampilkan daftar semua berita (Arsip) dengan Filter Kategori
     */
    public function index(Request $request)
    {
        $posts = Post::with('author')
            ->where('is_published', true)
            // Tambahkan logika filter kategori jika ada request
            ->when($request->kategori, function ($query) use ($request) {
                return $query->where('kategori', $request->kategori);
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString(); // Penting: Agar filter tidak hilang saat pindah halaman (page 2, dst)

        return view('public.posts.index', compact('posts'));
    }

    /**
     * Menampilkan detail satu berita (Single Post)
     */
    public function show($slug)
    {
        // Cari berita berdasarkan slug
        $post = Post::with('author')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Ambil 2 berita lain untuk rekomendasi (Related Posts)
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('is_published', true)
            // Jika ingin rekomendasi yang sejenis, aktifkan baris ini:
            // ->where('kategori', $post->kategori) 
            ->latest('published_at')
            ->limit(2)
            ->get();

        return view('public.posts.show', compact('post', 'relatedPosts'));
    }
}