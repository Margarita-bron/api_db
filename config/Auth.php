<?php
class Auth
{
  private $conn;
  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function checkApiKey($key)
  {
    $stmt = $this->conn->prepare("SELECT api_key FROM api_keys WHERE is_active = 1");
    $stmt->execute();
    $keys = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($keys as $hash) {
      if (password_verify($key, $hash)) {
        return true;
      }
    }
    return false;
  }
}
