<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function add_book(Request $req){

        $data['book_name'] = $req['book_name'];
        $data['author'] = $req['author'];

        if($req->file('cover_image')){
            $file = $req->file('cover_image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('images/book_images'), $filename);
            $data['cover_image'] = public_path().$filename;

        }
        $res = Book::create($data);
        $result = ($res) ? ['status' => 'success', 'msg' => 'Book created successfully'] : ['status' => 'failed', 'msg' => 'Operation failed'];
        return response()->json($result);
    }

    public function edit_book(Request $req){

        $data = Book::find($req->id);
        $data->book_name = $req['book_name'];
        $data->author = $req['author'];
        
        if($req->file('cover_image')){
            $file = $req->file('cover_image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('images/book_images'), $filename);
            $data['cover_image'] = public_path().$filename;

        }
        $res = $data->save();
        $result = ($res) ? ['status' => 'success', 'msg' => 'Book updated successfully'] : ['status' => 'failed', 'msg' => 'Operation failed'];
        return response()->json($result);
    }

    public function view_all_book(){
        return Book::all();
    }
    
    public function delete_book($id){
        Book::find($id)->delete();
        return response()->json(['status' => 'success', 'msg' => 'Book deleted successfully']);
    }
    
}
