define(['jquery', 'js/jquery.dataTables.min.js', 'js/jquery-ui.min.js'] , function($) {

    var exports = {};

    var init = exports.init = function() {
        $( "#id_situacao" ).change(function() {
            alert( "Handler for .change() called." );
        });

        var tabelaContatos = $('#tabela_contatos').DataTable({
            //dom: "Bfrtip",
            ajax: {
                url: "proxy.php",
//                dataType: "json",
  //              contentType: "aplication/json",
                dataSrc:""
            },
            columns: [
                { data: "id" },
                { data: "title" },
                { data: "description" }
            ],
        //select: true
        });

        $( "#tabs" ).tabs({ active: 0 });

        $('#tabela_contatos tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');

            }
            else {
                tabelaContatos.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }

            getMessagesFromContact();
        } );
    }
    
    /* 
    Obtém todas as trocas de mensagens relacionadas a um determinado contato no Fale Conosco
    */
    var getMessagesFromContact = exports.getMessagesFromContact = function(contactID) {
        $.ajax({
            url: "proxy.php?contactID=" + contactID,
            async: true,
            dataType: 'json',
            success: function (data) {
                var mensagens = "";
                for (var i = 0, len = data.length; i < len; i++) {
                    var desc = data[i].body;
                    arrayReturn.push([ data[i].id, data[i].name, data[i].email]);
                    mensagens += 
                      '<div class="panel panel-default"> '+
                      '  <div class="panel-heading">'+
                      '      <h3 class="panel-title">' + data[i].description + '</h3>'+
                      '  </div>'+
                      '  <div class="panel-body">'+
                      data[i].description +  
                      '  </div>'+
                      '</div>';    
                };
                mensagens += 
                    '<div class="form-group">'+
                    '<label for="comment">Sua resposta:</label>'+
                    '<textarea class="form-control" rows="5" id="comment"></textarea>'+
                    '</div>' + 
                    '<button type="button" id="sendMessage" class="btn">Basic</button>';

                $('#mensagens').html(mensagens);
                $('#sendMessage').click(function (){
                    $.ajax({
                        url: "proxy.php?addMessage=1",
                        data: "Teste", //ur data to be sent to server
                        contentType: "application/json; charset=utf-8", 
                        type: "POST",
                        success: function (data) {
                           alert("Mensagem enviada!");
                           $( "#tabs" ).tabs({ active: 0 });
                        },
                        error: function (x, y, z) {
                           alert(x.responseText +"  " +x.status);
                        }
                    });                    
                });
                $( "#tabs" ).tabs({ active: 1 });
            }            
        });        
    };

    /* 
    Obtém dados de todos os contatos do Fale Conosco
    */
   var getTableContacts = exports.getTableContacts = function() {
            
        $( "#tabs" ).tabs();

        $('ul.tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');
    
            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');
    
            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })

        /*
        var arrayReturn = [];
        $.ajax({
            url: "proxy.php",
            async: true,
            dataType: 'json',
            success: function (data) {
                for (var i = 0, len = data.length; i < len; i++) {
                    var desc = data[i].body;
                    arrayReturn.push([ data[i].id, data[i].name, data[i].email]);
                }
                
                tabela = $('#tabela_contatos').DataTable(
                    //{"aaData": arrayReturn}
                    {ajax: "proxy.php"}
                    );

                //$( "#tabs" ).tabs({ active: 0 });

                $('#tabela_contatos tbody').on( 'click', 'tr', function () {
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');

                    }
                    else {
                        tabela.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }

                    getMessagesFromContact();
                } );
            }
        });
        */
    };

    return exports;
});