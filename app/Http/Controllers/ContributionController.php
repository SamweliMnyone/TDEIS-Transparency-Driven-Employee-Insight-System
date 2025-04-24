<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class ContributionController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contributions = Contribution::where('user_id', Auth::id())
                            ->latest()
                            ->paginate(10);
        
        return view('TDEIS.auth.employee.view-contributions', compact('contributions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('TDEIS.auth.employee.add-contribution');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:certificate,project',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB max
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('contributions', 'public');
            $validated['file_path'] = $path;
        }

        // Add user ID
        $validated['user_id'] = Auth::id();

        Contribution::create($validated);

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contribution $contribution)
    {
        // Ensure user can only view their own contributions
        $this->authorize('view', $contribution);
        
        return view('show-contribution', compact('contribution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contribution $contribution)
    {
        $this->authorize('update', $contribution);
        
        return view('edit-contribution', compact('contribution'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contribution $contribution)
    {
        $this->authorize('update', $contribution);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:certificate,project',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($contribution->file_path) {
                Storage::disk('public')->delete($contribution->file_path);
            }
            
            $path = $request->file('file')->store('contributions', 'public');
            $validated['file_path'] = $path;
        }

        $contribution->update($validated);

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contribution $contribution)
    {
        $this->authorize('delete', $contribution);
        
        // Delete associated file if exists
        if ($contribution->file_path) {
            Storage::disk('public')->delete($contribution->file_path);
        }
        
        $contribution->delete();

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution deleted successfully!');
    }
}