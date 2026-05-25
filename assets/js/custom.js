let csrf = document.querySelector("meta[name='csrf']").content;

/* quill init  */
const quillToollbar = [
    ["bold", "italic", "underline", "blockquote"],
    [
        {
            list: "ordered",
        },
        {
            list: "bullet",
        },
    ],
    [
        {
            indent: "-1",
        },
        {
            indent: "+1",
        },
    ],
    [
        {
            direction: "rtl",
        },
    ],

    [
        {
            size: ["small", false, "large", "huge"],
        },
    ],
    [
        {
            header: [1, 2, 3, 4, 5, 6, false],
        },
    ],

    [
        {
            color: [],
        },
        {
            background: [],
        },
    ],
    [
        {
            font: [],
        },
    ],
    [
        {
            align: [],
        },
    ],

    ["clean"],
];


/* quill init */
const quill = new Quill("#editor-edit-post", {
    placeholder: "Add post here",
    modules: {
        toolbar: quillToollbar,
    },
    theme: "snow",
});

/* helpers functions */
const checkEmail = function (str) {
    const emails = str.split(",");
    let error = "";
    emails.map((email, key) => {
        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
            error += `email ${key + 1} not a valid email,`;
    });

    return error;
};

const sendInvites = function (emails, btn) {
    $.ajax({
        url: route("send-invites"),
        type: "post",
        beforeSend: () => {
            btn.append("<i class='fa fa-spin fa-spinner'></i>");
            btn.prop("disabled", true);
        },
        data: {
            _token: csrf,
            emails: emails,
            link: $("#copy-url-input").val(),
        },
        success: (res) => {
            btn.find(".fa-spin").remove();
            btn.prop("disabled", false);
            let modal = bootstrap.Modal.getInstance($("#exampleModal1"));
            modal.hide();
            alertify.success("invites sent successfully");
        },
        error: (err) => {
            btn.find(".fa-spin").remove();
            btn.prop("disabled", false);
            alertify.error("Something went wrong. Please try again!");
        },
    });
};

const isNumeric = (str) => !isNaN(str);

/* end of helpers */

$(document).ready(function () {
    let emails = "";

    $(this).on("click", "#logout-link", () => $("#logout-form").submit());

    /*  invites functionality */

    $(this).on("click", "#invite-btn", function () {
        let btn = $(this);

        $.ajax({
            url: route("invites"),
            beforeSend: () => {
                btn.append("<i class='fa fa-spin fa-spinner'></i>");
                btn.prop("disabled", true);
            },
            success: (res) => {
                elm = $("#exampleModal1");
                btn.find(".fa-spin").remove();
                btn.prop("disabled", false);
                $("#model-body").html(res);
                modal = new bootstrap.Modal(elm, {
                    backdrop: "static",
                });
                modal.show();

                elm.on("hide.bs.modal", (e) => (emails = ""));
            },
            error: (err) => {
                btn.find(".fa-spin").remove();
                btn.prop("disabled", false);
                alertify.error("Something went wrong. Please try again!");
            },
        });
    });

    /*  end invites functionality */

    /*  copy link functionality */

    $(this).on("click", "#copy-url-btn", () => {
        $("#copy-url-input").select();
        let command = document.execCommand("copy");
        command
            ? alertify.success("link copied")
            : alertify.error("something went wrong");
    });

    /* end copy link functionality */

    /* check blank email text */
    $(this).on("keyup", "#floatingTextarea", function () {
        if ($(this).val() != "") $("#btn-invites").attr("disabled", false);
        else $("#btn-invites").attr("disabled", true);
    });
    /* check blank email text */

    /*  copy link functionality */
    $(this).on("click", "#btn-invites", function () {
        let btn = $(this);
        $("#email-errors").empty();
        emails = $("#floatingTextarea").val();

        if (checkEmail(emails).length > 0)
            $("#email-errors").text(checkEmail(emails));
        else sendInvites(emails, btn);
    });

    /*  end of copy link functionality */

    /* reactions functionality */

    $(this).on("click", ".reaction", function () {
        let btn = $(this);
        let id = $(this).data("id");
        let reaction = $(this).data("reaction");

        let reactionBtn = $(".post-list").find(
            `[data-bs-target="#collapseExample-${id}"]`
        );
        console.log(reactionBtn);
        $.ajax({
            url: route("post-react"),
            type: "post",
            data: {
                post_id: id,
                reaction: reaction,
                _token: csrf,
            },
            success: (res) => {
                if (res.success) {
                    reactionBtn.html(
                        `<i class="${reaction}"></i> React ${res.count}`
                    );
                    bootstrap.Collapse.getInstance(
                        $(`#collapseExample-${id}`)
                    ).hide();
                }
            },
        });
    });
    /* end of reactions functionality */

    /* search functionality */
    $(this).on("keyup", "#search", function () {
        if ($("#search").val().length > 2)
            $.ajax({
                url: route("search", { search: $("#search").val() }),
                beforeSend: () =>
                    $("#search-loading").html(
                        "<i class='fa fa-spin fa-spinner'></i"
                    ),
                success: (res) => {
                    $("#search-loading").html(`<i class="fas fa-search"></i>`);
                    $(".search-result").html(searchResult(res));
                },
                error: (err) => {
                    $("#search-loading").html(`<i class="fas fa-search"></i>`);
                    alertify.error("Something went wrong, please try again!");
                },
            });
    });
    /* end of search functionality */

    /* delete post functionality */
    $(this).on("click", ".delete-post", function () {
        alertify.confirm(
            "Confirm",
            "Are you sure ?",
            () => postdelete($(this), $(this).data("id")),
            () => { }
        );
    });

    /*post img preview */
    $(this).on('change', '#post-edit-file-input', e => {

        const reader = new FileReader();
        reader.onload = () => $('#img-preview-edit').attr('src', reader.result).removeClass('d-none');
        reader.readAsDataURL(e.target.files[0]);

    });


    /* edit delete */

    $(this).on("click", ".edit-post", function () {
        let btn = $(this);
        let id = btn.data("id");
        const modal = new bootstrap.Modal($("#edit-post-model"));
        $.ajax({
            url: route("posts.edit", { post: id }),
            success: (res) => {
                $("#edit-post").find(`input[name="title"]`).val(res.title);
                $('#edit-post').find('#post-id').val(res.id);
                /* set content in quill editor */
                let delta = quill.clipboard.convert(res.discription);
                quill.setContents(delta, "silent");

                if (typeof res.file == "string") {
                    $("#edit-post")
                        .find("#img-preview-edit")
                        .removeClass("d-none")
                        .attr("src", res.file_path);
                }

                modal.show();

                $("#edit-post-model").on("hide.bs.modal", (e) => {
                    $("#edit-post").find("#img-preview-edit").addClass("d-none")

                });
            },
        });

    });

    /* disable button if title is blanked */
    $(this).on("keyup", '#edit-post-titile', function () {
        let val = $(this).val();
        let btn = $('#edit-post-btn');
        val.length > 0 ? btn.removeClass('disabled') : btn.addClass('disabled');
    });
    /* edit post */

    $(this).on('click', '#edit-post-btn', function () {

        let btn = $(this);
        let id = $('#post-id').val();
        const data = new FormData(document.querySelector('#edit-post'));
        data.append('discription', quill.root.innerHTML);
        data.append('_token', csrf)
        data.append('_method', 'put')

        $.ajax({
            url: route('posts.update', { post: id }),
            type: 'post',
            data: data,
            async: true,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            beforeSend: () => {
                btn.append("<i class='fa fa-spin fa-spinner'></i>").prop("disabled", true);
            },
            success: res => {

                $(`#post-${id}`).replaceWith(res);
                alertify.success("Post updated successfully")
                let modal = bootstrap.Modal.getInstance($("#edit-post-model"));
                modal.hide();
                btn.prop("disabled", false).find('.fa-spin').remove();
            },
            error: err => {
                btn.prop("disabled", false).find('.fa-spin').remove();
                alertify.error('Something went wrong,please try again!')
            }


        })
    })
});

