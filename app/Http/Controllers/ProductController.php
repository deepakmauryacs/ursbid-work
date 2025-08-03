<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
      return view('admin/product/add');
    }

    public function create(Request $request)
    {
      $validator = $request->validate([
        'title'=>'required',
        // 'title'=>'required|unique:product',
        'sub_id'=>'required',
        'cat_id'=>'required',
        // 'super_id' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $existingProduct = DB::table('product')
    ->where('cat_id', $request->cat_id)
    ->where('sub_id', $request->sub_id)
    ->where('title', $request->title)
    ->first();

if ($existingProduct) {
    
    return back()->withErrors(['error' => 'Product with the same Category, Sub category, and title already exists.'])->withInput();
}



    $uniqSlug = Str::slug($request->title);
     $blogId = DB::table('product')->insertGetId([
        'title' => $request->title,
        'user_type' => $request->user_type,
        'insert_by' => $request->insert_by,
        'update_by' => $request->update_by,
        'user_id' => 0,
        // 'super_id' => $request->super_id,
        'sub_id' => $request->sub_id,
        'order_by' => $request->order_by,
        'cat_id' => $request->cat_id,
        'slug'=> $uniqSlug,
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

    $inserted = DB::table('product')->where('id', $blogId)->update(['image' => $filename]);
    }

    if($inserted)
           {
            return back()->with('success', 'Product has Been Inserted successfully.');
           }else{
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
           }
    }


    // view page------------------------------------------


    public function view(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 20);

        // $query = DB::table('product')
        // ->join('category', 'product.cat_id', '=', 'category.id')
        // ->join('sub', 'product.sub_id', '=', 'sub.id')
        // ->join('super', 'product.super_id', '=', 'super.id')
        // ->select('product.*', 'category.*')
        // ->addSelect('product.image as img','product.id as product_id','product.title as product_tilte','product.status as product_status', 'category.id as category_id','category.title as category_title', 'sub.id as sub_id','sub.title as sub_title','super.title as super_title' );
        $query = DB::table('product')
    ->leftJoin('category', 'product.cat_id', '=', 'category.id')
    ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
    // ->leftJoin('super', 'product.super_id', '=', 'super.id')
    ->select('product.*', 'category.*')
    ->addSelect(
        'product.image as img',
        'product.id as product_id',
        'product.title as product_tilte',
        'product.status as product_status',
        'category.id as category_id',
        'category.title as category_title',
        'sub.id as sub_id',
        'sub.title as sub_title',
        // 'super.title as super_title'
    )->orderBy('product.order_by');



        if ($keyword) {
            $query->where('product.title', 'like', '%' . $keyword . '%')
                  ->orWhere('sub.title', 'like', '%' . $keyword . '%')
                //   ->orWhere('super.title', 'like', '%' . $keyword . '%')
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
        return view('admin/product/list', compact('blogs', 'data'));
    }



    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('product')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('product')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('product')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'product  Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    // edit page data--------------------------------
    public function edit($id){
      $blog = DB::table('product')->where('id', $id)->first();
      return view('/admin/product/edit', compact('blog'));
    }

    public function update(Request $request, $id)
{
    $validator = $request->validate([
        // 'title' => 'required|unique:product,title,' . $id,
        'title' => 'required',
        // 'super_id' => 'required',
        'sub_id'=>'required',
        'cat_id'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $existingProduct = DB::table('product')
            ->where('cat_id', $request->cat_id)
            ->where('sub_id', $request->sub_id)
            ->where('title', $request->title)
            ->where('id', '!=', $id)
            ->first();
    
        if ($existingProduct) {
            return back()->withErrors(['error' => 'Product with the same Category, sub category, and title already exists.'])->withInput();
        }


    $uniqSlug = Str::slug($request->title);
    $blog = DB::table('product')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'product not found.'])->withInput();
    }
    if ($request->hasFile('image') && !empty($blog->image)) {
        $existingImagePath = public_path('uploads') . '/' . $blog->image;
        if (File::exists($existingImagePath)) {
            File::delete($existingImagePath);
        }
    }
    $updateData = [
        'title' => $request->title,
        'sub_id' => $request->sub_id,
        'cat_id' => $request->cat_id,
        'super_id' => $request->super_id,
        'user_type' => $request->user_type,
        'insert_by' => $request->insert_by,
        'update_by' => $request->update_by,
        'order_by' => $request->order_by,
        'slug' => $uniqSlug,
    ];
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        $updateData['image'] = $filename;
    }
    $updated = DB::table('product')->where('id', $id)->update($updateData);
    if ($updated) {
      return redirect('admin/product/list')->with('success', 'product has been updated successfully.');
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

public function getSupCategories(Request $request) {
    $cat_id = $request->input('cat_id');
    $sub_id = $request->input('sub_id');

    // dd($request->all());

    // Corrected the SQL query to include both cat_id and sub_id parameters
    $supCategories = DB::select("SELECT * FROM product WHERE cat_id = ? AND sub_id = ?", [$cat_id, $sub_id]);

    // Prepare the HTML options for the select element
    $options = '<option value="">Select product</option>';
    foreach ($supCategories as $supCat) {
        $options .= '<option value="' . $supCat->id . '">' . $supCat->title . '</option>';
    }
    
    return $options;
}



}