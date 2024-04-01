const openBtn = document.querySelector('#openModal');
const closeBtn = document.querySelector('#closeModal');
const modal = document.querySelector('#modal');

openBtn.addEventListener('click', () => {
    modal.classList.add("open");
});

closeBtn.addEventListener('click', () => {
    modal.classList.remove("open");
});