<?php

namespace App\Http\Controllers;

use App\Models\Article;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index');
    }

    public function detail($slug)
    {
        $article = Article::with(['categories', 'tags'])->where('slug', $slug)->firstOrFail();

        return view('blog.detail', compact('article'));
    }
}
