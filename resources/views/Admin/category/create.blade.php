<div class="page-header">
    <div class="page-title">
        <h4>Category Add</h4>
    </div>
</div>

<form method="POST" id="categoryForm" action="{{route('category.store')}}" enctype="multipart/form-data">
    @csrf
    <div id="categoryContainer">
        <!-- Brand Block -->
        <div class="card brand-block mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="name" class="form-control">
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Category Image</label>
                            <div class="image-upload">
                                <input type="file" name="category_image" class="form-control imageInput" >
                                <div class="image-uploads" id="previewContainer">
                                    <img src="{{ asset('back/img/icons/upload.svg') }}" alt="img" id="defaultIcon">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                            @error('category_image')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <img class="imagePreview" src="#" style="display:none" />
                    </div>
                </div>
              
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <input type="submit" class="btn btn-submit me-2" value="Create">
        <a href="{{route('category.index')}}" class="btn btn-cancel">Cancel</a>
    </div>
</form>