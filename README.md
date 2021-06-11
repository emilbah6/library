<h1 align="center">Library Test with Laravel 8 & MySql</h1>

<h4>Steps to configure the project</h4>
<ol>
<li>Clone the repository or download the zip</li>
<li>Enter the root of the project</li>
<li>Run composer install</li>
<li>Create a database</li>
<li>Rename the .env.example to .env and set the database login credentials (DB_USERNAME, DB_PASSWORD) and the database name (DB_DATABASE)</li>
<li>Run php artisan migrate to create the necessary tables</li>
<li>Run php artisan serve</li>
</ol>

<h4>Short info about the project</h4>
<p>There are 2 pages, Index & My Books. On the index page you can search for books using the <a href="https://anapioficeandfire.com/Documentation#books">Ice And Fire API</a> and you can save the books that are returned from the API. On the My Books page you can find all saved books and you can search, edit and delete them.</p>
