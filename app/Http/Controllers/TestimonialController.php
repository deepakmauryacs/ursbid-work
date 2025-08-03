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

class TestimonialController extends Controller
{
    public function index()
    {
      return view('admin/testimonial/add');
    }

    public function create(Request $request)
{
    $validator = $request->validate([
        'name' => 'required',
        'position' => 'required',
        'description' => 'required',
        
    ]);
    $uniqSlug = Str::slug($request->name);
    

    $blogId = DB::table('testimonial')->insert([
        'title' => $request->name,
        'position' => $request->position    ,
        'description' => $request->description,
        'slug'=> $uniqSlug,
    ]);


    if ($blogId) {
        return back()->with('success', 'testimonial has been inserted successfully.');
    } else {
        return back()->withErrors(['error' => 'Insertion failed.'])->withInput();
    }
}



    // view page------------------------------------------


    public function view(Request $request)
{
    $keyword = $request->input('keyword');
    $recordsPerPage = $request->input('r_page', 15);
    
    $query = DB::table('testimonial');
        

    if ($keyword) {
        $query->where('title', 'like', '%' . $keyword . '%');
    }
    
    $blogs = $query->paginate($recordsPerPage);
    $data = [
        'keyword' => $keyword,
        'r_page' => $recordsPerPage,
    ];

    return view('admin/testimonial/list', compact('blogs', 'data'));
}




    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('testimonial')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('testimonial')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }










    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('testimonial')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'testimonial   Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    




    public function edit($id){
      $blog = DB::table('testimonial')->where('id', $id)->first();
      return view('/admin/testimonial/edit', compact('blog'));
    }









    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'name' => 'required',
        'position' => 'required',
        'description' => 'required',
        
    ]);

    $uniqSlug = Str::slug($request->name);

    // Fetch the current record
    $blog = DB::table('testimonial')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'testimonial  not found.'])->withInput();
    }

    // Prepare data for updating
    $updateData = [
        'title' => $request->name,
        'position' => $request->position,
        'description' => $request->description,
        'slug' => $uniqSlug,
    ];

    // Perform the update
    $updated = DB::table('testimonial')->where('id', $id)->update($updateData);

    if ($updated) {
        return redirect('admin/testimonial/list')->with('success', 'testimonial  has been updated successfully.');
    } else {
        return back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}
    
    
   

}