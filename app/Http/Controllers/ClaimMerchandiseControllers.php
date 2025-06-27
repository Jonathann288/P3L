<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\claimmerchandise;
use App\Models\merchandise;
use App\Models\Pembeli;

class ClaimMerchandiseControllers extends Controller
{
    public function showClaimMerchandise(Request $request)
    {
        $filter = $request->input('filter', 'all');
        
        $query = claimmerchandise::with(['pembeli', 'merchandise']);
        
        if ($filter === 'unclaimed') {
            $query->where('status', 'belum_diambil');
        }
        
        $claims = $query->orderBy('tanggal_claim', 'desc')->get();
        
        return view('CustomerService.DashboardClaimMerchandise', [
            'claims' => $claims,
            'filter' => $filter
        ]);
    }
    
    public function updateClaimStatus(Request $request, $id)
    {
        $claim = claimmerchandise::findOrFail($id);
        
        $claim->tanggal_pengambilan = now();
        $claim->status = 'sudah_diambil';
        $claim->save();
        
        return redirect()->back()->with('success', 'Status klaim berhasil diperbarui');
    }
}