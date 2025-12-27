<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PdfController extends Controller
{
    public function proxyPdf(Request $request)
    {
        $url = $request->query('url');

        if (!$url) {
            return response('URL not specified', 400);
        }

        // Ambil PDF dari backend
        $response = Http::get($url);

        if ($response->failed()) {
            return response('File not found', 404);
        }

        // Kirim ke browser dengan header CORS
        return response($response->body(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.basename($url).'"')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
