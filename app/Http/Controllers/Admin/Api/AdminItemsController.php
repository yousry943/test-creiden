<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Item;

class AdminItemsController extends Controller
{
    // CRUD custome post in  wordpress route 
    public function index()
    {
   
        $items = Item::where('post_type', 'item')
        ->where('post_status', 'publish')
        ->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required',
     
        ]);

   // create new custome post in wordpress

        $item = Item::create([
            'post_title' => $request->post_title,
            'post_content' => $request->post_content,
            'post_name' => $request->post_name,
            'author' => 1,
            'post_type' => 'item',
            'post_status' => 'publish',
        ]);

        return response()->json($item);
    }

    public function show($id)
    {
        $item = Item::find($id);
        return response()->json($item);
    }


        public function update(Request $request, $id)
        {
                $request->validate([
                    'post_title' => 'required',
                    'post_content' => 'required',
                ]);
        
                $item = Item::find($id);
                $item->update([
                    'post_title' => $request->post_title,
                    'post_content' => $request->post_content,
                    'post_name' => $request->post_name,
                    'author' => 1,
                    'post_type' => 'item',
                    'post_status' => 'publish',
                ]);
    
                return response()->json($item);
    
        }
    
            public function destroy($id)
            {
                $item = Item::find($id);
                $item->delete();
                return response()->json($item);
            }



}
