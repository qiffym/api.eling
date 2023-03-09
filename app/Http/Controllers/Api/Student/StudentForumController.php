<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineClasses\DiscussionForumResource;
use App\Models\DiscussionForum;
use App\Models\OnlineClass;
use App\Models\OnlineClassContent;

class StudentForumController extends Controller
{
    public function show(OnlineClass $my_class, OnlineClassContent $content, DiscussionForum $forum)
    {
        try {
            return $this->successResponse('Detail discussion forum retrieved successfully', new DiscussionForumResource($forum));
        } catch (\Throwable $th) {
            return $this->notFoundResponse('Not Found.');
        }
    }
}
