let edit = true;
// escapeHtml from: https://stackoverflow.com/questions/24816/escaping-html-strings-with-jquery

function escapeHtml(str) {
  const entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
    "/": "&#x2F;",
    "`": "&#x60;",
    "=": "&#x3D;",
  };

  return String(str).replace(/[&<>"'`=]/g, (s) => entityMap[s]);
}

// 主留言
function getComment(username, id, time, text) {
  const comment = `
    <div class='col-md-9'>
      <div class='comment__block card mb-4'>
        <div class='card-body'>
          <div class='comment__block--info mb-1 d-flex justify-content-between align-items-center'>
            <div class='d-flex align-items-center'>
              <span class='mb-0 card-title comment__username'>${escapeHtml(
                username
              )}</span>
            </div>
            <div class='dropdown dropleft'>
              <button class='btn btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
              <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <div class='comment__edit dropdown-item'>編輯</div>
                <div class='comment__delete dropdown-item' data-id='${escapeHtml(
                  id
                )}' data-type='comment'>刪除</div>
              </div>
            </div>
          </div>
          <div class='comment__info--time mb-2'>${escapeHtml(time)}</div>
          <p class='comment__text card-text' data-id='${escapeHtml(
            id
          )}' parent-id='0'>${escapeHtml(text)}</p>
          <div class='like__area mb-2'>
            <svg width='1.3em' height='1.3em' viewBox='0 0 16 16' class='bi bi-heart like' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
              <path fill-rule='evenodd' d='M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
            </svg>
            <svg width='1.3em' height='1.3em' viewBox='0 0 16 16' class='bi bi-heart-fill liked hidden' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
              <path fill-rule='evenodd' class='liked__heart' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>
            </svg>
          </div>
          <div class='subcomments'></div>
          <form class='subcomment__form d-flex flex-column mt-2' action ='./handle_add_comment.php' method='POST' >
            <textarea class='subcomment__input' name='text' placeholder='輸入回覆 . . .' required></textarea>
            <input type='hidden' name='parent_id' value='${escapeHtml(id)}' />
            <input type='button' class='button add__comment mt-2' value='我要回覆' />
          </form>
        </div>
      </div>
    </div>
  `;
  return comment.replace(/\r\n|\n/g, "");
}

// 子留言
function getSubComment(username, parentUsername, parentId, id, time, text) {
  const author =
    username === parentUsername ? '<span class="origin">樓主</span>' : "";

  const subComment = `
    <div class='subcomment__block'>
      <div class='subcomment__block--info mb-1 d-flex justify-content-between align-items-center'>
        <div class='d-flex align-items-center'>
          <span class='mb-0 card-title subcomment__username'>${escapeHtml(
            username
          )}</span>
          ${author}
        </div>
        <div class='dropdown dropleft'>
          <button class='btn btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
          <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
            <div class='comment__edit dropdown-item'>編輯</div>
            <div class='comment__delete dropdown-item' data-id='${escapeHtml(
              id
            )}' data-type='subcomment'>刪除</div>
          </div>
        </div>
      </div>
      <div class='subcomment__info--time mb-2'>${escapeHtml(time)}</div>
      <p class='subcomment__text' data-id='${escapeHtml(
        id
      )}' parent-id='${escapeHtml(parentId)}'>${escapeHtml(text)}</p>
      <div class='like__area'>
        <svg width='1.3em' height='1.3em' viewBox='0 0 16 16' class='bi bi-heart like' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
          <path fill-rule='evenodd' d='M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
        </svg>
        <svg width='1.3em' height='1.3em' viewBox='0 0 16 16' class='bi bi-heart-fill liked hidden' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
          <path fill-rule='evenodd' class='liked__heart' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>
        </svg>
      </div>
    </div>
  `;
  return subComment.replace(/\r\n|\n/g, "");
}

// 編輯留言表格
function getCommentForm(id, text) {
  const comment = `
    <form class='comment__update--form' action='./handle_update.php' method='POST'>
      <textarea class='comment__update--text' name='update__text'>${escapeHtml(
        text
      )}</textarea>
      <input type='hidden'name='id' value='${escapeHtml(id)}' />
      <div class="mt-2">
        <input type='button' class='button comment__update--confirm' value='編輯留言' />
        <span class='comment__update--cancel' >取消編輯</span>
      </div>
    </form>
  `;
  return comment.replace(/\r\n|\n/g, "");
}

// 編輯留言
function getNewText(parentId, id, text) {
  if (parentId === "0") {
    return `
      <p class='comment__text card-text' data-id='${escapeHtml(
        id
      )}'>${escapeHtml(text)}</p>
    `;
  }
  return `
    <p class='subcomment__text' data-id='${escapeHtml(id)}'>${escapeHtml(
    text
  )}</p>
  `;
}

// 編輯暱稱或帳號
function getNewData(name, data) {
  if (name === "nickname") {
    const newNickname = `
      <div class='my__profile--nickname'>暱稱：
        <span name='nickname' class='my__profile--update--nickname'>${escapeHtml(
          data
        )}
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="ml-1 bi bi-pencil-square nickname edit__img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <title>編輯暱稱</title>
            <path class="nickname" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path class="nickname" fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
          </svg>
        </span>
      </div>
    `;
    return newNickname.replace(/\r\n|\n/g, "");
  }
  const newUsername = `
    <div class='my__profile--username'>帳號：
      <span name='nickname' class='my__profile--update--username'>${escapeHtml(
        data
      )}
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="ml-1 bi bi-pencil-square username edit__img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <title>編輯帳號</title>
          <path class="nickname" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
          <path class="nickname" fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
        </svg>
      </span>
    </div>
  `;
  return newUsername.replace(/\r\n|\n/g, "");
}

$(document).ready(() => {
  $(".wrap").click((e) => {
    const target = $(e.target);

    //  編輯暱稱、帳號、密碼介面
    function showUpdateForm(className) {
      if (target.hasClass(className) && className === "password") {
        $(`.my__${className}--update`).toggleClass("hidden");
        $("input[type=password]").val("");
      } else if (target.hasClass(className)) {
        $(`.my__${className}--update`).toggleClass("hidden");
        $(`input[name=${className}]`).val("");
      }
    }

    showUpdateForm("nickname");
    showUpdateForm("username");
    showUpdateForm("password");

    // 登入或註冊
    function member(action) {
      const username = target.parent().find('input[name="username"]').val();
      const password = target.parent().find('input[name="password"]').val();

      let nickname = null;
      if (action === "register") {
        nickname = target.parent().find('input[name="nickname"]').val();
        if (nickname === "") {
          $(`.${action}__err`).remove();
          $(`<div class="${action}__err">請完整輸入欄位資訊</div>`).insertAfter(
            `.${action}__title`
          );
        }
      }

      if (username === "" || password === "") {
        $(`.${action}__err`).remove();
        $(`<div class="${action}__err">請完整輸入欄位資訊</div>`).insertAfter(
          `.${action}__title`
        );
      } else {
        $.ajax({
          method: "POST",
          url: `handle_${action}.php`,
          data: {
            nickname,
            username,
            password,
          },
        })
          .done((resp) => {
            const msg = JSON.parse(resp);
            if (msg.OK) {
              $(`.${action}__err`).remove();
              window.location.href = "index.php";
            } else {
              $(`.${action}__err`).remove();
              $(`<div class="${action}__err">${msg.message}</div>`).insertAfter(
                `.${action}__title`
              );
            }
          })
          .fail((resp) => {
            const msg = JSON.parse(resp);
            $(`.${action}__err`).remove();
            $(`<div class="${action}__err">${msg.message}</div>`).insertAfter(
              `.${action}__title`
            );
          });
      }
    }

    // 出現編輯留言介面
    if (target.hasClass("comment__edit")) {
      const cardBody = target.parent().parent().parent().parent();
      const commentText = cardBody.children().eq(2);
      const text = commentText.text();
      const commentId = commentText.data("id");

      if (edit) {
        // 留言隱藏並出現編輯表單
        commentText.toggleClass("hidden");
        const updateForm = getCommentForm(commentId, text);
        // 將編輯留言的表單插入 commentText 之後的節點
        $(updateForm).insertAfter(commentText);
        edit = false;
      } else {
        // 將編輯留言的表單拿掉並呈現留言
        cardBody.children().eq(3).remove();
        commentText.toggleClass("hidden");
        edit = true;
      }

      // 編輯留言
    } else if (target.hasClass("comment__update--confirm")) {
      const id = target.parent().prev().val();
      const newText = target.parent().prev().prev().val();
      const parentId = target.parent().parent().prev().attr("parent-id");

      if (newText === "") {
        alert("請輸入廢文！");
      } else {
        $.ajax({
          method: "POST",
          url: "handle_update.php",
          data: {
            id,
            newText,
          },
        })
          .done((resp) => {
            const msg = JSON.parse(resp);
            // 移除編輯前留言
            target.parent().parent().prev().remove();
            // 插入新留言
            const updateText = getNewText(parentId, id, newText);
            $(updateText).insertAfter(
              target.parent().parent().parent().children().eq(1)
            );
            // 隱藏編輯表單
            target.parent().parent().toggleClass("hidden");
            alert(msg.message);
            edit = true;
          })
          .fail(() => {
            alert("編輯失敗");
            edit = true;
          });
      }

      // 取消編輯留言介面
    } else if (target.hasClass("comment__update--cancel")) {
      const commentForm = target.parent().parent();
      const commentText = commentForm.prev();
      commentForm.toggleClass("hidden");
      commentText.toggleClass("hidden");
      edit = true;

      // 刪除留言
    } else if (target.hasClass("comment__delete")) {
      if (!window.confirm("確定要刪除嗎？")) return;
      const id = target.data("id");
      const type = target.data("type");

      $.ajax({
        method: "POST",
        url: "handle_delete_comment.php",
        data: {
          id,
        },
      })
        .done((resp) => {
          const msg = JSON.parse(resp);
          if (type === "subcomment") {
            target.parent().parent().parent().parent().hide();
          } else {
            target.parent().parent().parent().parent().parent().parent().hide();
          }
          alert(msg.message);
        })
        .fail(() => {
          alert("刪除失敗，請稍後再試一次");
        });

      // 新增留言
    } else if (target.hasClass("add__comment")) {
      const parentID = target.prev().val();
      const parentUsername = target
        .parent()
        .parent()
        .children()
        .eq(0)
        .find(".comment__username")
        .text();
      const text = target.parent().children().eq(0).val();

      if (text === "") {
        alert("請輸入廢文！");
      } else {
        $.ajax({
          method: "POST",
          url: "handle_add_comment.php",
          data: {
            text,
            parentID,
          },
        })
          .done((resp) => {
            const msg = JSON.parse(resp);
            alert(msg.message);
            let newComment;
            let newSubComment;
            const [username, id, time] = [msg.username, msg.id, msg.time];

            if (parentID === "0") {
              newComment = getComment(username, id, time, text);
              $(".new__comment--text").val("");
              $(".comment").prepend(newComment);
            } else {
              newSubComment = getSubComment(
                username,
                parentUsername,
                parentID,
                id,
                time,
                text
              );
              $(".subcomment__input").val("");
              target.parent().prev().append(newSubComment);
            }
          })
          .fail(() => {
            alert("新增失敗，請稍後再試一次");
          });
      }

      // 編輯暱稱或帳號
    } else if (
      target.hasClass("nickname__btn") ||
      target.hasClass("username__btn")
    ) {
      const newData = target.prev().val();
      const name = target.prev().attr("name");

      if (newData === "") {
        alert("請輸入欲修改的內容！");
      } else {
        $.ajax({
          method: "POST",
          url: "handle_update.php",
          data: {
            name,
            newData,
          },
        })
          .done((resp) => {
            const msg = JSON.parse(resp);
            const newDataBlock = getNewData(name, newData);
            alert(msg.message);
            if (msg.result === "success") {
              target.prev().val("");
              target.parent().prev().remove();
              target.parent().parent().prepend(newDataBlock);
              target.parent().toggleClass("hidden");
            }
          })
          .fail(() => {
            alert("帳號編輯失敗，請稍後再試一次");
          });
      }

      // 編輯密碼
    } else if (target.hasClass("password__btn")) {
      const currentPassword = target.parent().children().eq(0).val();
      const newPassword = target.parent().children().eq(1).val();
      const newPasswordConfirmed = target.prev().val();

      if (
        currentPassword === "" ||
        newPassword === "" ||
        newPasswordConfirmed === ""
      ) {
        alert("請確實輸入內容！");
      } else if (newPassword !== newPasswordConfirmed) {
        alert("請輸入兩次相同的新密碼");
      } else {
        $.ajax({
          method: "POST",
          url: "handle_update.php",
          data: {
            currentPassword,
            newPassword,
            newPasswordConfirmed,
          },
        })
          .done((resp) => {
            const msg = JSON.parse(resp);
            alert(msg.message);
            if (msg.result === "success") {
              target.parent().toggleClass("hidden");
              target.parent().children().eq(0).val("");
              target.parent().children().eq(1).val("");
              target.prev().val("");
            }
          })
          .fail((resp) => {
            const msg = JSON.parse(resp);
            alert(msg.message);
          });
      }

      // 登入
    } else if (target.hasClass("login__btn")) {
      member("login");

      // 註冊
    } else if (target.hasClass("register__btn")) {
      member("register");

      // 已登入並按讚
    } else if (target.hasClass("like")) {
      target.toggleClass("hidden");
      target.next().toggleClass("hidden");
      const commentId = target.parent().prev().attr("data-id");

      $.ajax({
        method: "POST",
        url: "handle_like_comment.php",
        data: {
          commentId,
        },
      })
        .done((resp) => {
          const msg = JSON.parse(resp);
          if (msg.OK) {
            const likedNumber = msg.likes;
            target.parent().children().eq(2).remove();
            target
              .parent()
              .append(
                `<span class="liked__text" data="${likedNumber}">${likedNumber} 人已按讚</span>`
              );
          }
        })
        .fail(() => {
          alert("按讚失敗，請稍後再試一次");
        });

      // 退讚
    } else if (target.hasClass("liked__heart")) {
      target.parent().toggleClass("hidden");
      target.parent().prev().toggleClass("hidden");
      const commentId = target.parent().parent().prev().attr("data-id");

      $.ajax({
        method: "POST",
        url: "handle_delete_like_comment.php",
        data: {
          commentId,
        },
      })
        .done((resp) => {
          const msg = JSON.parse(resp);
          if (msg.OK) {
            let likedNumber = target.parent().next().attr("data");
            likedNumber -= 1;
            target.parent().next().remove();
            if (likedNumber >= 1) {
              target
                .parent()
                .parent()
                .append(
                  `<span class="liked__text" data="${likedNumber}">${likedNumber} 人已按讚</span>`
                );
            }
          }
        })
        .fail(() => {
          alert("退讚失敗，請稍後再試一次");
        });
    }
  });
});
