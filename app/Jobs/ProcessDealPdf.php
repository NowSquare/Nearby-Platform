<?php

namespace App\Jobs;

use App\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessDealPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $deal = $this->deal;
      $path = storage_path('app/deals/pdf/');
      $fileName = str_slug($deal->title) . '.pdf';

      \File::makeDirectory($path, 0775, true, true);

      // Update PDF in cache
      $deal_hash = \App\Http\Controllers\Core\Secure::staticHash($deal->id);

      // $print indicates whether the view has to be rendered for PDF generation
      $print = true;

      $isMobile = true;
      $ga_code = '';

      // Description that fits one line for html tags
      $description = preg_replace('/\s+/', ' ', preg_replace('/\r|\n/', ' ', $deal->details));

      // Set locale
      app()->setLocale($deal->language);

      $pdf = \Barryvdh\DomPDF\Facade::loadView('app.deals.view-deal', compact('deal_hash', 'deal', 'description', 'isMobile', 'print', 'ga_code'))
        ->setPaper([0, 0, 396.00, 792.00])
        ->setWarnings(false);

      $pdf->save($path . $deal->id . '.pdf');
    }
}
