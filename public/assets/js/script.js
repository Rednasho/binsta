const showComments = document.querySelector('#show-comments');
const hideComments = document.querySelector('#hide-comments');
const comments = document.querySelector('#comments');

function displayProfilePicture(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        const profilePicture = document.getElementById('profile-picture');
        profilePicture.src = e.target.result;
    };

    reader.readAsDataURL(file);
}

showComments.addEventListener('click', () => {
    showComments.classList.remove('flex');
    showComments.classList.add('hidden');
    hideComments.classList.remove('hidden');
    hideComments.classList.add('flex');
    comments.classList.remove('hidden');
    comments.classList.add('flex');
});

hideComments.addEventListener('click', () => {
    showComments.classList.add('flex');
    showComments.classList.remove('hidden');
    hideComments.classList.add('hidden');
    hideComments.classList.remove('flex');
    comments.classList.add('hidden');
    comments.classList.remove('flex');
});

