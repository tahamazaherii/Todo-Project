<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Mail\TodoDueEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckDueTodos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:check-due-todos';
    protected $signature = 'todos:check-due';
    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Check for todos with passed due date and send emails';

    /**
     * Execute the console command.
     */

    public function handle()
    {

    //این برای ارسال ایمیل وقتی که تایم که قرار داده بوده انجام بشه فرار رسیده
        try {
            $this->info('Checking due todos...');

            $todos = Todo::where('due_date', '<', now())
                ->where('status', false)
                ->where('email_sent', false)
                ->get();

            if ($todos->isEmpty()) {
                $this->info('No due todos found.');
                return;
            }

            foreach ($todos as $todo) {
                $userEmail = $todo->user->email;
                $this->info('Sending email to: ' . $userEmail);
                Mail::to($userEmail)->send(new TodoDueEmail($todo));
                // Uncomment this line to update the email_sent flag after sending
                $todo->update(['email_sent' => true]);
            }

            $this->info('Due todos check completed.');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }


}
