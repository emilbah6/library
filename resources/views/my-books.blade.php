@extends("layout.app")

@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <form id="search-book-form" class="form-inline">
                    <label class="sr-only" for="book-search">Search for a book</label>
                    <input type="text" name="search_value" class="form-control mb-2 mr-sm-2 w-25" id="book-search"
                           placeholder="Search by name/country/publisher/year">
                    <button type="submit" class="btn btn-success mb-2">Search</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="table-responsive">
                    <table id="books-table" class="table table-striped">
                        <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Name</th>
                            <th>Authors</th>
                            <th>Country</th>
                            <th>No. of Pages</th>
                            <th>Publisher</th>
                            <th>Release Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include("components.edit-book-modal")
    </div>
@endsection
@section('scripts')
    <script>
        var booksTable = null;
        $(document).ready(function () {
            loadBooks();

            $("#edit-book-form").submit(function (e) {
                e.preventDefault()
                let formData = $(this).serializeArray();
                let data = {};
                formData.forEach(element => {
                    data[element.name] = element.value
                })
                let book_id = data.id;
                delete data.id
                $(".invalid-feedback").hide()

                $.ajax({
                    method: "PATCH",
                    url: "{{route("edit.book", ["id"=> "@id"])}}".replace("@id", book_id),
                    data: data,
                    success: function (response) {
                        if (response.status === "success") {
                            $(".feedback").addClass("valid-feedback").text(response.message).show();
                            loadBooks();
                            setTimeout(function () {
                                $(".feedback").removeClass("valid-feedback").text("").hide()
                                $("#edit-book-modal").modal("hide")
                            }, 2000)
                        } else {
                            $(".feedback").addClass("invalid-feedback").text(response.message).show();
                            setTimeout(function () {
                                $(".feedback").removeClass("invalid-feedback").text("").hide()
                            }, 2000)
                        }
                    },
                    error: function (errorResponse) {
                        if (errorResponse.status == 422) {
                            let errors = errorResponse.responseJSON.errors;
                            for (let key in errors) {
                                $(`.book-${key}`).text(errors[key][0]).show()
                            }
                        }
                        console.log(errorResponse)
                    }
                })
            })

            $("#search-book-form").submit(function (e) {
                e.preventDefault()
                let formData = $(this).serializeArray();
                let data = {}
                formData.forEach(element => {
                    data[element.name] = element.value.trim()
                })

                $(".loader").show()
                $(".books-table-container").hide()

                $.ajax({
                    "url": "{{route("get.books")}}",
                    "method": "GET",
                    "data": data,
                    success: function (response) {
                        $(".loader").hide()
                        $(".books-table-container").show()
                        if (response.status == 'success') {
                            if (booksTable != null)
                                booksTable.destroy();

                            booksTable = $("#books-table").DataTable({
                                "language": {
                                    "emptyTable": "No books found"
                                },
                                "responsive": true,
                                "searching": false,
                                data: response.data,
                                columns: [
                                    {title: "ISBN", data: "isbn"},
                                    {title: "Name", data: "name"},
                                    {title: "Authors", data: "authors"},
                                    {title: "Country", data: "country"},
                                    {title: "No. of Pages", data: "number_of_pages"},
                                    {title: "Publisher", data: "publisher"},
                                    {title: "Release Date", data: "release_date"},
                                    {
                                        title: "Action", render: function (data, type, row, meta) {
                                            return `<button class="btn btn-primary btn-sm" onclick="openBookForEditing(${row.id})">Edit</button> <button class="btn btn-danger btn-sm" onclick="deleteBook('${row.id}','${row.name}')">Delete</button>`;

                                        }
                                    }
                                ]
                            });
                        }
                    },
                    error: function (errorResponse) {
                        console.log(errorResponse)
                    }
                })
            });

        })

        function loadBooks() {
            $.ajax({
                "url": "{{route("get.books")}}",
                "method": "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        if (booksTable != null)
                            booksTable.destroy();

                        booksTable = $("#books-table").DataTable({
                            "language": {
                                "emptyTable": "No books found"
                            },
                            "responsive": true,
                            "searching": false,
                            "lengthChange": false,
                            data: response.data,
                            columns: [
                                {title: "ISBN", data: "isbn"},
                                {title: "Name", data: "name"},
                                {title: "Authors", data: "authors"},
                                {title: "Country", data: "country"},
                                {title: "No. of Pages", data: "number_of_pages"},
                                {title: "Publisher", data: "publisher"},
                                {title: "Release Date", data: "release_date"},
                                {
                                    title: "Action", render: function (data, type, row, meta) {
                                        return `<button class="btn btn-primary btn-sm" onclick="openBookForEditing(${row.id})">Edit</button> <button class="btn btn-danger btn-sm" onclick="deleteBook('${row.id}','${row.name}')">Delete</button>`;

                                    }
                                }
                            ]
                        });
                    }
                },
                error: function (errorResponse) {
                    console.log(errorResponse)
                }
            })
        }

        function openBookForEditing(book_id) {
            $.ajax({
                "url": "{{route("view.specific.book", ["id" => "@id"])}}".replace("@id", book_id),
                "method": "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        $("#edit-book-modal").modal("show")
                        let editBookForm = $("#edit-book-form")
                        for (let key in response.data) {
                            editBookForm.find(`input[name="${key}"]`).val(response.data[key])
                        }
                    }
                },
                error: function (errorResponse) {
                    console.log(errorResponse)
                }
            })
        }

        function deleteBook(book_id, book_name) {
            if (confirm(`Are you sure you want to delete the book ${book_name}`)) {
                $.ajax({
                    "url": "{{route("delete.book", ["id" => "@id"])}}".replace("@id", book_id),
                    "method": "DELETE",
                    "data": {
                        name: book_name
                    },
                    success: function (response) {
                        alert(response.message);
                        if (response.status == 'success') {
                            loadBooks()
                        }
                    },
                    error: function (errorResponse) {
                        console.log(errorResponse)
                    }
                })

            }
        }
    </script>
@endsection
