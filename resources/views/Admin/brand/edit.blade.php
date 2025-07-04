<div class="page-header">
    <div class="page-title">
        <h4>Brand Update</h4>
      
    </div>
</div>

<form method="POST" id="updateBrandForm" action="{{route('brand.update',$id)}}" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $id }}">

      @csrf
    @method('PUT')
    <div id="brandContainer">
        <!-- Brand Block -->
        <div class="card brand-block mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Brand Name</label>
                            <input type="text" name="name" class="form-control" value="{{$brand->name}}">
                            @error('name.*')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="2">{{$brand->description}}</textarea>
                            @error('description.*')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Product Image</label>
                           <div class="image-upload">
                                <input type="file" name="product_image" class="form-control imageInput" >
                                <div class="image-uploads" id="previewContainer">
                                    <img src="{{ asset('back/img/icons/upload.svg') }}" alt="img" id="defaultIcon">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                            @error('product_image.*')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <img class="imagePreview" src="{{ asset($brand->logo)}}"  />
                    </div>
                </div>
             
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <input type="submit" class="btn btn-submit me-2" value="Update">
        <a href="{{route('brand.index')}}" class="btn btn-cancel">Cancel</a>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
            $(document).on('change', '.imageInput', function() {
              
            var input = this;
            var preview = $(this).closest('.row').find('.imagePreview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
</script>