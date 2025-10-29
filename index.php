<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once "config/Database.php";
require_once "config/Auth.php";
require_once "models/EventsModel.php";

function jsonResponse($data, $status = 200)
{
  http_response_code($status);
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit;
}

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$headers = getallheaders();
$api_key = $headers["X-API-Key"] ?? $headers["x-api-key"] ?? null;

if (!$api_key || !$auth->checkApiKey($api_key)) {
  jsonResponse(["error" => "Invalid API key"], 401);
}

$event = new EventsModel($db);

$method = $_SERVER["REQUEST_METHOD"];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$id = $uri[2] ?? null;

error_log('Request URI: ' . $_SERVER['REQUEST_URI']);
error_log('Parsed id: ' . $id);

$inputData = json_decode(file_get_contents("php://input"), true) ?? [];

function filterInput($data)
{
  return [
    'title' => htmlspecialchars(strip_tags($data['title'] ?? "")),
    'date' => htmlspecialchars(strip_tags($data['date'] ?? "")),
    'location' => htmlspecialchars(strip_tags($data['location'] ?? "")),
    'description' => htmlspecialchars(strip_tags($data['description'] ?? ""))
  ];
}

switch ($method) {
  case "GET":
    if ($id) {
      $item = $event->readOne($id);
      if ($item) {
        jsonResponse($item);
      } else {
        jsonResponse(["error" => "Event not found"], 404);
      }
    } else {
      jsonResponse($event->readAll());
    }
    break;
  case "POST":
    $input = filterInput($inputData);
    if (!$input["title"] || !$input["date"]) {
      jsonResponse(["error" => "Title and date are required"], 400);
    }
    $created = $event->create($input);
    if ($created) {
      jsonResponse(["message" => "Event created"], 201);
    } else {
      jsonResponse(["error" => "Failed to create event"], 500);
    }
    break;
  case "PUT":
    if (!$id) jsonResponse(["error" => "ID is required"], 400);
    $input = filterInput($inputData);
    if (!$input["title"] || !$input["date"]) {
      jsonResponse(["error" => "Title and date are required"], 400);
    }
    $updated = $event->update($id, $input);
    if ($updated) {
      jsonResponse(["message" => "Event updated"]);
    } else {
      jsonResponse(["error" => "Failed to update event"], 500);
    }
    break;
  case "DELETE":
    if (!$id) jsonResponse(["error" => "ID is required"], 400);
    $deleted = $event->delete($id);
    if ($deleted) {
      jsonResponse(["message" => "Event deleted"]);
    } else {
      jsonResponse(["error" => "Failed to delete event"], 500);
    }
    break;
  default:
    jsonResponse(["error" => "Method not allowed"], 405);
}
