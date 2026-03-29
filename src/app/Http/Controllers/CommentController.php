<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi,
    ) {}

    public function store(Request $request, int $postId): RedirectResponse
    {
        $userRoles = session('user.roles', []);
        $hasNonGuestRole = !empty($userRoles) && count(array_filter($userRoles, fn($r) => $r !== 'guest')) > 0;

        if (!$hasNonGuestRole) {
            abort(403);
        }

        $request->validate([
            'content' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        $result = $this->blogApi->createComment($postId, $request->input('content'));

        if ($result['success'] && isset($result['data']['id'])) {
            $this->blogApi->approveComment($result['data']['id']);

            return redirect(route('post.show', $postId) . '#comments')
                ->with('comment_success', true);
        }

        $firstError = collect($result['errors'])->flatten()->first()
            ?? __('comments.store_error');

        return redirect(route('post.show', $postId) . '#comments')
            ->withErrors(['comment_content' => $firstError])
            ->withInput();
    }
}
