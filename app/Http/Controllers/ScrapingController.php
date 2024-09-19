<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product; 
class ScrapingController extends Controller
{
    public function scrape()
    {
        $client = new Client();
        $url = 'https://www.mercadolivre.com.br/ofertas';
        // $url = 'https://www.mercadolivre.com.br/ofertas?category=MLB1500#filter_applied=category&filter_position=3&origin=qcat';
        // $url = 'https://www.mercadolivre.com.br/ofertas?container_id=MLB916440-2#filter_applied=container_id&filter_position=3&is_recommended_domain=false&origin=scut';


        try {
            $response = $client->get($url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
                ]
            ]);
            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);

            $crawler->filterXPath('//div[contains(@class, "andes-card poly-card")]')->each(function (Crawler $node) {
                try {
                    $imageUrl = $node->filterXPath('.//div[@class="poly-card__portada"]//img')->attr('data-src') ?: $node->filterXPath('.//div[@class="poly-card__portada"]//img')->attr('src');
                    
                    $title = $node->filterXPath('.//a[@class="poly-component__title"]')->text();
                    
                    $price = $node->filterXPath('.//span[@class="andes-money-amount__fraction"]')->text();

                    Product::create([
                        'name' => $title,
                        'price' => floatval(str_replace('.', '', $price)), 
                        'image_url' => $imageUrl
                    ]);

                } catch (\Exception $e) {
                }
            });

            return response()->json(['success' => 'Produtos extraídos e salvos com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao acessar a URL: ' . $e->getMessage()], 500);
        }
    }

    public function deleteAllProducts()
    {
        try {
            Product::truncate();
            
            return response()->json(['success' => 'Todos os produtos foram excluídos com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir produtos: ' . $e->getMessage()], 500);
        }
    }
}
