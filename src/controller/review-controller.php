<?php
require_once __DIR__ . '/../repositories/review-repository.php';
require_once __DIR__ . '/base-controller.php';

class ReviewController extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new ReviewRepository();
  }

  /**
   * Submit a new review (no booking validation)
   */
  public function submitReview()
  {
    try {
      $this->repository->startTransaction();

      // Get POST data
      $propertyId = $_POST['property_id'] ?? null;
      $clientName = trim($_POST['client_name'] ?? 'Anonymous');
      $rating = $_POST['rating'] ?? null;
      $comment = trim($_POST['comment'] ?? '');

      // Validation
      $errors = [];

      if (empty($propertyId)) {
        $errors['property_id'] = '*Property ID is required';
      }

      if (empty($rating) || $rating < 1 || $rating > 5) {
        $errors['rating'] = '*Please select a rating between 1 and 5 stars';
      }

      if (!empty($errors)) {
        return $this->response([
          'error' => true,
          'message' => 'Please fill in all required fields.',
          'fields' => $errors
        ]);
      }

      // If no name provided, use "Anonymous"
      if (empty($clientName)) {
        $clientName = 'Anonymous';
      }

      // Add new review
      $result = $this->repository->addReview(
        $propertyId,
        $clientName,
        $rating,
        $comment
      );

      $this->repository->commitTransaction();

      return $this->response([
        'error' => false,
        'message' => 'Thank you for your review!',
        'data' => [
          'action' => 'created'
        ]
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }

  /**
   * Get rating statistics for a property
   */
  public function getPropertyRating($propertyId)
  {
    try {
      return $this->repository->getRatingStats($propertyId);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  /**
   * Get reviews for a property
   */
  public function getReviews($propertyId, $limit = 10, $offset = 0)
  {
    try {
      $reviews = $this->repository->getReviewsByProperty($propertyId, $limit, $offset);
      $stats = $this->repository->getRatingStats($propertyId);

      return $this->response([
        'error' => false,
        'data' => [
          'reviews' => $reviews,
          'stats' => $stats
        ]
      ]);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
