$(document).ready(function () {
    // Initialize DataTable
    $('#ingredientTable').DataTable({
        ajax: {
            url: "/api/ingredients",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add Ingredient',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#ingredientForm").trigger("reset");
                    $('#ingredientModal').modal('show');
                    $('#ingredientUpdate').hide();
                    $('#ingredientImage').remove();
                }
            }
        ],
        columns: [
            { data: 'id' },
            {
                data: null,
                render: function (data, type, row) {
                    return `<img src=${data.image_path} width="50" height="60">`;
                }
            },
            { data: 'name' },
            { data: 'unit' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a href='#' class='editBtn' data-id=${data.id}><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a>
                        <a href='#' class='deleteBtn' data-id=${data.id}><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></a>
                    `;
                }
            }
        ],
    });

    // Submit new ingredient
    $("#ingredientSubmit").on('click', function (e) {
        e.preventDefault();
        var data = $('#ingredientForm')[0];
        let formData = new FormData(data);
        $.ajax({
            type: "POST",
            url: "/api/ingredients",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $("#ingredientModal").modal("hide");
                var $ingredientTable = $('#ingredientTable').DataTable();
                $ingredientTable.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Edit ingredient
    $('#ingredientTable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#ingredientImage').remove();
        $('#ingredientId').remove();
        $("#ingredientForm").trigger("reset");
        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'ingredientId', name: 'id', value: id }).appendTo('#ingredientForm');
        $('#ingredientModal').modal('show');
        $('#ingredientSubmit').hide();
        $('#ingredientUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/ingredients/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#name').val(data.name);
                $('#unit').val(data.unit);
                $("#ingredientForm").append(`<img src="${data.img_path}" width='200px' height='200px' id="ingredientImage" />`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Update ingredient
    $("#ingredientUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#ingredientId').val();
        var table = $('#ingredientTable').DataTable();
        var data = $('#ingredientForm')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `/api/ingredients/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#ingredientModal').modal("hide");
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Delete ingredient
    $('#ingredientTable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
        var table = $('#ingredientTable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        bootbox.confirm({
            message: "Do you want to delete this ingredient?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/ingredients/${id}`,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            $row.fadeOut(4000, function () {
                                table.row($row).remove().draw();
                            });
                            bootbox.alert(data.success);
                        },
                        error: function (error) {
                            console.log(error);
                            bootbox.alert('Error deleting ingredient.');
                        }
                    });
                }
            }
        });
    });
});
