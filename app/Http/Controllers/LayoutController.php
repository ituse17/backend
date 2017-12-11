<?php

namespace App\Http\Controllers;

use App\InvoiceLayouts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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

    public function clearDatabase(){
        // Remove the tables.
        DB::unprepared("SET FOREIGN_KEY_CHECKS = 0;
            SET GROUP_CONCAT_MAX_LEN=32768;
            SET @tables = NULL;
            SELECT GROUP_CONCAT('`', table_name, '`') INTO @tables
              FROM information_schema.tables
              WHERE table_schema = (SELECT DATABASE());
            SELECT IFNULL(@tables,'dummy') INTO @tables;
            
            SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables);
            PREPARE stmt FROM @tables;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
            SET FOREIGN_KEY_CHECKS = 1;"
        );

        Artisan::call('migrate');

        return "SUCCESS";
    }
}
