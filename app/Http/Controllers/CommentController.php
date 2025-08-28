<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Issue $issue): JsonResponse
    {
        $comments = $issue->comments()
            ->with('issue') // Eager load the issue relationship
            ->latest()
            ->paginate(10);

        return response()->json([
            'comments' => $comments->items(),
            'pagination' => [
                'hasMore' => $comments->hasMorePages(),
                'nextPage' => $comments->nextPageUrl(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'issue_id' => 'required|exists:issues,id',
            'author_name' => 'required|string|max:255',
            'body' => 'required|string|min:1',
        ]);

        $comment = Comment::create($request->only(['issue_id', 'author_name', 'body']));

        // Load the issue relationship for the response
        $comment->load('issue');

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' => 'Comment added successfully.'
        ]);
    }
}