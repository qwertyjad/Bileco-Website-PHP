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
    public function getPaginatedNews($limit, $offset) {
        $query = "SELECT * FROM news ORDER BY date DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNewsCount() {
        $query = "SELECT COUNT(*) FROM news";
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    

    public function searchNews($query) {
        $sql = "SELECT id, title, content, image, date FROM news 
                WHERE title LIKE :query OR content LIKE :query 
                ORDER BY date DESC";
        
        $stmt = $this->db->conn->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
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

    public function getCommentCount($news_id) {
        $stmt = $this->db->conn->prepare("SELECT COUNT(*) as comment_count FROM comments WHERE news_id = :news_id");
        $stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['comment_count'] ?? 0;
    }

    public function getPreviousPost($currentId) {
        $stmt = $this->db->conn->prepare("SELECT id, title FROM news WHERE id < ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$currentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNextPost($currentId) {
        $stmt = $this->db->conn->prepare("SELECT id, title FROM news WHERE id > ? ORDER BY id ASC LIMIT 1");
        $stmt->execute([$currentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRelatedPosts($excludeId) {
        $stmt = $this->db->conn->prepare("SELECT id, title, image FROM news WHERE id != ? ORDER BY RAND() LIMIT 3");
        $stmt->execute([$excludeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getArchives() {
        $query = "SELECT DISTINCT DATE_FORMAT(date, '%M %Y') AS archive_date, 
                         DATE_FORMAT(date, '%Y-%m') AS archive_link 
                  FROM news 
                  ORDER BY date DESC";
        
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNewsByMonth($date_filter) {
        $query = "SELECT id, title, image, date FROM news WHERE DATE_FORMAT(date, '%Y-%m') = :date_filter ORDER BY date DESC";
        
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':date_filter', $date_filter);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Static methods for user-related functions
    public static function startSessionIfNotStarted() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getUserDetails($conn, $user_id) {
        $query = "SELECT status, role FROM tbl_users WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function redirectBasedOnRole($user_role) {
        if ($user_role === 's_admin') {
            header("Location: super-admin/index.php");
            exit();
        } elseif ($user_role === 'admin') {
            header("Location: admin/index.php");
            exit();
        }
    }

    public static function includeNavbarBasedOnStatus($user_status) {
        if ($user_status === 'online') {
            include 'components/navbar-u.php';
        } else {
            include 'components/navbar.php';
        }
    }
     // ✅ Add this function to fetch the latest news
     public function getLatestNews($limit = 5) {
        $sql = "SELECT id, title, content, image, date FROM news ORDER BY date DESC LIMIT :limit";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSuperAdminFirstName() {
        $query = "SELECT firstname FROM tbl_users WHERE role = 's_admin' LIMIT 1";
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
}
?>
