import { Book } from './../classes/Book';
import { key } from '../../vars/Tools';

const submitButton = document.querySelector('#submit-search');
const titleForm = document.querySelector('#search-title');
const form = document.querySelector('#search-form');
const resultsContainer = document.querySelector('#results');

form.addEventListener('submit', (event) => {
    resultsContainer.innerHTML = '';
    event.preventDefault();
    searchBook(titleForm.value)
        .then((results) => {
            results.forEach(isbn => {
                const book = new Book(isbn, key);
                book.useDatasD()
                    .then(() => {
                        resultsContainer.append(book.getCard());
                    });
            });
        });
});

async function searchBook(title) {
    const formData = new FormData();
    formData.append("string", title);

    const request = await fetch('./dbBooks/searchBook.php', {
        method: "POST",
        body: formData,
    });

    if (request.ok === true) {
        return await request.json();
    }
}