const showComments = document.querySelectorAll("[data-postshowid]");
const hideComments = document.querySelectorAll("[data-posthideid]");
const likeBtns = document.querySelectorAll("#likeBtn");
const commentForms = document.querySelectorAll("#comment-form");

function displayProfilePicture(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        const profilePicture = document.getElementById('profile-picture');
        profilePicture.src = e.target.result;
    };

    reader.readAsDataURL(file);
}

likeBtns.forEach((btn) => {
    btn.addEventListener("click", async () => {
        const postId = btn.dataset.postid;

        const res = await fetch(`/feed/like/${postId}`, {
            method: "POST"
        });
        const data = await res.json();

        if (data.error) {
            window.location.replace(`/user/login`);
        }

        const likeCounter = document.getElementById(postId);
        likeCounter.innerText = data.likes;

        btn.innerHTML = '<i class="fa-regular fa-heart text-3xl"></i>';
        if (data.liked === 'no') {
            btn.innerHTML = '<i class="fa-solid fa-heart text-red-500 text-3xl"></i>';
        }
    });
});

commentForms.forEach((commentForm) => {
   commentForm.addEventListener('submit', async event => {
       event.preventDefault();

       const postId = commentForm.dataset.postid;
       const formData = new FormData(commentForm);
       const comment = formData.get('comment');

       const res = await fetch(`/feed/comment/${postId}`, {
           method: "POST",
           body: new URLSearchParams(formData)
       });
       const data = await res.json();

       if (data.error) {
           window.location.replace(`/user/login`);
       }

       const comments = document.getElementById(`comments${postId}`);
       const inputEL = document.getElementById(`input${postId}`)
       const commentEL = document.createElement('p');
       const usernameEL = document.createElement('a');

       usernameEL.innerText = data.username;
       usernameEL.classList.add('font-bold');
       usernameEL.classList.add('mt-3');
       usernameEL.href = `/search/profile/${data.userid}`;

       commentEL.innerText = comment;

       comments.appendChild(usernameEL);
       comments.appendChild(commentEL);

       inputEL.value = '';
   });
});

showComments.forEach((showComment) => {
    showComment.addEventListener('click', () => {
        let postId = showComment.dataset.postshowid;

        showComment.classList.remove('flex');
        showComment.classList.add('hidden');

        let hideComment = document.querySelector(`#hide-comments${postId}`);
        hideComment.classList.remove('hidden');
        hideComment.classList.add('flex');

        let comment = document.querySelector(`#comments${postId}`);
        comment.classList.remove('hidden');
        comment.classList.add('flex');
    });
});

hideComments.forEach((hideComment) => {
    hideComment.addEventListener('click', () => {
        let postId = hideComment.dataset.posthideid;

        hideComment.classList.add('hidden');
        hideComment.classList.remove('flex');

        let showComment = document.querySelector(`#show-comments${postId}`);
        showComment.classList.add('flex');
        showComment.classList.remove('hidden');

        let comment = document.querySelector(`#comments${postId}`);
        comment.classList.add('hidden');
        comment.classList.remove('flex');
    });
});

