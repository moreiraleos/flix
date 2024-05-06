<?php

class EntityProvider
{
    public  static function getEntities($con, $categoryId, $limit, $entityId = null)
    {
        $sql = "SELECT * FROM entities ";
        if ($categoryId != null) {
            $sql .= "WHERE 	categoryId = :categoryId ";
        }

        if ($entityId != null) {
            $sql .= " AND id != :id ";
        }

        $sql .= "ORDER BY RAND() LIMIT :limit";
        $query = $con->prepare($sql);
        if ($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }
        if ($entityId != null) {
            $query->bindValue(":id", $entityId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();
        $result = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }
        return $result;
    }

    public  static function getTvShowsEntities($con, $categoryId = null, $limit = null, $entityId = null)
    {
        $sql = "SELECT DISTINCT(entities.id)
                FROM entities
                INNER JOIN videos
                ON entities.id = videos.entityId
                WHERE videos.isMovie = 0 ";

        if ($categoryId != null) {
            $sql .= "AND categoryId = :categoryId ";
        }

        if ($entityId != null) {
            $sql .= " AND id != :id ";
        }

        $sql .= "ORDER BY RAND() LIMIT :limit";
        $query = $con->prepare($sql);
        if ($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }
        if ($entityId != null) {
            $query->bindValue(":id", $entityId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();
        $result = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row["id"]);
        }
        return $result;
    }

    public  static function getMoviesEntities($con, $categoryId = null, $limit = null, $entityId = null)
    {
        $sql = "SELECT DISTINCT(entities.id)
                FROM entities
                INNER JOIN videos
                ON entities.id = videos.entityId
                WHERE videos.isMovie = 1 ";

        if ($categoryId != null) {
            $sql .= "AND categoryId = :categoryId ";
        }

        if ($entityId != null) {
            $sql .= " AND id != :id ";
        }

        $sql .= "ORDER BY RAND() LIMIT :limit";
        $query = $con->prepare($sql);
        if ($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }
        if ($entityId != null) {
            $query->bindValue(":id", $entityId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();
        $result = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row["id"]);
        }
        return $result;
    }

    public  static function getSearchEntities($con, $term)
    {
        $sql = "SELECT * FROM entities WHERE name LIKE CONCAT('%', :term, '%') LIMIT 30";
        // $sql = "SELECT * FROM entities WHERE MATCH(name) AGAINST (':term') LIMIT 30";
        $query = $con->prepare($sql);
        $query->bindValue(":term", $term);
        $query->execute();
        $result = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }
        return $result;
    }
}
