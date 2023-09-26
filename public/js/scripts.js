$(document).ready(function () {
    // Create submit button for comment
    let submitButtonContainer = $('<div>').addClass('comment__submit-container');
    let submitButton = $('<button>').attr('type', 'button').addClass('block py-2 px-3 bg-indigo-600 text-white rounded comment__submit-button').text('Submit');
    submitButtonContainer.append(submitButton);
    // Variables for saving data of previous comment that was being edited
    let prevCommentId;
    let prevCommentTextContainer;
    let prevCommentText;

    $('.comment-actions__edit').click(function () {
        let commentContainer = $(this).closest('.comment');
        let commentTextContainer = commentContainer.find('.comment-info__text');
        let commentText = commentTextContainer.val();
        //Remove editable from previous comments, so that only one comment at a time is able to be edited
        if (prevCommentId && prevCommentId !== commentContainer[0].id) {
            prevCommentTextContainer.val(prevCommentText);
            prevCommentTextContainer.removeClass('comment_editable');
            prevCommentTextContainer.prop('readonly', function (_, value) {
                return true;
            });
        }
        if (!commentTextContainer.hasClass('comment_editable')) {
            // Add editable
            commentTextContainer.addClass('comment_editable');
            commentTextContainer.prop('readonly', false);
            commentTextContainer.after(submitButtonContainer);
        } else {
            // Remove editable
            commentTextContainer.removeClass('comment_editable');
            commentTextContainer.prop('readonly', true);
            commentContainer.find('.comment__submit-container').remove();
        }
        // Save comment that was being edited
        prevCommentId = commentContainer[0].id;
        prevCommentTextContainer = commentTextContainer;
        prevCommentText = commentText;
        commentContainer.find('.comment__submit-button').click(function () {
            if (commentTextContainer.val().trim()) {
                let commentId = commentContainer.attr('id');
                $.ajax({
                    type: 'POST',
                    url: `/editComment/${commentId}`,
                    data: {id: commentId, comment_text: commentTextContainer.val(), token: $("input[name='token']").val()},
                    success: function () {
                        location.reload();
                    },
                    error: function (error) {
                        console.error('Error editing comment: ' + error);
                    }
                });
            }
        })
    })
});