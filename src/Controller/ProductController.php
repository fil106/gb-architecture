<?php

declare(strict_types = 1);

namespace Controller;

use Framework\Render;
use Service\Order\Basket;
use Service\Product\Product;
use Service\Vkontakte\Vk;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sorting\Sort;
use Sorting\SortByName;
use Sorting\SortByPrice;

class ProductController
{
    use Render;

    /**
     * Информация о продукте
     *
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function infoAction(Request $request, $id): Response
    {
        $vk = new Vk();
        $basket = (new Basket($request->getSession()));

        if ($request->isMethod(Request::METHOD_POST)) {
            $basket->addProduct((int)$request->request->get('product'));
        }

        $productInfo = (new Product())->getInfo((int)$id);

        if ($productInfo === null) {
            return $this->render('error404.html.php');
        }

        $isInBasket = $basket->isProductInBasket($productInfo->getId());

        return $this->render('product/info.html.php', [
            'productInfo' => $productInfo,
            'isInBasket' => $isInBasket,
            'vkHeadScript' => $vk->setHeadScript(),
            'vkPublishButton' => $vk->getPublishButton()
        ]);
    }

    /**
     * Список всех продуктов
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        $productList = (new Product())->getAll();

        // Применить паттерн Стратегия
        $sortBy = $request->query->get('sort');

        $sort = new Sort();

        switch ($sortBy) {
            case 'price':
                $productList = $sort->sorting(new SortByPrice(), $productList);
                break;
            case 'name':
                $productList = $sort->sorting(new SortByName(), $productList);
                break;
        }

        return $this->render('product/list.html.php',
            [
                'productList' => $productList,
            ]
        );
    }
}
