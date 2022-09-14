<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\DiscussionForum;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use App\Models\SubComment;
use Illuminate\Http\Request;

class SubCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OnlineClass $online_class, OnlineClassContent $content, DiscussionForum $forum, Comment $comment)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        SubComment::create([
            'comment_id' => $comment->id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment,
        ]);

        return $this->acceptedResponse('New sub comment created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineClass $online_class, OnlineClassContent $content, DiscussionForum $forum, Comment $comment, SubComment $sub_comment)
    {
        abort_if($sub_comment->user_id != auth()->user()->id, 403, 'Forbidden.');

        $request->validate([
            'comment' => 'required'
        ]);

        $sub_comment->comment = $request->comment;
        $sub_comment->edited = true;
        $sub_comment->save();
        return $this->acceptedResponse('Sub comment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineClass $online_class, OnlineClassContent $content, DiscussionForum $forum, Comment $comment, SubComment $sub_comment)
    {
        abort_if($sub_comment->user_id != auth()->user()->id, 403, 'Forbidden.');

        $sub_comment->delete();
        return $this->successResponse('Your sub comment deleted successfully');
    }
}
