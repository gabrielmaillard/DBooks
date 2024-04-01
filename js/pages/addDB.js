import { key } from "../../vars/Tools";
import { Book } from "../classes/Book";
import { GoogleBooks } from "../classes/GoogleBooks";
import { OpenLibrary } from "../classes/OpenLibrary";

const searchBar10 = document.querySelector('#isbn10');
const searchBar13 = document.querySelector('#isbn13');

async function updateChangesOpenLibrary(key, ISBN13) {
    const parent = document.querySelector('#result');
    parent.innerHTML = '';

    const book = new Book(ISBN13, key);
    const exists = await book.checkAddedToDatabase();
    if (!exists) {
        await book.useDatasW();
    } else {
        await book.useDatasD();
    }
    parent.append(book.getFullPage());

    parent.style.opacity = 1;
}

searchBar10.addEventListener('input', () => {
    if (searchBar10.value.length === 10) {
        const ISBN13 = convertISBN10to13(searchBar10.value);
        updateChangesOpenLibrary(key, ISBN13);
    }
});

searchBar13.addEventListener('input', () => {
    if (searchBar13.value.length === 1 && searchBar13.value !== "9") {
        searchBar13.value = "978" + searchBar13.value;
    }

    if (searchBar13.value.length === 13) {
        updateChangesOpenLibrary(key, searchBar13.value);
    }
});