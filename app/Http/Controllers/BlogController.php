<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index()
    {
      return view('admin/blog/add');
    }

    public function create(Request $request)
    {
      $validator = $request->validate([
        'title'=>'required|unique:blogs',
        'description'=>'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    $uniqSlug = Str::slug($request->title);
     $blogId = DB::table('blogs')->insertGetId([
        'title' => $request->title,
        'description' => $request->description,
        'slug'=> $uniqSlug,
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

    $inserted = DB::table('blogs')->where('id', $blogId)->update(['image' => $filename]);
    }

    if($inserted)
           {
            return back()->with('success', 'Blog Has Been Inserted successfully.');
           }else{
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
           }
    }


    // view page------------------------------------------


    public function view(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 1);
        $query = DB::table('blogs');
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
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
        return view('admin/blog/list', compact('blogs', 'data'));
    }



    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('blogs')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('blogs')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('blogs')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'Blog  Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    // edit page data--------------------------------
    public function edit($id){
      $blog = DB::table('blogs')->where('id', $id)->first();
      return view('/admin/blog/edit', compact('blog'));
    }

    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'title' => 'required|unique:blogs,title,' . $id,
        'description' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    $uniqSlug = Str::slug($request->title);
    $blog = DB::table('blogs')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'Blog not found.'])->withInput();
    }
    if ($request->hasFile('image') && !empty($blog->image)) {
        $existingImagePath = public_path('uploads') . '/' . $blog->image;
        if (File::exists($existingImagePath)) {
            File::delete($existingImagePath);
        }
    }
    $updateData = [
        'title' => $request->title,
        'description' => $request->description,
        'slug' => $uniqSlug,
    ];
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        $updateData['image'] = $filename;
    }
    $updated = DB::table('blogs')->where('id', $id)->update($updateData);
    if ($updated) {
      return redirect('admin/blog/list')->with('success', 'Blog has been updated successfully.');
    } else {
        return back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}
   

}