<?php

namespace App\Modules\Management\SeminerManagement\SeminerReviews\Controller;

use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\GetAllData;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\DestroyData;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\GetSingleData;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\StoreData;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\UpdateData;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\UpdateStatus;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\SoftDelete;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\RestoreData;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\ImportData;
use App\Modules\Management\SeminerManagement\SeminerReviews\Validations\BulkActionsValidation;
use App\Modules\Management\SeminerManagement\SeminerReviews\Validations\DataStoreValidation;
use App\Modules\Management\SeminerManagement\SeminerReviews\Actions\BulkActions;
use App\Http\Controllers\Controller as ControllersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Modules\Management\SeminerManagement\Seminer\Models\Model as Seminars;
use App\Modules\Management\SeminerManagement\SeminerReviews\Models\Model as SeminarReviews;


class Controller extends ControllersController
{

    public function index()
    {

        $data = GetAllData::execute();
        return $data;
    }

    public function store(DataStoreValidation $request)
    {
        $data = StoreData::execute($request);
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }
    public function updateStatus()
    {
        $data = UpdateStatus::execute();
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }
    public function destroy($slug)
    {
        $data = DestroyData::execute($slug);
        return $data;
    }
    public function restore()
    {
        $data = RestoreData::execute();
        return $data;
    }
    public function import()
    {
        $data = ImportData::execute();
        return $data;
    }
    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }

    /**
     * Get seminar details with reviews for comment management
     */
    public function getSeminarDetails($id)
    {
        $seminar = Seminars::with(['reviews' => function ($q) {
            $q->orderBy('id', 'desc');
        }])->find($id);

        if (!$seminar) {
            return response()->json(['error' => 'Seminar not found'], 404);
        }

        // For each top-level review, attach replies from the JSON column and any legacy child rows
        foreach ($seminar->reviews as $review) {
            // replies from JSON column
            $jsonReplies = [];
            if (!empty($review->comment_reply)) {
                $jsonReplies = json_decode($review->comment_reply, true) ?: [];
            }

            // legacy replies stored as separate rows (parent_id)
            $legacyReplies = [];
            try {
                $legacy = SeminarReviews::where('parent_id', $review->id)->orderBy('id', 'asc')->get();
                foreach ($legacy as $lr) {
                    $legacyReplies[] = [
                        'id' => $lr->id,
                        'name' => $lr->name,
                        'email' => $lr->email,
                        'user_id' => $lr->user_id,
                        'comment' => $lr->comment,
                        'rating' => $lr->rating,
                        'is_admin' => $lr->is_admin ?? false,
                        'created_at' => optional($lr->created_at)->toDateTimeString(),
                        'replies' => [],
                    ];
                }
            } catch (\Exception $e) {
                $legacyReplies = [];
            }

            $all = array_merge($jsonReplies, $legacyReplies);
            // attach as collection of simple objects
            $review->replies = collect($all)->map(function ($r) {
                if (is_array($r)) return (object) $r;
                return $r;
            });
        }

        return response()->json([
            'seminar' => $seminar,
            'seminars' => Seminars::where('date_time', '>', Carbon::today())
                ->where('status', 'active')
                ->where('id', '!=', $seminar->id)
                ->get()
        ]);
    }

    /**
     * Submit a reply to a review
     */
    public function submitReply(Request $request, $id)
    {

        $rules = [
            'comment' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $parentId = $request->parent_id ?? $request->review_id ?? null;

        // We need to support replying to either:
        // - a DB row (parent_id points to a SeminarReviews id)
        // - a nested JSON reply id (our JSON replies use string ids like r_...)

        // Try direct DB row first
        $parent = SeminarReviews::find($parentId);
        $replyData = [
            'id' => uniqid('r_'),
            'name' => 'Admin',
            // 'name' => config('app.name', 'Admin'),
            'email' => auth()->user()->email ?? $request->email,
            'user_id' => auth()->id(),
            'rating' => 0,
            'is_admin' => true,
            'comment' => $request->comment,
            'created_at' => now()->toDateTimeString(),
            'replies' => [],
        ];

        if ($parent) {
            // append as top-level parent's JSON replies
            $existing = [];
            if (!empty($parent->comment_reply)) {
                $existing = json_decode($parent->comment_reply, true) ?: [];
            }
            $existing[] = $replyData;
            $parent->comment_reply = json_encode($existing);
            $parent->save();
            return response()->json(['success' => true, 'message' => 'Reply submitted successfully']);
        }

        // If not a DB row, search through top-level reviews to find which one contains this JSON id
        $topLevel = SeminarReviews::where('seminar_id', $request->seminar_id)->get();
        foreach ($topLevel as $tl) {
            $payload = [];
            if (!empty($tl->comment_reply)) {
                $payload = json_decode($tl->comment_reply, true) ?: [];
            }

            // attempt recursive insert by reference
            if ($this->insertReplyIntoPayload($payload, $parentId, $replyData)) {
                $tl->comment_reply = json_encode($payload);
                $tl->save();
                return response()->json(['success' => true, 'message' => 'Reply submitted successfully']);
            }
        }

        return response()->json(['error' => 'Parent not found for nested reply'], 404);
    }

    /**
     * Recursively search the payload (array of nodes) by reference and insert replyData into the node with id == parentId.
     * Returns true if inserted.
     */
    protected function insertReplyIntoPayload(array &$nodes, $parentId, array $replyData)
    {
        foreach ($nodes as $idx => &$node) {
            if (isset($node['id']) && $node['id'] == $parentId) {
                if (!isset($node['replies']) || !is_array($node['replies'])) {
                    $node['replies'] = [];
                }
                $node['replies'][] = $replyData;
                return true;
            }
            if (isset($node['replies']) && is_array($node['replies'])) {
                if ($this->insertReplyIntoPayload($node['replies'], $parentId, $replyData)) {
                    return true;
                }
            }
        }
        return false;
    }
}
