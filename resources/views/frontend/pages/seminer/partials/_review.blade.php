@php
    // Normalize $review for both Eloquent model and plain array/stdClass from JSON
    $r = $review;
    if (is_array($r)) {
        $r = (object) $r;
    }

    $reviewId = $r->id ?? null;
    $level = $level ?? 0; // Track nesting level (0 = top level, 1 = reply, 2 = reply to reply)
    $maxLevel = 2; // Maximum 3 levels (0, 1, 2) like Facebook

    // Author name
    $reviewAuthor = 'Anonymous';
    if (isset($r->user) && is_object($r->user) && !empty($r->user->first_name)) {
        $reviewAuthor = $r->user->first_name;
    } elseif (!empty($r->name)) {
        $reviewAuthor = $r->name;
    }

    // Created at
    $created = '';
    if (!empty($r->created_at)) {
        try {
            if (is_object($r->created_at) && method_exists($r->created_at, 'diffForHumans')) {
                $created = $r->created_at->diffForHumans();
            } else {
                $created = \Carbon\Carbon::parse($r->created_at)->diffForHumans();
            }
        } catch (\Exception $e) {
            $created = (string) $r->created_at;
        }
    }

    $rating = $r->rating ?? null;
    $commentText = $r->comment ?? ($r->review ?? '');

    // Truncate long comments
    $maxLength = 150;
    $isLongComment = strlen($commentText) > $maxLength;
    $shortComment = $isLongComment ? substr($commentText, 0, $maxLength) : $commentText;

    // Simple reply processing - only show replies if they actually exist and have content
    $replies = collect();
    $totalReplies = 0;

    if (!empty($r->replies)) {
        $rawReplies = is_array($r->replies) ? $r->replies : $r->replies->toArray();

        // Filter out empty replies and normalize to objects
        $replies = collect($rawReplies)
            ->filter(function ($reply) {
                if (empty($reply)) {
                    return false;
                }

                $comment = '';
                if (is_array($reply)) {
                    $comment = $reply['comment'] ?? '';
                } elseif (is_object($reply)) {
                    $comment = $reply->comment ?? '';
                }

                return !empty(trim($comment));
            })
            ->map(function ($reply) {
                return is_array($reply) ? (object) $reply : $reply;
            });

        // Count total replies recursively
        $countReplies = function ($items) use (&$countReplies) {
            $count = 0;
            foreach ($items as $item) {
                $count++; // Count this reply
                if (!empty($item->replies)) {
                    $nested = collect($item->replies)->filter(function ($r) {
                        $comment = is_array($r) ? $r['comment'] ?? '' : $r->comment ?? '';
                        return !empty(trim($comment));
                    });
                    $count += $countReplies($nested);
                }
            }
            return $count;
        };

        $totalReplies = $countReplies($replies);
    }

    $canReply = $level < $maxLevel;
    $showRepliesButton = $totalReplies > 0;
@endphp

<div class="facebook-comment {{ $level > 0 ? 'reply-comment' : 'main-comment' }} {{ $level >= 2 ? 'last-level' : '' }}"
    id="review-{{ $reviewId ?? 'anon' }}" data-level="{{ $level }}" data-review-id="{{ $reviewId ?? '' }}"
    data-total-replies="{{ $totalReplies }}">

    <div class="comment-content">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <div class="comment-avatar {{ $level > 0 ? 'reply-avatar' : 'main-avatar' }}">
                    {{ strtoupper(substr($reviewAuthor, 0, 1)) }}
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="comment-bubble">
                    <div class="comment-header">
                        <strong class="comment-author">{{ $reviewAuthor }}</strong>
                        {{-- @if (isset($r->is_admin) && $r->is_admin)
                            <span class="admin-badge">Admin</span>
                        @endif --}}
                    </div>

                    @if ($rating && $level === 0)
                        <div class="rating-stars">
                            @for ($i = 0; $i < $rating; $i++)
                                ★
                            @endfor
                        </div>
                    @endif

                    <div class="comment-text">
                        <span class="comment-short {{ $isLongComment ? '' : 'd-none' }}">
                            {{ $shortComment }}...
                        </span>
                        <span class="comment-full {{ !$isLongComment ? '' : 'd-none' }}">
                            {{ $commentText }}
                        </span>
                        @if ($isLongComment)
                            <button class="see-more-btn btn-link" onclick="toggleComment(this)">See more</button>
                        @endif
                    </div>
                </div>

                <div class="comment-actions">
                    @if ($canReply)
                        <button class="action-btn reply-btn" data-id="{{ $reviewId ?? '' }}">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    @endif

                    @if ($showRepliesButton)
                        @if ($level === 0)
                            <button class="action-btn view-replies-btn" onclick="toggleReplies(this)">
                                <i class="fas fa-comments"></i> View {{ $totalReplies }}
                                {{ $totalReplies === 1 ? 'reply' : 'replies' }}
                            </button>
                        @else
                            <button class="action-btn view-replies-btn" onclick="toggleReplies(this)">
                                <i class="fas fa-comments"></i> {{ $totalReplies }}
                                {{ $totalReplies === 1 ? 'reply' : 'replies' }}
                            </button>
                        @endif
                    @endif

                    <span class="comment-time">{{ $created }}</span>
                </div>

                {{-- Reply Form --}}
                @if ($canReply)
                    <div class="reply-form-container d-none">
                        <form class="reply-form" method="POST"
                            action="{{ route('seminar.review.reply', $reviewId ?? '') }}">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $reviewId ?? '' }}">
                            <input type="hidden" name="seminar_id" value="{{ request()->route('id') ?? '' }}">

                            @if (!auth()->check())
                                <div class="mb-2">
                                    <input type="text" name="name" class="form-control form-control-sm"
                                        placeholder="Your name" required>
                                </div>
                                <div class="mb-2">
                                    <input type="email" name="email" class="form-control form-control-sm"
                                        placeholder="Your email" required>
                                </div>
                            @endif

                            <div class="mb-2">
                                <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="Write a reply..." required></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">Reply</button>
                                <button type="button" class="btn btn-link btn-sm cancel-reply">Cancel</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Replies container --}}
    @if ($showRepliesButton && $replies->count() > 0)
        <div class="replies-container {{ $level === 0 ? 'main-replies' : 'nested-replies' }}" {{-- style="display: none;" --}}
            style="{{ $level >= 1 ? 'display: none;' : '' }}" data-level="{{ $level }}">
            @foreach ($replies as $reply)
                @include('frontend.pages.seminer.partials._review', [
                    'review' => $reply,
                    'level' => $level + 1,
                ])
            @endforeach
        </div>
    @endif
