<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Siswa::all();
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data
            siswa.'], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'umur' => 'required|integer',
        ]);

        try {
            $siswa = Siswa::create($validatedData);

            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data
            siswa.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return Siswa::findOrFail($id);
        }  catch (\Exception $e) {
        return response()->json(['error' => 'Siswa tidak ditemukan.'],
        404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['sometimes', 'required', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
            'umur' => 'sometimes|required|integer|min:6|max:18'
        ], [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi',
            'kelas.regex' => 'Format kelas harus seperti "XII IPA 1"',
            'umur.min' => 'Umur minimal adalah 6 tahun',
            'umur.max' => 'Umur maksimal adalah 18 tahun'
        ]);

        try {
            $siswa = Siswa::findOrFail($id);
            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|max:255',
                'kelas' => 'sometimes|required|string|max:10',
                'umur' => 'sometimes|required|integer',
            ]);
            $siswa->update($validatedData);

            return response()->json($siswa);
        } catch (\Exception $e) {
                return response()->json(['error' => 'Gagal memperbarui data siswa.'], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

                return response()->json(null, 204);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
            }
    }
}