/* display dynamic result */
const searchResult = function (res) {
    let html = "";
    if (res.length == 0) {
        html += `<div class="text-center">`;
        html += `<h4>No Result</h4>`;
        html += `<p class="text-muted">You may want to try searching for something else.</p>`;
        html += `<div>`;
    } else {
        for (const key in res) {
            let test = "";
            if (key == "Post") {
                html += `<div class="result-item-wrapper mb-3">`;
                html += `<p class="text-muted">Post</p>`;
                res.Post.map((post) => {
                    html += `<a href=${route("posts.show", {
                        post: post.id,
                    })} class="result-item-link mb-4">`;
                    html += post.title;
                    html += `</a>`;
                });
                html += `</div>`;
            } else if (key == "User") {
                html += `<div class="result-item-wrapper mb-3">`;
                html += `<p class="text-muted">User</p>`;
                res.User.map((user) => (html += userResult(user)));
                html += `</div>`;
            } else {
                html += `<div class="result-item-wrapper mb-3">`;
                html += `<p class="text-muted">Space</p>`;
                res.Space.map((space) => {
                    html += `<a href=${route("show-spaces", {
                        id: space.id,
                    })} class="result-item-link mb-4">`;
                    html += space.name;
                    html += `</a>`;
                });
                html += `</div>`;
            }
        }
    }

    return html;
};

const userResult = function (user) {
    console.log("hello");
    let html = "";
    html += `<a href="${route('member', { id: user.id })}" class="result-item-link mb-4">`;
    html += `<div class="d-flex align-items-center result-item">`;
    html += `<img src="${user.profile_pic}" class="icon_bgColor">`;
    html += `<div class="text_rightSide pl-3">`;
    html += `<h6 class="mb-0">${user.name}</h6>`;
    html += `<small>${user.email}</small>`;
    html += `</div>`;
    html += `</div>`;
    html += `</a>`;
    return html;
};

const postdelete = function (btn, id) {
    let postCard = $(`[data-id="${id}"]`);

    $.ajax({
        url: route("posts.destroy", { post: id }),
        type: "post",
        beforeSend: () => postCard.addClass("div-disabled"),
        data: {
            _method: "delete",
            _token: csrf,
        },
        success: (res) => {
            postCard.remove();
            alertify.success("Post Deleted successfully")
        },
        error: (err) => {
            postCard.removeClass("div-disabled");
            alertify.error("Something went wrong, please try again!");
        },
    });
};

const leaveSpace = function (id) {
    $.ajax({
        url: route('leave-space', {
            id: id
        }),
        type: 'post',
        data: {
            _token: csrf,
            _method: "delete"
        },
        success: res => {
            $(`#space-${id}`).remove()
            alertify.success("You’re not a member of this  space anymore.");
        },
        error: err => alertify.error("something went wrong")
    });
}
