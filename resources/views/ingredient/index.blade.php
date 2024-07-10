@extends('layouts.main')
@section('content')
    <div id="items" class="container">
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#ingredientmodal">add<span
            class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
        {{-- @include('layouts.flash-messages') --}}
        {{-- <a class="btn btn-primary" href="{{ route('items.create') }}" role="button">add</a> --}}
        {{-- <form method="POST" enctype="multipart/form-data" action="{{ route('item-import') }}">
            {{ csrf_field() }}
            <input type="file" id="ingredientName" name="ingredientUpload" required>
            <button type="submit" class="btn btn-info btn-primary ">Import Excel File</button>

        </form> --}}
        <div class="card-body" style="height: 210px;">
            <input type="text" id='itemSearch' placeholder="--search--">
        </div>
        <div class="table-responsive">
            <table id="ingredientTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Ingredients ID</th>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>Image</th>
                        
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ingredientBody"></tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="ingredientModal" role="dialog" style="display:none">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create new ingredient</h4>
              <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="ingredientForm" method="POST" action="#" enctype="multipart/form-data">
      
                  <div class="form-group">
                      <label for="ingredientId" class="control-label">Ingredient ID</label>
                      <input type="text" class="form-control" id="ingredientId" name="id" readonly>
                    </div>
                
                <div class="form-group">
                  <label for="name" class="control-label">Name</label>
                  <input type="text" class="form-control " id="name" name="name">
                </div>
                <div class="form-group">
                  <label for="unit" class="control-label">Unit</label>
                  <input type="text" class="form-control " id="unit" name="unit">
                </div>
               
                <div class="form-group">
                  <label for="image" class="control-label">Image</label>
                  <input type="file" class="form-control" id="img" name="image" />
                </div>
              </form>
            </div>
            <div class="modal-footer" id="footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button id="ingredientSubmit" type="submit" class="btn btn-primary">Save</button>
              <button id="ingredientUpdate" type="submit" class="btn btn-primary">update</button>
            </div>
      
          </div>
        </div>
      </div>
@endsection
