<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Issue $issue): JsonResponse
    {
        $comments = $issue->comments()
            ->with('issue')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'comments' => $comments->items(),
            'pagination' => [
                'hasMore' => $comments->hasMorePages(),
                'nextPage' => $comments->nextPageUrl(),
                'currentPage' => $comments->currentPage(),
                'total' => $comments->total(),
            ]
        ]);
    }

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $comment = Comment::create([
            'issue_id' => $validated['issue_id'],
            'author_name' => $validated['author_name'],
            'body' => $validated['body']
        ]);

        $comment->load('issue');

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' => 'Comment added successfully.'
        ], 201); 
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|min:1|max:2000',
        ]);

      

        $comment->update([
            'body' => $request->body
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' => 'Comment updated successfully.'
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
       

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }
}