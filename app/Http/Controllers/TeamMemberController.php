<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak - hanya admin yang dapat mengelola tim.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $members = TeamMember::orderBy('id')->get();
        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $destination = public_path('images/team');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $filename = time() . '_' . preg_replace('/\s+/', '_', $photo->getClientOriginalName());
            $photo->move($destination, $filename);

            $data['photo'] = 'team/' . $filename;
        } else {
            unset($data['photo']);
        }

        TeamMember::create($data);

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(TeamMember $team)
    {
        $member = $team;
        return view('admin.team.edit', compact('member'));
    }

    public function update(Request $request, TeamMember $team)
    {
        $member = $team;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                $oldPath = public_path('images/' . $member->photo);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $photo = $request->file('photo');
            $destination = public_path('images/team');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $filename = time() . '_' . preg_replace('/\s+/', '_', $photo->getClientOriginalName());
            $photo->move($destination, $filename);

            $data['photo'] = 'team/' . $filename;
        } else {
            unset($data['photo']);
        }

        $member->update($data);

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $team)
    {
        $member = $team;

        if ($member->photo) {
            $oldPath = public_path('images/' . $member->photo);
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $member->delete();

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
