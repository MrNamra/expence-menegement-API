<?php

namespace App\Http\Controllers;

use App\Models\expense;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index() : JsonResponse {
        try{
            $user_id = auth('api')->user()->id;
            $data = expense::select('id','title','dec','price','created_at')->where('user_id', $user_id)->get();
            return response()->json(["message" => 'success', 'data' => $data],200);
        }catch(\Exception $e){
            dd($e);
            return response()->json(["error" => 'oops, Something Went wrong!'],500);
        }
    }

    public function store(Request $request) : JsonResponse {
        try{
            $user_id = auth('api')->user()->id;
            $data = expense::create([
                'user_id'=> $user_id,
                'title'=> $request->input('title'),
                'dec'=> $request->input('dec'),
                'price'=> $request->input('price'),
            ]);
            dd($data);
            $data = expense::where('user_id', $user_id)->get();
            return response()->json(["message" => 'success', 'data' => $data],200);
        }catch(\Exception $e){
            dd($e);
            return response()->json(["error" => 'oops, Something Went wrong!'],500);
        }
    }

    public function update(Request $request) : JsonResponse {
        try{
            $user_id = auth('api')->user()->id;
            $expense = expense::select('id','title','dec','price','created_at')->where('user_id',$user_id)->where('id',$request->input('id'))->first();
            if(!$expense)
                return response()->json(["message" => 'oops, Not Found!'],404);
            $expense->title = $request->input('title');
            $expense->dec = $request->input('dec');
            $expense->price = $request->input('price');
            $expense->save();

            return response()->json(["message" => 'success'],200);
        }catch(\Exception $e){
            dd($e);
            return response()->json(["error" => 'oops, Something Went wrong!'],500);
        }
    }

    public function show($id) : JsonResponse {
        try{
            $user_id = auth('api')->user()->id;
            $data = expense::select('id','user_id','title','dec','price','created_at','updated_at')
                    ->where('user_id',$user_id)->where('id',$id)->first();
            if(!$data){
                return response()->json(["error" => 'No Data Found'],404);
            }
            return response()->json([ 'data' => $data, "message" => 'success'],200);
        }catch(\Exception $e){
            dd($e);
            return response()->json(["error" => 'oops, Something Went wrong!'],500);
        }
    }
    public function delete(Request $request) : JsonResponse {
        try{
            $user_id = auth('api')->user()->id;
            $data = expense::where('user_id',$user_id)->where('id',$request->input('id'))->first();
            if(!$data)
                return response()->json(["message" => 'oops, Not Found!'],404);
            $data->delete();
            return response()->json(["message" => 'Deleted successfully!'],200);
        }catch(\Exception $e){
            dd($e);
            return response()->json(["error" => 'oops, Something Went wrong!'],500);
        }
    }
}
