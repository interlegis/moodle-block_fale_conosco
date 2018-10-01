<?php
require_once("../../config.php");
require_once("./searchform.php");
include('httpful.phar');

// Check if user is logged and not guest
require_login();
if (isguestuser()) {
    die();
}

global $DB;

// Prepare page
$context = context_system::instance(); 
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/blocks/fale_conosco/index.php');
$PAGE->requires->css(new moodle_url('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'));
$PAGE->requires->css(new moodle_url('https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'));
$PAGE->requires->css(new moodle_url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'));


//$PAGE->requires->js('/blocks/fale_conosco/datatable.js', true);
/*
$PAGE->requires->js(new moodle_url('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'),true);
$PAGE->requires->js(new moodle_url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'),true);
*/

$PAGE->set_context($context);


// Get data
$strtitle = get_string('fale_conosco', 'block_fale_conosco');
$user = $USER;
//$icon = $OUTPUT->pix_icon('print', get_string('print', 'block_fale_conosco'), 'block_fale_conosco');

global $DB, $CFG;


/*
$uri = 'https://escolamodelows.interlegis.leg.br/api/v1/fale_conosco';
$response = \Httpful\Request::get($uri)
            //->sendsJson()
            //->body('{"school_initial": "SSL"}')
            ->expectsJson()
            ->send();

$data=$response->body;

html_writer::span("<p>Quantidade" . count($data) . '</p>');


// Print the header
$PAGE->navbar->add($strtitle);
$PAGE->set_title($strtitle);
$PAGE->set_heading($strtitle);

*/
//$PAGE->requires->css('/blocks/fale_conosco/printstyle.css');
echo $OUTPUT->header();

// Print concluded courses
echo $OUTPUT->box_start('generalbox boxaligncenter');
echo $OUTPUT->heading(get_string('fale_conosco', 'block_fale_conosco'));


//Instantiate simplehtml_form 
$mform = new searchform();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.

  //Set default data (if any)
  //$mform->set_data($toform);
  //displays the form
  $mform->display();
}
?>
<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/>
-->
<style>
    /*
    body{
			margin-top: 100px;
			font-family: 'Trebuchet MS', serif;
			line-height: 1.6
		}
		.container{
			width: 800px;
			margin: 0 auto;
		}



		ul.tabs{
			margin: 0px;
			padding: 0px;
			list-style: none;
		}
		ul.tabs li{
			background: none;
			color: #222;
			display: inline-block;
			padding: 10px 15px;
			cursor: pointer;
		}

		ul.tabs li.current{
			background: #ededed;
			color: #222;
		}

		.tab-content{
			display: none;
			background: #ededed;
			padding: 15px;
		}

		.tab-content.current{
			display: inherit;
		}
        */
</style>

<script>

        var arrayReturn = [];
        
        /*$.ajax({
            url: "http://jsonplaceholder.typicode.com/posts",
            async: true,
            dataType: 'json',
            success: function (data) {
                for (var i = 0, len = data.length; i < len; i++) {
                    var desc = data[i].body;
                    arrayReturn.push([ data[i].userId, '<a href="http://google.com" target="_blank">'+data[i].title+'</a>', desc.substring(0, 12)]);
                }
                alert('loaded ' + data.length);
                inittable(arrayReturn);
            }
        });

        function inittable(data) { 
            //console.log(data);
            $('#photos').DataTable( {
                "aaData": data
            } );
        }*/
</script>

<?php
$PAGE->requires->js_call_amd('block_fale_conosco/config', 'init');
//$PAGE->requires->js_call_amd('block_fale_conosco/config', 'getTableContacts');
?>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Contatos</a></li>
    <li><a href="#tabs-2">Detalhes</a></li>
  </ul>
  <div id="tabs-1">
    <table id="tabela_contatos" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>E-mail</th>
            </tr>
        </thead>
    </table>
  </div>
  <div id="tabs-2">
    <div id="mensagens"></div>
  </div>
</div>


<?php
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
