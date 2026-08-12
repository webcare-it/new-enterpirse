<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CombinedOrder;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use Auth;
use Session;
use Redirect;

class AuthorizeNetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth'); // later enable it when needed user login while payment
    }

    public function handleonlinepay(Request $request)
    {
        return redirect()->route('home');
    }
}
