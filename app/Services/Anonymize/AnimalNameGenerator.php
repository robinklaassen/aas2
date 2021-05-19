<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

class AnimalNameGenerator implements NameGenerator
{
    const NAMES = [
        "Paard",
        "Lieveheersbeestje",
        "Kikker",
        "Kikkervisje",
        "Adelaar",
        "Vogel",
        "Vis",
        "Dolfijn",
        "Bever",
        "Duif",
        "Mus",
        "Zebra",
        "Giraffe",
        "Ringstaartmaki",
        "Aap",
        "Dino",
        "Schaap",
        "Koe",
        "Lam",
        "Neushoorn",
        "Kwal",
        "Egel",
        "Kreeft",
        "Portugees oorlogsschip",
        "Kat",
        "Hond",
        "Papegaai",
        "Parkiet",
        "Pinguïn",
        "Zeehond",
        "Leeuw",
        "Zeeleeuw",
        "Vlinder",
        "Rups",
        "N-vis",
        "X-kikker",
        "U-geit",
        "Narwal"
    ];

    public function name(): string
    {
        return self::NAMES[random_int(0, count(self::NAMES))];
    }
}
