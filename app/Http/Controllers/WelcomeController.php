<?php

namespace App\Http\Controllers;

use App\Models\Dooprize;
use App\Models\Recipient;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index() {
        return view('welcome');
    }
     /**
     * Generate random dooprizes for recipients from the database.
     *
     * @param int $numberOfPrizes
     * @return array
     */
    public function generateDooprizes(Request $request)
    {
        $numberOfPrizes = $request->jumlah;

        // Simulasi penundaan 5 detik untuk memperoleh pemenang
        sleep(5);

        $dooprizes = [];
        $count = Recipient::count();
        // if ($count < $numberOfPrizes) {
        //     return 'Tidak ada pemenang';
        // }
        // Get recipients from the database
        $recipients = Recipient::inRandomOrder()->limit($numberOfPrizes)->get();

        // Generate random prizes for each recipient
        foreach ($recipients as $recipient) {
            $prize = $this->generateRandomPrize();
            $dooprizes[] = [
                'recipient' => $recipient->name,
                'prize' => $prize
            ];
        }

        return $dooprizes;
    }

    /**
     * Generate a random prize.
     *
     * @return string
     */
    private function generateRandomPrize()
    {
        // Example of possible prizes, modify as needed
        $prizes = Dooprize::latest()->pluck('name')->toArray();

        return $prizes[array_rand($prizes)];
    }

}
