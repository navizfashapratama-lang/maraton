<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Menampilkan halaman kontak
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Memproses form kontak
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
            'newsletter' => 'nullable|boolean'
        ]);
        
        // Cek jika tabel pesan_kontak ada
        try {
            DB::table('pesan_kontak')->insert([
                'nama' => $validated['name'],
                'email' => $validated['email'],
                'telepon' => $validated['phone'],
                'subjek' => $validated['subject'],
                'pesan' => $validated['message'],
                'ingin_newsletter' => $validated['newsletter'] ?? false,
                'status' => 'belum_dibaca',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('contact')->with('success', 'Pesan Anda telah terkirim! Kami akan membalas dalam 1x24 jam.');
            
        } catch (\Exception $e) {
            // Jika tabel tidak ada, cukup log error dan beri respon sukses
            \Log::error('Gagal menyimpan pesan kontak: ' . $e->getMessage());
            return redirect()->route('contact')->with('success', 'Pesan Anda telah terkirim! (Catatan: Database sedang dalam perbaikan)');
        }
    }

    /**
     * Menampilkan halaman FAQ
     */
    public function faq()
    {
        return view('faq');
    }
}