<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderByDesc('id')->paginate(10);
        return view('ursbid-admin.testimonial.list', compact('testimonials'));
    }

    public function create()
    {
        return view('ursbid-admin.testimonial.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:1,2',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        Testimonial::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial created successfully.',
        ]);
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('ursbid-admin.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:1,2',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $testimonial->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial deleted successfully.',
        ]);
    }
}

