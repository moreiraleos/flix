<?php

class CategoryContainers
{
    private $con, $username;

    public function __construct($con, $username)
    {
        $this->con = $con;
        $this->username = $username;
    }

    public function showAllCategories()
    {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategory'>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null);
        }

        return $html . "</div>";
    }

    private function getCategoryHtml($sqlData, $title, $tvShows = true, $movies = true, $entityId = null)
    {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if ($tvShows && $movies) {
            $entities = EntityProvider::getEntities($this->con, $categoryId, 30, $entityId);
        } else if ($tvShows) {
            $entities = EntityProvider::getTvShowsEntities($this->con, $categoryId, 30);
        } else {
            $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 30);
        }

        if (sizeof($entities) == 0) {
            return;
        }

        $entitiesHTML = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);
        foreach ($entities as $entity) {
            $entitiesHTML .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <a href='category.php?id={$categoryId}'>
                        <h3>{$title}</h3>
                    </a>
                    <div class='entities'>
                        {$entitiesHTML}
                    </div>
                </div>";
        // return $entitiesHTML . "<br />";
    }

    public function showCategory($categoryId, $title = null, $entityId = null)
    {
        $query = $this->con->prepare("SELECT * FROM categories WHERE id = :id");
        $query->bindValue(":id", $categoryId);
        $query->execute();

        $html = "<div class='previewCategory noScroll'>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, $title, true, true,  $entityId);
        }

        return $html . "</div>";
    }

    public function showTVShowCategories()
    {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategory'>
                    <h1>TV Shows</h1>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, true, false);
        }

        return $html . "</div>";
    }

    public function showMovieCategories()
    {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategory'>
                    <h1>Movies</h1>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, false, true);
        }

        return $html . "</div>";
    }
}
