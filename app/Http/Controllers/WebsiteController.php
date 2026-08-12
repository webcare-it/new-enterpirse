<?php

namespace App\Http\Controllers;

use App\Models\Admin\ProductPrice;
use App\Models\Page;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
	public function header(Request $request)
	{
		return view('backend.website_settings.header');
	}
	public function footer(Request $request)
	{
		$lang = $request->lang;
		return view('backend.website_settings.footer', compact('lang'));
	}
	public function pages(Request $request)
	{
		$pages = \App\Models\Page::oldest('position')->get();
		return view('backend.website_settings.pages.index', compact('pages'));
	}
	public function priceFix(Request $request)
	{

		$prices = ProductPrice::all();

		foreach ($prices as $price) {
			$price->update([
				'discount' => 0,
				'discount_type' => 'flat',
			]);
		}
		$prices = ProductPrice::all();
		$updatedCount = 0;

		foreach ($prices as $price) {
			$regular = (float) $price->regular_price;
			$discount = (float) $price->discount;
			$calculatedSale = $regular;

			if ($price->discount_type == 'flat') {
				$calculatedSale = max(0, $regular - $discount);
			} elseif ($price->discount_type == 'percent') {
				$calculatedSale = max(0, $regular * (1 - $discount / 100));
			}

			if ((float) $price->sale_price !== $calculatedSale) {
				$price->sale_price = $calculatedSale;
				$price->save();
				$updatedCount++;
			}
		}

		return $prices = ProductPrice::all();
	}
	public function appearance(Request $request)
	{
		return view('backend.website_settings.appearance');
	}

	public function sort(Request $request)
	{
		foreach ($request->positions as $item) {
			Page::where('id', $item['id'])
				->update(['position' => $item['position']]);
		}

		return response()->json(['success' => true]);
	}
}
