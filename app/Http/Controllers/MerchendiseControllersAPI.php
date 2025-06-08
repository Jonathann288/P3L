<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\merchandise;

class MerchendiseControllersAPI extends Controller
{
    public function index()
    {
        try {
            $merchandise = merchandise::all();

            return response()->json([
                'success' => true,
                'message' => 'Data merchandise berhasil diambil',
                'data' => $merchandise
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error: ' . $e->getMessage(),
            ], 500);
        }
    }

}
