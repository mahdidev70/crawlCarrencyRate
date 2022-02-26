<?php

namespace App\Console\Commands;

use DOMDocument;
use Carbon\Carbon;
use App\Models\Price;
use Illuminate\Console\Command;

class crawlCurrentPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get current price of currencies';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startTime = Carbon::parse('09:00:00')->format('H:i:s');
        $endTime = Carbon::parse('17:00:00')->format('H:i:s');
        $now = Carbon::now('Asia/Tehran')->format('H:i:s');
        if (!($now >= $startTime && $now <= $endTime)) {
            // not crawl time
            return false;
        }

        $endpoint = "https://www.tgju.org/";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);
        $content = $response->getBody();

        $dom = new DOMDocument();
        @$dom->loadHTML($content);
        $elements = $dom->getElementsByTagName('td'); // DOMNodeList object

        $usd = null;
        $eur = null;

        foreach ($elements as $element) {
            $classes = $element->getAttribute('data-market-p');
            if ($usd == null && strpos($classes, 'sana_buy_usd') !== false) {
                $usd = (int) str_replace(',', '', $element->textContent);
            }
            if ($eur == null && strpos($classes, 'sana_buy_eur') !== false) {
                $eur = (int) str_replace(',', '', $element->textContent);
            }
            if ($usd != null && $eur != null) {
                $percent = $usd * 100 / $eur;
                $price = Price::create(['usd' => $usd, 'eur' => $eur, 'percent' => $percent]);
                break;
            }
        }
    }
}
