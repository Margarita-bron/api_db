<?php
class EventsModel
{
  public $conn;
  public $table = 'events';

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function readAll()
  {
    $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function readOne($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
    $stmt->execute(['id' => (int)$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  public function create($data)
  {
    $stmt = $this->conn->prepare("INSERT INTO {$this->table} (title, date, location, description) VALUES(:title, :date, :location, :description)");
    $stmt->execute($data);
    return $stmt;
  }

  public function update($id, $data)
  {
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET title=:title, date=:date, location=:location, description=:description WHERE id=:id");
    $data['id'] = $id;
    $stmt->execute($data);
    return $stmt;
  }

  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=:id");
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
  }
}
