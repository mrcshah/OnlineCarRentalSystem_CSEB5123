<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $yourBranch = $user->branches()->get();
        $otherBranches = Branch::whereDoesntHave('staff', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('branches.index', compact('yourBranch', 'otherBranches'));    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);
        
        $branch = Branch::create([
            'name' => $request->name,
            'location' => $request->location,
            'code' => Str::random(6),
            'owner_id' => Auth::id(),
        ]);

        $branch->staff()->attach(Auth::id());

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        //
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:6',
        ]);

        $branch = Branch::where('code', $request->code)->first();

        if (!$branch) {
            return redirect()->back()->with('error', 'Branch not found.');
        }

        if ($branch->staff()->get()->contains(Auth::id())) {
            return redirect()->back()->with('error', 'You are already a member of this branch.');
        }

        $branch->staff()->attach(Auth::id());

        return redirect()->route('branches.index')->with('success', 'Joined branch successfully.');
    }

    public function leave(Branch $branch)
    {
        if ($branch->owner_id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot leave your own branch.');
        }

        $branch->staff()->detach(Auth::id());

        return redirect()->route('branches.index')->with('success', 'Left branch successfully.');
    }

    public function kick(Branch $branch, User $user)
    {
        if ($branch->owner_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        if ($branch->owner_id == $user->id) {
            return redirect()->back()->with('error', 'You cannot kick the owner of the branch.');
        }

        $branch->staff()->detach($user->id);

        return back()->with('success', 'Kicked user from branch successfully.');
    }

    public function manage(Branch $branch)
    {
        if (!$branch->staff->contains(Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        return view('branches.manage', ['branch' => $branch]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        if ($branch->owner_id !== auth()->id()) {
            abort(403);
        }

        $branch->staff()->detach(); // remove all staff
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }
}
