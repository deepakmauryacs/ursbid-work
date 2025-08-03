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



class CategoryController extends Controller
{
    public function sellerdashboard()
    {

        return view('seller/dashboard');
    }

    public function index()
    {
      return view('admin/category/add');
    }

    public function create(Request $request)
    {
      $validator = $request->validate([
        'title'=>'required|unique:category',
        
    ]);
    $uniqSlug = Str::slug($request->title);
     $blogId = DB::table('category')->insert([
        'title' => $request->title, 
        'slug'=> $uniqSlug,
    ]);


    if($blogId)
           {
            return back()->with('success', 'Category Has Been Inserted successfully.');
           }else{
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
           }
    }


    // view page------------------------------------------


    public function view(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 15);
        $query = DB::table('category');
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
        return view('admin/category/list', compact('blogs', 'data'));
    }



    // active deactive ------------------------------------------------

    public function active($id){
      $inserted = DB::table('category')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactive($id){
      $inserted = DB::table('category')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    // delete ---------------------------------------------------------

    public function delete($id){
      $inserted = DB::table('category')->where('id', $id)->delete();
      if($inserted)
      {
        return back()->with('success', 'Category  Has Been Deleted.');
    }else{
        return back()->with('error', ' Failed.');
    }
    }

    // edit page data--------------------------------
    public function edit($id){
      $blog = DB::table('category')->where('id', $id)->first();
      return view('/admin/category/edit', compact('blog'));
    }

    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'title' => 'required|unique:category,title,' . $id,
        
    ]);
    $uniqSlug = Str::slug($request->title);
    $blog = DB::table('category')->where('id', $id)->first();
    if (!$blog) {
        return back()->withErrors(['error' => 'category not found.'])->withInput();
    }
    
    $updateData = [
        'title' => $request->title,
        'slug' => $uniqSlug,
    ];
    
    $updated = DB::table('category')->where('id', $id)->update($updateData);
    if ($updated) {
      return redirect('admin/category/list')->with('success', 'Category has been updated successfully.');
    } else {
        return back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}



public function sellerlist(Request $request)
{
    $keyword = $request->input('keyword');
    $recordsPerPage = $request->input('r_page', 15);

    $query = DB::table('seller')
        ->where('verify', 1)
        ->whereRaw('FIND_IN_SET(?, acc_type)', [1]);

    if ($keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
              ->orWhere('email', 'like', '%' . $keyword . '%')
              ->orWhere('phone', 'like', '%' . $keyword . '%')
              ->orWhere('gender', 'like', '%' . $keyword . '%');
        });
    }

    $blogs = $query->paginate($recordsPerPage);

    $data = [
        'keyword' => $keyword,
        'r_page' => $recordsPerPage,
    ];

    return view('admin.register.sellerlist', compact('blogs', 'data'));
}


    // public function userlist(Request $request)
    // {
    // $keyword = $request->input('keyword');
    // $recordsPerPage = $request->input('r_page', 25);
    // $sec_keyword = $request->input('order_by');

    // $query = DB::table('seller')
    //     ->where('verify', 1)
    //     ->whereRaw('FIND_IN_SET(?, acc_type)', [3]);

    // if ($keyword) {
    //     $query->where(function ($q) use ($keyword) {
    //         $q->where('name', 'like', '%' . $keyword . '%')
    //           ->orWhere('email', 'like', '%' . $keyword . '%')
    //           ->orWhere('phone', 'like', '%' . $keyword . '%')
    //           ->orWhere('gender', 'like', '%' . $keyword . '%');
    //     });
    // }

    // $blogs = $query->paginate($recordsPerPage);

    // $data = [
    //     'keyword' => $keyword,
    //     'r_page' => $recordsPerPage,
    //     'order_by' => $sec_keyword,
    // ];
    //     return view('admin/register/userlist', compact('blogs', 'data'));
    // }


    public function userlist(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 25);
        $sec_keyword = $request->input('order_by');
    
        // Base query
        $query = DB::table('seller')
            ->where('seller.verify', 1) // Prefix 'seller' to avoid ambiguity
            ->whereRaw('FIND_IN_SET(?, seller.acc_type)', [3]); // Prefix acc_type with 'seller'
    
        // Apply keyword search if provided
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('seller.name', 'like', '%' . $keyword . '%')
                  ->orWhere('seller.email', 'like', '%' . $keyword . '%')
                  ->orWhere('seller.phone', 'like', '%' . $keyword . '%')
                  ->orWhere('seller.gender', 'like', '%' . $keyword . '%'); // Prefix all columns with 'seller'
            });
        }
    
        // Apply ordering by ref_by frequency if sec_keyword == 'one'
        if ($sec_keyword == 'one') {
            $query->select('seller.*')
                ->leftJoin(DB::raw('(SELECT ref_by, COUNT(*) as ref_count FROM seller GROUP BY ref_by) as ref_counts'), 'seller.ref_by', '=', 'ref_counts.ref_by')
                ->orderBy('ref_counts.ref_count', 'asc');
        }
    
        // Paginate the results
        $blogs = $query->paginate($recordsPerPage);
    
        $data = [
            'keyword' => $keyword,
            'r_page' => $recordsPerPage,
            'order_by' => $sec_keyword,
        ];
    
        return view('admin/register/userlist', compact('blogs', 'data'));
    }
    



    public function contractorlist(Request $request)
    {
        $keyword = $request->input('keyword');
    $recordsPerPage = $request->input('r_page', 15);

    $query = DB::table('seller')
        ->where('verify', 1)
        ->whereRaw('FIND_IN_SET(?, acc_type)', [2]);

    if ($keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
              ->orWhere('email', 'like', '%' . $keyword . '%')
              ->orWhere('phone', 'like', '%' . $keyword . '%')
              ->orWhere('gender', 'like', '%' . $keyword . '%');
        });
    }

    $blogs = $query->paginate($recordsPerPage);

    $data = [
        'keyword' => $keyword,
        'r_page' => $recordsPerPage,
    ];
        return view('admin/register/contractorlist', compact('blogs', 'data'));
    }
    public function buyerlist(Request $request)
    {
        $keyword = $request->input('keyword');
    $recordsPerPage = $request->input('r_page', 15);

    $query = DB::table('seller')
        ->where('verify', 1)
        ->whereRaw('FIND_IN_SET(?, acc_type)', [4]);

    if ($keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
              ->orWhere('email', 'like', '%' . $keyword . '%')
              ->orWhere('phone', 'like', '%' . $keyword . '%')
              ->orWhere('gender', 'like', '%' . $keyword . '%');
        });
    }

    $blogs = $query->paginate($recordsPerPage);

    $data = [
        'keyword' => $keyword,
        'r_page' => $recordsPerPage,
    ];
        return view('admin/register/buyerlist', compact('blogs', 'data'));
    }


