<?php
require_once __DIR__ . '/base-repository.php';

class ReviewRepository extends base_repository
{
  /**
   * Add a new review (no booking validation required)
   */
  public function addReview($propertyId, $clientName, $rating, $comment)
  {
    $sql = "INSERT INTO reviews (property_id, client_name, rating, comment) 
            VALUES (:propertyId, :clientName, :rating, :comment)";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
    $stmt->bindParam(':clientName', $clientName);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':comment', $comment);

    return $stmt->execute();
  }

  /**
   * Get average rating for a property
   */
  public function getAverageRating($propertyId)
  {
    $sql = "SELECT AVG(rating) as average_rating 
            FROM reviews 
            WHERE property_id = :propertyId";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['average_rating'] ? round($result['average_rating'], 1) : 0;
  }

  /**
   * Get total review count for a property
   */
  public function getReviewCount($propertyId)
  {
    $sql = "SELECT COUNT(*) as count 
            FROM reviews 
            WHERE property_id = :propertyId";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
  }

  /**
   * Get reviews for a property with pagination
   */
  public function getReviewsByProperty($propertyId, $limit = 10, $offset = 0)
  {
    $sql = "SELECT 
              review_id,
              client_name,
              rating,
              comment,
              created_at
            FROM reviews
            WHERE property_id = :propertyId
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Get rating statistics for a property
   */
  public function getRatingStats($propertyId)
  {
    $sql = "SELECT 
              AVG(rating) as average_rating,
              COUNT(*) as total_reviews,
              SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
              SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
              SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
              SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
              SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
            FROM reviews 
            WHERE property_id = :propertyId";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['total_reviews'] == 0) {
      return [
        'average_rating' => 0,
        'total_reviews' => 0,
        'five_star' => 0,
        'four_star' => 0,
        'three_star' => 0,
        'two_star' => 0,
        'one_star' => 0
      ];
    }

    $result['average_rating'] = round($result['average_rating'], 1);
    return $result;
  }
}
