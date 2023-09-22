$(document).ready(function () {
    let prevComment;
    let prevCommentText;
    $('.comment-actions__edit').click(function () {
        if (prevComment) {
            prevComment.val(prevCommentText);
        }
        let commentContainer = $(this).closest('.comment');
        let commentTextContainer = commentContainer.find('.comment-info__text');
        prevComment = commentTextContainer;
        prevCommentText = commentTextContainer.val();
        //Remove editable from all comments, so that only one comment at a time is able to be edited
        $('.comment-info__text').removeClass('comment_editable');
        $('.comment-info__text').prop('readonly', function (_, value) {
            return true;
        });
        if (!commentTextContainer.hasClass('comment_editable')) {
            commentTextContainer.toggleClass('comment_editable');
            commentTextContainer.prop('readonly', function (_, value) {
                return !value; // Toggle the value (true to false, or false to true)
            });
        }

    })
});