// active deactive ------------------------------------------------

public function buyeractive($id){
    $inserted = DB::table('buyer')->where('id', $id)->update(['status' => 0]);
    if($inserted)
    {
      return back()->with('success', 'Status Has Been Changed.');
  }else{
      return back()->with('error', 'Updation Failed.');
  }
  }

  public function buyerdeactive($id){
    $inserted = DB::table('buyer')->where('id', $id)->update(['status' => 1]);
    if($inserted)
    {
      return back()->with('success', 'Status Has Been Changed.');
  }else{
      return back()->with('error', 'Updation Failed.');
  }
  }
// active deactive ------------------------------------------------

public function selleractive($id){
    $inserted = DB::table('seller')->where('id', $id)->update(['status' => 0]);
    if($inserted)
    {
      return back()->with('success', 'Status Has Been Changed.');
  }else{
      return back()->with('error', 'Updation Failed.');
  }
  }

  public function sellerdeactive($id){
    $inserted = DB::table('seller')->where('id', $id)->update(['status' => 1]);
    if($inserted)
    {
      return back()->with('success', 'Status Has Been Changed.');
  }else{
      return back()->with('error', 'Updation Failed.');
  }
  }
  
  
  
  
    // active deactive ------------------------------------------------

    public function activeuser($id){
      $inserted = DB::table('seller')->where('id', $id)->update(['status' => 0]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }

    public function deactiveuser($id){
      $inserted = DB::table('seller')->where('id', $id)->update(['status' => 1]);
      if($inserted)
      {
        return back()->with('success', 'Status Has Been Changed.');
    }else{
        return back()->with('error', 'Updation Failed.');
    }
    }



   

    public function joinmember(Request $request , $id)
    {
    $keyword = $request->input('keyword');
    $recordsPerPage = $request->input('r_page', 50);

    $query = DB::table('seller')
        ->where('verify', 1)
        ->where('ref_by', $id);

    if ($keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
              ->orWhere('email', 'like', '%' . $keyword . '%')
              ->orWhere('phone', 'like', '%' . $keyword . '%')
              ->orWhere('gender', 'like', '%' . $keyword . '%');
        });
    }

    $blogs = $query->paginate($recordsPerPage);

    $data = [
        'keyword' => $keyword,
        'r_page' => $recordsPerPage,
    ];
        return view('admin/register/memberlist', compact('blogs', 'data'));
    }


}