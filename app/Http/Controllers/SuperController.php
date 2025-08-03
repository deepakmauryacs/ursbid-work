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

class SuperController extends Controller
{
    public function index()
    {
      return view('admin/super/add');
    }


    public function create(Request $request)
{
    $validator = $request->validate([
        'title'=>'required|unique:super',
        'sub_id' => 'required',
        'cat_id' => 'required',
        'super_id' => 'required',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5048',

    ]);

 

    // If not exists, proceed to insert the new record
    $uniqSlug = Str::slug($request->title);
    $blogId = DB::table('super')->insertGetId([
        'title' => $request->title,
        'sub_id' => $request->sub_id,
        'cat_id' => $request->cat_id,
        'meta_title' => $request->meta_title, 
        'super_id' => $request->super_id,
        'meta_description' => $request->meta_description, 
        'meta_keyword' => $request->meta_keyword,
        'description' => $request->description,
        'slug' => $uniqSlug,
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        DB::table('super')->where('id', $blogId)->update(['image' => $filename]);
    }

    if ($blogId) {
        return back()->with('success', 'Data Has Been Inserted successfully.');
    } else {
        return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
    }
}


    // view page------------------------------------------


    public function view(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 20);
        // $query = DB::table('super');
        // if ($keyword) {
        //     $query->where('title', 'like', '%' . $keyword . '%')
        //           ->orWhere('description', 'like', '%' . $keyword . '%');
        // }

        // $query = DB::table('super')
        // ->join('category', 'super.cat_id', '=', 'category.id')
        // ->join('sub', 'super.sub_id', '=', 'sub.id')
        // ->select('super.*', 'category.*')
        // ->addSelect('super.id as super_id','super.image as super_img','super.title as super_tilte','super.description as super_description','super.status as super_status', 'category.id as category_id','category.title as category_title', 'sub.id as sub_id','sub.title as sub_title' );

        $query = DB::table('super')
    ->leftJoin('category', 'super.cat_id', '=', 'category.id')
    ->leftJoin('sub', 'super.sub_id', '=', 'sub.id')
    ->leftJoin('product', 'super.super_id', '=', 'product.id')
    ->select('super.*', 'category.*')
    ->addSelect(
        'super.image as super_img',
        'super.id as product_id',
        'super.title as super_title',
        'super.status as super_status',
        'category.id as category_id',
        'category.title as category_title',
        'sub.id as sub_id',
        'sub.title as sub_title',
        'product.title as product_tilte'
    )->orderBy('super.id');


        if ($keyword) {
            $query->where('super.title', 'like', '%' . $keyword . '%')
                  ->orWhere('sub.title', 'like', '%' . $keyword . '%')
                  ->orWhere('category.title', 'like', '%' . $keyword . '%');
        }
        if ($keyword) {
            $blogs = $query->paginate($recordsPerPage);
        } else {
            $blogs = $query->paginate($recordsPerPage);
        }
        $data = [
            'keyword' => $keyword,
            'r_page' => $recordsPerPage,
        ];
        return view('admin/super/list', compact('blogs', 'data'));
    }



    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('super')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('super')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('super')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'Super Category  Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    // edit page data--------------------------------
    public function edit($id){
      $blog = DB::table('super')->where('id', $id)->first();
      return view('/admin/super/edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'title' => 'required|unique:super,title,' . $id,
        // 'title' => 'required',
        'super_id' => 'required',
            'sub_id' => 'required',
            'cat_id' => 'required',
            
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5048',

        ]);
    
       
    
        // Retrieve the current product
        $blog = DB::table('super')->where('id', $id)->first();
        if (!$blog) {
            return back()->withErrors(['error' => 'Product not found.'])->withInput();
        }


        // Delete existing image if a new one is provided
    if ($request->hasFile('image') && !empty($blog->image)) {
        $existingImagePath = public_path('uploads') . '/' . $blog->image;
        if (File::exists($existingImagePath)) {
            File::delete($existingImagePath);
        }
    }
    
        // Prepare the data for update
        $uniqSlug = Str::slug($request->title);
        $updateData = [
            'title' => $request->title,
            'sub_id' => $request->sub_id,
            'cat_id' => $request->cat_id,
            'super_id' => $request->super_id,
            'meta_title' => $request->meta_title, 
            'meta_description' => $request->meta_description, 
            'meta_keyword' => $request->meta_keyword,
            'description' => $request->description,
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
        // Update the product
        $updated = DB::table('super')->where('id', $id)->update($updateData);
        if ($updated) {
            return redirect('admin/super/list')->with('success', 'Date has been updated successfully.');
        } else {
            return back()->withErrors(['error' => 'Update failed.'])->withInput();
        }
    }
    




// get_sub_cat
   
public function getSubCategories(Request $request) {
    $cat_id = $request->cat_id;
    $subCategories = DB::select("SELECT * FROM sub WHERE cat_id = ?", [$cat_id]);
    $options = '<option value="">Select Sub Category</option>';
    foreach ($subCategories as $subCat) {
        $options .= '<option value="' . $subCat->id . '">' . $subCat->title . '</option>';
    }
    return $options;
}




}