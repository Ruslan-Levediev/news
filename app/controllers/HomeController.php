<?php
namespace App\Controllers;

use App\Factories\ModelFactory;
use App\Core\View;

class HomeController {
    private $newsRepo;
    private string $baseUrl;

    public function __construct() {

        $this->newsRepo = ModelFactory::create('newsrepository');


        $this->baseUrl = '/project/public';
    }

    public function index() {
        $title = "Головна - SMGNews";

        $mainNewsList = $this->newsRepo->getMainNews();

        $latestNews = $this->newsRepo->getLatestNews(3);

        $view = new View('home/index');
        $view->render([
            'title' => $title,
            'mainNewsList' => $mainNewsList, 
            'latestNews' => $latestNews,
            'baseUrl' => $this->baseUrl,
        ]);
    }



}
