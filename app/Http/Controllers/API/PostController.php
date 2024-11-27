<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\fileExists;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = post::all();
        return response()->json([
            'status' => true,
            'message' => 'All Posts',
            'data' => $data,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',

            ]
        );
        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Fails',
                'errors' => $validateUser->errors()->all(),
            ],401);
        }
        // Image showing

        $image = $request->image;
        $ext = $image->GetClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $image->move(public_path().'/uploads',$imageName);

        $post = post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Created Successfully',
            'post' => $post,

        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = post::select([
            'id',
            'title',
            'description',
            'image',
        ])->where(['id' => $id])->get();

        return response()->json([
            'status' => true,
            'message' => 'Single Post',
            'user' => $user,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',

            ]
        );
        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Fails',
                'errors' => $validateUser->errors()->all(),
            ],401);
        }

        $postImage = post::select('id', 'image')->where(['id' => $id])->get();

        if($request->image != '') {
        $path = public_path().'/uploads';

    }  if($postImage[0]->image != '' && $postImage[0]->image != null) {
        $old_file = $path. $postImage[0]->image;
        if(fileExists($old_file)) {
            unlink($old_file);
        }

        $image = $request->image;
        $ext = $image->GetClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $image->move(public_path().'/uploads',$imageName);

    } else{
        $imageName = $postImage->image;
    }

        // Image showing

        $post = post::where(['id' => $id])->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Updated Successfully',
            'post' => $post,

        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $imagePath = post::select('image')->where('id' , $id)->get();
        $filepath = public_path().'/uploads/'. $imagePath[0]['image'];
        unlink($filepath);
        $post = post::where('id',$id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post Deleted Successfully',
            'post' => $post,

        ],200);
    }
}
