<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiAboutController extends Controller
{
    public function about()
    {
        $sectionIds = json_decode(get_setting('about_sections'), true) ?? [];
        $counterItems = \App\Models\BusinessSetting::where('type', 'counter_items')->first();

        $aboutQuery = \App\Models\Admin\About::query();
        if (!empty($sectionIds)) {
            $aboutQuery->whereIn('id', $sectionIds);
        }
        $aboutItems = $aboutQuery->get();

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $aboutItems->map(function ($item) {
                    return [
                        'id'             => $item->id,
                        'title'          => $item->title,
                        'sub_title'      => $item->sub_title,
                        'description'    => $item->description,
                        'image'          => $item->image ? uploaded_asset($item->image) : null,
                        'image_position' => $item->image_position,
                    ];
                }),
                'counters' => $counterItems ? json_decode($counterItems->value, true) : [],
            ]
        ]);
    }
}
