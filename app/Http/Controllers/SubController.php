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

class SubController extends Controller
{
    public function index()
    {
      return view('admin/sub/add');
    }

    public function create(Request $request)
{
    $validator = $request->validate([
        'title' => 'required',
        'cat_id' => 'required',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5048',
    ]);
    $uniqSlug = Str::slug($request->title);
    $existingRecord = DB::table('sub')
        ->where('cat_id', $request->cat_id)
        ->where('title', $request->title)
        ->exists();

    if ($existingRecord) {
        return back()->withErrors(['error' => 'A record with the same category and title already exists.'])->withInput();
    }

    $blogId = DB::table('sub')->insertGetId([
        'title' => $request->title,
        'cat_id' => $request->cat_id,
        'order_by' => $request->order_by,
        'slug'=> $uniqSlug,
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        
        DB::table('sub')->where('id', $blogId)->update(['image' => $filename]);
    }
    if ($blogId) {
        return back()->with('success', 'Sub Category has been inserted successfully.');
    } else {
        return back()->withErrors(['error' => 'Insertion failed.'])->withInput();
    }
}



    // view page------------------------------------------


    public function view(Request $request)
{
    $keyword = $request->input('keyword');
    $recordsPerPage = $request->input('r_page', 10);
    
    $query = DB::table('sub')
        ->join('category', 'sub.cat_id', '=', 'category.id')
        ->select('sub.*', 'category.*')
        ->addSelect('sub.image as img','sub.id as sub_id','sub.title as sub_tilte','sub.status as sub_status', 'category.id as category_id','category.title as category_title' )->orderBy('sub.order_by');

    if ($keyword) {
        $query->where('sub.title', 'like', '%' . $keyword . '%');
    }
    
    $blogs = $query->paginate($recordsPerPage);
    $data = [
        'keyword' => $keyword,
        'r_page' => $recordsPerPage,
    ];

    return view('admin/sub/list', compact('blogs', 'data'));
}




    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('sub')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('sub')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }










    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('sub')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'Sub  Category Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    




    public function edit($id){
      $blog = DB::table('sub')->where('id', $id)->first();
      return view('/admin/sub/edit', compact('blog'));
    }









    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'title' => 'required',
        'cat_id' => 'required',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5048',
    ]);

    $uniqSlug = Str::slug($request->title);

    // Check if the record with the same cat_id and title exists, excluding the current record
    $existingRecord = DB::table('sub')
        ->where('cat_id', $request->cat_id)
        ->where('title', $request->title)
        ->where('id', '!=', $id)
        ->exists();

    if ($existingRecord) {
        return back()->withErrors(['error' => 'A record with the same cat_id and title already exists.'])->withInput();
    }

    // Fetch the current record
    $blog = DB::table('sub')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'Sub Category not found.'])->withInput();
    }

    // Delete existing image if a new one is provided
    if ($request->hasFile('image') && !empty($blog->image)) {
        $existingImagePath = public_path('uploads') . '/' . $blog->image;
        if (File::exists($existingImagePath)) {
            File::delete($existingImagePath);
        }
    }

    // Prepare data for updating
    $updateData = [
        'title' => $request->title,
        'cat_id' => $request->cat_id,
        'order_by' => $request->order_by,
        'slug' => $uniqSlug,
    ];

    // Handle the image upload if provided
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
    if ($blog->cat_id == $request->cat_id && $blog->order_by == $request->order_by && $blog->title == $request->title && (empty($updateData['image']) || $blog->image == $updateData['image'])) {
        return redirect('admin/sub/list')->with('success', 'Sub Category has not been updated as no changes were made.');
    }

    // Perform the update
    $updated = DB::table('sub')->where('id', $id)->update($updateData);

    if ($updated) {
        return redirect('admin/sub/list')->with('success', 'Sub Category has been updated successfully.');
    } else {
        return back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}
    
    
   

}