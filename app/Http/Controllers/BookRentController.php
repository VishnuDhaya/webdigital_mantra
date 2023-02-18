<?php

namespace App\Http\Controllers;

use JWTAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Rentbook;
use Illuminate\Support\Facades\Log;


class BookRentController extends Controller
{
    public function add_rent_book(Request $req){

        $data['user_id'] = JWTAuth::user()->u_id;
        $data['book_id'] = $req->b_id;
        $data['rent_status'] = "taken";
        $data['taken_date'] = Carbon::now(); 
        $res = RentBook::create($data);
        $result = ($res) ? ['status' => 'success', 'msg' => 'Book rented successfully'] : ['status' => 'failed', 'msg' => 'Operation failed'];
        return response()->json($result);

    }

    public function return_rent_book($b_id){

        $data = Rentbook::where([
                                    ['user_id', JWTAuth::user()->u_id],
                                    ['book_id', $b_id]
                            ])->first();
        $data->rent_status = 'return';
        $data->return_date = Carbon::now();  
        $res = $data->save();
        $result = ($res) ? ['status' => 'success', 'msg' => 'Return book successfully'] : ['status' => 'failed', 'msg' => 'Operation failed'];
        return response()->json($result);
    }

    public function all_rented_detail(Request $req){
        return Rentbook::all();
    }
}
