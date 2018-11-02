var formCommentId = null;

$(document).ready(
    function()
    {
        $("#commentDialog").dialog(
            {
                autoOpen: false,
                resizable: true,
                modal: true,
                buttons: [
                    {
                        text: "Save",
                        icon: "ui-icon-save",
                        click: function()
                        {
                            if (!formCommentId) {
                                SaveNewComment();
                            } else {
                                SaveEditComment(formCommentId);
                            }
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "Cancel",
                        icon: "ui-icon-cancel",
                        click: function()
                        {
                            $( this ).dialog( "close" );
                        }
                    },
                ]
            }
        );

        $("#addComment").click(function()
        {
            formCommentId = null;
            console.log("addComment");
            $("#taComment").val("");
            $("#commentDialog")
                .dialog(
                    {
                        title: "New Comment"
                    }
                )
                .dialog("open");
            return false;
        });
    }
);

function SaveNewComment()
{
    $.ajax(
        {
            url: $("#commentSaveURL").val(),
            dataType: "json",
            type: 'post',
            data: {
                "comment": $("#taComment").val(),
            },

            success: function(msg)
            {
                if (0 == msg.result) {
                    location.reload();
                }
            },
            error: function (msg)
            {
                alert('Ошибка: ' + msg.status + ', ' + msg.statusText);
            }
    });
    return false;

}

function DeleteComment(commentId)
{
    console.log("DeleteComment");
    if (!confirm('Are you shure?')) {
        return false;
    }
    $.ajax(
        {
            url: $("#commentDeleteURL" + commentId).val(),
            dataType: "json",
            type: 'delete',
            data: {
                "id": commentId,
            },

            success: function(msg)
            {
                if (0 == msg.result) {
                    location.reload();
                }
            },
            error: function (msg)
            {
                alert('Ошибка: ' + msg.status + ', ' + msg.statusText);
            }
        });
    return false;
}

function EditComment(commentId)
{
    formCommentId = commentId;
    $("#taComment").val($("#comment" + commentId).html());
    $("#commentDialog")
        .dialog(
            {
                title: "Edit Comment"
            }
        )
        .dialog("open");
}

function SaveEditComment(commentId)
{
    console.log("SaveEditComment(" + commentId + ")");

    $.ajax(
        {
            url: $("#commentEditURL" + commentId).val(),
            dataType: "json",
            type: 'post',
            data: {
                "id": commentId,
                "comment": $("#taComment").val(),
            },

            success: function(msg)
            {
                if (0 == msg.result) {
                    location.reload();
                }
            },
            error: function (msg)
            {
                alert('Ошибка: ' + msg.status + ', ' + msg.statusText);
            }
        });
    return false;

    return false;
}