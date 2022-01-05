<?php

namespace App\Controller;

use App\Repository\DayTempRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class ApiWeatherController extends AbstractController
{
    /**
     * @Route("/api/weather", name="api_weather")
     */
    public function index(
        DayTempRepository $dayTempRepository,
        Request $request,
        string $cityFromEnv,
        string $xToken
    ): Response {

        if (!$request->headers->has('X-AUTH-TOKEN') || $request->headers->get('X-AUTH-TOKEN') != $xToken) {
            return $this->json(['error' => true, 'message' => 'Wrong X-AUTH-TOKEN']);
        }

        $day = $request->query->get('day');

        try {
            $date = new DateTime($day);
        } catch (\Exception $exception) {
            return $this->json(['error' => true, 'message' => 'Invalid day format.']);
        }

        if (!$date || is_null($day)) {
            return $this->json(['error' => true, 'message' => 'Wrong day format.']);
        }

        $data = $dayTempRepository->getDayTempByCity($cityFromEnv, $date);

        if (empty($data)) {
            return $this->json(['message' => 'Empty data. Try later']);
        }

        return $this->json($data, 200, [], ['groups' => ['api', 'timestampable']]);
    }
}
