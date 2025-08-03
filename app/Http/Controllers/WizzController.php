<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\File;

class WizzController extends Controller
{
    public function index()
    {
      return view('admin/wizz/add');
    }

    
public function create(Request $request)
{
    $validator = $request->validate([
        'title' => 'required|unique:wizz',
    ]);

    $uniqSlug = Str::slug($request->title);

    $blogId = DB::table('wizz')->insertGetId([
        'title' => $request->title, 
        'slug' => $uniqSlug,
    ]);

    if ($blogId) {
        return redirect()->route('admin.wizz.two', ['id' => $blogId])->with('success', 'Data has been inserted successfully.');

    } else {
        return back()->withErrors(['error' => 'Insertion failed.'])->withInput();
    }
}



    // view page------------------------------------------


    public function view(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 15);
        $query = DB::table('wizz');
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
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
        return view('admin/wizz/list', compact('blogs', 'data'));
    }



    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('wizz')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('wizz')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('wizz')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'Category  Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    // edit page 2 data--------------------------------
    public function two($id){
      $blog = DB::table('wizz')->where('id', $id)->first();
      return view('/admin/wizz/two', compact('blog'));
    }
    // edit page 2 data--------------------------------
    public function three($id){
      $blog = DB::table('wizz')->where('id', $id)->first();
      return view('/admin/wizz/three', compact('blog'));
    }
    // edit page data--------------------------------
    public function edit($id){
      $blog = DB::table('wizz')->where('id', $id)->first();
      return view('/admin/wizz/edit', compact('blog'));
    }

    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'field1' => 'required',
        
    ]);
    
    
    $updateData = [
        'field1' => $request->field1,
        
    ];

    $last_id= $request->last_id;
    
    $updated = DB::table('wizz')->where('id', $id)->update($updateData);
    if ($updated) {
        return redirect()->route('admin.wizz.three', ['id' => $last_id])->with('success', 'Data has been updated successfully.');
    } else {
        return back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}


public function updatethree(Request $request)
{
    $validator = $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
       
    ]);

    $last_id = $request->last_id;

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        $inserted = DB::table('wizz_img')->insert(['image' => $filename, 'wizz_id' => $last_id]);
        
        if ($inserted) {
            return back()->with('success', 'Image has been inserted successfully.');
        } else {
            return back()->withErrors(['error' => 'Insertion failed.'])->withInput();
        }
    }
    
    return back()->withErrors(['error' => 'No image file uploaded.'])->withInput();
}


   

}