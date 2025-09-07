<?php

namespace App\Modules\Management\CourseManagement\Course\Others;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Modules\Management\CommunicationManagement\Subscriber\Models\Model as Subscriber;
use App\Mail\CourseNotificationMail;

class SendSubscriberEmail implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $emails;

    public $data;

    public function __construct($data = [], $emails = null)
    {
        $this->emails = $emails ?: Subscriber::all()->pluck('email')->toArray();
        // Force conversion to array to prevent serialization issues
        if (is_object($data) && method_exists($data, 'toArray')) {
            $this->data = $data->toArray();
        } elseif (is_object($data) && method_exists($data, 'getAttributes')) {
            $this->data = $data->getAttributes();
        } else {
            $this->data = (array) $data;
        }
    }

    /**
     * Prepare the instance for serialization.
     */
    public function __sleep()
    {
        // Ensure data is always an array before serialization
        if (is_object($this->data)) {
            if (method_exists($this->data, 'toArray')) {
                $this->data = $this->data->toArray();
            } else {
                $this->data = (array) $this->data;
            }
        }
        return array_keys(get_object_vars($this));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Data should already be an array due to constructor conversion
            $emailData = $this->data;
            
            Log::info('SendSubscriberEmail: About to send emails', [
                'email_count' => count($this->emails),
                'data_type' => gettype($emailData),
                'data_keys' => is_array($emailData) ? array_keys($emailData) : 'not array',
                'title' => $emailData['title'] ?? 'no title'
            ]);
            
            if (count($this->emails) <= 50) {
                // Send emails directly for small batches
                foreach ($this->emails as $email) {
                    Mail::to($email)->send(new CourseNotificationMail($emailData));
                }
            } else {
                // Create batches for larger email lists - pass already converted array data
                $chunks = collect($this->emails)->chunk(50);
                $jobs = [];
                
                foreach ($chunks as $chunk) {
                    $jobs[] = new SendSubscriberEmail($emailData, $chunk->toArray());
                }
                
                Bus::batch($jobs)->dispatch();
            }
        } catch (\Exception $e) {
            Log::error('SendSubscriberEmail failed: ' . $e->getMessage() . ' Data type: ' . gettype($this->data));
            throw $e;
        }
    }
}
