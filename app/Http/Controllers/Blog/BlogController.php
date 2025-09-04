<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Modules\Management\BlogManagement\Blog\Models\Model as Blogs;
use App\Modules\Management\BlogManagement\BlogTag\Models\Model as BlogTags;
use App\Modules\Management\BlogManagement\BlogWriter\Models\Model as BlogWriters;
use App\Modules\Management\BlogManagement\BlogCategory\Models\Model as BlogsCategories;
use App\Modules\Management\CommunicationManagement\Subscriber\Models\Model as BlogSubscribers;


class BlogController extends Controller
{
    public function blog()
    {
        $category = request()->get('category');
        $cat_id = BlogsCategories::where('slug', $category)->value('id');

        $blog_categories = BlogsCategories::active()->get();
        $blog_tags = BlogTags::active()->get();
        $blog_writers = BlogWriters::active()->get();

        $blog_single = Blogs::where('is_published', 1)
            ->where('is_featured', 1)
            ->where('status', 'active')
            ->with('category')
            ->latest()
            ->first();

        $blogs = Blogs::where('is_published', 1)
            ->select([
                'id',
                'title',
                'is_featured',
                'blog_category_id',
                'images',
                'is_published',
                'slug',
                'creator',
                'created_at'
            ])
            ->where('status', 'active')
            ->when($blog_single, function ($query) use ($blog_single) {
                return $query->whereNotIn('id', [$blog_single->id]);
            })
            ->with('category');

        if ($category) {
            $blogs->where('blog_category_id', $cat_id);
        }

        $blogs = $blogs->paginate(6);

        return view('frontend.pages.blog.blog', compact('blog_categories', 'blog_tags', 'blog_writers', 'blog_single', 'blogs'));
    }

    public function blog_details($slug)
    {
        $blog = Blogs::where('slug', $slug)->where('is_published', 1)
            ->where('status', 'active')
            ->with(['category'])->first();

        return view('frontend.pages.blog.blog-details', compact('blog'));
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ], [
            'email.required' => 'Email is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'This email is already subscribed.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Subscription failed. ' . $validator->errors()->first());
        }

        // Subscribe the user to the blog
        BlogSubscribers::create([
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'You have successfully subscribed to the blog!');
    }
}
