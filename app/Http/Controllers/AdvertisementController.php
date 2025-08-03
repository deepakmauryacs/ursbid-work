<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AdvertisementController extends Controller
{
    public function index()
    {
      return view('admin/advertisement/add');
    }

    public function create(Request $request)
{
    $validator = $request->validate([
        
        'cat_id' => 'required',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5048',
    ]);
   
    $blogId = DB::table('advertisement')->insertGetId([
        'cat_id' => $request->cat_id,   
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        
        DB::table('advertisement')->where('id', $blogId)->update(['image' => $filename]);
    }
    if ($blogId) {
        return back()->with('success', 'Advertisement has been inserted successfully.');
    } else {
        return back()->withErrors(['error' => 'Insertion failed.'])->withInput();
    }
}



    // view page------------------------------------------


    public function view(Request $request)
{
    $keyword = $request->input('keyword');
    $recordsPerPage = $request->input('r_page', 15);
    
    $query = DB::table('advertisement')
        ->join('sub', 'advertisement.cat_id', '=', 'sub.id')
        ->select('advertisement.*', 'sub.*')
        ->addSelect('advertisement.image as img','advertisement.id as advertisement_id','advertisement.status as sub_status', 'sub.id as sub_id','sub.title as category_title' );

    
    
    $blogs = $query->paginate($recordsPerPage);
    $data = [
        'keyword' => $keyword,
        'r_page' => $recordsPerPage,
    ];

    return view('admin/advertisement/list', compact('blogs', 'data'));
}




    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('advertisement')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('advertisement')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }










    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('advertisement')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'Advertisement Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    

    public function edit($id){
      $blog = DB::table('advertisement')->where('id', $id)->first();
      return view('/admin/advertisement/edit', compact('blog'));
    }



    public function update(Request $request, $id)
{
    $validator = $request->validate([
       
        'cat_id' => 'required',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5048',
    ]);

  

    // Fetch the current record
    $blog = DB::table('advertisement')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'advertisement not found.'])->withInput();
    }

    if ($request->hasFile('image') && !empty($blog->image)) {
        $existingImagePath = public_path('uploads') . '/' . $blog->image;
        if (File::exists($existingImagePath)) {
            File::delete($existingImagePath);
        }
    }

    $updateData = [
        'cat_id' => $request->cat_id,
        
    ];

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        
        try {
            $file->move(public_path('uploads'), $filename);
            $updateData['image'] = $filename;
            Log::info('Image uploaded successfully: ' . $filename);
        } catch (Exception $e) {
            Log::error('Failed to upload image: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to upload image.'])->withInput();
        }
    }

    // Check if there are changes before updating
    if ($blog->cat_id == $request->cat_id && (empty($updateData['image']) || $blog->image == $updateData['image'])) {
        return redirect('admin/advertisement/list')->with('success', 'advertisement has not been updated as no changes were made.');
    }

    // Perform the update
    $updated = DB::table('advertisement')->where('id', $id)->update($updateData);

    if ($updated) {
        return redirect('admin/advertisement/list')->with('success', 'advertisement has been updated successfully.');
    } else {
        return back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}
    
    
   

}