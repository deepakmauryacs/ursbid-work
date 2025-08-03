<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UnitController extends Controller
{
    public function index()
    {
      return view('admin/unit/add');
    }

    public function create(Request $request)
    {
      $validator = $request->validate([
        'title'=>'required',
        'sub_id'=>'required',
        'cat_id'=>'required',
    ]);
    $uniqSlug = Str::slug($request->title);
     $blogId = DB::table('unit')->insertGetId([
        'title' => $request->title,
        'sub_id' => $request->sub_id,
        'cat_id' => $request->cat_id,
        'slug'=> $uniqSlug,
    ]);

 

    if($blogId)
           {
            return back()->with('success', 'unit has Been Inserted successfully.');
           }else{
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
           }
    }


    // view page------------------------------------------


    public function view(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 20);

        $query = DB::table('unit')
        ->join('category', 'unit.cat_id', '=', 'category.id')
        ->join('sub', 'unit.sub_id', '=', 'sub.id')
        ->select('unit.*', 'category.*')
        ->addSelect('unit.id as unit_id','unit.title as unit_tilte','unit.status as unit_status', 'category.id as category_id','category.title as category_title', 'sub.id as sub_id','sub.title as sub_title' );


        if ($keyword) {
            $query->where('unit.title', 'like', '%' . $keyword . '%')
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
        return view('admin/unit/list', compact('blogs', 'data'));
    }



    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('unit')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('unit')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('unit')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'unit  Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    // edit page data--------------------------------
    public function edit($id){
      $blog = DB::table('unit')->where('id', $id)->first();
      return view('/admin/unit/edit', compact('blog'));
    }

    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'title' => 'required',
        
        'sub_id'=>'required',
        'cat_id'=>'required',
    ]);
    $uniqSlug = Str::slug($request->title);
    $blog = DB::table('unit')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'unit not found.'])->withInput();
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
        'slug' => $uniqSlug,
    ];
    
    $updated = DB::table('unit')->where('id', $id)->update($updateData);
    if ($updated) {
      return redirect('admin/unit/list')->with('success', 'unit has been updated successfully.');
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