<div class="page-header">
    <div class="page-title">
        <h4>Subcategory Update</h4>
      
    </div>
</div>

<form method="POST" id="subcategoryForm" action="{{route('subcategory.update',$id)}}">
    <input type="hidden" name="id" value="{{ $id }}">

      @csrf
    @method('PUT')
    <div id="subcategoryContainer">
        <!-- Brand Block -->
        <div class="card brand-block mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label>Category</label>
                            <h6>Choose Category</h6>
                            <select name="parent_id" id="parent_id">
                                <option value="">Select Category</option>
                                @foreach($category as $catval)
                                <option value="{{$catval->id}}">{{$catval->name}}</option>
                                @endforeach
                            </select>
                            <input type="text" name="name" class="form-control" value="{{$subcategory->name}}">
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Subcategory Name</label>
                            <input type="text" name="name" class="form-control" value="{{$subcategory->name}}">
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                  
                 
                </div>
             
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <input type="submit" class="btn btn-submit me-2" value="Update">
        <a href="{{route('subcategory.index')}}" class="btn btn-cancel">Cancel</a>
    </div>
</form>
