export class GoogleBooks {
    #apiKey = '';

    constructor(apiKey) {
        console.log('New Google Books Interaction created');
        this.#apiKey = apiKey;
    }

    async fetchISBN(isbn) {
        const link = `https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}&key=${this.#apiKey}&maxResults=2`;
        const request = await fetch(link);
        const data = request.json();
        return data;
    }
}