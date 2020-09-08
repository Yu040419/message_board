let edit = true;

// escapeHtml from: https://stackoverflow.com/questions/24816/escaping-html-strings-with-jquery

function escapeHtml(str) {
  const entityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
    '/': '&#x2F;',
    '`': '&#x60;',
    '=': '&#x3D;',
  };

  return String(str).replace(/[&<>"'`=]/g, s => entityMap[s]);
}

// 主留言
function getComment(nickname, username, id, time, text) {
  const comment = `
    <div class='comment__block'>
      <div class='comment__block--info'>
        <div class='comment__info--name'>${escapeHtml(nickname)}
          <span class='comment__username' data-name=${escapeHtml(username)}> (@${escapeHtml(username)} )</span>
          <img src='./img/edit.png' title='編輯廢文' class='img comment__edit' />
          <img src='./img/delete.png' title='刪除廢文' class='img comment__delete' data-id='${escape(id)}'/>
        </div>
        <div class='comment__info--time'>${escapeHtml(time)}</div>
      </div>
      <div class='comment__text' data-id='${escapeHtml(id)}'>${escapeHtml(text)}</div>
      <div class='subcomments'></div>
      <form class='subcomment__form' action ='./handle_add_comment.php' method='POST' >
        <textarea class='subcomment__input' name='text' placeholder='輸入回覆 . . .' required></textarea>
        <input type='hidden' name='parent_id' value='${escapeHtml(id)}' />
        <input type='button' class='btn add__comment' value='我要回覆' />
      </form>
    </div>
  `;
  return comment.replace(/\r\n|\n/g, '');
}

// 子留言
function getSubComment(nickname, username, parentUsername, id, time, text) {
  const author = (username === parentUsername) ? '<span class="origin" >樓主</span>' : '';

  const subComment = `
    <div class='subcomment__block'>
      <div class='subcomment__block--info'>
        <div class='subcomment__info--name'>${escapeHtml(nickname)}
          <span class='comment__username' data-name=${escapeHtml(username)}> (@${escapeHtml(username)} )</span>
          <img src='./img/edit.png' title='編輯廢文' class='img comment__edit' />
          <img src='./img/delete.png' title='刪除廢文' class='img comment__delete' data-id='${escapeHtml(id)}'/>
          ${author}
        </div>
        <div class='subcomment__info--time'>${escapeHtml(time)}</div>
      </div>
      <div class='subcomment__text' data-id='${escapeHtml(id)}'>${escapeHtml(text)}</div>
    </div>

  `;
  return subComment.replace(/\r\n|\n/g, '');
}

// 編輯留言表格
function getCommentForm(id, text) {
  const comment = `
    <form class='comment__update--form' action='./handle_update.php' method='POST'>
      <textarea class='comment__update--text' name='update__text'>${escapeHtml(text)}</textarea>
      <input type='hidden'name='id' value='${escapeHtml(id)}' />
      <input type='button' class='btn comment__update--confirm' value='編輯留言' />
      <span class='comment__update--cancel' >取消編輯</span>
    </form>
  `;
  return comment.replace(/\r\n|\n/g, '');
}

// 編輯留言
function getNewText(className, id, text) {
  if (className === 'comment__block') {
    return `
      <div class='comment__text' data-id='${escapeHtml(id)}'>${escapeHtml(text)}</div>
    `;
  }
  return `
    <div class='subcomment__text' data-id='${escapeHtml(id)}'>${escapeHtml(text)}</div>
  `;
}

// 編輯暱稱或帳號
function getNewData(name, data) {
  if (name === 'nickname') {
    const newNickname = `
      <div class='my__profile--nickname'>暱稱：
        <span name='nickname' class='my__profile--update--nickname'>${escapeHtml(data)}
          <img src='./img/edit.png' title='編輯暱稱' class='img nickname' />
        </span>
      </div>
    `;
    return newNickname.replace(/\r\n|\n/g, '');
  }
  const newUsername = `
    <div class='my__profile--username'>帳號：
      <span name='nickname' class='my__profile--update--username'>${escapeHtml(data)}
        <img src='./img/edit.png' title='編輯帳號' class='img username' />
      </span>
    </div>
  `;
  return newUsername.replace(/\r\n|\n/g, '');
}

$(document).ready(() => {
  $('.wrap').click((e) => {
    const target = $(e.target);

    //  編輯暱稱、帳號、密碼介面
    function showUpdateForm(className) {
      if (target.hasClass(className) && className === 'password') {
        $(`.my__${className}--update`).toggleClass('hidden');
        $('input[type=password]').val('');
      } else if (target.hasClass(className)) {
        $(`.my__${className}--update`).toggleClass('hidden');
        $(`input[name=${className}]`).val('');
      }
    }

    showUpdateForm('nickname');
    showUpdateForm('username');
    showUpdateForm('password');

    // 編輯留言介面
    if (target.hasClass('comment__edit')) {
      const commentBlock = target.parent().parent().parent();
      const commentText = commentBlock.children().eq(1);
      const text = commentText.text();
      const commentId = commentText.data('id');

      if (edit) {
        commentText.toggleClass('hidden');
        const updateForm = getCommentForm(commentId, text);
        // 將 form 插入 commentText 之後的節點
        $(updateForm).insertAfter(commentText);
        edit = false;
      } else {
        commentBlock.children().eq(2).remove();
        commentText.toggleClass('hidden');
        edit = true;
      }

      // 編輯留言
    } else if (target.hasClass('comment__update--confirm')) {
      const id = target.prev().val();
      const newText = target.prev().prev().val();
      const className = target.parent().parent().attr('class');

      if (newText === '') {
        alert('請輸入廢文！');
      } else {
        $.ajax({
          method: 'POST',
          url: 'handle_update.php',
          data: {
            id,
            newText,
          },
        }).done((resp) => {
          const msg = JSON.parse(resp);
          // 移除編輯前文字
          target.parent().prev().remove();
          const updateText = getNewText(className, id, newText);
          $(updateText).insertAfter(target.parent().parent().children().eq(0));
          // 隱藏編輯表單
          target.parent().toggleClass('hidden');
          alert(msg.message);
          edit = true;
        }).fail(() => {
          alert('編輯失敗');
          edit = true;
        });
      }

      // 取消編輯留言介面
    } else if (target.hasClass('comment__update--cancel')) {
      const commentForm = target.parent();
      const commentText = target.parent().parent().children().eq(1);
      commentForm.toggleClass('hidden');
      commentText.toggleClass('hidden');
      edit = true;

      // 刪除留言
    } else if (target.hasClass('comment__delete')) {
      if (!window.confirm('確定要刪除嗎？')) return;
      const id = target.data('id');

      $.ajax({
        method: 'POST',
        url: 'handle_delete_comment.php',
        data: {
          id,
        },
      }).done((resp) => {
        const msg = JSON.parse(resp);
        target.parent().parent().parent().hide();
        alert(msg.message);
      }).fail(() => {
        alert('刪除失敗，請稍後再試一次');
      });

      // 新增留言
    } else if (target.hasClass('add__comment')) {
      const parentID = target.prev().val();
      const parentUsername = target.parent().parent().children().eq(0)
        .find('.comment__username')
        .data('name');
      const text = target.parent().children().eq(0).val();

      if (text === '') {
        alert('請輸入廢文！');
      } else {
        $.ajax({
          method: 'POST',
          url: 'handle_add_comment.php',
          data: {
            text,
            parentID,
          },
        }).done((resp) => {
          const msg = JSON.parse(resp);
          alert(msg.message);
          let newComment;
          let newSubComment;
          const [nickname, username, id, time] = [msg.nickname, msg.username, msg.id, msg.time];

          if (parentID === '0') {
            newComment = getComment(nickname, username, id, time, text);
            $('.new__comment--text').val('');
            $('.comment').prepend(newComment);
          } else {
            newSubComment = getSubComment(nickname, username, parentUsername, id, time, text);
            $('.subcomment__input').val('');
            target.parent().prev().append(newSubComment);
          }
        }).fail(() => {
          alert('新增失敗，請稍後再試一次');
        });
      }

      // 編輯暱稱或帳號
    } else if (target.hasClass('nickname__btn') || target.hasClass('username__btn')) {
      const newData = target.prev().val();
      const name = target.prev().attr('name');

      if (newData === '') {
        alert('請輸入欲修改的內容！');
      } else {
        $.ajax({
          method: 'POST',
          url: 'handle_update.php',
          data: {
            name,
            newData,
          },
        }).done((resp) => {
          const msg = JSON.parse(resp);
          const newDataBlock = getNewData(name, newData);
          alert(msg.message);
          if (msg.result === 'success') {
            target.prev().val('');
            target.parent().prev().remove();
            target.parent().parent().prepend(newDataBlock);
            target.parent().toggleClass('hidden');
          }
        }).fail(() => {
          alert('帳號編輯失敗，請稍後再試一次');
        });
      }
    } else if (target.hasClass('password__btn')) {
      const currentPassword = target.parent().children().eq(0).val();
      const newPassword = target.parent().children().eq(1).val();
      const newPasswordConfirmed = target.prev().val();

      if (currentPassword === '' || newPassword === '' || newPasswordConfirmed === '') {
        alert('請確實輸入內容！');
      } else if (newPassword !== newPasswordConfirmed) {
        alert('請輸入兩次相同的新密碼');
      } else {
        $.ajax({
          method: 'POST',
          url: 'handle_update.php',
          data: {
            currentPassword,
            newPassword,
            newPasswordConfirmed,
          },
        }).done((resp) => {
          const msg = JSON.parse(resp);
          alert(msg.message);
          if (msg.result === 'success') {
            target.parent().toggleClass('hidden');
            target.parent().children().eq(0).val('');
            target.parent().children().eq(1).val('');
            target.prev().val('');
          }
        }).fail((resp) => {
          const msg = JSON.parse(resp);
          alert(msg.message);
        });
      }
    }
  });
});
