<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredients</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
</head>

<body>
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-12">
                <h2>List of Ingredients</h2>
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="text-light"> Manage Ingredients </h3>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addIngredientModal">  
                            <i class="bi-plus-circle me-2"></i> Add New Ingredient
                        </button>
                    </div>
                    <div class="card-body" id="show_all_ingredient">
                        <h1 class="text-center text-secondary my-5">Loading...</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- New Ingredient Modal --}}
    <div class="modal fade" id="addIngredientModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('store') }}" method="POST" id="add_ingredient_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="name">Ingredient</label>
                                <input type="text" name="name" class="form-control" placeholder="Ingredient Name" required>
                            </div>
                            <div class="col-lg">
                                <label for="unit">Unit</label>
                                <input type="text" name="unit" class="form-control" placeholder="Unit" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="image_path">Select Image</label>
                            <input type="file" name="image_path" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="add_ingredient_btn" class="btn btn-primary">Add Ingredient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Ingredient Modal --}}
    <div class="modal fade" id="editIngredientModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update') }}" method="POST" id="edit_ingredient_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ing_id" id="ing_id">
                    <input type="hidden" name="ing_image_path" id="ing_image_path">
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="name">Ingredient</label>
                                <input type="text" name="name" id="edit_name" class="form-control" placeholder="Ingredient Name" required>
                            </div>
                            <div class="col-lg">
                                <label for="unit">Unit</label>
                                <input type="text" name="unit" id="edit_unit" class="form-control" placeholder="Unit" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="image_path">Select Image</label>
                            <input type="file" name="image_path" class="form-control">
                            <img id="current_image" src="" alt="" width="100" class="img-fluid img-thumbnail mt-2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="edit_ingredient_btn" class="btn btn-success">Update Ingredient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script>
      $(function() {
    // Add new ingredient ajax request
    $("#add_ingredient_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_ingredient_btn").text('Adding...');
        $.ajax({
            url: '{{ route('store') }}',
            method: 'POST',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if(response.status == 200){
                    Swal.fire(
                        'Added',
                        'Ingredient Added Successfully',
                        'success'
                    )
                    fetchAllIngredients();
                }
                $("#add_ingredient_btn").text('Add Ingredient');
                $("#add_ingredient_form")[0].reset();
                $("#addIngredientModal").modal('hide');
            },
            error: function(response) {
                Swal.fire(
                    'Error',
                    'There was an error adding the ingredient',
                    'error'
                )
                $("#add_ingredient_btn").text('Add Ingredient');
            }
        });
    });

  // Edit ingredient modal show
  $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
          url: '{{ route('update') }}',
          method: 'get',
          data: {
            id: id,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            $("#name").val(response.name);
            $("#unit").val(response.unit);
            $("#image_path").html(
              `<img src="storage/public/profile_images/${response.image_path}" width="100" class="img-fluid img-thumbnail">`);
            $("#ing_id").val(response.id);
            $("#ing_image_path").val(response.image_path);
          }
        });
      });

// Update ingredient ajax request
$("#edit_ingredient_form").submit(function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    $("#edit_ingredient_btn").text('Updating...');
    $.ajax({
        url: '{{ route('update') }}',
        method: 'post',
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.status == 200) {
                Swal.fire(
                    'Updated!',
                    'Ingredient Updated Successfully!',
                    'success'
                );
                fetchAllIngredients();
            }
            $("#edit_ingredient_btn").text('Update Ingredient');
            $("#edit_ingredient_form")[0].reset();
            $("#editIngredientModal").modal('hide');
        },
        error: function(response) {
            Swal.fire(
                'Error',
                'There was an error updating the ingredient',
                'error'
            );
            $("#edit_ingredient_btn").text('Update Ingredient');
        }
    });
});

  // delete ingredients ajax request
  $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '{{ route('delete') }}',
              method: 'delete',
              data: {
                id: id,
                _token: csrf
              },
              success: function(response) {
                console.log(response);
                Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
                fetchAllIngredients();
              }
            });
          }
        })
      });
    // Fetch all ingredients ajax request
    fetchAllIngredients();
 
 function fetchAllIngredients() {
   $.ajax({
     url: '{{ route('fetchAll') }}',
     method: 'get',
     success: function(response) {
       $("#show_all_ingredient").html(response);
       $("table").DataTable({
         order: [0, 'desc']
            });
        }
        });
    }
});

    </script>
</body>
</html>
