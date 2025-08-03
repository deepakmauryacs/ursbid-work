<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class YtController extends Controller
{
    

    public function index()
    {
      return view('admin/yt/add');
    }

    public function create(Request $request)
    {
      $validator = $request->validate([
        'link'=>'required',
        
    ]);
    $uniqSlug = Str::slug($request->link);
     $blogId = DB::table('yt')->insert([
        'link' => $request->link, 
        'slug'=> $uniqSlug,
    ]);


    if($blogId)
           {
            return back()->with('success', 'Data Has Been Inserted successfully.');
           }else{
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
           }
    }


    // view page------------------------------------------


    public function view(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 15);
        $query = DB::table('yt');
        if ($keyword) {
            $query->where('link', 'like', '%' . $keyword . '%');
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
        return view('admin/yt/list', compact('blogs', 'data'));
    }



    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('yt')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('yt')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('yt')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'Data Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    // edit page data--------------------------------
    public function edit($id){
      $blog = DB::table('yt')->where('id', $id)->first();
      return view('/admin/yt/edit', compact('blog'));
    }

    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'link' => 'required',
        
    ]);
    $uniqSlug = Str::slug($request->link);
    $blog = DB::table('yt')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'data not found.'])->withInput();
    }
    
    $updateData = [
        'link' => $request->link,
        'slug' => $uniqSlug,
    ];
    
    $updated = DB::table('yt')->where('id', $id)->update($updateData);
    if ($updated) {
      return redirect('admin/yt/list')->with('success', 'Data has been updated successfully.');
    } else {
        return back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}






}