<div class="page-header">
    <div class="page-title">
        <h4>Role Manage List</h4>
        <h6>Manage your Role</h6>
    </div>
    <div class="page-btn">
        <!-- <a href="{{ route('role_manange.create') }}" class="btn btn-added"> -->
            <img src="{{ asset('back/img/icons/plus.svg') }}" class="me-2" alt="img" />
            Add Role
        </a>
      
    </div>
</div>

<div class="card">
    <div class="card-body">
                <div class="wordset">
                  <ul>
                    <li>
                      <a
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="pdf"
                        id="pdfDownload"
                        ><img src="{{asset('back/img/icons/pdf.svg')}}" alt="img"
                        /></a>
                    </li>
                    <li>
                        <a
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="excel"
                        id="excelDownload"
                        ><img src="{{asset('back/img/icons/excel.svg')}}" alt="img"
                      /></a>
                    </li>
                    <li>
                      <a
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="print"
                        id="printData"
                        ><img src="{{asset('back/img/icons/printer.svg')}}" alt="img"
                      /></a>
                    </li>
                  </ul>
                </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                         <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>