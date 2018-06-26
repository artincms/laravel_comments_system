<div class="modal fade" id="create_modal_show_comment" tabindex="-1" role="dialog" aria-labelledby="showComment" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="comments">
                    <laravel_comments_system :target_model_name="'App\\Article'" :target_id="1" :target_parent_column_name="'parent_id'" :user-id="{{LCS_getUserId()}}" ></laravel_comments_system>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModalForReplyComment" tabindex="-1" role="dialog" aria-labelledby="showComment" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="titleReplyComment"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="modalIframeShowReplyComment" src=""></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitReplyComment">Save</button>
                <button type="button" class="btn btn-primary" id="submitReplyCommentClose">Save && close</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>