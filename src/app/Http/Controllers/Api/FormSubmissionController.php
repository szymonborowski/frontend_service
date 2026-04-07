<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = FormSubmission::query()->orderByDesc('created_at');

        if ($request->filled('form_type')) {
            $query->where('form_type', $request->input('form_type'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('payload', 'like', "%{$search}%");
        }

        $submissions = $query->paginate($request->input('per_page', 15));

        return response()->json($submissions);
    }

    public function show(int $id): JsonResponse
    {
        $submission = FormSubmission::findOrFail($id);

        return response()->json($submission);
    }

    public function destroy(int $id): JsonResponse
    {
        FormSubmission::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
