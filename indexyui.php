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
//$PAGE->requires->js('/blocks/fale_conosco/datatable.js', true);
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

/*
$table = new html_table();
$table->align = array('left', 'left', 'left', 'center');
$table->width = '100%';
$table->head  = array(
    get_string('id', 'block_fale_conosco'), 
    get_string('name', 'block_fale_conosco'), 
    get_string('email, block_fale_conosco'), 
    get_string('cpf', 'block_fale_conosco')
);

$table->data = $data;
echo html_writer::table($table);
*/
?>

<script>
YUI().use('datatable', 'datasource-io', 'datasource-get', 'datasource-local', 
    'datasource-jsonschema', 'datatable-paginator', 'tabview', 'escape', 'plugin',
    function(Y){

    var dataSource = new Y.DataSource.IO({
        source:"proxy.php?zip=94089&query=chinese"
    });
    
    dataSource.plug(Y.Plugin.DataSourceJSONSchema, {
        schema: {
            resultListLocator: "",
            resultFields: [
                {key:"id"},
                {key:"name"},
                {key:"email"}
            ]
        }
    });
    
    var cols = [
        {key: "id"},//, sortable: true},
        {key: "name"},//, sortable: true},
        {key: 'email'}//, sortable: true}
    ];

   
    var table = new Y.DataTable({
        columns: cols,
        rowsPerPage: 10,
        //pageSizes: [ 4, 'Show All' ]
    });
    table.plug(Y.Plugin.DataTableDataSource, {
        datasource: dataSource,
        initialRequest: ""
    });
    table.addAttr("selectedRow", { value: null });
    table.delegate('click', function (e) {
        this.set('selectedRow', e.currentTarget);
    }, '.yui3-datatable-data tr', table);
    
    
    var tabview = new Y.TabView({
        srcNode: '#demo',
    }).render("#tabs");

    table.after('selectedRowChange', function (e) {
        var tr = e.newVal,              // the Node for the TR clicked ...
            last_tr = e.prevVal,        //  "   "   "   the last TR clicked ...
            rec = this.getRecord(tr);   // the current Record for the clicked TR

        //Y.log(rec.get('id'));
        usuario = Y.one('#id_usuario'), 
        usuario.set('value', rec.get("id"));
    
        var dataSourceContato = new Y.DataSource.IO({
            source:"proxy.php?zip=94089&query=chinese"
        });
        
        dataSourceContato.plug(Y.Plugin.DataSourceJSONSchema, {
            schema: {
                resultListLocator: "",
                resultFields: [
                    {key:"id"},
                    {key:"name"},
                    {key:"email"}
                ]
            }
        });

        tabview.selectChild(1);

        //
        //  This if-block does double duty,
        //  (a) it tracks the first click to toggle the "details" DIV to visible
        //  (b) it un-hightlights the last TR clicked
        //
        if ( !last_tr ) {
            // first time thru ... display the Detail DT DIV that was hidden
            //Y.one("#chars").show();
        } else {
            last_tr.removeClass("myhilite");
        }

        //
        //  After unhighlighting, now highlight the current TR
        //
        tr.addClass("myhilite");
    });
    
    dataSource.after("response", function() {
        table.render("#datatable")}
    );

});

</script>

<table id="grid-basic" class="table table-condensed table-hover table-striped">
    <thead>
        <tr>
            <th data-column-id="id" data-type="numeric">ID</th>
            <th data-column-id="sender">Sender</th>
            <th data-column-id="received" data-order="desc">Received</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>10238</td>
            <td>eduardo@pingpong.com</td>
            <td>14.10.2013</td>
        </tr>
        ...
    </tbody>
</table>

<style>
.yui3-skin-sam .yui3-datatable-caption {
    font-size: 13px;
    font-style: normal;
    text-align: left;
}

.yui3-datatable-col-nchars {
    text-align: center;
}

.yui3-skin-sam .yui3-datatable tr.myhilite td {
    background-color: #C0ffc0;
}

#datatable tbody tr {      /*  Turn on cursor to show TR's are selectable on Master DataTable only  */
    cursor: pointer;
}
</style>

<div id="demo">
    <ul>
        <li><a href="#datatable">Tabela</a></li>
        <li><a href="#datatable-item">Mensagem</a></li>
    </ul>
    <div>
        <div class="yui3-u-1-3" id="datatable">
        </div>
        <div id="datatable-item">
            <p>Mensagem específica</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>

<script>
var arrayReturn = [];
            $.ajax({
                url: "http://jsonplaceholder.typicode.com/posts",
                async: false,
                dataType: 'json',
                success: function (data) {
 for (var i = 0, len = data.length; i < len; i++) {
 var desc = data[i].body;
 arrayReturn.push([ data[i].userId, '<a href="http://google.com" target="_blank">'+data[i].title+'</a>', desc.substring(0, 12)]);
 }
 inittable(arrayReturn);
                }
            });

function inittable(data) { 
 //console.log(data);
 $('#photos').DataTable( {
 "aaData": data
 } );
 }
</script>

<table id="photos" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Title</th>
                <th>Body</th>
            </tr>
        </thead>
        
    </table>

<?php
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
