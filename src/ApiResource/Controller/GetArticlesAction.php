<?php

namespace App\ApiResource\Controller;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Article;
use App\Pattern\ArticleAggregator;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Request;

class GetArticlesAction extends AbstractController
{
    public function __invoke(ArticleAggregator $aggregator, Request $request, ManagerRegistry $doctrine, ArticleRepository $articleRepository): CacheItem
    {
        $client = MemcachedAdapter::createConnection(
            'memcached://localhost:11222?retry_timeout=10'
        );

        $cache = new MemcachedAdapter(
            $client,
            $namespace = '',
            $defaultLifetime = 3600
        );

        $cachedArticles = $cache->getItem('flora_test.articles');


        $aggregator->addDatabase('default');
        $aggregator->addDatabase('database_1');
        $aggregator->addDatabase('database_2');
        $aggregator->addDatabase('database_3');
        $aggregator->addDatabase('database_4');
        $dbs = $aggregator->getDatabaseSources();

        $data = [];
        foreach ($dbs as $db) {
            $entityManager = $doctrine->getManager($db);
            $page = (int) $request->query->get('page', 1);
            $data[] = $entityManager->getRepository(Article::class)->getArticles($page);
        }

        if (!$cachedArticles->isHit()) {
            $cachedArticles->set($data)->expiresAfter(10);
            $cache->save($cachedArticles);
        }
        else {
            $cache->get();
        }


        return $cachedArticles;
    }
}