let table = null;

$(function () {
    $(document).ready(function () {
        if ($(".data-table").length) {
            table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/admin/brand",
                columns: [
                    {
                        data: "id",
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        },
                    },
                    {
                        data: "logo",
                        name: "logo",
                        render: function (data, type, full, meta) {
                            if (type === "display") {
                                return `<img src="${data}" width="100"/>`;
                            }
                            return data; // base64 passed as-is
                        },
                    },
                    { data: "name", name: "name" },
                    { data: "description", name: "description" },
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

        // Form validation stays the same
        $("#brandForm").on("submit", async function (e) {
            e.preventDefault();

            let isValid = true;
            $(".error-msg").remove();

            const ajaxChecks = [];

            $(".brand-block").each(function (index, block) {
                const brandName = $(block).find('input[name="name[]"]');
                const description = $(block).find(
                    'textarea[name="description[]"]'
                );
                const imageInput = $(block).find(
                    'input[name="product_image[]"]'
                );

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
                            custom_ajax: true,
                        }).then((response) => {
                            if (response.exists) {
                                brandName.after(
                                    '<small class="text-danger error-msg">Brand name already exists</small>'
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

    async function generatePdf(shouldDownload = true) {
        let selected = [];
        let anyChecked = $(".row-checkbox:checked").length > 0;
        let table = $(".data-table").DataTable();

        for (let rowIndex = 0; rowIndex < table.rows().count(); rowIndex++) {
            let data = table.row(rowIndex).data();
            let node = table.row(rowIndex).node();
            let isChecked = $(node).find(".row-checkbox").is(":checked");

            if (anyChecked && !isChecked) continue;

            selected.push({
                id: data.id,
                name: data.name,
                logoUrl: data.logo,
                description: data.description || "-",
            });
        }

        for (let i = 0; i < selected.length; i++) {
            selected[i].logoBase64 = await getBase64ImageFromUrl(
                selected[i].logoUrl
            );
        }

        const body = [
            [
                { text: "Logo", style: "tableHeader" },
                { text: "Name", style: "tableHeader" },
                { text: "Description", style: "tableHeader" },
            ],
        ];

        selected.forEach((item) => {
            const logoCell = item.logoBase64
                ? {
                      image: item.logoBase64,
                      width: 50,
                      alignment: "center",
                      margin: [0, 5, 0, 5],
                  }
                : { text: "No Image", style: "noImage", alignment: "center" };

            body.push([
                logoCell,
                { text: item.name, style: "boldCell" },
                { text: item.description, style: "tableCell" },
            ]);
        });

        const docDefinition = {
            content: [
                { text: "Brand Catalog", style: "title" },
                {
                    table: {
                        headerRows: 1,
                        widths: [70, "*", "*"],
                        body: body,
                    },
                    layout: {
                        fillColor: (rowIndex) =>
                            rowIndex === 0
                                ? "#1A5276"
                                : rowIndex % 2 === 0
                                ? "#EBF5FB"
                                : null,
                        hLineColor: () => "#aaa",
                        vLineColor: () => "#aaa",
                        paddingLeft: () => 8,
                        paddingRight: () => 8,
                        paddingTop: () => 6,
                        paddingBottom: () => 6,
                    },
                },
            ],
            styles: {
                title: {
                    fontSize: 20,
                    bold: true,
                    color: "#154360",
                    alignment: "center",
                    margin: [0, 0, 0, 20],
                },
                tableHeader: {
                    bold: true,
                    fontSize: 13,
                    color: "white",
                    fillColor: "#1A5276",
                    alignment: "center",
                },
                tableCell: {
                    fontSize: 11,
                    color: "#333",
                },
                boldCell: {
                    fontSize: 12,
                    bold: true,
                    color: "#1A5276",
                },
                noImage: {
                    fontSize: 10,
                    italics: true,
                    color: "#999",
                },
            },
        };

        if (shouldDownload) {
            pdfMake.createPdf(docDefinition).download("brand-list.pdf");
        } else {
            pdfMake.createPdf(docDefinition).print();
        }
    }

    // Bind the events
    $("#pdfDownload").click(() => generatePdf(true));
    $("#printData").click(() => generatePdf(false));

    // EXCEL //

    $("#excelDownload").click(async function () {
        const anyChecked = $(".row-checkbox:checked").length > 0;
        const table = $(".data-table").DataTable();

        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet("Brands");

        worksheet.columns = [
            { header: "ID", key: "id", width: 10 },
            { header: "Name", key: "name", width: 30 },
            { header: "Logo URL", key: "logo", width: 50 },
        ];

        let rowIndex = 2; // Excel rows start at 1, row 1 = header

        table.rows().every(function () {
            const data = this.data();
            const node = this.node();
            const isChecked = $(node).find(".row-checkbox").is(":checked");

            if (!anyChecked || isChecked) {
                // Add row with id and name, leave logo empty for now
                worksheet.addRow({
                    id: data.id,
                    name: data.name,
                    logo: "",
                });

                // Set hyperlink cell
                const cell = worksheet.getCell(`C${rowIndex}`); // C = 3rd column = logo column
                if (data.logo) {
                    cell.value = {
                        text: data.logo,
                        hyperlink: data.logo,
                    };
                    cell.font = {
                        color: { argb: "FF0000FF" },
                        underline: true,
                    }; // blue & underlined
                } else {
                    cell.value = "No Image URL";
                }

                rowIndex++;
            }
        });

        const buffer = await workbook.xlsx.writeBuffer();
        saveAs(new Blob([buffer]), "brands-with-clickable-urls.xlsx");
    });
    // EXCEL //

    async function getBase64ImageFromUrl(imageUrl) {
        if (!imageUrl) return null;
        try {
            const response = await fetch(imageUrl);
            const blob = await response.blob();

            return await new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            });
        } catch (err) {
            console.error("Error fetching image:", err);
            return null;
        }
    }

    $("#updateBrandForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 255,
                remote: {
                    url: "/admin/brand/check-brand",
                    type: "post",
                    data: {
                        name: function () {
                            return $("input[name='name']").val();
                        },
                        id: function () {
                            return $("input[name='id']").val(); // Current brand ID
                        },
                        _token: $('meta[name="csrf-token"]').attr("content"), // <-- get token dynamically
                    },
                },
            },
            description: {
                maxlength: 1000, // Optional: limit characters in description
            },
            // product_image: {
            //     extension: "jpg|jpeg|png|gif|svg",
            //     filesize: 2097152 // 2MB in bytes
            // }
        },
        messages: {
            name: {
                required: "Brand name is required",
                maxlength: "Brand name must not exceed 255 characters",
                remote: "Brand name already exists",
            },
            description: {
                maxlength: "Description must not exceed 1000 characters",
            },
            // product_image: {
            //     extension: "Only image files are allowed (jpg, jpeg, png, gif, svg)",
            //     filesize: "Image size must be less than 2MB"
            // }
        },
    });

    // Custom method for file size
    $.validator.addMethod(
        "filesize",
        function (value, element, param) {
            return this.optional(element) || element.files[0].size <= param;
        },
        "File size must be less than {0}"
    );
}); // document end
