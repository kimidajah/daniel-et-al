<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Store a newly created teacher.
     */
    public function store(Request $request)
    {
        // Pastikan hanya role TU yang bisa mengelola guru
        if (Auth::user()->role !== 'tu') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'nip' => 'nullable|string|max:30|unique:teacher_profiles,nip',
            'subject' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 'guru',
        ]);

        TeacherProfile::create([
            'user_id' => $user->id,
            'nip' => $request->input('nip'),
            'subject' => $request->input('subject'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Guru berhasil ditambahkan.',
        ]);
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, User $teacher)
    {
        // Pastikan hanya role TU dan target user memiliki role guru
        if (Auth::user()->role !== 'tu' || $teacher->role !== 'guru') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $profileId = $teacher->teacherProfile->id ?? 'NULL';

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'password' => 'nullable|string|min:6|confirmed',
            'nip' => 'nullable|string|max:30|unique:teacher_profiles,nip,' . $profileId,
            'subject' => 'required|string|max:255',
        ]);

        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->input('password'));
        }

        $teacher->update($userData);

        $teacher->teacherProfile()->updateOrCreate(
            ['user_id' => $teacher->id],
            [
                'nip' => $request->input('nip'),
                'subject' => $request->input('subject'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data Guru berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(User $teacher)
    {
        // Pastikan hanya role TU dan target user memiliki role guru
        if (Auth::user()->role !== 'tu' || $teacher->role !== 'guru') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        // Hapus user (akan menghapus teacher_profile secara berantai karena cascade delete)
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Guru berhasil dihapus.',
        ]);
    }
}
