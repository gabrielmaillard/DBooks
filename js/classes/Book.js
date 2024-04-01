import { generateSentenceWithElements, Languages } from "../../vars/Tools";
import { GoogleBooks } from "./GoogleBooks";
import { OpenLibrary } from "./OpenLibrary";

export class Book {
    #apiKey = "";

    #isbn = 0;
    #coverURI = "";
    #title = "";
    #authorsKeysNames = [];
    #fullDescription = "";
    #languageCode = "";
    #publishedDate = "";
    #publisher = "";
    #pageCount = 0;
    #subjects = [];

    /**
     * Constructor
     * @param {Number} isbn ISBN13 Unique Identifier
     * @param {String} apiKey GoogleBooksAPI Key
     */
    constructor(isbn, apiKey) {
        this.#isbn = isbn;
        this.#apiKey = apiKey; // GoogleBooksAPI Key
    }

    getCard() {
        const card = document.querySelector('#template-book-card').content.cloneNode(true);

        card.querySelector('.book-card').addEventListener('click', () => {
            window.location.href = "index?isbn=" + this.#isbn;
        });

        //Getting the elements from the clone of the template
        const cover = card.querySelector('.cover');
        const title = card.querySelector('.title');
        const authors = card.querySelector('.authors');
        const description = card.querySelector('.description');

        description.innerText = this.#fullDescription.substring(0, 270)+"...";
        cover.setAttribute("src", this.#coverURI);
        title.innerText = this.#title;
        authors.innerText = generateSentenceWithElements(this.#authorsKeysNames.map(keyName => keyName['authorName']), "écrit par ", ", ", " et ");

        return card;
    }

    /**
     * Get the HTML Structure of the full page book
     * @returns {HTMLElement} Structure of the full page book
     */
    getFullPage() {
        const fullPage = document.querySelector('#template-book-full-page').content.cloneNode(true);

        // Getting the elements from the clone of the template
        const cover = fullPage.querySelector('.cover-image');
        const title = fullPage.querySelector('#title');
        const fullDescription = fullPage.querySelector('#description');
        const publishedDate = fullPage.querySelector('#published-date');
        const pageCount = fullPage.querySelector('#page-count');
        const publisher = fullPage.querySelector('#publisher');
        const isbn = fullPage.querySelector('#isbn');
        const language = fullPage.querySelector('#language');
        const subjects = fullPage.querySelector('#subjects');
        const authors = fullPage.querySelector('#authors');

        // Setting up the values
        cover.setAttribute('src', this.#coverURI);
        title.innerText = this.#title;
        if (this.#fullDescription === undefined) {
            fullDescription.innerHTML = '<textarea class="big" type="text" placeholder="La description est indisponible..." id="enter-description"></textarea>'; // If no description, the user will enter it
            fullDescription.querySelector('#enter-description').addEventListener('input', (e) => {
                this.#fullDescription = e.target.value;
            });
        } else {
            fullDescription.innerText = this.#fullDescription;
        }
        publishedDate.innerText = this.#publishedDate;
        pageCount.innerText = this.#pageCount;
        publisher.innerText = this.#publisher;
        isbn.innerText = this.#isbn;
        language.innerText = Languages[(this.#languageCode)];
        if (this.#subjects !== undefined) {
            subjects.innerText = generateSentenceWithElements(this.#subjects, "", ", ", " et ");
        }
        else {
            subjects.innerText = "indisponible(s)";
        }
        authors.innerText = generateSentenceWithElements(this.#authorsKeysNames.map(keyName => keyName['authorName']), "écrit par ", ", ", " et ");

        // Managing add to the Base
        const addToDB = fullPage.querySelector('#add-to-db');
        this.checkAddedToDatabase()
            .then((isAdded) => { // isAdded is a string !
                if (isAdded) {
                    this.#transformIntoRemoveButton(addToDB);
                } else {
                    this.#transformIntoAddButton(addToDB);
                }
            });

        return fullPage;
    }
    
    /**
     * Transform any button into an add button
     * @param {HTMLButtonElement} prevButton The button which will we transformed into an add button
     */
    #transformIntoAddButton(prevButton) {
        const button = prevButton.cloneNode(true);

        button.innerText = "Ajouter à la Base ";
        button.setAttribute("id", "add-to-database");
        button.classList.remove("primary-red");
        button.classList.add("primary");

        button.addEventListener('click', () => {
            // Create the loader here
            this.#addToDatabase()
                .then(() => {
                    if (button.parentNode.parentNode.parentNode.querySelector('#enter-description')) {
                        button.parentNode.parentNode.parentNode.querySelector('#enter-description').disabled = true;
                    }
                    this.#transformIntoRemoveButton(button);
                });
        });
        
        const image = document.createElement("img");
        image.setAttribute("src", "emojis/📄.svg");
        image.setAttribute("alt", "File emoji");
        image.classList.add("emoji");

        button.append(image);
        prevButton.parentNode.replaceChild(button, prevButton);
    }

    /**
     * Transform any button into a remove button
     * @param {HTMLButtonElement} prevButton The button which will be transformed into a remove button
     */
    #transformIntoRemoveButton(prevButton) {
        const button = prevButton.cloneNode(true);

        button.innerText = "Supprimer de la Base ";
        button.setAttribute("id", "remove-from-database");
        button.classList.remove("primary");
        button.classList.add("primary-red");

        button.addEventListener('click', () => {
            this.#removeFromDatabase()
                .then(() => {
                    this.#transformIntoAddButton(button);
                });
        });

        const image = document.createElement("img");
        image.setAttribute("src", "emojis/🗑.svg");
        image.setAttribute("alt", "Trash can emoji");
        image.classList.add("emoji");

        button.append(image);
        prevButton.parentNode.replaceChild(button, prevButton);
    }
    
    /**
     * Get the datas about a book from the Web
     * @param {Number} isbn ISBN13 : Unique identifier of a book which can be got from its bar code
     * @returns {Array} Array of datas got from GoogleBooks and OpenLibrary
     */
    async #getDatasW (isbn) {
        const responses = await Promise.allSettled([
            fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&jscmd=details&format=json`),
            fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&jscmd=data&format=json`),
            fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}&key=${this.#apiKey}&maxResults=2`),
        ]);
        
        return await Promise.all(responses.map(response => response.value.json()));
    }

    /**
     * Use the datas gotten from the Web (#getDatasW) for the properties
     */
    async useDatasW() {
        const results = await this.#getDatasW(this.#isbn);
        console.log(results);
        
        // Extract datas into variables
        const gbResults = results["2"]["items"]['0']["volumeInfo"]; // GoogleBooksAPI Results
        const olResults = results[0][`ISBN:${this.#isbn}`]["details"];
        olResults["subjects"] = results[1][`ISBN:${this.#isbn}`]["subjects"]; // Modify the OpenLibrary results to add it the subjects
        olResults["authors"] = results[1][`ISBN:${this.#isbn}`]["authors"];
        olResults["covers"] = results[1][`ISBN:${this.#isbn}`]["cover"];

        // Get the cover URI : GoogleBooks / OpenLibrary
        this.#coverURI = ((olResults?.covers?.large ?? gbResults?.imageLinks?.thumbnail) ?? OpenLibrary.getCover(olResults?.covers?.[0])) ?? "https://placehold.co/260x398?text=Couverture+\\n+indisponible";
        if (this.#coverURI.slice(0, 5) !== "https") { // Make http URL become an HTTPS URL
            this.#coverURI = "https" + this.#coverURI.substring(4);
        }
        // Title : OpenLibrary / GoogleBooks
        this.#title = olResults?.title ?? gbResults?.title;
        // Full description : GoogleBooks / OpenLibrary
        this.#fullDescription = gbResults?.description ?? olResults?.description; // CHECK IF IT IS REALLY .DESCRIPTION FOR OPENLIBRARY
        // Language Code : GoogleBooks
        this.#languageCode = gbResults?.language; // CHECK FOR OPENLIBRARY
        // Published date : GoogleBooks / OpenLibrary
        this.#publishedDate = gbResults?.publishedDate ?? olResults?.publish_date;
        // Publisher : OpenLibrary / GoogleBooks
        this.#publisher = olResults?.publishers?.[0] ?? gbResults?.publisher;
        // Page count : GoogleBooks
        this.#pageCount = gbResults?.pageCount; // CHECK IF EXISTS IN OPENLIBRARY
        // Subjects : OpenLibrary
        this.#subjects = olResults?.subjects?.map(subject => subject['name']);
        // Authors Keys/Names : OpenLibrary
        this.#authorsKeysNames = olResults?.authors?.map(keyName => {
            return {
                key: keyName["url"].split("/")[keyName["url"].split("/").length - 2],
                authorName: keyName["name"]
            }
        });
    }

    /**
     * Get datas about a book from the Database
     * @param {Number} isbn ISBN13 Unique identifier 
     * @returns {Promise} Request response
     */
    async #getDatasD (isbn) {
        const formData = new FormData();
        formData.append('isbn', isbn);

        const request = await fetch('./dbBooks/getBook.php', {
            method: "POST",
            body: formData,
        });

        if (request.ok === true) {
            return await request.json();
        }
    }