</div>

@push('styles')
    <style>
        /* Facebook-like Comment System Styles */
        .facebook-comment {
            margin-bottom: 12px;
            position: relative;
        }

        .facebook-comment.main-comment {
            margin-bottom: 20px;
        }

        .facebook-comment.reply-comment {
            margin-left: 44px;
            margin-bottom: 8px;
        }

        .facebook-comment.last-level {
            opacity: 0.9;
        }

        /* Avatar Styles */
        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .comment-avatar.main-avatar {
            width: 44px;
            height: 44px;
            font-size: 16px;
        }

        .comment-avatar.reply-avatar {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        /* Comment Bubble */
        .comment-bubble {
            background: #f0f2f5;
            border-radius: 18px;
            padding: 12px 16px;
            position: relative;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .facebook-comment.reply-comment .comment-bubble {
            background: #e4e6ea;
            border-radius: 16px;
            padding: 8px 12px;
        }

        /* Comment Header */
        .comment-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .comment-author {
            font-weight: 600;
            font-size: 14px;
            color: #1c1e21;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Rating Stars */
        .rating-stars {
            color: #ffa500;
            font-size: 14px;
            margin-bottom: 8px;
        }

        /* Comment Text */
        .comment-text {
            font-size: 14px;
            line-height: 1.4;
            color: #1c1e21;
            word-wrap: break-word;
        }

        .see-more-btn {
            color: #65676b;
            font-size: 14px;
            font-weight: 600;
            padding: 0;
            margin-left: 4px;
            text-decoration: none !important;
        }

        .see-more-btn:hover {
            color: #1877f2;
            text-decoration: underline !important;
        }

        /* Comment Actions */
        .comment-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
            padding-left: 16px;
        }

        .action-btn {
            background: none;
            border: none;
            color: #65676b;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .action-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            color: #1877f2;
        }

        .action-btn i {
            font-size: 10px;
        }

        .comment-time {
            font-size: 12px;
            color: #65676b;
            margin-left: auto;
        }

        /* Reply Form - keep container unstyled to avoid display conflicts; style the inner form instead */
        .reply-form-container {
            margin-top: 8px;
        }

        .reply-form {
            background: #ffffff;
            border: 1px solid #dddfe2;
            border-radius: 16px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            box-sizing: border-box;
        }

        .reply-form .form-control {
            border: 1px solid #dddfe2;
            border-radius: 16px;
            font-size: 14px;
        }

        .reply-form .form-control:focus {
            border-color: #1877f2;
            box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.2);
        }

        .reply-form .btn {
            font-size: 12px;
            padding: 4px 12px;
        }

        /* Replies Container */
        .replies-container {
            margin-top: 12px;
        }

        .replies-container.main-replies {
            margin-left: 44px;
            padding-left: 16px;
            border-left: 2px solid #e4e6ea;
        }

        .replies-container.nested-replies {
            margin-left: 32px;
            padding-left: 12px;
            border-left: 1px solid #e4e6ea;
        }

        /* Hover Effects */
        .facebook-comment:hover .comment-actions {
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .facebook-comment.reply-comment {
                margin-left: 20px;
            }

            .comment-actions {
                flex-wrap: wrap;
                gap: 8px;
            }

            .comment-time {
                margin-left: 0;
                margin-top: 4px;
                width: 100%;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .facebook-comment {
            animation: fadeIn 0.3s ease;
        }

        /* Loading States */
        .comment-loading {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #1877f2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function toggleComment(btn) {
            const commentText = btn.closest('.comment-text');
            const shortText = commentText.querySelector('.comment-short');
            const fullText = commentText.querySelector('.comment-full');

            if (shortText.classList.contains('d-none')) {
                shortText.classList.remove('d-none');
                fullText.classList.add('d-none');
                btn.textContent = 'See more';
            } else {
                shortText.classList.add('d-none');
                fullText.classList.remove('d-none');
                btn.textContent = 'See less';
            }
        }

        function toggleReplies(btn) {
            const comment = btn.closest('.facebook-comment');
            const repliesContainer = comment.querySelector('.replies-container');
            const level = parseInt(comment.dataset.level) || 0;
            const totalReplies = parseInt(comment.dataset.totalReplies) || 0;

            if (!repliesContainer) {
                console.warn('No replies container found');
                return;
            }

            if (repliesContainer.style.display === 'none' || repliesContainer.style.display === '') {
                repliesContainer.style.display = 'block';
                if (level === 0) {
                    btn.innerHTML = '<i class="fas fa-comments"></i> Hide replies';
                } else {
                    btn.innerHTML = '<i class="fas fa-comments"></i> Hide replies';
                }
            } else {
                repliesContainer.style.display = 'none';
                if (level === 0) {
                    btn.innerHTML =
                        `<i class="fas fa-comments"></i> View ${totalReplies} ${totalReplies === 1 ? 'reply' : 'replies'}`;
                } else {
                    btn.innerHTML =
                        `<i class="fas fa-comments"></i> ${totalReplies} ${totalReplies === 1 ? 'reply' : 'replies'}`;
                }
            }
        }

        // Handle reply button clicks (guard against multiple bindings from recursive partial)
        if (!window.__seminarReplyBound) {
            window.__seminarReplyBound = true;
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('reply-btn') || e.target.closest('.reply-btn')) {
                    e.preventDefault();
                    const btn = e.target.classList.contains('reply-btn') ? e.target : e.target.closest(
                    '.reply-btn');
                    const comment = btn.closest('.facebook-comment');
                    const replyForm = comment.querySelector('.reply-form-container');

                    console.log('Reply button clicked', {
                        btn,
                        comment,
                        replyForm
                    });

                    if (!replyForm) {
                        console.error('Reply form container not found');
                        return;
                    }

                    // Hide all other reply forms first
                    document.querySelectorAll('.reply-form-container').forEach(form => {
                        if (form !== replyForm) {
                            form.classList.add('d-none');
                            form.classList.remove('d-block');
                            form.style.display = 'none';
                        }
                    });

                    // Toggle current form using Bootstrap classes
                    if (replyForm.classList.contains('d-none')) {
                        // Show form
                        replyForm.classList.remove('d-none');
                        replyForm.classList.add('d-block');
                        // Force visibility against any external CSS
                        replyForm.style.setProperty('display', 'block', 'important');
                        replyForm.style.setProperty('visibility', 'visible', 'important');
                        replyForm.style.setProperty('opacity', '1', 'important');
                        replyForm.style.setProperty('position', 'relative', 'important');
                        replyForm.style.setProperty('z-index', '100', 'important');
                        // Temporary outline to confirm visibility
                        // replyForm.style.setProperty('outline', '2px solid #ff5722', 'important');
                        // Ensure it's in view
                        try {
                            replyForm.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        } catch (_) {}
                        console.log('Showing reply form');

                        // Focus textarea
                        setTimeout(() => {
                            const textarea = replyForm.querySelector('textarea[name="comment"]');
                            if (textarea) {
                                textarea.focus();
                                console.log('Focused textarea');
                            }
                        }, 100);
                    } else {
                        // Hide form
                        replyForm.classList.add('d-none');
                        replyForm.classList.remove('d-block');
                        replyForm.style.setProperty('display', 'none', 'important');
                        // Clean any temporary inline styles
                        replyForm.style.removeProperty('visibility');
                        replyForm.style.removeProperty('opacity');
                        replyForm.style.removeProperty('position');
                        replyForm.style.removeProperty('z-index');
                        replyForm.style.removeProperty('outline');
                        console.log('Hiding reply form');
                    }
                }

                if (e.target.classList.contains('cancel-reply') || e.target.closest('.cancel-reply')) {
                    e.preventDefault();
                    const btn = e.target.classList.contains('cancel-reply') ? e.target : e.target.closest(
                        '.cancel-reply');
                    const comment = btn.closest('.facebook-comment');
                    const replyForm = comment.querySelector('.reply-form-container');
                    if (replyForm) {
                        replyForm.classList.add('d-none');
                        replyForm.classList.remove('d-block');
                        replyForm.style.setProperty('display', 'none', 'important');
                        replyForm.style.removeProperty('visibility');
                        replyForm.style.removeProperty('opacity');
                        replyForm.style.removeProperty('position');
                        replyForm.style.removeProperty('z-index');
                        replyForm.style.removeProperty('outline');
                        console.log('Cancel button - hiding form');
                    }
                }
            });

            // Auto-resize textareas
            document.addEventListener('input', function(e) {
                if (e.target.tagName === 'TEXTAREA') {
                    e.target.style.height = 'auto';
                    e.target.style.height = e.target.scrollHeight + 'px';
                }
            });
        }
    </script>
@endpush
