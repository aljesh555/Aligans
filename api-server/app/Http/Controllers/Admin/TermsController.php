<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term;

class TermsController extends Controller
{
    /**
     * Display the terms management page
     */
    public function index()
    {
        $activeTerms = Term::getActive();
        
        // If no active terms found, create default one
        if ($activeTerms->isEmpty()) {
            $defaultTerm = Term::create([
                'title' => 'Terms & Conditions',
                'content' => '<p>Default terms and conditions content.</p>',
                'is_active' => true
            ]);
            $activeTerms = collect([$defaultTerm]);
        }
        
        return view('admin.terms.index', compact('activeTerms'));
    }
    
    /**
     * Show the form for editing terms
     */
    public function edit()
    {
        // Get latest term for editing template
        $terms = Term::latest()->first();
        
        // If no terms found, create default one
        if (!$terms) {
            $terms = Term::create([
                'title' => 'Terms & Conditions',
                'content' => '<p>Default terms and conditions content.</p>',
                'is_active' => true
            ]);
        }
        
        return view('admin.terms.edit', compact('terms'));
    }
    
    /**
     * Update the terms
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        // Deactivate all existing terms
        Term::where('is_active', true)->update(['is_active' => false]);
        
        // Create new terms version
        Term::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => true
        ]);
        
        return redirect()->route('admin.terms.index')
            ->with('success', 'Terms and conditions updated successfully.');
    }
    
    /**
     * View terms history
     */
    public function history()
    {
        $termsHistory = Term::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.terms.history', compact('termsHistory'));
    }
} 