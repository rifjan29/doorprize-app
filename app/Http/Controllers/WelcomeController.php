<?php

namespace App\Http\Controllers;

use App\Models\Dooprize;
use App\Models\Penerima;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        // sleep(5);

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
            $findDooprize = Dooprize::where('name',$prize)->first();
            $penerima = new Penerima;
            $penerima->id_penerima = $recipient->id;
            $penerima->id_hadiah = $findDooprize->id;
            $penerima->nama_penerima = $recipient->name;
            $penerima->nama_hadiah = $prize;
            $penerima->save();
            $deletDooprize = Dooprize::where('name',$prize)->first()->delete();
            $deletePenerima = Recipient::find($recipient->id)->delete();
            Session::put('prize', $prize);
            Session::put('recipient', $recipient->name);
            $dooprizes[] = [
                'recipient' => Session::get('recipient'),
                'prize' => Session::get('prize'),
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

    public function export() {
        $penerima = Penerima::latest()->get();
        return view('export',compact('penerima'));
    }

}
