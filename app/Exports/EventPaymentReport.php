<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventPaymentReport implements FromArray, WithHeadings
{
    use Exportable;

    protected $event;

    public function __construct(\App\Models\Event $event)
    {
        $this->event = $event;
    }

    public function array(): array
    {
        // Fill data array
        $data = [];
        foreach ($this->event->participants()->orderBy('voornaam')->get() as $p) {

            // Payment amount
            switch ($p->inkomen) {
                case 0:
                    $toPay = $this->event->prijs;
                    break;

                case 1:
                    $toPay = round((0.85 * $this->event->prijs) / 5) * 5;
                    break;

                case 2:
                    $toPay = round((0.7 * $this->event->prijs) / 5) * 5;
                    break;

                case 3:
                    $toPay = round((0.5 * $this->event->prijs) / 5) * 5;
                    break;
            }

            // Date of payment is not Carbon :(
            $x = $p->pivot->datum_betaling;
            $betaling = substr((string) $x, 8, 2) . '-' . substr((string) $x, 5, 2) . '-' . substr($x, 0, 4);

            // Declaration of income only when asked for
            //dd($p->inkomensverklaring->year);
            $i = ($p->inkomensverklaring !== null) ? $p->inkomensverklaring->format('d-m-Y') : null;
            if ($p->inkomen === 0) {
                $i = 'x';
            }

            $data[] = [
                str_replace('  ', ' ', $p->voornaam . ' ' . $p->tussenvoegsel . ' ' . $p->achternaam),
                $toPay,
                $p->pivot->created_at->format('d-m-Y'),
                $betaling,
                $i,
                null,
                null,
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Naam',
            'Bedrag',
            'Betaling',
            'Inkomensverklaring',
            'Correct',
            'Opmerking',
        ];
    }
}
