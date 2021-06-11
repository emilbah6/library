<!-- Modal -->
<div class="modal fade" id="edit-book-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-book-form">
                    {{ method_field('PATCH') }}
                    <div class="form-group">
                        <label for="book-name">Name</label>
                        <input type="text" class="form-control" id="book-name" name="name" placeholder="Book name">
                        <div class="invalid-feedback book-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="book-isbn">ISBN</label>
                        <input type="text" class="form-control" id="book-isbn" name="isbn" placeholder="ISBN">
                        <div class="invalid-feedback book-isbn"></div>
                    </div>
                    <div class="form-group">
                        <label for="book-authors">Authors</label>
                        <input type="text" class="form-control" id="book-authors" name="authors" placeholder="Authors">
                        <small>On adding authors, divide them with , (comma)</small>
                        <div class="invalid-feedback book-authors"></div>
                    </div>
                    <div class="form-group">
                        <label for="book-country">Country</label>
                        <input type="text" class="form-control" id="book-country" name="country" placeholder="Country">
                        <div class="invalid-feedback book-country"></div>
                    </div>
                    <div class="form-group">
                        <label for="book-pages">No. of pages</label>
                        <input type="number" min="0" class="form-control" id="book-pages" name="number_of_pages"
                               placeholder="No. of pages">
                        <div class="invalid-feedback book-number_of_pages"></div>
                    </div>
                    <div class="form-group">
                        <label for="book-publisher">Publisher</label>
                        <input type="text" min="0" class="form-control" id="book-publisher" name="publisher"
                               placeholder="Publisher">
                        <div class="invalid-feedback book-publisher"></div>
                    </div>
                    <div class="form-group">
                        <label for="book-release-date">Release date</label>
                        <input type="date" min="0" class="form-control" id="book-release-date" name="release_date"
                               placeholder="Release date">
                        <small>The format of the shown date is MM/DD/YYYY</small>
                        <div class="invalid-feedback book-release_date"></div>
                    </div>
                    <input type="hidden" name="id">
                    <div class="my-2 feedback"></div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
