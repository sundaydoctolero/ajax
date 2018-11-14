<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ajax Links Sample</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- toastr notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

</head>
<body>
    <div class="container">
        <div class="card card-block">
            <h2 class="card-title">Laravel AJAX Examples</h2>

        </div>

        <button id="btn-add" name="btn-add" class="btn btn-primary btn-xs">Add New Record</button>
        <table class="table table-inverse">
            <thead>
            <tr>
                <th>ID</th>
                <th>Link</th>
                <th>Description</th>
                <th>Command</th>
            </tr>
            </thead>
            <tbody id="links-list" name="links-list">
            @foreach ($links as $link)
                <tr id="link{{$link->id}}">
                    <td>{{$link->id}}</td>
                    <td>{{$link->url}}</td>
                    <td>{{$link->description}}</td>
                    <td>
                        <button class="btn btn-info btn-open" value="{{$link->id}}">Edit
                        </button>
                        <button class="btn btn-danger btn-delete" value="{{$link->id}}">Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>

        <div class="modal fade" id="modalForm" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalFormTitle"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="frmModal" name="frmModal" class="form-horizontal">
                            <div class="form-group">
                                <label for="inputLink" class="col-sm-2 control-label">Link</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="link" name="link"
                                           placeholder="Enter URL" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="description" name="description"
                                           placeholder="Enter Link Description" value="">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-save" value="add">Save changes
                        </button>
                        <input type="hidden" id="link_id" name="link_id" value="0">
                    </div>
                </div>
            </div>
        </div>







    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- toastr notifications -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#btn-add').click(function(){
                $('#btn-save').val('add');
                $('#frmModal').trigger("reset");
                $('#modalFormTitle').text("Add New Record");
                $('#modalForm').modal("show");
            })

            $('body').on('click', '.btn-open', function () {
                var link_id = $(this).val();
                $.get('links/'+link_id,function(data){
                    console.log(data);
                    $('#btn-save').val('update');
                    $('#frmModal').trigger("reset");
                    $('#link_id').val(data.id);
                    $('#link').val(data.url);
                    $('#description').val(data.description);
                    $('#modalFormTitle').text("Modify Record");
                    $('#modalForm').modal("show");
                })
            });

            $('#btn-save').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var formData = {
                    url: $('#link').val(),
                    description: $('#description').val()
                }

                var state = $("#btn-save").val();
                var type = "POST";
                var link_id = $("#link_id").val();
                var ajaxurl = "/links";

                if (state == "update") {
                    type = "PUT";
                    ajaxurl = 'links/' + link_id;
                }

                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function(data){
                        var row = '<tr id="link' + data.id + '"><td>' + data.id + '</td><td>' + data.url + '</td><td>' + data.description + '</td>';
                        row += '<td><button class="btn btn-info btn-open" value="' + data.id + '">Edit</button>&nbsp;';
                        row += '<button class="btn btn-danger btn-delete" value="' + data.id + '">Delete</button></td></tr>';
                        if(state == "add"){
                            $('#links-list').append(row);
                        } else if (state == 'update'){
                            $('#link' + data.id).replaceWith(row);
                        }
                        $('#frmModal').trigger("reset");
                        $('#modalForm').modal("hide");
                        toastr.success('Successfully added Post!' + data.url, 'Success Alert', {timeOut: 5000});
                    },
                    error: function(data){
                        var errors = data.responseJSON;
                        console.log(errors);
                        toastr.error('Validation Error!' , 'Error Alert', {timeOut: 5000});
                    }
                });


            });

            $('body').on('click', '.btn-delete', function () {
                var link_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                   type: "DELETE",
                   url: '/links/' + link_id,
                   success: function(data){
                       $("#link" + link_id).remove();
                       console.log(data);
                   },
                   error: function(data){

                   }
                });
            });

        });
    </script>
</body>
</html>