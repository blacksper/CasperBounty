/**
 * Created by Егор on 27.07.2017.
 */

$(document).on("click", ".testjs", function () {
    var toolId = $(this).data('id');
    console.log(toolId);
    $(".toolId").val( toolId );

    // As pointed out in comments,
    // it is superfluous to have to manually call the modal.
    // $('#addBookDialog').modal('show');
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});