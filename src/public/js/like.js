document.addEventListener('DOMContentLoaded', function () {
    const likeBtn = document.getElementById('like-button');

    likeBtn.addEventListener('click', function () {
        const itemId = this.dataset.itemId;

        fetch(`/like/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            // 星アイコンと数を更新
            const likeIcon = document.getElementById('like-icon');
            likeIcon.src = data.liked
                ? 'img/image copy 2.png'
                : 'img/image copy.png';

            document.getElementById('like-count').textContent = data.likeCount;
        })
        .catch(err => console.error(err));
    });
});