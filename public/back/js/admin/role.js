 var table = null;
 $(document).ready(function () {
        if ($(".data-table").length) {
            table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/admin/role",
                columns: [
                    {
                        data: "id",
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        },
                    },
                   
                    { data: "name", name: "name" },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });

            // Select All checkbox
            $("#selectAll").on("click", function () {
                const rows = table.rows({ search: "applied" }).nodes();
                $('input[type="checkbox"].row-checkbox', rows).prop(
                    "checked",
                    this.checked
                );
            });

            // Uncheck SelectAll if one is unchecked
            $(".data-table tbody").on(
                "change",
                "input.row-checkbox",
                function () {
                    if (!this.checked) {
                        $("#selectAll").prop("checked", false);
                    }
                }
            );
        }

        // Now it's safe to use `table` below

        // Handle Select All
        $("#selectAll").on("click", function () {
            const rows = table.rows({ search: "applied" }).nodes();
            $('input[type="checkbox"].row-checkbox', rows).prop(
                "checked",
                this.checked
            );
        });

        // Handle individual checkbox to uncheck "selectAll" if any unchecked
        $(".data-table tbody").on("change", "input.row-checkbox", function () {
            if (!this.checked) {
                $("#selectAll").prop("checked", false);
            }
        });

        // Optional: Get selected row IDs
        $("#your-button-id").on("click", function () {
            const selectedIds = [];
            $(".row-checkbox:checked").each(function () {
                selectedIds.push($(this).val());
            });

            console.log("Selected IDs: ", selectedIds);
        });

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        
    });