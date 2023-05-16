function deleteTag(tagId) {
  const apiToken = document.querySelector('meta[name="api-token"]').content;
  if (confirm('本当に削除しますか？')) {
    fetch(`/api/v1/tag/delete/${tagId}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + apiToken
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
