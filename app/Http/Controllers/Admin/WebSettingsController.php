<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class WebSettingsController extends Controller
{
    public function edit()
    {
        $setting = WebSetting::first();
        return view('ursbid-admin.web_settings.edit', compact('setting'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_keywords' => 'nullable|string',
            'site_logo_1' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'site_logo_2' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'site_favicon' => 'nullable|file|mimes:png,ico,jpg,jpeg|max:512',
            'copyright_text' => 'nullable|string',
            'custom_code_header' => 'nullable|string',
            'custom_code_footer' => 'nullable|string',
        ]);
    
        $uploadPath = public_path('uploads/web_settings');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
    
        $filesToCheck = ['site_logo_1', 'site_logo_2', 'site_favicon'];
    
        foreach ($filesToCheck as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $filename = $fileKey . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $validated[$fileKey] = 'uploads/web_settings/' . $filename;
            } else {
                // If no new file uploaded, preserve existing if any
                unset($validated[$fileKey]);
            }
        }
    
        $setting = WebSetting::first();
    
        if ($setting) {
            $setting->update($validated);
        } else {
            $setting = WebSetting::create($validated);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Web settings saved successfully.',
            'data' => $setting,
        ]);
    }

}
