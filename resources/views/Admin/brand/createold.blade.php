<div class="page-header">
    <div class="page-title">
        <h4>Brand ADD</h4>
        <h6>Create new Brand</h6>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <label>Brand Name</label>
                    <input type="text">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control"></textarea>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Product Image</label>
                    <div class="image-upload">
                        <input type="file" id="productImage" accept="image/*">
                        <div class="image-uploads" id="previewContainer">
                            <img src="{{ asset('back/img/icons/upload.svg') }}" alt="img" id="defaultIcon">
                            <h4>Drag and drop a file to upload</h4>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-6">

                <img id="imagePreview" src="#" alt="Preview" style="display: none;" />
            </div>
            <div class="col-lg-12">
                <a href="javascript:void(0);" class="btn btn-submit me-2">Submit</a>
                <a href="brandlist.html" class="btn btn-cancel">Cancel</a>
            </div>
        </div>
    </div>
</div>
