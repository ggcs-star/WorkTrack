<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $comments = TaskComment::with(['user.profile', 'task'])
            ->visibleTo($request->user())
            ->latest()
            ->paginate(20);

        return view('activity.index', ['comments' => $comments]);
    }
}
