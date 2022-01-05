<?php

namespace App\Command;

use App\Repository\DayTempRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetTempCommand extends Command
{
    protected static $defaultName = 'app:GetTemp';
    protected static $defaultDescription = 'Get current temperature from Api service for city from env config';
    /**
     * @var DayTempRepository
     */
    private $dayTempRepository;

    /**
     * @var string
     */
    private $cityFromEnv;
    /**
     * @var string
     */
    private $openWeatherApiKey;
    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(
        DayTempRepository $dayTempRepository,
        HttpClientInterface $client,
        string $cityFromEnv,
        string $openWeatherApiKey
    ) {
        parent::__construct();
        $this->dayTempRepository = $dayTempRepository;
        $this->cityFromEnv = $cityFromEnv;
        $this->openWeatherApiKey = $openWeatherApiKey;
        $this->client = $client;
    }


    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $request_link = "https://api.openweathermap.org/data/2.5/weather?q={$this->cityFromEnv}&appid={$this->openWeatherApiKey}";

        $response = $this->client->request(
            'GET',
            $request_link
        );

        $current_weather = $response->toArray();

        if (isset($current_weather['main']['temp'])) {
            $this->dayTempRepository->addCityTemp($this->cityFromEnv, $current_weather['main']['temp']);
            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
}
