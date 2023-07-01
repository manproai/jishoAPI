<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JishoController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->query('keyword');
        $page = $request->query('page');

        $response = Http::get('https://jisho.org/api/v1/search/words', [
            'keyword' => $keyword,
            'page' => $page
        ]);

        $data = $response->json();

        $definitions = $this->extract_definitions($data);

        return response()->json($definitions);
    }

    private function extract_definitions($data) {
        $results = [];
        $wordNumber = 1;
        $results = array_merge($results, [$data['data'][0]['japanese']]);
        foreach($data['data'][0]['senses'] as $word_data) {
            $results = array_merge($results, [$word_data['english_definitions']]);
            $wordNumber++;
        }

        
        return $results;
    }    
}
