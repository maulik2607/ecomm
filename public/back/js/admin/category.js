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
             createdRow: function (row, data, dataIndex) {
        $('td', row).eq(1).addClass('productimgname'); // Adds class to first column td
    }
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


       $("#categoryForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 255,
                    remote: {
                    url: "/admin/category/check-category",
                    type: "post",
                    data: {
                        name: function () {
                            return $("input[name='name']").val();
                        },
                        id: function () {
                            return $("input[name='id']").val(); // Current brand ID
                        },
                       _token: $('meta[name="csrf-token"]').attr("content") // <-- get token dynamically
                    }
                }
            }
         
            // product_image: {
            //     extension: "jpg|jpeg|png|gif|svg",
            //     filesize: 2097152 }
        },
        messages: {
            name: {
                required: "Category name is required",
                maxlength: "Category name must not exceed 255 characters",
                remote: "Category name already exist"
            }
            // product_image: {
            //     extension: "Only image files are allowed (jpg, jpeg, png, gif, svg)",
            //     filesize: "Image size must be less than 2MB"
            // }
        }
    });
  

   

    // Custom method for file size
    $.validator.addMethod("filesize", function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, "File size must be less than {0}");


      //  PDF //

// $("#pdfDownload").click(async function () {
//     let table = $(".data-table").DataTable();
//     let selected = [];
//     let anyChecked = $(".row-checkbox:checked").length > 0;

//     for (let rowIndex = 0; rowIndex < table.rows().count(); rowIndex++) {
//         let data = table.row(rowIndex).data();
//         let node = table.row(rowIndex).node();
//         let isChecked = $(node).find(".row-checkbox").is(":checked");

//         if (anyChecked && !isChecked) continue;

//         // Extract image and name from HTML in `name` column
//         let $nameCell = $("<div>").html(data.name);
//         let imageUrl = $nameCell.find("img").attr("src");
//         let nameText = $nameCell.text().trim();

//         selected.push({
//             name: nameText,
//             imageUrl: imageUrl
//         });
//     }

//     for (let i = 0; i < selected.length; i++) {
//         selected[i].imageBase64 = await getBase64ImageFromUrl(selected[i].imageUrl);
//     }

//     const body = [
//         [
//             { text: "Category", style: "tableHeader" },
//         ],
//     ];

//     selected.forEach((item) => {
//         const cell = {
//             columns: [
//                 {
//                     image: item.imageBase64,
//                     width: 30,
//                 },
//                 {
//                     text: item.name,
//                     margin: [10, 8, 0, 0],
//                     style: "boldCell",
//                 },
//             ]
//         };

//         body.push([cell]);
//     });

//     const docDefinition = {
//         content: [
//             { text: "Category List", style: "title" },
//             {
//                 table: {
//                     headerRows: 1,
//                     widths: ["*"],
//                     body: body,
//                 },
//                 layout: {
//                     fillColor: (rowIndex) =>
//                         rowIndex === 0 ? "#1A5276" :
//                         rowIndex % 2 === 0 ? "#EBF5FB" : null,
//                     hLineColor: () => "#aaa",
//                     vLineColor: () => "#aaa",
//                     paddingLeft: () => 8,
//                     paddingRight: () => 8,
//                     paddingTop: () => 6,
//                     paddingBottom: () => 6,
//                 },
//             },
//         ],
//         styles: {
//             title: {
//                 fontSize: 20,
//                 bold: true,
//                 color: "#154360",
//                 alignment: "center",
//                 margin: [0, 0, 0, 20],
//             },
//             tableHeader: {
//                 bold: true,
//                 fontSize: 13,
//                 color: "white",
//                 fillColor: "#1A5276",
//                 alignment: "center",
//             },
//             boldCell: {
//                 fontSize: 12,
//                 bold: true,
//                 color: "#1A5276",
//             },
//         },
//     };

