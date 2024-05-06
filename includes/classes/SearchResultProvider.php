<?php

class SearchResultProvider
{
    private $con, $username;

    public function __construct($con, $username)
    {
        $this->con = $con;
        $this->username = $username;
    }

    public function getResults($inputText)
    {
        $entities =  EntityProvider::getSearchEntities($this->con, $inputText);

        $html = "<div class='previewCategories noScroll'>";

        $html .= $this->getResultHtml($entities);

        return $html .= "</div>";
    }

    public function getResultHtml($entities)
    {
        if (sizeof($entities) == 0) {
            return;
        }

        $entitiesHTML = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);
        foreach ($entities as $entity) {
            $entitiesHTML .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <div class='entities'>
                        {$entitiesHTML}
                    </div>
                </div>";
    }
}
