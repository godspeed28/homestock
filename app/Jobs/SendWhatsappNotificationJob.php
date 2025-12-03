<?php

namespace App\Jobs;

use App\Helpers\WhatsappHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsappNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public $number,
        public $message
    ) {}

    public function handle()
    {
        // Log sebelum mengirim
        Log::info('Memulai pengiriman WhatsApp', [
            'number' => $this->number,
            'message_length' => strlen($this->message),
            'job_id' => $this->job->getJobId() ?? 'N/A'
        ]);

        try {
            // Kirim WhatsApp
            $result = WhatsappHelper::send($this->number, $this->message);

            // Log keberhasilan
            Log::info('WhatsApp berhasil dikirim', [
                'number' => $this->number,
                'result' => $result
            ]);

            return $result;
        } catch (\Exception $e) {
            // Log error
            Log::error('Gagal mengirim WhatsApp', [
                'number' => $this->number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e; // Re-throw agar job bisa retry
        }
    }
}
