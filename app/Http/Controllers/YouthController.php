<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Youth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class YouthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function youths()
    {
        $youths = Youth::with('user')->latest()->get();

        if($youths->count() > 0){
            return response()->json([
                'status'=> 200,
                'youths'=> $youths,
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'message'=> 'No record found',
            ],404);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=> ['required', 'min:3',Rule::unique('youths','name')],
            'address'=>'required',
            'contact_no'=>['required','numeric','digits:11'],
            'school'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors'=>$validator->messages()
            ],422);
        }else{
            $youth = Youth::create([
                'name'=> $request->name,
                'address'=> $request->address,
                'contact_no'=> $request->contact_no,
                'school'=> $request->school,
                'user_id'=>$id
            ]);
            if($youth){
                return response()->json([
                    'status'=> 200,
                    'message'=>'Added Successfully'
                ],200);
            }else{
                return response()->json([
                    'status'=> 500,
                    'message'=> 'Something went wrong'
                ],500);
            }

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Youth $youth,$id)
    {
        $youth = Youth::with('user')->find($id);
        if($youth){
            return response()->json([
                'status'=> 200,
                'data'=>$youth
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'errors'=> 'Post no found'
            ],404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=> ['required', 'min:3',Rule::unique('youths','name')->ignore($id, 'id')],
            'address'=>'required',
            'contact_no'=>['required','numeric','digits:11'],
            'school'=>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors'=>$validator->messages()
            ],422);
        }else{
                $youth=Youth::find($id);

            if($youth){
                $youth->update([
                    'name'=> $request->name,
                    'address'=> $request->address,
                    'contact_no'=> $request->contact_no,
                    'school'=> $request->school
                ]);
                return response()->json([
                    'status'=> 200,
                    'message'=>'Record Updated Successfully'
                ],200);
            }else{
                return response()->json([
                    'status'=> 404,
                    'message'=> 'Record not found'
                ],404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $youth = Youth::find($id);
        if($youth){
            $youth->delete();
            return response()->json([
                'status'=> 200,
                'message'=> 'Deleted Successfully'
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'errors'=> 'Post not found'
            ],404);
        }
    }
}
