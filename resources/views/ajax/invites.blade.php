<div class="modal-content">
    <div class="modal-header border-0">
        <div class="modal-title">
            <h4 id="exampleModalLabel">Invite to Textnext</h4>

        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
        </button>
    </div>
    <div class="modal-body pt-0">
        <h5>Share invite link</h5>
        <p>Anyone with this link can join as a member.</p>
        <div class="input-group">
            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)"
                value="{{ $url }}" id="copy-url-input">
            <div class="input-group-append" style="cursor: pointer">
                <span class="input-group-text text-success" id="copy-url-btn">Copy</span>
            </div>
        </div>

        <div class="d-flex line_divider justify-content-center mt-4 w-100">
            <hr class="width-set-100"><span class="width-set-100 d-block text-center">Or invite manually</span>
            <hr class="width-set-100">
        </div>
        <form>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                <label for="floatingTextarea">Enter emails</label>
                <div id="emailHelp" class="form-text">Use commas to separate addresses</div>
                <span class="text-danger fw-bold" id="email-errors"></span>
            </div>
        </form>
    </div>
    <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="btn-invites" disabled>Create</button>
    </div>
</div>
