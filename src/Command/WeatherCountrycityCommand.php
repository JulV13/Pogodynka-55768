<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:countrycity',
    description: 'Komenda pobierajaca pogode po kodzie kraju i nazwie miasta',
)]
class WeatherCountrycityCommand{

    public function __construct(
        private WeatherUtil $weatherUtil
    ){}
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country Code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        $io->writeln(sprintf('Country and city: %s %s', $country, $city));

        $io->table(['Date', 'Temperature'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemperatureCelsius(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
