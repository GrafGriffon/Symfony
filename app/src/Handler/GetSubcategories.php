<?php

namespace App\Handler;

use App\Repository\CategoryRepository;

class GetSubcategories
{
    static function printIndex(int $idParent, array $categories, CategoryRepository $repository): array
    {
        foreach ($repository->findAll() as $item) {
            if ($item->getParent()->getId() == $idParent) {
                $categories[] = $item->getID();
                $categories = self::printIndex($item->getId(), $categories, $repository);
            }
        }
        return $categories;
    }
}