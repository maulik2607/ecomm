<div class="page-header">
    <div class="page-title">
        <h4>Subcategory Add</h4>
    </div>
</div>


<form method="POST" id="subcategoryForm" action="{{route('subcategory.store')}}">
    @csrf
    <div id="subcategoryContainer">
       
        <div class="card brand-block mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                            <label>Category</label>
                            <span>Choose category</span>
                            <select class="form-control" name="parent_id" id="parent_id">
                                <option value="">Select Category</option>
                                @foreach($allCategories as $catval)
                                <option value="{{$catval->id}}">{{$catval->name}}</option>
                                @endforeach
                            </select>
                            
                            @error('parent_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Subcategory Name</label>
                            <input type="text" name="name" class="form-control">
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
        <input type="submit" class="btn btn-submit me-2" value="Create">
        <a href="{{route('subcategory.index')}}" class="btn btn-cancel">Cancel</a>
    </div>

</form>