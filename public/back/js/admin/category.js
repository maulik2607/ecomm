let table = null;
$(document).ready(function () {
    if ($(".data-table").length) {
        table = $(".data-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "/admin/category",
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
        $(".data-table tbody").on("change", "input.row-checkbox", function () {
            if (!this.checked) {
                $("#selectAll").prop("checked", false);
            }
        });
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

    // Form validation stays the same
    $("#brandForm").on("submit", async function (e) {
        e.preventDefault();

        let isValid = true;
        $(".error-msg").remove();

        const ajaxChecks = [];

        $(".brand-block").each(function (index, block) {
            const brandName = $(block).find('input[name="name[]"]');
            const description = $(block).find('textarea[name="description[]"]');
            const imageInput = $(block).find('input[name="product_image[]"]');

            const trimmedName = $.trim(brandName.val());

            // Validate brand name
            if (trimmedName === "") {
                brandName.after(
                    '<small class="text-danger error-msg">Please enter brand name.</small>'
                );
                isValid = false;
            } else if (trimmedName.length > 255) {
                brandName.after(
                    '<small class="text-danger error-msg">Max 255 characters allowed.</small>'
                );
                isValid = false;
            } else {
                // Push uniqueness check promise
                ajaxChecks.push(
                    $.post("/admin/brand/check-brand", {
                        name: trimmedName,
                    }).then((response) => {
                        if (response.exists) {
                            brandName.after(
                                '<small class="text-danger error-msg">Brand name :' +
                                    trimmedName +
                                    " already exists.</small>"
                            );
                            isValid = false;
                        }
                    })
                );
            }

            // Validate description
            const trimmedDesc = $.trim(description.val());
            if (trimmedDesc === "") {
                description.after(
                    '<small class="text-danger error-msg">Please enter description.</small>'
                );
                isValid = false;
            } else if (trimmedDesc.length > 1000) {
                description.after(
                    '<small class="text-danger error-msg">Max 1000 characters allowed in description.</small>'
                );
                isValid = false;
            }

            // Validate image
            if ($.trim(imageInput.val()) === "") {
                imageInput.after(
                    '<small class="text-danger error-msg">Please choose brand logo.</small>'
                );
                isValid = false;
            } else if (imageInput[0].files.length > 0) {
                const file = imageInput[0].files[0];
                const allowedTypes = [
                    "image/jpeg",
                    "image/png",
                    "image/webp",
                    "image/jpg",
                ];

                if (!allowedTypes.includes(file.type)) {
                    imageInput.after(
                        '<small class="text-danger error-msg">Invalid image format. Use jpg, jpeg, png, or webp.</small>'
                    );
                    isValid = false;
                }

                if (file.size > 2 * 1024 * 1024) {
                    imageInput.after(
                        '<small class="text-danger error-msg">Image size must be less than 2MB.</small>'
                    );
                    isValid = false;
                }
            }
        });

        // Wait for all AJAX uniqueness checks to complete
        await Promise.all(ajaxChecks);

        if (isValid) {
            this.submit();
        }
    });
});
