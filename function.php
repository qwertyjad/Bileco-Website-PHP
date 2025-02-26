<?php
include 'conn.php';

class Functions {
    private $db;

    public function __construct() {
        $this->db = new conn(); // Create a database connection instance
    }

    public function getAllNews($limit = null, $page = 1, $offset = 0) {
        $sql = "SELECT * FROM news ORDER BY date DESC";
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->conn->prepare($sql);

        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewsById($id) {
        $stmt = $this->db->conn->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addNews($title, $content, $date, $image) {
        $stmt = $this->db->conn->prepare("INSERT INTO news (title, content, date, image) VALUES (:title, :content, :date, :image)");
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB); // Store image as a BLOB
        return $stmt->execute();
    }

    public function updateNews($id, $title, $content, $date, $image = null) {
        if ($image) {
            $stmt = $this->db->conn->prepare("UPDATE news SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
            $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
        } else {
            $stmt = $this->db->conn->prepare("UPDATE news SET title = :title, content = :content, date = :date WHERE id = :id");
        }

        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteNews($id) {
        $stmt = $this->db->conn->prepare("DELETE FROM news WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

     // Function to get the comment count for a specific news_id
     public function getCommentCount($news_id) {
        $stmt = $this->db->conn->prepare("SELECT COUNT(*) as comment_count FROM comments WHERE news_id = :news_id");
        $stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['comment_count'] ?? 0;
    }

     // Get Previous Post
     public function getPreviousPost($currentId) {
        $stmt = $this->db->conn->prepare("SELECT id, title FROM news WHERE id < ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$currentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get Next Post
    public function getNextPost($currentId) {
        $stmt = $this->db->conn->prepare("SELECT id, title FROM news WHERE id > ? ORDER BY id ASC LIMIT 1");
        $stmt->execute([$currentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get Related Posts by Category
    public function getRelatedPosts($excludeId) {
        $stmt = $this->db->conn->prepare("SELECT id, title, image FROM news WHERE id != ? ORDER BY RAND() LIMIT 3");
        $stmt->execute([$excludeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
