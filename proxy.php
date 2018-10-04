<?php
include('httpful.phar');

header("Content-Type: application/json");

if(isset($_GET["contactID"])) {
    $uri = 'https://escolamodelows.interlegis.leg.br/api/v1/fale_conosco/mensagens_conversa';
    $response = \Httpful\Request::post($uri)
    ->sendsJson()
    ->body('{"conversation_id": "1"}')
    //->expectsJson()
    ->send();
} elseif(isset($_REQUEST["addMessage"])) {
    $uri = 'https://escolamodelows.interlegis.leg.br/api/v1/fale_conosco/adicionar';
    $response = \Httpful\Request::post($uri)
    ->sendsJson()
    ->body('{
        "name": "Matheus",
        "email": "garcia.figueiredo@gmail.com",
        "cpf": "05272886674",
        "description": "Palmeiras nunca vai ganhar um mundial",
        "date": "2018-08-01",
        "course_id": "1",
        "course_category_id": "1" ,
        "school_initials": "SSL",
        "id_conversation": "1"
        }')
    ->send();
}
else {
    $uri = 'https://escolamodelows.interlegis.leg.br/api/v1/fale_conosco/conversa';
    $response = \Httpful\Request::post($uri)
    ->sendsJson()
    ->body(
        '{
            "school_initials": "SSL",
            "page" : "1",
            "limit": "10"
        }'
    )
    //->expectsJson()
    ->send();
}

$data=$response->body;
echo json_encode($data);
