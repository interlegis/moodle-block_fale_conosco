define(['jquery', 'js/jquery.dataTables.min.js', 'js/jquery-ui.min.js'] , function($) {

  var exports = {};
  var answered = 1;
  var init = exports.init = function() {
    // Altera entre mensagens respondidas e não respondidas
    $( "#id_situacao" ).change(function() {
      answered = $(this).val();
      $("#id_situacao").val(answered);
      tabelaContatos.ajax.url('proxy.php?answered=' + answered)
      tabelaContatos.ajax.reload();
    });

    // Obtém dados de todas as conversas
    var tabelaContatos = $('#tabela_contatos').DataTable({
      ajax: {
        url: "proxy.php?answered=" + 0,
        dataSrc:""
      },
      columns: [
        { data: "id_conversa" },
        { data: "titulo" },
        { data: "cpf" }
      ],
      // Passando o datatable para português
      "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "_MENU_ resultados por página",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "Nenhum registro encontrado",
        "sSearch": "Pesquisar",
        "oPaginate": {
          "sNext": "Próximo",
          "sPrevious": "Anterior",
          "sFirst": "Primeiro",
          "sLast": "Último"
        },
        "oAria": {
          "sSortAscending": ": Ordenar colunas de forma ascendente",
          "sSortDescending": ": Ordenar colunas de forma descendente"
        }
      },
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
      data = tabelaContatos.row(this).data();
      getMessagesFromContact(data);
    } );
  }

  // Obtém todas as trocas de mensagens relacionadas a um determinado contato no Fale Conosco
  var getMessagesFromContact = exports.getMessagesFromContact = function(data_conversation) {
    $.ajax({
      url: "proxy.php?conversationID=" + data_conversation.id_conversa,
      type: "POST",
      dataType: 'json',
      success: function (data) {
        var mensagens = "";
        for (var i = 0, len = data.length; i < len; i++) {
          var desc = data[i].body;
          arrayReturn.push([ data[i].id, data[i].name, data[i].email]);
          var data_mensagem = data[i].data_mensagem.substring(8, 10) + '/' + data[i].data_mensagem.substring(5, 7) + '/' + data[i].data_mensagem.substring(0, 4);
          var hora_mensagem = data[i].data_mensagem.substring(11, 16)
          mensagens += '<div class="row">'
          if (data[i].aluno != false) {
            mensagens += '<div class="col-sm-10">'
            mensagens += '<div class="panel panel-success"> ';
          }
          else {
            mensagens += '<div class="col-sm-10 pull-right">'
            mensagens += '<div class="panel panel-primary"> ';
          }
          mensagens +=
          '  <div class="panel-heading">'+
          '      <h3 class="panel-title">' + data[i].cpf + '</h3>'+
          '  </div>'+
          '  <div class="panel-body" style="word-break: break-all;">'+
          data[i].texto_mensagem +
          '  </div><div class="text-right" style="font-style: italic; margin-right:10px;">'+
          // '<hr>' + //Com ou sem?
          data_mensagem + '  ' +
          hora_mensagem +
          '</div></div></div></div><hr>';
        };
        mensagens +=
          '<div class="row">' +
          '<div class="form-group">'+
          '<label for="comment">Mensagem:</label>'+
          '<textarea class="form-control" rows="5" id="comment"></textarea>'+
          '</div>' +
          '<button type="button" id="sendMessage" class="btn">Enviar</button>' +
          '</div>';

        $('#mensagens').html(mensagens);
        $('#sendMessage').click(function (){
          description = ($('#comment').val());
          description = description.replace(/(?:\r\n|\r|\n)/g, '<br>');
          $('#comment').attr('value', "")
          $.ajax({
            url: "proxy.php?addMessage=" + data_conversation.id_conversa + "&description=" + description,
            contentType: "application/json; charset=utf-8",
            type: "post",
            dataType: 'json',
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

  // Obtém dados de todos os contatos do Fale Conosco
  var getTableContacts = exports.getTableContacts = function() {
    $( "#tabs" ).tabs();
    $('ul.tabs li').click(function(){
      var tab_id = $(this).attr('data-tab');
      $('ul.tabs li .tab-content').removeClass('current');
      $(this).addClass('current');
      $("#"+tab_id).addClass('current');
    })
  };

  return exports;
});
