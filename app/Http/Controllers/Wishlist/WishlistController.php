<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Management\CourseManagement\Course\Models\WishlistModel;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        $courseFromRoute = $request->route('course');

        $user = auth()->user();
        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to add to wishlist');
        }

        $courseId = (int) $courseFromRoute;

        // Check if already in wishlist
        $existing = WishlistModel::where('user_id', $user->id)->where('course_id', $courseId)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Course already in wishlist');
        }

        // Add to wishlist
        WishlistModel::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
        ]);

        return redirect()->back()->with('success', 'Course added to wishlist');
    }

    public function removeFromWishlist(Request $request)
    {
        $courseFromRoute = $request->route('course');

        $user = auth()->user();
        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to add to wishlist');
        }
        $courseId = (int) $courseFromRoute;

        // Remove from wishlist
        WishlistModel::where('user_id', $user->id)->where('course_id', $courseId)->forceDelete();

        return redirect()->back()->with('success', 'Course removed from wishlist');
    }

    public function viewWishlist(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to view your wishlist');
        }

        // Get wishlist with course details
        $wishlist = WishlistModel::with('course')
            ->where('user_id', $user->id)
            ->paginate(12);

        return view('frontend.pages.wishlist.index', compact('wishlist'));
    }
}
