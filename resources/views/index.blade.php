@extends("layout.app")

@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <form id="search-book-form" class="form-inline">
                    <label class="sr-only" for="book-name">Search for a book</label>
                    <input type="text" name="name_of_book" class="form-control mb-2 mr-sm-2" id="book-name"
                           placeholder="Enter book name">
                    <button type="submit" class="btn btn-success mb-2">Search</button>
                </form>
            </div>
        </div>
        <div class="row my-5">
            <div class="text-center loader col-md-10 offset-md-1" style="display: none">
                <div class="fa-3x">
                    <i class="fas fa-spinner fa-pulse"></i>
                </div>
            </div>
            <div class="col-md-10 offset-md-1 books-table-container">
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
                <div class="alert alert-danger alert-dismissible fade show my-5" role="alert"
                     style="display: none">
                    <span id="alert-text"></span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            var booksTable = $("#books-table").DataTable({
                "language": {
                    "emptyTable": "No books found"
                },
                "lengthChange": false,
                "responsive": true
            });
            $("#search-book-form").submit(function (e) {
                e.preventDefault()
                let formData = $(this).serializeArray();
                let data = {}
                formData.forEach(element => {
                    data[element.name] = element.value.trim()
                })

                $(".loader").show()
                $(".books-table-container").hide()
                $(".alert").hide();
                $.ajax({
                    "url": "{{route("external.book.search")}}",
                    "method": "GET",
                    "data": data,
                    success: function (response) {
                        $(".loader").hide()
                        $(".books-table-container").show()
                        if (response.status == 'success') {
                            booksTable.destroy();
                            booksTable = $("#books-table").DataTable({
                                "language": {
                                    "emptyTable": "No books found"
                                },
                                "responsive": true,
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
                                            return `<button class="btn btn-primary btn-sm" onclick="addBook(this)" data-book='${JSON.stringify(row)}'>Save</button>`;

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

        function addBook(element) {
            let data = $(element).data("book")
            $(element).prop("disabled", true)
            $(".alert").hide()
            $.ajax({
                "url": "{{route("add.book")}}",
                "method": "POST",
                "data": data,
                success: function (response) {
                    if (response.status === "success") {
                        $(element).removeClass("btn-primary").addClass("btn-success").text("Saved")
                    } else {
                        $(element).prop("disabled", false)
                        $(".alert").show().find("#alert-text").text("Server error occurred while saving the book.");
                    }
                },
                error: function (errorResponse) {
                    if (errorResponse.status == 422) {
                        $(".alert").show().find("#alert-text").text(`The book ${data.name} already exists in My Books`);
                    } else
                        $(".alert").show().find("#alert-text").text("Server error occurred while saving the book.");
                }
            })
        }
    </script>
@endsection
