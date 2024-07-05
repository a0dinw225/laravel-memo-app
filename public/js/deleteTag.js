function getCookie(name) {
  let cookieValue = null;
  if (document.cookie && document.cookie !== '') {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].trim();

      if (cookie.substring(0, name.length + 1) === (name + '=')) {
        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
        break;
      }
    }
  }

  return cookieValue;
}

function deleteTag(tagId) {
  const apiUrl = document.querySelector('meta[name="api-url"]').content;
  const token = getCookie('laravel_memo_app_sanctum_token');

  if (confirm('本当に削除しますか？')) {
    fetch(`${apiUrl}/api/v1/tag/delete/${tagId}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
      },
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error('Error: ' + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      console.log('タグが削除されました。', data);
      window.location.href = '/';
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }
}
