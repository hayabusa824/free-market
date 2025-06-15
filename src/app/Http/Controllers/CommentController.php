<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {

        $validated = $request->validated();

        Comment::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'comment' => $request->input('comment'),
        ]);

        // コメント数を増加
        $item->increment('comment_count');

        return redirect()->back();
    }

}
