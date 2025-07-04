<script src="{{asset('back/js/jquery-3.6.0.min.js')}}"></script>

<script src="{{asset('back/js/feather.min.js')}}"></script>

<script src="{{asset('back/js/jquery.slimscroll.min.js')}}"></script>

<script src="{{asset('back/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('back/js/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('back/js/bootstrap.bundle.min.js')}}"></script>

<script src="{{asset('back/plugins/apexchart/apexcharts.min.js')}}"></script>
<script src="{{asset('back/plugins/apexchart/chart-data.js')}}"></script>

<script src="{{asset('back/js/script.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/additional-methods.min.js" integrity="sha512-owaCKNpctt4R4oShUTTraMPFKQWG9UdWTtG6GRzBjFV4VypcFi6+M3yc4Jk85s3ioQmkYWJbUl1b2b2r41RTjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script src="{{asset('back/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('back/plugins/sweetalert/sweetalerts.min.js')}}"></script>
@if(!empty($js))
<script src="{{ asset('back/js/admin/' . $js . '.js') }}"></script>
@endif
<script>
    @if(session('success'))
    toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
    toastr.error("{{ session('error') }}");
    @endif

    @if(session('info'))
    toastr.info("{{ session('info') }}");
    @endif

    @if(session('warning'))
    toastr.warning("{{ session('warning') }}");
    @endif
</script>