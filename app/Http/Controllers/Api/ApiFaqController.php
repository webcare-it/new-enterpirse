<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiFaqController extends Controller
{
    public function faqs()
    {
        $faqs = \App\Models\Admin\Faq::get();

        return response()->json([
            'success' => true,
            'data' => [
               'faqs' => $faqs->map(function ($faq) {
                    return [
                        'id' => $faq->id,
                        'question' => $faq->question,
                        'answer' => $faq->answer,
                    ];
            }),
            ]
        ]);
    }
}