//     pdfMake.createPdf(docDefinition).download("category-list.pdf");
// });
async function generateCategoryPdf(shouldDownload = true) {
    let table = $(".data-table").DataTable();
    let selected = [];
    let anyChecked = $(".row-checkbox:checked").length > 0;

    for (let rowIndex = 0; rowIndex < table.rows().count(); rowIndex++) {
        let data = table.row(rowIndex).data();
        let node = table.row(rowIndex).node();
        let isChecked = $(node).find(".row-checkbox").is(":checked");

        if (anyChecked && !isChecked) continue;

        // Extract image and name from HTML
        let $nameCell = $("<div>").html(data.name);
        let imageUrl = $nameCell.find("img").attr("src");
        let nameText = $nameCell.text().trim();

        selected.push({
            name: nameText,
            imageUrl: imageUrl,
        });
    }

    // Convert image URLs to base64
    for (let i = 0; i < selected.length; i++) {
        selected[i].imageBase64 = await getBase64ImageFromUrl(selected[i].imageUrl);
    }

    const body = [
        [{ text: "Category", style: "tableHeader" }],
    ];

    selected.forEach((item) => {
        const cell = {
            columns: [
                {
                    image: item.imageBase64,
                    width: 30,
                },
                {
                    text: item.name,
                    margin: [10, 8, 0, 0],
                    style: "boldCell",
                },
            ]
        };
        body.push([cell]);
    });

    const docDefinition = {
        content: [
            { text: "Category List", style: "title" },
            {
                table: {
                    headerRows: 1,
                    widths: ["*"],
                    body: body,
                },
                layout: {
                    fillColor: (rowIndex) =>
                        rowIndex === 0 ? "#1A5276" :
                        rowIndex % 2 === 0 ? "#EBF5FB" : null,
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
            boldCell: {
                fontSize: 12,
                bold: true,
                color: "#1A5276",
            },
        },
    };

    if (shouldDownload) {
        pdfMake.createPdf(docDefinition).download("category-list.pdf");
    } else {
        pdfMake.createPdf(docDefinition).print();
    }
}

// Bind the buttons
$("#pdfDownload").click(() => generateCategoryPdf(true));
$("#printData").click(() => generateCategoryPdf(false));


    //  PDF //

    // EXCEL //

 $("#excelDownload").click(async function () {
    const anyChecked = $(".row-checkbox:checked").length > 0;
    const table = $(".data-table").DataTable();

    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet("Categories");

    worksheet.columns = [
        { header: "ID", key: "id", width: 10 },
        { header: "Category Name", key: "name", width: 30 },
        { header: "Image URL", key: "image", width: 50 },
    ];

    let rowIndex = 2;

    table.rows().every(function () {
        const data = this.data();
        const node = this.node();
        const isChecked = $(node).find(".row-checkbox").is(":checked");

        if (!anyChecked || isChecked) {
            // Extract image src and name from HTML
            const $nameCell = $("<div>").html(data.name);
            const imageUrl = $nameCell.find("img").attr("src") || "No Image";
            const nameText = $nameCell.text().trim();

            worksheet.addRow({
                id: data.id,
                name: nameText,
                image: "",
            });

            // Add hyperlink to image URL
            const cell = worksheet.getCell(`C${rowIndex}`);
            if (imageUrl !== "No Image") {
                cell.value = {
                    text: imageUrl,
                    hyperlink: imageUrl,
                };
                cell.font = {
                    color: { argb: "FF0000FF" },
                    underline: true,
                };
            } else {
                cell.value = "No Image";
            }

            rowIndex++;
        }
    });

    const buffer = await workbook.xlsx.writeBuffer();
    saveAs(new Blob([buffer]), "category-list.xlsx");
});
    // EXCEL //

    // PRINT //
    // $("#printData").click(async function () {
    //     let selected = [];
    //     let anyChecked = $(".row-checkbox:checked").length > 0;
    //     let table = $(".data-table").DataTable();

    //     // Collect row data
    //     for (let rowIndex = 0; rowIndex < table.rows().count(); rowIndex++) {
    //         let data = table.row(rowIndex).data();
    //         let node = table.row(rowIndex).node();
    //         let isChecked = $(node).find(".row-checkbox").is(":checked");

    //         if (anyChecked && !isChecked) continue;

    //         selected.push({
    //             id: data.id,
    //             name: data.name,
    //             logoUrl: data.logo, // This is the image URL from backend
    //             description: data.description || "-",
    //         });
    //     }

    //     // Convert all logos to base64 before building PDF body
    //     for (let i = 0; i < selected.length; i++) {
    //         selected[i].logoBase64 = await getBase64ImageFromUrl(
    //             selected[i].logoUrl
    //         );
    //     }

    //     // Prepare PDF table body
    //     const body = [
    //         [
    //             { text: "Logo", style: "tableHeader" },
    //             { text: "Name", style: "tableHeader" },
    //             { text: "Description", style: "tableHeader" },
    //         ],
    //     ];

    //     selected.forEach((item) => {
    //         const logoCell = item.logoBase64
    //             ? {
    //                   image: item.logoBase64,
    //                   width: 50,
    //                   alignment: "center",
    //                   margin: [0, 5, 0, 5],
    //               }
    //             : {
    //                   text: "No Image",
    //                   style: "noImage",
    //                   alignment: "center",
    //               };

    //         body.push([
    //             logoCell,
    //             { text: item.name, style: "boldCell" },
    //             { text: item.description, style: "tableCell" },
    //         ]);
    //     });

    //     // Create PDF doc
    //     const docDefinition = {
    //         content: [
    //             { text: "Brand Catalog", style: "title" },
    //             {
    //                 table: {
    //                     headerRows: 1,
    //                     widths: [70, "*", "*"],
    //                     body: body,
    //                 },
    //                 layout: {
    //                     fillColor: (rowIndex) =>
    //                         rowIndex === 0
    //                             ? "#1A5276"
    //                             : rowIndex % 2 === 0
    //                             ? "#EBF5FB"
    //                             : null,
    //                     hLineColor: () => "#aaa",
    //                     vLineColor: () => "#aaa",
    //                     paddingLeft: () => 8,
    //                     paddingRight: () => 8,
    //                     paddingTop: () => 6,
    //                     paddingBottom: () => 6,
    //                 },
    //             },
    //         ],
    //         styles: {
    //             title: {
    //                 fontSize: 20,
    //                 bold: true,
    //                 color: "#154360",
    //                 alignment: "center",
    //                 margin: [0, 0, 0, 20],
    //             },
    //             tableHeader: {
    //                 bold: true,
    //                 fontSize: 13,
    //                 color: "white",
    //                 fillColor: "#1A5276",
    //                 alignment: "center",
    //             },
    //             tableCell: {
    //                 fontSize: 11,
    //                 color: "#333",
    //             },
    //             boldCell: {
    //                 fontSize: 12,
    //                 bold: true,
    //                 color: "#1A5276",
    //             },
    //             noImage: {
    //                 fontSize: 10,
    //                 italics: true,
    //                 color: "#999",
    //             },
    //         },
    //     };

    //     // Open print dialog instead of downloading
    //     pdfMake.createPdf(docDefinition).print();
    // });
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
});
