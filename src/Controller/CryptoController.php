<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

class CryptoController extends AbstractController
{
    /**
     * @Route("/", name="crypto")
     */
    public function index(): Response
    {
        // Vytvoříme HTTP klienta
        $client = new Client();

        // Získáme aktuální kurz kryptoměny Bitcoin v EUR
        $response_eur = $client->get('https://api.coindesk.com/v1/bpi/currentprice/EUR.json');
        $data_eur = json_decode($response_eur->getBody()->getContents());

        // Získáme aktuální kurz kryptoměny Bitcoin v USD
        $response_usd = $client->get('https://api.coindesk.com/v1/bpi/currentprice/USD.json');
        $data_usd = json_decode($response_usd->getBody()->getContents());

        // Vytvoříme pole s daty
        $data = [
            'price_eur' => number_format($data_eur->bpi->EUR->rate_float, 2, ',', ' '),
            'price_usd' => number_format($data_usd->bpi->USD->rate_float, 2, '.', ','),
        ];

        // Vykreslíme šablonu a předáme jí data
        return $this->render('crypto/index.html.twig', [
            'data' => $data,
        ]);
    }
}
