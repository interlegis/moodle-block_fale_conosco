<?php
include('httpful.phar');
require_once("../../config.php");
header("Content-Type: application/json");

if(isset($_GET["conversationID"])) {
    $id = intval($_GET['conversationID']);
    $uri = 'https://escolamodelows.interlegis.leg.br/api/v1/fale_conosco/mensagens';
    // $uri = 'http://localhost:3000/api/v1/fale_conosco/mensagens';
    $response = \Httpful\Request::post($uri)
    ->sendsJson()
    ->body('{"conversation_id": "' . $id . '"}')
    ->send();
} elseif(isset($_REQUEST["addMessage"])) {
    $id = intval($_GET['addMessage']);
    $cpf = $USER->username;
    $description = $_GET['description'];

    $uri = 'https://escolamodelows.interlegis.leg.br/api/v1/fale_conosco/adicionar';
    // $uri = 'http://localhost:3000/api/v1/fale_conosco/adicionar';
    $response = \Httpful\Request::post($uri)
    ->sendsJson()
    ->body('{
      	"name": "' . $USER->username . '",
      	"email": "' . $USER->email . '",
      	"cpf": "' . $cpf . '",
      	"description": "' . $description . '",
      	"is_student": false,
        "conversation_id": "' . $id . '"
    }')
    ->send();
} elseif(isset($_REQUEST["schoolInitials"])) {
  $not_answered = intval($_GET['answered'])== 0 ? 'false':'true';
  $not_answered = trim($not_answered, '"');
  $uri = 'https://escolamodelows.interlegis.leg.br/api/v1/fale_conosco/conversa';
  // $uri = 'http://localhost:3000/api/v1/fale_conosco/conversa';
  $response = \Httpful\Request::post($uri)
  ->sendsJson()
  ->body(
    '{
      "school_initials": "SSL",
      "page" : "1",
      "limit": "2000",
      "not_answered": '. $not_answered .'
  }')
    ->send();
}

$data=$response->body;
echo json_encode($data);
