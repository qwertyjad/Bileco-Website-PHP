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
   // In Functions.php
// Add these methods to your Functions class

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
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
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

    public static function getUserNum($conn, $user_id) {
        $query = $conn->prepare("SELECT id, accountnum, firstname, middlename, lastname, role, status FROM tbl_users WHERE id = :user_id LIMIT 1");
        $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC); // Ensure accountnum is included
    }
    
    // Fetch user details from tbl_users or tbl_accreditation
    public static function getUserDetails($conn, $user_id, $user_type = null) {
        if ($user_type === 'tbl_users') {
            $query = "SELECT status AS online, role FROM tbl_users WHERE id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($user_type === 'tbl_accreditation') {
            $query = "SELECT status, 'accredited' AS role FROM tbl_accreditation WHERE id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // If user_type is not set, check both tables
            // First, check tbl_users
            $query = "SELECT status AS online, role FROM tbl_users WHERE id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['user_type'] = 'tbl_users'; // Set user_type in session
                return $user;
            }

            // If not found in tbl_users, check tbl_accreditation
            $query = "SELECT online_status, 'accredited' AS role FROM tbl_accreditation WHERE id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $accred_user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($accred_user) {
                $_SESSION['user_type'] = 'tbl_accreditation'; // Set user_type in session
                return $accred_user;
            }

            return false; // User not found in either table
        }
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

    // Include the appropriate navbar based on user status, role, and type
    public static function includeNavbarBasedOnStatus($user_status, $user_role = null, $user_type = null) {
        if ($user_status === 'online') {
            if ($user_type === 'tbl_accreditation' && $user_role === 'accredited') {
                include 'components/navbar-accre.php'; // Navbar for accredited users
            } else {
                include 'components/navbar-u.php'; // Navbar for tbl_users (admins, super admins, etc.)
            }
        } else {
            include 'components/navbar.php'; // Navbar for guests
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
    
    // New Announcement Methods
    public function getAllAnnouncement($limit = null, $page = 1, $offset = 0) {
        $sql = "SELECT * FROM announcements ORDER BY date DESC";
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

    public function getPaginatedAnnouncement($limit, $offset) {
        $query = "SELECT * FROM announcements ORDER BY date DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnnouncementCount() {
        $query = "SELECT COUNT(*) FROM announcements";
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function searchAnnouncement($query) {
        $sql = "SELECT id, title, content, image, date FROM announcements 
                WHERE title LIKE :query OR content LIKE :query 
                ORDER BY date DESC";
        
        $stmt = $this->db->conn->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnnouncementById($id) {
        $stmt = $this->db->conn->prepare("SELECT * FROM announcements WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAnnouncement($title, $content, $date, $image) {
        $stmt = $this->db->conn->prepare("INSERT INTO announcements (title, content, date, image) VALUES (:title, :content, :date, :image)");
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
        return $stmt->execute();
    }

    public function updateAnnouncement($id, $title, $content, $date, $image = null) {
        if ($image) {
            $stmt = $this->db->conn->prepare("UPDATE announcements SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
            $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
        } else {
            $stmt = $this->db->conn->prepare("UPDATE announcements SET title = :title, content = :content, date = :date WHERE id = :id");
        }

        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteAnnouncement($id) {
        $stmt = $this->db->conn->prepare("DELETE FROM announcements WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getLatestAnnouncement($limit = 5) {
        $sql = "SELECT id, title, content, image, date FROM announcements ORDER BY date DESC LIMIT :limit";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnnouncementArchives() {
        $query = "SELECT DISTINCT DATE_FORMAT(date, '%M %Y') AS archive_date, 
                         DATE_FORMAT(date, '%Y-%m') AS archive_link 
                  FROM announcements 
                  ORDER BY date DESC";
        
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnnouncementsByMonth($date_filter) {
        $query = "SELECT id, title, image, date FROM announcements WHERE DATE_FORMAT(date, '%Y-%m') = :date_filter ORDER BY date DESC";
        
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':date_filter', $date_filter);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add these methods to your Functions class

public function getPaginatedBidsAwards($limit, $offset) {
    $query = "SELECT * FROM bids_awards ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getBidsAwardsCount() {
    $query = "SELECT COUNT(*) FROM bids_awards";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getBidsAwardsById($id) {
    $stmt = $this->db->conn->prepare("SELECT * FROM bids_awards WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function addBidsAwards($title, $content, $date, $image) {
    $stmt = $this->db->conn->prepare("INSERT INTO bids_awards (title, content, date, image) VALUES (:title, :content, :date, :image)");
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    return $stmt->execute();
}

public function updateBidsAwards($id, $title, $content, $date, $image = null) {
    if ($image) {
        $stmt = $this->db->conn->prepare("UPDATE bids_awards SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    } else {
        $stmt = $this->db->conn->prepare("UPDATE bids_awards SET title = :title, content = :content, date = :date WHERE id = :id");
    }

    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public function deleteBidsAwards($id) {
    $stmt = $this->db->conn->prepare("DELETE FROM bids_awards WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}

// Add these methods to your Functions class

public function getPaginatedCSRPrograms($limit, $offset) {
    $query = "SELECT * FROM csr_programs ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getCSRProgramsCount() {
    $query = "SELECT COUNT(*) FROM csr_programs";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getCSRProgramsById($id) {
    $stmt = $this->db->conn->prepare("SELECT * FROM csr_programs WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function addCSRPrograms($title, $content, $date, $image) {
    $stmt = $this->db->conn->prepare("INSERT INTO csr_programs (title, content, date, image) VALUES (:title, :content, :date, :image)");
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    return $stmt->execute();
}

public function updateCSRPrograms($id, $title, $content, $date, $image = null) {
    if ($image) {
        $stmt = $this->db->conn->prepare("UPDATE csr_programs SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    } else {
        $stmt = $this->db->conn->prepare("UPDATE csr_programs SET title = :title, content = :content, date = :date WHERE id = :id");
    }

    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public function deleteCSRPrograms($id) {
    $stmt = $this->db->conn->prepare("DELETE FROM csr_programs WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}


// Add these methods to your Functions class

public function getPaginatedGenerationMix($limit, $offset) {
    $query = "SELECT * FROM generation_mix ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getGenerationMixCount() {
    $query = "SELECT COUNT(*) FROM generation_mix";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getGenerationMixById($id) {
    $stmt = $this->db->conn->prepare("SELECT * FROM generation_mix WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function addGenerationMix($title, $content, $date, $image) {
    $stmt = $this->db->conn->prepare("INSERT INTO generation_mix (title, content, date, image) VALUES (:title, :content, :date, :image)");
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    return $stmt->execute();
}

public function updateGenerationMix($id, $title, $content, $date, $image = null) {
    if ($image) {
        $stmt = $this->db->conn->prepare("UPDATE generation_mix SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    } else {
        $stmt = $this->db->conn->prepare("UPDATE generation_mix SET title = :title, content = :content, date = :date WHERE id = :id");
    }

    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public function deleteGenerationMix($id) {
    $stmt = $this->db->conn->prepare("DELETE FROM generation_mix WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}

// Add these methods to your Functions class

public function getPaginatedMaintenance($limit, $offset) {
    $query = "SELECT * FROM maintenance ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getMaintenanceCount() {
    $query = "SELECT COUNT(*) FROM maintenance";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getMaintenanceById($id) {
    $stmt = $this->db->conn->prepare("SELECT * FROM maintenance WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function addMaintenance($title, $content, $date, $image) {
    $stmt = $this->db->conn->prepare("INSERT INTO maintenance (title, content, date, image) VALUES (:title, :content, :date, :image)");
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    return $stmt->execute();
}

public function updateMaintenance($id, $title, $content, $date, $image = null) {
    if ($image) {
        $stmt = $this->db->conn->prepare("UPDATE maintenance SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    } else {
        $stmt = $this->db->conn->prepare("UPDATE maintenance SET title = :title, content = :content, date = :date WHERE id = :id");
    }

    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public function deleteMaintenance($id) {
    $stmt = $this->db->conn->prepare("DELETE FROM maintenance WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}


// Add these methods to your Functions class

public function getPaginatedNationalStories($limit, $offset) {
    $query = "SELECT * FROM national_stories ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getNationalStoriesCount() {
    $query = "SELECT COUNT(*) FROM national_stories";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getNationalStoriesById($id) {
    $stmt = $this->db->conn->prepare("SELECT * FROM national_stories WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function addNationalStories($title, $content, $date, $image) {
    $stmt = $this->db->conn->prepare("INSERT INTO national_stories (title, content, date, image) VALUES (:title, :content, :date, :image)");
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    return $stmt->execute();
}

public function updateNationalStories($id, $title, $content, $date, $image = null) {
    if ($image) {
        $stmt = $this->db->conn->prepare("UPDATE national_stories SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    } else {
        $stmt = $this->db->conn->prepare("UPDATE national_stories SET title = :title, content = :content, date = :date WHERE id = :id");
    }

    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public function deleteNationalStories($id) {
    $stmt = $this->db->conn->prepare("DELETE FROM national_stories WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}

// Add these methods to your Functions class

public function getPaginatedPowerRate($limit, $offset) {
    $query = "SELECT * FROM power_rate ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getPowerRateCount() {
    $query = "SELECT COUNT(*) FROM power_rate";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getPowerRateById($id) {
    $stmt = $this->db->conn->prepare("SELECT * FROM power_rate WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function addPowerRate($title, $content, $date, $image) {
    $stmt = $this->db->conn->prepare("INSERT INTO power_rate (title, content, date, image) VALUES (:title, :content, :date, :image)");
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    return $stmt->execute();
}

public function updatePowerRate($id, $title, $content, $date, $image = null) {
    if ($image) {
        $stmt = $this->db->conn->prepare("UPDATE power_rate SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    } else {
        $stmt = $this->db->conn->prepare("UPDATE power_rate SET title = :title, content = :content, date = :date WHERE id = :id");
    }

    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public function deletePowerRate($id) {
    $stmt = $this->db->conn->prepare("DELETE FROM power_rate WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}

// Add these methods to your Functions class

public function getPaginatedUncategorized($limit, $offset) {
    $query = "SELECT * FROM uncategorized ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getUncategorizedCount() {
    $query = "SELECT COUNT(*) FROM uncategorized";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getUncategorizedById($id) {
    $stmt = $this->db->conn->prepare("SELECT * FROM uncategorized WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function addUncategorized($title, $content, $date, $image) {
    $stmt = $this->db->conn->prepare("INSERT INTO uncategorized (title, content, date, image) VALUES (:title, :content, :date, :image)");
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    return $stmt->execute();
}

public function updateUncategorized($id, $title, $content, $date, $image = null) {
    if ($image) {
        $stmt = $this->db->conn->prepare("UPDATE uncategorized SET title = :title, content = :content, date = :date, image = :image WHERE id = :id");
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
    } else {
        $stmt = $this->db->conn->prepare("UPDATE uncategorized SET title = :title, content = :content, date = :date WHERE id = :id");
    }

    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

public function deleteUncategorized($id) {
    $stmt = $this->db->conn->prepare("DELETE FROM uncategorized WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}

public function fetchAccreditedUsers($conn) {
    $query = "SELECT * FROM tbl_accredited_users ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>
