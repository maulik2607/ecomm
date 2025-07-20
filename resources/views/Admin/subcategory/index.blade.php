<div class="page-header">
  <div class="page-title">
    <h4>SubCategory list</h4>

  </div>
  <div class="page-btn">
    <a href="{{route('subcategory.create')}}" class="btn btn-added">
      <img src="{{asset('back/img/icons/plus.svg')}}" class="me-1" alt="img">Add SubCategory
    </a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="wordset">
      <ul>
        <li>
          <a href="{{ route('categories.template') }}" class="btn btn-primary">
            Download Category Template
          </a>
        </li>
        <li>
          <a
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="pdf"
            id="pdfDownload"><img src="{{asset('back/img/icons/pdf.svg')}}" alt="img" /></a>
        </li>
        <li>
          <a
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="excel"
            id="excelDownload"><img src="{{asset('back/img/icons/excel.svg')}}" alt="img" /></a>
        </li>
        <li>
          <a
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="print"
            id="printData"><img src="{{asset('back/img/icons/printer.svg')}}" alt="img" /></a>
        </li>
      </ul>
    </div>
    <div class="table-responsive">
      <table class="data-table table table-bordered">
        <thead>
          <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>Name</th>
            <th>Main Category</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>