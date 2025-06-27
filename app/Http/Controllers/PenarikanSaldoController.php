<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Penitip;

class PenarikanSaldoController extends Controller
{
    public function showPenarikanForm()
    {
        try {
            $penitip = Auth::guard('penitip')->user();
            return view('penitip.penarikan_saldo', compact('penitip'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function prosesPenarikan(Request $request)
    {
        $request->validate([
            'nominal_penarikan' => 'required|numeric|min:10000',
        ]);

        $penitip = Auth::guard('penitip')->user();
        $nominalPenarikan = (float) $request->input('nominal_penarikan');
        $saldoSaatIni = (float) $penitip->saldo_penitip;

        if ($nominalPenarikan > $saldoSaatIni) {
            return redirect()->back()->with('error', 'Saldo Anda tidak mencukupi.');
        }

        $biayaPenarikan = $nominalPenarikan * 0.05;
        $totalDebit = $nominalPenarikan + $biayaPenarikan;

        if ($totalDebit > $saldoSaatIni) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi setelah ditambah biaya penarikan.');
        }

        try {
            DB::transaction(function () use ($penitip, $nominalPenarikan, $totalDebit) {
                $penitip->saldo_penitip -= $totalDebit;
                $penitip->nominal_penarikan = $nominalPenarikan; 
                $penitip->save(); 
            });

            return redirect()->route('penitip.penarikan.form')->with('success', 'Penarikan saldo berhasil diajukan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }
}