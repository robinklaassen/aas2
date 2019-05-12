<?php

use Illuminate\Database\Seeder;
use App\Review;

class ReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reviews')->delete();

        Review::create([
            'event_id' => 1,
            'bs-uren' => 6,
            'bs-mening' => 3,
            'bs-tevreden' => 3,
            'bs-manier' => 0,
            'bs-manier-mening' => 'Haha',
            'bs-thema' => 1,
            'bs-thema-wat' => 'Het levensverhaal van Ranonkeltje',
            'kh-slaap' => 3,
            'kh-slaap-wrm' => 'Je moeder',
            'kh-bijspijker' => 2,
            'kh-bijspijker-wrm' => 'Beetje koud',
            'kh-geheel' => 4,
            'kh-geheel-wrm' => 'Vooral de aankleding was tof',
            'leidingploeg' => 'Beste ploeg allertijden!',
            'slaaptijd' => 1,
            'slaaptijd-hoe' => 'Ik kon niet slapen',
            'kamplengte' => 3,
            'kamplengte-wrm' => 'Niet te veel en niet te weinig',
            'eten' => 'Zuurkool jeeej',
            'avond-leukst' => 'Bonte avond!',
            'avond-minst' => 'Smokkelspel, heb mijn enkel verzwikt',
            'allerleukst' => 'Een knuffel krijgen van Saskia',
            'allervervelendst' => 'Afwassen',
            'cijfer' => 8,
            'nogeens' => 'Zeker, altijd',
            'kampkeuze' => 'mei, zomer',
            'tip' => 'Meer chocola',
            'verder' => 'Ik besta niet echt'
        ]);
    }
}
