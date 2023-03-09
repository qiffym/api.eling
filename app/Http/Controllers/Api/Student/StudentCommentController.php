<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\DiscussionForum;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use Illuminate\Http\Request;

class StudentCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OnlineClass $my_class, OnlineClassContent $content, DiscussionForum $forum)
    {
        try {
            $request->validate([
                'comment' => 'required',
            ]);

            Comment::create([
                'discussion_forum_id' => $forum->id,
                'user_id' => auth()->user()->id,
                'comment' => $request->comment,
            ]);

            return $this->acceptedResponse('New comment created successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineClass $my_class, OnlineClassContent $content, DiscussionForum $forum, Comment $comment)
    {
        abort_if($comment->user_id != auth()->user()->id, 403, 'Forbidden.');

        try {
            $request->validate([
                'comment' => 'required',
            ]);

            $comment->comment = $request->comment;
            $comment->edited = true;
            $comment->save();

            return $this->acceptedResponse('Comment updated successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineClass $my_class, OnlineClassContent $content, DiscussionForum $forum, Comment $comment)
    {
        abort_if($comment->user_id != auth()->user()->id, 403, 'Forbidden.');

        $comment->delete();

        return $this->successResponse('Your comment deleted successfully');
    }
}
