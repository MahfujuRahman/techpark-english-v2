<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $image;
    public $published_at;
    public $what_is_this_course;
    public $why_is_this_course;
    public $slug;

    public function __construct($courseData)
    {
        // Extract individual properties so they're available directly in the template
        $this->title = $courseData['title'] ?? '';
        $this->image = $courseData['image'] ?? '';
        $this->published_at = $courseData['published_at'] ?? '';
        $this->what_is_this_course = $courseData['what_is_this_course'] ?? '';
        $this->why_is_this_course = $courseData['why_is_this_course'] ?? '';
        $this->slug = $courseData['slug'] ?? '';
    }

    public function build()
    {
        return $this->view('mail.subscriber_notification')
                    ->subject('New Course: ' . ($this->title ?: 'TechPark English Course'));
    }
}
