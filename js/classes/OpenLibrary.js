export class OpenLibrary {
    constructor () {
        console.log('New OpenLibrary Interaction created');
    }

    static async fetchBookDetails(isbn) {
        const request = await fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&jscmd=details&format=json`);
        const data = request.json();
        return data;
    }

    static async fetchSubjects(isbn) {
        const request = await fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&jscmd=data&format=json`);
        const data = request.json();
        return data;
    }

    static async fetchAuthor(olidKey) {
        const request = await fetch(`https://openlibrary.org${olidKey}.json`);
        const data = request.json();
        return data;
    }

    static async fetchBookAll(isbn) {
        const responses = await Promise.allSettled([
            fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&jscmd=details&format=json`),
            fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&jscmd=data&format=json`),
        ]);

        const results = await Promise.all(responses.map(response => response.value.json()));
        const datas = [];

        results.forEach(result => {
            datas.push(result[`ISBN:${isbn}`]);
        });

        return datas;
    }
}