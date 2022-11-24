<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Config\Framework\HttpClient;
use Symfony\Component\PropertyAccess\PropertyAccess;






class ArticleController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }



    #[Route('/news', name: 'news')]
    public function fetchGitHubInformation()
    {
        $response = $this->client->request(
            'GET',
            'https://newsapi.org/v2/top-headlines?country=ng&apiKey=7f10504a8fe64d3fad0d69174b6a1c7a'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $contents = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        $articles = $contents['articles'];
        //dd($articles);


        //dump($contents['articles']);
        return $this->render('news/index.html.twig', array(
            'contents' => $contents,
            'articles' => $articles,
        ));
    }

    #[Route('/articles', name: 'article_list')]
    public function index(ManagerRegistry $doctrine)
    {

        //get all from database
        $articles = $doctrine->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', array(
            'articles' => $articles
        ));
    }

    #[Route('/articles/{id}', name: 'article_show')]
    public function getArticleById(ManagerRegistry $doctrine, $id)
    {
        //get all from database
        $article = $doctrine->getRepository(Article::class)->find($id);


        return $this->render('article/show.html.twig', array(
            'article' => $article
        ));
    }


    #[Route('/articles/save')]
    
    public function articleSave(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        //create new article
        $article = new Article();
        $article->setTitle('Article One');
        $article->setBody('This is the body for article one');

        //persist tells us that we actually want to save it
        $entityManager->persist($article);

        //to execute it, we need to call flush
        $entityManager->flush();

        return new Response('Saves an article with the id of '. $article->getId());
    }
}