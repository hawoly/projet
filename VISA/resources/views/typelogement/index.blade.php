<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>visa</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 </head>
 <body>
  <div class="container">    
     <br />
 
     <br />
     <div align="right">
      <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">un autre type de logement</button>
     </div>
     <br />
   <div class="table-responsive">
    <table id="user_table" class="table table-bordered table-striped">
     <thead>
      <tr>
       <th width="35%">typelogement</th>
                
                <th width="30%">Action</th>
      </tr>
     </thead>
    </table>
   </div>
   <br />
   <br />
  </div>
 </body>
</html>

<div id="formModal" class="modal fade" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ajouter un autre type</h4>
        </div>
        <div class="modal-body">
         <span id="form_result"></span>
         <form method="post" id="sample_form" class="form-horizontal">
          @csrf
          <div class="form-group">
            <label class="control-label col-md-4" >Type Logement: </label>
            <div class="col-md-8">
             <input type="text" name="typelogement" id="typelogement" class="form-control" />
            </div>
           </div>
         
        
                <br />
                <div class="form-group" align="center">
                 <input type="hidden" name="action" id="action" value="Add" />
                 <input type="hidden" name="hidden_id" id="hidden_id" />
                 <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Add" />
                </div>
         </form>
        </div>
     </div>
    </div>
</div>

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Confirmation</h2>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
            </div>
            <div class="modal-footer">
             <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){

 $('#user_table').DataTable({
  processing: true,
  serverSide: true,
  ajax: {
   url: "{{ route('logement.index') }}",
  },
  columns: [
   {
    data: 'typelogement',
    name: 'typelogement'
   },
   {
    data: 'action',
    name: 'action',
    orderable: false
   }
  ]
 });

 $('#create_record').click(function(){
  $('.modal-title').text('ajouter un type de logement');
  $('#action_button').val('Ajouter');
  $('#action').val('Add');
  $('#form_result').html('');

  $('#formModal').modal('show');
 });

 $('#sample_form').on('submit', function(event){
  event.preventDefault();
  var action_url = '';

  if($('#action').val() == 'Add')
  {
   action_url = "{{ route('logement.store') }}";
  }

  if($('#action').val() == 'Edit')
  {
   action_url = "{{ route('logement.update') }}";
  }

  $.ajax({
   url: action_url,
   method:"POST",
   data:$(this).serialize(),
   dataType:"json",
   success:function(data)
   {
    var html = '';
    if(data.errors)
    {
     html = '<div class="alert alert-danger">';
     for(var count = 0; count < data.errors.length; count++)
     {
      html += '<p>' + data.errors[count] + '</p>';
     }
     html += '</div>';
    }
    if(data.success)
    {
     html = '<div class="alert alert-success">' + data.success + '</div>';
     $('#sample_form')[0].reset();
     $('#user_table').DataTable().ajax.reload();
    }
    $('#form_result').html(html);
   }
  });
 });

 $(document).on('click', '.edit', function(){
  var id = $(this).attr('id');
  $('#form_result').html('');
  $.ajax({
   url :"/logement/"+id+"/edit",
   dataType:"json",
   success:function(data)
   {
    $('#typelogement').val(data.result.typelogement);
    $('#hidden_id').val(id);
    $('.modal-title').text('Edit Record');
    $('#action_button').val('Edit');
    $('#action').val('Edit');
    $('#formModal').modal('show');
   }
  })
 });

 var user_id;

 $(document).on('click', '.delete', function(){
  user_id = $(this).attr('id');
  $('#confirmModal').modal('show');
 });

 $('#ok_button').click(function(){
  $.ajax({
   url:"logement/destroy/"+user_id,
   beforeSend:function(){
    $('#ok_button').text('Deleting...');
   },
   success:function(data)
   {
    setTimeout(function(){
     $('#confirmModal').modal('hide');
     $('#user_table').DataTable().ajax.reload();
     alert('Data Deleted');
    }, 20);
   }
  })
 });

});
</script>
</body>
</html>
