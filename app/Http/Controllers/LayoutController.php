<?php

namespace App\Http\Controllers;

use App\InvoiceLayouts;
use Illuminate\Http\Request;
use Response;

class LayoutController extends Controller {

    private $httpHeaders = [
        'X-Frame-Options'         => 'deny',
        'X-XSS-Protection'        => '1; mode=block',
        'X-Content-Type-Options'  => 'nosniff',
        'Content-Security-Policy' => 'default-src \'none\''
    ];

    public function sendResponse($message) {

        return Response::json($message, 200, $this->httpHeaders);
    }

    public function sendError($error, $code = 404) {
        return Response::json($error, $code, $this->httpHeaders);
    }

    public function saveLayout(Request $request) {
        $layout = new InvoiceLayouts;
        $layout->data = $request->getContent();
        $layout->save();

        return $this->sendResponse('SUCCESS');
    }

    public function getLayouts() {
        $data = [];
        $layouts = InvoiceLayouts::all();
        foreach ($layouts as $layout) {
            $data[] = json_decode($layout->data);
        }

        return json_encode($data);
    }
}
