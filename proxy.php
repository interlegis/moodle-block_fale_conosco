<?php
include_once($CFG->dirroot . "/blocks/escola_modelo/lib/httpful.phar");
require_once("../../config.php");
require_once($CFG->dirroot . "/blocks/escola_modelo/classes/util.php");
header("Content-Type: application/json");

if(isset($_GET["conversationID"])) {
    $id = intval($_GET['conversationID']);
    $uri = evlURLWebServices() + '/api/v1/fale_conosco/mensagens';
    $response = \Httpful\Request::post($uri)
    ->sendsJson()
    ->body('{"conversation_id": "' . $id . '"}')
    ->send();
} elseif(isset($_REQUEST["addMessage"])) {
    $id = intval($_GET['addMessage']);
    $cpf = $USER->username;
    $description = $_GET['description'];

    $uri = evlURLWebServices() + '/api/v1/fale_conosco/adicionar';
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
} else {
  $was_answered = intval($_GET['answered'])== 0 ? 'false':'true';
  $was_answered = trim($was_answered, '"');
  $uri = evlURLWebServices() . '/api/v1/fale_conosco/conversa';
  $response = \Httpful\Request::post($uri)
  ->sendsJson()
  ->body(
    '{
      "school_initials": "' . evlSiglaEscola() . '",
      "page" : "1",
      "limit": "2000",
      "was_answered": '. $was_answered .'
  }')
  ->send();
}

$data=$response->body;
echo json_encode($data);
