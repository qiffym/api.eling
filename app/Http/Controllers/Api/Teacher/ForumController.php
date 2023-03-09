<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\DiscussionForumResource;
use App\Models\DiscussionForum;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OnlineClass $online_class, OnlineClassContent $content)
    {
        $forums = DiscussionForum::where('online_class_content_id', $content->id)->get();
        $data = collect($forums)->map(fn ($forum) => [
            'id' => $forum->id,
            'content_id' => $content->id,
            'content_of' => $content->title,
            'topic' => $forum->title,
            'description' => $forum->description,
            'created_at' => $forum->created_at->diffForHumans(),
        ]);

        return $this->successResponse('Discussion Forums Retrieved Successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OnlineClass $online_class, OnlineClassContent $content)
    {
        try {
            $request->validate([
                'topic' => 'required|string',
                'description' => 'nullable',
            ]);

            DiscussionForum::create([
                'online_class_content_id' => $content->id,
                'title' => $request->topic,
                'description' => $request->description,
            ]);

            return $this->acceptedResponse('New discussion forum created successfully');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OnlineClass $online_class, OnlineClassContent $content, DiscussionForum $forum)
    {
        try {
            return $this->successResponse('Detail discussion forum retrieved successfully', new DiscussionForumResource($forum));
        } catch (\Throwable $th) {
            return $this->notFoundResponse('Not Found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineClass $online_class, OnlineClassContent $content, DiscussionForum $forum)
    {
        try {
            $request->validate([
                'topic' => 'required|string',
                'description' => 'nullable',
            ]);

            $forum->title = $request->topic;
            $forum->description = $request->description;
            $forum->save();

            return $this->acceptedResponse('Discussion Forum updated successfully');
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
    public function destroy(OnlineClass $online_class, OnlineClassContent $content, DiscussionForum $forum)
    {
        $forum->delete();

        return $this->successResponse('Discussion Forum deleted successfully');
    }
}
