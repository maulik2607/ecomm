<div class="page-header">
    <div class="page-title">
        <h4>Brand ADD</h4>
        <h6>Create multiple Brands</h6>
    </div>
</div>

<form method="POST" id="brandForm" action="{{route('brand.store')}}" enctype="multipart/form-data">
    @csrf
    <div id="brandContainer">
        <!-- Brand Block -->
        <div class="card brand-block mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Brand Name</label>
                            <input type="text" name="name[]" class="form-control">
                            @error('name.*')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description[]" rows="2"></textarea>
                            @error('description.*')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Product Image</label>
                            <div class="image-upload">
                                <input type="file" name="product_image[]" class="form-control imageInput" accept="image/*">
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

                        <img class="imagePreview" src="#" style="display:none" />
                    </div>
                </div>
                <button type="button" class="btn btn-danger removeBrand mt-3">Remove</button>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <button type="button" id="addBrand" class="btn btn-primary">+ Add More</button>
    </div>

    <div class="col-lg-12">
        <input type="submit" class="btn btn-submit me-2" value="Create">
        <a href="{{route('brand.index')}}" class="btn btn-cancel">Cancel</a>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Add new brand block
        $('#addBrand').click(function() {
            var newBlock = $('.brand-block').first().clone();
            newBlock.find('input, textarea').val('');
            newBlock.find('.text-danger').remove();
            newBlock.find('.imagePreview').attr('src', '#').hide();
            $('#brandContainer').append(newBlock);
        });

        // Remove brand block
        $(document).on('click', '.removeBrand', function() {
            if ($('.brand-block').length > 1) {
                $(this).closest('.brand-block').remove();
            } else {

                toastr.options = {
                    "positionClass": "toast-top-center"
                };
                toastr.warning("At least one brand is required.");
            }
        });

        // Image preview handler
      
        // Handle form submit (AJAX optional)
        // $('#brandForm').on('submit', function (e) {
        //     e.preventDefault();
        //     // You can send this form data via AJAX here
        //     alert('Form Submitted');
        // });
    });




    // jQuery Validation
</script>