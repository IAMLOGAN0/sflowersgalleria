@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Category</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Create Category</h4>

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.category.store')}}" method="POST" enctype="multipart/form-data"    >
                        @csrf
                        <div class="form-group">
                            <label for="icon-picker">Icon</label>
                            <div>
                                <button type="button" class="btn btn-primary icon-picker-btn" id="icon-picker">
                                    <i class="fas fa-plus me-2"></i>
                                    <span class="selected-icon-text">Select Icon</span>
                                </button>
                                <input type="hidden" name="icon" id="selected-icon" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" class="form-control" name="image" value="">
                        </div>
                        <div class="form-group">
                            <label>Banner</label>
                            <input type="file" class="form-control" name="banner_image" value="">
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="">
                        </div>
                        <div class="form-group">
                            <label for="inputState">Status</label>
                            <select id="inputState" class="form-control" name="status">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submmit" class="btn btn-primary">Create</button>
                    </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>
@endsection