    /**
     * Use datas gotten from the database for the properties
     */
    async useDatasD() {
        const results = await this.#getDatasD(this.#isbn);
        
        if (results["idImage"] === 0) {
            this.#coverURI = "https://placehold.co/260x398?text=Couverture+\\n+indisponible";
        } else {
            this.#coverURI = "images/" + results["idImage"] + ".jpeg";
        }
        this.#authorsKeysNames = results["author_names"];
        this.#publishedDate = results["publishedDate"];
        this.#title = results["title"];
        this.#publisher = results["publisherName"];
        this.#pageCount = results["pageCount"];
        this.#languageCode = results["languageCode"];
        this.#fullDescription = results["description"];
    }

    /**
     * Add book datas to the database
     * @returns {Promise} Request promise
     */
    async #addToDatabase() {
        // Here put the code to add to the database (send request to the server with php)
        if (await this.checkAddedToDatabase()) {
            return;
        }
        const formData = new FormData();
        formData.append('isbn', this.#isbn);
        formData.append('title', this.#title);
        formData.append('description', this.#fullDescription);
        formData.append('publisherName', this.#publisher);
        formData.append('idLocation', 1);
        formData.append('pageCount', this.#pageCount);
        formData.append('languageCode', this.#languageCode);
        formData.append('authorsKeysNames', JSON.stringify(this.#authorsKeysNames));
        formData.append('publishedDate', this.#publishedDate);
        formData.append('coverURI', this.#coverURI);
            
        const request = await fetch('./dbBooks/addBook.php', {
            method: 'POST',
            body: formData,
        });

        return request;
    }

    /**
     * Remove book datas from the database
     * @returns {Promise} Request response
     */
    async #removeFromDatabase() {
        const formData = new FormData();
        formData.append('isbn', this.#isbn);

        const request = await fetch('./dbBooks/removeBook.php', {
            method: "POST",
            body: formData,
        });

        if (request.ok === true) {
            return await request.json();
        }
    }
    
    /**
     * Check if the book is in the database
     * @returns {Promise} Request response
     */
    async checkAddedToDatabase() {
        const formData = new FormData();
        formData.append('isbn', this.#isbn);

        const request = await fetch('./dbBooks/checkBookExists.php', {
            method: 'POST',
            body: formData,
        });

        if (request.ok === true) {
            const response = await request.json();
            if (response === "1") {
                return true;
            }
            return false;
        }
    }
}