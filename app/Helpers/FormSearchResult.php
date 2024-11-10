<?php

namespace App\Helpers;

use Spatie\Searchable\SearchResultCollection;

class FormSearchResult
{
    public static function form(SearchResultCollection $result): array
    {
        $formRes = [];

        foreach ($result as $searchItem)
        {
            $formRes[] = $searchItem->searchable;
        }

        return $formRes;
    }
}
