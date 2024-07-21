<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\group_info;
use Exception;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index() {
        try{
            $user_id = auth('api')->user()->id;
            $data = group::where('user_id', $user_id)->get();

            if(!$data)
                return response()->json(["message" => 'Data Not Found!'],204);

            return response()->json(["message" => 'success!', 'data' => $data],200);
        }catch(Exception $e){
            return response()->json(["error" => 'oops, Something Went wrong!'],500);
        }
    }
    // maybe fault
    public function show($id) {
        try{
            $user_id = auth('api')->user()->id;
            $data = group::where('user_id', $user_id)->where('id', $id)->with('group_info')->first();
            if(!$data)
                return response()->json(['error' => 'Data Not Found!'],402);
            
            return response()->json(['message' => 'success!', 'data' => $data],200);
            
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
    public function delete($id) {
        try{
            $user_id = auth('api')->user()->id;
            $group = group::where('id', $id)->where('user_id', $user_id)->first();
            if($group){
                $group_info = group_info::where('group_id', $group->id);
                if($group_info){
                    $group_info->delete();
                    $group->delete();
                }
            }else{
                return response()->json(['error' => 'Data Not Found!'],204);
            }
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
    public function edit(Request $request, $id) {
        try{
            $user_id = auth('api')->user()->id;
            $dec = $request->input('dec') ? $request->input('dec') : null;
            $total = $request->input('total') ? $request->input('total') : 0.00;
            $group = group::where('id', $id)->where('user_id', $user_id);
            $group->first();
            if($group){
                $group->update([
                    'name' => $request->input('name'),
                    'dec' => $dec,
                    'totle' => $total,
                ]);

                return response()->json(['message' => 'success'],200);
            }else{
                return response()->json(['error' => 'Data not Found!'],204);
            }
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
    public function store(Request $request) {
        try{
            $user_id = auth('api')->user()->id;
            $data = group::create([
                'name' => $request->input('name'),
                'dec' => $request->input('dec'),
                'totle' => $request->total,
                'user_id' => $user_id,
            ]);
            return response()->json(['message' => 'success'],200);
        }catch(Exception $e){
            return response()->json(['error' => 'Something went wrong!'],500);
        }
    }
    public function storeDetails(Request $request,$group_id) {
        try{
            $user_id = auth('api')->user()->id;
            $group = group::where('id', $group_id)->where('user_id', $user_id)->get();
            if($group){
                for($j = 0; $j < count($request->input('name')); $j++){
                    group_info::create([
                        'group_id' => $group_id,
                        'name' => $request->input('name')[$j],
                        'paid' => !empty($request->input('paid')[$j])??$request->input('paid')[$j],
                        'amm_to_paid' => !empty($request->input('amm_to_paid')[$j])?$request->input('amm_to_paid')[$j]:0,
                    ]);
                }
                
                return response()->json(['message' => 'success'],200);
            }else{
                return response()->json(['error' => 'Data Not Found!'],204);
            }
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
    public function deleteDetail($id) {
        try{
            $user_id = auth('api')->user()->id;
            $group_info = group_info::find($id);
            if($group_info){
                $group = group::where('id', $group_info->group_id)->where('user_id', $user_id)->first();
                if($group){
                    $group_info->delete();
                    return response()->json(['message' => 'success'],200);
                }else{
                    return response()->json(['error' => 'Data Not Found!'],204);
                }   
            }else{
                return response()->json(['error' => 'Data Not Found!'],204);
            }
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
    public function editDetail(Request $request, $id) {
        try{
            $user_id = auth('api')->user()->id;
            $paid = $request->input('paid');
            $data_id = $request->input('id');
            $name = $request->input('name');
            $amm_to_paid = $request->input('amm_to_paid');
            $isPaid = $request->input('isPaid');
            $paidby = $request->input('paidby');
            $totla = $request->input('total');
            $group = group::where('id', $id)->where('user_id', $user_id)->first();
            $group_info = null;
            if($group){
                while($name){
                    $i=0;
                    $group_info = group_info::where('id', $data_id[$i])->first();
                    if($group_info){
                            $group_info->update([
                                'name' => $request->input('name')[$i],
                                'paid' => isset($paid[$i]) ? $paid[$i] : null,
                                'amm_to_paid' => isset($amm_to_paid[$i]) ?? $amm_to_paid[$i],
                                'isPaid' => isset($isPaid[$i]) ?? $isPaid[$i],
                                'paidby' => isset($paidby[$i]) ? $paidby[$i] : null,
                            ]);
                        $i++;
                        return response()->json(['message' => 'success'],200);
                    }
                }
                $group->update([
                    'totle' => $totla
                ]);
            }else{
                return response()->json(['error' => 'Data not Found!'],204);
            }
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
    public function calulate(Request $request, $group_id) {
        try{
            $user_id = auth('api')->user()->id;
            $group = group::where('id', $group_id)->where('user_id', $user_id)->first();
            if($group){
                $group_infos = group_info::where('group_id', $group->id)->get();
                if($group_infos){
                    $total = $group->total;
                    $par_person = $total / count($group_infos);
                    $person = [];
                    foreach($group_infos as $info){
                        $info->update([
                            'amm_to_paid' => $par_person - $info->paid
                        ]);
                    }
                    return response()->json(['message' => 'success'],200);
                }else{
                    return response()->json(['error' => 'Data Not Found!'],204);
                }
            }else{
                return response()->json(['error' => 'Data Not Found!'],204);
            }
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
    public function markAsPaid(Request $request, $id) {
        try{
            $user_id = auth('api')->user()->id;
            $group_info = group_info::find($id);
            if($group_info){
                $group = group::where('id', $group_info->id)->where('user_id', $user_id)->first();
                if($group){
                    $group->update([
                        'isPaid' => ($request->input('isPaid') == 1) ? 1 : 0,
                        'paidby' => $request->input('paidby'),
                    ]);
                    return response()->json(['message' => 'success'],200);
                }else{                    
                    return response()->json(['error' => 'Data Not Found!'],402);
                }
                return response()->json(['error' => 'Data Not Found!'],404);
            }else{
                return response()->json(['error' => 'Data Not Found!'],404);
            }
        }catch(Exception $e){
            return response()->json(['error' => 'Something Went Wrong!'],500);
        }
    }
}